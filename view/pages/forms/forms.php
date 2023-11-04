<?php _loggedIn(); ?>
<!-- INCLUDE CRYPTO -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js" integrity="sha256-/H4YS+7aYb9kJ5OKhFYPUjSJdrtV6AeyJOtTkw6X72o=" crossorigin="anonymous"></script>
<nav class="sticky navbar navbar-expand-xs navbar-dark bg-primary">
    <div class="container d-flex justify-content-end">
        <a class="nav-item nav-link border my-1" href="#" id="btn-loadFile"> Import .crdp file </a>
        <a class="nav-item nav-link border my-1" href="#" id="btn_gen_file"> Export data </a>
        <a class="d-none" href="#" id="download_button" download="HorseData.crdp">Download</a>
        <!-- <a class="navbar-brand">File data</a> -->

        <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#file_actions-nav" aria-controls="file_actions-nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="file_actions-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link border" href="#" id="btn-loadFile"> Import .crdp file </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link border" href="#" id="btn_gen_file"> Export data </a>
                    <a class="d-none" href="#" id="download_button" download="HorseData.crdp">Download</a>
                </li>
            </ul>
        </div> -->
    </div>
</nav>
<?php if (!_session()): ?>

    <div class="jumbotron text-center">
        <h4>Dont have an account yet? <a href="?p=register" class="link">Register here</a> </h4>
    </div>

<?php endif; ?>
<br>
<div class="container register-form">
    <div class="form">
        <div class="note">
            <h3> - Register your horse.</h3>
            <p>To register a horse, you must be registered into the application and have a valid membership.</p>
        </div>
        <div class="form-content">
            <div class="row pt-5 text-center">

                <div class="col-md-12">
                    <h4>First Owner Information</h4>
                    <hr>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="i_am_current_owner"/>
                        <label class="custom-control-label" for="i_am_current_owner"> I am the first owner </label>
                    </div>
                    <div class="row mt-3" id="first_owner_information-container">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Name of the first owner </label>
                                <input type="text" class="form-control" id="name_first_owner"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Identification of the first owner </label>
                                <input type="text" class="form-control" id="id_first_owner" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Address of first owner: </label>
                                <input type="text" class="form-control" id="address_first_owner"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> First owner phone number: </label>
                                <input type="number" class="form-control" id="phone_number_first_owner"/>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- BREEDER INFORMATION -->
                <div class="col-md-12 pt-5">
                    <h4>Breeder Information</h4>
                    <hr>
                    <div class="row" id="breeder_information">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Name of the breeder </label>
                                <input type="text" class="form-control" id="breeder_name"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Breeder identification number </label>
                                <input id="breeder_identification" type="text" class="form-control"/>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END OF BREEDER INFORMATION -->


                <!-- HORSE INFORMATION -->
                <div class="col-md-12 pt-5" id="horse_information">

                    <h4>Horse Information</h4>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Name of the Horse </label>
                                <input type="text" class="form-control" id="name_of_the_horse" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Horse Birth Date: </label>
                                <input type="date" class="form-control" id="horse_birth_date"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Purity of Breed </label>
                                <select class="form-control" id="purity_of_breed">
                                    <option value="1">Pure Bred</option>
                                    <option value="2">YB</option>
                                    <option value="3">Y1</option>
                                    <option value="4">Y2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Horse Sex </label>
                                <select class="form-control" id="horse_sex">
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Microchip Number </label>
                                <input type="text" class="form-control" id="microchip_number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Date of evaluation </label>
                                <input type="date" class="form-control" id="date_of_evaluation"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Horse Size </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" placeholder="Ex: 14" min="0" max="" id="horse_size">
                                    <div class="input-group-append">
                                        <select class="form-control bg-secondary text-white" id="typesize" data-ini="hand">
                                            <option value="hand" selected> Hands </option>
                                            <option value="inches">Inches (In)</option>
                                            <option value="centimeters">Centimeters (cm)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Observations </label>
                                <textarea class="form-control" id="observations"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END OF HORSE INFORMATION -->


                <!-- Parents Information -->
                <div class="col-md-12 pt-5">
                    <h4>Father Information</h4>
                    <hr>
                    <div class="row" id="father_information">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Name of the Father </label>
                                <input type="text" class="form-control" id="father_name"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> DNA of the Father </label>
                                <select class="form-control" id="dna_father">
                                    <option value="Take Sample">Take Sample</option>
                                    <option value="UCR">UCR (University of Costa Rica)</option>
                                    <option value="Chile">Chile</option>
                                    <option value="Davis">Davis</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Father Size </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" placeholder="Ex: 14" min="0" max="" id="father_size">
                                    <div class="input-group-append">
                                        <select class="form-control bg-secondary text-white" id="father_typesize" data-ini="hand">
                                            <option value="hand">Hands </option>
                                            <option value="inches">Inches (In)</option>
                                            <option value="centimeters">Centimeters (cm)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Father Registered in Ascacopa / Costarricense de Paso </label>
                                <select class="form-control" id="father_registration_status">
                                    <option value="0">Not Registered</option>
                                    <option value="1">Registered</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 d-none" id="father_registration-container">
                            <div class="form-group">
                                <label for=""> Registration Number of the Father </label>
                                <input id="father_registration" type="text" class="form-control" />
                            </div>
                        </div>

                    </div>
                </div>


                <div class="col-md-12 pt-5">
                    <h4>Mother Information</h4>
                    <hr>
                    <div class="row" id="mother_information">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Name of the Mother </label>
                                <input type="text" class="form-control" id="mother_name"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Microcapsule of the Mother </label>
                                <input type="text" class="form-control" id="mother_microcapsule"/>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Mother Size </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" placeholder="Ex: 14" min="0" max="" id="mother_size">
                                    <div class="input-group-append">
                                        <select class="form-control bg-secondary text-white" id="mother_typesize" data-ini="hand">
                                            <option value="hand">Hands </option>
                                            <option value="inches">Inches (In)</option>
                                            <option value="centimeters">Centimeters (cm)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Mother is in Ascacopa / Costarricense de Paso </label>
                                <select class="form-control" id="mother_registration_status">
                                    <option value="0">Not Registered</option>
                                    <option value="1">Registered</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 d-none" id="mother_registration-container">
                            <div class="form-group">
                                <label for=""> Registration Number of the Mother </label>
                                <input id="mother_registration" type="text" class="form-control" />
                            </div>
                        </div>
                    </div>



                </div>


                <!-- END PARENTS INFORMATION -->


            <!-- END OF FORM CONTENTS -->
            </div>

            <br><br><br>
            <p class="text-warning">By registering in the application, you agree to the terms and service conditions.</p>
            <hr>
            <button type="button" class="btn btn-dark" id="btn_register" >
                Register
            </button>
        </div>
    </div>
</div>
<br>


<script>
///////////////// fixed menu on scroll for desktop

</script>
