function system_setup(){
	getId('display_content').innerHTML = '<img src="../../images/ajax-loader.gif">';
		$.post("system_setup.php",function(response,error){
				getId('display_content').innerHTML = response;
			});
}

function setup(openClose,btn_caret){
	//$('#'+openClose).slideToggle('slow');
	
		if(getId(openClose).style.display == 'block' ){
			$('#'+openClose).hide('slow');
			$('#'+btn_caret).attr('class','glyphicon glyphicon-plus');
			
			
		}else{
				getId(openClose).style.display = 'block';
			$('#'+btn_caret).attr('class','glyphicon glyphicon-minus');
		}
}

function change_academic_session(token){
	abdul_android_pageOverlay();
	getId("abdul_android_boxContent").innerHTML = 'Saving,please  wait.....';
	$.post("../php/change_academic_session.php",{token:token},function(response,error){
				getId('abdul_android_boxContent').innerHTML = response;
				 setTimeout(function(){myandroidAlert.close();},5000);
			});
	
}
function change_academic_year(token){
	abdul_android_pageOverlay();
	getId("abdul_android_boxContent").innerHTML = 'Saving,please  wait.....';
	$.post("../php/change_academic_year.php",{token:token},function(response,error){
				getId('abdul_android_boxContent').innerHTML = response;
				 setTimeout(function(){myandroidAlert.close();},5000);
			});
	
}


function add_user(){
		pageOVerlay();
		$.post('add_user.php',function(response,error){
				
					getId('alertBoxContent').innerHTML = response;
			});
}

function add_system_user(){
	var staff_login_id = getId('staff_login_id').value;
	
	 document.getElementById("pageOverlay").style.display = 'none';
	abdul_android_pageOverlay();
	getId("abdul_android_boxContent").innerHTML = 'adding user, please  wait.....';
	
	$.post('../php/add_system_user.php',{staff_login_id:staff_login_id},function(response,error){
										myAlert.close();
					getId('abdul_android_boxContent').innerHTML = response;
					//system_setup();

				 setTimeout(function(){myandroidAlert.close();},5000);
			});
}


 function student_account_setup(token){

           document.getElementById("student_account_setup_wrap").innerHTML = '<center><img src="../../images/ajax-loader.gif"><br> please, wait...<c/enter>';
 }

function student_overview(token){
	           document.getElementById("student_account_setup_wrap").innerHTML = '<center><img src="../../images/ajax-loader.gif"><br> please, wait...<c/enter>';
		$.post("student_overview.php",{token:token},function(response,error){
				getId('student_account_setup_wrap').innerHTML = response;
			});
}

function staff_overview(token){
	           document.getElementById("staff_account_setup_wrap").innerHTML = '<center><img src="../../images/ajax-loader.gif"><br> please, wait...<c/enter>';
		$.post("staff_overview.php",{token:token},function(response,error){
				getId('staff_account_setup_wrap').innerHTML = response;
			});
}

 function staff_account_setup(token){

           document.getElementById("staff_account_setup_wrap").innerHTML = '<center><img src="../../images/ajax-loader.gif"><br> please, wait...<c/enter>';
		   $.post("staff_account_setup.php",{token:token},function(response,error){
				getId('staff_account_setup_wrap').innerHTML = response;
			});
 }
	
	
	 function assign_classes(token,cl){
		$.post("../php/assign_classes.php",{token:token,cl:cl},function(response,error){
				alert(response);
			});
 }

 
 function show_subjects_toAssign(){
	 $("#subss").slideDown();
 }
 
 function assign_this_subject_out(token,sub){
				abdul_android_pageOverlay();
				document.getElementById("abdul_android_boxContent").style.textAlign = 'center';
				document.getElementById("abdul_android_boxContent").innerHTML = 'Adding,please  wait.....';
	  //document.getElementById("mySubjects").innerHTML = '<center><img src="../../images/ajax-loader.gif"><br> please, wait...<c/enter>';
		   $.post("../php/assign_this_subject_out.php",{token:token,sub:sub},function(response,error){
					document.getElementById("abdul_android_boxContent").innerHTML = response;	
					assigned_subjects(token);
					setTimeout(function(){myandroidAlert.close();},3000);
			
			});
 }
 
 function assigned_subjects(token){
	  document.getElementById("mySubjects").innerHTML = '<center><img src="../../images/ajax-loader.gif"><br> please, wait...<c/enter>';
		   $.post("../php/assigned_subjects.php",{token:token},function(response,error){
				getId('mySubjects').innerHTML = response;
			});
 }
 
 function remove_assigned_subject(token,id){
			abdul_android_pageOverlay();
				document.getElementById("abdul_android_boxContent").style.textAlign = 'center';
				document.getElementById("abdul_android_boxContent").innerHTML = 'Removing,please  wait.....';
	 		   $.post("../php/remove_assigned_subject.php",{id:id},function(response,error){
					document.getElementById("abdul_android_boxContent").innerHTML = response;	
					assigned_subjects(token);
					setTimeout(function(){myandroidAlert.close();},3000);
			});
 }
 
 function activate_account(token){
	 abdul_android_pageOverlay();
				document.getElementById("abdul_android_boxContent").style.textAlign = 'center';
				document.getElementById("abdul_android_boxContent").innerHTML = 'Activating,please  wait.....';
	 		   $.post("../php/activate_account.php",{token:token},function(response,error){
					document.getElementById("abdul_android_boxContent").innerHTML = response;	
					setTimeout(function(){myandroidAlert.close();},3000);
			});
 }