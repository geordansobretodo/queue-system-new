<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Cashier Dashboard</title>
    <link rel="icon" href="<?php echo base_url('assets/img/bqslogo1.png'); ?>">
    <style>
        body {
            background-color: #f8f9fa;
            text-decoration: none;
        }

        .container-fluid {
            margin-top: 20px;
        }

        .navbar {
            background-color: #2B2D42;
        }

        .navbar-brand,
        .navbar-nav .nav-link {
            color: #fff;
        }

        .navbar-brand i {
            margin-right: 5px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 20px;
        }

        .queue-info {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .icon {
            font-size: 48px;
        }

        .btn-action {
            width: 100%;
            margin-top: 5px;
            font-size: 12px;
        }

        .btn-no-show {
            width: 100%;
            margin-top: 5px;
            font-size: 12px;
            background-color: #2B2D42;
            border-color: #2B2D42;
        }

        .details-card {
            margin-top: 20px;
        }

        .openDisplayBtn {
            text-decoration: none;
            background-color: #EF2D56;
            color: white;
            font-weight: 500;
            transition: all ease-in-out 250ms;
            outline: none !important;
        }

        .openDisplayBtn:hover {
            text-decoration: none;
            color: white;
            opacity: 0.7;

        }

        .d-none {
            display: none;
        }

        .bold-label {
            font-weight: bold;
        }

        .center-text {
            text-align: center;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#"><i class="fas fa-cash-register"></i> Cashier Dashboard</a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">
                    Welcome, <?php echo $this->session->userdata('name'); ?> <i class="fas fa-user"></i>
                </a>
            </li>
            <li class="nav-item">
                <!-- Logout Form -->
                <a href="<?= base_url('Cashier/logout'); ?>"><button type="submit" name="logout" class="nav-link btn btn-link">Logout <i class="fas fa-sign-out-alt"></i></button></a>
            </li>
        </ul>
    </nav>

    <div class="container-fluid">
        <div class="text-center mb-4">
            <div class="d-grid gap-1 d-md-block ms-auto">
                <button style="background-color: #2B2D42;" onclick="openMonitor()" target="_blank" class="openDisplayBtn btn">Open Customer Display</button>
                <a style="background-color: #2B2D42;" href="<?= base_url('Queue/get_queue'); ?>" target="blank" class="openDisplayBtn btn">Open Queue Registration Page</a>
            </div>


            <!-- serve and notify trigger -->
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="queue-info">
                                    <i class="fas fa-users icon"></i>
                                    <p>Current Queue: <span id="currentQueueNumber"></span></p>
                                    <p hidden>Queue Id: <span id="currentQueueId"></span></p>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <button id="serveNextBtn" class="btn btn-primary btn-action btn-block">
                                            <i class="fas fa-user-check"></i> Serve Regular
                                        </button>
                                    </div>

                                    <div class="col-md-4">
                                        <button id="serveNextClaimingBtn" class="btn btn-info btn-action btn-block">
                                            <i class="fas fa-receipt"></i> Serve Claiming
                                        </button>
                                    </div>

                                    <div class="col-md-4">
                                        <button id="serveNextPriorityBtn" class="btn btn-warning btn-action btn-block">
                                            <i class="fas fa-star"></i> Serve Priority
                                        </button>
                                    </div>

                                    <div class="col-md">
                                        <button id="notifyBtn" class="btn btn-success btn-action btn-block">
                                            <i class="fas fa-bell"></i> Notify
                                        </button>

                                        <!-- Checkbox for showing on monitor, now outside the modal -->
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="checkbox" id="showOnMonitor" name="show_on_monitor" value="1" checked>
                                            <label class="form-check-label" for="showOnMonitor">
                                                Show on Monitor
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="details-card">
                            <h5>Details for the Next Customer:</h5>
                            <!-- Table to display customer details -->
                            <!-- Hidden input to hold the queue ID -->
                            <input type="hidden" name="queue_number" id="queueNumber" value="<?php echo isset($queue_number) ? $queue_number : ''; ?>">
                            <span id="queueStatus"><?php echo isset($status) ? $status : ''; ?></span>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody id="customerDetails">
                                        <!-- Fetched data will be displayed here -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Additional hidden inputs for other data -->
                            <input type="hidden" id="tableID">
                            <input type="hidden" id="queue_table">
                        </div>

                    </div>
                </div>

                <!-- complete and no show trigger -->
                <div class="row justify-content-center mt-3" id="completeDeclineButtons" style="display: none;">
                    <div class="col-md-8">
                        <button id="completeBtn" class="btn btn-success btn-block mb-1">Complete</button>
                    </div>
                    <div class="col-md-8">
                        <button id="noShowBtn" class="btn btn-danger btn-block">No Show</button>
                    </div>
                </div>
            </div>

            <!-- New form for updating to 'served' -->
            <form id="updateServedForm" method="post" action="">
                <input type="hidden" name="trigger_update_served" value="1">
                <input type="submit" style="display: none;"> <!-- Hidden submit button -->
            </form>

            <!-- Modal for transaction completion -->
            <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="successModalLabel">Transaction Completed</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Your transaction has been completed.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for no show -->
            <div class="modal fade" id="noShowModal" tabindex="-1" role="dialog" aria-labelledby="noShowModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="noShowModalLabel">Transaction Completed</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>The customer has been marked as "No Show."</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for action required -->
            <div class="modal fade" id="incompleteServiceModal" tabindex="-1" role="dialog" aria-labelledby="incompleteServiceModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="incompleteServiceModalLabel">Action Required</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Please make sure the currently being served customer is marked as "served" or "no show" first.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New form for updating to 'no show' -->
            <form id="updateNoShowForm" method="post" action=""
                <input type="hidden" name="trigger_update_no_show" value="1">
                <input type="submit" style="display: none;"> <!-- Hidden submit button -->
            </form>


            <!-- Modal for transaction completion -->
            <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="successModalLabel">Transaction Completed</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Your transaction has been completed.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Notify no serving Modal -->
            <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="completeModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="completeModalLabel">Action Required</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Please make sure there are currently served customer.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Notify Modal -->
            <div class="modal fade" id="successNotifyModal" tabindex="-1" role="dialog" aria-labelledby="completeModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="completeModalLabel">Customer Notified!</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Notified Successfully</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Error already notified Modal -->
            <div class="modal fade" id="errorNotifiedModal" tabindex="-1" role="dialog" aria-labelledby="completeModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="completeModalLabel">Already Notified</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>The customer is already notified.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <audio id="myAudio">
                <source src="<?= base_url('assets/sound/bell-sound.mp3'); ?>" type="audio/mp3">
                Your browser does not support the audio element.
            </audio>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <!-- buttons -->
    <script>
        let monitor;

        function openMonitor() {
            monitor = window.open('<?= base_url('Queue/monitor'); ?>');
        }
        // Function to announce the current customer queue number and cashier using text-to-speech
        function announceQueueNumber(queueNumber, cashierName) {
            // Check if SpeechSynthesis is supported by the browser
            if ('speechSynthesis' in window) {
                var msg = new SpeechSynthesisUtterance();
                msg.text = 'Customer ' + queueNumber + ', please go to Cashier ' + cashierName;
                window.speechSynthesis.speak(msg);
            } else {
                // Fallback for browsers that do not support SpeechSynthesis
                alert('Text-to-speech is not supported in this browser.');
            }
        }

        // Function to play audio
        function playAudio(cashierName) {
            // Check if the currentQueueNumber is not 'N/A'
            var currentQueueNumber = $('#currentQueueNumber').text().trim();
            var showOnMonitor = $('#showOnMonitor').is(':checked'); // Check the state of the checkbox

            if (currentQueueNumber && currentQueueNumber !== 'N/A') {
                if (!showOnMonitor) {
                    // If checkbox is unchecked, play audio in the current window
                    announceQueueNumber(currentQueueNumber, cashierName);
                } else if (monitor && !monitor.closed) {
                    // If checkbox is checked, play audio on the monitor window (if monitor is open)
                    monitor.announceQueueNumber(currentQueueNumber, cashierName);
                }
                updatenotifyqueue($('#tableID').val(), $('#queue_table').val());
            }
        }

        // Function to update notify_queue column
        function updatenotifyqueue(tableID, queueTable) {
            var id = $('#currentQueueId').text().trim();
            $.post('<?php echo base_url('Cashier/update_notify_status'); ?>', {
                id: id,
                notify_value: 1 // This sets notify_queue to 1
            }, function(response) {
                console.log("Notify status update response:", response);
            }, 'json');
        }


        //"Notify" button click event
        $(document).ready(function() {
            var button = document.getElementById('notifyBtn');
            var cashierName = '<?php echo htmlspecialchars(($this->session->userdata("name")) ? $this->session->userdata("name") : "Unknown", ENT_QUOTES, 'UTF-8'); ?>';
            var cashierId = '<?php echo $this->session->userdata("id"); ?>';
            $('#notifyBtn').click(function() {
                playAudio(cashierName);
                var id = $('#currentQueueId').text(); // Assuming queue number is displayed in the UI
                var showOnMonitor = $('#showOnMonitor').is(':checked'); // Get the checkbox value

                // Call the playAudio function
                playAudio(cashierName);

                // Send an AJAX POST request to update the notify_queue column
                $.post('<?php echo base_url('Cashier/update_notify_status'); ?>', {
                        id: id,
                        show_on_monitor: showOnMonitor ? 1 : 0,
                    }, function(response) {
                        console.log("Notify status update response:", response);
                        console.log("Response Type:", typeof response); // Log the type of the response

                        if (response && response.status) { // Check if response and status exist
                            if (response.status === 'success') {
                                console.log('Notify status updated successfully.');
                                $('#successNotifyModal').modal('show'); // Show the success modal
                            } else if (response.status === 'noop') {
                                $('#errorNotifiedModal').modal('show'); // Show the "No operation performed" modal
                            } else if (response.status === 'no_data') {
                                // If no data is fetched, show the error modal
                                $('#errorModal').modal('show'); // Show the error modal
                            } else {
                                console.error('Error:', response.message || 'Unknown error occurred.');
                                $('#errorModal').modal('show'); // Show the error modal
                            }
                        } else {
                            console.error('Error: Invalid response format.');
                            alert('Error: Invalid response format.');
                            $('#errorModal').modal('show');
                        }
                    }, 'json')
                    .fail(function(xhr, status, error) {
                        console.error("Error:", error);
                        console.error("Response Text:", xhr.responseText);
                        $('#errorModal').modal('show'); // Show the error modal
                    });
            });
        });

        // After the modal is closed, check if "Show on Monitor" is checked and act accordingly
        $('#successNotifyModal').on('hidden.bs.modal', function() {
            var showOnMonitor = $('#showOnMonitor').is(':checked'); // Get the checkbox value
            var cashierName = '<?php echo $this->session->userdata('cashier_name'); ?>';

            if (showOnMonitor) {
                var id = $('#currentQueueId').text();
                // If checked, send data to display on monitor
                console.log('Showing on monitor...');

                // Example AJAX request to update the monitor display (modify URL and data as needed)
                $.post('<?php echo base_url('Cashier/show_on_monitor'); ?>', {
                        id: id,
                        cashier_name: cashierName,
                        cashier_id: cashierId
                    }, function(response) {
                        if (response && response.status === 'success') {
                            console.log('Successfully updated the monitor display.');
                        } else {
                            console.error('Error showing on monitor:', response.message || 'Unknown error occurred.');
                        }
                    }, 'json')
                    .fail(function(xhr, status, error) {
                        console.error("Monitor display error:", error);
                        console.error("Response Text:", xhr.responseText);
                    });
            } else {
                console.log('Not showing on monitor.');
            }
        });


        // <!-- serve next button -->
        $(document).ready(function() {
            let isServing = false; // Flag to track if a customer is being served

            $('#serveNextBtn').click(function() {
                let isServing = false; // Flag to track if a customer is being served

                // Check if customer details are already displayed (i.e., if a customer is being served)
                var currentQueueNumber = $('#currentQueueNumber').text().trim();
                var button = document.getElementById('notifyBtn');

                if (currentQueueNumber !== '' && currentQueueNumber !== 'N/A') {
                    // If there is customer data (a customer is being served), show the modal
                    $('#incompleteServiceModal').modal('show');
                } else {
                    // If no customer data is present, fetch the next customer details
                    let cashierId = <?= $this->session->userdata('id'); ?>; // Ensure this is set correctly
                    $.ajax({
                        url: "<?= base_url('Cashier/fetch_details'); ?>",
                        method: "POST",
                        dataType: "json",
                        data: {
                            cashier_id: cashierId
                        },
                        success: function(data) {
                            console.log('Cashier ID sent: ', cashierId); // Log the cashier ID being sent
                            console.log('Response:', data); // Log the server response

                            if (data.status === 'success' || data.status === 'serving') {
                                $('#customerDetails').html(
                                    '<tr><td class="bold-label center-text">Name</td><td class="center-text">' + data.data.name + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Student Number</td><td class="center-text">' + data.data.student_number + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Service Type</td><td class="center-text">' + data.data.service_type + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Payment For</td><td class="center-text">' + data.data.payment_for + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Payment Mode</td><td class="center-text">' + data.data.payment_mode + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Email</td><td class="center-text">' + data.data.email + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Queue Type</td><td class="center-text">' + data.data.queue_type + '</td></tr>'
                                );

                                $('#currentQueueId').text(data.data.id);
                                $('#currentQueueNumber').text(data.data.queue_number);
                                $('#detailsTableContainer').removeClass('d-none');
                                $('#completeDeclineButtons').show();

                                // Set the flag to true since a customer is now being served
                                isServing = true;
                            } else if (data.status === 'already_serving' || data.status === 'serving') {
                                // Similarly, save the data if already serving
                                $('#customerDetails').html(
                                    '<tr><td class="bold-label center-text">Name</td><td class="center-text">' + data.data.name + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Student Number</td><td class="center-text">' + data.data.student_number + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Service Type</td><td class="center-text">' + data.data.service_type + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Payment For</td><td class="center-text">' + data.data.payment_for + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Payment Mode</td><td class="center-text">' + data.data.payment_mode + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Email</td><td class="center-text">' + data.data.email + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Queue Type</td><td class="center-text">' + data.data.queue_type + '</td></tr>'
                                );
                                $('#currentQueueId').text(data.data.id);
                                $('#currentQueueNumber').text(data.data.queue_number);
                                $('#detailsTableContainer').removeClass('d-none');
                                $('#completeDeclineButtons').show();
                            } else {
                                $('#customerDetails').html('<tr><td colspan="2">No pending customers found.</td></tr>');
                                $('#detailsTableContainer').removeClass('d-none');
                                $('#completeDeclineButtons').hide();
                                $('#currentQueueId').text('');
                                $('#currentQueueNumber').text('');
                                isServing = false;
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('AJAX Error:', error); // Log the error
                            console.log('AJAX Status:', status); // Log the status
                            console.log('XHR Object:', xhr); // Log the XHR object for debugging
                            alert('Failed to fetch details.');
                        }
                    });
                }
            });

            $('#serveNextClaimingBtn').click(function() {
                let isServing = false; // Flag to track if a customer is being served

                // Check if customer details are already displayed (i.e., if a customer is being served)
                var currentQueueNumber = $('#currentQueueNumber').text().trim();
                var button = document.getElementById('notifyBtn');

                if (currentQueueNumber !== '' && currentQueueNumber !== 'N/A') {
                    // If there is customer data (a customer is being served), show the modal
                    $('#incompleteServiceModal').modal('show');
                } else {
                    // If no customer data is present, fetch the next customer details
                    var cashierId = <?= $this->session->userdata('id'); ?>; // Ensure this is set correctly
                    $.ajax({
                        url: "<?= base_url('Cashier/fetch_details_claiming'); ?>",
                        method: "POST",
                        dataType: "json",
                        data: {
                            cashier_id: cashierId,
                        },
                        success: function(data) {
                            console.log('Cashier ID sent: ', cashierId); // Log the cashier ID being sent
                            console.log('Response:', data); // Log the server response

                            if (data.status === 'success' || data.status === 'serving') {
                                $('#customerDetails').html(
                                    '<tr><td class="bold-label center-text">Name</td><td class="center-text">' + data.data.name + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Student Number</td><td class="center-text">' + data.data.student_number + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Service Type</td><td class="center-text">' + data.data.service_type + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Payment For</td><td class="center-text">' + data.data.payment_for + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Payment Mode</td><td class="center-text">' + data.data.payment_mode + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Email</td><td class="center-text">' + data.data.email + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Queue Type</td><td class="center-text">' + data.data.queue_type + '</td></tr>'
                                );

                                $('#currentQueueId').text(data.data.id);
                                $('#currentQueueNumber').text(data.data.queue_number);
                                $('#detailsTableContainer').removeClass('d-none');
                                $('#completeDeclineButtons').show();

                                // Set the flag to true since a customer is now being served
                                isServing = true;
                            } else if (data.status === 'already_serving' || data.status === 'serving') {
                                // Similarly, save the data if already serving
                                $('#customerDetails').html(
                                    '<tr><td class="bold-label center-text">Name</td><td class="center-text">' + data.data.name + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Student Number</td><td class="center-text">' + data.data.student_number + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Service Type</td><td class="center-text">' + data.data.service_type + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Payment For</td><td class="center-text">' + data.data.payment_for + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Payment Mode</td><td class="center-text">' + data.data.payment_mode + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Email</td><td class="center-text">' + data.data.email + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Queue Type</td><td class="center-text">' + data.data.queue_type + '</td></tr>'
                                );
                                $('#currentQueueId').text(data.data.id);
                                $('#currentQueueNumber').text(data.data.queue_number);
                                $('#detailsTableContainer').removeClass('d-none');
                                $('#completeDeclineButtons').show();
                            } else {
                                $('#customerDetails').html('<tr><td colspan="2">No pending customers found.</td></tr>');
                                $('#detailsTableContainer').removeClass('d-none');
                                $('#completeDeclineButtons').hide();
                                $('#currentQueueId').text('');
                                $('#currentQueueNumber').text('');
                                isServing = false;
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('AJAX Error:', error); // Log the error
                            console.log('AJAX Status:', status); // Log the status
                            console.log('XHR Object:', xhr); // Log the XHR object for debugging
                            alert('Failed to fetch details.');
                        }
                    });
                }
            });

            $('#serveNextPriorityBtn').click(function() {
                let isServing = false; // Flag to track if a customer is being served

                // Check if customer details are already displayed (i.e., if a customer is being served)
                var currentQueueNumber = $('#currentQueueNumber').text().trim();
                var button = document.getElementById('notifyBtn');

                if (currentQueueNumber !== '' && currentQueueNumber !== 'N/A') {
                    // If there is customer data (a customer is being served), show the modal
                    $('#incompleteServiceModal').modal('show');
                } else {
                    // If no customer data is present, fetch the next customer details
                    var cashierId = <?= $this->session->userdata('id'); ?>; // Ensure this is set correctly
                    $.ajax({
                        url: "<?= base_url('Cashier/fetch_details_priority'); ?>",
                        method: "POST",
                        dataType: "json",
                        data: {
                            cashier_id: cashierId,
                        },
                        success: function(data) {
                            console.log('Cashier ID sent: ', cashierId); // Log the cashier ID being sent
                            console.log('Response:', data); // Log the server response

                            if (data.status === 'success' || data.status === 'serving') {
                                $('#customerDetails').html(
                                    '<tr><td class="bold-label center-text">Name</td><td class="center-text">' + data.data.name + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Student Number</td><td class="center-text">' + data.data.student_number + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Service Type</td><td class="center-text">' + data.data.service_type + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Payment For</td><td class="center-text">' + data.data.payment_for + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Payment Mode</td><td class="center-text">' + data.data.payment_mode + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Email</td><td class="center-text">' + data.data.email + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Queue Type</td><td class="center-text">' + data.data.queue_type + '</td></tr>'
                                );

                                $('#currentQueueId').text(data.data.id);
                                $('#currentQueueNumber').text(data.data.queue_number);
                                $('#detailsTableContainer').removeClass('d-none');
                                $('#completeDeclineButtons').show();

                                // Set the flag to true since a customer is now being served
                                isServing = true;
                            } else if (data.status === 'already_serving' || data.status === 'serving') {
                                // Similarly, save the data if already serving
                                $('#customerDetails').html(
                                    '<tr><td class="bold-label center-text">Name</td><td class="center-text">' + data.data.name + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Student Number</td><td class="center-text">' + data.data.student_number + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Service Type</td><td class="center-text">' + data.data.service_type + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Payment For</td><td class="center-text">' + data.data.payment_for + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Payment Mode</td><td class="center-text">' + data.data.payment_mode + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Email</td><td class="center-text">' + data.data.email + '</td></tr>' +
                                    '<tr><td class="bold-label center-text">Queue Type</td><td class="center-text">' + data.data.queue_type + '</td></tr>'
                                );
                                $('#currentQueueId').text(data.data.id);
                                $('#currentQueueNumber').text(data.data.queue_number);
                                $('#detailsTableContainer').removeClass('d-none');
                                $('#completeDeclineButtons').show();
                            } else {
                                $('#customerDetails').html('<tr><td colspan="2">No pending customers found.</td></tr>');
                                $('#detailsTableContainer').removeClass('d-none');
                                $('#completeDeclineButtons').hide();
                                $('#currentQueueId').text('');
                                $('#currentQueueNumber').text('');
                                isServing = false;
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('AJAX Error:', error); // Log the error
                            console.log('AJAX Status:', status); // Log the status
                            console.log('XHR Object:', xhr); // Log the XHR object for debugging
                            alert('Failed to fetch details.');
                        }
                    });
                }
            });

            // Bind click events to the buttons after they are shown
            $(document).on('click', '#completeBtn, #noShowBtn', function() {
                let isServing = false; // Flag to track if a customer is being served

                // Your button actions here
                if ($(this).attr('id') === 'completeBtn') {
                    console.log('Complete button clicked');
                    // Handle complete action
                } else if ($(this).attr('id') === 'noShowBtn') {
                    console.log('No Show button clicked');
                    // Handle no show action
                }

                // Reset the state
                $('#completeDeclineButtons').hide();
                $('#currentQueueId').text('');
                $('#currentQueueNumber').text('');
                $('#customerDetails').html('');
                $('#detailsTableContainer').addClass('d-none');

                isServing = false;
            });
        });

        // <!-- complete button -->
        $(document).ready(function() {
            $('#completeBtn').click(function() {
                var id = $('#currentQueueId').text(); // Get the queue number from the UI
                var email = $('#customerDetails').find('td:contains("Email")').next().text().trim(); // Get the email
                var button = document.getElementById('notifyBtn');

                console.log("Complete button clicked");
                $(this).prop('disabled', true); // Disable the button to prevent multiple clicks

                // Send POST request to update the status to 'served'
                $.post('<?php echo base_url('Cashier/update_status_complete'); ?>', {
                        id: id,
                        email: email,
                    }, function(response) {
                        console.log("Server response:", response);

                        if (response.status === 'success') {
                            $('#successModal').modal('show'); // Show the success modal
                            $('#currentQueueId').text('N/A');
                            $('#currentQueueNumber').text('N/A');
                            fetchCustomerDetails(); // Refresh the customer details after marking as served
                        } else {
                            alert('Error: ' + response.message); // Show an error message if the update fails
                        }
                    }, 'json')
                    .fail(function(xhr, status, error) {
                        console.error("Error:", error);
                        console.error("Response Text:", xhr.responseText);
                    })
                    .always(function() {
                        $('#completeBtn').prop('disabled', false); // Re-enable the button
                    });
            });
        });

        // <!-- no show button -->
        $(document).ready(function() {
            let isServing = false;
            $('#noShowBtn').click(function() {
                var id = $('#currentQueueId').text(); // Get the queue number from the UI
                var email = $('#customerDetails').find('td:contains("Email")').next().text().trim(); // Get the email
                var button = document.getElementById('notifyBtn');

                console.log("No Show button clicked");
                $(this).prop('disabled', true); // Disable the button to prevent multiple clicks

                // Send POST request to update the status to 'served'
                $.post('<?php echo base_url('Cashier/update_status_no_show'); ?>', {
                        id: id,
                        email: email,
                    }, function(response) {
                        console.log("Server response:", response);

                        if (response.status === 'success') {
                            $('#noShowModal').modal('show'); // Show the success modal
                            $('#currentQueueId').text('N/A');
                            $('#currentQueueNumber').text('N/A');
                            fetchCustomerDetails(); // Refresh the customer details after marking as served
                        } else {
                            alert('Error: ' + response.message); // Show an error message if the update fails
                        }
                    }, 'json')
                    .fail(function(xhr, status, error) {
                        console.error("Error:", error);
                        console.error("Response Text:", xhr.responseText);
                    })
                    .always(function() {
                        $('#noShowBtn').prop('disabled', false); // Re-enable the button
                    });
            });
        });

        // Handle the modal close event to refresh the UI
        $('#incompleteServiceModal').on('hidden.bs.modal', function() {
            if (isServing) {
                // Re-fetch the customer details only if a customer is being served
                $.ajax({
                    url: "<?= base_url('Cashier/fetch_details'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        cashier_id: cashierId,
                    },
                    success: function(data) {

                        if (data.status === 'success' || data.status === 'serving') {
                            $('#customerDetails').html(
                                '<tr><td class="bold-label">Name</td><td>' + data.data.name + '</td></tr>' +
                                '<tr><td class="bold-label">Student Number</td><td>' + data.data.student_number + '</td></tr>' +
                                '<tr><td class="bold-label">Service Type</td><td>' + data.data.service_type + '</td></tr>' +
                                '<tr><td class="bold-label">Payment For</td><td>' + data.data.payment_for + '</td></tr>' +
                                '<tr><td class="bold-label">Payment Mode</td><td>' + data.data.payment_mode + '</td></tr>' +
                                '<tr><td class="bold-label">Email</td><td>' + data.data.email + '</td></tr>' +
                                '<tr><td class="bold-label">Queue Type</td><td>' + data.data.queue_type + '</td></tr>'
                            );

                            $('#currentQueueId').text(data.data.id);
                            $('#currentQueueNumber').text(data.data.queue_number);
                            $('#detailsTableContainer').removeClass('d-none');
                            $('#completeDeclineButtons').show();
                        } else {
                            $('#customerDetails').html('<tr><td colspan="2">' + data.message + '</td></tr>');
                            $('#detailsTableContainer').removeClass('d-none');
                            $('#completeDeclineButtons').hide();
                            $('#currentQueueId').text('');
                            $('#currentQueueNumber').text('');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error:', error);
                        console.log('AJAX Status:', status);
                        console.log('XHR Object:', xhr);
                        alert('Failed to fetch details.');
                    }
                });
            }
        });

        function fetchCustomerDetails() {
            // Function to fetch the latest customer details and update the UI
            $.ajax({
                url: "<?= base_url('Cashier/fetch_details'); ?>",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.status !== 'error') {
                        // Update the customer details table with the new data
                        $('#customerDetails').html(
                            '<tr><td class="bold-label">Name</td><td>' + data.name + '</td></tr>' +
                            '<tr><td class="bold-label">Student Number</td><td>' + data.student_number + '</td></tr>' +
                            '<tr><td class="bold-label">Service Type</td><td>' + data.service_type + '</td></tr>' +
                            '<tr><td class="bold-label">Payment For</td><td>' + data.payment_for + '</td></tr>' +
                            '<tr><td class="bold-label">Payment Mode</td><td>' + data.payment_mode + '</td></tr>' +
                            '<tr><td class="bold-label">Email</td><td>' + data.email + '</td></tr>' +
                            '<tr><td class="bold-label">Queue Type</td><td>' + data.queue_type + '</td></tr>'
                        );

                        // Update the current queue number display
                        $('#currentQueueId').text(data.data.id);
                        $('#currentQueueNumber').text(data.queue_number);

                        // Show the updated table and buttons
                        $('#detailsTableContainer').removeClass('d-none');
                        $('#completeDeclineButtons').show(); // Ensure buttons are shown
                    } else {
                        $('#customerDetails').html('<tr><td colspan="2">No pending customer.</td></tr>');
                        $('#detailsTableContainer').removeClass('d-none');
                        $('#completeDeclineButtons').hide(); // Ensure buttons are hide
                    }
                },
                error: function() {
                    alert('Failed to fetch details.');
                }
            });
        }
    </script>
</body>

</html>