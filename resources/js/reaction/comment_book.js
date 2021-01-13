var url = window.location.origin;

$("#comment").on('input', function (e) {
    if (this.value.length >= 1) {
        $('#btn-cmt').removeClass('event-none');
    } else {
        $('#btn-cmt').addClass('event-none');
    }
});

$('.cmt-form').submit(function (e) {
    e.preventDefault();
    var book_id = $('#book_id').text();
    var comment = $('#comment').val();

    $.ajax({
        url: url + '/comments',
        type: 'POST',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            book_id: book_id,
            comment: comment
        },
        success: function (res) {
            var name = res.user_name;
            $('.review-list').append(`
                <li>
                    <em class="bold-text">${name}</em>
                    <p>${comment}</p>
                </li>
            `);
            $('#comment').val('');
        },
        error: function (XHR, status, error) {
            if (error == 'Unauthorized') {
                window.location.href = url + '/login';
            }
        },
        complete: function (res) {

        }
    });
})
