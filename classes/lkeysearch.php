<?php

class lkeysearch {

	static function add_keys_with_tag($tag, $keys, $record_ids){
		if(count($keys) != count($record_ids)){
			throw new Kohana_Exception("LKeySearch Exception", "Number of keys must equal number of record_ids");
		}

		$inserts = array();
		for( $i=0; $i<count($keys); $i++){
			$inserts[] = "( '$tag', '$keys[$i]', $record_ids[$i] )";
		}
		$insert_sql = "INSERT INTO lsearchkeys ( tag, search_key, record_id ) VALUES ".implode($inserts, ",");
		Kohana::$log->add(Log::INFO, $insert_sql);

		$db = Database::instance();
		$db->query(Database::INSERT, $insert_sql);
	}

	static function drop_keys_with_tag($tag){
		$sql = "DELETE FROM lsearchkeys WHERE tag = '$tag'";
		$db = Database::instance();
		$db->query(Database::DELETE, $sql);
	}

	static function search_keys_like($search, $offset, $limit){

	}

}
