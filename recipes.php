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
$query_recipes = "SELECT R.NAME            AS recipe_name, 
       R.id              AS recipe_id, 
       I.*, 
       V.*, 
       measurements.NAME AS measurement_name 
FROM   recipes R 
       LEFT JOIN recipe_ingredients I 
              ON R.id = I.recipe_id 
       LEFT JOIN ingredients V 
              ON I.ingredient_id = V.id
       LEFT JOIN measurements 
              ON V.measurement = measurements.id
WHERE I.recipe_id IS NOT NULL
ORDER  BY R.NAME ASC";
$recipes = mysql_query($query_recipes, $StrikeRecipes) or die(mysql_error());
$row_recipes = mysql_fetch_assoc($recipes);
$totalRows_recipes = mysql_num_rows($recipes);


$currentTab='recipes';
	require("includes/pagetop.php");
	require("includes/nav.php");
?>

<!-- BEGIN content -->	
	<div class="container">
		<h1>Recipes
		<a href="/addRecipe.php" class="btn btn-primary pull-right">Add a Recipe</a></h1>
		<?php if(isset($_GET['edited'])){?>
			<div class="alert alert-success" role="alert">Recipe updated</div>
		<?php } ?>
		<?php if(isset($_GET['added'])){?>
			<div class="alert alert-success" role="alert">Recipe updated</div>
		<?php } ?>
			<?php
				$thisRecipe = 0;
				do { 
			?>
			
			<?php if($thisRecipe != $row_recipes['recipe_id']) {
				if($thisRecipe != 0){ 
				?>

				</ul>
				<?php }?>

				<h2><?php echo $row_recipes['recipe_name']; ?>
				<a href="/editRecipe.php?rid=<?php echo $row_recipes['recipe_id']; ?>" class="btn btn-primary btn-xs pull-right">Edit</a>
				</h2>
				<ul>
			<?php
					$thisRecipe = $row_recipes['recipe_id'];
			 ?>	
				<?php }
				if($row_recipes['recipe_id'] == $thisRecipe){ ?>

					<li>
						<?php echo $row_recipes['quantity_value']; ?>
						<?php echo $row_recipes['measurement_name']; ?>
						<span><?php echo $row_recipes['name']; ?></span></li>
				<?php } ?>
				<?php } while ($row_recipes = mysql_fetch_assoc($recipes)); ?>

				</ul>
		
		</div>
<!-- END content -->	

<?php
	require("includes/pagebottom.php");
?>
<?php
mysql_free_result($recipes);
?>
