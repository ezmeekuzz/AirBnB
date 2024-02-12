$(document).ready(function() {
    var propertyId = window.location.pathname.split('/').pop();

    // Initialize FullCalendar with month view
    $('#event-calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month'
        },
        defaultView: 'month',
        events: '/admin/pricing/Lists?property_id=' + propertyId, // Replace with your backend endpoint for fetching events
        selectable: true,
        select: function(start, end, jsEvent, view) {
            console.log('Select Event - Start:', start.format(), 'End:', end.format());
            // Check if any selected date is in the past
            if (isAnyDateInPast(start, end)) {
                Swal.fire({
                    title: 'Cannot Select Past Dates',
                    text: 'Please select upcoming dates.',
                    icon: 'error',
                });
                return;
            }

            // Handle the selection of multiple dates
            Swal.fire({
                title: 'Enter Price for Selected Dates',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Save',
                showLoaderOnConfirm: true,
                inputValidator: (value) => {
                    // Validate the input value
                    if (!value || isNaN(value)) {
                        return 'Please enter a valid number.';
                    }
                    return null;
                },
                preConfirm: (price) => {
                    // Send the price and selected dates to your backend for processing
                    var selectedDates = getSelectedDates(start, end);
                    return fetch('/admin/pricing/insert-multiple', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            property_id: propertyId,
                            dates: selectedDates,
                            price: price,
                        }),
                    })
                    .then(response => {
                        console.log('Server Response:', response);
                        if (!response.ok) {
                            throw new Error('Server error');
                        }
                        $('#event-calendar').fullCalendar('refetchEvents');
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        );
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
            .then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Price Saved!',
                        'The price for selected dates has been saved.',
                        'success'
                    );
                    // Optionally, you can refresh the calendar to reflect the changes
                    $('#event-calendar').fullCalendar('refetchEvents');
                }
            });
        },
        dayClick: function(date, jsEvent, view) {
            console.log('Day Click Event - Date:', date.format());
            // Check if the clicked date is in the past
            if (date.isBefore(moment(), 'day')) {
                Swal.fire({
                    title: 'Cannot Select Past Dates',
                    text: 'Please select an upcoming date.',
                    icon: 'error',
                });
                return;
            }

            // Handle the day click event
            // You can show a modal or prompt to enter the price for the clicked date
            Swal.fire({
                title: 'Enter Price for ' + date.format(),
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Save',
                showLoaderOnConfirm: true,
                inputValidator: (value) => {
                    // Validate the input value
                    if (!value || isNaN(value)) {
                        return 'Please enter a valid number.';
                    }
                    return null;
                },
                preConfirm: (price) => {
                    // Send the price and date to your backend for processing
                    return fetch('/admin/pricing/insert', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            property_id: propertyId,
                            date: date.format(),
                            price: price,
                        }),
                    })
                    .then(response => {
                        console.log('Server Response:', response);
                        if (!response.ok) {
                            throw new Error('Server error');
                        }
                        $('#event-calendar').fullCalendar('refetchEvents');
                        return response.json();
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        );
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
            .then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Price Saved!',
                        'The price for ' + date.format() + ' has been saved.',
                        'success'
                    );
                    // Optionally, you can refresh the calendar to reflect the changes
                    $('#event-calendar').fullCalendar('refetchEvents');
                }
            });
        }
    });

    // Function to check if any selected date is in the past
    function isAnyDateInPast(start, end) {
        var currentDate = moment();
        return start.isBefore(currentDate, 'day') || end.isBefore(currentDate, 'day');
    }

    function getSelectedDates(start, end) {
        var selectedDates = [];
        var currentDate = start.clone();
    
        while (currentDate.isBefore(end, 'day') || currentDate.isSame(end, 'day')) {
            selectedDates.push(currentDate.format('YYYY-MM-DD'));
            currentDate.add(1, 'day');
        }
    
        return selectedDates.slice(0, -1); // Exclude the last date
    }
    
       
    
});
