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
$query_measurements = "SELECT * FROM measurements";
$measurements = mysql_query($query_measurements, $StrikeRecipes) or die(mysql_error());
$row_measurements = mysql_fetch_assoc($measurements);
$totalRows_measurements = mysql_num_rows($measurements);

mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
$query_ingredients = "SELECT I.*, 	S.supplier_name AS supplier_name, 	Q.name AS measurement_name FROM ingredients I  LEFT JOIN suppliers S	ON S.id = I.supplier  LEFT JOIN measurements Q	ON Q.id = I.measurement ";
$ingredients = mysql_query($query_ingredients, $StrikeRecipes) or die(mysql_error());
$row_ingredients = mysql_fetch_assoc($ingredients);
$totalRows_ingredients = mysql_num_rows($ingredients);

$colname_thisRecipe = "-1";
if (isset($_GET['rid'])) {
  $colname_thisRecipe = $_GET['rid'];
}
mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
$query_thisRecipe = sprintf("SELECT	R.name as recipe_name, 		R.id as recipe_id, 		I.*,         V.*,         measurements.name AS measurement_name FROM recipes R LEFT JOIN recipe_ingredients I ON R.id = I.recipe_id LEFT JOIN ingredients V on I.ingredient_id = V.ID LEFT JOIN measurements on V.measurement = measurements.id   WHERE R.id = %s", GetSQLValueString($colname_thisRecipe, "int"));
$thisRecipe = mysql_query($query_thisRecipe, $StrikeRecipes) or die(mysql_error());
$row_thisRecipe = mysql_fetch_assoc($thisRecipe);
$totalRows_thisRecipe = mysql_num_rows($thisRecipe);

//Update each entry
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
	mysql_select_db($database_StrikeRecipes, $StrikeRecipes);

	//Delete the old values
	$deleteOldIngredientsList = 'DELETE FROM recipe_ingredients WHERE id!=0 AND recipe_id='.$_POST['id'].";";
	$DeleteResult = mysql_query($deleteOldIngredientsList, $StrikeRecipes) or die(mysql_error());
	
	// assemble the new insert statement
	$insertSQL = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity_id, quantity_value) VALUES ";
	for ($i = 0, $len = count($_POST['ingredient_name']); $i < $len; $i++) {
 		$insertSQL .= sprintf(" (%s, %s, %s, %s)",
			GetSQLValueString($_POST['id'], "int"),
			GetSQLValueString($_POST['ingredient_name'][$i], "int"),
			GetSQLValueString($_POST['quantity_id'][$i], "int"),
			GetSQLValueString($_POST['ingredient_amount'][$i], "int"));
		if($i < $len - 1){
	 		$insertSQL .= ",";
		}
	}
	$Result2 = mysql_query($insertSQL, $StrikeRecipes) or die(mysql_error());
	
	$updateGoTo = "/recipes.php?edited=true";
	if (isset($_SERVER['QUERY_STRING'])) {
		$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
		$updateGoTo .= $_SERVER['QUERY_STRING'];
	}
	header(sprintf("Location: %s", $updateGoTo));
  
} //end loop

	$currentTab='recipes';
	require("includes/pagetop.php");
	require("includes/nav.php");
?>
<script>
	function setValues(obj){

		var measurementName = $(':selected', $(obj)).data('measurement'),
			measurementId= $(':selected', $(obj)).data('measurement_id');
			console.log(measurementName + ' = ' + measurementId);
		$(obj).parent().parent().find('.measurementId').val(measurementId);
		$(obj).parent().parent().find('.measurementName').html(measurementName);
	}
</script>

<!-- BEGIN content -->


<div class="container">
	<h1>Edit Recipe</h1>
	<form action="<?php echo $editFormAction; ?>" method="POST" name="form" class="form-horizontal" id="ingredientForm">
	<input type="hidden" name="id" value="<?php echo $row_thisRecipe['recipe_id']; ?>" />
		<div class="form-group">
			<label for="recipe_name" class="col-sm-2 control-label">Recipe Name</label>
			<div class="col-sm-10">
				<input id="recipe_name" type="text" required="required" class="form-control" name="recipe_name" value="<?php echo $row_thisRecipe['recipe_name']; ?>" placeholder="Recipe name">
			</div>
		</div>



<?php do { ?>
			<div class="form-group ingredient" id="ingredient_0">
				<label for="ingredient" class="col-sm-2 control-label">Ingredient</label>
				<div class="col-sm-7">
					<select name="ingredient_name[]" required="required" class="form-control" 
					onchange="setValues($(this));">
						<option value="" <?php if (!(strcmp("", $row_thisRecipe['measurement']))) {echo "selected=\"selected\"";} ?>>Choose Ingredient</option>
						<?php
do {  
?>
						<option value="<?php echo $row_ingredients['id']?>"
							<?php if (!(strcmp($row_ingredients['id'], $row_thisRecipe['id']))) {echo "selected=\"selected\"";} ?>
								data-measurement="<?php echo $row_ingredients['measurement_name']?>"
								data-measurement_id="<?php echo $row_ingredients['measurement']?>"
								><?php echo $row_ingredients['name']?></option>
						<?php
} while ($row_ingredients = mysql_fetch_assoc($ingredients));
  $rows = mysql_num_rows($ingredients);
  if($rows > 0) {
      mysql_data_seek($ingredients, 0);
	  $row_ingredients = mysql_fetch_assoc($ingredients);
  }
?>
					</select>
				</div>
				<div class="col-sm-2">
					<input name="ingredient_amount[]" type="text"  required="required" class="form-control" value="<?php echo $row_thisRecipe['quantity_value']; ?>">
				</div>
				<div class="col-sm-1"> <span class="formControl measurementName"><?php echo $row_thisRecipe['measurement_name']; ?></span>
					<input name="quantity_id[]" class="measurementId" type="hidden" value="<?php echo $row_thisRecipe['quantity_id']; ?>"/>
				</div>
			</div>
			<?php } while ($row_thisRecipe = mysql_fetch_assoc($thisRecipe)); ?>
			
<div class="form-group" id="addButton">
			<div class="col-sm-12 text-right">(should create a blank input to clone)
				<button class="btn btn-default " type="button" id="addIngredientButton">Add another ingredient</button>
			</div>
		</div>
		<hr />
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-primary">Edit Recipe</button>
			</div>
		</div>
		<input type="hidden" name="MM_insert" value="form" />
		<input type="hidden" name="MM_update" value="form" />
	</form>
</div>
<!-- END content --> 



<!-- Element to clone -->
<div class="hidden">
		<div class="form-group ingredient" id="blankIngredient">
			<label for="ingredient" class="col-sm-2 control-label">Ingredient</label>
			<div class="col-sm-7">
				<select name="ingredient_name[]" required="required" class="form-control" 
					onchange="setValues($(this));">
					<option value="">Choose Ingredient</option>
					<?php
do {  
?>
					<option value="<?php echo $row_ingredients['id']?>" data-measurement="<?php echo $row_ingredients['measurement_name']?>"
					 data-measurement_id="<?php echo $row_ingredients['measurement']?>"><?php echo $row_ingredients['name']?></option>
					<?php
} while ($row_ingredients = mysql_fetch_assoc($ingredients));
  $rows = mysql_num_rows($ingredients);
  if($rows > 0) {
      mysql_data_seek($ingredients, 0);
	  $row_ingredients = mysql_fetch_assoc($ingredients);
  }
?>
				</select>
			</div>
			<div class="col-sm-2">
				<input name="ingredient_amount[]" type="text"  required="required" class="form-control" placeholder="Amount">
			</div>
			<div class="col-sm-1">
				<span class="formControl measurementName"></span>
				<input name="quantity_id[]" class="measurementId" type="hidden"/>
			</div>
		</div>
</div>
<!-- END element to clone -->
<script>
$('#addIngredientButton').click(
		function(){
			var $clonedDiv = $('#blankIngredient').clone(),
				$num = parseInt( $("#ingredientForm .ingredient").last().prop('id').match(/\d+/g), 10 ) +1;
			$clonedDiv.prop('id','ingredient_' + $num).insertBefore('#addButton');
			return false;
		}
	);
</script>
<?php
	require("includes/pagebottom.php");
?>
<?php
mysql_free_result($measurements);

mysql_free_result($ingredients);

mysql_free_result($thisRecipe);
?>
