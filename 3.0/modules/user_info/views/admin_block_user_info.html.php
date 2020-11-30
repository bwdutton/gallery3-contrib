<?php defined("SYSPATH") or die("No direct script access.") ?>

<div>
  <p><?= t("This will display information about users when they Login, Logout, Failed Login Attempts, Admin Re-Authenticate, and User Created.
   It can be configured <a href=\"%user_info_configure\">here</a>.<br><br> User Created - Logs users that the Admin creates and can log the users that are created by visitors when using the Registration Module and the Registration Module is set to 'Visitors can create accounts and no administrator approval is required'.",
          array("user_info_configure" => html::mark_clean(url::site("admin/user_info")))) ?></p>
</div>

<div>
<table border="2">

<!-- First Row - Displays Number of Records -->
<tr>
<?php if ($number_of_records > 0) { ?>
	<td align="center" colspan="6"><?= t("Number of Records: %number_of_records",array("number_of_records" => $number_of_records)) ?></td>
	<?php } else { ?>
	<td align="center" colspan="6"><?= t("Number of Records: 0") ?></td>
	<?php } ?> 
</tr>

<!--Second Row - List the Page Numbers for Pagination if there are records in the Database -->
<?php if ($number_of_records) { ?>
<tr>
	<td align="center" colspan="6"><?= $pager ?></td>
</tr>
<?php } ?>

<!--Third Row - Headers -->
<tr>
  <th><?= t("ID") ?></th>
  <th><?= t("User ID") ?></th>
  <th><?= t("User Name") ?></th>
  <th><?= t("IP Address") ?></th>
  <th><?= t("Time Stamp") ?></th>
  <th><?= t("Action") ?></th>
</tr>

<!--Forth Row etc.. - Lists the actual data -->
<?php if ($number_of_records > 0) { ?>
<?php foreach($data as $myData) { ?>
<tr>
	<td><?php echo $myData->id ?></td>
	
	<td><?php echo $myData->user_id ?></td>
	
	<td>
		<?php if ($myData->user_id){ ?>
			<a href="<?= url::site("user_profile/show/$myData->user_id") ?>" target="_blank"><?= html::clean($myData->user_name) ?> </a>
		<?php } else { ?>
			<?php echo $myData->user_name ?><br>
		<?php } ?>
	</td>
	
	<td>
		<a href="<?= url::site("admin/user_info/lookupip?ip=$myData->ip_address") ?>" target="_blank"><?= html::clean($myData->ip_address) ?> </a>	
	</td>

	<td>
		<?php if ($use_default_gallery_date_format == "Yes") { ?>
			<?=	gallery::date_time($myData->time_stamp) ?>
		<?php
		} else {
			echo date($date_format,$myData->time_stamp);	
		}
		?>
	</td>

	<td>
		<?php 
		switch ($myData->action)
			{
				case "Failed Login":
				echo "<font color=$color_failed_login>";		
				echo $myData->action;
				echo "</font>";
				break;
				
				case "Login":
				echo "<font color=$color_login>";
				echo $myData->action;
				echo "</font>";
				break;

				case "Logout":
				echo "<font color=$color_logout>";
				echo $myData->action;
				echo "</font>";
				break;

				case "Re-Authenticate Login":
				echo "<font color=$color_re_authenticate_login>";
				echo $myData->action;
				echo "</font>";
				break;

				case "User Created":
				echo "<font color=$color_user_created>";
				echo $myData->action;
				echo "</font>";
				break;

				default:
				echo "<font color='#000000'>";
				echo $myData->action;
				echo "</font>";
			}
		?>
	</td>
</tr>
	<?php } ?>
<?php } else { ?>
<tr>
	<td colspan="6"><center><b><?= t("No Records in Database") ?></b></center></td>
</tr>
<?php } ?>

</table>
</div>
