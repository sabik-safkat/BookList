function send(){
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
}

function removeAlert(field, message){
    if($('#'+field).val() != ''){
        $('#'+message).hide();
    }
}

function editBook(id, title, author){
    if (id > 0 && title != '' && author != ''){
        $('#book_title').val(title);
        $('#author').val(author);
        $('#book_id').val(id);
        $("#book_title").prop("readonly", true);
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
    $("#book_title").prop("readonly", false);
}