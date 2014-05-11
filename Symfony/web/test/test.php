<html xmlns="http://www.w3.org/1999/xhtml" lang="ru">
  <head>
    <meta charset="utf-8">
  </head>
  <body>  
    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", "On");
    define("WORKPATH", "C:\\www\\elibre_data\\test\\");

    echo "The OS is \"" . PHP_OS . "\"";
    echo "<br>Server software is \"" . $_SERVER['SERVER_SOFTWARE'] . "\"";
    echo "<br>DIRECTORY_SEPARATOR is \"" . DIRECTORY_SEPARATOR . "\"";

    exit;

    $dirname = WORKPATH . "Тестова тека " . time();
    var_dump($dirname);
    echo "<br>";

    $dirname_enc = mb_convert_encoding($dirname, "windows-1251", "utf-8");
    var_dump($dirname_enc);
    echo "<br>";

    $res = mkdir($dirname_enc);

    echo "<br>";
    var_dump($res);
    ?>
  </body>
</html>