$(document).ready(function () {

    $('.logout').on('click', function(){
        $.ajax({
            url: '/user/logout',
            success: function (result) {
                window.location = '/';
            }
        });
    });
    $('.linked-btn').on('click', function(){
        var href = $(this).attr('href');
        if(href){
            window.location = href;
        }
    });
    $('.edit-user').on('click', function(){
        $('#editUserModal').modal('show');
    });
    $('#editUserForm').on('submit', function(e){
        e.preventDefault();
        var displayName = $('#displayNameEdit').val();
        //update the user in session
        $.ajax({
            url: '/user/update',
            method: "POST",
            data: { displayName: displayName },
            dataType: "json",
            success: function (result) {
                if (result.error) {
                    console.log('json error: ' + result.error);
                }else if(result.displayName){
                    $('.user-badge').html(result.displayName);
                }
            },
            error: function () {
                console.log("todo: error handling");
            },
            complete: function () {
                $('#editUserModal').modal('hide');
            }
        });
    });
});