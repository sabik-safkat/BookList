<?php

namespace App\Http\Controllers;

/* Path to Model */
use App\Models\Book;

/* Library for Excel generation */
use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Http\Request;

class BookController extends Controller
{
    public function bookList(Request $request){
        $books = Book::query();
        $term = isset($request->search_term)?$request->search_term:'';
        $column = isset($request->search_column)?$request->search_column:'';
        $books->when($request->search_term, function ($query, $term) { 
            return $query->where('title', 'LIKE', '%'.$term.'%')
                            ->orWhere('author', 'LIKE', '%'.$term.'%');
        });
        $books->when($request->search_column, function ($query, $column) { 
            return $query->orderBy($column, 'ASC');
        });
        
        $data = [
            'books' => $books->get(),
            'search_term' => $term
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

    public function export(Request $request){
        $column = isset($request->column)?$request->column:'';
        $books = Book::query();
        if(isset($request->column)){
            $books->select($column);
        } else {
            $books->select('title', 'author');
        }
        $results = $books->get();
        $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject);
        $csv->insertOne(array_keys($results[0]->getAttributes()));
        foreach ($results as $result) {
            $csv->insertOne($result->toArray());
        }
        return response((string) $csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Disposition' => 'attachment; filename="books.csv"',
        ]);
    }

    public function xml(Request $request){
        $column = isset($request->column)?$request->column:'';
        $books = Book::query();
        if(isset($request->column)){
            $books->select($column);
        } else {
            $books->select('title', 'author');
        }
        $results = $books->get();

        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument();
        $xml->startElement('Books');

        foreach ($results as $result) {
            $xml->startElement('Book');
            $xml->writeAttribute('title', $result->title);
            $xml->writeAttribute('author', $result->author);
            $xml->endElement();
        }

        $xml->endElement();
        $xml->endDocument();
        $contents = $xml->outputMemory();
        \File::put(public_path('/'.'output.xml'),$contents);

	    return \Response::download(public_path('/'.'output.xml'));
    
    }
}
