
<!-- BEGIN nav.php -->

<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/"><strong>Strike</strong></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="<?php if($currentTab=='brew') { echo 'active'; } ?>"><a href="/brewSchedule.php">Brew Schedule</a></li>
            <li class="<?php if($currentTab=='recipes') { echo 'active'; } ?>"><a href="/recipes.php">Recipes</a></li>
            <li class="<?php if($currentTab=='ingredients') { echo 'active'; } ?>"><a href="/ingredients.php">Ingredients</a></li>
            <li class="<?php if($currentTab=='order') { echo 'active'; } ?>"><a href="/order.php">Need to Order</a></li>
            <li class="<?php if($currentTab=='suppliers') { echo 'active'; } ?>"><a href="/suppliers.php">Suppliers</a></li>
           
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	
<!-- END nav.php -->
