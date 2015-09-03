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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addSupplier")) {
  $insertSQL = sprintf("INSERT INTO suppliers (supplier_name, supplier_phone) VALUES (%s, %s)",
                       GetSQLValueString($_POST['supplier_name'], "text"),
                       GetSQLValueString($_POST['supplier_phone'], "text"));

  mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
  $Result1 = mysql_query($insertSQL, $StrikeRecipes) or die(mysql_error());

  $insertGoTo = "addSupplier.php?added=true";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

	$currentTab='suppliers';
	require("includes/pagetop.php");
	require("includes/nav.php");
?>

<!-- BEGIN content -->	
	<div class="container">
		<h1>Add a Supplier to the system</h1>
		
		<?php if(isset($_GET['added'])){?>
			<div class="alert alert-success" role="alert">Supplier added</div>
		<?php }?>
		
		
		<form method="POST" action="<?php echo $editFormAction; ?>" class="form-horizontal" name="addSupplier">
				<div class="form-group">
				<label for="supplier_name" class="col-sm-2 control-label">Supplier Name</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="supplier_name" placeholder="Item name" required="required">
				</div>
			</div>
			<div class="form-group">
				<label for="supplier_phone" class="col-sm-2 control-label">Contact Number</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="supplier_phone" placeholder="(800) GET-MORE" required="required">
				</div>
			</div>
		
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary">Add Item</button>
				</div>
			</div>
			<input type="hidden" name="MM_insert" value="addSupplier" />
		</form>
		
	</div>
	
	
	
	
	
<!-- END content -->	

<?php
	require("includes/pagebottom.php");
?>