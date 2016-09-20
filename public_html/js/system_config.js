var fileArray = [];
var onchangeCount = 0;

$(document).ready(function () {




    var inputFileId = "input_files";

    //Attach event function on input for files(images) for preview
    var fileInput = document.getElementById(inputFileId);
    fileInput.addEventListener('change', function () {
        handleFileSelect(inputFileId);
    }, false);
    console.log("fileInput.files.length: " + fileInput.files.length);


    fileInput.addEventListener('change', function () {
        handleSaveImageButton(fileInput.files.length);
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

function setTableWithArray(tableId, list) {

    //console.log("fuction setTableWithData(", tableId, ",", Object.keys(list[0]), ")" + " | " + list.length);

    var table = document.getElementById(tableId);
    var updateSubmitId = eval('update' + table.dataset.dbrecord).id;
    console.log(updateSubmitId);
    var tableDataDbrecord = table.dataset.dbrecord;
    console.log(tableDataDbrecord);
    var tbody = document.createElement("TBODY");
    var labels = Object.keys(list[0]);

    var thead = document.createElement("THEAD");
    var tr = document.createElement("TR");

    for (var i = 0; i < labels.length + 2; i++) {
        var td = document.createElement("TH");
        var label = "";
        if (i < labels.length) {

            label = labels[i];
        } else {
            label = "delete";
        }

        td.innerHTML = label;
        tr.appendChild(td);


    }

    thead.appendChild(tr);
    table.appendChild(thead);
    for (var i = 0; i < list.length; i++) {
        var obj = list[i];
        var row = tbody.insertRow(i);
        var rowId = tableId + "_row_" + i;
        console.log(rowId);
        row.id = rowId;
        console.log(list[i].id);
        row.dataset.id = list[i].id;
        //row.id = "date" + i;
        for (var j = 0; j < labels.length; j++) {
            var td = row.insertCell(j);
            var label = Object.keys(obj)[j];
            var child = "";

            if (label === "file") {// file , currently just img. DOM img 
                var img = document.createElement('IMG');
                img.src = "data:image/jpeg;base64," + images[i].file;
                img.height = "100";
                img.class = "slide_image";
                child = img;
            } else { // any other data type other than file. DOM input
                var input = document.createElement("INPUT");
                input.id = tableId + 'input' + i;
                input.style.fontFamily = "inherit";
                //'Tw Cen MT', Monospace, 'Sans-serif'
                input.name = Object.keys(obj)[j];
                var value = eval('obj.' + labels[j]);
                if (label === "date") {
                    var date = getJSDate(value);
                    var adjustedMonth = date.getUTCMonth() + 1;

                    value = date.getUTCDate().toString() + "/" + (adjustedMonth < 10 ? "0" : "") + adjustedMonth.toString() + "/" + date.getUTCFullYear();
                    value += " " + (date.getUTCHours() < 10 ? "0" : "") + date.getUTCHours().toString() + ":" + (date.getUTCMinutes() < 10 ? "0" : "") + date.getUTCMinutes().toString() + ":" + (date.getUTCSeconds() < 10 ? "0" : "") + date.getUTCSeconds();
                    input.className += " flatpickr"; // be aware of the space before the appending class name
                    input.dataset.enableTime = true;
                    input.dataset.enableSeconds = true;
                    input.dataset.weekNumbers = true;
                }
                input.setAttribute("value", value);

                if (label === "colour") { // if the key name is colour
                    input.className += " jscolor"; // set class to jscolor.js colour picker // be aware of the space before the appending class name
                }

                if (label === "visible" && (eval('obj.' + labels[j]) === 1 || eval('obj.' + labels[j]) === 0)) {
                    input.setAttribute("type", "checkbox");
                    if (eval('obj.' + labels[j]) === 1) {
                        input.setAttribute("checked", true);
                    }
                } else {
                    input.setAttribute("type", "text");
                }

                input.addEventListener('focus', function () {
                    handleUpdateButton(input.id, "", updateSubmitId, 'focus');
                });
                input.addEventListener('blur', function () {
                    handleUpdateButton(input.id, 'Date B', updateSubmitId, 'blur');
                });
                child = input;

            }
            td.appendChild(child);
        }

        var singleDeleteBtnCell = row.insertCell();
        singleDeleteBtnCell.class = "deleteBtnCol";
        var deleteImg = document.createElement('IMG');
        deleteImg.class = "deleteButton";
        deleteImg.src = "public_html/img/content/delete.png";
        deleteImg.width = "20";
        deleteImg.style.cursor = "pointer";
        deleteImg.addEventListener('click', function () {
            deleteRecord(this.parentElement.parentElement, tableDataDbrecord);
        });
        singleDeleteBtnCell.appendChild(deleteImg);

        var multiDeleteBtnCell = row.insertCell();
        multiDeleteBtnCell.class = "multiDeleteBtnCol";
        var checkbox = document.createElement('input');
        checkbox.class = "deleteCheck";
        checkbox.type = "checkbox";
        multiDeleteBtnCell.appendChild(checkbox);
    }
    table.appendChild(tbody);

}

function setTableWithObject(tableId, object) {
    var table = document.getElementById(tableId);
    var labels = Object.keys(object);
    console.log(labels);
    for (var i = 0; i < labels.length; i++) {
        var row = table.insertRow();
        var label = row.insertCell(0);
        var cell = row.insertCell(1);
        cell.style.padding = '5px 15px 0px 15px';
        var underScoreRemoved = labels[i].replace(/_/gi, " ");
        var number = underScoreRemoved.replace(/num/gi, "number");
        var seconds = number.replace(/sec/gi, "seconds");
        label.innerHTML = seconds;
        var input = document.createElement('INPUT');
        input.name = labels[i];
        input.value = eval('object.' + labels[i]);
        cell.appendChild(input);
    }
}

function getJSDate(dbDateTime) {
    return  new Date(Date.parse(dbDateTime.replace('-', '/', 'g')));
}

function previewFiles() {

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

    //var newFileArray = [];
    var files = document.getElementById(inputFileId).files;// they are the users choice of each time

    //var fileArrayLength = fileArray.length;
    //console.log(fileArrayLength);

    //if (fileArrayLength === 0) {
    //   for (var i = 0, f; f = files[i]; i++) {

    //       newFileArray.push(f);
    //   }
    // } else {
//        for (var i = 0, f; f = files[i]; i++) {
//            var exists = false;
//            var name = f.name;
//            var size = f.size;
//            var type = f.type;
//            for (var j = 0, e; e = fileArray[j]; j++) {
//                if (name === e.name && size === e.size && type === e.type) {
//                    exists = true;
//                }
//            }
//
//            if (exists === false) {
//                newFileArray.push(f);
//            }
//
//
//
//        }
//    }

    // console.log(newFileArray.length);

    var table = document.getElementById('tb_selectedFiles');
    for (var i = 0, f; f = files[i]; i++) {
        //for (var i = 0, f; f = newFileArray[i]; i++) {


        console.log(f);
        //var row = table.insertRow(fileArray.length);
        var row = table.insertRow();
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
        var sequence = document.createTextNode(i);
        //var sequence = document.createTextNode(fileArray.length + i + 1);
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
        button.addEventListener('click', function () {
            removeRow("tb_selectedFiles", i);
        }, false);
        //button.onclick = function(){removeRow("tb_selectedFiles", i);};
        //removeFileRow("tb_selectedFiles", fileArray.length+i);
        removeCell.appendChild(button);

        //fileArray.push(f);
    }


    //   console.log(fileArray);

    var saveImageBtn = document.getElementById("saveImage");
    saveImageBtn.addEventListener('click', function () {
        //saveImages(fileArray, "image");
        saveImages("imageUploadForm");
    });

}

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
function handleSaveImageButton(fileArrayLength) {
//    var fileListLength = document.getElementById(fileInputId).files.length;
    if (fileArrayLength > 0) {
        document.getElementById("saveImage").style.display = 'block';
    } else {
        document.getElementById("saveImage").style.display = 'none';
    }
    console.log(document.getElementById("saveImage").style.display);
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

function deleteRecord(rowElement, tableDataDbrecord) {
    console.log(rowElement.dataset.id);
    rowElement.parentElement.removeChild(rowElement);

    $.ajax({
        type: "POST",
        url: '\system_config.php',
        data: {action: 'deleteRecord',
            record: tableDataDbrecord,
            id: rowElement.dataset.id},
        success: function () {

        }
    });
}


function saveImages(formId) {

    var formData = new FormData(document.getElementById(formId));

    $.ajax({
        url: 'system_config.php',
        type: 'POST',
        xhr: function () {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        success: function (data) {
            console.log("Data Uploaded: " + data);
        },
        data: formData,
        cache: false,
        contentType: false,
        processData: false
    });
    return false;
}

function handleUpdateButton(inputId, inputValueOld, updateSubmitId, eventName) {
    //document.forms.namedItem(formName).elements.namedItem("updateImage").style.display = "block";
    if (eventName === 'focus') {
        document.getElementById(updateSubmitId).style.display = "block";
    } else if (eventName === 'blur') {
        console.log(document.getElementById(inputId).value + " " + inputValueOld);
        if (document.getElementById(inputId).value === inputValueOld) {
            document.getElementById(updateSubmitId).style.display = "none";
        } else {
            document.getElementById(updateSubmitId).style.display = "block";
        }
    }
}