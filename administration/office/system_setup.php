<?php include_once('../php/connection.php'); ?>

<h4><i class="menu-icon fa fa-desktop"></i> System Setup</h4>
<div class="breadcrumb ace-save-state" id="breadcrumbs" style="margin:0; border-color: #2e6da4;border-radius:0">
	<div class="" id="sub_nav">
		<i class="ace-icon fa fa-user home-icon"></i><a href="#"> <b> Setup system</b></a>

	</div>
</div>


<div class="panel-group">
	<div class="panel">
		<div class="panel-heading" onclick="setup('session_wrap','session_caret')" style="padding:2px">
			<h4 class="panel-title" name="collapseOne" style='font-size:12px'>
				<span class="btn"><span id="session_caret" class="glyphicon glyphicon-minus"></span></span>
				<a href="#">
					Session
				</a>
			</h4>
		</div>

		<div class="panel-body" id="session_wrap" style="display:block">
			<?php include_once('system_session_year.php'); ?>
		</div>

	</div>
	<div class="panel">
		<div class="panel-heading" onclick="setup('system_users_body','users_caret')" style="padding:2px">
			<h4 class="panel-title" style='font-size:12px'>
				<span class="btn "><span id="users_caret" class="glyphicon glyphicon-plus"></span></span>
				<a href="#">
					System Users
				</a>
			</h4>
		</div>

		<div class="panel-body" id="system_users_body" style="display:none">

			<div> <button style="float:right" class="btn btn-default" onclick="add_user()"><span class="glyphicon glyphicon-plus-sign"></span></button></div>
			<div id="system_users">
				<?php include_once('system_users.php'); ?>

			</div>
		</div>
	</div>
	<div class="panel ">
		<div class="panel-heading" onclick="setup('subject_collapse','caret1')" style="padding:2px">
			<h4 class="panel-title" style='font-size:12px'>
				<span class="btn"><span id="caret1" class="glyphicon glyphicon-plus"></span></span>
				<a href="#">
					Subjects
				</a>
			</h4>
		</div>
		<div class="panel-body" id="subject_collapse" style="display:none">

			<?php include_once('system_subjects.php'); ?>

		</div>
	</div>
	<div class="panel ">
		<div class="panel-heading" onclick="setup('stock_item','sub_caret','iframe','iframe1')" style="padding:2px">
			<h4 class="panel-title" style='font-size:12px'>
				<span class="btn"><span id="sub_caret" class="glyphicon glyphicon-plus"></span></span>
				<a href="#">
					Store Items & Other categories
				</a>
			</h4>
		</div>
		<div class="panel-body" id="stock_item" style="display:none">
			<center>
				<h4><img src='../../images/ajax-loader.gif' style="width:40px; height:40px;" class="iframeloader"></h4>
			</center>
			<iframe style="width: 100%; height:80vh;" id='iframe1' src="stock_items_frame.php?session_id=<?php echo $_POST['session_id']; ?>" frameborder="0">
			</iframe>

		</div>
	</div>
	<div class="panel ">
		<div class="panel-heading" onclick="setup('term_info','caret2','iframe')" style="padding:2px">
			<h4 class="panel-title" style='font-size:12px'>
				<span class="btn"><span id="caret2" class="glyphicon glyphicon-plus"></span></span>
				<a href="#">
					Manage Term Info
				</a>
			</h4>
		</div>
		<div class="panel-body" id="term_info" style="display:none">
			<center>
				<h4><img src='../../images/ajax-loader.gif' style="width:40px; height:40px;" class="iframeloader"></h4>
			</center>
			<iframe style="width: 100%; height:90vh;" id='iframe1' src="manage_term_info.php?session_id=<?php echo $_POST['session_id']; ?>" frameborder="0">
			</iframe>

		</div>
	</div>
	<div class="panel ">
		<div class="panel-heading" onclick="setup('manage_comm','caret3','iframe')" style="padding:2px">
			<h4 class="panel-title" style='font-size:12px'>
				<span class="btn"><span id="caret3" class="glyphicon glyphicon-plus"></span></span>
				<a href="#">
					Manage Comment
				</a>
			</h4>
		</div>
		<div class="panel-body" id="manage_comm" style="display:none">
			<center>
				<h4><img src='../../images/ajax-loader.gif' style="width:40px; height:40px;" class="iframeloader"></h4>
			</center>
			<iframe style="width: 100%; height:90vh;" id='iframe2' src="manage_comment.php?session_id=<?php echo $_POST['session_id']; ?>" frameborder="0">
			</iframe>

		</div>
	</div>
</div>


<div id="abdul_android_pageOverlay"></div>
<div id="abdul_android_alertBox">
	<div id="abdul_android_boxContent" class="text-center"></div>

</div>