<?php

namespace App\Http\Controllers\Books;

use Smalot\PdfParser\Parser;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HandleFile;
use App\Traits\CrudOperationsTrait;
use App\Traits\HasPermissions;

class BookController extends Controller
{
    use HandleFile, CrudOperationsTrait, HasPermissions; 

    /*
    |-------------------------------------------------------------------------- 
    | Constructor Method
    |-------------------------------------------------------------------------- 
    */
    public function __construct()
    {
        // $this->authorizePermissions('book');
    }

    /*
    |-------------------------------------------------------------------------- 
    | Validate Request Data Function
    |-------------------------------------------------------------------------- 
    */
    // private function validator($request)
    // {
    //     $rules = [
    //         'title' => 'required|string|max:255',
    //         'author' => 'required|string|max:255',
    //         'publish' => 'required|string|max:255',
    //         'file'=> 'required|mimes:pdf',
    //         'cover_image' => 'required', 
    //         'size' => 'required|string|max:50',
    //         'number_pages' => 'required|integer|min:1',
    //         'published_at' => 'required|date',
    //         'is_downloadable' => 'required|boolean',
    //         'category_id' => 'required|exists:categories,id',
    //     ];
    //     return $this->validateRequestData($request, $rules);
    // }

    /*
    |-------------------------------------------------------------------------- 
    | Display a listing of books.
    |-------------------------------------------------------------------------- 
    */
    public function index(Request $request)
    {
        try {
            $query = Book::latest('id');

            if ($request->has('search') && !empty($request->search)) {
                $query->where(function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('author', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->has('category_id') && !empty($request->category_id)) {
                $query->where('category_id', $request->category_id);
            }

            $books = $query->paginate(10);
            return BookResource::collection($books);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch books. Please try again later.'], 500);
        }
    }

    /*
    |-------------------------------------------------------------------------- 
    | Handle File Upload and Size Calculation
    |-------------------------------------------------------------------------- 
    */
    private function handleFile($file, $title)
    {
        $uploadedFile = $this->UploadFiles($file, $title, 'file');
        $size = round($file->getSize() / (1024 * 1024), 2) ;

        $parser = new Parser();
        try {
            $pdf = $parser->parseFile($file->getPathname());
            $numberPages = count($pdf->getPages());
        } catch (\Exception $e) {
            throw new \Exception('Failed to parse PDF: ' . $e->getMessage());
        }

        return ['uploaded_file' => $uploadedFile, 'size' => $size, 'number_pages' => $numberPages];
    }

    /*
    |-------------------------------------------------------------------------- 
    | Store a newly created book in the database.
    |-------------------------------------------------------------------------- 
    */
    public function store(StoreBookRequest $request)
    {
        // $validationResult = $this->validator($request);
        // if ($validationResult !== null) {
        //     return $validationResult;
        // }

        $input = $request->all();
        $input['user_id'] = Auth::id();

        if ($request->hasFile('cover_image')) {
            $input['cover_image'] = $this->UploadFiles($request->file('cover_image'), $request->title, 'image');
        }

        if ($request->hasFile('file')) {
            try {
                $fileData = $this->handleFile($request->file('file'), $request->title);
                $input['file'] = $fileData['uploaded_file'];
                $input['size'] = $fileData['size'];
                $input['number_pages'] = $fileData['number_pages'];
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        try {
            $book = $this->createRecord(new Book, $input);
            return new BookResource($book);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to add book. Please try again later.'], 500);
        }
    }

    /*
    |-------------------------------------------------------------------------- 
    | Update the specified book in the database.
    |-------------------------------------------------------------------------- 
    */
    public function update(UpdateBookRequest $request, Book $book)
    {
        // $validationResult = $this->validator($request);
        // if ($validationResult !== null) {
        //     return $validationResult;
        // }

        $input = $request->all();

        if ($request->hasFile('cover_image')) {
            $input['cover_image'] = $this->updateFile($request, 'cover_image', $book->cover_image, null, 'image');
        }

        if ($request->hasFile('file')) {
            try {
                $fileData = $this->handleFile($request->file('file'), $request->title);
                $input['file'] = $fileData['uploaded_file'];
                $input['size'] = $fileData['size'];
                $input['number_pages'] = $fileData['number_pages'];
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        try {
            $this->updateRecord(new Book, $book->id, $input);
            return new BookResource($book);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to update book. Please try again later.'], 500);
        }
    }

    /*
    |-------------------------------------------------------------------------- 
    | Remove the specified book from the database.
    |-------------------------------------------------------------------------- 
    */
    public function destroy(Book $book)
    {
        try {
            $this->deleteRecord(new Book, $book->id, ['cover_image', 'file']); 
            return response()->json(['message' => 'Book deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to delete book. Please try again later.'], 500);
        }
    }
}
