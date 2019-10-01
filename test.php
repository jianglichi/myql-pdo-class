<?
/*
寫這個class是因為我懶得
每次跑sql 都要 prepare + bind 一大堆參數 



SQL 二種自動bind 方式 

一、指定要bind的變數名
  example:
  select * from test where id=:newid
  newid 即為自動bind的變數
  
二、table欄位和變數名稱相同時 只要用 ? 就可以自動bind
  (有人覺得有安全考量那就用第1種方式)
  
  example:
  select * from test where id=?
  會自動 bind id 這個變數
  
  

可自動bind三種變數 : 一般變數  $_GET  $_POST

*/


include 'class/CDbshell_Pdo.php';
$_db = new CDbshell_Pdo;

$dsn = "mysql:host=localhost;dbname=test;charset=utf8";
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 
//記得指定db username password
$_db->dbh = new PDO($dsn, 'user', 'user_password', $options);	


//範例一
//指定要bind的變數名稱  在：之後
$newid = 5;
$sql = "select * from test where id=:newid";
$_db->query($sql);
$tmp = $_db->fetch_array();
var_dump($tmp);



//範例二 
//使用 ? 程式會直接bind與欄位相同名稱的參數
$id = 1;
$sql = "select * from test where id=?";
$_db->query($sql);
$tmp = $_db->fetch_array();
var_dump($tmp);