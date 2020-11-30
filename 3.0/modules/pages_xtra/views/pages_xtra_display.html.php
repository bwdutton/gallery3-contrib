<?php defined("SYSPATH") or die("No direct script access.") ?>

<?php  if (module::get_var("pages_xtra", "show_sidebar")) : ?>
 <style type="text/css">
<?php  if (module::get_var("gallery", "active_site_theme") == "greydragon") : ?>
    #g-column-right {
      display: none;
    }
    .g-page-block-content {
      width: 99%;
    }
    <?php else: ?>
   #g-sidebar {
      display: none;
    }
    #g-content {
      width: 950px;
    }
    <?php endif ?>
  </style>
 <?php endif ?>

<div class="g-page-block">

<?php // Disable next line so that H1 is NOT auto-generated and auto-linked with Title. Manually write <h1> tags into body code, for static pages.
?>   
<?php/* <h1><?=  t($title) ?></h1>  */?>

          
<div class="g-page-block-content">
<br />
<!-- addthis Toolbox begin -->
<div id="g-view-menu"> 
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_preferred_1"></a>
<a class="addthis_button_preferred_2"></a>
<a class="addthis_button_preferred_3"></a>
<a class="addthis_button_compact"></a>
<a class="addthis_counter addthis_bubble_style"></a>
</div>	
</div>
<!-- addthis Toolbox end -->

    <?=t($body) ?>    
  </div>
</div>
