<?php defined("SYSPATH") or die("No direct script access.") ?>
<ul id="g-update-list">
<?php foreach($update_links as $title => $url): ?>
  <li style="clear: both;">
    <a href="<?= $url ?>">
      <?= t($title) ?>
    </a>
  </li>
<?php endforeach ?>
</ul>
