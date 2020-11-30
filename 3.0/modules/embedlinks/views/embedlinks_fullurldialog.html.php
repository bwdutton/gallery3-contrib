<?php defined("SYSPATH") or die("No direct script access.") ?>
<style>
input[type="text"] {
  width: 95%;
}
</style>
<h1 style="display: none;"><?= t("URLs") ?></h1>
<div id="g-embed-links-full-url-data">
<?php $counter = 0; ?>
<?php for ($i = 0; $i < count($titles); $i++): ?>    
  <table class="g-links-full-url" >
  <thead><tr><th colspan="2"><?= t($titles[$i][0]) ?></th></thead>
    <tbody>
          <?php for ($j = $counter; $j < $titles[$i][1]+$counter; $j++): ?>    
            <tr>
              <td width="100"><?= t($details[$j][0]) ?></td>
              <td><input type="text" onclick="this.focus(); this.select();" value="<?= $details[$j][1] ?>" readonly></td>
            </tr>
          <?php endfor ?>
          <?php $counter+= $titles[$i][1]; ?>      
    </tbody>
  </table>
<?php endfor ?>
</div>
