<?php defined("SYSPATH") or die("No direct script access.") ?>
<?php if (module::get_var("pages", "show_sidebar")) : ?>
  <style type="text/css">
    <?php if (module::get_var("gallery", "active_site_theme") == "greydragon") : ?>
    #g-column-right {
      display: none;
    }
    .g-page-block-content {
      width: 99%;
    }
    <?php else: ?>
    #g-sidebar {
      display: none;
    }
    #g-content {
      width: 950px;
    }
    <?php endif ?>
  </style>
<?php endif ?>
<div class="g-page-block">
  <h1> <?= t($title) ?> </h1>
  <div class="g-page-block-content">
    <?=t($body) ?>
  </div>
</div>
