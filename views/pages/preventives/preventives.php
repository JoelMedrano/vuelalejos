<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Preventivos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <?php

                    if (isset($routesArray[2])) {

                        if ($routesArray[2] == "new" || $routesArray[2] == "edit") {

                            echo '<li class="breadcrumb-item"><a href="/preventives">Preventivos</a></li>';
                            echo '<li class="breadcrumb-item active">' . $routesArray[2] . '</li>';
                        }
                    } else {

                        echo '<li class="breadcrumb-item active">Preventivos</li>';
                    }

                    ?>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

    <div class="container-fluid">
        <?php

        if (isset($routesArray[2])) {

            if ($routesArray[2] == "new" || $routesArray[2] == "edit") {

                include "actions/" . $routesArray[2] . ".php";
            }
        } else {

            include "actions/list.php";
        }

        ?>
    </div>

</section>
<!-- /.content -->