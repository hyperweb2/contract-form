<?php

namespace Hw2CF;

/* @var $pdf \Hw2CF\PdfTemplate */
$pdf = $this;
?>

<html>
    <head>
        <meta http-equiv=Content-Type content="text/html; charset=UTF-8">
        <style>
            body {
                font-size: 16px;
                font-weight: 400;
                text-rendering: optimizeLegibility;
                width: 100%;
                /* 
                   this margin change the space between header and footer 
                   page
                */
                margin: 130px auto 100px;
            }        


            #footer { 
                position: fixed; 
                left: 0px; 
                right: 0px; 
                bottom: -100px;
                width: 100%;
                height: 150px; 
            }

            #footer .page:after { content: counter(page/*, upper-roman*/); }

            #content {
                /*page-break-before: always;*/
                width: 100%;
            }

            table {
                border-style: solid;
                border-width: 1px;
            }

            .signature {
                width:150px;
                height: 100px;
            }

        </style>
    </head>
    <body>
        <div id="footer">
            <center>
                <p class="page"></p>
            </center>
        </div>


        <div id="content">
            <?php
                // print your fields here
            ?>
        </div>

    </body>
</html>
