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

        <!-- Date Picker -->
        <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>


        <!-- color picker -->
        <script src="resources/library/jscolor.js"></script>
        <script src="public_html/js/models.js"></script>
        <script src="public_html/js/json.js"></script>
        <script src="public_html/js/storage.js"></script>
        

        <script type="text/javascript">
            $(document).ready(function () {
                /* Initialisation
                 * Read Configuration XML file
                 * Pass the values to the function for time caculation. 
                 * Present the data in a form for view and change
                 *   */
                
                // Configuration instance
                //var config = new Configuration(false);

                //Save configuration data to localStorage
                $.getJSON('resources/config.json', function (data) {
                    $.each(data, function (key, val) {

                        // Store configuration json data 
                        // Store it to localStorage if it is supported. 
                        if (localStorage) {
                            localStorage.setItem(key, JSON.stringify(val));
                        }
                        // Store somewhere else if it is not supported
                        else {

                        }


                    });
                   // console.log(localStorage);
                });

                //set inner html values according to config data
                var categoriesJson = JSON.parse(localStorage.getItem("category"));
                setTableWithJsonArray("tb_category", categoriesJson);

                var spPathJson = JSON.parse(localStorage.getItem("sharepoint"));
                setTableWithJsonArray("tb_sharepoint", spPathJson);
                var duedatesJson = JSON.parse(localStorage.getItem("duedate"));
                setTableWithJsonArray("tb_dates", duedatesJson);



                /* Data Table config*/
//                $('#tb_dates').DataTable({
//                    paging: false,
//                    searching: false,
//                    ordering: true
//                });
//                 $('#tb_category').DataTable({
//                    paging: false,
//                    searching: false,
//                    ordering: true
//                });

                /*Date Picker config*/
                $(".datepicker").datepicker(
                        {
                            dateFormat: 'dd/mm/yy'

                        });

                addRowSequence("tb_dates");
            });


            /*Functions*/


            function addRowSequence(table_id) {
//                var table = document.getElementById(table_id);
//                var rows = table.rows;
//                var numRows = table.rows.length;
//                console.log(numRows);
//                for(i=0;i<numRows;i++){
//                  console.log(rows.item(i).column[0]);
//                  
//                }

            }

            function setTableWithJsonArray(tableId, jsonArray) {

                var table = document.getElementById(tableId);
                var numOfRows = jsonArray.length;
                var keys = [];
                // get String values of the jsonArray keys
                for (var obj in jsonArray) {
                    if (jsonArray.hasOwnProperty(obj)) {
                        for (var prop in jsonArray[obj]) {
                            // if the key string value is not in array 'keys'
                            if (keys.indexOf(prop.toString()) === -1) {
                                // store into 'keys' array    
                                keys.push(prop.toString());

                            }
                        }
                    }
                }


                // create header
                var thead = document.createElement("THEAD");
                var tr = document.createElement("TR");
                for (i = 0; i < keys.length; i++) {
                    var td = document.createElement("TH");
                    td.innerHTML = keys[i];
                    tr.appendChild(td);
                }
                thead.appendChild(tr);
                table.appendChild(thead);

                var lengthKeys = keys.length;
                var tbody = document.createElement("TBODY");
                for (i = 0; i < numOfRows; i++) {
                    var tr = document.createElement("TR");
                    for (j = 0; j < lengthKeys; j++) {
                        var td = document.createElement("TD");
                        var input = document.createElement("INPUT");
                        input.setAttribute("class", "fontfamily");// it would not inherit the body font unless assigned to classes with the certain styles
                        // set name of input by the json key
                        input.setAttribute("name", keys[j]);
                         

                        var value = eval('jsonArray[' + i + '].' + keys[j]);

                        // set value of input by the json value
                        input.setAttribute("value", value);

                        // set class of input 
                        if (keys[j] === "colour") { // if the key name is colour
                            input.className += " jscolor"; // set class to jscolor.js colour picker // be aware of the space before the appending class name
                        }

                        // set type and special attributes according to the characteristic of the value
                        // if value is Y or N then 
                        if (value === "Y" || value === "N") {
                            input.setAttribute("type", "checkbox");
                            if (value === "Y") {
                                input.setAttribute("checked", true);
                            }
                        } else {
                            input.setAttribute("type", "text");
                        }
                        if (keys[j] === "date") {
                            input.className += " datepicker"; // be aware of the space before the appending class name
                            // input.setAttribute("data-date-format", "dd/mm/yyyy");
                        }

                        //append input to td, td to tr
                        td.appendChild(input);
                        tr.appendChild(td);
                        tbody.appendChild(tr);
                    }
                    table.appendChild(tbody);
                }
            }

            function getJsonObjFromTable(tableId, jsonKey, valueContainerName) {

                // table rows array. index 0 = header, index = 1  
                var rows = document.getElementById(tableId).getElementsByTagName('tr');
                console.log(rows);
                var keys = [];
                var headers = rows[0].getElementsByTagName('th');
                console.log(headers);
                for (i = 0; i < headers.length; i++) {
                    keys.push(headers[i].textContent);

                }
                console.log(keys);

                // jquery   
//                var keys = [];
//                $('#' + tableId + '>thead>tr>td').each(function () {
//                    console.log($(this).text());
//                    keys.push($(this).text());
//                });

                // create a json object
                var jsonObj = {};
                var jsonValue = [];
                for (i = 1; i < rows.length; i++) {
                    // i=0 ; row containing headers. 
                    var values = rows[i].getElementsByTagName(valueContainerName); // hmmmmm
                    //var obj = getModel(valueContainerName);
                    var jsonArrayText = "{";
                    for (j = 0; j < values.length; j++) {
                        jsonArrayText += "\""+ keys[j] +"\"" + ":" + "\"" + values[j].value +"\"";
                        jsonArrayText += j===values.length-1?"}":",";
                    }
             
                    jsonValue.push(jsonArrayText);
                    
                }
                
                jsonObj = JSON.parse("{" + "\"" + jsonKey + "\"" + ":" + "[" +jsonValue + "]}");
                
                console.log(jsonObj);
                console.log(JSON.stringify(jsonObj));
                return jsonObj;
            }

            function save() {
                var data = $("#configForm").serializeArray();

                console.log(data);


//                var url = "config/config.json"; // 
//
//                $.ajax({
//                    type: "POST",
//                    url: url,
//                    data: , // serializes the form's elements.
//                    success: function (data)
//                    {
//                        alert(data); // show response from the php script.
//                    }
//                });
//
//                e.preventDefault(); // avoid to execute the actual submit of the form.
            }

//            function saveConfigFile() {
//                var fso = new ActiveXObject("Scripting.FileSystemObject");
//                var s = fso.createTextFile("config\\config.json", true);
//                s.WriteLine;
//                s.close();
//            }

        </script>
        <style type="text/css"> 
            body{
                width: 100%;
                font-family: "Tw Cen MT", Monospace, 'Sans-serif';

            }
            #container{
                width: 90%;
                padding: 10px;
            }
            #configContents {
                width: auto;
                max-width: 1000px;
                padding: 0px;
            }
            fieldset {
                margin: 30px;
                padding: 15px;
                width: auto;
            }
            table {
                width: auto;
            }
            tr {
                width: 100%;
            }
            td {


            }
            .fontfamily {
                font-family: "Tw Cen MT", Monospace, 'Sans-serif';
                

            }
            input[type="text"]{
                border: 0px;
            }
            input[type="text"]:focus{
                outline: none;
                border:1px solid #5AB0DB;
            }
           
            legend{
                padding: 0px 10px 0px 10px;
            }
            input {

            }
        </style>
    </head>
    <body>
        <div id="container">
            <div id="config" style=""><input style="width:20%; max-width: 40px;outline:none;" type="image" src="https://s19.postimg.org/llh8rg9j7/close.png" alt="config"/></div>
            <div id="configContents">
                <h2>Configuration</h2>
                <input id="testButton" type="button" value="test1" onclick="getJsonObjFromTable('tb_dates','dates', 'input');"/>
                <form id="configForm" method="post" onsubmit="">
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


                    <fieldset>
                        <legend>Others</legend>
                        <table>
                            <thead><tr><td></td><td></td></tr></thead>
                            <tbody>
                                <tr>
                                    <td></td><td></td>

                                </tr>

                            </tbody>
                        </table>


                    </fieldset>
                    <input id="configFormSubmit" type="button" value="Submit" onclick="save();"/>
                </form>

            </div>


        </div>
    </body>
</html>
