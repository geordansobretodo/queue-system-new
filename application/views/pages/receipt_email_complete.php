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
            <h1>
                <img src="https://stdominiccollege.edu.ph/images/sdcalogo.png" alt="logo-header" class="logo">
                Notification: Transaction Complete!
            </h1>
            <p>Dear Customer, <b><?= htmlspecialchars($name) ?></b></p>
            <p>Thank you for patiently waiting, your transaction process has been successful.</p><br>
            <p>Details of completed transaction:</p>
            <table class="details">
                <tr>
                    <th>Queue Number:</th>
                    <td><b><?= htmlspecialchars($queue_number) ?></b></td>
                </tr>
                <tr>
                    <th>Queue Type:</th>
                    <td><?= htmlspecialchars($queue_type) ?></td>
                </tr>
                <tr>
                    <th>Student Number:</th>
                    <td><?= htmlspecialchars($student_number) ?></td>
                </tr>
                <tr>
                    <th>Service Type:</th>
                    <td><?= htmlspecialchars($service_type) ?></td>
                </tr>
                <tr>
                    <th>Payment For:</th>
                    <td><?= htmlspecialchars($payment_for) ?></td>
                </tr>
                <tr>
                    <th>Payment Mode:</th>
                    <td><?= htmlspecialchars($payment_mode) ?></td>
                </tr>
                <tr>
                    <th>Start Service Time:</th>
                    <td><?= htmlspecialchars($start_service_time) ?></td>
                </tr>
                <tr>
                    <th>End Service Time:</th>
                    <td><?= htmlspecialchars($end_service_time) ?></td>
                </tr>
            </table><br>
            <p>Thank you and God bless.</p>
        </div>
    </body>
</html>