<?php
$select = "*";

$url = "barticles?select=*&linkTo=state_barticle&equalTo=1";
$method = "GET";
$fields = array();

$response = CurlController::request($url, $method, $fields);

if ($response->status == 200) {
    $total = $response->total;
} else {
    $total = 0;
}

?>

<nav class="main-header navbar navbar-expand navbar-navy navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <?php if ($_SESSION["admin"]->rol_user == "administrador") : ?>
            <!-- Solicitudes-->
            <li class="nav-item dropdown">
                <a class="nav-link" href="articles/request">
                    <i class="fas fa-cogs"></i>
                    <span class="badge badge-primary navbar-badge"><?php echo $total ?></span>
                </a>
            </li>

        <?php endif ?>


        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">3</span>
            </a>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/logout" role="button">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </li>
    </ul>
</nav>