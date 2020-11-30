<?php defined("SYSPATH") or die("No direct script access.") ?>
<?php // @todo Set hover on AlbumGrid list items for guest users ?>
<div id="g-info">
  <?= $theme->album_top() ?>
  <h1><?= html::purify($item->title) ?></h1>
  <div class="g-description"><?= nl2br(html::purify($item->description)) ?></div>
</div>
<?php
$children_all = $item->viewable()->children();
$theme->pagination = new Pagination();
$theme->pagination->initialize(
  array("query_string" => "page", "total_items" => $children_count, "items_per_page" => $page_size, "style" => "classic"));
$children_offset = ($theme->pagination->current_page -1) * $page_size ;
?>
<ul id="g-album-grid" class="ui-helper-clearfix">
  <?php if (count($children)): ?>
    <?php for ($i = 0; $i < $children_offset; $i++): ?>
       <?php $child = $children_all[$i] ?>
       <?= three_nids::fancylink($child, "header") ?>
    <?php endfor ?>

    <?php foreach ($children as $i => $child): ?>
      <?php $item_class = "g-photo" ?>
      <?php if ($child->is_album()): ?>
        <?php $item_class = "g-album" ?>
      <?php endif ?>
      <li id="g-item-id-<?= $child->id ?>" class="g-item <?= $item_class ?>">
        <?= $theme->thumb_top($child) ?>
        <?= three_nids::fancylink($child, "album") ?>
        <?= $theme->thumb_bottom($child) ?>
        <?= $theme->context_menu($child, "#g-item-id-{$child->id} .g-thumbnail") ?>
      </li>
    <?php endforeach ?>

    <?php for($i= $children_offset + $page_size; $i < $children_count; $i++): ?>
       <?php $child = $children_all[$i] ?>
       <?= three_nids::fancylink($child, "header") ?>
    <?php endfor ?>
  <?php else: ?>
    <?php if ($user->admin || access::can("add", $item)): ?>
      <?php $addurl = url::file("index.php/simple_uploader/app/$item->id") ?>
      <li><?= t("There aren't any photos here yet! <a %attrs>Add some</a>.",
                array("attrs" => html::mark_clean("href=\"$addurl\" class=\"g-dialog-link\""))) ?></li>
    <?php else: ?>
      <li><?= t("There aren't any photos here yet!") ?></li>
    <?php endif ?>
  <?php endif ?>
</ul>
<?= $theme->album_bottom() ?>

<?= $theme->paginator() ?>
