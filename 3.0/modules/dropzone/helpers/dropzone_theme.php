<?php defined("SYSPATH") or die("No direct script access.");

class dropzone_theme_Core {
	public static function head($theme) {
		return $theme->script('dropzone.js').$theme->css("dropzone.css");
	}
}
