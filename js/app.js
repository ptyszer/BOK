$(function () {
    console.log('ready!');

    var conversations = $("#conversations");
    var currentId = conversations.data('current');

    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });

    conversations.find('tr').each(function (index, element) {
        if($(this).data('id') === currentId){
            $(this).addClass('active');
        }
    });

});