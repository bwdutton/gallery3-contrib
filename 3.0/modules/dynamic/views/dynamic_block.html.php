<?php defined("SYSPATH") or die("No direct script access.") ?>
<div class="g-dynamic-block">
  <ul>
  <?php foreach ($albums as $album => $text): ?>
  <li>
  <a href="<?= url::site("dynamic/$album") ?>"><?= t($text) ?></a>
  </li>
  <?php endforeach ?>
  </ul>
</div>
