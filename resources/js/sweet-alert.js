$(document).ready(function() {
    $(".delete").click(function(e) {
        e.preventDefault();
        var btn_delete = e.currentTarget.id;

        swal({
                title: "Are you sure?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm) {
                if (isConfirm) {
                    swal("Shortlisted!", "Candidates are successfully shortlisted!", "success");
                    setTimeout(function() {
                        $('#' + btn_delete).submit();
                    }, 1500);
                } else {
                    swal("Cancelled", "Your imaginary file is safe :)", "error");
                }
            });
    });
});
