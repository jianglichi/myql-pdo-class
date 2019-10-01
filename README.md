# myql-pdo-class
mysql-pdo connection class easy to bind parameter


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
  
  

