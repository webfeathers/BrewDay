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
$query_Recipes = "SELECT * FROM recipes ORDER BY name ASC";
$Recipes = mysql_query($query_Recipes, $StrikeRecipes) or die(mysql_error());
$row_Recipes = mysql_fetch_assoc($Recipes);
$totalRows_Recipes = mysql_num_rows($Recipes);

mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
$query_batch_sizes = "SELECT * FROM batch_sizes";
$batch_sizes = mysql_query($query_batch_sizes, $StrikeRecipes) or die(mysql_error());
$row_batch_sizes = mysql_fetch_assoc($batch_sizes);
$totalRows_batch_sizes = mysql_num_rows($batch_sizes);

	$currentTab='brew';
	require("includes/pagetop.php");
	require("includes/nav.php");
?>

<!-- BEGIN content -->
<div class="container">
	<h1>Schedule a Brew </h1>

	<form name="addBrews" action="calculateBrews.php" method="post">
		<?php do { ?>
		<div class="row">
			<div class="col-sm-6">
				<input type="hidden" name="brew[<?php echo $row_Recipes['id']; ?>][brewname]" value="<?php echo $row_Recipes['name']; ?>" />
 				<h3><?php echo $row_Recipes['name']; ?></h3>
			</div>
			<div class="col-sm-2">
				<span for="brew[<?php echo $row_Recipes['id']; ?>][date]">Brew Date:</span>
				<input class="col-sm-4 form-control datepicker" 
					id="brew[<?php echo $row_Recipes['id']; ?>][date]" 
					name="brew[<?php echo $row_Recipes['id']; ?>][date]" 
					type="text" placeholder="yyyy-mm-dd">
			</div>
			
			<?
				//addButtons has it's own loop over the different batch sizes
				include("includes/addButtons.php");
			?>
		</div>
			<?php } while ($row_Recipes = mysql_fetch_assoc($Recipes)); ?>
		<div class="row">
			<div class="col-sm-4 col-sm-offset-8 text-center">
				<button class="btn btn-primary" type="submit">Check Ingredients</button>
			</div>
		</div>
	</form>
</div>
<!-- END content -->



<?php
	require("includes/pagebottom.php");
?>
<?php
mysql_free_result($Recipes);

mysql_free_result($batch_sizes);
?>
