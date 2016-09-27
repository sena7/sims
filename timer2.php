<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
    <?php
//        if (!isset($_SESSION['count'])) {
//            $_SESSION['count'] = 0;
//        } else {
//            $_SESSION['count'] ++;
//        }
    $pastDateList = [];
    $futureDateList = [];
    $systemConfig = "";

    class date {

        public $category_id;
        public $name;
        public $date;
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

    // table date
    $selectDateSql = "select category_id, name, date, visible from date where visible = 1 order by date asc ";
    $selectDateSqlStmt = $pdo->prepare($selectDateSql);
    $selectDateSqlStmt->execute();
    $selectDateResult = $selectDateSqlStmt->fetchAll(PDO::FETCH_CLASS, "date");
    // var_dump($result);

    $now = new DateTime(); // utc I think 20160913

    foreach ($selectDateResult as $date) {
        if (new DateTime($date->date) < $now) {
            array_push($pastDateList, $date);
        } else {
            array_push($futureDateList, $date);
        }
    }

    // table system_config
    $selectSysConfigSql = "select num_timer, slide_show_sec, timer_show_sec, show_past_times, time_type from system_config where id=(select max(id) from system_config) LIMIT 1";
    $selectSysConfigSqlStmt = $pdo->prepare($selectSysConfigSql);
    $selectSysConfigSqlStmt->execute();
    $selectSysConfigResult = $selectSysConfigSqlStmt->fetchAll(PDO::FETCH_CLASS, "config");
    $config = $selectSysConfigResult[0];
    ?>

<html>
    <head>
        <link rel="shortcut icon" href="">

        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="public_html/js/models.js"></script>

        <script type ="text/javascript">

            var timerIntervals = [];
            var timerTemplateList = [];
            var config; // comes from setting/config. 

            $(document).ready(function () {
                var pastDateList = <?php echo json_encode($pastDateList); ?>;
                var futureDateList = <?php echo json_encode($futureDateList); ?>;
                var config = <?php echo json_encode($config); ?>;


                console.log(config);
                console.log(config.num_timer);
                console.log(config.show_past_times);


                for (var i = 0; i < config.num_timer; i++) {
                    var template = createSimpleTimerTemplate("timerContainer", i);
                    document.getElementById("timer_title" + i).innerHTML = futureDateList[i].name;
                    document.getElementById("timer_date" + i).innerHTML = futureDateList[i].date;
                    timerTemplateList.push(template);


                }

                var interval = setInterval(function () {
                    setTimerValues(futureDateList, config.num_timer, config.time_type);
                }, 1000);
                timerIntervals.push(interval);
            });


            function createTimerTemplate(parentElemId, index) {
                var parent = document.getElementById(parentElemId);
                var template = document.createElement('DIV');

                template.dataset.sequence = index;
                template.id = "timer" + index;

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

            function createSimpleTimerTemplate(parentElemId, index) {
                var parent = document.getElementById(parentElemId);
                var template = document.createElement('DIV');

                template.dataset.sequence = index;
                template.id = "timer" + index;

                var title = document.createElement('DIV');
                title.id = 'timer_title' + index;
                title.class = 'timer_title';
                title.style.color = '#8678E9';

//                var titleText = document.createTextNode(DueDate.name);
//                title.appendChild(titleText);

                var date = document.createElement('DIV');
                date.id = 'timer_date' + index;
                date.class = 'timer_date'
                date.style.color = '#867800';
//                var dateText = document.createTextNode(DueDate.date);
//                date.appendChild(dateText);

                var countdown = document.createElement('DIV');
                countdown.id = 'timer_countdown' + index;
                countdown.class = 'timer_countdown';

                template.appendChild(title);
                template.appendChild(date);
                template.appendChild(countdown);

                parent.appendChild(template);
                return template;
            }

            function setTimerValues(dbDateList, numTimer, timeType) {

                for (var i = 0; i < numTimer; i++) {
                    writeTime(document.getElementById("timer_countdown" + i), getJSDate(dbDateList[i].date), timeType);
                    // same with dtStringToJSDate(dbDateList[i].date) why ?
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

            function getJSDate(dbDateTime) {
                // ******* dbDateTime type should be time stamp
                return  new Date(Date.parse(dbDateTime.replace('-', '/', 'g')));
            }

            function writeTime(element, jsDate, mode) {
                
                var time = new TimeDHMS(jsDate);
                if (mode === "dhms") {

                    element.innerHTML = getStringValue(time.day, "day") + " " + time.hour + "h " + time.minute + "m " + time.second + "s";
                }
                if (mode === "ymdhms") {
                    console.log(time.day%365);
                    var MonthAndDay = new getMonthAndDay(new Date(), jsDate, time.day % 365);
                    
                    element.innerHTML = getStringValue(Math.floor(time.day/365), "year") + " "
                                      + getStringValue(MonthAndDay.monthCount, "month") + " "
                                      + getStringValue(MonthAndDay.daysLeft, "day") + " "
                            + time.hour + "h " + time.minute + "m " + time.second + "s";
                }

                function getStringValue(value, unit) {
                     if (value === 1) {
                        return value.toString() + " " + unit;
                    } else if(value > 1){
                        return value.toString() + " " + unit + "s";
                    } else {
                        return "";
                    } 
                }



                function getMonthAndDay(fromDate, toDate, daysLeft) {
                    
                    var daysInMonthArr = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                    this.monthCount = 0;
                    this.daysLeft = 0;
                    
                                      
                    var futureMonthIndex = toDate.getUTCMonth();
                    var currentMonthIndex = fromDate.getUTCMonth();

                          var monthCnt = 0;
                          var daysInMonthCnt = 0;
                          for(var i = currentMonthIndex+1; i< 12; i++ ){
                              monthCnt += 1;
                              daysInMonthCnt += daysInMonthArr[i];
                          }
                          for(var i = 0; i<futureMonthIndex; i++){
                              monthCnt += 1;
                              daysInMonthCnt += daysInMonthArr[i];
                          }
                          
                          var daysInCurrentMonth = daysInMonthArr[currentMonthIndex];
                          var daysLeftTmp = daysLeft - daysInMonthCnt;
                          
                          if(daysLeftTmp > daysInCurrentMonth){
                              monthCnt +=1;
                              daysInMonthCnt +=daysInCurrentMonth;
                          }
                          
                          this.monthCount = monthCnt;
                          this.daysLeft = daysLeft - daysInMonthCnt;

                }
            }


        </script>

        <style type="text/css">
            div.container{
                font-size: 4em;
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