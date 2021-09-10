	$(document).ready(function(){
			$("#d_down").click(function(){
				$('#login_dropdown').slideToggle('slow');
			}); 
			$("#waec_neco").click(function(){
				$('#waec_neco_dropdown').slideToggle('slow');
			});
			
			$('#waec_neco_dropdown').mouseleave(function(){
					$(this).slideUp('slow');
			});
			$('#login_dropdown').mouseleave(function(){
					$(this).slideUp('slow');
			});
			
			$('.carousel').carousel(3);
	});
	
	