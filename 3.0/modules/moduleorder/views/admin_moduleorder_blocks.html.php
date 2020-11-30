<?php defined("SYSPATH") or die("No direct script access.") ?>
<?php $indent_provider = module::get_var("gallery", "identity_provider") ?>
<?php foreach ($modules as $ref => $text): ?>
<?php $moduleinfo = module::info($text) ?>
<?php if ($text == "gallery" || $text == $indent_provider || $moduleinfo->name == ""): ?>
<li style="background-color:#A8A8A8; margin:0.5em; padding:0.3em 0.8em; display: none" ref="<?= $ref ?>"><?= $text ?></li>
<?php else: ?>
<li class="g-draggable" ref="<?= $ref ?>"><?= $moduleinfo->name ?></li>
<?php endif ?>
<?php endforeach ?>
