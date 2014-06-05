<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Андрей
 * Date: 28.05.14
 * Time: 9:48
 * To change this template use File | Settings | File Templates.
 */
require_once 'helper.php';
$db=new DbMySQL();



function get_pages($page){
   // $db=new DbMySQL();
    //$count = $count[0]['count'];

   // $pages = ceil($count/NUM_IN_PAGE);//определим количество страниц
   // if($pages>1){
   //     for($i=1;$i<=$pages;$i++){
  //          echo '<a href="javascript:ajax_get_data(' . $page-1 . ');">'.$i.' </a>';//ajax_get_data будет выводить данные
  //  }
  //  }
}



if (isset($_POST['req'])){

    if ( isset($_POST['page']) ) {
        $page = (int)$_POST['page'];
        if ( $page < 1 ) $page = 1;
    } else {
        $page = 1;
    }



    if ( isset($_POST['date']) ) {
        $Seldata = "'".$_POST['date']."'";
        $now='DATE('.$Seldata.')';
    }else $now = 'DATE(NOW())';

    if ( isset($_POST['from']) ) {
        if ($_POST['from']!=''){
            if ($_POST['from'][0]=='%') {$percent='';} else {$percent='%';}

        $fromdata = "'". $percent .$_POST['from']."'";
        $from = " AND src LIKE " . $fromdata;
        } else $from='';
    }else $from='';

    if ( isset($_POST['to']) ) {
        if ($_POST['to']!=''){
            if ($_POST['to'][0]=='%') {$percent='';} else {$percent='%';}

            $todata = "'". $percent .$_POST['to']."'";
            $to = " AND dst LIKE " . $todata;
        } else $to='';
    }else $to='';


    if ( isset($_POST['sort']) ) {
        if ($_POST['sort']='asc'){
            $sort = ' ASC ';

        }

        if ($_POST['sort']='desc'){
            $sort = ' DESC ';

        }
        if ($_POST['sort']=''){
            $sort = '';

        }
    }else $sort='';


    //$now = 'DATE(\'2014-06-03\')';
   // $total = $db->select("SELECT count(*) as CNT FROM cdr WHERE STR_TO_DATE(calldate,'%Y-%m-%d')=$now AND dst in ('788032','788040','788033','788031','788113','788012') ");
$total=$db->select("
SELECT count(*) as CNT FROM
(SELECT calldate,src,dst,disposition,duration,recordingfile,uniqueid FROM cdr WHERE CAST(calldate as date)=$now
)as TAB WHERE
 dst in ('788032','788040','788033','788031','788113','788012')");
    $cnt_pages = ceil( $total[0]['CNT'] / ITEMS_PER_PAGE );
    if ( $page > $cnt_pages ) $page = $cnt_pages;
// Начальная позиция
    $start = ( $page - 1 ) * ITEMS_PER_PAGE;

//$now = 'DATE(NOW())';

//echoPage($page,$cnt_pages);

  ShowTable($db->select("
SELECT calldate,src,dst,disposition,duration,recordingfile,uniqueid FROM
(SELECT calldate,src,dst,disposition,duration,recordingfile,uniqueid FROM cdr
WHERE CAST(calldate as date)=$now

)as TAB WHERE
 dst in ('788032','788040','788033','788031','788113','788012') ". $from . $to ." ORDER by CAST(calldate as datetime) ". $sort . " LIMIT ".$start.", ".ITEMS_PER_PAGE));


  //  foreach ($db->select("SELECT calldate,src,dst,disposition,duration,recordingfile,uniqueid FROM cdr WHERE DATE(CAST(calldate as date))=$now AND dst in ('788032','788040','788033','788031','788113','788012') ORDER by uniqueid LIMIT ".$start.", ".ITEMS_PER_PAGE ) as $key=>$data){

 echoPage($page,$cnt_pages);

}