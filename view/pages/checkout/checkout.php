
<?php if (!isset($_GET['tsc']) || empty($_GET['tsc'])):  include('notransaction.php'); ?>
<?php else: include('transaction.php'); ?>
<?php endif; ?>
