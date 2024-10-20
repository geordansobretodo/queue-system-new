<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Q-Ease</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stdominiccollege.edu.ph/WEBDOSE/plugins/waitme/waitMe.min.css">

    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/get_queue.css">
    <link rel="icon" href="<?= base_url() ?>/assets/img/sdcafafa.jpg">

    <script src="https://www.google.com/recaptcha/api.js?render=6LcHMQUqAAAAAFVuePktntV_NN1C9_mpMsNEWwjr"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* Custom styles */
        .school-logo {
            max-width: 30%;
            max-height: 20%;
            display: inline-block;
            vertical-align: middle;
        }

        .btn {
            width: 450px;
            max-width: 100%;
            border-color: crimson;
            border-width: 4px;
        }

        .btn:hover {
            border-color: goldenrod;
        }

        .dropdown-menu {
            width: 450px;
            max-width: 100%;
            border-color: goldenrod;
            border-width: 2px;
        }

        .process {
            border-color: transparent;
        }

        .process:hover {
            border-color: #2ECC71;
        }

        /* Add this style to center the "Get Queue" button */
        .modal-footer {
            text-align: center;
        }

        #getQueueButton {
            margin: 0 auto;
        }

        #closeButton {
            margin: 0 auto;
        }

        #printButton {
            margin: 0 auto;
            border-color: #198754;
        }

        .form-control {
            border-color: crimson;
            border-width: 4px;
        }

        .form-control:hover {
            border-color: goldenrod;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg shadow border-bottom border-2 border-danger">
        <div class="container-fluid">
            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-content">
                <div class="hamburger-toggle">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </button>
            <div class="collapse navbar-collapse" id="navbar-content">
                <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside">Related Platforms</a>
                        <ul class="dropdown-menu shadow">
                            <li><a class="dropdown-item" href="https://stdominiccollege.edu.ph/" target="blank">SDCA Website</a></li>
                            <li><a class="dropdown-item" href="https://stdominiccollege.edu.ph/SDCALMSv2/index.php/Main" target="blank">iClass</a></li>
                            <li><a class="dropdown-item" href="https://stdominiccollege.edu.ph/sdcap/index.php/Student/Main" target="blank">SDCA Old Portal</a></li>
                            <li><a class="dropdown-item" href="https://stdominiccollege.edu.ph/index.php/research" target="blank">RDO Microsite</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true"><Q-Ease></Q-Ease></a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" tabindex="-1" data-bs-toggle-theme="true" aria-disabled="true">Toggle Theme <i class="fa fa-adjust"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <section class="my-5">
        <div class="container">
            <div class="p-4 border border-2 border-danger rounded-4">
                <center>
                    <img src="<?= base_url() ?>/assets/img/sdcalogo.png" alt="SDCA" class="school-logo">
                    <h1>Get <span class="text-danger">Queue</span></h1>
                    <br>
                    <!-- CustomerDropdown -->
                    <p class="lead">Type of Customer:</p>
                    <p>Note: Priority Customers must present a valid ID that they are either PWD or Senior.</p>
                    <div class="dropdown-center">
                        <button type="button" class="btn btn-lg dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" changeable="true" id="customerDropdown">
                            Choose:
                        </button>
                        <ul class="dropdown-menu">
                            <?php foreach ($customertype as $ctype): ?>
                                <li><a class='dropdown-item'><?= $ctype['option_name'] ?></a></li>

                            <?php endforeach ?>
                        </ul>
                    </div>

                    <!-- ServiceType Dropdown -->
                    <br><br>
                    <b>
                        <p class="lead">Type of Service:</p>
                    </b>
                    <div class="dropdown-center">
                        <button type="button" class="btn btn-lg dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" changeable="true" id="serviceDropdown">
                            Choose:
                        </button>
                        <ul class="dropdown-menu">
                            <?php foreach ($servicetype as $stype): ?>
                                <li><a class='dropdown-item'><?= $stype['option_name'] ?></a></li>

                            <?php endforeach ?>
                        </ul>
                    </div>
                    <!-- PaymentFor Dropdown -->
                    <br><br>
                    <b>
                        <p class="lead">Payment For:</p>
                    </b>
                    <div class="dropdown-center">
                        <button type="button" class="btn btn-lg dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" changeable="true" id="paymentForDropdown">
                            Choose:
                        </button>
                        <ul class="dropdown-menu">
                            <?php foreach ($paymentfor as $paymfor): ?>
                                <li><a class='dropdown-item'><?= $paymfor['option_name'] ?></a></li>

                            <?php endforeach ?>
                        </ul>
                    </div>
                    <!-- PaymentMode Dropdown -->
                    <br><br>
                    <b>
                        <p class="lead">Mode of Payment:</p>
                    </b>
                    <div class="dropdown-center">
                        <button type="button" class="btn btn-lg dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" changeable="true" id="paymentModeDropdown">
                            Choose:
                        </button>
                        <ul class="dropdown-menu">
                            <?php foreach ($paymentmode as $paymod): ?>
                                <li><a class='dropdown-item'><?= $paymod['option_name'] ?></a></li>

                            <?php endforeach ?>
                        </ul>
                    </div>

                    <!-- Name and Student Number Input -->
                    <br><br>
                    <b>
                        <p class="lead">Student's Name:</p>
                    </b>
                    <div class="input-group input-group-lg mb-3" style="width: 450px; max-width: 100%;">
                        <input type="text" class="form-control" placeholder="e.g: Juan G. Dela Cruz" aria-label="Name" aria-describedby="name-addon" id="nameInput" required>
                    </div>
                    <p style="font-size: 15px;">Enter the student's name.</p>

                    <b>
                        <p class="lead">Student Number:</p>
                    </b>
                    <div class="input-group input-group-lg mb-3" style="width: 450px; max-width: 100%;">
                        <input type="text" class="form-control" placeholder="e.g: 201901730" aria-label="Student Number" aria-describedby="studentnumber-addon" id="studentNumberInput" pattern="\d{1,9}" maxlength="9" oninput="validateStudentNumber()" onblur="validateStudentNo()">
                    </div>

                    <!-- E-Mail Input -->
                    <b>
                        <p class="lead">Email:</p>
                    </b>
                    <div class="input-group input-group-lg mb-3" style="width: 450px; max-width: 100%;">
                        <input type="email" class="form-control" placeholder="Enter your email (optional)" aria-label="Email" aria-describedby="email-addon" id="emailInput" name="email">
                    </div>
                    <p style="font-size: 15px;">Optional: For those who want to be notified Via Email.</p>

                    <input type="text" name="id" id="idinput" hidden />

                    <!-- Process Queue Button -->
                    <br><br>
                    <div class="print">
                        <button class="btn process btn-success btn-lg" type="button" aria-expanded="false" id="processQueueButton">Process Queue</button>
                    </div>
                </center>
            </div>
        </div>
    </section>

    <!-- Queue Details Modal -->
    <div class="modal fade" id="queueDetailsModal" tabindex="-1" aria-labelledby="queueDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="queueDetailsModalLabel">Queue Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="text-center display-3 fw-bold" id="queueNumberDisplay"></p>
                    <p id="estimatedWaitTime"></p>
                    <p class="text-warning-emphasis" style="font-size: 15px;">Please screenshot your queue number for reference.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger btn-lg" id="closeButton" type="button" aria-expanded="false" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Process Queue Error Modal -->
    <div class="modal fade" id="errordetailsModal" tabindex="-1" aria-labelledby="errorDetailModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errordetailsModal">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="text-warning-emphasis" style="font-size: 24px;">Please input a valid student number.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger btn-lg" id="closeButton" type="button" aria-expanded="false" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Input Student Number Error Modal -->
    <div class="modal fade" id="inputerrordetailsModal" tabindex="-1" aria-labelledby="inputerrorDetailModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inputerrordetailsModal">Input Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="text-warning-emphasis" style="font-size: 24px;">Please enter only numeric values in the student number.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger btn-lg" id="closeButton" type="button" aria-expanded="false" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Post Error Modal -->
    <div class="modal fade" id="posterrordetailsModal" tabindex="-1" aria-labelledby="posterrordetailsModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="posterrordetailsModal">Queueing Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="text-warning-emphasis" style="font-size: 16px;">Please ensure all required options are selected and your name is provided. If you entered a student number, make sure it contains only numeric values.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger btn-lg" id="closeButton" type="button" aria-expanded="false" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Error Modal -->
    <div class="modal fade" id="emailerrordetailsModal" tabindex="-1" aria-labelledby="emailerrordetailsModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailerrordetailsModal">Input Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="text-warning-emphasis" style="font-size: 24px;">Invalid email address provided, please put a proper email.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger btn-lg" id="closeButton" type="button" aria-expanded="false" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://stdominiccollege.edu.ph/WEBDOSE/plugins/waitme/waitMe.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        var baseUrl = "<?= base_url(); ?>";
    </script>

    <script src="<?= base_url() ?>/assets/js/get_queue.js"></script>

    <script src="<?= base_url() ?>/assets/js/script.js"></script>
</body>

</html>