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
use App\Traits\HasPermissions;

class BookController extends Controller
{
  use HandleFile , HasPermissions;

   /**
    *   
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->authorizePermissions('book');
    }
    public function index()
    {
      $books = Book::all();
      return response()-> json([$books]);
      }

 
}
