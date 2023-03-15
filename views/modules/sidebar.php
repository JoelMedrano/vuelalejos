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

                <!-- ARTÍCULOS -->
                <li class="nav-item">
                    <a href="#" class="nav-link <?php if (
                                                    !empty($routesArray) &&
                                                    ($routesArray[1] == "categories" ||
                                                        $routesArray[1] == "laboratories" ||
                                                        $routesArray[1] == "therapies" ||
                                                        $routesArray[1] == "substances" ||
                                                        $routesArray[1] == "articles" ||
                                                        $routesArray[1] == "globalarticles")
                                                ) : ?>active<?php endif ?>">
                        <i class="nav-icon fas fa-medkit"></i>
                        <p>
                            Artículos
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="categories" class="nav-link <?php if (!empty($routesArray) && $routesArray[1] == "categories") : ?>active<?php endif ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Categorias</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="laboratories" class="nav-link <?php if (!empty($routesArray) && $routesArray[1] == "laboratories") : ?>active<?php endif ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Laboratorios</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="therapies" class="nav-link <?php if (!empty($routesArray) && $routesArray[1] == "therapies") : ?>active<?php endif ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Terapias</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="substances" class="nav-link <?php if (!empty($routesArray) && $routesArray[1] == "substances") : ?>active<?php endif ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sustancia Activa</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="articles" class="nav-link <?php if (!empty($routesArray) && $routesArray[1] == "articles") : ?>active<?php endif ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Artículos</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="globalarticles" class="nav-link <?php if (!empty($routesArray) && $routesArray[1] == "globalarticles") : ?>active<?php endif ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Conf. Articulos</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- COMPRAS -->
                <li class="nav-item">
                    <a href="#" class="nav-link <?php if (
                                                    !empty($routesArray) &&
                                                    ($routesArray[1] == "providers")
                                                ) : ?>active<?php endif ?>">
                        <i class="nav-icon fas fa-hospital-alt"></i>
                        <p>
                            Compras
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="providers" class="nav-link <?php if (!empty($routesArray) && $routesArray[1] == "providers") : ?>active<?php endif ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Proveedores</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="purchases" class="nav-link <?php if (!empty($routesArray) && $routesArray[1] == "purchases") : ?>active<?php endif ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Compras</p>
                            </a>
                        </li>
                    </ul>

                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>