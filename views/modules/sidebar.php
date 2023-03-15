<aside class="main-sidebar sidebar-light-navy elevation-4">
    <!-- Brand Logo -->
    <a href="../../index3.html" class="brand-link navbar-navy">
        <img src="views/assets/img/template/logo.png" width="150px" alt="AdminLTE Logo" style="opacity: .8">
        <!-- <span class="brand-text font-weight-light">AdminLTE 3</span> -->
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">

                <?php if ($_SESSION["admin"]->picture_user == null) : ?>

                    <img src="<?php echo TemplateController::srcImg() ?>views/img/users/default/default.png" class="img-circle elevation-2" alt="User Image">

                <?php else : ?>

                    <img src="<?php echo TemplateController::srcImg() ?>views/img/users/<?php echo $_SESSION["admin"]->id_user ?>/<?php echo $_SESSION["admin"]->picture_user ?>" class="img-circle elevation-2" alt="User Image">
                <?php endif ?>
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo $_SESSION["admin"]->displayname_user ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="/" class="nav-link <?php if (empty($routesArray)) : ?>active<?php endif ?>">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="admins" class="nav-link <?php if (!empty($routesArray) && $routesArray[1] == "admins") : ?>active<?php endif ?>">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>
                            Admins
                        </p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>