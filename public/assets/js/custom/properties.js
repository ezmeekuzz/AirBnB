document.addEventListener('DOMContentLoaded', async function () {
    var dropdown = document.querySelector('.dropdown.guest-details');
    var bsDropdown = new bootstrap.Dropdown(dropdown);
    async function fetchAndSetLockDateRanges() {
        try {
            const response = await fetch('/properties/icsData/' + property_id);
            const data = await response.json();
            const lockDateRanges = Object.values(data).map(({ start, end }) => [start, end]);
    
            return lockDateRanges;
        } catch (error) {
            console.error('Error fetching lock days:', error);
        }
    }

    const lockDateRanges = await fetchAndSetLockDateRanges();

    const picker = new Litepicker({ 
        element: document.getElementById('dateRangePicker'),
        firstDay: 0,
        format: "MM-DD-YYYY",
        minDate: new Date()-1,
        minDays: 3,
        inlineMode: true,
        lang: "en-US",
        singleMode: false,
        autoApply: false,
        allowRepick: true,
        lockDays: lockDateRanges,
        buttonText: {
            "apply": "<i class='fas fa-check'></i> Apply Selection",
            "cancel": "<i class='fas fa-times'></i> Cancel Selection"
        },
        selectForward: true,
        setup: (picker) => {

            const lockDaysFilter = (day) => {
                const startDay = picker.getDate();
    
                if (startDay && startDay.getDay() === 5 && day.getDay() === 0) {
                    iziToast.error({
                        title: 'You can\'t select Sunday as the end date when starting on Friday',
                        timeout: 2000,
                    });
                    return true;
                }
    
                return false;
            };
            
            function handleData(data) {
                data.forEach(function (priceData) {
                    var date = priceData.date;
                    var price = priceData.price;
                    var timestamp = new Date(date + 'T00:00:00').getTime();
                    var dateCell = document.querySelector('[data-time="' + timestamp + '"]');
                    if (dateCell) {
                        dateCell.innerHTML += '<br>$' + price;
                    }
                });
            }
        
            function datePrices() {
                $.ajax({
                    type: 'GET',
                    url: '/properties/datePrices/' + property_id,
                    dataType: 'json',
                    success: function (data) {
                        handleData(data);
                    }
                });
            }
    
            picker.setOptions({
                lockDaysFilter: (day) => lockDaysFilter(day)
            });

            picker.on('before:show', (el) => {
                datePrices();
            });

            picker.on('button:apply', () => {
                datePrices();
                var selectedDates = getSelectedDates(picker);
                const hasOverlapResult = hasOverlap(lockDateRanges, selectedDates);
                if (hasOverlapResult) {
                    iziToast.error({
                        title: 'Selected date range contains blocked dates',
                        timeout: 2000,
                    });
                    return;
                }
                calculateTotalPrice(picker);
            });
            picker.on('button:cancel', () => {
                bsDropdown.hide();
                picker.clearSelection();
            });
            picker.on('change:month', (date, calendarIdx) => {
                datePrices();
            });
            picker.on('preselect', (startDate, endDate) => {
                datePrices();
                if (startDate && startDate.getDay() === 5) {
                    picker.setOptions({
                        lockDaysFilter: (day) => {
                            const d = day.getDay();
                
                            if (d === 0) {
                                let currentDay = startDate;
                
                                while (currentDay.getDay() !== 0) {
                                    currentDay.setDate(currentDay.getDate() + 1);
                                }
                
                                return currentDay.getTime() === day.getTime();
                            }
                
                            return false;
                        },
                    });
                }

                if (startDate && startDate.getDay() === 6) {
                    iziToast.error({
                        title: 'You can\'t check in on Saturday',
                        timeout: 2000,
                    });
                    picker.clearSelection();
                } else {
                    $('#checkInLabel').html(startDate.format('MM-DD-YYYY'));
                }
    
                if (endDate && endDate.getDay() === 6) {
                    iziToast.error({
                        title: 'You can\'t check out on Saturday',
                        timeout: 2000,
                    });
                    picker.clearSelection();
                } else {
                    if(endDate) {
                        $('#checkOutLabel').html(endDate.format('MM-DD-YYYY'));
                    }
                }
            });
        }
    });
    function calculateTotalPrice(picker) {
        // Make the AJAX request
        $.ajax({
            type: 'GET',
            url: '/properties/calculateTotalPrice/' + property_id + '/' + picker.getStartDate().format('YYYY-MM-DD') + '/' + picker.getEndDate().format('YYYY-MM-DD'),
            dataType: 'json',
            success: function (data) {
                // Assuming data.total_price is a numeric value
                const totalAmount = data.total_price || 0;
                const cleaningFee = data.cleaning_fee || 0;
                const extraGuestFee = data.extra_guest_fee || 0;
                const basicNumberOfGuest = parseInt(data.basic_number_of_guest) || 0;
                const guestLimit = parseInt(data.guest_limit) || 0;
                const petFee = data.pet_fee || 0;
                const hotTubFee = data.hot_tub_fee || 0;
                const overAllTotalAmount = parseFloat(totalAmount) + parseFloat(cleaningFee) + parseFloat(hotTubFee) || 0;
    
                // Log start and end dates
                const startDate = picker.getStartDate().format('YYYY-MM-DD');
                const endDate = picker.getEndDate().format('YYYY-MM-DD');
                const dateDifference = calculateDateDifference(startDate, endDate);
    
                // Format totalAmount as currency
                const formattedTotalAmount = totalAmount.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
                const formattedCleaningFee = '$' + cleaningFee.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
                const formattedHotTubFee = '$' + hotTubFee.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
                const formattedOverAllTotalAmount = overAllTotalAmount.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
    
                // Set the formatted totalAmount as the HTML content of the element with ID totalAmount
                $('#totalAmount').html(formattedTotalAmount + ' (x'+ dateDifference +' Nights)');
                $('#overAllTotalAmount').html(formattedOverAllTotalAmount);
                $('#cleaningFeeLabel').html(formattedCleaningFee);
                $('#hotTubFeeLabel').html(formattedHotTubFee);                
    
                var incrementButtons = document.querySelectorAll('.increment');
                var decrementButtons = document.querySelectorAll('.decrement');
                var adults = document.getElementById('adults');
                var childrens = document.getElementById('childrens');
                var infants = document.getElementById('infants');
                var pets = document.getElementById('pets');
    
                var extraGuestTotalAmount; // Declare extraGuestTotalAmount at a higher scope
                var totalAmountOverAll; // Declare totalAmountOverAll at a higher scope
                var petTotalFee; // Declare petTotalFee at a higher scope
                
                incrementButtons.forEach(function (button) {
                    button.addEventListener('click', function (event) {
                        event.stopPropagation();
                        updateCounter(this, 1);
                        petTotalFee = parseInt(pets.value) * parseFloat(petFee);
                        $('#petFeeLabel').html(petTotalFee.toLocaleString('en-US', { style: 'currency', currency: 'USD' }));
                        if ((parseInt(adults.value) + parseInt(childrens.value)) > basicNumberOfGuest) {
                            var additionalGuest = (parseInt(adults.value) + parseInt(childrens.value)) - basicNumberOfGuest;
                            extraGuestTotalAmount = (parseInt(additionalGuest) * parseFloat(extraGuestFee)) * dateDifference;
                            totalAmountOverAll = overAllTotalAmount + extraGuestTotalAmount + petTotalFee;
                            $('#extraGuestFeeLabel').html(extraGuestTotalAmount.toLocaleString('en-US', { style: 'currency', currency: 'USD' }));
                            $('#overAllTotalAmount').html(totalAmountOverAll.toLocaleString('en-US', { style: 'currency', currency: 'USD' }));
                        }
                        updateReservedButtonState();
                    });
                });
                
                decrementButtons.forEach(function (button) {
                    button.addEventListener('click', function (event) {
                        event.stopPropagation();
                        updateCounter(this, -1);
                        petTotalFee = parseInt(pets.value) * parseFloat(petFee);
                        $('#petFeeLabel').html(petTotalFee.toLocaleString('en-US', { style: 'currency', currency: 'USD' }));
                        if ((parseInt(adults.value) + parseInt(childrens.value)) >= basicNumberOfGuest) {
                            var additionalGuest = (parseInt(adults.value) + parseInt(childrens.value)) - basicNumberOfGuest;
                            extraGuestTotalAmount = (parseInt(additionalGuest) * parseFloat(extraGuestFee)) * dateDifference;
                            totalAmountOverAll = overAllTotalAmount + extraGuestTotalAmount + petTotalFee;
                            $('#extraGuestFeeLabel').html(extraGuestTotalAmount.toLocaleString('en-US', { style: 'currency', currency: 'USD' }));
                            $('#overAllTotalAmount').html(totalAmountOverAll.toLocaleString('en-US', { style: 'currency', currency: 'USD' }));
                        }
                        updateReservedButtonState();
                    });
                });
                
                function updateReservedButtonState() {
                    if ((parseInt(adults.value) + parseInt(childrens.value)) > 0) {
                        $('.reservedBtn').prop('disabled', false);
                    } else {
                        $('.reservedBtn').prop('disabled', true);
                    }
                }
                
                $('.reservedBtn').on('click', function () {
                    var adultsInp = adults.value;
                    var childrensInp = adults.value;
                    var infantsInp = adults.value;
                    var petsInp = adults.value;
                    window.location.href = '/check-out?checkIn=' +
                        picker.getStartDate().format('YYYY-MM-DD') +
                        '&checkOut=' + picker.getEndDate().format('YYYY-MM-DD') +
                        '&slug=' + slug +
                        '&adults=' + encodeURIComponent(adultsInp) +
                        '&childrens=' + encodeURIComponent(childrensInp) +
                        '&infants=' + encodeURIComponent(infantsInp) +
                        '&pets=' + encodeURIComponent(petsInp) +
                        '&cleaningFee=' + cleaningFee +
                        '&extraGuestFee=' + extraGuestTotalAmount +
                        '&hotTubFee=' + hotTubFee +
                        '&petFee=' + petTotalFee +
                        '&property_id=' + property_id +
                        '&nightStayTotalAmount=' + totalAmount +
                        '&totalAmount=' + totalAmountOverAll;
                });

                bsDropdown.show();
            },
            error: function (xhr, status, error) {
                console.error('Error fetching total amount:', error);
    
                // Show an iziToast error notification with the error message
                iziToast.error({
                    title: '1 or more of the dates within the range doesn\'t have a price!',
                    timeout: 2000,
                });
                return false;
            }
        });
    }

    function calculateDateDifference(startDate, endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const differenceInTime = end - start;
        const differenceInDays = differenceInTime / (1000 * 3600 * 24);
        return differenceInDays;
    }

    function updateCounter(button, increment) {
        var counterInput = button.closest('.number-counter').querySelector('.counter');
        var currentValue = parseInt(counterInput.value) || 0;
        var newValue = currentValue + increment;

        newValue = Math.max(newValue, 0);

        counterInput.value = newValue;
        
        updateDropdownHeader();
    }

    function updateDropdownHeader() {
        var adultsCount = parseInt(document.getElementById('adults').value) || 0;
        var childrensCount = parseInt(document.getElementById('childrens').value) || 0;
        var infantsCount = parseInt(document.getElementById('infants').value) || 0;
        var petsCount = parseInt(document.getElementById('pets').value) || 0;
    
        var totalLabel = document.getElementById('dropdownMenuButton');
        if (adultsCount + childrensCount + infantsCount + petsCount === 0) {
            totalLabel.innerHTML = "<i class='fas fa-cogs'></i> Select Guest Option";
        } else {
            totalLabel.innerHTML = "<i class='fas fa-cogs'></i> (" + adultsCount + " Adults, " + childrensCount + " Children, " + infantsCount + " Infants, " + petsCount + " Pets)";
        }
    }

    function hasOverlap(lockDates, selectedDates) {
        for (const selectedDate of selectedDates) {
            for (const [start, end] of lockDates) {
                if (selectedDate >= start && selectedDate <= end) {
                    return true;  // There is an overlap
                }
            }
        }
        return false;  // No overlap found
    }
    function getSelectedDates(picker) {
        const selectedDates = [];
        const startDate = picker.getStartDate().clone();
        const endDate = picker.getEndDate();

        while (startDate.isSameOrBefore(endDate)) {
            selectedDates.push(startDate.format('YYYY-MM-DD'));
            startDate.add(1, 'day');
        }
        return selectedDates;
    }
});
$(document).ready( function(){
    var embed= "<iframe width='100%' height='100%' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='https://maps.google.com/maps?&amp;q="+encodeURIComponent( address )+"&amp;output=embed'></iframe>";
    $('.place').html(embed);
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