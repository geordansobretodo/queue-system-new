<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="<?= base_url('assets/img/sdcafafa.jpg'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/index.css'); ?>">
    <title>Cashier Login</title>
    <style>
        body {
            background-color: #f2f2f2;
        }

        .container {
            margin-top: 50px;
        }

        .login-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .login-container h2 {
            color: #CD0B0B;
            font-size: 25px;
        }

        .login-container form {
            margin-top: 20px;
        }

        .login-container label {
            font-weight: bold;
        }

        .login-container .form-control {
            margin-bottom: 15px;
        }

        .login-container .btn-primary {
            background-color: #CD0B0B;
            border: none;
        }

        .login-container a {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #CD0B0B;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg topNav">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url(''); ?>">Queue Ease</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4 login-container">
                <h2 class="text-center mb-4">Please Select a Cashier</h2>

                <!-- Display error message if any -->
                <?php if ($this->session->flashdata('login_error')): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $this->session->flashdata('login_error'); ?>
                    </div>
                <?php endif; ?>

                <!-- Login form -->
                <?= form_open('Login/cashier'); ?>
                <div class="form-group">
                    <label for="cashier_id"><i class="fas fa-cash-register"></i> Cashier:</label>
                    <select class="form-control" id="cashier_id" name="cashier_id" required>
                        <?php foreach ($cashier as $cashiers): ?>
                            <option value="<?= $cashiers['id']; ?>"><?= $cashiers['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-key"></i> Password:</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="width:100%;">Login</button>
                <?= form_close(); ?>
                <a href="<?= base_url('Login/admin'); ?>"><i class="fas fa-user-shield"></i> Admin</a>
            </div>
        </div>
    </div>

</body>
</html>