<?php
	$currentTab='';
	require("includes/pagetop.php");
	require("includes/nav.php");
?>

<!-- BEGIN content -->	
	<div class="container">
		<h1>Home</h1>
		<a href="/addIngredient.php" class="btn btn-primary">Add Item to Inventory</a>
		<a href="/brewSchedule.php" class="btn btn-primary">Brew Schedule</a>
	</div>
<!-- END content -->	

<?php
	require("includes/pagebottom.php");
?>