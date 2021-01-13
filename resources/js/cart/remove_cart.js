$('.remove-item').click(function (e) {
    e.preventDefault();
    var url = window.location.origin;
    var id = $(this).attr('href');
    var parent = $(this).parent().parent();

    $.ajax({
        url: url + '/remove-cart/' + id,
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (res) {
            var html = '<div class="content-message">' + res.message + '</div>';
            var htmlEmpty = '<tr><td colspan="6"><h4>Not information</h4></td></tr>';
            parent.remove();
            $('.notification-client').append(html);
            $('.content-message').delay(2500).slideUp();
            setTimeout(function () {
                $('.notification-client').empty();
            }, 60000)

            if (!$('.product-detail')[0]) {
                $('.cart-table-holder').empty();
                $('.cart-table-holder').append(htmlEmpty);
            }
        },
        error: function (error) {

        },
        complete: function (res) {

        }
    })
});
