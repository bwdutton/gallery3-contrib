<?php defined("SYSPATH") or die("No direct script access.") ?>
<?= t("Tags:") ?>
<?php $i = 0 ?>
<?php foreach ($tags as $tag): ?>
<?= (++$i != 1) ? ", " : " " ?>
<a href="<?= url::site("tag/{$tag->name}") ?>"><?= $tag->name ?></a><?php endforeach ?>
