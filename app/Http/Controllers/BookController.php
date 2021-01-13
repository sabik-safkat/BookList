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
        if ($request->book_id > 0){
            $book = Book::where('id', $request->book_id)->first();
            $book->title = $request->book_title;
            $book->author = $request->author;
            $book->save(); 
        } else {
            $book = new Book;
            $book->title = $request->book_title;
            $book->author = $request->author;
            $book->save(); 
        }
        return redirect()->back();
    }

    public function deleteBook(Request $request){
        Book::where('id', $request->id)->delete();
        return redirect()->back();
    }
}
