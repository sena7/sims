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
            }
            div{
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

                position: relative;display:inline-block;width:880px;height:400px;padding:20px;
            }
            div.section{
            }  
            div.section:hover{
                /*border:1px solid #009966;*/
                opacity: 0.5;
                filter: alpha(opacity=50); /* For IE8 and earlier */
                background-repeat: no-repeat;
                background-size: auto 100%;
                background-position: center center;
            }
            div.section a {
                color: darkblue;
                display:block;width:100%;height:100%;font-size:10em;text-decoration: none;
            } 
            div #simsshow:hover{
                background-image: url('public_html/img/content/home_reel.png');
            }
            div #clock:hover{
                background-image: url('public_html/img/content/clock.png');
            }
            div #config:hover{
                background-image: url('public_html/img/content/gear.png');
            }
            div #moments:hover{
                background-image: url('public_html/img/content/team01.jpg');
            }
        </style>
    </head>
    <body>
        <?php
        ?>
        <div class="container" style="position: absolute; width:99%;height:95%;text-align:center;vertical-align: middle;">
            <div id="simsshow" class="section" style="background-color: antiquewhite;">
                <a href="slideshow.php" style=" ">S</a></div>
            <div id="clock" class="section" style="background-color: lightcoral;">
                <a href="" style="">I</a></div>
            <div id="config" class="section" style="background-color: darkgrey;">
                <a href="" style="display:block;width:100%;height:100%;font-size:10em;text-decoration: none;">M</a></div>
            <div id="moments" class="section" style="background-color: thistle;">
                <a href="" style="">S</a></div>

        </div>
    </body>
</html>

