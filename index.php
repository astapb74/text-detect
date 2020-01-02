<?php $title = 'OCR Test:' ?>
<html>
    <head>
    <title><?= $title ?></title>    
    <link rel="stylesheet" href="/main.css">
    </head>
    <body>
        <h1 class='blue'><?= $title ?></h1>
        <ul>
            <li>
                <a href="/?filename=fann.png">
                    <img width="65" src="/fann.png" />
                </a>
            </li>
        </ul>
        <?php if (!empty($_GET['filename'])): ?>    
            <?php require_once "controller.php" ?>
        <?php endif; ?>    
        <h1 style="clear: both;"><?= $stringRes ?></h1>
    </body>
</html>