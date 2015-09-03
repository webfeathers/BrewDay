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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form" && isset($_POST['schedule']))) {
  $insertSQL = sprintf("INSERT INTO brew_schedule (brew_recipe_id, brew_planned_start, brew_batch_size_id) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['schedule']['recipe_id'], "text"),
                       GetSQLValueString($_POST['schedule']['brew_date'], "date"),
                       GetSQLValueString($_POST['schedule']['brew_size_id'], "text"));
  mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
  $Result1 = mysql_query($insertSQL, $StrikeRecipes) or die(mysql_error());
  $updateGoTo = "/brewSchedule.php?added=true";
	if (isset($_SERVER['QUERY_STRING'])) {
		$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
		$updateGoTo .= $_SERVER['QUERY_STRING'];
	}
	header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
$query_ingredients = "SELECT * FROM ingredients";
$ingredients = mysql_query($query_ingredients, $StrikeRecipes) or die(mysql_error());
$row_ingredients = mysql_fetch_assoc($ingredients);
$totalRows_ingredients = mysql_num_rows($ingredients);

if(isset($_POST['schedule'])){
	$brewsArray = array ();
	foreach($_POST['schedule'] as $key => $value){
		array_push($brewsArray, $key);
	}
	
	mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
	$query_recipeIngredients = sprintf("SELECT * FROM recipe_ingredients WHERE recipe_id in (%s)", implode(", ", $brewsArray));
	
	$recipeIngredients = mysql_query($query_recipeIngredients, $StrikeRecipes) or die(mysql_error());
	$row_recipeIngredients = mysql_fetch_assoc($recipeIngredients);
	$totalRows_recipeIngredients = mysql_num_rows($recipeIngredients);
}

$currentTab='brew';
	require("includes/pagetop.php");
	require("includes/nav.php");
?>
<!-- BEGIN content -->

<div class="container">
	<h1>Schedule a Brew </h1>
	
	<p>Here's what we're going to brew:</p>
<form method="post">
	
<? 
if(isset($_POST['brew'])){
foreach($_POST['brew'] as $key => $value){ 
	foreach($value['sizes'] as $sizes => $size){
//if($size['quantity'] > 0 ){ print_r($_POST['brew']); }
	$necessaryIngredients = Array();
	

		if($size['quantity'] > 0){		
			$pluralize = $size['quantity'] > 1 ? ' batches ' : ' batch ';		
			echo "<p>On " . $value['date'] 
			   . " brew " . $size['quantity'] 
			   . " " .  $size['batchSizeName'] 
			   . $pluralize . '  of ' 
			   . $value['brewname'] . "</p>";
			   echo '<input type="text" name="schedule[brew_date]" value="'.$value['date'].'" />';
			   echo '<input type="text" name="schedule[recipe_id]" value="'.$key.'" />';
		   echo '<input type="text" name="schedule[brew_size_id]" value="'.$size['batchSizeId'].'" />';
		   
		} //END if

	} //END foreach $value

} //end foreach
}//end if isset


?>
	
	
		<button class="btn btn-primary">Looks Good - Brew It</button>
		<?php if(isset($_POST['brew'])){ ?>
		<input type="hidden" name="MM_insert" value="form" />
		<?php } ?>
	</form>
	
</div>
<!-- END content -->

<h2>Ingredients</h2>
<div class="col-sm-12">
	<?php do { ?>
		<p>
			We Need [measurement of] <?php echo $row_ingredients['name']; ?>
			We HAVE <?php echo $row_ingredients['amount_on_hand']; ?>
		</p>
		<?php } while ($row_ingredients = mysql_fetch_assoc($ingredients)); ?>
</div>

<?php
	require("includes/pagebottom.php");
?>
<?php
mysql_free_result($ingredients);
if($recipeIngredients){
	mysql_free_result($recipeIngredients);
}
?>
