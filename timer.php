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
            
            $(document).ready(function(){});
            
            window.onload = function () {



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

                    for (i = 0; i < futureList.length; i++) {
                        date = new Date(futureList[i]);

                        e = document.createElement("LI");
                        size = maxFontSize - fontSizeUnit * i;
                        e.style.cssText = "font-size:" + size + "em;";
                        var adjustedMonth = date.getMonth() + 1;
                        
                        n = document.createTextNode(msMap.getKeyByValue(futureList[i])
                                + " " +  getDecimalPrefix(date.getDate()) + date.getDate() + "/" + getDecimalPrefix(adjustedMonth) + adjustedMonth + "/" + date.getFullYear()
                                //+ " " + date.getHours() + ":" + date.getMinutes()
                                )
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

                    document.getElementById("title").innerHTML = title + " " + "Go Live" ;

                    if (getYears > 0) {
                        if (document.getElementById("getYears").getAttribute("hidden") === true) {
                            document.getElementById("getYears").setAttribute("hidden", 'false');

                        }
                        if (document.getElementById("getYearsTag").getAttribute("hidden") === true) {
                            document.getElementById("getYearsTag").setAttribute("hidden", false);
                        }

                        document.getElementById("getYears").innerHTML = getYears;

                        if (getYears !== 1) {
                            document.getElementById("getYearsTag").innerHTML = "years";
                        }else {
                            document.getElementById("getYearsTag").innerHTML = "year";
                        }
                    } else {
                        document.getElementById("getYears").setAttribute("hidden", true);
                        document.getElementById("getYearsTag").setAttribute("hidden", true);
                    }


                    if (getMonths > 0) {
                        if (document.getElementById("getMonths").getAttribute("hidden") === true) {
                            document.getElementById("getMonths").setAttribute("hidden", false);

                        }
                        if (document.getElementById("getMonthsTag").getAttribute("hidden") === true) {
                            document.getElementById("getMonthsTag").setAttribute("hidden", false);
                        }

                        document.getElementById("getMonths").innerHTML = getMonths;

                        if (getMonths !== 1) {
                            document.getElementById("getMonthsTag").innerHTML = "months";
                        }else{
                              document.getElementById("getMonthsTag").innerHTML = "month";
                        }
                    } else {
                        document.getElementById("getMonths").setAttribute("hidden", true);
                        document.getElementById("getMonthsTag").setAttribute("hidden", true);
                    }

                    if (getDays > 0) {

                        if (document.getElementById("getDays").getAttribute("hidden") === true) {
                            document.getElementById("getDays").setAttribute("hidden", false);

                        }
                        if (document.getElementById("getDaysTag").getAttribute("hidden") === true) {
                            document.getElementById("getDaysTag").setAttribute("hidden", false);
                        }


                        document.getElementById("getDays").innerHTML = getDays;

                        if (getDays > 1) {
                            document.getElementById("getDaysTag").innerHTML = "days";
                        }
                    } else {
                        document.getElementById("getDays").setAttribute("hidden", true);
                        document.getElementById("getDaysTag").setAttribute("hidden", true);
                    }
                    if(remainMs<86400000){
                        document.getElementById("top").setAttribute("hidden", true);
                    }

                    if (getHours > 0) {
                        if (getHours < 10) {

                            var hours = (0).toString() + getHours.toString();
                            console.log(hours);
                            document.getElementById("getHours").innerHTML = hours;
                        } else {
                            document.getElementById("getHours").innerHTML = getHours;
                        }
                    }

                    if (remainMs < 3600000) { // if the remaining time is less than an hour
                        document.getElementById("getHours").setAttribute("hidden", true);
                        document.getElementById("colon_hm").setAttribute("hidden", true);
                        //  document.getElementById("colon_hm").innerHTML = "";
                        document.getElementById("colon_hm").style.display = 'none';// error. change this
                    }



                    if (getMinutes < 10 & getMinutes >= 0) {

                        var minutes = (0).toString() + getMinutes.toString();
                        document.getElementById("getMinutes").innerHTML = minutes;
                    } else
                    {
                        document.getElementById("getMinutes").innerHTML = getMinutes;


                    }




                    if (remainMs < 60000) { // if the remaining time is less than a minute
                        //document.getElementById("colon_ms").setAttribute("hidden", true);
                        document.getElementById("colon_ms").style.display = "none";
                        //document.getElementById("getMinutes").setAttribute("hidden", true);
                        document.getElementById("getMinutes").style.display = "none";
                    }




                    if (getSeconds < 10 & getSeconds >= 0) {

                        var seconds = (0).toString() + getSeconds.toString();
                        console.log(seconds);
                        document.getElementById("getSeconds").innerHTML = seconds;
                    } else {
                        document.getElementById("getSeconds").innerHTML = getSeconds;

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

            function getDecimalPrefix (decimal){
                if(decimal < 10){
                    return "0";
                }else {
                    return "";
                }
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
                
                vertical-align: middle;
                text-align: center;
                
            }
            div {
                
}
        </style>
    </head>

    <body>


        <!--	   <table align="center" style="border: 0px; color:#000000;width:100%;height:100%;font-family:'Arial Black', Gadget, sans-serif;font-size:3.0em;margin:100px;">
                   <tr><td>
                       
                       <table><tr><td>local time:&nbsp;</td><td id="localtime"></td></tr>
                       <tr><td>Go Live Date:&nbsp;</td><td id="then0"></td></tr>
                       </table>
                   
                    
                   </td></tr>
                   <tr>
                     <td>
                     <table align="center" style="font-size: 2.5em; font-family:'Arial Black', Gadget, sans-serif;">
                         <thead >CS<span>Live</span></thead>
                        <tbody>
                        <tr>
                             <td id="getYears">&nbsp;</td><td id="getYearsTag">year&nbsp;</td>  
                             <td id="getMonths">&nbsp;</td><td id = "getMonthsTag">month&nbsp;</td>  
                             <td id="getDays">&nbsp;</td><td id="getDaysTag">day&nbsp;</td>   
                             <td id="getHours">00</td><td>:</td><td id="getMinutes">00</td><td>:</td><td id="getSeconds">00</td>
                            
                            
                          
                        </tr>
                        </tbody>
                     </table></td>
                   </tr>
                </table>-->


        <div  id = "container" class="container" style="padding:0px;border: 0px; color:#000000;width:100%;height:100%;font-family:'Arial Black', Gadget, sans-serif; margin: 0 auto;">
            <div id="pastTimer">
                <ul id="pastList"  style="list-style: none;text-align: center;margin-top:0;margin-bottom: 0;margin-left:auto;margin-right:auto;padding:10px;">
                    <li></li>
                </ul>
            </div>

            <div style="text-align:center;position:relative;margin:0;color:#009966" id="currentTimer">
                <div id="title" style="position: relative;"></div>
                <div id="timerframe" style="top:0px;">


                    <div id = "top"> 
                        <div class="element" id="getYears"></div><div class="element" id="getYearsTag"></div>
                        <div class="element" id="getMonths"></div><div class="element" id="getMonthsTag"></div>
                        <div  class="element" id="getDays"></div><div id="getDaysTag" class = "element" style="padding-left: 30px;"></div>


                    </div>
                    <div>
                        <div class="element" style="" id="getHours"></div><div class="element" id = "colon_hm" >&nbsp;&colon;</div>
                        <div class="element" style="" id="getMinutes"></div><div class="element" id = "colon_ms" >&nbsp;&colon;</div>
                        <div class="element" style="" id="getSeconds"></div>
                    </div>

                </div>
            </div>



            <div id="futureTimer">
                <ul id="futureList"  style="list-style: none;text-align: center;margin-top:0;margin-bottom: 0;margin-left:auto;margin-right:auto;padding:10px;">

                </ul>

            </div>

        </div>



    </body>
</html>