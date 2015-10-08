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
  $insertSQL = sprintf("INSERT INTO ingredients (name, `size`, producer, supplier, amount_on_hand, amount_ordered, date_ordered, measurement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['size'], "text"),
                       GetSQLValueString($_POST['producer'], "text"),
                       GetSQLValueString($_POST['supplier'], "text"),
                       GetSQLValueString($_POST['amount_on_hand'], "text"),
                       GetSQLValueString($_POST['amount_ordered'], "text"),
                       GetSQLValueString($_POST['date_ordered'], "text"),
                       GetSQLValueString($_POST['measurement'], "text"));

  mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
  $Result1 = mysql_query($insertSQL, $StrikeRecipes) or die(mysql_error());

  $insertGoTo = "addIngredient.php?added=true";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
$query_suppliers = "SELECT * FROM suppliers";
$suppliers = mysql_query($query_suppliers, $StrikeRecipes) or die(mysql_error());
$row_suppliers = mysql_fetch_assoc($suppliers);
$totalRows_suppliers = mysql_num_rows($suppliers);

mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
$query_measurements = "SELECT * FROM measurements";
$measurements = mysql_query($query_measurements, $StrikeRecipes) or die(mysql_error());
$row_measurements = mysql_fetch_assoc($measurements);
$totalRows_measurements = mysql_num_rows($measurements);


	$currentTab='ingredients';
	require("includes/pagetop.php");
	require("includes/nav.php");
?>
<!-- BEGIN content -->

<div class="container">
	<h1>Add an Ingredient</h1>
	<?php if(isset($_GET['added'])){?>
	<div class="alert alert-success" role="alert">Ingredient added</div>
	<?php }?>
	<form action="<?php echo $editFormAction; ?>" method="POST" name="form" class="form-horizontal">
		<div class="form-group">
			<label for="name" class="col-sm-2 control-label">Item Name</label>
			<div class="col-sm-10">
				<input id="name" type="text" required="required" class="form-control" name="name" placeholder="Item name">
			</div>
		</div>
		<div class="form-group">
			<label for="size" class="col-sm-2 control-label">Bag/Box Size</label>
			<div class="col-sm-10">
				<input name="size" type="number"  required="required" class="form-control" id="size" placeholder="Bag/Box Size">
			</div>
		</div>
		<div class="form-group">
			<label for="measurement" class="col-sm-2 control-label">Measurement</label>
			<div class="col-sm-10">
				<select name="measurement" required="required" class="form-control" id="measurement">
					<option value="">Choose</option>
					<?php
do {  
?>
					<option value="<?php echo $row_measurements['id']?>"><?php echo $row_measurements['name']?></option>
					<?php
} while ($row_measurements = mysql_fetch_assoc($measurements));
  $rows = mysql_num_rows($measurements);
  if($rows > 0) {
      mysql_data_seek($measurements, 0);
	  $row_measurements = mysql_fetch_assoc($measurements);
  }
?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="producer" class="col-sm-2 control-label">Producer</label>
			<div class="col-sm-10">
				<input name="producer" type="text"  required="required" class="form-control" id="producer" placeholder="Producer">
			</div>
		</div>
		<div class="form-group">
			<label for="supplier" class="col-sm-2 control-label">Supplier</label>
			<div class="col-sm-10">
				<select  class="form-control" name="supplier" id="supplier"  required="required">
					<option value="">Choose one</option>
					<?php
do {  
?>
					<option value="<?php echo $row_suppliers['id']?>"><?php echo $row_suppliers['supplier_name']?></option>
					<?php
} while ($row_suppliers = mysql_fetch_assoc($suppliers));
  $rows = mysql_num_rows($suppliers);
  if($rows > 0) {
      mysql_data_seek($suppliers, 0);
	  $row_suppliers = mysql_fetch_assoc($suppliers);
  }
?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="amount_on_hand" class="col-sm-2 control-label">Amount on hand</label>
			<div class="col-sm-10">
				<input value="<?php echo $row_ingredientsItem['amount_on_hand']; ?>" type="text" class="form-control" name="amount_on_hand" placeholder="">
			</div>
		</div>
		<div class="form-group">
			<label for="amount_ordered" class="col-sm-2 control-label">Amount Ordered</label>
			<div class="col-sm-5">
				<input value="<?php echo $row_ingredientsItem['amount_ordered']; ?>" type="text" class="form-control" name="amount_ordered" placeholder="">
			</div>
		</div>
		<div class="form-group">
			<label for="date_ordered" class="col-sm-2 control-label">Date Ordered</label>
			<div class="col-sm-5">
				<input value="<?php echo $row_ingredientsItem['date_ordered']; ?>" type="text" class="form-control datepicker" name="date_ordered" placeholder="">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-primary">Add Item</button>
			</div>
		</div>
		<input type="hidden" name="MM_insert" value="form" />
	</form>
</div>

<!-- END content -->

<?php
	require("includes/pagebottom.php");
?>
<?php
mysql_free_result($suppliers);

mysql_free_result($measurements);
?>
