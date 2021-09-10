<div class="row">
	<div class="col-lg-12">
				<nav class="navbar navbar-default" style="padding-bottom:0;color:#458ac0;margin-bottom:0">
						 
							<!-- Brand and toggle get grouped for better mobile display -->
							<div class="navbar-header">
							  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							  </button>
							  <a class="navbar-brand" href="#" style="margin-top:20px;"><?php echo $school_abbr; ?></a>
							</div>

							<!-- Collect the nav links, forms, and other content for toggling -->
							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							  <ul class="nav navbar-nav">
								<li class="<?php if(isset($_GET['staff']))echo 'active';?> page_nav_btnn" name="staff"><a href="#" style="color:#458ac0;">Manage Staff <span class="sr-only">(current)</span></a></li>
								<li class="<?php if(isset($_GET['students']))echo 'active';?> page_nav_btnn" name="students"><a href="#" style="color:#458ac0;">Manage Students</a></li>
								<li class="<?php if(isset($_GET['admission']))echo 'active';?> page_nav_btnn" name='admission'><a href="#" style="color:#458ac0;">Manage Admission</a></li>
							<li class="dropdown">
								  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" style='color:#458ac0;'>Others <span class="caret"></span></a>
								  <ul class="dropdown-menu" role="menu">
									<li><a href="#">Action</a></li>
									<li><a href="#">Another action</a></li>
									<li><a href="#">Something else here</a></li>
									<li class="divider"></li>
									<li><a href="#">Separated link</a></li>
									<li class="divider"></li>
									<li><a href="#">One more separated link</a></li>
								  </ul>
								</li>
							  </ul>
							 <!-- <form action="?token=info" method="post" class="navbar-form navbar-left" role="search">
								<div class="form-group">
								  <input type="text" class="form-control" placeholder="Search">
								</div>
								<button type="submit" class="btn btn-default">Submit</button>
							  </form>-->
							  <ul class="nav navbar-nav navbar-right">
								
								<!--<li class="dropdown">
								  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Others <span class="caret"></span></a>
								  <ul class="dropdown-menu" role="menu">
									<li><a href="#">Action</a></li>
									<li><a href="#">Another action</a></li>
									<li><a href="#">Something else here</a></li>
									<li class="divider"></li>
									<li><a href="#">Separated link</a></li>
								  </ul>
								</li>-->
								<li><a href="../php/logout.php" style="color:#458ac0;">Logout</a></li>
							  </ul>
							</div><!-- /.navbar-collapse -->
						 <!-- /.container-fluid -->
						</nav>
	</div>
</div>