<?php

class DbMySQL
{
    
    // var $host         = "localhost";
    var $host         = HOST_DB;
    var $login        = LOGIN_DB;
    var $pass         = PASS_DB;
    var $name         = NAME_DB;
    static $db_link   = null;
    var $error_report = true;
    var $sql          = "";
    

    static function instance()
    {
        if (self::$db_link === NULL) {
            self::$db_link = new self();
        }
        return self::$db_link;
    }
	
    function __construct()
    {
        return $this->_connect();

    }
	
    function _connect()
    {
        @$this->db_link = mysql_connect($this->host, $this->login, $this->pass);
        if ($this->db_link){
            if(mysql_select_db($this->name)){
                mysql_query ("set names 'utf8'");
                mysql_query ("set character_set_results='utf8'");
                return true;
            }
        }
        return false;
    }

    function _query($tSql)
    {
        $this->sql = $tSql;
        $res = mysql_query($tSql, $this->db_link);
        if (mysql_errno($this->db_link)>0){
            $this->show_error();
        }
        return $res;
    }
    
    function select($tSql)
    {
        $result = array();
        $res = $this->_query($tSql, $this->db_link);

        if($res){
            while ($row = mysql_fetch_assoc($res)){
                $result[] = $row;
            }
        }else {
            return false;
        }

        return $result;
    }

    function select_row($tSql)
    {
        $result = array();
        
        $res = $this->_query($tSql, $this->db_link);
        if ($res){
            $tmp = mysql_fetch_assoc($res);
            if (is_array($tmp)){
                $result = $tmp;
            }
        }else{
            return false;
        }
        return $result;
    }

    function selectrow($q) 
    {
        return $this->select_row($q);
    }
    
    function selectone($q) 
    {
        $result = array();
        
        $res = $this->_query($q, $this->db_link);
        if ($res){
            $tmp = mysql_fetch_array($res);
            if (isset($tmp[0])){
                return $tmp[0];
            }
        }
        return false;
    }

    function query($tSql)
    {
        $res = $this->_query($tSql, $this->db_link);
        return $res;
    }
    
    function insert($tSql)
    {
        $res = $this->_query($tSql, $this->db_link);
        $last_insert_id = 0;
        if (mysql_errno($this->db_link)==0){
            $last_insert_id = mysql_insert_id($this->db_link);
        }
        return $last_insert_id;
    }

    function close()
    {
        return mysql_close($this->db_link);
    }
    
    function get_last_error()
    {
        return mysql_error($this->db_link);
    }
    
    function show_error()
    {
        if ($this->error_report){
            $str = $this->get_last_error();
            echo "Mysql error: [$str] in query: [{$this->sql}]\n";
        }else{
            //echo "While nothing...";
        }
    }
    
    function dropTable($table_name)
    {
        return $this->_query("DROP TABLE IF EXISTS `$table_name`");
    }
    
    function renameTable($oldName, $newName)
    {
        $q = 'RENAME TABLE `' . $oldName . '` TO `' . $newName . '`';
        return $this->_query($q);
    }

    function q($var)
    {
        return '\'' . mysql_real_escape_string($var, $this->db_link) . '\'';
    }

    // Функция экранирования переменных
    function quote_smart($value)
    {
        // если magic_quotes_gpc включена - используем stripslashes
        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }
        // Если переменная - число, то экранировать её не нужно
        // если нет - то окружем её кавычками, и экранируем
        if (!is_numeric($value)) {
            $value = "'" . mysql_real_escape_string($value) . "'";
        }
        return $value;
    }

    // Составляем безопасный запрос
    /*
    $query = sprintf("SELECT * FROM users WHERE user=%s AND password=%s",
            quote_smart($_POST['username']),
            quote_smart($_POST['password']));

    mysql_query($query);
    */
}
?>