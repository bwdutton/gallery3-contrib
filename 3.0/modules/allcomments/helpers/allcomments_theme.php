<?php defined("SYSPATH") or die("No direct script access.");

class allcomments_theme
{
  public static function head($theme) {
    return $theme->css("allcomments.css");
  }
}

?>
