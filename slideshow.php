<?php

class image {
    public $file;
}

require('config.php');

//$image_id = 2;
//$sql = "SELECT file FROM image WHERE id=:id";
$sql = "SELECT file FROM image where visible =1";
$query = $pdo->prepare($sql);
//$query->execute(array(':id' => $image_id));
$query->execute();
//$query->bindColumn(1, $name, PDO::PARAM_STR);
//$query->bindColumn(2, $image, PDO::PARAM_LOB);
//$result = $query->fetchAll(PDO::FETCH_BOUND);
//$result = $query->fetchAll(PDO::FETCH_ASSOC);
$result = $query->fetchAll(PDO::FETCH_CLASS, 'image');
foreach ($result as $row) {
    $row->file = base64_encode($row->file);
}

?>

<!DOCTYPE html>
<html>

    <head>
        <link rel="shortcut icon" href="">

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!--        <script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/4.0/1/MicrosoftAjax.js"></script>
        <script src='//cdn.jsdelivr.net/sharepointplus/3.0.10/sharepointplus.min.js' type='text/javascript'></script>-->

        <!-- config file (json) -->
       
        <script src="public_html/js/storage.js"></script>
        <script type="text/javascript">

            var images;

            $(document).ready(function () {
               
                // 1. parse config.json
                $.getJSON("resources/config.json", function(data){
                    $.each(data, function(key, val){
                        console.log(key, val);
                    });
                });
                // 2. save config to localStorage
                // 
               
               
                images = <?php echo json_encode($result); ?>;
                var list = [];
                for(var i = 0; i<images.length; i++){
                    list.push("data:image/jpeg;base64,"+ images[i].file);
                }              
                console.log(list);        
                        
                var img_container = document.getElementById("img_container");
                for (i = 0; i < list.length; i++) {
                    var img = document.createElement('IMG');
                    img.setAttribute("src", list[i]);
                    img.setAttribute("id", "img" + i);
                    img.setAttribute("class", "mySlides w3-animate-fading");
                    img.setAttribute("width", "100%");
                    //img.style.cssText = "max-height:1080px;max-width:1920px;";
                    /*margin-left:auto;margin-right:auto;top:0; left:0; right:0; bottom:0;overflow:hidden;*/
                    img_container.appendChild(img);
                }

                //console.log(list.toString());

                //window.location.assign("timer.html");
                var myIndex = 0;

                var slideShowTime = 5000;
                var timerShowTime = 30000;

              
                var timeout;
                var imgs = document.getElementsByClassName("mySlides");
                console.log(imgs.length);

                slide();

                function slide() {

                    var i;


                    for (i = 0; i < imgs.length; i++) {
                        imgs[i].style.display = "none";
                    }

                    if (myIndex < imgs.length) {
                        imgs[myIndex].style.display = "block";
                        document.getElementById("timer_container").style.display = "none";
                        timeout = setTimeout(slide, slideShowTime);
                        myIndex++;

                    } else if (myIndex >= imgs.length) {

                        myIndex = 0;
                        clearTimeout(timeout);
                        document.getElementById("timer_container").style.display = "block";
                        timeout = setTimeout(slide, timerShowTime);
                    }
                }

    

            }


            );


            // program flow precedence 
            // 1. read config file
            // 2. read uri
            function getFiles(url) {
                var executor = new SP.RequestExecutor(url);
                executor.executeAsync({
                    url: url,
                    method: "GET",
                    seccess: successHnadler,
                    error: errorHandler
                });
            }



        </script>

        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
        <style>

            * {
                box-sizing: border-box;
            }
            /*                        .fadein_element
                                    {
                                        opacity: 0;
                                        animation: fadein ease-in 5s;
                                    }
                                    @keyframes fadein{
                                        from{
                                            opacity: 0;
                                        }
                                        to{
                                            opacity: 1;
                                        }
                                    }*/

            ::-webkit-scrollbar { 
                display: none; 
            }

            @-webkit-keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }  
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            body{
                height: 100%;
                margin: 0;
                padding: 0;
            }
            div{
                max-width:1920px;max-height:1080px;width:100%;height:100%;
            }
        </style>
        <title>
        </title>    

    </head>
    <body>
        <?php
         echo $_SERVER['HTTP_HOST'];
         echo "<script>console.log($_SERVER.$GLOBALS);</script>";
        ?>
        <div id = "container" style="">
            <div id="config" style="position:absolute; z-index: 1;text-align: right;margin: 0; padding:10px;"><input style="width:5%; max-width: 40px;outline:none;" type="image" src="https://s19.postimg.org/jd32a42f7/config.png" alt="config"/></div>
            <!--position:relative;overflow:hidden; -->
            <div id="img_container" style="position:absolute;overflow:hidden;margin:auto;">
                <!--padding:10px;max-height:100%;overflow:hidden;vertical-align:middle; text-align:center;position:relative;overflow:auto; -->

            </div> 
            <div id="timer_container" style="position:absolute;margin: auto;-webkit-animation: fadeIn 5s;animation: fadeIn 5s;" >
                <!--width:50%;height:100%;overflow:hidden;display:none;position:absolute; -->
                <object id="timer" type="text/html" data="timer.php"
                        style="width: 100%;height:100%;">
                    <!-- max-width: 100%;
                         max-height: 100%; overflow:hidden;  -->
                </object>

            </div>

        </div>
    </body>





</html>

