<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2012 Bharat Mediratta
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

class Dupcheck_Core {
  static function stats() {
    $missing_md5s = db::build()
      ->select("items.id")
      ->from("items")
      ->join("fullsize_md5sums", "items.id", "fullsize_md5sums.item_id", "left")
      ->where("type", "=", "photo")
      ->and_open()
      ->where("fullsize_md5sums.item_id", "IS", null)
      ->close()
      ->execute()
      ->count();

    $total_items = ORM::factory("item")->where("type", "=", "photo")->count_all();
    if (!$total_items) {
      return array(0, 0, 0);
    }
    $percent = round(100 * (($total_items - $missing_md5s) / $total_items));
    return array($missing_md5s, $total_items, $percent);
  }

  static function grabmd5($item) {
    if($item->is_photo()){
      $file_md5 = md5_file($item->file_path());
      $dupcheck = ORM::factory("fullsize_md5sum")
      ->where("itemmd5", "=", $file_md5)
      ->find_all();
      if($dupcheck->count() > 0) {
        if(isset($GLOBALS['ignoredupewarning'])){
          unset($GLOBALS['ignoredupewarning']);
        } else {
          message::warning(t("The image just uploaded: '".$item->title."' appears to be a duplicate! Click <a class=\"g-dialog-link g-quick-delete ui-icon-trash\" href=\"".url::site("quick/form_delete/$item->id?csrf=__CSRF__")."\">-here-</a> to delete it."));
        }
      }
// Error check for bad md5sum?
      if(strlen($file_md5) != 32){
        message::warning(t("Image: ".$item->file_path()." has an MD5 Err. ($file_md5)"));
      }
      $record = ORM::factory("fullsize_md5sum");
      $record->item_id = $item->id;
      $record->itemmd5 = $file_md5;
      $record->save();
    }
  }

  static function updatemd5($item) {
    if($item->is_photo()){
      $file_md5 = md5_file($item->file_path());
// Error check for bad md5sum?
      if(strlen($file_md5) != 32){
        message::warning(t("Image: ".$item->file_path()." has an MD5 Err. ($file_md5)"));
      }
      $record = ORM::factory("fullsize_md5sum")->where("item_id", "=", $item->id)->find();
      $record->itemmd5 = $file_md5;
      $record->save();
    }
  }

  static function check_index() {
    list ($remaining) = dupcheck::stats();
    if ($remaining) {
      site_status::warning(
        t('Your MD5 index needs to be updated.  <a href="%url" class="g-dialog-link">Fix this now</a>',
          array("url" => html::mark_clean(url::site("admin/maintenance/start/dupcheck_task::update_md5s?csrf=__CSRF__")))),
          "md5_index_out_of_date");
    }
  }
}
