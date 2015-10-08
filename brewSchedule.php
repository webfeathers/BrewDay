<?php require_once('Connections/StrikeRecipes.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
$query_planned_brews = "SELECT S.*, 
       R.name            AS recipe_name, 
       B.batch_size_name AS batch_size 
FROM   brew_schedule S 
       LEFT JOIN recipes R 
              ON S.brew_recipe_id = R.id 
       LEFT JOIN batch_sizes B 
              ON S.brew_batch_size_id = B.batch_size_id ";
$planned_brews = mysql_query($query_planned_brews, $StrikeRecipes) or die(mysql_error());
$row_planned_brews = mysql_fetch_assoc($planned_brews);
$totalRows_planned_brews = mysql_num_rows($planned_brews);

$currentTab='brew';
	require("includes/pagetop.php");
	require("includes/nav.php");
?>

<!-- BEGIN content -->

<div class="container">
	<h1>Brew Schedule <a href="/addBrew.php" class="btn btn-primary pull-right">Add a Brew</a> </h1>
<?php if(isset($_GET['added'])){ ?>
	<div class="alert alert-success">Brews scheduled!</div>
<?php } ?>
<?php if(isset($_GET['started'])){ ?>
	<div class="alert alert-success">Brews Started!</div>
<?php } ?>
<?php if(isset($_GET['completed'])){ ?>
	<div class="alert alert-success">Brews Completed!</div>
<?php } ?>

			<h3>Here's the line-up:</h3>

		<table class="table">
			<thead>
				<th>Recipe</th>
				<th>Batch Size</th>
				<th>Planned Brew Date</th>
				<th>Ingredients On Hand</th>
				<th>Ingredients Pending</th>
				<th>Brew Started</th>
				<th>Brew Complete</th>
			</thead>
			<tbody>
				<?php do { ?>
				<td><?php echo $row_planned_brews['recipe_name']; ?></td>
					<td><?php echo $row_planned_brews['batch_size']; ?></td>
					<td><?php echo $row_planned_brews['brew_planned_start']; ?></td>
					<td>ing on hand</td>
					<td>ing pending</td>
					<td><?php
							if( $row_planned_brews['brew_actual_started_date'] != ''){
								echo $row_planned_brews['brew_actual_started_date'];
							} else {
								?><button class="btn btn-info btn-sm" onclick="startBrew(<?php echo $row_planned_brews['brew_id'];?>);">start brew</button><?php
							}?></td>
					<td><?php
							if( $row_planned_brews['brew_completion_date'] != ''){
								echo $row_planned_brews['brew_completion_date'];
							} else {
								if( $row_planned_brews['brew_actual_started_date'] != '' ) {
								?><button class="btn btn-info btn-sm" onclick="completeBrew(<?php echo $row_planned_brews['brew_id'];?>);">complete brew</button><?php
								}
							}?></td>
					</tbody>
					<?php } while ($row_planned_brews = mysql_fetch_assoc($planned_brews)); ?>
		</table>
		
</div>
<form id="startBrew" method="post" action="startBrew.php">
	<input type="hidden" name="brew_id" id="start_brew_id" value="">
</form>
<form id="completeBrew" method="post" action="completeBrew.php">
	<input type="hidden" name="brew_id" id="complete_brew_id" value="">
</form>
<!-- END content -->

<?php
	require("includes/pagebottom.php");
?>
<?php
mysql_free_result($planned_brews);
?>