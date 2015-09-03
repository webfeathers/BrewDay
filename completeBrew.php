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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form")) {
  $updateSQL = sprintf("UPDATE brew_schedule SET brew_completion_date=%s WHERE brew_id=%s",
                       GetSQLValueString($_POST['brew_completed_date'], "date"),
                       GetSQLValueString($_POST['brew_id'], "int"));

  mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
  $Result1 = mysql_query($updateSQL, $StrikeRecipes) or die(mysql_error());

  $updateGoTo = "brewSchedule.php?completed=true";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$maxRows_thisBrew = 10;
$pageNum_thisBrew = 0;
if (isset($_GET['pageNum_thisBrew'])) {
  $pageNum_thisBrew = $_GET['pageNum_thisBrew'];
}
$startRow_thisBrew = $pageNum_thisBrew * $maxRows_thisBrew;

$colname_thisBrew = "13";
if (isset($_POST['brew_id'])) {
  $colname_thisBrew = $_POST['brew_id'];
}
mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
$query_thisBrew = sprintf("SELECT S.*,         R.name            AS recipe_name,         B.batch_size_name AS batch_size FROM brew_schedule S         LEFT JOIN recipes R                ON S.brew_recipe_id = R.id         LEFT JOIN batch_sizes B                ON S.brew_batch_size_id = B.batch_size_id WHERE brew_id = %s ", GetSQLValueString($colname_thisBrew, "int"));
$query_limit_thisBrew = sprintf("%s LIMIT %d, %d", $query_thisBrew, $startRow_thisBrew, $maxRows_thisBrew);
$thisBrew = mysql_query($query_limit_thisBrew, $StrikeRecipes) or die(mysql_error());
$row_thisBrew = mysql_fetch_assoc($thisBrew);

if (isset($_GET['totalRows_thisBrew'])) {
  $totalRows_thisBrew = $_GET['totalRows_thisBrew'];
} else {
  $all_thisBrew = mysql_query($query_thisBrew);
  $totalRows_thisBrew = mysql_num_rows($all_thisBrew);
}
$totalPages_thisBrew = ceil($totalRows_thisBrew/$maxRows_thisBrew)-1;


$currentTab='brew';
	require("includes/pagetop.php");
	require("includes/nav.php");
?>

<!-- BEGIN content -->

<?php
print_r($_POST);
?>

<div class="container">
	<h1>Complete a brew <a href="/addBrew.php" class="btn btn-primary pull-right">Cancel</a> </h1>
	<?php do { ?>
		<h3>Done with the Brew?</h3>
		<p>This <?php echo $row_thisBrew['batch_size']; ?> batch of <?php echo $row_thisBrew['recipe_name']; ?> was scheduled to start on <?php echo $row_thisBrew['brew_planned_start']; ?>, and actually started on <?php echo $row_thisBrew['brew_actual_started_date']; ?>.</p>
		<form action="<?php echo $editFormAction; ?>" name="form" method="POST">
			<p>Set the brew completion to today (<?php echo date("Y-m-d") ?>) by clicking
				<button type="submit" class="btn btn-primary">Yes! We're done brewing</button>
			</p>
			<input type="hidden" name="brew_completed_date" value="<?php echo date("Y-m-d") ?>"/>
			<input type="hidden" name="brew_id" value="<?php echo $row_thisBrew['brew_id']; ?>" />
			<input type="hidden" name="startit" value="true" />
			<input type="hidden" name="MM_update" value="form" />
		</form>
		<?php } while ($row_thisBrew = mysql_fetch_assoc($thisBrew)); ?>
</div>
<!-- END content -->

<?php
	require("includes/pagebottom.php");
?>
<?php
mysql_free_result($thisBrew);
?>
