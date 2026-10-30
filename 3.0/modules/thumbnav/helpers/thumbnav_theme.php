<?php defined("SYSPATH") or die("No direct script access.");

class ThumbNav_theme_Core {

  public static function head($theme) {
    $theme->css("thumbnav.css");
  }
}