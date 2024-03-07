
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
        startDate: moment(startDate),
        endDate: moment(endDate),
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

        // AJAX call on successful selection
        $.ajax({
            url: '/search-result/displayResult', // Replace with your actual backend endpoint
            method: 'GET',
            data: {
                startDate: start.format('YYYY-MM-DD'),
                endDate: end.format('YYYY-MM-DD'),
                adult: $('#adult').val(),
                children: $('#children').val(),
                infant: $('#infant').val(),
                pet: $('#pet').val(),
            },
            beforeSend: function() {
                $('#loading').css('display', 'flex');
            },
            complete: function() {
                $('#loading').css('display', 'none');
            },
            success: function(response) {
                document.getElementById('result').innerHTML = response;
                $('#changeSearch').modal('hide');
                history.pushState({}, document.title, '/search-result?startDate=' + start.format('YYYY-MM-DD') + '&endDate=' + end.format('YYYY-MM-DD') + '&adult=' + $('#adult').val() + '&children=' + $('#children').val() + '&infant=' + $('#infant').val() + '&pet=' + $('#pet').val());
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            },
            error: function(error) {
                console.error("AJAX error:", error);
            }
        });
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
document.addEventListener("DOMContentLoaded", function () {
    function searchAvailability() {
        $.ajax({
            url: '/search-result/displayResult', // Replace with your actual backend endpoint
            method: 'GET',
            data: {
                startDate: startDate,
                endDate: endDate,
                adult: adult,
                children: children,
                infant: infant,
                pet: pet,
            },
            beforeSend: function() {
                $('#loading').css('display', 'flex');
            },
            complete: function() {
                $('#loading').css('display', 'none');
            },
            success: function(response) {
                document.getElementById('result').innerHTML = response;
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            },
            error: function(error) {
                console.error("AJAX error:", error);
            }
        });
    }
    searchAvailability();
});
document.addEventListener('DOMContentLoaded', function () {
    // Event listener for when the modal is shown
    $('#gallery').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var propertyId = button.data('property-id'); // Extract property ID from data-attribute

        // Ajax request to your controller
        $.ajax({
            url: '/search-result/propertyGallery/' + propertyId,
            type: 'GET',
            dataType: 'json',
            success: function (images) {
                // Clear existing carousel items and indicators
                $('#propertyImagesCarousel .carousel-inner').empty();
                $('#propertyImagesCarousel .carousel-indicators').empty();

                // Add images and indicators to the carousel
                images.forEach(function (image, index) {
                    var activeClass = index === 0 ? 'active' : '';
                    var imageUrl = image['location']; // Replace 'your_image_field' with the actual field name in your model
                    var carouselItem = `<div class="carousel-item ${activeClass}">
                                            <img src="${imageUrl}" class="d-block w-100" alt="Image ${index + 1}">
                                        </div>`;
                    $('#propertyImagesCarousel .carousel-inner').append(carouselItem);

                    // Add indicators
                    var indicator = `<button type="button" data-bs-target="#propertyImagesCarousel" data-bs-slide-to="${index}" class="${activeClass}" aria-label="Image ${index + 1}"></button>`;
                    $('#propertyImagesCarousel .carousel-indicators').append(indicator);
                });
            },
            error: function (xhr, status, error) {
                console.error(error);
                // Handle error if needed
            }
        });
    });
    // Event listener for when the modal is shown
    $('#priceBreakdown').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var propertyId = button.data('property-id');
    
        // Ajax request to your controller
        $.ajax({
            url: '/search-result/propertyPriceBreakdown/' + propertyId,
            type: 'GET',
            dataType: 'json',
            data: {
                startDate: startDate,
                endDate: endDate,
                adult: adult,
                children: children,
                infant: infant,
                pet: pet,
            },
            success: function (response) {
                $('#propertyPriceBreakdown tbody').empty(); // Clear existing tbody content
                $.each(response.pricing, function (index, pricingDetail) {
                    var dateObj = new Date(pricingDetail.date);
                    var formattedDate = dateObj.toLocaleString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
    
                    var row = `<tr><td>Room ${formattedDate}</td><td>$${pricingDetail.price}</td></tr>`;
                    $('#propertyPriceBreakdown tbody').append(row);
                });
                $.each(response.fees, function (key, value) {
                    if (key !== 'Total') { // Exclude the 'Total' row from the loop
                        var row = `<tr><td>${key}</td><td>${value}</td></tr>`;
                        $('#propertyPriceBreakdown tbody').append(row);
                    }
                });
                var totalRow = `<tr style="font-weight: bold;" class="table-info font-bold"><td>Total</td><td>${response.fees['Total']}</td></tr>`;
                $('#propertyPriceBreakdown tbody').append(totalRow);
                console.log(response.pricing);
            },
            error: function (xhr, status, error) {
                console.error(error);
                // Handle error if needed
            }
        });
    });
});