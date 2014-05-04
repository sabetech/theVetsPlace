<?php
define("DB_HOST","localhost");
define("DB_DATABASE",'thevetsplace');
define("DB_USER",'root');
define("DB_PASSWORD",'sabe');

	class theVetsDB_API{
		var $link;
		var $result;
		
		function theVetsDB_API(){
			
		}
		
		function connect(){
			if($this->link){
				return true;
			}
			
			$this->link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
			
			if(!$this->link){
				echo "Could not connect";
				return false;
			}
			
			if (!mysql_select_db(DB_DATABASE, $this->link)){
				echo 'cannot connect to database:'; 
				return false;
			}
			
			if ($this->link){
				return true;
			}
		}
		
		function getLastInsertID(){
			return mysql_insert_id();
		}
		function runQuery($query_str){
			if (!$this->connect()) {
				return false;
			}
 	
			$this->result = mysql_query($query_str);
 	
			if(!$this->result){
				return false;
			}
			
			return true;
		}
		
		function fetch(){
			return mysql_fetch_assoc($this->result);
		}
		
		function free_result() {
			if (!$this->result) {
				return ;
			}
			mysql_free_result($this->result);
		}
		
		function get_num_rows() {
			return mysql_num_rows($this->result);
		}
		
		function beginTransaction(){
			mysql_query("BEGIN");
		}
		
		function commit(){
			mysql_query("COMMIT");
		}
		
		function rollback(){
			mysql_query("ROLLBACK");
		}
	}

?>