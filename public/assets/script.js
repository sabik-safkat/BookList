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
    if(confirm("Are you sure you want to delete this entry?")){
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
}

function editBook(id, title, author){
    if (id > 0 && title != '' && author != ''){
        $('#book_title').val(title);
        $('#author').val(author);
        $('#book_id').val(id);
        $("html, body").animate({ scrollTop: 0 }, "slow");
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