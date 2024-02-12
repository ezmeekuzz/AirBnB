$(document).ready(function() {
    $('#bookings').DataTable();
});
$('.delete-btn').on('click', function() {
    const id = $(this).data('id');
    const row = $(this).closest('tr');
    const table = $('#bookings').DataTable();
    swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: true,
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/admin/booking/delete/' + id,
                method: 'DELETE',
                success: function(response) {
                    if (response.status === 'success') {
                        // Remove the deleted row from the table
                        table.row(row).remove().draw(false);
                    }
                }
            });
        }
    });
});  
$('.approve-btn').on('click', function() {
    const id = $(this).data('id');
    const row = $(this).closest('tr');
    const table = $('#bookings').DataTable();
    swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, approve it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: true,
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: '/admin/booking/approve/' + id,
                method: 'POST',
                success: function(response) {
                    if (response.status === 'success') {
                        // Reload the entire page after successful approval
                        location.reload();
                    }
                }
            });
        }
    });
});  