<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$this->inc('elements/header_top.php'); ?>

<main>
<?php 
$a = new Area('Main');
$a->enableGridContainer();
print '<div class="row"><div class="col-sm-12"><div id="service-messages-container" class="col-md-12"><service-messages></service-messages></div></div></div>';
$a->display($c);
?>
</main>

<?php   $this->inc('elements/footer_bottom.php'); ?>
