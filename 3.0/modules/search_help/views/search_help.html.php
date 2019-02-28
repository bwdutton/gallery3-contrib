
<script>
$(document).ready(function(){
	$("#g-search").focus(function() {
		$("<div id='arrow_box' class='arrow_box'><?= t("<b>Search help</b><br />+ stands for AND<br />-  stands for NOT<br />[no operator] implies OR.") ?></div>").appendTo("#g-quick-search-form");
	});
	$("#q").focus(function() {
		$("<div id='arrow_box' class='arrow_box'><?= t("<b>Search help</b><br />+ stands for AND<br />-  stands for NOT<br />[no operator] implies OR.") ?></div>").appendTo("#g-search-form");
	});
	$("#g-search, #q").keypress(function() {
		$("#arrow_box").fadeOut(1000);
	});
	$(document).on("click","#arrow_box", function() {
		$("#arrow_box").remove();
	});
});
</script>
