var fileArray = [];
var onchangeCount = 0;

$(document).ready(function () {
// check if the current user's browser supports File APIs
    if (window.File && window.FileReader && window.FileList && window.Blob) {
        console.log("All the File APIs supported");
    } else {
        alert('The File APIs are not fully supported in this browser.');
    }


    var inputFileId = "input_files";

    //Attach event function on input for files(images) for preview
    var fileInput = document.getElementById(inputFileId);
    fileInput.addEventListener('change', function () {
        handleFileSelect(inputFileId);
    }, false);
    console.log("fileInput.files.length: " + fileInput.files.length);

    var inputFileSubmitId = "f2_submit";
    fileInput.addEventListener('change', function () {
        handleSaveButton(inputFileId, inputFileSubmitId);
    }, false);

    //local storage
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
//                $('#tb_images').DataTable({
//                    paging: true,
//                    searching: false,
//                    ordering: true,
//                    rowReorder: true
//                });


    /*Date Picker config*/
    $(".datepicker").datepicker(
            {
                dateFormat: 'dd/mm/yy'
            });

});
/*******************************Functions****************************/



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
            input.setAttribute("class", "fontfamily"); // it would not inherit the body font unless assigned to classes with the certain styles
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
            jsonArrayText += "\"" + keys[j] + "\"" + ":" + "\"" + values[j].value + "\"";
            jsonArrayText += j === values.length - 1 ? "}" : ",";
        }

        jsonValue.push(jsonArrayText);
    }

    jsonObj = JSON.parse("{" + "\"" + jsonKey + "\"" + ":" + "[" + jsonValue + "]}");
    console.log(jsonObj);
    console.log(JSON.stringify(jsonObj));
    return jsonObj;
}

function save() {
    var data = $("#configForm").serializeArray();
    console.log(data);
}

function previewFile() {

    var files = document.querySelector('input[type=file]').files;
    var imgs = document.getElementsByTagName('img');
    console.log(imgs[0]);
    //var files = document.querySelector('input[type=file]');
    console.log(files[0]);
    var readerList = [];
    console.log(files.length);
    for (i = 0; i < files.length; i++) {

        var reader = new FileReader();
        readerList.push(reader);
        var img = document.getElementById('previews' + i.toString());
        reader.addEventListener("load", function () {
            img.src = reader.result;
        }, false);
        if (files[i]) {
            reader.readAsDataURL(files[i]);
        }
    }
    console.log(readerList);
}

function handleFileSelect(inputFileId) {
    onchangeCount += 1;

    var newFileArray = [];
    var files = document.getElementById(inputFileId).files;// they are the users choice of each time

    

//var files = evt.target.files; // FileList object
    //var files = document.getElementsByTagName('input').files;

    //var files = document.getElementById(fileInputId).files;
    // files is a FileList of File objects. List some properties.
    //var fileArray = getFileArray(fileInputId);
    //var fileArray = [];





    var fileArrayLength = fileArray.length;
    console.log(fileArrayLength);

    if (fileArrayLength === 0) {
        for (var i = 0, f; f = files[i]; i++) {
             
             newFileArray.push(f);
        }
    } else {
        for (var i = 0, f; f = files[i]; i++) {
          var exists = false;
          var name = f.name;
          var size = f.size;
          var type = f.type;
          for(var j= 0, e; e = fileArray[j]; j++){
              if(name===e.name&&size===e.size&&type===e.type){
                  exists = true;
              }
          }
             
          if(exists===false){
                newFileArray.push(f);  
          }
           


        }
    }

    console.log(newFileArray.length);




    var table = document.getElementById('tb_selectedFiles');
    for (var i = 0, f; f = newFileArray[i]; i++) {


        console.log(f);
        var row = table.insertRow(fileArray.length+i);
        var rowId = "row" + i;
        var rowClass = "delete";
        row.id = rowId;
        row.class = rowClass;
        var sequenceCell = row.insertCell(0);
        var imageCell = row.insertCell(1);
        var nameCell = row.insertCell(2);
        var sizeCell = row.insertCell(3);
        var removeCell = row.insertCell(4);
        var img = document.createElement('IMG');
        img.id = "previews" + i;
        img.height = "50";
        img.alt = "img" + i;
        var imgSize = 0;
        var sizeUnit = "";
        if (f.size >= 1000000) {
            imgSize = Math.round((f.size / 1000000) * 10) / 10;
            sizeUnit = "MBs";
        } else if (f.size < 1000000 && f.size > 1000) {
            imgSize = Math.round(f.size / 1000);
            sizeUnit = "KBs";
        } else {
            imgSize = f.size();
            sizeUnit = "Bytes";
        }

        var sequence = document.createTextNode(fileArray.length+i+1);
        sequenceCell.appendChild(sequence);
        var name = document.createTextNode(escape(f.name));
        var size = document.createTextNode(imgSize + sizeUnit);
        nameCell.appendChild(name);
        sizeCell.appendChild(size);
        imageCell.appendChild(img);
        previewFile(f, img);
        var button = document.createElement("BUTTON");
        var t = document.createTextNode("remove");
        button.appendChild(t);
        button.setAttribute('data', "index: '" + i + "'");
        button.addEventListener('click', function(){removeRow("tb_selectedFiles", i);}, false);
        //button.onclick = function(){removeRow("tb_selectedFiles", i);};
        //removeFileRow("tb_selectedFiles", fileArray.length+i);
        removeCell.appendChild(button);
    }

    for(var i=0, e; e=newFileArray[i]; i++){
        fileArray.push(e);
    }

   
}

//             function handleFileSelect(evt) {
//                var files = evt.target.files; // FileList object
//                // files is a FileList of File objects. List some properties.
//                var list = document.getElementById('list');
//                var ol = document.createElement('OL');
//
//                for (var i = 0, f; f = files[i]; i++) {
//
//
//                    console.log(f);
//                    var li = document.createElement('LI');
//
//                    var img = document.createElement('IMG');
//                    img.id = "previews" + i;
//                    img.height = "50";
//                    img.alt = "img" + i;
//                    var imgSize=0;
//                    var sizeUnit="";
//                    if (f.size >= 1000000) {
//                        imgSize = Math.round((f.size/1000000)*10)/10;
//                        sizeUnit = "MBs";
//                    } else if (f.size < 1000000 && f.size > 1000) {
//                        imgSize = Math.round(f.size/1000);
//                        sizeUnit = "KBs"
//                    } else {
//                        imgSize = f.size();
//                        sizeUnit = "Bytes"
//                    }
//                    var text = document.createTextNode(escape(f.name) + " " + imgSize + sizeUnit);
//                    li.appendChild(text);
//                    li.appendChild(img);
//
//                    ol.appendChild(li);
//                    previewFile(f, img);
//
//                }
//
//                list.appendChild(ol);
//
//            }

function previewFile(file, img) {

    var reader = new FileReader();
    reader.addEventListener("load", function () {
        img.src = reader.result;
    }, false);
    if (file) {
        reader.readAsDataURL(file);
    }
}



function removeRow(tableId, index) {
    var table = document.getElementById(tableId);
    console.log("triggered ? ");
    table.deleteRow(index);
    var removed = fileArray.pop(index);
    console.log(removed);
    //then rearrange.
 
}
function handleSaveButton(fileInputId, submitId) {
    var fileListLength = document.getElementById(fileInputId).files.length;
    if (fileListLength > 0) {
        document.getElementById(submitId).style.display = 'block';
    } else {
        document.getElementById(submitId).style.display = 'none';
    }
    console.log(document.getElementById(submitId).style.display);
}

function getFileArray(inputId) {
    console.log("inputId: " + inputId);
    var fileInput = document.getElementById(inputId);
    console.log(!fileInput);
    var fileList = document.querySelector('input[type=file]').files;
    var fileArray = [];
    console.log("fileList[0]: " + fileList[0]);
    if (fileList.length > 0) {
        for (i = 0, f; f = fileList[i]; i++) {
            console.log(f);
            fileArray.push(f);
        }


    }
    return fileArray;
}
//Array.prototype.contains

function getGlobalVarFileArray() {
}