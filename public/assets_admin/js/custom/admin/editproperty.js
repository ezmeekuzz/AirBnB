$(function() {    
    $('#editproperty').submit(function(event) {
        event.preventDefault();
        var propertyname = $('#propertyname').val();
        var description = $('#description').val();
        var address = $('#address').val();
        var cleaningfee = $('#cleaningfee').val();
        var extraguest = $('#extraguest').val();
        var hottub = $('#hottub').val();
        var petfee = $('#petfee').val();
        var basic_number_of_guest = $('#basic_number_of_guest').val();
        var guest_limit = $('#guest_limit').val();
        var ics_link = $('#ics_link').val();
        if(propertyname != "" && description != "" && address != "" && cleaningfee != "" && extraguest != "" && hottub != "" && petfee != "" && basic_number_of_guest != "" && guest_limit != "" && ics_link != "") {
            var formData = new FormData(this);
            $.ajax({
                url:"/admin/editproperty/update",
                method: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').css('display', 'flex');
                },
                complete: function(){
                    $('#loading').css('display', 'none');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        swal({
                            type: 'success',
                            title: 'Success',
                            text: response.message,
                        });
                    } else {
                        swal({
                            type: 'error',
                            title: 'Ooopss...',
                            text: response.message,
                        });
                    }
                },
                error: function() {
                    alert('Error occurred while inserting data.');
                }
            });
        }
        else {
            swal({
                type: 'error',
                title: 'Ooopss...',
                text: 'Please fill up required information.',
            });
        }
    });
});
$(document).ready(function() {
    $('#description').summernote({
        toolbar: [
            ['style', ['style']],
            ['fontsize', ['fontsize']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['picture', 'hr']],
            ['table', ['table']]
        ],
        tabsize: 2,
        height: 250,
        fontSizes: ['8', '9', '10', '11', '12', '14', '18', '24', '36', '48' , '64', '82', '150'],
        followingToolbar: false
    });
});