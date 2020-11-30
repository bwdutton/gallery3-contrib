<?php defined("SYSPATH") or die("No direct script access.") ?>
<?php // @todo Set hover on AlbumGrid list items ?>

<?php list($children_count_true, $children_all) = search::search($q,1000,0);
     $theme->pagination = new Pagination();
		$theme->pagination->initialize(array("query_string" => "page","total_items" => $children_count_true,"items_per_page" => $page_size,"style" => "classic"));
	$children_offset = ($theme->pagination->current_page -1) * $page_size ; ?>

<div id="g-search-results">
  <h2><?= t("Results for <b>%term</b>", array("term" => $q)) ?></h2>

  <?php if (count($items)): ?>
  <ul id="g-album-grid" class="ui-helper-clearfix">
<?php for($i=0;$i<$children_offset;$i++): ?>
	<?php $child = $children_all[$i] ?>
	<?= three_nids::fancylink($child,"header") ?>
<?php endfor ?>
<?php foreach ($items as $child): ?>
  <li id="g-item-id-<?= $child->id ?>" class="g-item g-album">
	<?= $theme->thumb_top($child) ?>
	<?= three_nids::fancylink($child,"dynamic") ?>
	<?= $theme->thumb_bottom($child) ?>
	<?= $theme->context_menu($child, "#g-item-id-{$child->id} .g-thumbnail") ?>
  </li>
<?php endforeach ?>
<?php for($i=$children_offset+$page_size;$i<$children_count;$i++): ?>
	 <?php $child = $children_all[$i] ?>
	<?= three_nids::fancylink($child,"header") ?>
<?php endfor ?>
</ul>
  <?= $theme->paginator() ?>

  <?php else: ?>
  <p>
    <?= t("No results found for <b>%term</b>", array("term" => $q)) ?>
  </p>

  <?php endif; ?>
</div>
