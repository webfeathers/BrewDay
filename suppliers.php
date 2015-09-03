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
$query_suppliers = "SELECT * FROM suppliers";
$suppliers = mysql_query($query_suppliers, $StrikeRecipes) or die(mysql_error());
$row_suppliers = mysql_fetch_assoc($suppliers);
$totalRows_suppliers = mysql_num_rows($suppliers);

$currentTab='suppliers';
	require("includes/pagetop.php");
	require("includes/nav.php");
?>

<!-- BEGIN content -->	
	<div class="container">
		<h1>Suppliers
		<a href="/addSupplier.php" class="pull-right btn btn-sm btn-default">Add a new Supplier</a></h1>
		
		<?php if(isset($_GET['edited'])){?>
			<div class="alert alert-success" role="alert">Supplier edited</div>
		<?php }?>
		
		<ul id="supplierListing">
			<?php do { ?>
			<li>
				<strong><?php echo $row_suppliers['supplier_name']; ?></strong>
				<span><?php echo $row_suppliers['supplier_phone']; ?></span>
				<a class="btn btn-primary" href="editSupplier.php?id=<?php echo $row_suppliers['id']; ?>">edit</a>
			</li>
<?php } while ($row_suppliers = mysql_fetch_assoc($suppliers)); ?>
		</ul>
		
	</div>
	
	
	
	
	
<!-- END content -->	

<?php
	require("includes/pagebottom.php");
?>
<?php
mysql_free_result($suppliers);
?>
