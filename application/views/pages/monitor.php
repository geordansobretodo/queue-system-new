<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue-ease</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="<?= base_url('assets/img/sdcafafa.jpg'); ?>">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style include>
        .custom-border {
            height: 100vh;
            background-color: #212529;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .sub-column {
            width: 100%;
            border: 1px solid #6c757d;
            padding: 20px;
            margin-bottom: 20px;
            background-color: rgba(52, 58, 64, 0.7);
            border-radius: 8px;
            height: calc(100% / 1);
            text-align: center;
            font-size: x-large;
        }

        .sub-column2 {
            width: 100%;
            border: 1px solid #6c757d;
            padding: 20px;
            margin-bottom: 20px;
            background-color: rgba(52, 58, 64, 0.7);
            border-radius: 8px;
            height: calc(100% / 1);
            text-align: center;

        }

        .sub-column,
        .sub-column2 {
            margin: 0;
            overflow: hidden;
        }

        .sub-column h3 {
            color: #FFC917;
            font-size: 55px;
        }

        .sub-column2 h3 {
            color: #dc3545;
            font-size: 55px;
        }

        .sub-column p,
        .sub-column2 p {
            color: #ffffff;
        }

        .sub-column h4 {
            color: #fff;
            font-size: 50px;
        }

        #counter1NowServing,
        #counter2NowServing {
            color: #fff;
            font-size: 30px;
        }

        .serving-customers {
            color: #fff;
            font-size: 50px;
            list-style: none;
        }

        li {
            list-style: none;
        }

        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .animation-background {
            background: linear-gradient(-45deg, #3D0000, #7A0000, #CD0B0B, #FF0022);
            background-size: 400% 400%;
            animation: gradientAnimation 10s ease infinite;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col custom-border d-flex flex-column align-items-center justify-content-center animation-background">
                <div class="sub-column">
                    <h3>PENDING QUEUES</h3>
                    <br><br>
                    <h4 id="queueNumber1">--</h4>
                    <br><br>
                    <h4 id="queueNumber2">--</h4>
                    <br><br>
                    <h4 id="queueNumber3">--</h4>
                    <br><br>
                    <h4 id="queueNumber4">--</h4>
                    <br><br>
                    <h4 id="queueNumber5">--</h4>
                    <br><br>
                    <h4 id="queueNumber6">--</h4>
                    <br><br>
                    <h4 id="queueNumber7">--</h4>
                </div>
            </div>

            <div class="col custom-border d-flex flex-column align-items-center justify-content-center">
                <div class="sub-column">
                    <h3>NOW SERVING</h3>
                    <br><br>
                    <h4 id="servingNumber1">--</h4>
                    <br><br>
                    <h4 id="servingNumber2">--</h4>
                    <br><br>
                    <h4 id="servingNumber3">--</h4>
                    <br><br>
                    <h4 id="servingNumber4">--</h4>
                    <br><br>
                    <h4 id="servingNumber5">--</h4>
                    <br><br>
                    <h4 id="servingNumber6">--</h4>
                    <br><br>
                    <h4 id="servingNumber7">--</h4>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        $(document).ready(function() {

            function announceQueueNumber(queueNumber, cashierName) {

                if ('speechSynthesis' in window) {
                    var msg = new SpeechSynthesisUtterance();
                    msg.text = 'Customer ' + queueNumber + ', please go to Cashier:' + cashierName;
                    window.speechSynthesis.speak(msg);
                    // meSpeak.speak(text, { amplitude: 100, wordgap: 0, pitch: 50, speed: 150 });
                } else {
                    // alert('Text-to-speech is not supported in this browser.');
                }
            }

            function playAudio(queueNumber, cashierName, idrow, queueType) {
                // if (notify_queue == 1) {
                announceQueueNumber(queueNumber, cashierName);
                $.ajax({
                    url: "<?php echo base_url('Queue/fetch_notify_queue'); ?>",
                    method: 'POST',
                    data: {
                        queue_type: queueType,
                        id: idrow,
                        notify_queue: 0
                    },
                    success: function(response) {
                        console.log('Notification status updated successfully.');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating notification status:', error);
                    }
                });
                // }
            }

            function updateServing() {
                $.ajax({
                    url: '<?= base_url('Queue/fetch_current_serving'); ?>',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log('Fetching serving queue data...');
                        if (data && data.priority && data.regular) {
                            let combinedQueue = data.priority.concat(data.regular);

                            for (let i = 0; i < 7; i++) {
                                if (i < combinedQueue.length) {
                                    let queueItem = combinedQueue[i];
                                    $('#servingNumber' + (i + 1)).text(queueItem.queue_number + ' ' + queueItem.cashier_name || '--');
                                    if (queueItem.notify_queue == 1) {
                                        playAudio(queueItem.queue_number, queueItem.cashier_name, queueItem.id, queueItem.queue_type);
                                        // announceQueueNumber(queueItem.queue_number, queueItem.cashier_name);
                                    }
                                } else {
                                    $('#servingNumber' + (i + 1)).text('--');
                                }
                            }
                        } else {
                            console.error('Invalid data format');
                            $('[id^="queueNumber"]').text('--');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching pending queue data:', error);
                        $('[id^="queueNumber"]').text('--');
                    }
                });
            }

            function updatePending() {
                $.ajax({
                    url: '<?= base_url('Queue/fetch_pending_queue'); ?>',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log('Fetching pending queue data...');
                        if (data && data.priority && data.regular) {
                            let combinedQueue = data.priority.concat(data.regular);

                            for (let i = 0; i < 7; i++) {
                                if (i < combinedQueue.length) {
                                    $('#queueNumber' + (i + 1)).text(combinedQueue[i].queue_number || '--');
                                } else {
                                    $('#queueNumber' + (i + 1)).text('--');
                                }
                            }
                        } else {
                            console.error('Invalid data format');
                            $('[id^="queueNumber"]').text('--');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching pending queue data:', error);
                        $('[id^="queueNumber"]').text('--');
                    }
                });
            }

            function fetchCashierName(cashierId, callback) {
                $.ajax({
                    url: '<?= base_url('Queue/fetch_cashier_name'); ?>',
                    method: 'GET',
                    data: {
                        cashier_id: cashierId
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.name) {
                            callback(data.name);
                        } else {
                            callback('Unknown cashier');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching cashier name:', error);
                        callback('Unknown cashier');
                    }
                });
            }
            setInterval(updatePending, 3000);
            setInterval(updateServing, 3000);
        });
    </script>
</body>

</html>