<!DOCTYPE html>
<html lang="en">
<?php _component('admin/head'); ?>
<?php _component('session_data') ?>
<body class="scroll-assists">
    <?php _pageContent(); ?>
    <?php _component('admin/footer'); ?>
    <?php _asyncExecute(); ?>
    <?php _pageScript(); ?>
</body>
</html>
