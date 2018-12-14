<?php
// modified to include service-messages component - Terry SoRelle 2018-12-13
// todo: see if we can find a better place for this. Improve format
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header.php'); ?>

<main>
<?php 
$a = new Area('Main');
$a->enableGridContainer();
?>
<?php
print '<div class="row"><div class="col-sm-12" style="padding-left:10%;padding-right:15%"><div id="service-messages-container" class="col-md-12"><service-messages></service-messages></div></div></div>';
$a->display($c);
?>

<?php 
$a = new Area('Page Footer');
$a->enableGridContainer();
$a->display($c);
?>

</main>

<?php   $this->inc('elements/footer.php'); ?>
