$(document).ready(function () {
    var currentDate = moment();
    var nextDate = moment(currentDate).add(2, 'days');
    // Function to reset date range to default values
    function resetDateRange() {
        $('input[name="daterange"]').data('daterangepicker').setStartDate(currentDate);
        $('input[name="daterange"]').data('daterangepicker').setEndDate(nextDate);
    }

    $('input[name="daterange"]').daterangepicker({
        opens: 'left',
        startDate: currentDate,
        endDate: nextDate,
        minDate: currentDate, // Minimum selectable date
    }, function(start, end, label) {
        // Move date-related variable initialization inside the daterangepicker callback
        var currentDate = moment();
        var nextDate = moment(currentDate).add(2, 'days');
        var nights = end.diff(start, 'days');

        // Check if check-in or check-out is on a Saturday
        if (start.isoWeekday() === 6 || end.isoWeekday() === 6) {
            iziToast.error({
                title: 'Check-in and check-out are not allowed on Saturdays.',
                timeout: 2000,
            });
            resetDateRange(); // Reset date range to default values
        } else if (start.isoWeekday() === 5 && end.isoWeekday() === 7 && nights === 2) {
            iziToast.error({
                title: 'Check-out on Sunday is not allowed when check-in is on Friday.',
                timeout: 2000,
            });
            resetDateRange(); // Reset date range to default values
        } else if (nights < 2) {
            iziToast.error({
                title: 'Minimum selection should be 2 nights.',
                timeout: 2000,
            });
            resetDateRange(); // Reset date range to default values
        } else {
            // Use the start and end variables for the console.log
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        }
    });

    // Initialize start and end variables outside the daterangepicker callback
    var start, end;

    $('#bookNow').on('click', function() {
        // Set start and end variables before making the AJAX call
        start = $('input[name="daterange"]').data('daterangepicker').startDate;
        end = $('input[name="daterange"]').data('daterangepicker').endDate;

        window.location.href = "/home/searchDateAvailability?start_date=" + start.format('YYYY-MM-DD') + "&end_date=" + end.format('YYYY-MM-DD') + "&adult=" + $('#adult').val() + "&children=" + $('#children').val() + "&infant=" + $('#infant').val() + "&pet=" + $('#pet').val();
    });
});
$(document).ready(function() {
    $('#sendMessage').submit(function(event) {
        event.preventDefault();

        if (!validateForm()) {
            return;
        }

        var messageData = {
            fullname: $('#fullname').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            messageContent: $('#messageContent').val()
        };

        $.ajax({
            type: 'POST',
            url: '/properties/sendMessage',
            data: messageData,
            dataType: 'json',
            beforeSend: function() {
                $('#loading').css('display', 'flex');
            },
            complete: function() {
                $('#loading').css('display', 'none');
            },
            success: function(response) {
                iziToast.success({
                    title: response.message,
                    timeout: 2000,
                });

                $('#sendMessage')[0].reset();
            },
            error: function() {
                iziToast.error({
                    title: 'Error occurred while submitting the form.',
                    timeout: 2000,
                });
            }
        });
    });

    function validateForm() {
        var fullname = $('#fullname').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var message = $('#messageContent').val();

        if (!fullname || !email || !phone || !message) {
            iziToast.error({
                title: 'All fields are required. Please fill in all the fields.',
                timeout: 2000,
            });
            return false;
        }

        var emailRegex = /^\S+@\S+\.\S+$/;
        if (!emailRegex.test(email)) {
            iziToast.error({
                title: 'Please enter a valid email address.',
                timeout: 2000,
            });
            return false;
        }

        return true;
    }    
});
