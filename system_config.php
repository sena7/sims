<!DOCTYPE html>

<?php

class date {

    public $id;
    public $name;
    public $date;
    public $category;
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
    public $show_past_times;
    public $time_type;

}

require('config.php');
$selectDateSql = "select a.id, b.name category, a.name, a.date, a.visible from date a left join date_category b on b.id = a.category_id order by a.date asc ";
$selectDateSqlStmt = $pdo->prepare($selectDateSql);
$selectDateSqlStmt->execute();
$selectDateResult = $selectDateSqlStmt->fetchAll(PDO::FETCH_CLASS, "date");


$selectCatSql = "select id, name, colour from date_category";
$selectCatSqlStmt = $pdo->prepare($selectCatSql);
$selectCatSqlStmt->execute();
$selectCatResult = $selectCatSqlStmt->fetchAll(PDO::FETCH_CLASS, "dateCategory");

$selectSysConfigSql = "select num_timer, slide_show_sec, timer_show_sec, show_past_times, time_type from system_config where id=(select max(id) from system_config) LIMIT 1";
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

if (isset($_POST['action'])) {
//post
// delete 1 row
    if ($_POST['action'] == 'deleteRecord') {

        $sql = "";
        if ($_POST['record'] == 'date') {
            $sql = "delete from date where id=:id";
        } else if ($_POST['record'] == 'date_category') {
            $sql = "delete from date_category where id=:id";
        } else if ($_POST['record'] == 'image') {
            $sql = "delete from image where id=:id";
        } else {
            
        }

        $query = $pdo->prepare($sql);
        // $query->bindParam(':record', $_POST['record'], PDO::PARAM_STR);
        $query->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        $query->execute();
    }
}

//if (isset($_POST['saveDate'])) {
//
//
//    $sql = "update date SET id=:id, category_id = :category_id, name = :name, date = :date, visible =:visible where id=:id";
//    // (select max(order_num) from image) + :order_num
//
//    $query->bindParam(':category_id', $_POST['saveDate']['category_id'], PDO::PARAM_STR);
//    $query->bindParam(':name', $_POST['saveDate']['name'], PDO::PARAM_STR);
//    $query->bindParam(':date', $_POST['saveDate']['date'], PDO::PARAM_STR);
//    $query->bindParam(':visible', $_POST['saveDate']['visible'], PDO::PARAM_INT);
//    $query->bindParam(':id', $_POST['saveDate']['id'], PDO::PARAM_INT);
//    $query = $pdo->prepare($sql);
//    $query->execute();
//}

if (isset($_FILES['user_files'])) {
    $files = $_FILES['user_files'];
    //echo print_r($files);
    $sql = "insert into image (id, order_num, name, file, visible) values ( null, 4, 'test', :file, 1)";
    if (isset($_FILES['user_files']['tmp_name'])) {
        echo var_dump(count($_FILES['user_files']));
         $cnt = count($_FILES['user_files']);
        for ($i = 0; $i < cnt; $i ++) {
            $fp = fopen($_FILES['user_files']['tmp_name'][$i], 'rb');

            $query = $pdo->prepare($sql);
            $query->bindParam(':file', $fp, PDO::PARAM_LOB);
            $query->execute();
        }
    }
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

                images = <?php echo json_encode($result); ?>;
                console.log(images);
                misc = <?php echo json_encode($config); ?>;
                console.log(misc);
                if (dates) {
                    setTableWithArray("tb_dates", dates);
                }
                if (dateCategories) {
                    setTableWithArray("tb_category", dateCategories);
                }
                if (images) {
                    setTableWithArray("tb_images", images);
                }
                if (misc) {
                    setTableWithObject("tb_misc", misc[0]);
                }

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

                var dateFormTest = document.getElementById("dateForm");
                dateFormTest.addEventListener('submit', function () {
                    viewFormData(dateFormTest);
                });

                function viewFormData(dom) {
                    var form = new FormData(dom);
                    alert(form.get('action'));
                }



            });

            function confirmRemove() {

            }

            function removeRowList() {

            }

            function insertRow() {

            }

            function redirectToHome()
            {
                window.location = 'index.php';
            }




        </script>

        <style>
            body{
                font-family: "Tw Cen MT", Monospace, 'Sans-serif';
            }
            input[type="button"], input[type="submit"], button{
                background-color: #ffffff;
                border: 2px solid;
                border-color: #bba5e7;
                color: #666666;
                padding: 10px 15px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                font-weight: bold;
                font-family: inherit;
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
            div .updateBtn{

            }
            div .buttons div{
                float:left;
                margin-top: 10px;
                margin-right: 10px;

            }

        </style>
    </head>
    <body>
        <div id="container">
            <div><img src="public_html/img/content/left_arrow.png" width="50" onclick="redirectToHome();" style="cursor:pointer;"/></div>
            <h2>Configuration</h2>
            <div id="configContents" class="accordion">
                <!---->
                <!--<input id="testButton" type="button" value="test1" onclick="getJsonObjFromTable('tb_dates', 'dates', 'input');"/>-->
                <!--<form id="configForm" onsubmit="" action="system_config.php" name="f1"  method="post" enctype="multipart/form-data">-->
                <h3>Dates<h3>
                        <div>
                            <!--                    <fieldset>
                                                    
                                                    
                                                    <legend>Dates</legend>-->
                            <form id="dateForm" action="" method="post" enctype="text/plain" name="" action="system_config.php" >
                                <div><table id="tb_dates" data-dbrecord="date">
<!--                                        <tr><td><input name="id" value=""></td>
                                            <td><input name="name" value=""></td>
                                            <td><input name="date" value=""></td>
                                            <td><input name="date_category" value=""></td>
                                            <td><input name="visible" value=""></td>
                                            <td><input name="" value=""></td></tr>-->
                                    </table>
                                </div>
                                <div class="buttons">
                                    <div><input type="button" value="Add New" onclick ="addRow('tb_dates', dates);" /></div>
                                    <div class="updateBtn">
                                        <input id="updatedate" type="submit" value="Save" data-submit-type="update" name="saveDate" style="display: none;" />
    <!--                                    <input type="button" value="Reset" style="display:;float:left;margin-right:10px;"/>-->
                                    </div>
                                </div>

                            </form>                       

                        </div>
                        <h3>Category</h3>
                        <div>
                            <form id="categoryForm" action="saveCategory" method="post" enctype="text/plain">
                                <div>
                                    <table id = "tb_category" data-dbrecord="date_category">
                                    </table>
                                   
                                </div>
                                <div class="buttons">
                                     <div><input type="button" value="Add New" onclick ="addRow('tb_category', dateCategories);" /></div>
                                     <div><input id="updatedate_category" type="submit" value="Save" data-submit-type="update" name="categoryUpdateSubmit" style="display: none;float:left;"/></div>
                                </div>
                            </form>    


                        </div>

                        <h3>Misc</h3>
                        <div>
                            <form>
                            <div><table id = "tb_misc" data-dbrecord="system_config">
                                </table>
                            </div>
                                <div class="buttons">
                                     <div><input id="updateConfig" type="submit" value="Save" data-submit-type="update" name="categoryUpdateSubmit" style="display: none;float:left;"/></div>
                                </div>
                            </form>
                        </div>

                        <h3>Images</h3>
                        <div>

                            <div>
                                <fieldset>
                                    <legend>Upload Images</legend>
                                    <div>
                                        <form id="imageUploadForm" action="saveImage" method="post" enctype="multipart/form-data">
                                            <input type="file" id="input_files" name="user_files[]" multiple="multiple" style="padding: 20px;float:left;"/>

                                        </form>
                                    </div>
                                    <div><input id="saveImage" type="submit" value="upload" name="imageUploadSubmit" style="display: none;float:left;" /></div>
                                    <div>

                                        <table id="tb_selectedFiles" ></table>

                                    </div>


                                </fieldset>
                            </div>
                            <fieldset>
                                <legend>Saved Images</legend>
                                <div>
                                    <form id="imageForm" action="saveImage" method="post" enctype="text/plain">
                                        <div><table id="tb_images" data-dbrecord="image">
                                            </table>
                                        </div>
                                        <div class="updateBtn"><input id="updateimage" type="submit" value="Save" data-submit-type="update" name="imageUpdateSubmit" style="display: none;float:left;"/>
                                        </div>
                                    </form>    

                                </div>
                            </fieldset>
                        </div>

                        <h3>Test</h3>
                        <div>
                            <form>
                                <table>
                                    <thead><tr><td>Name</td><td>Date</td><td>Visible</td>
                                           <td>Category</td>
                                            <td>delete</td></tr></thead>
                                    <tbody>
                                        <?php
                                        foreach ($selectDateResult as $date) {
                                            echo "<tr>";
                                            echo "<td><input type='text' value = '$date->name' style='font-family:inherit;'/ ></td>";
                                            echo "<td><input type='text' value = '$date->date' style='font-family:inherit;'/></td>";
                                            echo "<td><input type='text' value = '$date->visible' style='font-family:inherit;'/></td>";
                                           echo "<td><input type='text' value = '$date->category' style='font-family:inherit;'/></td>";
                                            echo "<td><input type='button' value ='delete' style='font-family:inherit;'></td>";
                                            echo "<tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                        <h3>Form Test</h3>
                        <div>
                            <form>
                                <table>
                                    <thead>
                                        <tr><td>Anchor</td></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td><a herf="a.com?id='1'" >A</a></td></tr>
                                        <tr><td><a herf="a.com?id='1'" >B</a></td></tr>
                                        <tr><td><a herf="a.com?id='1'" >C</a></td></tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                        </body>
                        </html>


                        <?php
//if ($_POST['action'] == 'saveImage') {
//    $sql = "insert into image (id, order_num, name, file, visible) values ( null, null, 'test', :file, 1)";
//    // (select max(order_num) from image) + :order_num
//    $data = $_POST['data'];
//    echo '<script>console.log("hi");</script>';
//    foreach ($data as $d) {
//        $query->bindParam(':file', $d, PDO::PARAM_LOB);
//        $query = $pdo->prepare($sql);
//        $query->execute();             
//    }
//}
                       
                        
                        ?>