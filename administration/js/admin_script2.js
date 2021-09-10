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

 function load_change_password(token){

	document.getElementById("staff_account_setup_wrap").innerHTML = '<center><img src="../../images/ajax-loader.gif"><br> please, wait...<c/enter>';
	$.post("load_change_password.php",{token:token},function(response,error){
		 getId('staff_account_setup_wrap').innerHTML = response;
	 });
}

function change_password(token){
	var pass1 = $('#new_password').val();
	var pass2 = $('#confirm_new_password').val();

	if(pass1 == ''){
		alert('Enter Your new password')
	}else if(pass1.length < 4){
		alert('You new password should be atleast four characters')
	}else if(pass1  != pass2){
		alert('Password mismatch, please check and try again...')
	}else{
		document.getElementById("loading_status").innerHTML = '<center><img src="../../images/ajax-loader.gif"><br> please, wait...<c/enter>';
	$.post("../php/change_password.php",{token:token,password:pass1},function(response,error){
		 getId('loading_status').innerHTML = response;
	 });
	}


	
}

	
	
	 function assign_classes(token,cl){
		$.post("../php/assign_classes.php",{token:token,cl:cl},function(response,error){
				alert(response);
			});
 }

 
 function show_subjects_toAssign(){
	 $("#subss").slideDown();
 }
 
 function assign_this_subject_out(token,sub,sessionID,termID,specified_class){
				
	  //document.getElementById("mySubjects").innerHTML = '<center><img src="../../images/ajax-loader.gif"><br> please, wait...<c/enter>';
	  //console.log(sessionID+'-'+termID);
	  var specified_class_decode = JSON.parse(atob(specified_class));
	  console.log(specified_class_decode);
	  //return 0;
	  async function ext(specified_class_encode,token,sub,sessionID,termID,specified_class){

	  			const { value: classes } = await Swal.fire({
				  title: 'Select field validation',
				  input: 'select',
				  inputOptions: specified_class_encode				    
				  ,
				  inputPlaceholder: 'Select a clas',
				  showCancelButton: true,
				  inputValidator: (value) => {
				    return new Promise((resolve) => {
				      if (value != '') {				        
				        abdul_android_pageOverlay();
						document.getElementById("abdul_android_boxContent").style.textAlign = 'center';
						document.getElementById("abdul_android_boxContent").innerHTML = 'Adding,please  wait.....';
						   $.post("../php/assign_this_subject_out.php",{token:token,sub:sub,sessionID:sessionID, termID:termID,class_id:value},function(response,error){
					   	console.log(response);
								document.getElementById("abdul_android_boxContent").innerHTML = response;	
								assigned_subjects(token);
								setTimeout(function(){myandroidAlert.close();},1000);
						
						});

				      } else {
				        resolve('You need to select Classes :)')
				      }
				    })
				  }
				})
	  }
	  ext(specified_class_decode,token,sub,sessionID,termID,specified_class);
		
 }
 
 function assigned_subjects(token){
 	console.log(3);
	  $("#mySubjects").html('<center><img src="../../images/ajax-loader.gif"><br> please, wait...<c/enter>');
	  console.log(4);
		   $.post("../php/assigned_subjects.php",{token:token},function(response,error){
				$('#mySubjects').html(response);
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