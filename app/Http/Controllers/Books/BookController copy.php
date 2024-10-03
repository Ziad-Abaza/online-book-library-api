<?php

namespace App\Http\Controllers\Books;

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
                $this->authorizePermissions('book');
    }

    /*
    |-------------------------------------------------------------------------- 
    | Validate Request Data Function
    |-------------------------------------------------------------------------- 
    */
    private function validator($request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publish' => 'required|string|max:255',
            'file'=> 'required',
            'cover_image' => 'required', 
            'size' => 'required|string|max:50',
            'number_pages' => 'required|integer|min:1',
            'published_at' => 'required|date',
            'is_downloadable' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
        ];
        return $this->validateRequestData($request, $rules);
    }

    /*
    |-------------------------------------------------------------------------- 
    | Display a listing of books.
    |-------------------------------------------------------------------------- 
    */
    public function index()
    {
        try {
            $books = Book::latest('id')->paginate(10); // Adjust pagination as needed
            return BookResource::collection($books); 
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch books. Please try again later.'], 500);
        }
    }

    /*
    |-------------------------------------------------------------------------- 
    | Store a newly created book in the database.
    |-------------------------------------------------------------------------- 
    */
    public function store(StoreBookRequest $request)
    {
        $validationResult = $this->validator($request);
        if ($validationResult !== null) {
            return $validationResult;
        }

        $input = $request->all();
        $input['user_id'] = Auth::id();

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $input['cover_image'] = $this->UploadFiles($request->file('cover_image'), $request->title, 'image');
        }

        // Handle book file upload
        if ($request->hasFile('file')) {
            $input['file'] = $this->UploadFiles($request->file('file'), $request->title, 'file');
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
        $validationResult = $this->validator($request);
        if ($validationResult !== null) {
            return $validationResult;
        }

        $input = $request->all();

        // Handle cover image update
        if ($request->hasFile('cover_image')) {
            $input['cover_image'] = $this->updateFile($request, 'cover_image', $book->cover_image, null, 'image');
        }

        // Handle book file update
        if ($request->hasFile('file')) {
            $input['file'] = $this->updateFile($request, 'file', $book->file, null, 'file');
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
            $this->deleteRecord(new Book, $book->id, ['cover_image', 'file']); // Delete both cover image and file
            return response()->json(['message' => 'Book deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to delete book. Please try again later.'], 500);
        }
    }
}