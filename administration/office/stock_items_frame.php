<?php session_start(); ?>
<?php
if (isset($_SESSION['staff_info_id'])) {
} else {
    header('location:../');
}
include_once('../php/connection.php');


$session_id = $_GET['session_id'];

$sql = "SELECT * FROM `misc_payment_details`";
$run = $conn->query($sql);
$stock_items = [];
if($run->num_rows > 0){
    while($row = $run->fetch_assoc()){
        $stock_items[] = $row;
    }
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
            font-size: 1em;
            width: 100%;



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

<body style="background:#fff">
    <div id="app" v-show="!isloadingpage" class="no-visible position-relative">
        <div v-if="!isloading" :class="'alert '+alertType+ ' py-1 px-2 text-center w-100'" id="successPay">{{message}}</div>
        <div v-else class="spinner-grow text-info" role="status" key="loader">
            <span class="sr-only">Loading...</span>
        </div>		
        <div class="row w-100">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <label class="w-25">Name: </label><input type="text" class="form-control w-75" v-model="items.name">
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <label class="w-25">Category: </label>
                <select type="text" class="form-control w-75" v-model="items.category">
                    <option value="exam">Exams</option>
                    <option value="material">Materials</option>
                    <option value="other">Others</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <label class="w-25">Abbr: </label><input type="text" min="0" class="form-control w-75" v-model="items.abbr" >
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <label class="w-25">Price: </label><input type="number" min="0" class="form-control w-75" v-model="items.amount" >
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <label  style="visibility: hidden;" class="w-100">add</label>
                <button class="btn btn-primary " @click="add">Add</button>
            </div>
        </div>
        <div id="pdfPrint" style="position:absolute; left:0;top:27%;"></div>
        <table class="table table-hover w-100">
            <thead>
                <tr>
                    <td>s/n</td>
                    <td>Name</td>
                    <td>Abbr</td>
                    <td>Category</td>
                    <td>Price</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item,index) in stock_items">
                    <td>{{index+1}} <input type="text" style="display: none;" class="form-control" v-model="item.id"></td>
                    <td><span :class="'item_'+index">{{item.name}}</span> <input type="text" class="form-control edx d-none name" :value="item.name"></td>
                    <td><span :class="'item_'+index">{{item.abbr}}</span> <input type="text" class="form-control edx d-none abbr" :value="item.abbr"></td>
                    <td><span :class="'item_'+index">{{item.category}}</span>                     
                        <select type="text"  class="form-control edx d-none category" >
                            <option :selected="item.category=='exam'" value="exam">Exams</option>
                            <option :selected="item.category=='material'" value="material">Materials</option>
                            <option :selected="item.category=='other'"  value="other">Others</option>
                        </select>
                    </td>
                    <td><span :class="'item_'+index">{{item.amount}}</span> <input type="text" class="form-control edx d-none amount" :value="item.amount"></td>
                    <td>
                        <button class="btn btn-warning text-white" @click="edit($event)" >edit</button>
                        <button class="btn btn-success text-white d-none" @click="save(index,item, $event)" >Save</button>
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
                message:'Stock Items',
                alertType:'alert-light',
                items: {
                    name: "",
                    category: "",
                    amount: "",
                    abbr: "",
                    id:""

                },
                stock_items: <?php echo json_encode($stock_items);?>
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
                        if(this.items[key] == '' && key != 'id'){
                            $this.message = `${key} field is required`;
                            $this.alertType ="alert-warning"; 
                            return 0;

                        }
                    }
                    this.isloading = true;
                    
                    $.post("../php/add_stock_items.php", {                            
                        name: this.items.name,
                        category: this.items.category,
                        amount: this.items.amount,
                        abbr: this.items.abbr,
                        id:"",
                        type:'add',                  
                    }, (response)=> {
                        /* $('#successPay').addClass('d-none'); */
                        response = JSON.parse(response)             
                        if (response.success == 200) {                        
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
                    elt.parent().parent().find('span').hide();
                    elt.parent().parent().find('.edx').removeClass('d-none');
                    elt.next().removeClass('d-none')
                    elt.addClass('d-none')
                },
                save(index,item, e) {
                    var elt = $(e.target);
                    var $this = this;
                    this.isloading = true;
                    var itemsx = {
                        name:elt.parent().parent().find('.name').val(),
                        abbr:elt.parent().parent().find('.abbr').val(),
                        category:elt.parent().parent().find('.category').val(),
                        amount:elt.parent().parent().find('.amount').val(),
                        id: item.id
                    }
                    
                    $.post("../php/add_stock_items.php", {
                        name: itemsx.name,
                        category: itemsx.category,
                        amount: itemsx.amount,
                        abbr: itemsx.abbr,
                        id:itemsx.id, 
                        type:'save',                     
                    }, function(response) {
                        /* $('#successPay').addClass('d-none'); */
                                            
                        if (response == 200) {                        
                            $this.message = "Completed successfullly ";
                            $this.alertType ="alert-success";                            
                            $this.isloading = false,
                            $this.stock_items[index] = itemsx;
                            elt.prev().removeClass('d-none')
                            elt.addClass('d-none')     
                            elt.parent().parent().find('span').show();
                            elt.parent().parent().find('.edx').addClass('d-none');                    
                            $this.resetTable();              
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