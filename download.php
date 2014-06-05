<?php
if (empty($_POST['f']))
    exit();
// тут проверка расширения файла например, что файл разрешен на скачивание
header('Content-Type: application/octet-stream');
readfile($_SERVER['SERVER_NAME'].$_POST['f']);
