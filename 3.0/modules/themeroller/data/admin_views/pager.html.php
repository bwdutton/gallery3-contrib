<?php defined("SYSPATH") or die("No direct script access.") ?>
<?php // See http://docs.kohanaphp.com/libraries/pagination ?>
<ul class="g-paginator">
  <?php /* @todo This message isn't easily localizable */
     $from_to_msg = t2("Item %from_number of %count",
                       "Items %from_number - %to_number of %count",
                       $total_items,
                       array("from_number" => $current_first_item,
                             "to_number" => $current_last_item,
                             "count" => $total_items)) ?>
  <li>
  <?php if ($first_page): ?>
    <a href="<?= str_replace('{page}', 1, $url) ?>" class="g-button ui-icon-left ui-state-default ui-corner-all">
      <span class="ui-icon ui-icon-seek-first"></span><?= t("First") ?></a>
  <?php else: ?>
    <a class="g-button ui-icon-left ui-state-disabled ui-corner-all">
      <span class="ui-icon ui-icon-seek-first"></span><?= t("First") ?></a>
  <?php endif ?>
  <?php if ($previous_page): ?>
    <a href="<?= str_replace('{page}', $previous_page, $url) ?>" class="g-button ui-icon-left ui-state-default ui-corner-all">
      <span class="ui-icon ui-icon-seek-prev"></span><?= t("Previous") ?></a>
  <?php else: ?>
    <a class="g-button ui-icon-left ui-state-disabled ui-corner-all">
      <span class="ui-icon ui-icon-seek-prev"></span><?= t("Previous") ?></a>
  <?php endif ?>
  </li>
  <li class="g-info"><?= $from_to_msg ?></li>
  <li class="g-text-right">
  <?php if ($next_page): ?>
    <a href="<?= str_replace('{page}', $next_page, $url) ?>" class="g-button ui-icon-right ui-state-default ui-corner-all">
      <span class="ui-icon ui-icon-seek-next"></span><?= t("Next") ?></a>
  <?php else: ?>
    <a class="g-button ui-state-disabled ui-icon-right ui-corner-all">
      <span class="ui-icon ui-icon-seek-next"></span><?= t("Next") ?></a>
  <?php endif ?>
  <?php if ($last_page): ?>
    <a href="<?= str_replace('{page}', $last_page, $url) ?>" class="g-button ui-icon-right ui-state-default ui-corner-all">
      <span class="ui-icon ui-icon-seek-end"></span><?= t("Last") ?></a>
  <?php else: ?>
    <a class="g-button ui-state-disabled ui-icon-right ui-corner-all">
      <span class="ui-icon ui-icon-seek-end"></span><?= t("Last") ?></a>
  <?php endif ?>
  </li>
</ul>
