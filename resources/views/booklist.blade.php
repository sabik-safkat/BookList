@extends('main')

@section('custom_css')
    
@stop

@section('content')
    <div class="col-md-12 text-center mt-5 p-3 form-holder">
        <form id="create_book" action="{{route('new-book-creation')}}" method="POST">
            @csrf
            <div class="form-group text-left book-inputs">
                <label for="book_title">Book Title</label>
                <input type="text" class="form-control" id="book_title" name="book_title" onchange="removeAlert('book_title','title_validation')">
                <span id="title_validation" class="validation_alert">{{ config('constants.book_name_validation') }}</span>
            </div>
            <div class="form-group text-left book-inputs">
                <label for="author">Author</label>
                <input type="text" class="form-control" id="author" name="author" onchange="removeAlert('book_title','title_validation')">
                <span id="author_validation" class="validation_alert">{{ config('constants.author_name_validation') }}</span>
            </div>
        </form>
        <button type="button" id="add_book" class="btn btn-default">Submit</button>
    </div>
    <div class="col-md-12 text-center mt-5 p-3 form-holder">
        @if(isset($books))
            <table style="width: 100%">
                <thead>
                    <th>Serial</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($books as $book)
                        <tr>
                            <td>1</td>
                            <td>{{$book->title}}</td>
                            <td>{{$book->author}}</td>
                            <td>
                                <span class="pointy" onclick="deleteBook('{{$book->id}}')"><i class="fa fa-trash"></i></span>&nbsp;
                                <span class="pointy" onclick="editBook('{{$book->id}}')"><i class="fa fa-edit"></i></span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@stop

@section('custom_js')
<script>
    $('#add_book').click(function(){
        var title = $('#book_title').val();
        var author = $('#author').val();
        flag = 0;
        if(title == '' || title == null){
            $('#title_validation').show();
            flag = 1;
        }
        if(author == '' || author == null){
            $('#author_validation').show();
            flag = 1;
        }
        if (flag == 0){
            $('#create_book').submit();
        }
    })

    function removeAlert(field, message){
        if($('#'+field).val() != ''){
            $('#'+message).hide();
        }
    }

    function deleteBook(id){
        alert(id);
    }

    function editBook(id){
        alert(id);
    }
</script>
@stop