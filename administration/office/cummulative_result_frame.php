<?php session_start(); ?>
<?php
if (isset($_SESSION['staff_info_id'])) {
} else {
    header('location:../');
}
include_once('../php/connection.php');
$sql = "SELECT * FROM classes as c INNER JOIN school_section as s ON s.school_section_id= c.school_section_id;";
$run = $conn->query($sql);
$classes = [];
if ($run->num_rows > 0) {
    while ($row = $run->fetch_assoc()) {
        $classes[] = $row;
    }
}
$run = $conn->query("SELECT * FROM session");
$sessions = [];
if ($run->num_rows > 0) {
    while ($row = $run->fetch_assoc()) {
        $sessions[] = $row;
    }
}

$run = $conn->query("SELECT * FROM term");
$terms = [];
if ($run->num_rows > 0) {
    while ($row = $run->fetch_assoc()) {
        $terms[] = $row;
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title> Countinuous Assessment </title>

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
    <!-- <script type="text/javascript" src="../js/datatable/buttons.html5.min.js"></script> -->
    
    <!-- <script type="text/javascript" src="../js/datatable/datatables.min.js"></script> -->
    <script type="text/javascript" src="../js/datatable/datatable_excel.js"></script>
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

        .no-visible {
            visibility: hidden;
        }

        .d-none {
            display: none;
        }

        .modal-backdrop {
            background-color: transparent;
        }

        th {
            color: #777;
        }

        .side-table tbody tr td {
            border: none !important;
            white-space: nowrap;
            word-break: keep-all;
        }

        .activexl {
            background: #2265a0;
            color: white !important;
        }

        .table-result thead tr th,
        .table-result tbody tr td {
            font-size: 0.8em;
        }

        .table-result tbody tr td {
            color: #333;
            font-weight: 600;
            padding: 3px;
        }
    </style>


</head>

<body style="background:#fff">


    <div id="app" class="pl-3" >
        <div id="keyloader" v-if="isloading" class="d-flex" style="justify-content:center; flex-wrap:wrap;align-items:center;width:100%;height:90%; position:absolute; left:0;z-index:132000; ">
            <div class="spinner-grow text-info" style="font-size: 7em;" role="status" key="loader">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <input type="text" style="display: none;" id="forIframeLoaded" :value="forIframeLoaded">
        <!-- <i class="text-secondary"><b> CUMMLATIVE RESULT </b></i>  -->
        <div class="w-100 no-visible" id="app-content">
            <center>
                <div style="width:300px" class="d-flex">
                    <select name="" id="term_id" v-model="term_id" class="form-control w-50 mx-2">
                        <option value="">SELECT TERM</option>
                        <option v-for="term in terms" :value="term.id">{{term.description}}</option>
                    </select>
                    <select name="" id="session_id" v-model="session_id" class="form-control w-50 mx-2">
                        <option value="">SELECT SESSION</option>
                        <option v-for="session in sessions" :value="session.section_id">{{session.section}}</option>
                    </select>
                </div>
            </center>
            <div class="row w-100">
                <div class="col-lg-2 col-md-2 p-0">
                    <table class="table table-hover mt-5 side-table w-100" style="border: 1px solid #ccc;border-radius; user-select:none;">
                        <thead class="d-none">
                            <th class="no-visible">Class</th>
                        </thead>
                        <tbody>
                            <tr v-for="clas in classes" @click="applySelect($event, clas.class_id, clas.class_name)" class="bookx">
                                <td class="py-2  px-4 booxinn">
                                    <div style="border-bottom: 1px solid #ccc;padding:8px 0px;pointer-events:none;"><img src="../images/book-min.png"> {{clas.class_name}} </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-10 col-md-10 pt-5 " style="overflow: hidden;height:100%;">
                    <div class="d-flex">
                        <div style="" id="printPdf"></div>
                        <p class="mx-5"><b>{{classname}}</b></p>
                    </div>
                    <div class="w-100 spreadSheet p-3" style="overflow: scroll;height:78vh;">
                        <table v-show="!isloading" class="table table-hover table-result mt-2 table-bordered">
                            <thead>
                                <th width="6%">Adm</th>
                                <th width="40%">Name</th>
                                <th width="2%" v-for="subject in subjects">{{subject.subject_code}}</th>
                                <th width="2%">Total Cousr.</th>
                                <th width="4%">Total</th>
                                <th width="2%"> Avg.</th>
                                <th width="2%">Position</th>
                            </thead>
                            <tbody>
                                <tr v-for="as in assessments">
                                    <td>{{as.adm_no}}</td>
                                    <td>{{as.fullname}}</td>
                                    <td v-for="sth in subjects">{{getScore(as.subjects,sth.id, 'total')}}</td>
                                    <td>{{as.subjects.length}}</td>

                                    <td v-if="term_id==3">{{as.total3}}</td>
                                    <td v-else>{{as.total}}</td>

                                    <td v-if="term_id==3">{{Number(as.total_avg3).toFixed(1)}}</td>
                                    <td v-else>{{Number(as.total_avg).toFixed(1)}}</td>

                                    <td v-if="term_id==3">{{giveRank(as.total3,'total3')}}</td>
                                    <td v-else>{{giveRank(as.total,'total')}}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        $(document).ready(function() {

            $('.side-table').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    /* 'copy', 'excel', */
                    'pdf'
                ],
                pageLength: 7,
                bInfo: false,
            });


            /*  */
            $('.dt-buttons').hide();
            $('#DataTables_Table_0_filter').find('input').css('width', '71%');
            $('#DataTables_Table_0_filter').find('input').addClass('mt-3');
            setTimeout(() => {}, 10);
            $('#keyloader').hide();
        })

        var vm = new Vue({
            el: "#app",
            data: {
                forIframeLoaded:200,
                sessions: <?= json_encode($sessions); ?>,
                terms: <?= json_encode($terms); ?>,
                classes: <?= json_encode($classes); ?>,
                isloading: true,
                classname: "",
                term_id: "",
                session_id: "",
                message: "Paying for ...",
                alertType: "alert-light",
                assessments: [],
                subjects: [],
                table: "",

            },
            methods: {
                init_Table() {
                    this.table = $('.table-result').DataTable({
                        responsive: false,
                        dom: 'Bfrtip',
                        buttons: [                            
                            /* 'csv', */
                            /* 'pdf', */                            
                            {
                                extend: 'excelHtml5',
                                text: 'Export Excel',   
                                sheetName: this.classname+'-'+$('#session_id option:selected').text()+'-'+$('#term_id option:selected').text(),

                            }
                        ],
                        pageLength: 200,
                        columnDefs: [{
                            width: 250,
                            targets: 1
                        }],
                    });
                    $('#DataTables_Table_1_wrapper .buttons-excel').detach().appendTo('#printPdf');
                    $('#DataTables_Table_1_wrapper .buttons-csv').detach().appendTo('#printPdf');

                },
                applySelect(e, class_id, classnamex) {

                    let elt = $(e.target);
                    if (this.term_id == "") {
                        alert('please select term')
                        return 0;
                    }
                    if (this.session_id == "") {
                        alert('please select term')
                        return 0;
                    }

                    elt.parent().find('tr').removeClass('activexl')
                    $('.booxinn').removeClass('activexl');

                    elt.addClass('activexl');
                    let $this = this;
                    $this.isloading = true;

                    $.post("../php/cummulative_data.php", {
                        session_id: this.session_id,
                        term_id: this.term_id,
                        class_id: class_id,
                    }, function(response) {
                        if (JSON.parse(JSON.parse(response)[0]).length < 1) {
                            alert('no result found');
                            $this.isloading = false
                            return 0;
                        }
                        $this.table.destroy();
                        $this.assessments = JSON.parse(JSON.parse(response)[0]);
                        $this.subjects = JSON.parse(JSON.parse(response)[1]);
                        /* console.log($this.assessments);
                        console.log($this.subjects); */
                        setTimeout(() => {
                            $this.isloading = false
                            $this.classname = classnamex;
                            $this.init_Table()
                            $('title').text('CONTINUOUS ASSESSMENT SHEET - ' + $this.classname);
                        }, 1000);
                        setTimeout(() => {

                        }, 1000);
                    });

                },
                giveRank(value, type) {
                    //value is the score
                    // declaring and initilising variables
                    let rank = 1;
                    prev_rank = rank;
                    position = 0;
                    var arrn = [];
                    this.assessments.forEach((item, i) => {
                        arrn[i] = item[type];
                    });

                    function onlyUnique(value, index, self) {
                        return self.indexOf(value) === index;
                    }
                    var unique = arrn.filter(onlyUnique);

                    unique.sort(function(a, b) {
                        return b - a
                    });
                    position = unique.indexOf(value);

                    function ordinal_suffix_of(i) {
                        i += 1;
                        var j = i % 10,
                            k = i % 100;
                        if (j == 1 && k != 11) {
                            return i + "st";
                        }
                        if (j == 2 && k != 12) {
                            return i + "nd";
                        }
                        if (j == 3 && k != 13) {
                            return i + "rd";
                        }
                        return i + "th";
                    }
                    return ordinal_suffix_of(position);
                },
                getScore(obj, id, type) {
                    let subject = "";
                    let $this = this;
                    obj.forEach((item) => {
                        if (item.subject_id == id) {
                            if (type == 'total') {
                                if ($this.term_id != 3) {
                                    subject = item.total
                                } else {
                                    subject = item.total3
                                }
                            }
                            if (type == 'grade') {
                                if ($this.term_id != 3) {
                                    subject = item.grade
                                } else {
                                    subject = item.grade3
                                }
                            }
                            if (type == 'position') {
                                if ($this.term_id != 3) {
                                    subject = item.position
                                } else {
                                    subject = item.position3
                                }
                            }
                        }
                    });
                    return subject; //score of the subject
                }
            },
            created() {
                //console.log(this.paymentTypes)          
            },
            mounted() {
                var $this = this;
                $('#app-content').removeClass('no-visible');
                $(document).ready(function() {
                    setTimeout(() => {
                        $this.init_Table();
                    }, 500);
                    $this.isloading = false;
                })
            }
        })
    </script>

</body>

</html>