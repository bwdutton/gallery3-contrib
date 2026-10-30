<?php defined("SYSPATH") or die("No direct script access.");

class fancybox_installer {
  public static function activate() {
    fancybox::check_config();
  }

  public static function deactivate() {
    site_status::clear("fancybox_config");
  }
}
?>