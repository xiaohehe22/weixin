<?php
/**
 * Created by PhpStorm.
 * User: CC
 * Date: 2016/8/8
 * Time: 17:00
 */

include_once ('functions.php');
$conn=db_connect();
mysqli_query($conn,"set names 'utf8'");
$query="insert into qixi(name,school,phone) values ('测试','测试','123456')";
$result=$conn->query($query);

