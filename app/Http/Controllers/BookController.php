<?php

namespace App\Http\Controllers;

/* Path to Model */
use App\Models\Book;

/* Library for Excel generation */
use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Http\Request;

use File;
use Response;


/**
 * Booklist CRUD and exports into XML and CSV
 *
 * @author Sabik Safkat
 */ 
class BookController extends Controller
{
    /**
     * Get the paginated booklist as whole or filtered by
     * title or author or some other search term 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|null
     */
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
            'books' => $books->paginate(10),
            'search_term' => $term
        ];
        return view('booklist', $data);
    }




    /**
     * Add a new book or edit existing one
     *
     * @param  \Illuminate\Http\Request  $request
     * @return route
    */
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




    /**
     * Delete existing book
     *
     * @param  \Illuminate\Http\Request  $request
     * @return route
    */
    public function deleteBook(Request $request){
        Book::where('id', $request->id)->delete();
        return redirect()->back();
    }



    /**
     * Export CSV of book title list, author list or the whole booklist 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return csv
    */
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


    
    /** Export XML of book title list, author list or the whole booklist 
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return xml
    */
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
            if(!isset($request->column) || $request->column == 'title'){
                $xml->writeAttribute('title', $result->title);
            }
            if(!isset($request->column) || $request->column == 'author'){
                $xml->writeAttribute('author', $result->author);
            }
            $xml->endElement();
        }

        $xml->endElement();
        $xml->endDocument();
        $contents = $xml->outputMemory();
        $name = 'output_'.time().'.xml';
        File::put(public_path('assets/xml_exports/'.$name),$contents);
        return Response::download(public_path('assets/xml_exports/'.$name));
    }
}
