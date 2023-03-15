<?php

if (isset($routesArray[3])) {

    $security = explode("~", base64_decode($routesArray[3]));

    if ($security[1] == $_SESSION["admin"]->token_user) {

        $select = "id_user,displayname_user,username_user,email_user,password_user,picture_user,id_company_user,rol_user";

        $url = "users?select=" . $select . "&linkTo=id_user&equalTo=" . $security[0];
        $method = "GET";
        $fields = array();

        $response = CurlController::request($url, $method, $fields);

        if ($response->status == 200) {

            $admin = $response->results[0];

            $url = "companies?select=id_company,ruc_company,name_company&linkTo=id_company&equalTo=" . $admin->id_company_user;
            $method = "GET";
            $fields = array();

            $company_user = CurlController::request($url, $method, $fields)->results[0];
        } else {

            echo '<script>

				window.location = "/admins";

				</script>';
        }
    } else {

        echo '<script>

				window.location = "/admins";

				</script>';
    }
}


?>
<div class="card card-dark card-outline">

    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">

        <input type="hidden" value="<?php echo $admin->id_user ?>" name="idAdmin">

        <div class="card-header">

            <?php

            require_once "controllers/admins.controller.php";

            $create = new AdminsController();
            $create->edit($admin->id_user);

            ?>

            <div class="col-md-8 offset-md-2">

                <!--=====================================
                Nombre y apellido
                ======================================-->

                <div class="form-group mt-5">

                    <label>Nombre</label>

                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')" name="displayname" value="<?php echo $admin->displayname_user ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>

                <!--=====================================
                Apodo o seudónimo
                ======================================-->

                <div class="form-group mt-2">

                    <label>Username</label>

                    <input type="text" class="form-control" pattern="[A-Za-z0-9]{1,}" onchange="validateRepeat(event,'t&n','users','username_user')" name="username" value="<?php echo $admin->username_user ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>

                <!--=====================================
                Correo electrónico
                ======================================-->

                <div class="form-group mt-2">

                    <label>Email</label>

                    <input type="email" class="form-control" pattern="[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}" onchange="validateRepeat(event,'email','users','email_user')" name="email" value="<?php echo $admin->email_user ?>" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>


                <!--=====================================
                Contraseña
                ======================================-->

                <div class="form-group mt-2">

                    <label>Password</label>

                    <input type="password" class="form-control" pattern="[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}" onchange="validateJS(event,'pass')" name="password" placeholder="********">

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>


                <!--=====================================
                Foto
                ======================================-->

                <div class="form-group mt-2">

                    <label>Foto</label>

                    <label for="customFile" class="d-flex justify-content-center">

                        <figure class="text-center py-3">

                            <img src="<?php echo TemplateController::returnImg($admin->id_user, $admin->picture_user, 'direct') ?>" class="img-fluid rounded-circle changePicture" style="width:150px">

                        </figure>

                    </label>

                    <div class="custom-file">

                        <input type="file" id="customFile" class="custom-file-input" accept="image/*" onchange="validateImageJS(event,'changePicture')" name="picture">

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                        <label for="customFile" class="custom-file-label">Choose file</label>

                    </div>

                </div>

                <!--=====================================
		        Compañias
		        ======================================-->

                <div class="form-group mt-2">

                    <label>Compañia<sup class="text-danger">*</sup></label>

                    <?php

                    $url = "companies?select=id_company,name_company,ruc_company";
                    $method = "GET";
                    $fields = array();

                    $company = CurlController::request($url, $method, $fields)->results;

                    ?>

                    <div class="form-group">

                        <select class="form-control select2" name="company" style="width:100%" required>

                            <option value="<?php echo $company_user->id_company ?>"><?php echo $company_user->ruc_company ?> - <?php echo $company_user->name_company ?></option>

                            <?php foreach ($company as $key => $value) : ?>

                                <option value="<?php echo $value->id_company ?>"><?php echo $value->ruc_company ?> - <?php echo $value->name_company ?></option>


                            <?php endforeach ?>

                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                </div>

                <!--=====================================
                País
                ======================================-->

                <div class="form-group mt-2">

                    <label>ROLES</label>

                    <?php

                    $roles = file_get_contents("views/assets/json/roles.json");
                    $roles = json_decode($roles, true);

                    ?>

                    <select class="form-control select2" name="rol" required>

                        <option value="<?php echo $admin->rol_user ?>"><?php echo $admin->rol_user ?></option>

                        <?php foreach ($roles as $key => $value) : ?>

                            <option value="<?php echo $value["rol"] ?>"><?php echo $value["name_rol"] ?></option>

                        <?php endforeach ?>

                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>

            </div>

        </div>

        <div class="card-footer">

            <div class="col-md-8 offset-md-2">

                <div class="form-group mt-3">

                    <a href="/admins" class="btn btn-light border text-left">Back</a>

                    <button type="submit" class="btn bg-dark float-right">Save</button>

                </div>

            </div>

        </div>


    </form>


</div>