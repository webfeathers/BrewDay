<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_StrikeRecipes = "strikerecipes.webfeathers.com";
$database_StrikeRecipes = "strike_recipes";
$username_StrikeRecipes = "strikerecipes";
$password_StrikeRecipes = "P@ssw0rd";
$StrikeRecipes = mysql_pconnect($hostname_StrikeRecipes, $username_StrikeRecipes, $password_StrikeRecipes) or trigger_error(mysql_error(),E_USER_ERROR); 
?>