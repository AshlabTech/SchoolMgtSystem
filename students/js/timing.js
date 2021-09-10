function printTime()
		{
			var now = new Date();
			var hours = now.getHours();
			var minutes = now.getMinutes();
			var seconds = now.getSeconds();
			var pmAm = "";
			if(hours > 11)
			{
				pmAm = "PM";
				hours = hours - 12;
			}
			 else if(hours <=11)
			{
				pmAm = "AM";
				
			}
			if(hours < 10)
				{
					hours = "0"+hours;
				}
			if(minutes <10)
			{
					minutes = "0"+minutes;
			}
			if(seconds < 10)
			{
				seconds = "0"+seconds;
			}
			document.getElementById("timing").innerHTML = hours+" : "+minutes+" : "+pmAm;
			
		}
		
		setInterval("printTime()", 1000);