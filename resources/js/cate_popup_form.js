function removeClass() {
    $('#cate-f').removeClass('show');
    setTimeout(function () {
        $('#cate-f').empty();
    }, 500)
}

$(document).ready(function () {
    $('#off-form').click(function () {
        removeClass();
    })
});

$('#form').submit(function (e) {
    e.preventDefault();
    var url = window.location.origin + '/admin/api-store-category';
    var parent_name = $('#parent_name').val();
    var child_name = $('#child_name').val();
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            'parent_name': parent_name,
            'child_name': child_name,
        },
        success: function (res) {
            var html = '<option value="' + res.dataChild.id + '">' + res.dataChild.name +
                '</option>';
            $('#category_id').append(html);
            removeClass();
        },
        error: function (error) {
            $('#errorParent').text(error.responseJSON.errors.parent_name);
            $('#errorChild').text(error.responseJSON.errors.child_name);

            if (!error.responseJSON.errors.parent_name) {
                $('#errorParent').text('');
            }

            if (!error.responseJSON.errors.child_name) {
                $('#errorChild').text('');
            }
        },
        complete: function (res) {

        }
    })
});
