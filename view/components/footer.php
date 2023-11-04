<style media="screen">
  body{
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }
  footer{
    margin-top: auto;
  }
</style>
<footer class="footer-2 bg-dark text-center-xs">
  <div class="container">
    <div class="row">
      <div class="col-sm-4">
        <a href="#">
          <img class="image-xs fade-half" alt="Pic" src="<?=PATH_ASSETS?>/img/cdp_logo_cropped.png">
        </a>
      </div>

      <div class="col-sm-4 text-center">
        <span class="fade-half">
          © Copyright <?=date('Y')?> Costarricense de Paso U.S</span>
      </div>

      <div class="col-sm-4 text-right text-center-xs d-none">
        <ul class="list-inline social-list">
          <li><a href="#"><i class="ti ti-instagram"></i></a></li>
          <li><a href="#"><i class="ti-facebook"></i></a></li>
          <li><a href="#"><i class="ti ti-twitter"></i></a></li>
        </ul>
      </div>
    </div>
  </div>
  <!-- Javascript required libraries. -->
  <script src="<?=PATH_ASSETS?>js/jquery.min.js"></script>
  <script src="<?=PATH_ASSETS?>js/bootstrap.min.js"></script>
  <?php _component('utils/datatables') ?>
  <script src="<?=PATH_ASSETS?>js/flexslider.min.js"></script>
  <script src="<?=PATH_ASSETS?>js/parallax.js"></script>
  <script src="<?=PATH_ASSETS?>js/scripts.js"></script>
  <script src="<?=PATH_RAI_FW?>fnon.min.js"></script>
  <script>
    <?php include(RAI_FW); ?>
  </script>


</footer>
