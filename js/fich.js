/**
 * Created with JetBrains PhpStorm.
 * User: Андрей
 * Date: 26.05.14
 * Time: 15:52
 * To change this template use File | Settings | File Templates.
 */
function _getDate(){
    var d = new Date();
    var curDay = (d.getDate()>=10)?d.getDate():"0"+d.getDate();
    var curMonth = (d.getMonth()>=10)?d.getMonth()+1:"0"+(d.getMonth()+1);
    var curYear = d.getFullYear();
    return curYear +"-"+curMonth+"-"+curDay;
}

function sort(el) {
    var col_sort = el.innerHTML;
    var tr = el.parentNode;
    var table = tr.parentNode.parentNode.lastChild;//tr.parentNode;
    var td, arrow, col_sort_num;

    for (var i=0; (td = tr.getElementsByTagName("td").item(i)); i++) {
        if (td.innerHTML == col_sort) {
            col_sort_num = i;
            if (td.prevsort == "y"){
                arrow = td.firstChild;
                el.up = Number(!el.up);
            }else{
                td.prevsort = "y";
                arrow = td.insertBefore(document.createElement("span"),td.firstChild);
                el.up = 0;
            }
            arrow.innerHTML = el.up?"▴ ":"▾ ";
        }else{
            if (td.prevsort == "y"){
                td.prevsort = "n";
                if (td.firstChild) td.removeChild(td.firstChild);
            }
        }
    }

    var a = new Array();

    for(i=0; i < table.rows.length; i++) {
        a[i-1] = new Array();
        a[i-1][0]=table.rows[i].getElementsByTagName("td").item(col_sort_num).innerHTML;
        a[i-1][1]=table.rows[i];
    }

    a.sort();
    if(el.up) a.reverse();

    for(i=0; i < a.length; i++)
        table.appendChild(a[i][1]);

}


function get_data(page,date,from,to,answer){
    date = date || _getDate();
    from = from || '';
    to = to || '';
    answer = answer || '';

    $.post("table.php",{date:date,page:page,from:from,to:to,answer:answer,req:'ok'}, function(data){
        $('#content').html(data);






        $('a.resume').hide();

        $('a.play').click(function() {


            $(this).hide();

            $(this).prev().show();

            $(this).prev().children('img').attr('src','note.png');


        });


        $('a.stop').click(function() {

            $('a.play').show();
            $('a.resume').hide();


        });

        $('a.resume').click(function() {
            $(this).children('img').attr('src','note.png');
        });
        $('a.pause').click(function() {
            // $(this).hide();
            //$(this).prev().show();
            $(this).prev().prev().children('img').attr('src','play.png');


        });
        $('img.download').click(function(){
            var f = $(this).attr('alt');
            $.post("download.php",{f:f});


        });
      /*  var count =0;
        $(".data-sort").click(function (){





            if (($(this).hasClass("data-sort") && count == 0)){
                $(this).addClass("headerSortUp");
                //get_data(1,_getDate(),'','','desc');
               // sort($("td.data-sort"));
            }

            if ($(this).hasClass("headerSortUp") && count == 1){
                $(this).removeClass("headerSortUp").addClass("headerSortDown");
                //get_data(1,_getDate(),'','','asc');
               // sort($("td.data-sort"));
            }

            if ($(this).hasClass("headerSortDown")&& count == 2){
                $(this).removeClass("headerSortDown").addClass("headerSortUp");


            }
            count++;

            if (count == 3){

                $(this).removeClass("headerSortUp headerSortDown");
                count=0;
                //get_data(1,'','','','');
            }



        });*/




    });






}

$(document).ready(function() {

    $('input#datepicker').keydown(function(e){
       /* switch (e.keyCode) {
            case 8:  // Backspace

            case 9:  // Tab
            case 13: // Enter
            case 37: // Left
            case 38: // Up
            case 39: // Right
            case 40: // Down
                break;

            default:


        }*/
        return false;
    });



    $('input#datepicker').datepicker({
        showOn:'both',

        buttonImage: 'datepicker.gif',
        maxDate:'+0d',
        onClose: function(dateText, inst) {
            var nowdate = _getDate();
            //if ($(this).val() nowdate)


        }
    });
    $('input#datepicker2').datepicker({
        showOn:'both',
        buttonImage: 'datepicker.gif',
        onClose: function(dateText, inst) {


        }
    });

get_data(1);
    res();
    $('input#datepicker').val(_getDate());
    $("input[name='oa']").click(function(){
        if ($(this).is(':checked')){
            $(this).attr('checked','checked');
        }else $(this).removeAttr('checked');

    });



    $("#go").click(function(){
        var dd =  $("input#datepicker").val();
        var fr = $("#from").val();
        var to = $("#to").val();
        var oa = $("input[name='oa']").is(":checked");

var answ = oa?'yes':'no';
        get_data(1,dd,fr,to,answ);

    });

    $("#from").keypress(function(e){
        if (e.keyCode==13){

            $("#go").click();
        }

    });
    $("#to").keypress(function(e){
        if (e.keyCode==13){

            $("#go").click();
        }

    });


    function res(){
        $("input#datepicker").val(null);
        $("input#datepicker2").val(null);
        $("#from").val(null);
        $("#to").val(null);

    }

    $("#res").click(function(){
        res();

    });






});
