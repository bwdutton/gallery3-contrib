<?php defined('SYSPATH') or die('No direct script access.'); ?>

<script type="text/javascript" src="<?= url::file("modules/ratings/js/mootools-1.2.4.js"); ?>"></script>
<style>
<?php include_once(MODPATH."ratings/css/ratings.css"); ?>
</style>

<style>
.rabidRating .ratingStars {
  display: block;
  background: url('<?= module::get_var("ratings","iconset"); ?>') no-repeat center;
}
.ratings-sb {
	color: <?= module::get_var("ratings","textcolor"); ?>;
	padding-left: 10px;
	padding-top: 3px;
	padding-bottom: 3px;
	font-size: 0.9em;
	background-color:<?= module::get_var("ratings","bgcolor"); ?>;
	width:183px;
	border:2px groove #fff;
	border-radius: 7px;
	-moz-border-radius: 7px;
	-moz-box-shadow: 3px 3px 4px #000;
	-webkit-box-shadow: 3px 3px 4px #000;
	box-shadow: 3px 3px 4px #000;
	/* For IE 8 */
	-ms-filter: "progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#000000')";
	/* For IE 5.5 - 7 */
	filter: progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#000000');
}
.rabidRating .ratingFill {
	background-color: <?= module::get_var("ratings","fillcolor"); ?>;
}
.rabidRating .ratingVoted {
	cursor: default;
	background-color: <?= module::get_var("ratings","votedcolor"); ?>;
}
.rabidRating .ratingActive {
	background-color: <?= module::get_var("ratings","hovercolor"); ?>;
}
.rabidRating .ratingText {
        color: <?= module::get_var("ratings","textcolor"); ?>;
}
.rabidRating .ratingText.loading {
	background: url('<?= url::file("modules/ratings/vendor/img/ajax-loading.gif"); ?>') no-repeat;
	text-indent: -999em;
}
</style>

<?php require_once(MODPATH."ratings/vendor/ratings.php"); $rr = new RabidRatings(); ?>

<script type="text/javascript">
<?php include_once(MODPATH."ratings/js/ratings.js"); ?>
</script>

<div class="ratings-sb ">
<?php
if(module::get_var("ratings","regonly") == 1 && identity::active_user()->guest){
} else {
  echo module::get_var("ratings","castyourvotestring");
}
?>
<?php $ratingid = "rate".$item->id; $rr->showStars($ratingid); ?>
</div>
