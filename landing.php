<!DOCTYPE html>
<html lang="en">
<?php _component('head'); ?>
<?php _component('session_data') ?>
<body class="scroll-assists">
    <?php _component('nav'); ?>
    <?php _pageContent(); ?>
    <?php _component('footer'); ?>
    <?php _asyncExecute(); ?>
    <?php _pageScript(); ?>
</body>
</html>
