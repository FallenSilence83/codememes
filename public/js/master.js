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
    $('.mute-button').on('click', function(e){
        e.preventDefault();
        var mute = 'false';
        if($(this).attr('name') == 'volume-high'){
            mute = 'true';
            $(this).attr('name', 'volume-off');
        }else{
            mute = 'false';
            $(this).attr('name', 'volume-high');

            try {
                popAudio = document.getElementById("popAudio");
                if (popAudio) {
                    popAudio.play();
                }
            }catch(error){
                console.log(error);
            }
        }
        $(this).blur();
        //update the user in session
        $.ajax({
            url: '/user/update',
            method: "POST",
            data: { mute: mute },
            dataType: "json",
            success: function (result) {
                if (result.error) {
                    console.log('json error: ' + result.error);
                }
            },
            error: function () {
                console.log("todo: error handling");
            },
            complete: function () {
            }
        });
    });
});