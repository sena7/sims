<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <link rel="contents" href="resource/library/reference.html">
        <link rel="shortcut icon" href="">
        <link rel="stylesheet" type="text/css" href="public_html/css/system_config.css">  


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
        <script src="public_html/js/models.js"></script>
        <script src="public_html/js/json.js"></script>
        <script src="public_html/js/storage.js"></script>

        <script src="public_html/js/system_config.js"></script> 
        <script type="text/javascript">

            function deleteRow(tableId, rowId) {
                var table = document.getElementById(tableId);
                table.deleteRow(0);
            }
            
            function removeRow(tableId, index) {
    var table = document.getElementById(tableId);
    console.log("triggered ? ");
    table.deleteRow(index);
    var removed = fileArray.pop(index);
    console.log(removed);
    //then rearrange.
  
}
        </script>

        <style>
            input[type="button"], input[type="submit"], button{
                background-color: #4CAF50; /* Green */
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

        </style>
    </head>
    <body>
        <?php
        //session_start();

        $imgs = array();
        $target_dir = "public_html/img/test/";
        $target = $target_dir . $_FILES['files']['name'];


        $order = "1";
        $files = ($_FILES['user_files']['name']);
        $name = "test";
        $visible = "Y";
        $sqlInsertImage = "INSERT INTO 'image'('id', 'order', 'name', 'file', 'visible') VALUES (null,'$order','$files','$name','$visible')";
        ?>
        m
        <div id="container">
            <div id="config" style=""><input style="width:20%; max-width: 40px;outline:none;" type="image" src="https://s19.postimg.org/llh8rg9j7/close.png" alt="config"/></div>
            <div id="configContents">
                <h2>Configuration</h2>
                <input id="testButton" type="button" value="test1" onclick="getJsonObjFromTable('tb_dates', 'dates', 'input');"/>
                <form id="configForm" onsubmit="" action="system_config.php" name="f1"  method="post" enctype="multipart/form-data">
                    <fieldset>
                        <legend>Dates</legend>
                        <table id="tb_dates">
                        </table>
                    </fieldset>

                    <fieldset>
                        <legend>Category</legend>
                        <table id = "tb_category">
                        </table>
                    </fieldset>

                    <fieldset>
                        <legend>Sharepoint folder path</legend>
                        <table id="tb_sharepoint">

                        </table>


                    </fieldset>



                    <input id="configFormSubmit" type="button" value="save">
                </form>
                <!--<form action="system_config.php" name="f2"  method="post" enctype="multipart/form-data">-->
                    <fieldset>
                        <legend>Images</legend>


                        <input type="file" id="input_files" name="user_files[]" multiple="multiple" />
                        <!--<output id="list"></output>-->
                        <table id="tb_selectedFiles"></table>
                        <input id="f2_submit" type="submit" value="save" name="submit" style="display: none;"/>
                     
                   
                    </fieldset>

                </form>

                <table id="tb_images">
                    <thead><tr><th>head</th></tr></thead>
                    <tbody>
                        <tr id="a"><td>a</td></tr>
                        <tr id="b"><td>b</td></tr>
                        <tr id="c"><td>c</td></tr>
                        <tr id="d"><td>d</td></tr>
                    </tbody>
                    <button onclick="deleteRow('tb_images',null)">delete</button>
                </table>
            </div>


        </div>
    </body>
</html>
