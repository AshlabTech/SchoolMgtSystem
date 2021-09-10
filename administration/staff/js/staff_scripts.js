$(document).ready(function(){
	
		
		$(".option_btn").on('click',function(){
			var name = $(this).attr('name');
		
			$(".option_btn").removeClass("active");
			$(this).addClass("active");
		});
		
			
		$(".menus").on('click',function(){
			alert();
			$(".menus").removeClass("active");
			$(this).addClass("active");
		});
		
		
	
});


	function empty(el){
		if(el == '')
			return true;
	}

	function getId(el){
		return document.getElementById(el);
	}
	
	
	function staff_login(){
		
			var staff_id = getId('staff_id').value;
			var staff_pass = getId('staff_pass').value;
			
			if(empty(staff_id)){
				getId('login_status').innerHTML = '<span style="color:red"> Provide your login ID </span>';
			}else if(empty(staff_pass)){
				getId('login_status').innerHTML = '<span style="color:red"> Provide your login password </span>';
			}else{
						
					getId('login_status').innerHTML='<center><p style="color:blue"><img src="../images/ajax-loader.gif"></center><p>';
					$.post('staff/php/login.php',{staff_id:staff_id,staff_pass:staff_pass},function(response,error){
						if(response == 1){
							setTimeout(function(){window.location.assign("staff/")},3000);
						}else{
							getId('login_status').innerHTML = response;
						}
							
				});
						
			}
			
	}


	function load_staff_dashboard(token){
		 document.getElementById("display_content").innerHTML = '<center><img src="../../images/ajax-loader.gif"><br> please, wait...<c/enter>';
		   $.post("pages/load_staff_dashboard.php",{token:token},function(response,error){
				getId('display_content').innerHTML = response;
			});
	}
	function load_staff_result_code(){
		getId('display_content').innerHTML = '<img src="../../images/ajax-loader.gif">';
		$.post("load_staff_result_code.php",function(response,error){
				getId('display_content').innerHTML = response;
			});
		}
	
	function load_staff_profile(token){
		 document.getElementById("display_content").innerHTML = '<center><img src="../../images/ajax-loader.gif"><br> please, wait...<c/enter>';
		   $.post("pages/staff_profile.php",{token:token},function(response,error){
				getId('display_content').innerHTML = response;
			});
	} 
	
	
		function load_contineous_accessment(){
		 document.getElementById("display_content").innerHTML = '<center><img src="../../images/ajax-loader.gif"><br> please, wait...</center>';
		   $.post("pages/load_contineous_accessment.php",{token:'token'},function(response,error){
				getId('display_content').innerHTML = response;
			});
	}
	
	
	
	function load_accessment_sheet(){
			var cl = document.getElementById("class_idd").value;
			var term_id = document.getElementById("term_id").value;
			var sub = document.getElementById("sub_id").value;
			
			if(cl !='' && term_id !='' && sub !=''){
				document.getElementById("accessment_sheet").innerHTML = '<center><img src="../../images/ajax-loader.gif"><br> please, wait...</center>';
		   $.post("pages/accessment_sheet.php",{cl:cl,sub:sub,term_id:term_id},function(response,error){
				getId('accessment_sheet').innerHTML = response;
			});
			}else{
				alert('Select all required...');
			}
	}
	
	
	function submit_accessment(token){
			var cl = document.getElementById("class_idd").value;
			var sub = document.getElementById("sub_id").value;
			var term_id = document.getElementById("term_id").value;
			var ca1 = document.getElementById("c1_"+token).value;
			var ca2 = document.getElementById("c2_"+token).value;
			var ca3 = document.getElementById("c3_"+token).value;
			var exam = document.getElementById("exam_"+token).value;
		
			
			 document.getElementById("total"+token).innerHTML = '<b style="color:red">calculating..</b>';
			 document.getElementById("grade"+token).innerHTML = '<b style="color:red">calculating..</b>';
		   $.post("php/submit_accessment.php",{token:token,term_id:term_id,cl:cl,sub:sub,ca1:ca1,ca2:ca2,ca3:ca3,exam:exam},function(response,error){
				var response_arr = response.split('|');
				var total = response_arr[0];
				var grade = response_arr[1];
				//alert(total+" = "+grade);
				getId("total"+token).innerHTML = total;
				getId("grade"+token).innerHTML = grade;
			});
		
	}
	
	
		function load_subs(token,cl){
		 document.getElementById("sub_id").innerHTML = '';
		   $.post("php/load_subs.php",{token:token,cl:cl},function(response,error){
				getId('sub_id').innerHTML = response;
			});
	}
	
	function preview_contineous_accesst(cl,sub,tr){
		$('#access_options_wrap').slideUp('slow');
		 document.getElementById("accessment_sheet").innerHTML = '<center><img src="../../images/ajax-loader.gif"><br> please, wait...</center>';
		  $.post("pages/preview_contineous_accesst.php",{cl:cl,sub:sub,tr:tr},function(response,error){
				getId('accessment_sheet').innerHTML = response;
			});
	}
	
	function back_continuous_assessment(){
			$('#access_options_wrap').slideDown('slow');
				var cl = document.getElementById("class_idd").value;
			var term_id = document.getElementById("term_id").value;
			var sub = document.getElementById("sub_id").value;
		 document.getElementById("accessment_sheet").innerHTML = '<center><img src="../../images/ajax-loader.gif"><br> please, wait...</center>';
		   $.post("pages/accessment_sheet.php",{cl:cl,sub:sub,term_id:term_id},function(response,error){
				getId('accessment_sheet').innerHTML = response;
			});
	}
	
	function update_staff_public_profile(token){
				var first_name = document.getElementById("first_name").value;
				var last_name = document.getElementById("last_name").value;
				var other_name = document.getElementById("other_name").value;
				var phone_number = document.getElementById("phone_number").value;
				var email_address = document.getElementById("email_address").value;
				var face_book_name = document.getElementById("face_book_name").value;
				var religion = getId('religion').value;
				var marital_status = getId('marital_status').value;
				var date_of_birth = getId('date_of_birth').value;
				var state = getId('state').value;
				var lga = getId('lga').value;
				var tribe = getId('tribe').value;
				var other_phone_numbers = getId('other_phone_numbers').value;
				//post to php using ajax posting method
		 document.getElementById("bio_status").innerHTML = '<center><img src="../../images/ajax-loader.gif"><br> please, wait...</center>';
		   $.post("php/update_staff_public_profile.php",{token:token,first_name:first_name,last_name:last_name,other_name:other_name,phone_number:phone_number,
		   email_address:email_address,face_book_name:face_book_name,religion:religion,marital_status:marital_status,
		   date_of_birth:date_of_birth,state:state,lga:lga,tribe:tribe,other_phone_numbers:other_phone_numbers},function(response,error){
				if(response == 1){
						getId('bio_status').innerHTML = '';
						abdul_android_pageOverlay();
					document.getElementById("abdul_android_boxContent").innerHTML = 'Your personal profile is updated sussessfully...';	
						load_staff_profile(token)
					setTimeout(function(){myandroidAlert.close();},5000);
				}else{
						getId('bio_status').innerHTML = response;
				}
			
			});
	}
	
	function  load_change_staff_picture(token){
	
		$.post('pages/load_change_staff_picture.php',{token:token},
			function(response,error){
					pageOVerlay();
					getId('alertBoxContent').innerHTML = response;
			});
	
}


var staff_pics_token;
function change_staff_picture(token){
		staff_pics_token = token;
		var file = document.getElementById("staffpassport").files[0];
	
		//alert(image_url);
		var formdata = new FormData();
			formdata.append("staffpassport", file);
			var ajax = new XMLHttpRequest();
			ajax.open("POST", "php/change_staff_picture.php");
			//ajax.addEventListener("load",change_staff_picture_complete, false);
			ajax.onreadystatechange = function(){
				if(ajax.readyState == 4 && ajax.status == 200)
				{
					if(ajax.responseText == 1)
					{
						$("#staff_user_pics_hold").innerHTML ='';
						$("#staff_user_pics_hold").innerHTML = '<img id="staff_picture" class="img img-circle" src="../../staff_image_uploads/staff_passport'+token+'"';
						
						setTimeout(function(){load_staff_profile(token)},1000);
						setTimeout(function(){window.reload(true);},15000);
						setTimeout(function(){load_staff_profile(token)},1700);
						setTimeout(function(){myAlert.close();},1600);

					}else{
						alert(ajax.responseText);
					}
				}
			}
			ajax.send(formdata);

	
	}
	

function close_change_staff_picture(token){
		myAlert.close();
	
		setTimeout(function(){load_staff_profile(staff_pics_token);},1000);
		
	}
	
	
	
	
