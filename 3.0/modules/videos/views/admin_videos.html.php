<?php defined("SYSPATH") or die("No direct script access.") ?>
<?php // rWatcher Edit: This file used to be admin_server_add.html.php ?>
<div class="g-block">
  <h1> <?= t("Add videos from server administration") ?> </h1>
  <div class="g-block-content">
    <?= $form ?>
    <h2><?= t("Authorized paths") ?></h2>
    <ul id="g-videos-paths">
      <?php if (empty($paths)): ?>
      <li class="g-module-status g-info"><?= t("No authorized image source paths defined yet") ?></li>
      <?php endif ?>
      <?php foreach ($paths as $id => $path): ?>
      <li>
        <?= html::clean($path) ?>
        <a href="<?= url::site("admin/videos/remove_path?path=" . urlencode($path) . "&amp;csrf=$csrf") ?>"
           id="icon_<?= $id ?>"
           class="g-remove-dir g-button"><span class="ui-icon ui-icon-trash"><?= t("delete") ?></span></a>
      </li>
      <?php endforeach ?>
    </ul>
  </div>
</div>
