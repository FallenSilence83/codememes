$(document).ready(function () {

    $('.logout').on('click', function(){

        $.ajax({
            url: '/user/logout',
            success: function (result) {
                window.location = '/';
            }
        });
    })
    $('.linked-btn').on('click', function(){
        var href = $(this).attr('href');
        if(href){
            window.location = href;
        }
    })
});