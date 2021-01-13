<?php

namespace App\Http\Controllers;

/* Path to Model */
use App\Models\Book;

use Illuminate\Http\Request;

class BookController extends Controller
{
    public function bookList(){
        $books = Book::all();
        $data = [
            'books' => $books
        ];
        return view('booklist', $data);
    }

    public function addBookList(Request $request){
        $book = new Book;
        $book->title = $request->book_title;
        $book->author = $request->author;
        $book->save();
        return redirect()->back();
    }
}
