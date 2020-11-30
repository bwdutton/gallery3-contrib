<?php defined("SYSPATH") or die("No direct script access.") ?>
<ul class="g-metadata">
  <li>
    <strong class="caption"><?= t("Title:") ?></strong>
    <?= html::purify($item->title) ?>
  </li>
  <?php if ($item->description): ?>
  <li>
    <strong class="caption"><?= t("Description:") ?></strong>
     <?= nl2br(html::purify($item->description)) ?>
  </li>
  <?php endif ?>
  <?php if (!$item->is_album()): ?>
  <li>
    <strong class="caption"><?= t("File name:") ?></strong>
    <?= html::clean($item->name) ?>
  </li>
  <?php endif ?>
  <?php if ($item->is_album()): ?>
  <li>
    <strong class="caption"><?= t("Number of photos:") ?></strong>
    <?= html::clean($item->viewable()->descendants_count()) ?>
  </li>
  <?php endif ?>
  <?php if ($item->captured): ?>
  <li>
    <strong class="caption"><?= t("Captured:") ?></strong>
    <?= date("M j, Y H:i:s", $item->captured)?>
  </li>
  <?php endif ?>
</ul>
