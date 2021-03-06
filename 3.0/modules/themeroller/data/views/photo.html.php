<?php defined("SYSPATH") or die("No direct script access.") ?>

<?php if (access::can("view_full", $theme->item())): ?>
<!-- Use javascript to show the full size as an overlay on the current page -->
<script type="text/javascript">
  $(document).ready(function() {
    $(".g-fullsize-link").click(function() {
      $.gallery_show_full_size(<?= html::js_string($theme->item()->file_url()) ?>, "<?= $theme->item()->width ?>", "<?= $theme->item()->height ?>");
      return false;
    });
  });
</script>
<?php endif ?>

<div id="g-item">
  <?= $theme->photo_top() ?>

  <?= $theme->paginator() ?>

  <div id="g-photo">
    <?= $theme->resize_top($item) ?>
    <?php if (access::can("view_full", $item)): ?>
    <a href="<?= $item->file_url() ?>" class="g-fullsize-link" title="<?= t("View full size")->for_html_attr() ?>">
      <?php endif ?>
      <?= $item->resize_img(array("id" => "g-item-id-{$item->id}", "class" => "g-resize")) ?>
      <?php if (access::can("view_full", $item)): ?>
    </a>
    <?php endif ?>
    <?= $theme->resize_bottom($item) ?>
  </div>

  <div id="g-info">
    <h1><?= html::purify($item->title) ?></h1>
    <div><?= nl2br(html::purify($item->description)) ?></div>
  </div>

  <?= $theme->photo_bottom() ?>
</div>
