<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2013 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */
class author_installer {
  public static function install() {
    $db = Database::instance();
    $db->query("CREATE TABLE IF NOT EXISTS {author_records} (
                 `id` int(9) NOT NULL auto_increment,
                 `item_id` INTEGER(9) NOT NULL,
                 `author` TEXT,
                 `dirty` BOOLEAN default 1,
                 PRIMARY KEY (`id`),
                 KEY(`item_id`))
               DEFAULT CHARSET=utf8;");
    module::set_version("author", 1);
	module::set_var("author", "usage_terms", '');
	module::set_var("author", "credit", '');
	
    return true;
  }

  public static function activate() {
		gallery::set_path_env(
        [
        	getenv("PATH"),
           	module::get_var("gallery", "extra_binary_paths")
        ]);
		
		$exiv = exec('which exiv2');
		if ($exiv == '') {
			# Proper warning
  		}
  		else {
	  		module::set_var("author", "exiv_path", $exiv);
	  		$out = [];
	  		exec("$exiv -V", $out);
			$parts = explode(' ', $out[0]);
	  		module::set_var("author", "exiv_version", $parts[1]);
	  	}
  }

  public static function deactivate() {
  }

  public static function uninstall() {
    Database::instance()->query("DROP TABLE IF EXISTS {author_records};");
  }
}
