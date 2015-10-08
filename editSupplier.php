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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "addSupplier")) {
  $updateSQL = sprintf("UPDATE suppliers SET supplier_name=%s, supplier_phone=%s WHERE id=%s",
                       GetSQLValueString($_POST['supplier_name'], "text"),
                       GetSQLValueString($_POST['supplier_phone'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
  $Result1 = mysql_query($updateSQL, $StrikeRecipes) or die(mysql_error());

  $updateGoTo = "/suppliers.php?edited=true";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_supplier = "-1";
if (isset($_POST['id'])) {
  $colname_supplier = $_POST['id'];
}
mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
$query_supplier = sprintf("SELECT * FROM suppliers WHERE id = %s", GetSQLValueString($colname_supplier, "int"));
$supplier = mysql_query($query_supplier, $StrikeRecipes) or die(mysql_error());
$row_supplier = mysql_fetch_assoc($supplier);
$totalRows_supplier = mysql_num_rows($supplier);$colname_supplier = "-1";
if (isset($_GET['id'])) {
  $colname_supplier = $_GET['id'];
}
mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
$query_supplier = sprintf("SELECT * FROM suppliers WHERE id = %s", GetSQLValueString($colname_supplier, "int"));
$supplier = mysql_query($query_supplier, $StrikeRecipes) or die(mysql_error());
$row_supplier = mysql_fetch_assoc($supplier);
$totalRows_supplier = mysql_num_rows($supplier);

$currentTab='suppliers';
	require("includes/pagetop.php");
	require("includes/nav.php");
?>

<!-- BEGIN content -->	
	<div class="container">
		<h1>Edit Supplier 
		<a href="/addSupplier.php" class="pull-right btn btn-sm btn-default">Add a new Supplier</a></h1>
		
		<?php if(isset($_GET['added'])){?>
			<div class="alert alert-success" role="alert">Supplier added</div>
		<?php }?>
		
		
		<form action="<?php echo $editFormAction; ?>" method="POST" class="form-horizontal" name="addSupplier">
			<input name="id" value="<?php echo $row_supplier['id']; ?>" type="hidden" />
				<div class="form-group">
				<label for="supplier_name" class="col-sm-2 control-label">Supplier Name</label>
				<div class="col-sm-10">
					<input value="<?php echo $row_supplier['supplier_name']; ?>" type="text" class="form-control" name="supplier_name" placeholder="Item name">
				</div>
			</div>
			<div class="form-group">
				<label for="supplier_phone" class="col-sm-2 control-label">Contact Number</label>
				<div class="col-sm-10">
					<input value="<?php echo $row_supplier['supplier_phone']; ?>" type="text" class="form-control" name="supplier_phone" placeholder="(800) GET-MORE">
				</div>
			</div>
		
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary">Edit Supplier</button>
				</div>
			</div>
			<input type="hidden" name="MM_update" value="addSupplier" />
		</form>
		
	</div>
	
	
	
	
	
<!-- END content -->	

<?php
	require("includes/pagebottom.php");
?>
<?php
mysql_free_result($supplier);
?>
