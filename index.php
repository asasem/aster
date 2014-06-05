
<html>
<head>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

    <script  src="/js/datepicker.js"></script>
    <script type="text/javascript" src="/js/player.js"></script>

    <script type="text/javascript" src="/js/fich.js"></script>

</head>
<?php require_once 'helper.php';?>
<body bgcolor="#F5F3DF" onload="init()">

<?php



ShowTableHeader();


?>
<div id="headtable">

   <?php echo '<table border="1" class="features-table">';
        echo ' <thead>
        <tr>
            <td>ДАТА ЗВОНКА</td>

            <td nowrap="" class="data-sort">ИСТОЧНИК</td>
            <td class="data-sort">НАЗНАЧЕНИЕ</td>
            <td class="data-sort">ДЛИТЕЛЬНОСТЬ</td>
            <td class="data-sort">СТАТУС</td>
            <td class="data-sort">ЗАПИСЬ</td>

        </tr>
        </thead> <tbody></tbody></table>';?>

</div>
<div id="content"></div>
<div id="InfoFile"></div>
<div id="InfoSound"></div>
<div id="Player">
<div id="InfoState"></div>
</div>

<object
    width="300"
    height="100"
    id="haxe"
    align="middle">
<embed src="asterplayer.swf?gui=none&h=20&w=300&"
           bgcolor="#F5F3DF"
           width="300"
           height="100"
           name="haxe"
           quality="high"
           align="middle"
           scale="noscale"
           allowScriptAccess="always"
           type="application/x-shockwave-flash"
           pluginspage="http://www.macromedia.com/go/getflashplayer"
        />
</object>
</body>
</html>


