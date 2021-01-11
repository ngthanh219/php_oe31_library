$(document).ready(function () {
    $('#category-form').click(function () {
        $('#cate-f').addClass('show');
        var url = window.location.origin + '/admin/category-popup';
        $.ajax({
            url: url,
            method: 'get',
            success: function (res) {
                $('#cate-f').append(res);
            }
        })
    })
});
