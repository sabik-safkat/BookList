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
                <input type="hidden" value="" name="book_id" id="book_id">
            </div>
            <div class="form-group text-left book-inputs">
                <label for="author">Author</label>
                <input type="text" class="form-control" id="author" name="author" onchange="removeAlert('book_title','title_validation')">
                <span id="author_validation" class="validation_alert">{{ config('constants.author_name_validation') }}</span>
            </div>
        </form>
        <button type="button" id="add_book" class="export-buttons">Submit</button>
        <button type="button" id="add_book" class="export-buttons" onclick="reset_search()">Reset</button>
    </div>
    <div class="col-md-12 text-center mt-5 p-3 form-holder">
            <form id="search_book" action="{{route('home')}}" method="GET">
                <table style="width: 100%">
                    <tbody>
                        <tr>
                            <td>
                                <input type="text" id="search_term" name="search_term" value="{{$search_term}}">
                                <input type="hidden" id="search_column" name="search_column" value="">
                                <button type="submit" class="search_button"><i class="fa fa-search"></i></button>
                                <a href="{{route('home')}}" class="export-buttons">Reset</a>
                            </td>
                            <td class="text-right">
                                <a href="{{route('export')}}" class="export-buttons">Export CSV</a>
                                <a href="{{route('export-xml')}}" class="export-buttons">Export XML</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <br/>
        @if(isset($books))
            <table style="width: 100%">
                <thead>
                    <th>Serial</th>
                    <th>
                        Title 
                        <i data-toggle="tooltip" title="{{ config('constants.click_to_sort_by_title') }}" class="fa fa-sort pointy" onclick="sort('title')"></i>
                        <a href="{{route('export', ['column' => 'title'])}}"><i class="fa fa-file-csv pointy"></i></a>
                    </th>
                    <th>
                        Author 
                        <i data-toggle="tooltip" title="{{ config('constants.click_to_sort_by_author') }}" class="fa fa-sort pointy" onclick="sort('author')"></i>
                        <a href="{{route('export', ['column' => 'author'])}}"><i class="fa fa-file-csv pointy"></i></a>
                    </th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach($books as $book)
                        <tr>
                            <td class="text-center">{{ $loop->index + 1 }}</td>
                            <td>{{$book->title}}</td>
                            <td>{{$book->author}}</td>
                            <td class="text-center">
                                <span class="pointy" onclick="deleteBook('{{$book->id}}')"><i class="fa fa-trash"></i></span>&nbsp;
                                <span class="pointy" onclick="editBook({{$book->id}}, '{{$book->title}}', '{{$book->author}}')"><i class="fa fa-edit"></i></span>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-center pt-5">
                            {{ $books->links() }}
                        </td>
                    </tr>
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
        $.ajax({
            url: "{{route('book-deletion')}}",
            data: {
                "_token": "{{ csrf_token() }}",
                "id" : id
            },
            type: 'POST',
            success: function(result){
                location.reload();
            }
        });
    }

    function editBook(id, title, author){
        if (id > 0 && title != '' && author != ''){
            $('#book_title').val(title);
            $('#author').val(author);
            $('#book_id').val(id);
        }
    }

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });

    function sort(column){
        $('#search_column').val(column);
        $('#search_book').submit();
    }

    function reset_search(){
        $('#book_title').val('');
        $('#book_id').val('');
        $('#author').val('');
    }
</script>
@stop