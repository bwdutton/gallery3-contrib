<?php defined("SYSPATH") or die("No direct script access.") ?>
[
<?php
    $thumb_arr=array();
    $items_id=array();;
    foreach ($items as $item) {
        array_push($items_id, $item->id);
        $thumb_arr[$item->id] = $item->thumb_img(array("class" => "g-exif-gps-thumbnail"));
    }
    $item_coordinates_all = ORM::factory("exif_coordinate")->where("item_id", "IN", $items_id)->find_all();
    foreach ($item_coordinates_all as $zero_index => $item_coordinates) {
        $thumb_html = base64_encode($thumb_arr[$item_coordinates->item_id]);
        $separator = $zero_index == count($item_coordinates_all) - 1 ? "\n" : ",\n";
?>
  {
    "type": "Feature",
    "properties": {
      "name": "<?= $item->name ?>",
      "image_html_base64": "<?= $thumb_html ?>",
      "url": "<?= url::abs_site("exif_gps/item/$item_coordinates->item_id"); ?>"
    },
    "geometry": {
      "type": "Point",
      "coordinates": [
        "<?= $item_coordinates->longitude; ?>",
        "<?= $item_coordinates->latitude; ?>"
      ]
    }
  }<?= $separator; ?>
<?php

    } ?>
]
