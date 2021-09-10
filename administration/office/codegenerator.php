<?php 
	include_once('../php/connection.php');		
	if (isset($_POST['generateNewCode'])) {
		
		$school = "select abbr from school limit 1";
		$school_run =  mysqli_query($conn,$school) or die(mysqli_error($conn));
		$row = mysqli_fetch_assoc($school_run);
		$abbr = $row['abbr'];
		$date = date('Y-m-d h:s');
		$store =array();
		$class_id = $_POST['class'];
		$txt = "";
		for ($i = 0; $i < 60; $i++) {
		    $value = rand(100,1000);
			$array = crc32($date) + $value;		    
			//$store = $array;
			//$txt .= $array."\\n";
			$data =  '('.$array.','.$class_id.')';			
			array_push($store,$data);
		}
		//echo $txt;
		//die();
		$check = mysqli_query($conn,"SELECT class_id from code where class_id = '$class_id' LIMIT 1") or die(mysqli_error($conn));
		$exist = mysqli_fetch_assoc($check);


		$class_sql = mysqli_query($conn,"SELECT class_name as name from classes where class_id = '$class_id' LIMIT 1") or die(mysqli_error($conn)) ;
		$className = mysqli_fetch_assoc($class_sql);


		if (is_null($exist)) {			
			$insert = mysqli_query($conn,"INSERT INTO code(name,class_id) VALUES  ".implode($store, ',')."") or die(mysqli_error($conn));
			
			if ($insert) {
				$success = "code generated successfully <div class='btn btn-primary btn-sm' onclick='location.reload();'>ok</div>";
				/*$myfile = fopen('result_code/'.$className['name'].$date.".txt", "w");
				fwrite($myfile, $txt);*/
			}

		}else{
			//code already generated else clear code and try again
			$message = "code already generated else clear code and try again";
		}
		
	}
	?>

<head>
		<title>Peace Group of Schs. | MOKWA </title>
			
		<link rel="shortcut icon" href="../../images/e_portal.png">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	   
		<link rel="stylesheet" href="../../css/bootstrap.css">
		<link rel="stylesheet" href="../../css/bootstrap.min.css">
		<link rel="stylesheet" href="../../css/bootstrap-theme.css">
		<link rel="stylesheet" href="../../css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="../../css/styles.css">
		
		
		<style>
			
		</style>

	</head>
<div class="row">
	<div class="col-sm-12 col-md-6 col-lg-6">
		<form accept="" method="POST" class="w-100" >		
		<center>			
			<select class="form-control" style="margin-bottom: 5px;" name="class" required="">
				<?php
				$sql_class = "select * from classes where status = '1'";
				$sql_class_run =  mysqli_query($conn,$sql_class) or die(mysqli_error($conn));
				while ($row = mysqli_fetch_assoc($sql_class_run)) {
					?>
					<option value="<?= $row['class_id'] ?>"><?= $row['class_name'] ?></option>
					<?php
				}
				?>
			</select>
			<input type="submit" name="generateNewCode" class="btn btn-lg bg-info" value="GENERATE" style="padding: 20px 40px; background-color: #6c2; color: white; border: none; cursor: pointer; box-shadow: 0px 0px 6px #ccc; border-radius: 6px;">

			<?php
				if (isset($message)) {
					?>
					<div class="alert alert-danger"><?php echo $message; ?></div>
					<?php
				}
				if (isset($success)) {
					?>
					<div class="alert alert-success"><?php echo $success; ?></div>
					<?php
				}
			?>
		</center>
	</form>
	</div>
	<div class="col-sm-12 col-md-6 col-lg-6" style="border: 1px solid #ccc;padding: 15px;">
		<script >
			function printOut(){				
				let pass = prompt('Please enter pass code', 10);
					if (pass == 100) {
						 var printContent = document.getElementById('showTopDetailsContent');
						 	  var windowUrl = 'about:blank';
						        var uniqueName = new Date();
						        var windowName = 'Print' + uniqueName.getTime();
						        var printWindow = window.open(windowUrl, windowName, 'left=500,top=500,width=0,height=0');
						        printWindow.document.write(printContent.innerHTML);
								printWindow.document.getElementById('showTopDetailsContent').style.overflow='none !important';
								printWindow.document.getElementById('showTopDetailsContent').style.height='100% !important';
								printWindow.document.close();
						        printWindow.focus();
						        printWindow.print();

						        printWindow.close();

						        return false;
					
					}
				}
			
		</script>
		<button onclick="printOut()">print</button>
		<div style="overflow-y: scroll; height: 500px; " id="showTopDetailsContent">
			
		<table class="table">
			<thead>
				<th>s/n</th>
				<th>class</th>
				<th>code</th>
			</thead>

			<tbody>
				
		<?php
			
			$session = mysqli_query($conn, "SELECT section_id FROM session WHERE status = '1'")or die(mysqli_error($conn));
			$csession = mysqli_fetch_assoc($session);
			$csession = $csession['section_id'];

			$term = mysqli_query($conn, "SELECT term_id FROM term WHERE status = '1'");
			$cterm = mysqli_fetch_assoc($term);
			$cterm = $cterm['term_id'];

			
			$fetch_code = mysqli_query($conn, "SELECT * FROM code as c INNER JOIN classes as x ON c.class_id = x.class_id ");
			
			$fetch_used_code = mysqli_query($conn, "SELECT * FROM result_code  WHERE session_id = $csession AND term_id = $cterm");
			$used_code = array();
			while ($crow = mysqli_fetch_assoc($fetch_used_code)) {
					$used_code[] = $crow['code'];
				}
			$sn = 0;
			while ($row = mysqli_fetch_assoc($fetch_code)) {
				$sn++;
				if(!in_array($row['name'], $used_code)){

					?>
					<tr>
						<td><?= $sn; ?></td>
						<td><?= $row['class_name']; ?></td>
						<td><?= $row['name']; ?></td>
					</tr>
			<?php
				}
			}
		?>
			</tbody>
		</table>
		</div>
	</div>
	
</div>