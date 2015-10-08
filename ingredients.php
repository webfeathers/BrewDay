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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_inventory = 10;
$pageNum_inventory = 0;
if (isset($_GET['pageNum_inventory'])) {
  $pageNum_inventory = $_GET['pageNum_inventory'];
}
$startRow_inventory = $pageNum_inventory * $maxRows_inventory;

mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
$query_inventory = "SELECT I.*, 	S.supplier_name AS supplier_name, 	Q.name AS measurement_name FROM ingredients I  LEFT JOIN suppliers S	ON S.id = I.supplier  LEFT JOIN measurements Q	ON Q.id = I.measurement ";
$query_limit_inventory = sprintf("%s LIMIT %d, %d", $query_inventory, $startRow_inventory, $maxRows_inventory);
$inventory = mysql_query($query_limit_inventory, $StrikeRecipes) or die(mysql_error());
$row_inventory = mysql_fetch_assoc($inventory);

if (isset($_GET['totalRows_inventory'])) {
  $totalRows_inventory = $_GET['totalRows_inventory'];
} else {
  $all_inventory = mysql_query($query_inventory);
  $totalRows_inventory = mysql_num_rows($all_inventory);
}
$totalPages_inventory = ceil($totalRows_inventory/$maxRows_inventory)-1;$maxRows_inventory = 10;
$pageNum_inventory = 0;
if (isset($_GET['pageNum_inventory'])) {
  $pageNum_inventory = $_GET['pageNum_inventory'];
}
$startRow_inventory = $pageNum_inventory * $maxRows_inventory;

mysql_select_db($database_StrikeRecipes, $StrikeRecipes);
$query_inventory = "
SELECT I.*, 	
	S.supplier_name AS supplier_name,
	S.supplier_phone AS supplier_phone,
	Q.name AS measurement_name 
FROM ingredients I 
LEFT JOIN suppliers S	ON S.id = I.supplier
LEFT JOIN measurements Q	ON Q.id = I.measurement ";

$query_limit_inventory = sprintf("%s LIMIT %d, %d", $query_inventory, $startRow_inventory, $maxRows_inventory);
$inventory = mysql_query($query_limit_inventory, $StrikeRecipes) or die(mysql_error());
$row_inventory = mysql_fetch_assoc($inventory);

if (isset($_GET['totalRows_inventory'])) {
  $totalRows_inventory = $_GET['totalRows_inventory'];
} else {
  $all_inventory = mysql_query($query_inventory);
  $totalRows_inventory = mysql_num_rows($all_inventory);
}
$totalPages_inventory = ceil($totalRows_inventory/$maxRows_inventory)-1;

mysql_select_db($database_StrikeRecipes, $StrikeRecipes);

$queryString_inventory = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_inventory") == false && 
        stristr($param, "totalRows_inventory") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_inventory = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_inventory = sprintf("&totalRows_inventory=%d%s", $totalRows_inventory, $queryString_inventory);

	$currentTab='ingredients';
	require("includes/pagetop.php");
	require("includes/nav.php");
?>

<!-- BEGIN content -->

<div class="container">
	<h1>Ingredients	<a href="/addIngredient.php" class="btn btn-primary pull-right">Add Ingredient</a>
</h1>
<table class="table">
	<thead>
		<th>Name</th>
		<th>Bag/Box size</th>
		<th>Producer</th>
		<th>Supplier</th>
		<th>Supplier Phone</th>
		<th>Amount On Hand</th>
		<th>Amount Ordered</th>
		<th>Date Ordered</th>
		<th></th>
	</thead>
	<tbody>
	<?php do { ?>
		<tr>
			<td><?php echo $row_inventory['name']; ?></td>
			<td><?php echo $row_inventory['size']; ?> <?php echo $row_inventory['measurement_name']; ?></td>
			<td><?php echo $row_inventory['producer']; ?></td>
			<td><?php echo $row_inventory['supplier_name']; ?></td>
			<td><a href="tel:<?php echo $row_inventory['supplier_phone']; ?>"><?php echo $row_inventory['supplier_phone']; ?></a></td>
			<td><?php echo $row_inventory['amount_on_hand'] > 0 ?  $row_inventory['amount_on_hand'] : 0 ; ?>
				<?php echo $row_inventory['measurement_name']; ?></td>
			<td><?php echo $row_inventory['amount_ordered'] > 0 ?  $row_inventory['amount_ordered'] : 0 ; ?>
				<?php echo $row_inventory['measurement_name']; ?></td>
			<td><?php echo $row_inventory['date_ordered']; ?></td>
			<td><a href="editIngredient.php?invid=<?php echo $row_inventory['id']; ?>" class="btn btn-primary btn-sm pull-right">Edit</a></td>
		</tr>
	<?php } while ($row_inventory = mysql_fetch_assoc($inventory)); ?>
	</tbody>
</table>
			<?php if($totalRows_suppliers > 10){ ?>
		<a href="<?php printf("%s?pageNum_inventory=%d%s", $currentPage, max(0, $pageNum_inventory - 1), $queryString_inventory); ?>">Previous</a> <a href="<?php printf("%s?pageNum_inventory=%d%s", $currentPage, min($totalPages_inventory, $pageNum_inventory + 1), $queryString_inventory); ?>">Next</a>
		<?php } ?>
	</ul>
</div>
<!-- END content -->

<?php
	require("includes/pagebottom.php");
?>
<?php
mysql_free_result($inventory);

?>
