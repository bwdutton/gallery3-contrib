<?php defined("SYSPATH") or die("No direct script access.") ?>
  <h2>
    <?= t("TagsMap Admin") ?>
  </h2>
<div class="g-block">
  <h3>
    <?= t("Google Maps Settings") ?>
  </h3>
  <br/><div>
  <?=t("You may sign up for a Google APIs Console key"); ?> <a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key" target="_new">here</a>.</div><br/>
  <?= $googlemaps_form ?>
</div>

<div class="g-block">
  <h3>
    <?= t("Assign GPS Coordinates") ?>
  </h3>
  <?php $tags_per_column = $tags->count()/5 ?>
  <?php $column_tag_count = 0 ?>

  <table id="g-gps-tag-admin" class="g-block-content">
    <caption class="understate">
      <?= t2("There is one tag", "There are %count tags", $tags->count()) ?>
    </caption>
    <tr>
      <td>
        <?php foreach ($tags as $i => $tag): ?>
          <?php $current_letter = strtoupper(mb_substr($tag->name, 0, 1)) ?>

          <?php if ($i == 0): /* first letter */ ?>
            <strong><?= $current_letter ?></strong>
            <ul>
          <?php elseif ($last_letter != $current_letter): /* new letter */ ?>
            <?php if ($column_tag_count > $tags_per_column): /* new column */ ?>
               </td>
              <td>
              <?php $column_tag_count = 0 ?>
            <?php endif ?>

            </ul>
            <strong><?= $current_letter ?></strong>
            <ul>
          <?php endif ?>

          <li>
            <?= html::clean($tag->name) ?>
            <span class="understate">(<?= $tag->count ?>)</span>

            <a href="<?= url::site("admin/tagsmap/edit_gps/$tag->id") ?>"><?= t("Edit GPS") ?></a>

            <?php
              // Check and see if this ID already has GPS data, display a delete button if it does.
              $existingGPS = ORM::factory("tags_gps")
                             ->where("tag_id", "=", $tag->id)
                             ->find_all();
              if (count($existingGPS) > 0) {
              ?>
               | <a href="<?= url::site("admin/tagsmap/confirm_delete_gps/$tag->id") ?>"><?= t("Delete GPS") ?></a>
              <?php
              }
            ?>
          </li>

          <?php $column_tag_count++ ?>
          <?php $last_letter = $current_letter ?>
        <?php endforeach /* $tags */ ?>
        </ul>
      </td>
    </tr>
  </table>
</div>

<div class="g-block">
  <h3>
    <?= t("Remove Orphaned GPS Data") ?>
  </h3>
  <table id="g-orphaned-tag-admin" class="g-block-content">
    <tr><td>
      <a href="<?= url::site("admin/tagsmap/orphaned_tags") ?>">
        <?= t("Search For and Delete Orphaned Data") ?>
      </a>
    </td></tr>
  </table>
</div>
