<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

require 'webroot' . DIRECTORY_SEPARATOR . 'index.php';

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