@extends('main')

@section('custom_css')
    
@stop

@section('content')
    <div class="col-md-12 text-center mt-5 p-3 form-holder">
        <form action="/action_page.php">
            <div class="form-group text-left book-inputs">
                <label for="email">Book Title</label>
                <input type="email" class="form-control" id="email">
            </div>
            <div class="form-group text-left book-inputs">
                <label for="pwd">Author</label>
                <input type="password" class="form-control" id="pwd">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
@stop

@section('custom_js')
@stop