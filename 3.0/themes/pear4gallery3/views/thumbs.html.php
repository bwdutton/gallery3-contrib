<?php defined("SYSPATH") or die("No direct script access.") ?>
<?= $theme->paginator() ?>
<?php  $javaScript = ""; ?>
<?php if (count($children)): ?>
  <?php foreach ($children as $i => $child): ?>
  <?php if ($i > 50) break; ?>
    <?php $item_class = "g-photo"; ?>
    <?php if ($child->is_album()): ?>
      <?php $item_class = "g-album\" onclick=\"window.location='".$child->url()."/'+getAlbumHash((typeof skimimg === 'undefined') ? 0 : skimimg);"; ?>
    <?php endif ?>
    <?php $img_class = "g-thumbnail"; ?>
    <?php if ($child->is_photo()): ?>
      <?php $img_class = "g-thumbnail p-photo"; ?>
    <?php elseif ($child->is_movie()): ?>
      <?php $item_class = "g-video\" onclick=\"window.location='".$child->url()."';"; ?>
      <?php $img_class = "g-thumbnail p-movie"; ?>
    <?php endif ?>
  <div id="g-item-id-<?= $child->id ?>" class="g-item gallery-thumb <?= $item_class ?>" title="<?= $child->description?>">
    <?= $theme->thumb_top($child) ?>
    <?php if ($child->is_album() || $child->is_movie()): ?>
        <div class="gallery-thumb-round"></div>
    <?php endif ?>
<?= $theme->context_menu($child, "#g-item-id-{$child->id} .g-thumbnail") ?>
      <?php if ($child->has_thumb()): ?>
      <img id="thumb_<?= $child->id ?>" alt="<?= $child->id ?>" class="<?= $img_class ?>" style="width: 200px; height; 200px;" src="<?= $theme->url("icons/empty_image.png") ?>"/>
 <?php $javaScript .= "thumbImages['thumb_" . $child->id . "'] = '" . $child->thumb_url() . "';\n" ?>
        <?php// = $child->thumb_img(array("class" => $img_class, "id" => "thumb_$child->id", "style" => "width: 200px; height 200px;")) ?>
      <?php else: ?>
        <span style="display: block; width: 200px; height: 200px;"></span>
      <?php endif ?>
    <?php if ($child->is_movie()): ?>
      <span class="p-video"></span>
    <?php endif ?>
<?php// Begin skimming
if($child->is_album()):
  $granchildren = $child->viewable()->children();
$offset = 0;
$step = round(200/min(count($granchildren),module::get_var("th_pear4gallery3", "skimm_lim", "50")));
foreach ($granchildren as $i => $granchild):?>
      <?php if(++$i > module::get_var("th_pear4gallery3", "skimm_lim", "50")) break; ?>
      <?php if ($granchild->has_thumb()): ?>
      <?= $granchild->thumb_img(array("style" => "display: none;")) ?>
 <?php $javaScript .= "thumbImages['area_" . $granchild->id ."'] = '" . $granchild->thumb_img(array("style" => "display: none;")) . "';\n" ?>
    <div class="skimm_div" style="height: 200px; width: <?=$step?>px; left: <?=$offset?>px; top: 0px;" onmouseover="$('#thumb_<?=$child->id?>').attr('src', '<?=$granchild->thumb_url()?>');skimimg=<?=$i-1?>;" id="area_<?=$granchild->id?>"></div>
      <?php endif ?>
<?php		$offset+=$step;
endforeach;
endif;
// End skimming // ?>
    <p class="giTitle <?php if(!$child->is_album()) print 'center';?>"><?= html::purify(text::limit_chars($child->title, 20)) ?> </p>
    <?php if($child->is_album() && !module::get_var("th_pear4gallery3", "hide_item_count")): ?><div class="giInfo"><?= count($granchildren)?> photos</div><?php endif ?>
</div>
  <?php endforeach ?>
<script  type="text/javascript">
<?php $item_no = ($page*$page_size)-$page_size; ?>
<?php foreach ($children as $i => $child): ?>
<?php if(!($child->is_album() || $child->is_movie())): ?>
slideshowImages[<?= $item_no++ ?>] = (['<?= $child->resize_url() ?>', '<?= $child->id ?>', '<?= $child->resize_width ?>','<?= $child->resize_height ?>', '<?= htmlentities($child->title, ENT_QUOTES) ?>', '<?php if (access::can("view_full", $child)) print "true" ?>', '<?= $child->url() ?>']);
<?php endif ?>
<?php endforeach ?>
<?= $javaScript ?>
</script>
<?php else: ?>
  <?php if ($user->admin || access::can("add", $item)): ?>
  <?php $addurl = url::site("uploader/index/$item->id") ?>
  <li><?= t("There aren't any photos here yet! <a %attrs>Add some</a>.",
    array("attrs" => html::mark_clean("href=\"$addurl\" class=\"g-dialog-link\""))) ?></li>
  <?php else: ?>
  <li><?= t("There aren't any photos here yet!") ?></li>
  <?php endif ?>
<?php endif ?>
