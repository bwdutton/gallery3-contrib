<?php defined("SYSPATH") or die("No direct script access.");

class rss_extra_event_Core {

  static function admin_menu($menu, $theme) {
    $menu
      ->get("settings_menu")
      ->append(Menu::factory("link")
               ->id("rss_extra")
               ->label(t("RSS Extra"))
               ->url(url::site("admin/rss_extra")));
  }
}
