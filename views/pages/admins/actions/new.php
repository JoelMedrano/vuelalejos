<div class="card card-dark card-outline">

    <form method="post" class="needs-validation" novalidate enctype="multipart/form-data">

        <div class="card-header">

            <?php

            require_once "controllers/admins.controller.php";

            $create = new AdminsController();
            $create->create();

            ?>

            <div class="col-md-8 offset-md-2">

                <!--=====================================
                Nombre y apellido
                ======================================-->

                <div class="form-group mt-5">

                    <label>Nombre</label>

                    <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}" onchange="validateJS(event,'text')" name="displayname" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>

                <!--=====================================
                Apodo o seudónimo
                ======================================-->

                <div class="form-group mt-2">

                    <label>Username</label>

                    <input type="text" class="form-control" pattern="[A-Za-z0-9]{1,}" onchange="validateRepeat(event,'t&n','users','username_user')" name="username" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>

                <!--=====================================
                Correo electrónico
                ======================================-->

                <div class="form-group mt-2">

                    <label>Email</label>

                    <input type="email" class="form-control" pattern="[.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}" onchange="validateRepeat(event,'email','users','email_user')" name="email" required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>


                <!--=====================================
                Contraseña
                ======================================-->

                <div class="form-group mt-2">

                    <label>Password</label>

                    <input type="password" class="form-control" pattern="[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}" onchange="validateJS(event,'pass')" name="password" required>

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

                            <img src="<?php echo TemplateController::srcImg() ?>views/img/users/default/default.png" class="img-fluid rounded-circle changePicture" style="width:150px">

                        </figure>

                    </label>

                    <div class="custom-file">

                        <input type="file" id="customFile" class="custom-file-input" accept="image/*" onchange="validateImageJS(event,'changePicture')" name="picture" required>

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

                            <option value="">Seleccionar Compañia</option>

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

                        <option value>Seleccionar Rol</option>

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