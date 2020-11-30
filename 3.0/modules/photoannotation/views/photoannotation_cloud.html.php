<?php defined("SYSPATH") or die("No direct script access.") ?>
<ul>
  <?php foreach ($users as $user): ?>
  <li class="size<?=(int)(($user->size / $max_count) * 7) ?>">
    <span><?= $user->size ?> photos are tagged with </span>
    <a href="<?= $user->url ?>"><?= html::clean($user->name) ?></a>
  </li>
  <?php endforeach ?>
</ul>
