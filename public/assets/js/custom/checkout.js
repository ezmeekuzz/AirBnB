$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const nightStayTotalAmount = urlParams.get('nightStayTotalAmount');
    const cleaningFee = urlParams.get('cleaningFee');
    const extraGuestFee = urlParams.get('extraGuestFee');
    const hotTubFee = urlParams.get('hotTubFee');
    const petFee = urlParams.get('petFee');
    const checkIn = urlParams.get('checkIn');
    const checkOut = urlParams.get('checkOut');
    const totalAmount = urlParams.get('totalAmount');
    const adults = urlParams.get('adults');
    const childrens = urlParams.get('childrens');
    const infants = urlParams.get('infants');
    const pets = urlParams.get('pets');
    const property_id = urlParams.get('property_id');
    const slug = urlParams.get('slug');

    // Convert the date strings to Date objects
    const checkInDate = new Date(checkIn);
    const checkOutDate = new Date(checkOut);

    // Calculate the difference in milliseconds
    const timeDifference = checkOutDate - checkInDate;

    // Convert the time difference to days (assuming 1 day = 24 hours)
    const daysDifference = timeDifference / (1000 * 60 * 60 * 24);

    function formatMoney(number, decPlaces = 2, decSep = '.', thouSep = ',') {
        var sign = number < 0 ? "-" : "";
        var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
        var j = (j = i.length) > 3 ? j % 3 : 0;

        return sign +
            (j ? i.substr(0, j) + thouSep : "") +
            i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSep) +
            (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
    }

    $('#nightStayTotalAmountLabel').html(formatMoney(nightStayTotalAmount));
    $('#cleaningFeeLabel').html(formatMoney(cleaningFee));
    $('#extraGuestFeeLabel').html(formatMoney(extraGuestFee));
    $('#hotTubFeeLabel').html(formatMoney(hotTubFee));
    $('#petFeeLabel').html(formatMoney(petFee));
    $('#nightStay').html(daysDifference);
    $('#totalAmountLabel').html(formatMoney(totalAmount));

    paypal.Buttons({
        createOrder: (data, actions) => {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: totalAmount
                    }
                }]
            });
        },
        onApprove: (data, actions) => {
            return actions.order.capture().then(function(orderData) {
                const transaction = orderData.purchase_units[0].payments.captures[0];
                var transaction_number = transaction.id;
                var email = orderData.payer.email_address;
                // Create FormData
                var formData = new FormData();
                formData.append('property_id', property_id);
                formData.append('cleaningFee', cleaningFee);
                formData.append('extraGuestFee', extraGuestFee);
                formData.append('hotTubFee', hotTubFee);
                formData.append('petFee', petFee);
                formData.append('checkIn', checkIn);
                formData.append('checkOut', checkOut);
                formData.append('totalAmount', totalAmount);
                formData.append('adults', adults);
                formData.append('childrens', childrens);
                formData.append('infants', infants);
                formData.append('pets', pets);
                formData.append('nightStayTotalAmount', nightStayTotalAmount);
                formData.append('transaction_number', transaction_number);
                formData.append('email', email);

                // Send AJAX request to insert data into the database
                $.ajax({
                    type: "POST",
                    url: 'checkout/submitBooking',
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(data) {
                        iziToast.success({
                            title: 'You successfully booked your stay!',
                            timeout: 2000,
                        });
                        setTimeout('window.location.href = "/' + slug + '"', 2000);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        iziToast.error({
                            title: xhr.responseText,
                            timeout: 2000,
                        });
                    }
                });
            });
        },

        onCancel: function(data) {
            Swal.fire({
                title: 'Warning!',
                text: 'You cancelled your payment!',
                icon: 'warning',
            });
        }
    }).render('#paypalButton');
});
