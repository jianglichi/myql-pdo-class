<?
/*
簡易方便的 mysql_pdo 
自動bind 三種變數
一般變數  $_GET  $_POST

*/


class CDbshell_Pdo {
	var     $dbh    =       0;
	var     $rs     =       0;



	function query($sqlstr,$value) {
		global $_SERVER;
		$this->rs = $this->dbh->prepare($sqlstr);
		
		
		if($value){
			$this->rs->execute($value);
		}else{
			// ?? 參數  post 或 get 與 sql 欄位 同名時 使用
			preg_match_all("/[a-zA-Z]*[\s]*=[\s]*\?/isU",$sqlstr,$matches);
			if($matches[0]){
				for($i=0;$i<count($matches[0]);$i++){
					$matches[0][$i] = trim(str_replace(' ','',$matches[0][$i]));
					$tmp = explode('=',$matches[0][$i]);
					$v = $tmp[0];
					global $$v;
					
					if(isset ($_POST[$v])){
						$value[] = $_POST[$v];
					}elseif(isset ($_GET[$v])){
						$value[] = $_GET[$v];
					}elseif(isset ($$v)){
						$value[] = $$v;
					}else{
						echo 'sql 參數錯誤';
						exit;
					}
				}
			}
			
			//新增
			if(substr(strtolower(str_replace('  ',' ',substr($sqlstr,0,18))),0,11)=='insert into'){
				preg_match_all("/\?/isU",$sqlstr,$matches);
				if($matches[0]){
					preg_match_all("/\((.*)\)/isU",$sqlstr,$matches1);
					$tmp = explode(',',$matches1[1][0]);
					$tmp1 = explode(',',$matches1[1][1]);
					
					for($i=0;$i<count($tmp1);$i++){
						$varname = trim($tmp1[$i]);
						if($varname=='?'){
							$v = trim($tmp[$i]);
							if($_POST[$v]){
								$value[] = $_POST[$v];
							}elseif($_GET[$v]){
								$value[] = $_GET[$v];
							}elseif($$v){
								$value[] = $$v;
							}else{
								echo 'sql 參數錯誤';
								exit;
							}
							
						}
					}
				}
			}
			
			
			//:bind
			//bind 直接指定 參數
			preg_match_all("/:(.*)[\n|,|\s)]/isU",$sqlstr.' ',$matches);
			if($matches[1]){
				for($i=0; $i<count($matches[1]); $i++){
					$varname = $matches[1][$i];
					global $$varname;
					$v = $$varname;
					if(strlen($v==0) and isset($_POST[$varname]))$v = $_POST[$varname];
					if(strlen($v==0) and isset($_GET[$varname]))$v = $_GET[$varname];
					if($varname and isset($v)){
						$this->rs->bindValue ( ":$varname" , $v );
					}
				}	
			}
			
			if($value)$this->rs->execute($value);
			else $this->rs->execute();
			
			$error = $this->rs->errorInfo();
			if($error[0]!='0000'){
				echo "sql error ". $error[0] . "<Br>\n";;
			}
			
			
		}
		return $this->rs;
	}


	function num_rows() {
		return $this->rows;
	} 


	function fetch_array () {
		$resu = $this->rs->fetch(PDO::FETCH_ASSOC);
		return $resu;
	}

	function close() {
		$this->rs->closeCursor();
	}	  


	function insert_id () {
		$id=-1;         
		if(!$this->rs) return 0;
		return $this->dbh->lastInsertId();

	}




}

?>
