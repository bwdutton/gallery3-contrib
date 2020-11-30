<?php defined("SYSPATH") or die("No direct script access.") ?>

<script type="text/javascript">
function ChangeLocale( locale ) {
    var old_locale_preference = <?= html::js_string($selected) ?>;
    if (old_locale_preference == locale) {
      return;
    }

    var expires = -1;
    if (locale) {
      expires = 365;
    }
    $.cookie("g_locale", locale, {"expires": expires, "path": "/"});
    window.location.reload(true);
}
</script>

<?php $i = 0 ?>
<?php foreach ($installed_locales as $locale => $value): ?>
  <?php if ($i>0) : ?>  <?php if ($i>1) : ?> | <?php endif ?> <a href="javascript:ChangeLocale( '<?= $locale ?>' )"> <?= html::purify($value) ?> </a> <?php endif ?>   <?php $i++ ?>
<?php endforeach ?>

