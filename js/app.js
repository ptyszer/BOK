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

    //dodanie nowej konwersacji
    $(".conversationAdd").on('submit', function (event) {
        event.preventDefault();

        $.post('../api/router.php/conversation', $(this).serialize())
            .done(function( data ) {
                console.log('submit', data);
                location.reload();
            }).fail(function() {
            alert('Wystąpił błąd podczas dodawania konwersacji');
        })
    });

    //przypisanie konwersacji
    $(".assign").on('click', function () {
    var id = $(this).data('id');
    console.log('clickon id: ' + id);
        $.ajax({
            url: "../api/router.php/conversation/",
            data: {
                id: id
            },
            type: "PATCH",
            dataType: "json"
        }).done(function () {
            location.reload();
        }).fail(function () {
            alert( "Wystąpił błąd");
        });
    });

    //dodanie nowej wiadomości
    $(".newMessage").on('submit', function (event) {
        event.preventDefault();
        console.log($(this).serialize());
        $.post('../api/router.php/message/', $(this).serialize())
            .done(function( data ) {
                console.log('submit', data)
                location.reload();
            }).fail(function() {
            alert('Wystąpił błąd podczas dodawania wiadomości');
        })
    });

});