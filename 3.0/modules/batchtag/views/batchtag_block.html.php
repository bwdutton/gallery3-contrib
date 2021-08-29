<?php defined("SYSPATH") or die("No direct script access.") ?>
<script type="text/javascript">
  $("#g-batch-tag-form").ready(function() {
    var url = '<?= url::site("tags/autocomplete") ?>';
    $("#g-batch-tag-form input:text").gallery_autocomplete(url, {multiple: true});
  });
</script>
<?= $batch_tag_form ?>
