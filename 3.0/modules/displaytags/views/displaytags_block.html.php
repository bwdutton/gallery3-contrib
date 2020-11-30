<?php defined("SYSPATH") or die("No direct script access.") ?>
<div class="g-display-tags-block">
  <?php $not_first = 0; ?>
  <?php foreach ($tags as $tag): ?>
  <?= ($not_first++) ? "," : "" ?>
    <a href="<?= $tag->url() ?>"><?= html::clean($tag->name) ?></a>
  <?php endforeach ?>
</div>
