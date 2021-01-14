$('.vote').click(function () {
    var url = window.location.origin;
    var vote = $(this).attr('value');
    var book_id = $('#book_id').text();

    $.ajax({
        url: url + '/vote',
        type: 'GET',
        dataType: 'json',
        data: {
            book_id: book_id,
            vote: vote
        },
        success: function (res) {

        },
        error: function (XHR, status, error) {
            if (error == 'Unauthorized') {
                window.location.href = url + '/login';
            }
        },
        complete: function (res) {

        }
    })
})
