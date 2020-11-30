<?php defined("SYSPATH") or die("No direct script access.") ?>
<ul id="g-contact-owner">
  <?php if (!empty($ownerLink)): ?>
  <li style="clear: both">
    <?= $ownerLink ?>
  </li>
  <?php endif  ?>

  <?php if (!empty($userLink)): ?>
  <li style="clear: both">
    <?= ($userLink); ?>
  </li>
  <?php endif ?>
</ul>

