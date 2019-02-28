			<div id="prev" class="floatlinks">
			<? if ($theme->previous_item): ?>
			<p><a href="<?= $theme->previous_item->url() ?>"><em><?= t("Previous item") ?> </em></a></p>
			<? endif ?>
			</div>
			<div id="next" class="floatlinks">
			<? if ($theme->next_item): ?>
			<p><a href="<?= $theme->next_item->url() ?>"><em><?= t("Next item") ?> </em></a></p>
			<? endif ?>
			</div>
    </div>
<? if (module::get_var("hover_navigation", "navigation")  == true) : ?>
    <!--Move navigation due to hover navigaton module setting-->		
	<script type="text/javascript">
		$("ul.g-paginator.ui-helper-clearfix").insertAfter($('#entire_image'));
	</script>
	<style type="text/css">
		#g-item #g-photo,
		#g-item #g-movie {
		padding: 0 0;
		}
	</style>
	<!--change padding above to make more room due to hover navigaton module setting-->
<? endif ?>