<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Queue-ease</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="<?= base_url() ?>/assets/css/index.css">
  <link rel="icon" href="<?= base_url() ?>/assets/img/sdcafafa.jpg">

  <style include>
    body {
      background-color: #f2f2f2;
      /* Adjust background color of the main content */
    }

    .navbar {
      background-color: #CD0B0B;
      height: 76px;
    }


    .btn-secondary {
      background-color: #CD0B0B;
      outline: none !important;
      border: none;
      padding: 10px;
      width: 200px;

    }

    .btn-secondary:hover,
    .btn-secondary:active,
    .btn-secondary:focus {
      background-color: #CD0B0B;
      opacity: 0.8;
    }

    h1,
    p {
      color: #000;
    }

    @media (max-width: 767.98px) {
      .login-button {
        display: none;
      }
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg topNav">
    <div class="container-fluid topNav">
      <a class="navbar-brand text-white" href="<?= base_url() ?>">Queue Ease</a>
    </div>

    </div>
    <a href="<?php echo base_url('Login/admin'); ?>"><button class="btn btn-secondary login-button">Go to Login <br>Page</button></a>
  </nav>


  <div class="d-flex align-items-center justify-content-center" style="height: 80vh;">
    <div>
      <h1 class="text-center animate-from-top">Welcome To <span>Queue Ease</span></h1>
      <p class="text-center animate-from-bottom">"Efficiency in line, your time, our priority."</p>
      <div class="text-center animate-from-bottom">
        <a href="<?= base_url('Queue/get_queue') ?>"><button class="btn btn-secondary">Get Your Queue Ticket</button></a>

      </div>
    </div>
  </div>
</body>

</html>