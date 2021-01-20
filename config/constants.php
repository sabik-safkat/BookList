<?php
return [
    'book_name_validation' => env('BOOK_NAME_VALIDATION','Book title is required!'),
    'author_name_validation' => env('AUTHOR_NAME_VALIDATION','Author name is required!'),
    'click_to_sort_by_title' => env('SORT_BY_TITLE','click here to sort the list by book title!'),
    'click_to_sort_by_author' => env('SORT_BY_AUTHOR','click here to sort the list by author!'),
    'delete_book' => env('DELETE_BOOK','Delete this book!'),
    'edit_book' => env('DELETE_BOOK','Edit this book!'),
    'export_xml' => env('EXPORT_XML','Export XML of this column!'),
    'export_csv' => env('EXPORT_CSV','Export CSV of this column'),
];
?>