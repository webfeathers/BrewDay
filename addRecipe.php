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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
  $insertSQL = sprintf("INSERT INTO recipes (name) VALUES (%s)",
                       GetSQLValueString($_POST['recipe_name'], "text"));

  mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
  $Result1 = mysql_query($insertSQL, $StrikeRecipes) or die(mysql_error());
  $lastInsertID = mysql_insert_id();

}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {

for ($i = 0, $len = count($_POST['ingredient_name']); $i < $len; $i++) {
  $insertSQL = sprintf("INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity_id, quantity_value) VALUES (%s, %s, %s, %s)",
                       $lastInsertID,
                       GetSQLValueString($_POST['ingredient_name'][$i], "int"),
                       GetSQLValueString($_POST['quantity_id'][$i], "int"),
                       GetSQLValueString($_POST['ingredient_amount'][$i], "int"));
  mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
  $Result2 = mysql_query($insertSQL, $StrikeRecipes) or die(mysql_error());
}
} //end loop

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

	$currentTab='recipes';
	require("includes/pagetop.php");
	require("includes/nav.php");
?>
<script>
	function setValues(obj){
		var measurementName = $(obj).find(':selected').data('measurement'),
			measurementId= $(obj).find(':selected').data('measurement_id');
		console.log(measurementName + ' = ' + measurementId);
		$(obj).parent().parent().find('.measurementId').val(measurementId);
		$(obj).parent().parent().find('.measurementName').html(measurementName);
	}
</script>

<!-- BEGIN content -->	
	<div class="container">
		<h1>Add a Recipe</h1>
		<p>A Recipe has a name, and several ingredients. Enter the Name and the measurement of each ingredient.
		
		<form action="<?php echo $editFormAction; ?>" method="POST" name="form" class="form-horizontal">
		<div class="form-group">
			<label for="recipe_name" class="col-sm-2 control-label">Recipe Name</label>
			<div class="col-sm-10">
				<input id="recipe_name" type="text" required="required" class="form-control" name="recipe_name" placeholder="Recipe name">
			</div>
		</div>
		
		<div class="form-group" id="addButton">
			<div class="col-sm-12 text-right"> (should create a blank input to clone)
				<button class="btn btn-default" type="button" id="addIngredientButton">Add another ingredient</button>
			</div>
		</div>
		<hr />
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-primary">Add Recipe</button>
			</div>
		</div>
		<input type="hidden" name="MM_insert" value="form" />
		</form>
		
		
	</div>
<!-- END content -->	

<!-- Element to clone -->
<div class="hidden">
		<div class="form-group" id="ingredient_0">
			<label for="ingredient" class="col-sm-2 control-label">Ingredient</label>
			<div class="col-sm-7">
				<select name="ingredient_name[]" required="required" class="form-control" 
					onchange="setValues($(this));">
					<option value="">Choose Ingredient</option>
					<?php
do {  
?>
					<option value="<?php echo $row_ingredients['id']?>" 
						data-measurement="<?php echo $row_ingredients['measurement_name']?>"
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
			$('#ingredient_0').clone('#ingredient_0').insertBefore('#addButton');
			return false;
		}
	);
	$('#ingredient_0').clone('#ingredient_0').insertBefore('#addButton');
</script>

<?php
	require("includes/pagebottom.php");
?>
<?php
mysql_free_result($measurements);

mysql_free_result($ingredients);
?>
