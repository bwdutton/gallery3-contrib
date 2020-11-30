<?php defined("SYSPATH") or die("No direct script access.") ?>
<script>
$(function() {
  $('#g-search').attr('value', '<?=$q?>');
  $('.pearTitle').html("Search results for \"<?=$q?>\"");
});
</script>
<?php if (count($items)): ?>
  <?php/* Treat dynamic pages just lite album pages. */ ?>
  <?php $children = $items ?>
  <?php $v = new View("album.html");
  $v->set_global("children", $items);// = $items;
  print $v;?>
<?php else: ?>
  <p>
  <?= t("No results found for <b>%term</b>", array("term" => $q)) ?>
  </p>
<?php endif; ?>
