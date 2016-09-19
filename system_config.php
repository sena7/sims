<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php

class date {

    public $id;
    public $name;
    public $date;
    public $category_id;
    public $visible;

}

class dateCategory {

    public $id;
    public $name;
    public $colour;

}

class image {

    public $id;
    public $order_num;
    public $file;
    public $name;
    public $visible;

}

class config {

    public $num_timer;
    public $slide_show_sec;
    public $timer_show_sec;

}

require('config.php');
$selectDateSql = "select id, category_id, name, date, visible from date order by date asc ";
$selectDateSqlStmt = $pdo->prepare($selectDateSql);
$selectDateSqlStmt->execute();
$selectDateResult = $selectDateSqlStmt->fetchAll(PDO::FETCH_CLASS, "date");


$selectCatSql = "select id, name, colour from date_category";
$selectCatSqlStmt = $pdo->prepare($selectCatSql);
$selectCatSqlStmt->execute();
$selectCatResult = $selectCatSqlStmt->fetchAll(PDO::FETCH_CLASS, "dateCategory");

$selectSysConfigSql = "select num_timer, slide_show_sec, timer_show_sec from system_config where id=(select max(id) from system_config) LIMIT 1";
$selectSysConfigSqlStmt = $pdo->prepare($selectSysConfigSql);
$selectSysConfigSqlStmt->execute();
$config = $selectSysConfigSqlStmt->fetchAll(PDO::FETCH_CLASS, "config");


//$image_id = 2;
//$sql = "SELECT file FROM image WHERE id=:id";
$sql = "SELECT id, order_num, name, file, visible FROM image";
$query = $pdo->prepare($sql);
//$query->execute(array(':id' => $image_id));
$query->execute();
//$query->bindColumn(1, $name, PDO::PARAM_STR);
//$query->bindColumn(2, $image, PDO::PARAM_LOB);
//$result = $query->fetchAll(PDO::FETCH_BOUND);
//$result = $query->fetchAll(PDO::FETCH_ASSOC);
$result = $query->fetchAll(PDO::FETCH_CLASS, 'image');
foreach ($result as $row) {
    $row->file = base64_encode($row->file);
}


//post
if($_POST['action'] == 'removeRow'){
    print_r("?");
    $sql = "";
    if($_POST['record'] == 'date'){
        $sql = "delete from date where id=:id";
    }else if($_POST['record'] == 'date_category'){
        $sql = "delete from date_category where id=:id";
    }else if($_POST['record'] == 'image'){
        $sql = "delete from image where id=:id";
    }else{
        
    }
    
       $query = $pdo->prepare($sql);
       // $query->bindParam(':record', $_POST['record'], PDO::PARAM_STR);
        $query->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        $query->execute();
        
      
}

?>
<html>
    <head>
        <link rel="contents" href="resource/library/reference.html">
        <link rel="shortcut icon" href="">
        <link rel="stylesheet" type="text/css" href="public_html/css/system_config.css">  

        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">

        <!--   Data Table    -->
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.css">

        <!-- Date Picker -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
        <title>Configuration</title>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

        <!--   Data Table    -->
        <script type="text/javascript" charset="utf-8" src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf-8" src="//cdn.datatables.net/rowreorder/1.1.2/js/dataTables.rowReorder.min.js"></script>
        <!-- Date Picker -->
        <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>


        <!-- color picker -->
        <script src="resources/library/jscolor.js"></script>

        <!-- flatpickr -->
        <link rel="stylesheet" type="text/css" href="public_html/css/flatpickr.min.css">
        <script src="resources/library/flatpickr.min.js"></script>

        <script src="public_html/js/models.js"></script>
        <script src="public_html/js/json.js"></script>
        <script src="public_html/js/storage.js"></script>
        <script src="public_html/js/system_config.js"></script> 



        <script type="text/javascript">
            //Global variables for data retrieval
            var dates;
            var dateCategories;
            var images;
            var misc;

            $(document).ready(function () {
                // check if the current user's browser supports File APIs
                if (window.File && window.FileReader && window.FileList && window.Blob) {
                    console.log("All the File APIs supported");
                } else {
                    alert('The File APIs are not fully supported in this browser.');
                }
                // data retrieved
                dates = <?php echo json_encode($selectDateResult); ?>;
                dateCategories = <?php echo json_encode($selectCatResult); ?>;
                console.log(Object.keys(dates[0])[0]);
                images = <?php echo json_encode($result); ?>;
                console.log(images);
                misc = <?php echo json_encode($config); ?>;
                console.log(misc);
                setTableWithArray("tb_dates", dates);
                setTableWithArray("tb_category", dateCategories);
                setTableWithArray("tb_images", images);
                setTableWithObject("tb_misc", misc[0]);

                $(".datepicker").datepicker(
                        {
                            dateFormat: 'dd/mm/yy'
                        });
                $(".flatpickr").flatpickr(
                        {dateFormat: 'd/m/Y H:i:S'}
                );
                $(".accordion")
                        .accordion({
                            heightStyle: "content"
                        });
                $('#tb_dates').DataTable({
                    paging: true,
                    pagingType: 'numbers',
                    searching: false,
                    ordering: true,
                    columnDefs: [
                        {"targets": [0],
                            "visible": false}
                    ]
                });
                $('#tb_category').DataTable({
                    paging: false,
                    searching: false,
                    ordering: true,
                    columnDefs: [
                        {"targets": [0],
                            "visible": false}
                    ]
                });
                $('#tb_images').DataTable({
                    paging: true,
                    searching: false,
                    pagingType: 'numbers',
                    ordering: true,
                    columnDefs: [
                        {"targets": [0],
                            "visible": false}
                    ]
                });

            });

            function confirmRemove(){
                
            }

            function removeRow(rowElement, tableDataDbrecord) {
                 console.log(rowElement.dataset.id);
                 rowElement.parentElement.removeChild(rowElement);
                
                $.ajax({
                    type:"POST", 
                    url: '\system_config.php',
                    data: {action: 'removeRow',
                           record: tableDataDbrecord, 
                           id: rowElement.dataset.id},
                    success: function(data){
                        alert(data);
                    }
                });
            }
            
            function removeRowList(){
                
            }

            function redirectToHome()
            {
                window.location = 'index.php';
            }
            
            function saveImages(){
                
            }


        </script>

        <style>
            body{
                font-family: "Tw Cen MT", Monospace, 'Sans-serif';
            }
            input[type="button"], input[type="submit"], button{
                background-color: #dbe7d5; /* Green */
                border: none;
                color: white;
                padding: 10px 15px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                border-radius: 5px;
                -webkit-border-radius: 5px;

            }
            .accordion {
                font-family: "Tw Cen MT", Monospace, 'Sans-serif';
            }
            .accordion .ui-accordion-content{
                font-family: "Tw Cen MT", Monospace, 'Sans-serif';
            }
            .ui-accordion-header {
                background-color: #6dbbbb; 
                font-size: 35px;
                font-weight: bold;
                /*font-family: "Tw Cen MT", Monospace, 'Sans-serif';*/
            }
            #configContents{
                font-family: "Tw Cen MT", Monospace, 'Sans-serif';
            }
            .slide_image:hover {
                height: 300px;
            }
            h3{
                font-weight: 200;
            }
            .dataTables_paginate.paging_numbers {

            }
            .paging_numbers{
                border-style: none;
            }
            #tb_dates_paginate {

            }
            .paginate_button.current {

            }

            .ui-accordion-header-active {
                border-style: none;
            }
            legend {
                font-weight: bold;
            }
            .accordion div table{
                text-align: left;
                margin-left: 0; 
            }
            .dataTables_wrapper{
                text-align:left;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <div><img src="public_html/img/content/left_arrow.png" width="50" onclick="redirectToHome();" /></div>
            <h2>Configuration</h2>
            <div id="configContents" class="accordion">
                <!---->
                <!--<input id="testButton" type="button" value="test1" onclick="getJsonObjFromTable('tb_dates', 'dates', 'input');"/>-->
                <!--<form id="configForm" onsubmit="" action="system_config.php" name="f1"  method="post" enctype="multipart/form-data">-->
                <h3>Dates<h3>
                        <div>
                            <!--                    <fieldset>
                                                    
                                                    
                                                    <legend>Dates</legend>-->
                            <form>
                                <table id="tb_dates" data-dbrecord="date">
                                </table>
                            </form>                       
                            <!--</fieldset>-->
                        </div>
                        <h3>Category</h3>
                        <div>
                            <!--                    <fieldset>
                                                    <legend>Category</legend>-->
                            <table id = "tb_category" data-dbrecord="date_category">
                            </table>
                            <!--</fieldset>-->

                        </div>

                        <h3>Misc</h3>
                        <div>
                            <!--                    <fieldset>
                                                    <legend>Category</legend>-->
                            <table id = "tb_misc" data-dbrecord="system_config">
                            </table>
                            <!--</fieldset>-->

                        </div>

                    <!--<input id="configFormSubmit" type="button" value="save">-->
                        <!--</form>-->
                        <!--<form action="system_config.php" name="f2"  method="post" enctype="multipart/form-data">-->

                        <h3>Images</h3>
                        <div>

                            <div>
                                <fieldset>
                                    <legend>Upload Images</legend>
                                    <div style="position: relative;">
                                        <!--<form action="system_config.php" method="post">-->
                                        <input type="file" id="input_files" name="user_files[]" multiple="multiple" style="padding: 20px;float:left;"/>
                                        <input id="upload" type="submit" value="upload" name="uploadImagesSubmit" onclick = "" style="display: none;float:left;"/>
                                        <!--</form>-->
                                    </div>
                                    <table id="tb_selectedFiles" ></table>

                                </fieldset>
                            </div>
                            <fieldset>
                                <legend>Saved Images</legend>
                                <div>
                                <table id="tb_images" data-dbrecord="image">
                                </table>
                                </div>
                            </fieldset>
                        </div>

                        </div>
                        </body>
                        </html>
