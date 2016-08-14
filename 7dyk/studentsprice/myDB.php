<?php


class myDB {
    
    public function say() {
    	return "hello";
    }
    
    /**
     * 连接数据库
     * @return resource
     */
    public function connect() {
        $conn = mysql_connect(DB_HOST,DB_USER,DB_PWD) or die("数据库连接失败Error:".mysql_errno().":".mysql_error());
        mysql_set_charset(DB_CHARSET);
        mysql_select_db(DB_DBNAME) or die("打开失败");
        return $conn;
    }
    
    
    /**
     * 完成记录插入操作
     * @param $table
     * @param $array
     * @return int
     */
    public function insert($table,$array) {
        $keys = join(",",array_keys($array));
        $vals = "'".join("','",array_values($array))."'";
        $sql = "insert into {$table}($keys) values({$vals})";
        mysql_query($sql);
        return mysql_insert_id();
    }
    
    /**
     * 记录更新操作
     * @param $table
     * @param $array
     * @param null $where
     * @return int
     */
    public function update($table,$array,$where=null) {
        $str=null;
        foreach($array as $key=>$val) {
            if($str==null) {
                $sep="";
            } else {
                $sep=",";
            }
            $str.=$sep.$key."='".$val."'";
        }
        $sql = "update {$table} set {$str}".($where==null?null:" where ".$where);
        echo $sql;
        mysql_query($sql);
        return mysql_affected_rows();
    }

    /**
     * 删除记录
     * @param $table
     * @param null $where
     * @return int
     */
    public function delete($table,$where=null) {
        $where = ($where==null?null:"where ".$where);
        $sql = "delete from {$table} {$where}";
        mysql_query($sql);
        return mysql_affected_rows();
    }
    
    /**
     * 得到一条记录
     * @param $sql
     * @param int $result_type
     * @return array
     */
    public function fetchOne($sql,$result_type=MYSQL_ASSOC) {
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result,$result_type);
        return $row;
    }
    
    /**
     * 得到所有结果
     * @param $sql
     * @param int $result_type
     * @return array
     */
    public function fetchAll($sql,$result_type=MYSQL_ASSOC) {
        $result = mysql_query($sql);
        $rows = null;
        while(@$row=mysql_fetch_array($result,$result_type)) {
            $rows[]=$row;
        }
        return $rows;
    }
    
    /**
     * 得到结果集中记录条数
     * @param $sql
     * @return int
     */
    public function getResultNum($sql) {
        $result = mysql_query($sql);
        return mysql_num_rows($result);
    }
    
    /**
     * 执行mysql语句
     * @param $sql
     * @return resource
     */
    public function query($sql){
        return mysql_query($sql);
    }
}
