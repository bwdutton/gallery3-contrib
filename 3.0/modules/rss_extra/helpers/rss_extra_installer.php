<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Extra RSS feeds for Menalto Gallery 3
 * Copyright (C) 2010 Serguei Dosyukov
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
class rss_extra_installer {
  static function install() {
    module::set_version("rss_extra", 1);
  }

  static function uninstall() {
  }
}
