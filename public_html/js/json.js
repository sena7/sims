
function getJson(path) {
 //   var data = null;

//    var request = new XMLHttpRequest();
//    request.open('GET', path, true);
//    console.log(request.status);
//    request.onload = function () {
//        if (request.status >= 200 && request.status < 400) {
//            //Success
//            data = JSON.parse(request.responseText);
//        } else {
//            //Reached the target. returned Error
//        }
//    };
//
//    request.onerror = function () {
//       
//    };
//      
//    request.send();      
//    return data;
}



function getJsonArrayFromTable(tableId) {
                var table = document.getElementById(tableId);



                // table rows array. index 0 = header, index = 1  
                var rows = document.getElementById(tableId).getElementsByTagName('tr');

                var keys = [];
                var headers = rows[0].getElementsByTagName('td');
                for (i = 0; i < headers.length; i++) {
                    keys.push(headers[i].textContent);
                    console.log(keys);
                }


                // jquery   
//                var keys = [];
//                $('#' + tableId + '>thead>tr>td').each(function () {
//                    console.log($(this).text());
//                    keys.push($(this).text());
//                });

            }
