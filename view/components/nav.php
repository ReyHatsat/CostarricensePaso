<style>
    #main_menu a{
      font-size:14px !important;
    }
    .bl-navigation-item{
        position:relative;
    }
    .bl-navigation-item::before{
        content:' ';
        width:0px;
        height:22px;
        background:rgba(0,0,0, 0.1);
        position:absolute;
        left:-5px;
        top:calc(50% - 12.5px);
        transition:linear 0.15s;
        border-radius:5px;
    }
    .bl-navigation-item:hover::before{
        width:calc(100% + 10px);
    }
</style>

<div class="nav-container">
  <nav class="nav-centered">
        <!-- <div class="nav-utility">
            <div class="module left">
                <i class="ti-location-arrow">&nbsp;</i>
                <span class="sub">68 Cardamon Place, Melbourne Vic 3000</span>
            </div>
            <div class="module left">
                <i class="ti-email">&nbsp;</i>
                <span class="sub">hello@foundry.net</span>
            </div>
            <div class="module right">
                <a class="btn btn-sm" href="#purchase-template">Buy Now</a>
            </div>
        </div> -->
        <div class="text-center pt-1">
             <a href="./">
                <!-- Horse-Registry-for-U.S-SMALL.png  -->
                <img style="width:100px;" alt="" src="<?=PATH_ASSETS?>/img/cdp_logo_cropped.png">
            </a>
        </div>
        <div class="nav-bar text-center">
            <div class="module widget-handle mobile-toggle right visible-sm visible-xs">
                <i class="ti-menu"></i>
            </div>
            <div class="module-group text-left">
                <div class="module left">
                    <ul class="menu" id="main_menu">

                      <?php if (isset($_SESSION[SES_OBJ]['is_admin']) && $_SESSION[SES_OBJ]['is_admin']): ?>
                          <li class="text-light" style="padding:0; margin:0; height:1px; width:15px !important;">-</li>
                          <li class="has-dropdown">
                              <a href="#" class="text-info">
                                  <i class="fas fa-cogs"></i> Admin
                              </a>
                              <ul class="text-left" style="width:200px;">
                                  <li>
                                      <a href="?p=adm_horses">
                                          <i class="fas fa-horse"> </i> All Horses
                                      </a>
                                  </li>
                                  <li>
                                      <a href="?p=adm_persons">
                                          <i class="fa fa-user"></i> All Users
                                      </a>
                                  </li>
                                  <li>
                                      <a href="?p=adm_transfer">
                                          <i class="fas fa-exchange-alt"></i> Transfer History
                                      </a>
                                  </li>
                                  <li>
                                      <a href="?p=adm_memberships">
                                          <i class="fas fa-list"></i>  Membership Types
                                      </a>
                                  </li>
                              </ul>
                          </li>

                      <?php endif; ?>

                        <li>
                            <a href="./" class="bl-navigation-item">
                                <i class="fa fa-home"></i>
                                Home
                            </a>
                        </li>
                        <!-- <li>
                            <a href="?p=plans">Membership Plans</a>
                        </li> -->
                        <?php if (!_session()): ?>
                            <li>
                                <a href="?p=register" class="bl-navigation-item">
                                    <i class="fa fa-user-plus"></i>
                                    Register
                                </a>
                            </li>
                            <li>
                                <a href="?p=login" class="bl-navigation-item">
                                    <i class="fa fa-sign-in-alt"></i>
                                    Log In
                                </a>
                            </li>
                        <?php endif; ?>


                        <li>
                            <a href="?p=contact" class="bl-navigation-item">
                                <i class="fas fa-envelope"></i>
                                Contact Us
                            </a>
                        </li>
                        <li>
                            <a href="?p=about" class="bl-navigation-item">
                                <i class="fa fa-info-circle"></i>
                                About
                            </a>
                        </li>

                        <?php if (_session()): ?>
                            <li class="has-dropdown">
                                <a href="#">
                                    <i class="fas fa-user-circle"></i>Account
                                </a>
                                <ul>
                                    <li>
                                            <a href="?p=profile">
                                                <i class="fas fa-user"></i>
                                                My Profile
                                            </a>
                                    </li>
                                    <li>
                                            <a href="?p=forms">
                                                <i class="fas fa-horse-head"></i>
                                                Register My Horse
                                            </a>
                                    </li>
                                    <li>
                                            <a href="?p=my_horses">
                                                <i class="fas fa-list"></i>
                                                My Horses
                                            </a>
                                    </li>
                                    <li class="mt-1 mb-3" style="border-bottom:1px solid rgba(255,255,255, 0.3);">  </li>
                                    <li class="bg-danger">
                                        <a href="?logout" class="text-white">
                                            <i class="fas fa-sign-out-alt"></i>
                                            Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>






                        <!-- <li class="has-dropdown">
                            <a href="#">
                                Mega Menu
                            </a>
                            <ul class="mega-menu">
                                <li>
                                    <ul>
                                        <li>
                                            <span class="title">Column 1</span>
                                        </li>
                                        <li>
                                            <a href="#">Single</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <ul>
                                        <li>
                                            <span class="title">Column 2</span>
                                        </li>
                                        <li>
                                            <a href="#">Single</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>


                        <li class="has-dropdown">
                            <a href="#">
                                Single Dropdown
                            </a>
                            <ul>
                                <li class="has-dropdown">
                                    <a href="#">
                                        Second Level
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="#">
                                                Single
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li> -->


                    </ul>
                </div>

                <!-- <div class="module widget-handle language left">
                    <ul class="menu">
                        <li class="has-dropdown">
                            <a href="#">ENG</a>
                            <ul>
                                <li>
                                    <a href="#">French</a>
                                </li>
                                <li>
                                    <a href="#">Deutsch</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div> -->
            </div>
        </div>
    </nav>
</div>


<?php if (isset($_GET[LOGOUT])): ?>
  <div id="logout_alert_notification" class="alert bg-danger text-white alert-dismissible fade show" role="alert"
      style="position:fixed; width:100%; top:0; left:0; z-index:999;"
    >
    <strong>Success!</strong> You have succesfully logged out from your account.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <script type="text/javascript">

    setTimeout(function(){
      findone('#logout_alert_notification').remove();
    }, 4000)

  </script>
<?php endif; ?>


<!-- START PAGE CONTENT -->
