function getId(x){
	return document.getElementById(x);
}

function empty(el){
		if(el == '')
			return true;
	}
	
	
function load_application_form(){
	getId('payment_wrap').innerHTML = '<img src="../images/ajax-loader.gif">';
	$.post("load_application_form.php",function(response){
		getId('payment_wrap').innerHTML = response;
	});
}

function load_lga(state){
	
		$.post("../administration/php/load_lga.php",{state:state},function(response){
		document.getElementById("lga").innerHTML = '';
				 var lga_array = '';
				 lga_array = response.split('||');
				 var sn = 0;
				 for(var option in lga_array){
					 if(sn < lga_array.length - 2){
						 	 var pair  = lga_array[option].split('|');
							var newoption = document.createElement("option");
							newoption.value = pair[0];
							newoption.innerHTML = pair[1];
							document.getElementById("lga").options.add(newoption);
					 }
					
						sn++;
				 }
			
	});
				
			
}

function submit_application(){

	var first_name = getId('first_name').value;
	var last_name = getId('last_name').value;
	var other_name = getId('other_name').value;
	var gender = getId('gender').value;
	var religion = getId('religion').value;
	var date_of_birth = getId('date_of_birth').value;
	var state = getId('state').value;
	var lga = getId('lga').value;
	var tribe = getId('tribe').value;
	var student_class = getId('class').value;
	//var student_passport = getId('student_passport').value;
	var previous_school = getId('previous_school').value;
	var reason_for_leaving_the_school = getId('reason_for_leaving_the_school').value;
	var email_address = getId('email_address').value;
	var phone_number = getId('phone_number').value;
	var guidian_other_phone_number = getId('guidian_other_phone_number').value;
	var residential_address = getId('residential_address').value;
	var postal_code = getId('postal_code').value;	
	var guidian_name = getId('guidian_name').value;
	var guidian_phone_number = getId('guidian_phone_number').value;
	var guadian_relationship = getId('guadian_relationship').value;
	var guidain_occupation = getId('guidain_occupation').value;
	var guidian_address = getId('guidian_address').value;

			if(empty(getId('first_name').value)){
				$('#first_name').focus();
			}	else if(empty(getId('last_name').value)){
				$('#last_name').focus();
			}else if(empty(getId('gender').value)){
				$('#gender').focus();
			}else if(empty(getId('religion').value)){
				$('#religion').focus();
			}
			else if(empty(getId('date_of_birth').value)){
				$('#date_of_birth').focus();
			}
			else if(empty(getId('state').value)){
				$('#state').focus();
			} 
			else if(empty(getId('lga').value)){
				$('#lga').focus();
			}
			else if(empty(getId('tribe').value)){
				$('#tribe').focus();
			}
			else if(empty(getId('class').value)){
				$('#class').focus();
			}
			else if(empty(getId('residential_address').value)){
				$('#residential_address').focus();
			}
			else if(getId('previous_school').value != '' && getId('reason_for_leaving_the_school').value == ''){
				$('#reason_for_leaving_the_school').focus();
			}
			else{
				
				
					//post the data to php
				abdul_android_pageOverlay();
				document.getElementById("abdul_android_boxContent").innerHTML = 'SAVING,please  wait.....';
					$.post('../php/submit_application.php',{first_name:first_name,last_name:last_name,other_name:other_name,gender:gender,religion:religion,
					date_of_birth:date_of_birth,state:state,lga:lga,tribe:tribe,email_address:email_address,
					phone_number:phone_number,student_class:student_class,previous_school:previous_school,reason_for_leaving_the_school:reason_for_leaving_the_school,
					guidian_other_phone_number:guidian_other_phone_number,residential_address:residential_address,postal_code,postal_code,
					guidian_name:guidian_name,guidian_phone_number:guidian_phone_number,guadian_relationship:guadian_relationship,
					guidian_address:guidian_address,guidain_occupation:guidain_occupation},
					function(response,error){
							document.getElementById("abdul_android_boxContent").innerHTML = response;	
							load_application_form();
							 setTimeout(function(){myAlert.close();},5000);
						
					});
			}
	
				
}



function abdul_android_pageOverlay()
		{
			var overlay = document.getElementById("abdul_android_pageOverlay");
			var winH 		=  window.innerHeight;
				overlay.style.display = 'block';
				overlay.style.height = winH+"px";
				setTimeout(function(){myAlert.show();},100);
				
		}
		
		function myAlertBox()
		{
			this.show = function()
			{
				 $("#abdul_android_alertBox").show(1000);
				
				 //document.getElementById("abdul_android_boxContent").innerHTML = 'loading,please  wait.....';
				 	
				 
			}
			
			this.close = function()
			{
				// document.getElementById("abdul_android_pageOverlay").style.display = 'none';
				 $("#abdul_android_pageOverlay").hide(2000);
				 $("#abdul_android_alertBox").hide(2000);
				 
				 
				 //document.getElementById("abdul_android_alertBox").style.display = 'none';
			}
		}
		
			var myAlert = new myAlertBox();
			