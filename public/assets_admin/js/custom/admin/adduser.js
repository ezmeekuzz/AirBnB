$(function() {    
    $('#adduser').submit(function(event) {
        event.preventDefault();
        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();
        var emailaddress = $('#emailaddress').val();
        var username = $('#username').val();
        var password = $('#password').val();
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(username != "" && password != "" && firstname != "" && lastname != "" && emailaddress != "") {
            if(emailaddress.match(mailformat)) {
                var formData = new FormData(this);
                $.ajax({
                    url:"/admin/adduser/insert",
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
                            $("#firstname").val('');
                            $("#lastname").val('');
                            $("#emailaddress").val('');
                            $("#username").val('');
                            $("#password").val('');
                            swal({
                                type: 'success',
                                title: 'Success',
                                text: response.message,
                            });
                        } else if(response.status === 'existed') {
                            swal({
                                type: 'error',
                                title: 'Ooopss...',
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
                    text: 'Invalid email format!',
                });
            }
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