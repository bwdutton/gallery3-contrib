<?php defined("SYSPATH") or die("No direct script access.") ?>
<?php

    $menu = Menu::factory("root");
    module::event("site_menu", $menu, $this->theme, "");

?>
<?php if( !empty($menu->elements['add_menu']->elements) || !empty($menu->elements['options_menu']->elements) ): ?>
  <div id="sobriety-actions-menu" class="g-block">
    <h2><?= t("Actions") ?></h2>
    <div class="g-block-content">
      <ul class="g-metadata">
        <?php foreach($menu->elements['add_menu']->elements as $menu_element): ?>
        <li><a href="<?= $menu_element->url ?>" class="g-dialog-link"><?= $menu_element->label ?></a></li>
        <?php endforeach; ?>
        <?php foreach($menu->elements['options_menu']->elements as $menu_element): ?>
        <li><a href="<?= $menu_element->url ?>" class="g-dialog-link"><?= $menu_element->label ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
<?php endif; ?>