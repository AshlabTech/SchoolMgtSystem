<?php session_start(); ?>
<?php
if (isset($_SESSION['staff_info_id'])) {
} else {
    header('location:../');
}
include_once('../php/connection.php');
$run = $conn->query("SELECT *, t.description as term, c.section_name_abr as sections FROM `manage_term_info` as m INNER JOIN term as t on t.id = m.term_id INNER JOIN school_section as c ON c.school_section_id = m.section_id INNER JOIN session as s on s.section_id = m.session_id
 ") or die (mysqli_error($conn));;
$term_info = [];
if($run->num_rows >0){
    while($row = $run->fetch_assoc()){
        $term_info[] = $row;
    }
}

$run = $conn->query("SELECT * FROM `school_section`") or die (mysqli_error($conn));
$sections = [];
if($run->num_rows >0){
    while($row = $run->fetch_assoc()){
        $sections[] = $row;
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
    <div id="app" v-show="!isloadingpage" class="no-visible position-relative">
        <div v-if="!isloading" :class="'alert '+alertType+ ' py-1 px-2 text-center w-100'" id="successPay">{{message}}</div>
        <div v-else class="spinner-grow text-info" role="status" key="loader">
            <span class="sr-only">Loading...</span>
        </div>		
        <div class="row w-100">
            <div class="col-lg-4 col-md-6 col-sm-6">
                <label class="w-100">Term: </label><input disabled type="text" class="form-control w-75" :value="term +', '+session+' Session'">
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <label class="w-100">Section: </label>
                <select type="text"  class="form-control edx category w-75" v-model="items.section_id"  >
                    <option v-for="section in sections" :value="section.school_section_id" >{{section.section_name_abr}}</option>
                </select>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <label class="w-100">Resumption Date: </label><input type="date" class="form-control w-75" v-model="items.resumption">
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <label class="w-100">Vacation Date: </label><input type="date" class="form-control w-75" v-model="items.vacation">
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <label class="w-100 ">Next Term Resumption Date: </label><input type="date" class="form-control w-75" v-model="items.next_resumption">
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <label  style="" class="d-inline">Apply to All Sections</label>
                <input type="checkbox" class="d-inline mb-2 mt-2" v-model="items.toAll" value="all">
                <button class="btn btn-primary w-75" @click="add">Add</button>
            </div>
        </div>
        <hr style="box-shadow: 1px 2px 4px #ccc;">
        <div id="pdfPrint" style="position:absolute; left:0;top:36%;"></div>        
        <table class="table table-hover w-100" style="border-left:3px solid #ccc;border-right:3px solid #ccc;">
            <thead>
                <tr>
                    <th width="7%">s/n</th>
                    <th>Term</th>
                    <th>Section</th>
                    <th width="9%">Resumption Date</th>
                    <th width="9%">Vacation Date</th>
                    <th width="9%">Next Term Resumption Date</th>
                    <th width="7%">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item,index) in term_info">
                    <td width="5%">{{index+1}} <input type="text" style="display: none;" class="form-control" v-model="item.id"></td>
                    <td align="center"><span :class="'text-center item_'+index">{{item.term +', '+ item.section }} Session</span></td>
                    <td><span :class="'spandx item_'+index">{{item.sections}}</span>                     
                        <select type="text"  class="form-control edx d-none section" >
                            <option v-for="section in sections" :value="section.school_section_id" :selected="section.school_section_id==item.school_section_id" >{{section.section_name_abr}}</option>
                        </select>
                    </td>
                    <td><span :class="'spandx item_'+index">{{item.resumption}}</span> <input type="date" class="form-control edx d-none resumption" :value="item.resumption"></td>
                    <td><span :class="'spandx item_'+index">{{item.vacation}}</span> <input type="date" class="form-control edx d-none vacation" :value="item.vacation"></td>
                    <td><span :class="'spandx item_'+index">{{item.next_resumption}}</span> <input type="date" class="form-control edx d-none next_resumption" :value="item.next_resumption"></td>
                    <td>
                        <button class="btn btn-warning text-white" @click="edit($event)" >edit</button>
                        <button class="btn btn-success text-white d-none" @click="save(index,item, $event, 'item_'+index)" >Save</button>
                    </td>
                </tr>
            </tbody>
        </table>
        
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
                term: '<?= ucfirst($term); ?>',
                session: '<?= $session; ?>',                
                session_id: '<?= $session_id; ?>',
                sections: <?php echo json_encode($sections); ?>,
                items: {
                    term_id: '<?= $term_id; ?>',
                    session_id:'<?= $session_id; ?>',
                    section_id:"",
                    resumption:"",
                    vacation:"",
                    next_resumption:"",
                    toAll: ["all"],
                    id:''
                },
                term_info: <?php echo json_encode($term_info);?>
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
                    
                    for(key in this.items){
                        
                        if(this.items[key] == '' && key != 'id' && key !='term_id' && key !='session_id' && key != 'toAll' ){
                            $this.message = `${key} field is required`;
                            $this.alertType ="alert-warning"; 
                            return 0;

                        }
                    }
                    this.isloading = true;
                    
                    $.post("../php/add_term_info.php", {                            
                        term_id : this.items.term_id,
                        session_id : this.items.session_id,
                        section_id : this.items.section_id,
                        resumption : this.items.resumption,
                        vacation : this.items.vacation,
                        next_resumption : this.items.next_resumption,
                        id : this.items.id,
                        toAll : this.items.toAll[0],
                        type:'add',                  
                    }, (response)=> {
                        /* $('#successPay').addClass('d-none'); */
                        response = JSON.parse(response)                            
                        if (response.success == 200) {     
                            if($this.items.toAll[0] =='all'){
                                if(confirm('Completed successfullly ')){
                                    location.reload();                                    
                                }else{
                                    location.reload();                                    
                                }
                            }                   
                            $this.items.id = response.id;
                            $this.message = "Completed successfullly ";
                            $this.alertType ="alert-success";                            
                            $this.isloading = false,
                            $this.stock_items.push($this.items);                        
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
                        section_id :elt.parent().parent().find('.section').val(),
                        resumption :elt.parent().parent().find('.resumption').val(),
                        vacation :elt.parent().parent().find('.vacation').val(),
                        next_resumption :elt.parent().parent().find('.next_resumption').val(),                                                
                        id: item.id,
                        section_name_abr:elt.parent().parent().find('.section option:selected ').text()
                    }
                    
                    $.post("../php/add_term_info.php", {                                                    
                        section_id : itemsx.section_id,
                        resumption : itemsx.resumption,
                        vacation : itemsx.vacation,
                        next_resumption : itemsx.next_resumption,
                        id : itemsx.id,                                                
                        type:'save',                  
                    }, (response)=> {
                        /* $('#successPay').addClass('d-none'); */                        
                        if (response == 200){                                                                       
                            $this.message = "Completed successfullly ";
                            $this.alertType ="alert-success";                            
                            $this.isloading = false,
                            $this.term_info[index]= itemsx;
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
                $('#app').removeClass('no-visible');
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