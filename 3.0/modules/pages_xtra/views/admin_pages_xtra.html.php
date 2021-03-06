<?php defined("SYSPATH") or die("No direct script access.") ?>
<script type="text/javascript">
  $("document").ready(function() {
    // using JS for adding link titles to avoid running t() for each tag
    $("#g-page-admin .g-page-name").attr("title", <?= t("Click to edit this page")->for_js() ?>);
    $("#g-page-admin .g-delete-link").attr("title", $(".g-delete-link:first span").html());

    // In-place editing for tag admin
    $(".g-editable").gallery_in_place_edit({
      form_url: <?= html::js_string(url::site("admin/pages_xtra/form_rename/__ID__")) ?>
    });
  });
</script>

<?php $pages_per_column = $pages->count()/5 ?>
<?php $column_page_count = 0 ?>

<div class="g-block">
  <h1> <?= t("Manage Pages_xtra") ?> </h1>
  <?= $form; ?>
  <div class="g-block-content">
    <table id="g-page-admin">
      <caption>
        <?= t2("There is one page", "There are %count pages", $pages->count()) ?><br />
        <a href="<?=url::site("admin/pages_xtra/createpage") ?>"><?=t("Add new page") ?></a>
      </caption>
      <tr>
        <td>
        <?php foreach ($pages as $i => $one_page): ?>
          <?php $current_letter = strtoupper(mb_substr($one_page->name, 0, 1)) ?>

          <?php if ($i == 0): /* first letter */ ?>
          <strong><?= html::clean($current_letter) ?></strong>
          <ul>
          <?php elseif ($last_letter != $current_letter): /* new letter */ ?>
          </ul>
            <?php if ($column_page_count > $pages_per_column): /* new column */ ?>
              <?php $column_page_count = 0 ?>
        </td>
        <td>
            <?php endif ?>
          <strong><?= html::clean($current_letter) ?></strong>
          <ul>
          <?php endif ?>
              <li>
                <span class="g-editable g-page-name" rel="<?= $one_page->id ?>"><?= html::clean($one_page->name) ?></span>
                <a href="<?= url::site("admin/pages_xtra/editpage/$one_page->id") ?>" class="g-edit-link g-button" title="<?= t("Edit this page") ?>"><span class="ui-icon ui-icon-pencil"><?= t("Edit this page") ?></span></a>
                <a href="<?= url::site("pages_xtra/show/" . $one_page->name) ?>" class="g-view-link g-button" title="<?= t("View this page") ?>" target="_blank"><span class="ui-icon ui-icon-info"><?= t("View this page") ?></span></a>
                <a href="<?= url::site("admin/pages_xtra/form_delete/$one_page->id") ?>" class="g-dialog-link g-delete-link g-button"><span class="ui-icon ui-icon-trash"><?= t("Delete this page") ?></span></a>
              </li>
          <?php $column_page_count++ ?>
          <?php $last_letter = $current_letter ?>
        <?php endforeach ?>
          </ul>
        </td>
      </tr>
    </table>
  </div>
</div>
