<?php session_start(); ?>
<?php
if (isset($_SESSION['staff_info_id'])) {
} else {
    header('location:../');
}
include_once('../php/connection.php');


$run = $conn->query("SELECT * FROM `comment_template`") or die (mysqli_error($conn));
$templates = [];
if($run->num_rows >0){
    while($row = $run->fetch_assoc()){
        $templates[] = $row;
    }
}
 
$session_id = $_GET['session_id'];
$session = "";
$term = "";
$term_id="";
$session_id = '';
	$session = $conn->query("SELECT * FROM session WHERE status = '1'") or die(mysqli_error($conn));
		$term = $conn->query("SELECT * FROM term WHERE status = '1'") or die(mysqli_error($conn));
		if ($session->num_rows>0) {
			if ($term->num_rows>0) {
				$ss1 = $session->fetch_assoc();
				$tt1 = $term->fetch_assoc();
	 			$session_id = $ss1['section_id'];
	 			$term_id = $tt1['id'];				 
				$session =  $ss1['section'];
				$term = $tt1['description'];				
			
			}else{
				echo "term not set";
				exit();
			}	
		}else{
			echo "session not set";
			exit();
		}
        
?>
<!DOCTYPE html>
<html>

<head>
    <title> <?php echo $school_abbr; ?> </title>

    <link rel="shortcut icon" href="../../images/e_portal.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../css/boostrap4.css">
    <link rel="stylesheet" href="../css/datatable.js">

    <link rel="stylesheet" href="../../css/bootstrap-theme.css">
    <link rel="stylesheet" href="../../css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css">
    <script src="../js/jquery3.js"></script>
    <script src="../js/bootstrap4.js"></script>
    <script src="../js/propper.js"></script>
    <link rel="stylesheet" type="text/css" href="../js/datatable/datatables.min.css" />

    <script type="text/javascript" src="../js/datatable/pdfmake.min.js"></script>
    <script type="text/javascript" src="../js/datatable/datatables.min.js"></script>
    <script type="text/javascript" src="../js/datatable/vfs_fonts.js"></script>
    <script src="../js/vue.js"></script>



    <style>
        body {
            margin: 0;
            padding: 0;
            font-size: 0.9em;
            width: 100%;
        }    

        /* width */
        ::-webkit-scrollbar {
        width: 8px;
        height:8px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
        box-shadow: inset 0 0 5px grey; 
        border-radius: 10px;
        }
        
        /* Handle */
        ::-webkit-scrollbar-thumb {
        background: #bbb; 
        border-radius: 10px;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
        background: #888; 
        }
        th{
            color:#777;
        }

        td {
            cursor: pointer;
        }
        .no-visible{
            visibility: hidden;
        }
        .d-none {
            display: none;
        }

        .modal-backdrop {
            background-color: transparent;
        }
    </style>

</head>

<body style="background:#fff; width:100%; padding:0px 20px;">
    <div id="app"  class=" position-relative">
        <div v-if="!isloading" :class="'alert '+alertType+ ' py-1 px-2 text-center w-100'" id="successPay">{{message}}</div>
        <div v-else class="spinner-grow text-info" role="status" key="loader">
            <span class="sr-only">Loading...</span>
        </div>		
        <div v-show="!isloadingpage"  id="app_content" class="no-visible">
        <div class="row w-100">            
            <div class="col-lg-4 col-md-4 col-sm-6">
                <label class="w-100">Comment: </label>
                <input type="text" class="form-control w-75" v-model="items.comment">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <label class="w-100">Comment Type: </label>
                <input type="text" class="form-control w-75" v-model="items.comment_type">
            </div>            
            <div class="col-lg-4 col-md-4 ">                                
                <center>
                    <button class="btn btn-primary w-75" @click="add">Add</button>
                </center>
            </div>
        </div>
        <hr style="box-shadow: 1px 2px 4px #ccc;">
        <div id="pdfPrint" style="position:absolute; left:0;top:36%;"></div>        
        <table class="table table-hover w-100" style="border-left:3px solid #ccc;border-right:3px solid #ccc;">
            <thead>
                <tr>
                    <th width="7%">s/n</th>
                    <th width="50%">Comment</th>
                    <th>Comment Type</th>    
                    <th>Action</th>               
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item,index) in templates">
                    <td width="7%">{{index+1}} <input type="text" style="display: none;" class="form-control" v-model="item.id"></td>                    
                    <td><span :class="'spandx item_'+index">{{item.comment}}</span> <input type="text" class="form-control edx d-none comment" :value="item.comment"></td>
                    <td><span :class="'spandx item_'+index">{{item.comment_type}}</span> <input type="text" class="form-control edx d-none comment_type" :value="item.comment_type"></td>
                    <td>
                        <button class="btn btn-warning text-white" @click="edit($event)" >edit</button>
                        <button class="btn btn-success text-white d-none" @click="save(index,item, $event, 'item_'+index)" >Save</button>
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>
    <script>
        $(document).ready(function() {

           var table =  $('.table').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    /* 'copy', 'excel', */
                    'pdf'
                ],
                pageLength: 3,

            });
            setTimeout(() => {
                $('.buttons-pdf').detach().appendTo('#pdfPrint');
                //$('#pdfPrint').appendTo($('buttons-pdf').html());	                            
            }, 1000);
        })

        var vm = new Vue({
            el: "#app",
            data: {
                isloading:true,
                isloadingpage:true,
                message:'Manage Term Info',
                alertType:'alert-light',
                templates: <?php echo json_encode($templates); ?>,
                items: {              
                    comment:"",
                    comment_type:"",                    
                    id:''
                },                
            },
            methods: {          
            
                forModal(student) {
                    //$('#exampleModalLongTitle').text(student)
                    this.showMessage()                                       
                   // this.selected = student;

                },
            
                add() {                    
                    //this.payment_no = 'OPT'+rand(1, 10);
                    var $this = this;
                    
                    if(this.items.comment == ''){
                        $this.message = `comment field is required`;
                        $this.alertType ="alert-warning"; 
                        return 0;
                    }

                    if(this.items.comment_type == ''){
                        $this.message = `comment type field is required`;
                        $this.alertType ="alert-warning"; 
                        return 0;
                    }

                    this.isloading = true;
                    
                    $.post("../php/add_comment_template.php", {                            
                        comment : this.items.comment,
                        comment_type : this.items.comment_type,                        
                        id : this.items.id,                        
                        type:'add',                  
                    }, (response)=> {
                        /* $('#successPay').addClass('d-none'); */
                        response = JSON.parse(response)                            
                        if (response.success == 200) {                                        
                            $this.items.id = response.id;
                            $this.message = "Completed successfullly ";
                            $this.alertType ="alert-success";                            
                            $this.isloading = false,
                            $this.templates.push($this.items);                        
                            $this.resetTable();
                        }
                    })
                },
                edit(e) {
                    var elt = $(e.target);
                    elt.parent().parent().find('.spandx').hide();
                    elt.parent().parent().find('.edx').removeClass('d-none');
                    elt.next().removeClass('d-none')
                    elt.addClass('d-none')
                },
                save(index,item, e, cl) {
                    var elt = $(e.target);
                    var $this = this;
                    this.isloading = true;
                    var itemsx = {
                        comment :elt.parent().parent().find('.comment').val(),
                        comment_type :elt.parent().parent().find('.comment_type').val(),                                                                   
                        id: item.id,                        
                    }
                    
                    $.post("../php/add_comment_template.php", {                                                    
                        comment : itemsx.comment,
                        comment_type : itemsx.comment_type,                       
                        id : itemsx.id,                                                
                        type:'save',                  
                    }, (response)=> {
                        /* $('#successPay').addClass('d-none'); */                        
                        if (response == 200){                                                                       
                            $this.message = "Completed successfullly ";
                            $this.alertType ="alert-success";                            
                            $this.isloading = false,
                            $this.templates[index]= itemsx;
                            $this.resetTable();
                            elt.parent().parent().find('.spandx').show();
                            elt.parent().parent().find('.edx').addClass('d-none');
                            elt.prev().removeClass('d-none')
                            elt.addClass('d-none')
                        }
                    })
                },
                showMessage(){
                    $('#successPay').removeClass('no-visible');
                },
                resetTable(){
                    var table =  $('.table').DataTable({
                    responsive: true,
                    dom: 'Bfrtip',
                    destroy: true,
                    buttons: [
                        /* 'copy', 'excel', */
                        'pdf'
                    ],
                    pageLength: 3,

                    });
                    setTimeout(() => {
                        $('.buttons-pdf').detach().appendTo('#pdfPrint');
                        //$('#pdfPrint').appendTo($('buttons-pdf').html());	                            
                    }, 1000);
                },
            },
          
            created() {
                //console.log(this.paymentTypes)          
            },
            mounted() {
                console.log(this.items)
                var $this = this;
                $('#app_content').removeClass('no-visible');
                $(document).ready(function() {     
                    setTimeout(() => {
                        $this.isloading = false;
                        $this.isloadingpage = false;
                        
                    }, 2500);              
                })
            }
        })
    </script>

</body>

</html>