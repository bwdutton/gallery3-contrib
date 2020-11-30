<?php defined("SYSPATH") or die("No direct script access.") ?>
<script>
  var parent_path = "<?= $parent_path ?>";
  var current_path = "<?= $resource->path ?>";
  var resource_type = "album";
</script>
<ul id="wc-thumb-grid" class="ui-helper-clearfix">
  <?php foreach ($resource->children as $child): ?>
  <li class="wc-thumb-grid-cell" ref="<?= $child->path ?>">
      <a class="wc-child-link wc-image-block" href="#">
        <?php if ($child->has_thumb): ?>
        <img src="<?= $child->thumb_url ?>" title="<?= $child->title ?>" />
        <?php else: ?>
        <span><?= $child->title ?></span>
        <?php endif ?>
      </a>
  </li>
  <?php endforeach ?>
</ul>

