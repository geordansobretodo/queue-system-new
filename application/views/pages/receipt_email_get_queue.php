<html>
    <head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        h1 {
            color: #333;
            margin: 0 0 10px 0;
        }

        p {
            color: #555;
            margin: 0 0 10px 0;
        }

        h4 {
            margin: 0 0 10px 0;
        }

        .details {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        .details th,
        .details td {
            padding: 8px 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .details th {
            background-color: #f0f0f0;
        }

        .details td {
            text-transform: capitalize;
            font-weight: normal;
        }

        .logo {
            width: 80px;
            height: auto;
        }
    </style>
    </head>
    <body>
        <div class="container">
            <h1><img src="https://stdominiccollege.edu.ph/images/sdcalogo.png" style="width:80px;height:auto;" alt="logo-header" border="0"> Notification: Your Appointment</h1>
            <p>Dear Customer, <b><?= htmlspecialchars($name) ?></b></p>
            <p>Please take note of the Transaction Details. Pay attention as your queue will be called.</p><br>
            <p>Details of your transaction are as follows:</p>
            <table class="details">
                <tr>
                    <th>QUEUE NUMBER:</th>
                    <td><?= htmlspecialchars($queue_number) ?></td>
                </tr>
                <tr>
                    <th>Student Number:</th>
                    <td><?= htmlspecialchars($studentNumber) ?></td>
                </tr>
                <tr>
                    <th>Service Type:</th>
                    <td><?= htmlspecialchars($selectedService) ?></td>
                </tr>
                <tr>
                    <th>Payment For:</th>
                    <td><?= htmlspecialchars($selectedPaymentFor) ?></td>
                </tr>
                <tr>
                    <th>Payment Mode:</th>
                    <td><?= htmlspecialchars($selectedPaymentMode) ?></td>
                </tr>
                <tr>
                    <th>Queue Time:</th>
                    <td><?= htmlspecialchars($queue_time) ?></td>
                </tr>
            </table><br>
            <p>Thank you and God bless.</p>
        </div>
    </body>
</html>