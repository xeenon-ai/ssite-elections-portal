<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

<title><?= $pageTitle ?? 'SSITE Elections Portal'; ?></title>

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- CSS -->

<link rel="stylesheet" href="/ssite-elections/assets/css/style.css">

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg navbar-dark shadow sticky-top">
    <div class="container">

        <a class="navbar-brand fw-bold"
           href="#">

<img src="/ssite-elections/assets/images/ssite-logo.png"
     alt="SSITE Logo"
     width="40"
     class="me-2">

<span class="fw-bold">
    SSITE Elections Portal
</span>

        </a>

        <button class="navbar-toggler"
                data-bs-toggle="collapse"
                data-bs-target="#menu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse"
             id="menu">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">

                    <a class="nav-link active"
                       href="#">Home</a>

                </li>

                <li class="nav-item">

                    <a class="nav-link"
                       href="#about">About</a>

                </li>

<li class="nav-item me-2">

<a class="btn btn-outline-light"
href="login.php">

Login

</a>

</li>

<li class="nav-item">

<a class="btn btn-warning"
href="signup.php">

Sign Up

</a>

</li>

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>