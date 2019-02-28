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
class delete_cache_task_Core {
  static function available_tasks() {
    return array(Task_Definition::factory()
                 ->callback("delete_cache_task::delete_cache")
                 ->name(t("Delete cache"))
                 ->description(t("Truncate the cache table from the database."))
                 ->severity(log::SUCCESS),
                 );
  }

  static function delete_cache($task) {
    $start = microtime(true);

    $total = $task->get("total");
    if (empty($total)) {
      $task->set("total", $total = db::build()->count_records("caches"));
      $task->set("last_id", 0);
      $task->set("completed", 0);
    }

    $last_id = $task->get("last_id");
    $completed = $task->get("completed");

    foreach (ORM::factory("cache")
             ->where("id", ">", $last_id)
             ->find_all(100) as $item) {

      $last_id = $item->id;
      $completed++;

      if ($completed == $total || microtime(true) - $start > 1.5) {
        break;
      }
    }

    $task->set("completed", $completed);
    $task->set("last_id", $last_id);

    if ($total == $completed) {
      $task->done = true;
      $task->state = "success";
      $task->percent_complete = 100;
      $db = Database::instance();
      $db->query("TRUNCATE TABLE {caches}");
    } else {
      $task->percent_complete = round(100 * $completed / $total);
    }
    $task->status = t2("One row updated", "%count / %total rows truncated from the cache table", $completed,
                       array("total" => $total));
  }
}
