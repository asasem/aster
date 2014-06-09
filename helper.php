<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Андрей
 * Date: 26.05.14
 * Time: 13:47
 * To change this template use File | Settings | File Templates.
 */
require_once 'config.php';
require_once 'Dbmysql.php';

function convdate($date_time_string) {
    date_default_timezone_set('Europe/Moscow');
    $dt_elements = explode(' ',$date_time_string);
    $date_elements = explode('-',$dt_elements[0]);
    $time_elements =  explode(':',$dt_elements[1]);
    $timestamp= mktime($time_elements[0],$time_elements[1],$time_elements[2],$date_elements[1],$date_elements[2],$date_elements[0]);
    return $timestamp;
}

function insDate($Str){
    $S = substr_replace($Str, '-', 4, 0);
    $Ss = substr_replace($S, '-', 7, 0);
    return $Ss;

}

function insTimes($Str){
    $S = substr_replace($Str, ':', 2, 0);
    $Ss = substr_replace($S, ':', 5, 0);
    return $Ss;

}

function getFullPath($Str,$full=false,$DIR_SIP='/'){
    $S = substr($Str,0,4);
    $Ss =  substr($Str,4,2);
    $Sss = substr($Str,6,2);
    if ($full == true){
        return $DIR_SIP.$S.$DIR_SIP.$Ss.$DIR_SIP.$Sss.$DIR_SIP;
    }
    else
    {
        return  $DIR_SIP.$S.$DIR_SIP.$Ss.$DIR_SIP.$Sss.$DIR_SIP.$Str;
    }

}


function parceFilename($Filename){
    $extension = end(explode(".", $Filename));
    $array = explode("-", $Filename);
    $p= pathinfo($array[5], PATHINFO_FILENAME);
    $array[5] = $p;
    array_push($array,getFullPath($array[3],true));
    $array[3] = insDate($array[3]);

    $array[4] = insTimes($array[4]);

    array_push($array,$extension);
    array_push($array,$Filename);
    array_push($array,$array[6].$Filename);
    return $array;
}

/*-----interface -----*/

function echoPage($page,$cnt_pages){
    if ( $cnt_pages > 1 )
    {
        echo '<div style="margin:0 auto;width:250px;">&nbsp;Страницы: ';
        // Проверяем нужна ли стрелка "В начало"
        if ( $page > 3 )
            $startpage = '<a href="javascript:get_data(1)"><<</a> ... ';
        else
            $startpage = '';
        // Проверяем нужна ли стрелка "В конец"
        if ( $page < ($cnt_pages - 2) )
            // $endpage = ' ... <a href="'.$_SERVER['PHP_SELF'].'?page='.$cnt_pages.'&req=ok">>></a>';
            $endpage = ' ... <a href="javascript:get_data('. $cnt_pages .')">>></a>';
        else
            $endpage = '';

        // Находим две ближайшие станицы с обоих краев, если они есть
        if ( $page - 2 > 0 )
            // $page2left = ' <a href="'.$_SERVER['PHP_SELF'].'?page='.($page - 2).'&req=ok">'.($page - 2).'</a> | ';
            $page2left = ' <a href="javascript:get_data('.($page - 2) .')">'.($page - 2).'</a> | ';
        else
            $page2left = '';
        if ( $page - 1 > 0 )
            //$page1left = ' <a href="'.$_SERVER['PHP_SELF'].'?page='.($page - 1).'&req=ok">'.($page - 1).'</a> | ';
            $page1left = ' <a href="javascript:get_data('.($page - 1) .')">'.($page - 1).'</a> | ';
        else
            $page1left = '';
        if ( $page + 2 <= $cnt_pages )
            // $page2right = ' | <a href="'.$_SERVER['PHP_SELF'].'?page='.($page + 2).'&req=ok">'.($page + 2).'</a>';
            $page2right = ' | <a href="javascript:get_data('.($page + 2) .')">'.($page + 2).'</a>';
        else
            $page2right = '';
        if ( $page + 1 <= $cnt_pages )
            // $page1right = ' | <a href="'.$_SERVER['PHP_SELF'].'?page='.($page + 1).'&req=ok">'.($page + 1).'</a>';
            $page1right = ' | <a href="javascript:get_data('.($page + 1) .')">'.($page + 1).'</a>';
        else
            $page1right = '';

        // Выводим меню
        echo $startpage.$page2left.$page1left.'<strong>'.$page.'</strong>'.$page1right.$page2right.$endpage;

        echo '</div>';
    }


}

function ShowTableHeader (){

    echo '<table border="1" class="features-table">';
    echo ' <thead>

  <td colspan="4" class="data-sort">ПОИСК</td>

 </thead> <tbody>';

echo '
<tr>
<td>Откуда</td><td><input id="from" type="text" name="from" ></td>
<td>Куда</td><td><input id="to" type="text" name="to"></td>
</tr>
<tr>
    <td>От</td><td><input id="datepicker" name="date_from"  type="text" ></td>
    <td>По</td><td><input id="datepicker2" name=date_to type="text" ></td>
</tr>
<tr>
    <td>Только Answered</td><td><input name=oa type=checkbox ></td>
    <td>Фильтр по дате</td><td><input name=cd type=checkbox ></td>
</tr>
<tr>
    <td>Применить</td><td><input id="go" type="submit" value="Применить фильтр"></td>
    <td>Сбросить</td><td><input id="res" type="submit" value="Сбросить фильтр"></td>
</tr>';

    echo '</tbody><tfoot></tfoot></table>';

}

function ShowTable($arr){
    echo '<table border="1" class="features-table">';
    echo '

  <tr>
  <td class="data-sort" onclick="sort(this)">ДАТА ЗВОНКА</td>
  <td class="data-sort" onclick="sort(this)">ИСТОЧНИК</td>
  <td class="data-sort" onclick="sort(this)">НАЗНАЧЕНИЕ</td>
  <td class="data-sort" onclick="sort(this)">ДЛИТЕЛЬНОСТЬ</td>
  <td class="data-sort" onclick="sort(this)">СТАТУС</td>
   <td class="data-sort" >ЗАПИСЬ</td>

  </tr>

  ';

    foreach ($arr as $key=>$data){
        switch ($data['disposition'] ){
            case 'ANSWERED':$status = '<img src="ANSWERED.png" class="opis">О</img>';
                break;
            case 'BUSY': $status = '<img src="NOANSWER.png" class="opis">З</img>';
                break;
            case 'NO ANSWER': $status = '<img src="BUSY.png" class="opis">Н/А</img>';
                break;
        }

        echo '<tr> ';

        echo '<td class="grey">' . date( 'd.m, H:i', convdate("$data[calldate]")) . '</td>';

        echo '<td class="green">' . $data['src'] . '</td>';

        echo '<td class="grey">' . $data['dst'] . '</td>';
        $min = (int)$data['duration']/60;

        echo '<td class="green">' . round($min,2) .' мин.'. '</td>';

        echo '<td class="grey">' .$status . '</td>';

        if ($data['recordingfile'] !=null ) {

            $e = parceFilename($data['recordingfile']);
            //echo '/monitor'. $e[9];
            if ($data['disposition']=='ANSWERED'){
                echo '<td>' .
                    '<div class="player"><a href="javascript:doResume()" class="resume"  ><img src="pause.png"></a>' ;

                echo    '<a href="javascript:doPlay('.'\''.'/monitor'. $e[9] .'\''.')" class="play"><img src="play.png"></a>
                        <a href="javascript:doPause()" class="pause"><img src="pause.png"></a>
                       <a href="javascript:doStop()" class="stop"><img src="stop.png"></a>
                   <!-- <img class="download" src="download.png" alt="/monitor'. $e[9].'">-->
                    <a href="/monitor'. $e[9].'" class="download" type="application/octet-stream" download><img src="download.png"  ></a>
</div>'.'</td>';}

            else echo '<td class="grey"></td>';
        } else echo '<td class="grey"></td>';

        echo '</tr>';




    }
    echo '</table> ';


}

