<?php $title = 'OCR Test:' ?>
<html>
    <head>
    <title><?= $title ?></title>    
    <style>
        .blue{color:blue;}
        .green{color:green;}
        .red{color:red;}
        .block_chart{
            float: left;
            margin-right: 10px;
        }
    </style>
    </head>
    <body>
        <h1 class='blue'><?= $title ?> <img width="65" src="/fann.png" /></h1>
        <?php require_once "controller.php" ?>
        <h1 style="clear: both;"><?= $stringRes ?></h1>
    </body>
</html>