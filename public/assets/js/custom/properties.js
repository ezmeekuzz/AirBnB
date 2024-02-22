document.addEventListener('DOMContentLoaded', async function () {
    var dropdown = document.querySelector('.dropdown.guest-details');
    var bsDropdown = new bootstrap.Dropdown(dropdown);
    var incrementButtons = document.querySelectorAll('.increment');
    var decrementButtons = document.querySelectorAll('.decrement');
    var adults = document.getElementById('adults');
    var childrens = document.getElementById('childrens');
    var infants = document.getElementById('infants');
    var pets = document.getElementById('pets');

    async function fetchAndSetLockDateRanges() {
        try {
            const response = await fetch('/properties/icsData/' + property_id);
            const data = await response.json();
    
            console.log('Fetched data:', data);
    
            const lockDateRanges = Object.values(data).map(({ start, end }) => {
                const originalStartDate = moment(start);
                const originalEndDate = moment(end);
    
                const timestampStartDate = originalStartDate.valueOf();
                const timestampEndDate = originalEndDate.valueOf();
    
                const adjustedStartDate = moment(start).add(1, 'day').format('YYYY-MM-DD');
                const adjustedEndDate = moment(end).subtract(1, 'day').format('YYYY-MM-DD');
    
                logDataTimeInRange(timestampStartDate, timestampEndDate);
    
                return [adjustedStartDate, adjustedEndDate];
            });
    
            return lockDateRanges;
        } catch (error) {
            console.error('Error fetching lock days:', error);
        }
    }
    
    function logDataTimeInRange(startTime, endTime) {
        const elementsWithinRange = [];
    
        const allDataTimeElements = document.querySelectorAll('.day-item[data-time]');
    
        console.log('All Data-Time Elements:', allDataTimeElements);
    
        allDataTimeElements.forEach((element) => {
            const timestamp = parseInt(element.getAttribute('data-time'));
    
            // Log each timestamp to help identify the issue
            console.log('Timestamp:', timestamp);
    
            // Check if the element has the data-time attribute and its timestamp is within the range
            if (!isNaN(timestamp) && timestamp >= startTime && timestamp <= endTime) {
                elementsWithinRange.push(timestamp);
            }
        });
    
        console.log('Data-time attributes within range:', elementsWithinRange);
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
            "apply": "SUBMIT",
            "cancel": "Clear Selection"
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
                resetInputFields();
                updateReservedButtonState();
                setTimeout(() => {
                    bsDropdown.show();
                }, 100);
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

    function resetInputFields() {
        document.getElementById('adults').value = 0;
        document.getElementById('childrens').value = 0;
        document.getElementById('infants').value = 0;
        document.getElementById('pets').value = 0;
        updateDropdownHeader();
    }

    function handleIncrement(event) {
        event.stopPropagation();
        updateCounter(this, 1);
    
        updateReservedButtonState();
    }

    function handleDecrement(event) {
        event.stopPropagation();
        updateCounter(this, -1);
    
        updateReservedButtonState();
    }

    const doneButton = document.querySelector('.done-button');
    
    doneButton.addEventListener('click', function() {
        calculateAndUpdateTotal();
        if ((parseInt(adults.value) + parseInt(childrens.value)) > 0) {
            $('.reservedBtn').prop('disabled', false);
        } else {
            $('.reservedBtn').prop('disabled', true);
        }
        bsDropdown.hide();
    });

    async function calculateAndUpdateTotal() {
        try {
            const totalPriceData = await calculateTotalPrice(picker);
    
            // Use the returned data
            petTotalFee = parseInt(pets.value) * parseFloat(totalPriceData.petFee);
            $('#petFeeLabel').html(petTotalFee.toLocaleString('en-US', { style: 'currency', currency: 'USD' }));
    
            // Calculate extraGuestTotalAmount and totalAmountOverAll regardless of the condition
            var additionalGuest = Math.max(0, (parseInt(adults.value) + parseInt(childrens.value)) - totalPriceData.basicNumberOfGuest);
            extraGuestTotalAmount = (parseInt(additionalGuest) * parseFloat(totalPriceData.extraGuestFee)) * totalPriceData.dateDifference;
            totalAmountOverAll = totalPriceData.overAllTotalAmount + extraGuestTotalAmount + petTotalFee;
            paypalFee = parseFloat(totalAmountOverAll) * 0.03;
            totalWithPaypal = totalAmountOverAll + paypalFee;
            taxFee = parseFloat(totalWithPaypal) * 0.09;
            totalWithTaxes = totalWithPaypal + taxFee;
    
            $('#extraGuestFeeLabel').html(extraGuestTotalAmount.toLocaleString('en-US', { style: 'currency', currency: 'USD' }));
            $('#overAllTotalAmount').html(totalWithTaxes.toLocaleString('en-US', { style: 'currency', currency: 'USD' }));
            $('#taxFee').html(taxFee.toLocaleString('en-US', { style: 'currency', currency: 'USD' }));
            $('#paypalFee').html(paypalFee.toLocaleString('en-US', { style: 'currency', currency: 'USD' }));
                    
            $('.reservedBtn').on('click', function () {
                var adultsInp = adults.value;
                var childrensInp = adults.value;
                var infantsInp = infants.value;
                var petsInp = adults.value;
                window.location.href = '/check-out?checkIn=' +
                    picker.getStartDate().format('YYYY-MM-DD') +
                    '&checkOut=' + picker.getEndDate().format('YYYY-MM-DD') +
                    '&slug=' + slug +
                    '&adults=' + encodeURIComponent(adultsInp) +
                    '&childrens=' + encodeURIComponent(childrensInp) +
                    '&infants=' + encodeURIComponent(infantsInp) +
                    '&pets=' + encodeURIComponent(petsInp) +
                    '&cleaningFee=' + totalPriceData.cleaningFee +
                    '&extraGuestFee=' + extraGuestTotalAmount +
                    '&hotTubFee=' + totalPriceData.hotTubFee +
                    '&petFee=' + petTotalFee +
                    '&property_id=' + property_id +
                    '&nightStayTotalAmount=' + totalPriceData.totalAmount.toFixed(2) +
                    '&paypalFee=' + parseFloat(paypalFee).toFixed(2) +
                    '&taxFee=' + parseFloat(taxFee).toFixed(2) +
                    '&totalAmount=' + parseFloat(totalWithTaxes).toFixed(2);
            });
        } catch (error) {
            // Handle error if needed
            console.error('Error in calculateAndUpdateTotal:', error);
        }
    }
                
    function updateReservedButtonState() {
        if ((parseInt(adults.value) + parseInt(childrens.value)) > 0) {
            $('.done-button').prop('disabled', false);
        } else {
            $('.done-button').prop('disabled', true);
        }
    }

    function bindIncrementDecrementButtons() {
        incrementButtons.forEach(function (button) {
            button.removeEventListener('click', handleIncrement);
            button.addEventListener('click', handleIncrement);
        });

        decrementButtons.forEach(function (button) {
            button.removeEventListener('click', handleDecrement);
            button.addEventListener('click', handleDecrement);
        });
    }

    bindIncrementDecrementButtons();

    async function calculateTotalPrice(picker) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'GET',
                url: '/properties/calculateTotalPrice/' + property_id + '/' + picker.getStartDate().format('YYYY-MM-DD') + '/' + picker.getEndDate().format('YYYY-MM-DD'),
                dataType: 'json',
                success: function (data) {
                    const totalAmount = data.total_price || 0;
                    const cleaningFee = data.cleaning_fee || 0;
                    const extraGuestFee = data.extra_guest_fee || 0;
                    const basicNumberOfGuest = parseInt(data.basic_number_of_guest) || 0;
                    const petFee = data.pet_fee || 0;
                    const hotTubFee = data.hot_tub_fee || 0;
                    const overAllTotalAmount = parseFloat(totalAmount) + parseFloat(cleaningFee) + parseFloat(hotTubFee) || 0;
                    const paypalFee = parseFloat(overAllTotalAmount) * 0.03 || 0;
                    const totalWithPaypal = overAllTotalAmount + paypalFee;
                    const taxFee = parseFloat(totalWithPaypal) * 0.09 || 0;
                    const totalWithTaxes = totalWithPaypal + taxFee;

                    const startDate = picker.getStartDate().format('YYYY-MM-DD');
                    const endDate = picker.getEndDate().format('YYYY-MM-DD');
                    const dateDifference = calculateDateDifference(startDate, endDate);

                    const result = {
                        totalAmount: totalAmount,
                        cleaningFee: cleaningFee,
                        extraGuestFee: extraGuestFee,
                        basicNumberOfGuest: basicNumberOfGuest,
                        petFee: petFee,
                        hotTubFee: hotTubFee,
                        overAllTotalAmount: overAllTotalAmount,
                        dateDifference: dateDifference,
                    };


                    const formattedTotalAmount = totalAmount.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
                    const formattedCleaningFee = '$' + cleaningFee.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
                    const formattedHotTubFee = '$' + hotTubFee.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
                    const formattedOverAllTotalAmount = totalWithTaxes.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
                    const formattedtaxFee = taxFee.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
                    const formattedPaypalFee = paypalFee.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
    
                    $('#totalAmount').html(formattedTotalAmount + ' (x'+ dateDifference +' Nights)');
                    $('#overAllTotalAmount').html(formattedOverAllTotalAmount);
                    $('#cleaningFeeLabel').html(formattedCleaningFee);
                    $('#hotTubFeeLabel').html(formattedHotTubFee);
                    $('#taxFee').html(formattedtaxFee);
                    $('#paypalFee').html(formattedPaypalFee);

                    resolve(result);
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching total amount:', error);
                    reject(error);
                }
            });
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
                    return true;
                }
            }
        }
        return false;
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

    document.addEventListener('click', function (event) {
        const dropdownElement = document.querySelector('.dropdown.guest-details');

        if (!dropdownElement.contains(event.target)) {
            bsDropdown.hide();
        }
    });
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
