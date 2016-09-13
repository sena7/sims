<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
        <?php
       
//        if (!isset($_SESSION['count'])) {
//            $_SESSION['count'] = 0;
//        } else {
//            $_SESSION['count'] ++;
//        }
        $timerDateList = [];  
        $pastDateList = [];
        $futureDateList = [];
        class date {

            public $category_id;
            public $name;
            public $date;
            public $visible;

        }

        require('config.php');
        $sql = "select category_id, name, date, visible from date order by date asc";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, "date");
        // var_dump($result);

        $now = new DateTime(); // utc I think 20160913
        print_r($now);
        foreach ($result as $date) {
           if( new DateTime($date->date) < $now){
               array_push($pastDateList, $date);
           }else {
               array_push($futureDateList, $date);
           }
        }
        
        print_r($pastDateList);
        print_r($futureDateList);
        ?>

<html>
    <head>
        <link rel="shortcut icon" href="">

        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script type ="text/javascript">

            var timerIntervals = [];
            var numTimer = 2; // comes from setting/config. 
            
            $(document).ready(function () {
                                 
                var futureDateList = <?php echo json_encode($futureDateList);?>;
                console.log(futureDateList[0].date);
                console.log(getJSDate(futureDateList[0].date));
                console.log(getJSDate(futureDateList[0].date).getUTCFullYear());
                console.log(getJSDate(futureDateList[0].date).getUTCMonth());
                console.log(getJSDate(futureDateList[0].date).getUTCDate());
                console.log(getJSDate(futureDateList[0].date).getUTCHours());
                console.log(getJSDate(futureDateList[0].date).getUTCMinutes());
                console.log(getJSDate(futureDateList[0].date).getUTCSeconds());
                var MS = getJSDate(futureDateList[0].date).getTime();
                console.log(getJSDate(futureDateList[0].date).getTime());
                var remainMs = Date.now()-MS;
                console.log(remainMs);
                var ms = 1;
                var s = ms*1000;
                var m = s*60;
                var h = m*60;
                var d = h*24;
                var y = d*365;
                
                console.log(y, d, h, m, s);
                var day = remainMs/d;
                var hour = (remainMs%d)/h;
                var minute = ((remainMs%d)%h)/m;
                var second = (((remainMs%d)%h)%m)/s;
                console.log(day, hour, minute, second);
                
                
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


                var pastList = [];
                var futureList = [];
                var pastMap = {};
                var futureMap = {};


                var now = Date.now();
                for (i = 0; i < msMap.length; i++) {


                    if (msMap[i].value < now) {
                        console.log(msMap[i].value < now);
                        pastMap.push(msMap[i]);
                    } else {
                        futureList.push(msMap[i]);
                    }
                }

//                pastList.sort(function (a, b) {
//                    return a - b
//                });
//                futureList.sort(function (a, b) {
//                    return a - b
//                });

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
                    //2015-04-29 09:00:00

                    title = msMap.getKeyByValue(futureList[0]);
                    value = futureList[0];
                    interval = setInterval(setTimer(new DueDate("DATE A", "2017-04-29 09:00:00", "category A", 1), ""), 1000);
                    setTimer();


                } else {
                    //document.getElementById("currentTimer").hidden = 'true';


                    var e = document.createElement("DIV");
                    e.align = "center";
                    var n = document.createTextNode("WELLDONE EVERYBODY");
                    e.appendChild(n);

                    var container = document.getElementById("container");
                    //  container.appendChild(e);
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



            function setTimer(DueDate, templateDivElem) {

                var nowMs = Date.now();
                var futureMs = getJSDate(DueDate.date);

                if (futureMs > nowMs) {

                    var remainMs = futureMs - nowMs;
                    var remainDate = new Date(remainMs);


                    var year = remainDate.getUTCFullYear() - 1970;
                    var month = remainDate.getUTCMonth();
                    var day = remainDate.getUTCDate() - 1;
                    var hour = remainDate.getUTCHours();
                    var minute = remainDate.getUTCMinutes();
                    var second = remainDate.getUTCSeconds();


                    templateDivElem.getElementByClass("title").innerHTML = DueDate.name + " " + "Go Live";
                    var timer = templateDivElem.getElementByClass("timer");
                    if (year > 0) {
                        timer.getElementByClass("timer_year").style.display = "";
                        timer.getElementByClass("timer_year").innerHTML = year + (year !== 1 ? " years" : " year");
                    } else {
                        timer.getElementByClass("timer_year").style.display = "none";
                    }

                    if (month > 0) {
                        timer.getElementByClass("timer_month").style.display = "";
                        timer.getElementByClass("timer_month").innerHTML = month + (month !== 1 ? " months" : " month");
                    } else {
                        timer.getElementByClass("timer_month").style.display = "none";
                    }

                    if (day > 0) {
                        timer.getElementByClass("timer_day").style.display = "";
                        timer.getElementByClass("timer_day").innerHTML = day + (day !== 1 ? " days" : " day");
                    } else {
                        timer.getElementByClass("timer_day").style.display = "none";
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
            function createTimerTemplate(parentElemId, index, DueDate) {
                var parent = document.getElementById(parentElemId);
                var template = document.createElement('DIV');

                template.dataset.sequence = index;

                var title = document.createElement('DIV');
                title.class = 'timer_title';
//                var titleText = document.createTextNode(DueDate.name);
//                title.appendChild(titleText);

                var date = document.createElement('DIV');
                date.class = 'timer_date';
//                var dateText = document.createTextNode(DueDate.date);
//                date.appendChild(dateText);

                var timer = document.createElement('DIV');
                timer.class = 'timer';
                var year = document.createElement('DIV');
                year.class = 'timer_year';
                var month = document.createElement('DIV');
                year.class = 'timer_month';
                var day = document.createElement('DIV');
                year.class = 'timer_day';
                var hour = document.createElement('DIV');
                year.class = 'timer_hour';
                var minute = document.createElement('DIV');
                year.class = 'timer_minute';
                var second = document.createElement('DIV');
                year.class = 'timer_second';

                timer.appendChild(year);
                timer.appendChild(month);
                timer.appendChild(day);
                timer.appendChild(hour);
                timer.appendChild(minute);
                timer.appendChild(second);


                template.appendChild(title);
                template.appendChild(date);
                template.appendChild(timer);

                parent.appendChild(template);
                return template;
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

            function getJSDate(dbDateTime) {
                return  new Date(Date.parse(dbDateTime.replace('-', '/', 'g')));
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



        <div  id = "container" class="container" style="padding:0px;border: 0px; color:#5AB0DB;width:100%;height:100%;max-height: 1020px;font-family:'Arial Black', Gadget, sans-serif; margin: 0 auto;">

            <div id ="timerContainer">
             
            </div>

        </div>



    </body>
</html>