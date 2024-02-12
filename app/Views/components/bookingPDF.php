<!-- app/Views/pdf_view.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
        }

        .additional-details {
            margin-top: 20px;
        }

        .bold-label {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Booking Details</h1>
        </div>

        <table>
            <tbody>
                <tr>
                    <th>Email</th>
                    <td><?= $bresult[0]['emailaddress']; ?></td>
                </tr>
                <tr>
                    <th>Date Range</th>
                    <td><?= date('F d, Y', strtotime($bresult[0]['checkin_date'])) . ' - ' . date('F d, Y', strtotime($bresult[0]['checkout_date'])); ?></td>
                </tr>
                <tr>
                    <th>Booking ID</th>
                    <td><?= $bresult[0]['booking_id']; ?></td>
                </tr>
            </tbody>
        </table>

        <div class="additional-details">
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Adult</td>
                        <td><?= $bresult[0]['adult']; ?></td>
                    </tr>
                    <tr>
                        <td>Children</td>
                        <td><?= $bresult[0]['children']; ?></td>
                    </tr>
                    <tr>
                        <td>Infant</td>
                        <td><?= $bresult[0]['infant']; ?></td>
                    </tr>
                    <tr>
                        <td>Pet</td>
                        <td><?= $bresult[0]['pet']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="additional-details">
            <table>
                <thead>
                    <tr>
                        <th>Fee Type</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Cleaning Fee</td>
                        <td>$<?= number_format($bresult[0]['cleaningfee'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Extra Guest Fee</td>
                        <td>$<?= number_format($bresult[0]['extraguestfee'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Hot Tub Fee</td>
                        <td>$<?= number_format($bresult[0]['hottubfee'], 2); ?></td>
                    </tr>
                    <tr>
                        <td>Pet Fee</td>
                        <td>$<?= number_format($bresult[0]['petfee'], 2); ?></td>
                    </tr>
                    <tr style="background: yellow;">
                        <td>Total Amount</td>
                        <td><b>$<?= number_format($bresult[0]['totalamount'], 2); ?></b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
