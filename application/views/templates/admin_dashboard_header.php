<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.0/css/buttons.dataTables.min.css">
    
    <link rel="stylesheet" href="<?= base_url('assets/css/admin2.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin_dashboard.css'); ?>">
    <link rel="icon" href="<?= base_url('assets/img/sdcafafa.jpg'); ?>">

    

    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
        }

        h2 {
            color: black;
        }

        .metric-icon {
            font-size: 24px;
            margin-right: 10px;
        }

        .date-filter-form {
            max-width: 400px;
            margin: 0 auto;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .date-filter-form label {
            display: block;
            margin-bottom: 5px;
        }

        .date-filter-form input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .date-filter-form button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .chart-container {
            margin-top: 20px;
            max-width: 600px;
            margin: 20px auto;
        }

        .table-filter-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .date-filter-form {
            max-width: 200px;
            margin-left: auto;
        }

        .date-filter-form input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .date-filter-form button {
            display: block;
            width: 100%;
            padding: 7px;
            background-color: #CD0B0B;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .pagination .page-item.disabled .page-link {
            pointer-events: none;
            opacity: 0.5;
        }
    </style>

    <title>Admin Dashboard</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand white-text" href="<?= base_url('Admin/home'); ?>">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('Admin/home'); ?>"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('Admin/users'); ?>"><i class="fas fa-users"></i> Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('Admin/cashiers'); ?>"><i class="fas fa-cash-register"></i> Cashier List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('Admin/transaction_management'); ?>"><i class="fas fa-list"></i> Transaction List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('Admin/daily_reports'); ?>"><i class="fas fa-chart-line"></i> Daily Reports</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('Admin/customer_details'); ?>"><i class="fas fa-window-restore"></i> Customer Details</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('Admin/cashier_metrics'); ?>"><i class="fas fa-book"></i> Cashier Metrics</a>
                </li>

            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="<?= base_url('Admin/logout'); ?>"><button type="submit" name="logout" class="nav-link btn btn-link">Logout <i class="fas fa-sign-out-alt"></i></button></a>
                </li>
            </ul>
        </div>
    </nav>