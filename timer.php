<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <link rel="shortcut icon" href="">

        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script type ="text/javascript">

            var interval = null;
            var title;
            var value;
            var numTimer = 1;

            $(document).ready(function () {
                //window.onload = countdown;


// dateMs list for testing
                var msList = [
                    Date.UTC(2016, 08, 01, 08, 00, 00),
                    Date.UTC(2017, 08, 17, 08, 00, 00),
                    Date.UTC(2018, 03, 18, 08, 00, 00),
                    Date.UTC(2018, 07, 18, 08, 00, 00),
                    Date.UTC(2018, 10, 18, 08, 00, 00),
                ];

                var msMap = {
                    "SRM": Date.UTC(2016, 08, 01, 08, 00, 00),
                    "Admissions": Date.UTC(2017, 08, 17, 08, 00, 00),
                    "Curriculum Planning & Financials": Date.UTC(2018, 03, 18, 08, 00, 00),
                    "Registration": Date.UTC(2018, 07, 18, 08, 00, 00),
                    "Eams Awards Graduation": Date.UTC(2018, 10, 18, 08, 00, 00),
                };

                function getDateList() {
                    // read file. 
                    // now for testing purpose, just declare here as functios
                    // order dates by asc

                    return;
                }


                var numberOfTimer = 1;
                var pastList = [];
                var futureList = [];

                var pastMap = [];
                var futureMap = [];

                console.log(msList.length);
                console.log(msList.toString());

                //function sortDatesMs() {

                var now = Date.now();
                for (i = 0; i < msList.length; i++) {


                    if (msList[i] < now) {
                        console.log(msList[i] < now);
                        pastList.push(msList[i]);
                    } else {
                        futureList.push(msList[i]);
                    }
                }
//            console.log(pastList.toString());    
//             console.log(futureList.toString());    
                pastList.sort(function (a, b) {
                    return a - b
                });
                futureList.sort(function (a, b) {
                    return a - b
                });
//            console.log(pastList.length);
//            console.log(futureList.length);
                console.log(pastList.toString());
                console.log(futureList.toString());
                //  }


                //  function buildScreen() {

                var maxFontSize = 0.4;
                var minFontSize = 0.1;
                var size;
                var date;
                if (pastList.length >= 1) {

                    var fontSizeUnit = (maxFontSize - minFontSize) / pastList.length;

                    var pastUl = document.getElementById("pastList");
                    var e, n;
                    for (i = 0; i < pastList.length; i++) {
                        date = new Date(pastList[i]);

                        e = document.createElement("LI");
                        size = minFontSize + fontSizeUnit * (i + 1);
                        e.style.cssText = 'font-size:' + size + 'em;';
                        var adjustedMonth = date.getMonth() + 1;

                        n = document.createTextNode(msMap.getKeyByValue(pastList[i]) + " " + getDecimalPrefix(date.getDate()) + date.getDate() + "/" + getDecimalPrefix(adjustedMonth) + adjustedMonth + "/" + date.getFullYear());
                        e.appendChild(n);

                        pastUl.appendChild(e);


                    }

                }
                if (futureList.length >= 1) {

                    var fontSizeUnit = (maxFontSize - minFontSize) / futureList.length;

                    var futureUl = document.getElementById("futureList");
                    var e, n;

                    for (i = numTimer; i < futureList.length; i++) {
                        var nowMs = Date.now();
                        var futureMs = futureList[i];

                        /* Date.UTC() insert (uk local time - 1) to make UTC time. month index starts from 0, so for June, it is 5*/




                        var remainMs = futureMs - nowMs;
                        date = new Date(remainMs);

                        var getYears = date.getUTCFullYear() - 1970;
                        var getMonths = date.getUTCMonth() + getYears * 12;
                        var monthz = "";
                        if (getMonths > 1) {
                            monthz = "months";
                        } else if (getMonths === 1) {
                            monthz = "month";
                        } else {

                            monthz = "";
                        }

                        var getDays = date.getUTCDate() - 1;
                        var dayz = "";
                        if (getDays > 1) {
                            dayz = "days";
                        } else if (getDays === 1) {
                            dayz = "day";
                        } else {

                            dayz = "";
                        }

                        e = document.createElement("LI");
                        size = maxFontSize - fontSizeUnit * i;
                        e.style.cssText = "font-size:" + size + "em;";
                        var adjustedMonth = date.getMonth() + 1;

                        n = document.createTextNode(msMap.getKeyByValue(futureList[i])
                                + " " + getDecimalPrefix(date.getDate()) + date.getDate() + "/" + getDecimalPrefix(adjustedMonth) + adjustedMonth + "/" + date.getFullYear() + " " + getMonths + monthz + " " + getDays + dayz
                                //+ " " + date.getHours() + ":" + date.getMinutes()

                                );
                        e.appendChild(n);

                        futureUl.appendChild(e);


                    }
                }
                //     }



                if (futureList.length >= 1) {
                    title = msMap.getKeyByValue(futureList[0]);
                    value = futureList[0];
                    interval = setInterval(timer, 1000);
                    timer();
                } else {
                    document.getElementById("currentTimer").hidden = 'true';


                    var e = document.createElement("DIV");
                    e.align = "center";
                    var n = document.createTextNode("WELLDONE EVERYBODY");
                    e.appendChild(n);

                    var container = document.getElementById("container");
                    container.appendChild(e);
                }
            });

            window.onload = function () {
            };


            Object.prototype.getKeyByValue = function (value) {
                for (var prop in this) {
                    if (this.hasOwnProperty(prop)) {
                        if (this[ prop ] == value)
                            return prop;
                    }
                }
            };


            function timer() {
                console.log('time:', value);
                var nowMs = Date.now();
                var futureMs = value;

                /* Date.UTC() insert (uk local time - 1) to make UTC time. month index starts from 0, so for June, it is 5*/


                if (futureMs > nowMs) {

                    var remainMs = futureMs - nowMs;
                    var remainDate = new Date(remainMs);


                    var getYears = remainDate.getUTCFullYear() - 1970;
                    var getMonths = remainDate.getUTCMonth();
                    var getDays = remainDate.getUTCDate() - 1;
                    var getHours = remainDate.getUTCHours();
                    var getMinutes = remainDate.getUTCMinutes();
                    var getSeconds = remainDate.getUTCSeconds();

                    console.log(getYears
                            , getMonths
                            , getDays
                            , getHours
                            , getMinutes
                            , getSeconds);

                    document.getElementById("title").innerHTML = title + " " + "Go Live";

                    if (getYears > 0) {
                        displayTrue("getYears");

                        document.getElementById("getYears").innerHTML = getYears + (getYears !== 1 ? " years" : " year");

                    } else {
                        displayNone("getYears");
                    }

                    if (getMonths > 0) {
//                        if (document.getElementById("getMonths").getAttribute("hidden") === true) {
//                            document.getElementById("getMonths").setAttribute("hidden", false);
//
//                        }
//                        if (document.getElementById("getMonthsTag").getAttribute("hidden") === true) {
//                            document.getElementById("getMonthsTag").setAttribute("hidden", false);
//                        }
                        displayTrue("getMonths");

                        document.getElementById("getMonths").innerHTML = getMonths + (getMonths !== 1 ? " months" : " month");


                    } else {
//                        document.getElementById("getMonths").setAttribute("hidden", true);
//                        document.getElementById("getMonthsTag").setAttribute("hidden", true);
                         displayNone("getMonths");

                    }

                    if (getDays > 0) {

                        displayTrue("getDays");

                        document.getElementById("getDays").innerHTML = getDays + (getDays !== 1 ? " days" : " day");


                    } else {
                        document.getElementById("getDays").setAttribute("hidden", true);

                    }
                    if (remainMs < 86400000) {
                         displayNone("top");
                    }

                    if (getHours > 0) {
                        if (getHours < 10) {

                            var hours = (0).toString() + getHours.toString();
                            console.log(hours);
                            document.getElementById("getHours").innerHTML = hours + "h";
                        } else {
                            document.getElementById("getHours").innerHTML = getHours + "h";
                        }
                    }

                    if (remainMs < 3600000) { // if the remaining time is less than an hour
                        displayNone("getHours");
                    }



                    if (getMinutes < 10 & getMinutes >= 0) {

                        var minutes = (0).toString() + getMinutes.toString();
                        document.getElementById("getMinutes").innerHTML = minutes + "m";
                    } else
                    {
                        document.getElementById("getMinutes").innerHTML = getMinutes + "m";


                    }




                    if (remainMs < 60000) { // if the remaining time is less than a minute
                        //document.getElementById("colon_ms").setAttribute("hidden", true);
                        //document.getElementById("colon_ms").style.display = "none";
                        //document.getElementById("getMinutes").setAttribute("hidden", true);
                        displayNone("getMinutes");
                    }




                    if (getSeconds < 10 & getSeconds >= 0) {

                        var seconds = (0).toString() + getSeconds.toString();
                        console.log(seconds);
                        document.getElementById("getSeconds").innerHTML = seconds + "s";
                    } else {
                        document.getElementById("getSeconds").innerHTML = getSeconds + "s";

                    }



                } else {

                    if (interval !== null) {
                        clearInterval(interval);
                    }

                    document.getElementById("timerframe").hidden = 'true';

                    var e = document.createElement("DIV");

                    var textNode = document.createTextNode("LIVE")
                    e.appendChild(textNode);
                    var container = document.getElementById("currentTimer");
                    container.appendChild(e);

                    setTimeout(function () {
                        location.reload(true);
                    }, 3000);

                }
            }

            function getDecimalPrefix(decimal) {
                if (decimal < 10) {
                    return "0";
                } else {
                    return "";
                }
            }

            function displayNone(elementId) {

                document.getElementById(elementId).style.display = 'none';

            }
            function displayTrue(elementId) {
                document.getElementById(elementId).style.display = '';
            }

            function test(){
                var mySQLDate = '2015-04-29 10:29:08';
                var date = new Date(Date.parse(mySQLDate.replace('-','/','g')));

                alert(date.getUTCMonth());
            }
        </script>

        <style type="text/css">
            div.container{
                font-size: 5em;
                font-family:'Arial Black', Gadget, sans-serif; 
                width: 100%;
                position: relative;
                vertical-align: middle;
                margin: auto;
                text-align: center;

                margin:auto;
                display: flex;
                justify-content: center;
                align-items: center;

                position: relative;
                display:inline-block;
            }
            #curruntTimer{
                text-align:center;
                width: 100%;
            }
            #timerframe{
                text-align:center;
                width: 100%;
            }
            .element {
                display:inline-block;
                padding: 10px;
                vertical-align: middle;
                text-align: center;

            }
            div.pastFuture {
                margin:auto;
                display: flex;
                justify-content: center;
                align-items: center;

                position: relative;
                display:inline-block;

            }

        </style>
    </head>

    <body>

 <?php
 require('config.php');
 $sql = "select name from date";
     $result = mysql_query($sql);
     echo(mysql_result($result, 0));
 ?>


        <div  id = "container" class="container" style="padding:0px;border: 0px; color:#5AB0DB;width:100%;height:100%;max-height: 1020px;font-family:'Arial Black', Gadget, sans-serif; margin: 0 auto;">
            <div><button onclick = "test();">test</button> </div>

            <div style="text-align:center;position:relative;margin:0;" id="currentTimer">
                <div id="title" style="position: relative;color:#5AB0DB;background-color: #eaf5fa;"></div>
                <div id="timerframe" style="top:0px;color:#eaf5fa; background-color: #5AB0DB;">


                    <div id = "top"> 
                        <div class="element" id="getYears"></div>
                        <div class="element" id="getMonths"></div>
                        <div  class="element" id="getDays"></div>


                    </div>
                    <div>
                        <div class="element" style="" id="getHours"></div>
                    
                        <div class="element" style="" id="getSeconds"></div>
                    </div>

                </div>
            </div>
            <div id="pastFuture">

                <div id="pastTimer" style="width:50%; float:left;">
                    <div style="background-color: #f0eef7;color:#9d8fca;font-size: 0.5em;">Past</div>
                    <div style="background-color: #9d8fca;color:#f0eef7;min-height: 200px;">
                        <ul id="pastList"  style="list-style: none;text-align: center;margin-top:0;margin-bottom: 0;margin-left:auto;margin-right:auto;padding:10px;">
                            <li></li>
                        </ul>
                    </div>
                </div>

                <div id="futureTimer" style="width:50%; float:left;">

                    <div style="background-color:#e5f9f4 ;color:#8fcabd;font-size: 0.5em;">Future</div>
                    <div style="background-color: #8fcabd;color:#e5f9f4;min-height: 200px;">
                        <ul id="futureList"  style="list-style: none;text-align: center;margin-top:0;margin-bottom: 0;margin-left:auto;margin-right:auto;padding:10px;">

                        </ul></div>


                </div>
            </div>
       




        </div>



    </body>
</html>