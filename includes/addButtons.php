
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
$query_batchSizes = "SELECT * FROM batch_sizes";
$batchSizes = mysql_query($query_batchSizes, $StrikeRecipes) or die(mysql_error());
$row_batchSizes = mysql_fetch_assoc($batchSizes);
$totalRows_batchSizes = mysql_num_rows($batchSizes);

?>

<?php do {
	$recipeId = $row_Recipes['id'];
	$batchSizeName = $row_batchSizes['batch_size_name'];
	$batchSizeId = $row_batchSizes['batch_size_id'];
		 echo <<<EOF
			<div class="col-sm-2">
				<span>$batchSizeName</span>
				<div class="input-group">
					<span class="input-group-btn">
						<button type="button" class="btn btn-default btn-number  addBrewButton"
							disabled="disabled" data-type="minus" 
							
							data-field="[$recipeId][$batchSizeName]">
							
							<span class="glyphicon glyphicon-minus"></span>
						</button>
					</span>
					<input type="hidden" 	name="brew[$recipeId][sizes][$batchSizeId][batchSizeName]" value="$batchSizeName">
					<input type="hidden" 	name="brew[$recipeId][sizes][$batchSizeId][batchSizeId]" value="$batchSizeId">
					
					<input type="text" 		name="brew[$recipeId][sizes][$batchSizeId][quantity]"
											id="[$recipeId][$batchSizeName]" 
						class="form-control input-number" value="0" min="0" max="10">

					<span class="input-group-btn ">
						<button type="button" class="btn btn-default btn-number addBrewButton" 
						
							data-type="plus" data-field="[$recipeId][$batchSizeName]">
							
							<span class="glyphicon glyphicon-plus"></span>
						</button>
					</span>
				</div>
			</div>
EOF;
	} while ($row_batchSizes = mysql_fetch_assoc($batchSizes));


?>

<?php
mysql_free_result($batchSizes);
?>
