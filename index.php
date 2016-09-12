<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>SIMS Project</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <style>
            html{
            }
            body{
                width:100%;height:100%;
                font-family: Impact, Charcoal, sans-serif;
            }
            div.vcenter{
            }
            .container{
                display:                 flex;
                display:                 -webkit-flex; /* Safari 8 */
                flex-wrap:               wrap;
                -webkit-flex-wrap:       wrap;         /* Safari 8 */
                justify-content:         center;
                -webkit-justify-content: center;       /* Safari 8 */

            }

            .container div {
                width:              150px;
                height:             150px;
                background-color:   #cccccc;

                margin:auto;
                display: flex;
                justify-content: center;
                align-items: center;

                position: relative;
                display:inline-block;
                width:400px;height:400px;
                padding:20px;
            }
            div.wrapper {

            }
            div.section{
                padding: 25px;
                margin: auto 0px auto 0px ;
                background-repeat: no-repeat;
                background-size: auto 100%;
                background-position: center center;
                background-origin: content-box;
            }  
            div.section:hover{
                
                opacity: 0.5;
                filter: alpha(opacity=50);  
                /*For IE8 and earlier*/ 
            }
            div.section a {
                color: #f9f8f1;
                display:block;width:100%;height:100%;font-size:10em;text-decoration: none;
            } 
            div.section a:hover{
                color:  #000000;

            }
            div #simsshow:hover{
                background-image: url('public_html/img/content/play.png');

            }
            div #clock:hover{
               
                background-image: url('public_html/img/content/clock_face.png');


            }
            div #config:hover{
                
                background-image: url('public_html/img/content/home_gear.png');
            }
           
            div #sims:hover p a{
                transition: color 1.5s ease;
                color:#fffff;
            }
            p{
                line-height:400px;margin:0px;
            }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script>
        
        </script>
    </head>
    <body>
        <?php
// include 'config.php';     
        
require('config.php');
//$sql = "";
//mysql_query($sql) or die('Error, delete query failed');


// config.php custom function. 
// $db_conn = connectDB();
// if ($db_conn->connect_error) {
//    die("Connection failed: " . $db_conn->connect_error);
// } 
// echo("<script>console.log($db_conn->server_version);</script>");


        ?>
        <div class="container" style="position: absolute; width:99%;height:95%;text-align:center;vertical-align: middle;">
            <div id="simsshow" class="section" style="background-color:#2FAACE">
                <p><a href="slideshow.php" style="">S</a></p></div>
            <div id="clock" class="section" style="background-color: #4985D6;">
                <p><a href="timer.php" style="">I</a></p></div>
            <div id="config" class="section" style="background-color: #8ADCFF;">
                <p><a href="system_config.php" style="">M</a></p></div>
            <div id="sims" class="section" style="background-color: #8678E9;">
                <p><a href="" style="" onmouseover="this.innerHTML = 'SIMS'" onmouseout="this.innerHTML='S'">S</a></p></div>

        </div>
    </body>
</html>

