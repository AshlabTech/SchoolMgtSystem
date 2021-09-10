
function switch_phases(off,on,num){
	if(off == 1){
			if(empty(getId('first_name').value)){
				$('#first_name').focus();
			}	
			else if(empty(getId('last_name').value)){
				$('#last_name').focus();
			}
			else if(empty(getId('gender').value)){
				$('#gender').focus();
			}
			else{
					$("#phase"+off).fadeOut('slow');
					$("#phase"+on).fadeIn('slow');
					$("#phase_number").html(num+' of 4');
			}
	}else if(on == 3 && off == 2){
		//validate before posting the data to php
	if(empty(getId('email_address').value)){
				$('#email_address').focus();
			}
			else if(empty(getId('phone_number').value)){
				$('#phone_number').focus();
			}
			else{
					$("#phase"+off).fadeOut('slow');
					$("#phase"+on).fadeIn('slow');
					$("#phase_number").html(num+' of 4');
			}
	}

	else{
					$("#phase"+off).fadeOut('slow');
					$("#phase"+on).fadeIn('slow');
					$("#phase_number").html(num+' of 4');
			}

}
function make_a_class_teacher(){
	var section = getId('section').value;

	
	if(section !=''){
		if(event.target.value == 'Yes'){
			$("#teacher_class").fadeIn('slow');
			load_classees(section);
		}else{
			$("#teacher_class").fadeOut('slow');
			getId("class").innerHTML = '';
			getId("class").innerHTML = '<option value="" selected></option>';
			
		}
			
		
	}else{
		alert('please select section ');
	}
	//school section 
	
}

function load_classees(token){
	getId("class").innerHTML = '';
	$.post("../php/load_classees.php",{token:token},function(data){
		getId("class").innerHTML = data;
	});
	
}
function add_new_staff(){
	// staff  data
	var first_name = getId('first_name').value;
	var last_name = getId('last_name').value;
	var other_name = getId('other_name').value;
	var gender = getId('gender').value;
	var religion = getId('religion').value;
	var marital_status = getId('marital_status').value;
	var date_of_birth = getId('date_of_birth').value;
	var state = getId('state').value;
	var lga = getId('lga').value;
	var tribe = getId('tribe').value;
	var email_address = getId('email_address').value;
	var phone_number = getId('phone_number').value;
	var other_phone_numbers = getId('other_phone_numbers').value;
	var residential_address = getId('residential_address').value;
	var postal_code = getId('postal_code').value;
	
	// next of kin data
	var next_of_kin = getId('next_of_kin').value;
	var next_of_kin_phone_number = getId('next_of_kin_phone_number').value;
	var relationship_with_next_of_kin = getId('relationship_with_next_of_kin').value;
	var next_of_kin_residential_address = getId('next_of_kin_residential_address').value;
	var next_of_kin_postal_code = getId('next_of_kin_postal_code').value;
	
	//staff educational
	var highest_qualification = getId('highest_qualification').value;
	var school = getId('school').value;
	var date_obtained = getId('date_obtained').value;
	var refree = getId('refree').value;
	var refree_hone_number = getId('refree_hone_number').value;
	//school section 
	var section = getId('section').value;
	var teacher_class = getId('class').value;
	var staff_category = getId('staff_category').value;
	
	
			getId('save_staff_output').innerHTML = '<img src="../../images/ajax-loader.gif">';
					//post the data to php
					$.post('../php/add_new_staff.php',{first_name:first_name,last_name:last_name,other_name:other_name,gender:gender,religion:religion,
					marital_status:marital_status,date_of_birth:date_of_birth,state:state,lga:lga,tribe:tribe,email_address:email_address,section:section,
					phone_number:phone_number,other_phone_numbers:other_phone_numbers,residential_address:residential_address,postal_code:postal_code,
					next_of_kin:next_of_kin,next_of_kin_phone_number:next_of_kin_phone_number,relationship_with_next_of_kin:relationship_with_next_of_kin,
					next_of_kin_residential_address:next_of_kin_residential_address,next_of_kin_postal_code:next_of_kin_postal_code,refree:refree,teacher_class:teacher_class, 
					refree_hone_number:refree_hone_number,date_obtained:date_obtained,school:school,highest_qualification:highest_qualification,
					staff_category:staff_category},
					function(response,error){
						getId('save_staff_output').innerHTML = response;
					});
			
	
}

function save_new_student(){
getId('add_new_student_feedback').innerHTML = '<div class="alert alert-danger">skkskksk</div>';
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
	var student_type = getId('student_type').value;

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
			else if(empty(getId('student_type').value)){
				$('#student_type').focus();
			}
			else if(empty(getId('guidian_name').value)){
				$('#guidian_name').focus();
			}
			else if(empty(getId('guidian_phone_number').value)){
				$('#guidian_phone_number').focus();
			} 
			else if(empty(getId('guadian_relationship').value)){
				$('#guadian_relationship').focus();
			}
			
			
			else if(getId('previous_school').value != '' && getId('reason_for_leaving_the_school').value == ''){
				$('#reason_for_leaving_the_school').focus();
			}
			else{
				getId('add_new_student_feedback').innerHTML = '<img src="../../images/ajax-loader.gif">';
					//post the data to php
					$.post('../php/save_new_student.php',{first_name:first_name,last_name:last_name,other_name:other_name,gender:gender,religion:religion,
					date_of_birth:date_of_birth,state:state,lga:lga,tribe:tribe,email_address:email_address,
					phone_number:phone_number,student_class:student_class,previous_school:previous_school,reason_for_leaving_the_school:reason_for_leaving_the_school,
					guidian_other_phone_number:guidian_other_phone_number,residential_address:residential_address,postal_code:postal_code,
					guidian_name:guidian_name,guidian_phone_number:guidian_phone_number,guadian_relationship:guadian_relationship,
					guidian_address:guidian_address,guidain_occupation:guidain_occupation,student_type:student_type},
					function(response,error){
										
						if(response == 200){
							alert('The student has been added successfully...');
							load_all_student();
						}else{
							getId('add_new_student_feedback').innerHTML = '<div class="alert alert-danger">'+response+'</div>';
						}
								
							
					});
			}
	
				
}

function update_student_info(token){
	
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
			else if(empty(getId('guidian_name').value)){
				$('#guidian_name').focus();
			}
			else if(empty(getId('guidian_phone_number').value)){
				$('#guidian_phone_number').focus();
			} 
			else if(empty(getId('guadian_relationship').value)){
				$('#guadian_relationship').focus();
			}
			else if(empty(getId('guidain_occupation').value)){
				$('#guidain_occupation').focus();
			}
			else if(getId('previous_school').value != '' && getId('reason_for_leaving_the_school').value == ''){
				$('#reason_for_leaving_the_school').focus();
			}
			else{
				getId('add_new_staff_form').innerHTML = '<img src="../../images/ajax-loader.gif">';
					//post the data to php
					$.post('../php/update_student_info.php',{token:token,first_name:first_name,last_name:last_name,other_name:other_name,gender:gender,religion:religion,
					date_of_birth:date_of_birth,state:state,lga:lga,tribe:tribe,email_address:email_address,
					phone_number:phone_number,student_class:student_class,previous_school:previous_school,reason_for_leaving_the_school:reason_for_leaving_the_school,
					guidian_other_phone_number:guidian_other_phone_number,residential_address:residential_address,postal_code:postal_code,
					guidian_name:guidian_name,guidian_phone_number:guidian_phone_number,guadian_relationship:guadian_relationship,
					guidian_address:guidian_address,guidain_occupation:guidain_occupation},
					function(response,error){
								
								alert(response);
								load_student_info_data(token);
								
							
					});
			}
	
				
}

function add_subject(){
		var subject_name = getId('subject_name').value;
		var subject_code = getId('subject_code').value;
		var school_section = getId('school_section').value;
		
			if(empty(subject_name)){
				$('#subject_name').focus();
			}else if(empty(subject_code)){
				$('#subject_code').focus();
			}else if(empty(school_section)){
				$('#school_section').focus();
			}else{
				abdul_android_pageOverlay();
				document.getElementById("abdul_android_boxContent").innerHTML = 'SAVING,please  wait.....';
				$.post('../php/add_subject.php',{subject_name:subject_name,subject_code:subject_code,school_section:school_section},
					function(response,error){
							document.getElementById("abdul_android_boxContent").innerHTML = response;	
							 setTimeout(function(){myandroidAlert.close();},5000);
						
					});
			}
		
}

	
		function make_payment(token){

			var class_pay_for =  document.getElementById("class_pay_for").value;
			var school_fee_term =  document.getElementById("school_fee_term").value;
			var payment_type = document.getElementById("payment_typee").value;
			var school_fee_day =  document.getElementById("school_fee_day").value;
			var school_fee_month =  document.getElementById("school_fee_monthh").value;
			var school_fee_year =  document.getElementById("school_fee_yearr").value;
			var amount_paid =  document.getElementById("amount_paid").value;
			var payee_name =  document.getElementById("payee_name").value;
			//var payment_session =  document.getElementById("payment_session").value;
			var payment_number =  document.getElementById("payment_number").value;
		
				getId("make_payment_status").innerHTML = "<h4 style='text-align:center'><img src='../../images/ajax-loader.gif'></h4>";
						$.post("../php/make_payment.php",{token:token,class_pay_for:class_pay_for,school_fee_term:school_fee_term,
						payment_type:payment_type,school_fee_day:school_fee_day,school_fee_month:school_fee_month,school_fee_year:school_fee_year,
						amount_paid:amount_paid,payee_name:payee_name,payment_number:payment_number},function(response){
							getId("make_payment_status").innerHTML = response;
							//getId("make_payment_status").innerHTML = '';
						})
			
			
			
		}
		
		function payment_number_showHide(token){
				if(token == 1){
					$("#payment_number_wrap").fadeIn('slow');
			
			}else{
					$("#payment_number_wrap").fadeOut('slow');
			}
		}
		
		


