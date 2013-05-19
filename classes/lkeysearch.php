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

		$db = Database::instance();
		$db->query(Database::INSERT, $insert_sql);
	}

	static function drop_keys_with_tag($tag){
		$sql = "DELETE FROM lsearchkeys WHERE tag = '$tag'";
		$db = Database::instance();
		$db->query(Database::DELETE, $sql);
	}

	static function distinct_search_keys_with_tag($tag, $sort = 'ASC'){
		
		$results = DB::select('search_key')
			->from('lsearchkeys')
			->distinct(true)
			->where('tag', '=', $tag)
			->order_by('search_key', $sort)
			->execute();
		return $results;
	}

	static function search_keys_like($search, $offset, $limit, $tag, $sort = 'ASC'){
		$results = ORM::Factory('lsearchkey')
		->where('tag', '=', $tag)
		->and_where_open()
		->where('search_key', 'LIKE', "$search%")
		->or_where('search_key', 'LIKE', "% $search%")
		->and_where_close()
		->order_by('search_key', $sort)
		->offset($offset)
		->limit($limit)
		->find_all();
		return $results;
	}

	static function search_keys_like_distinct($search, $offset, $limit, $tag){
		$sql =  "SELECT DISTINCT record_id, min(search_key) as min_search_key ".
			"FROM lsearchkeys ".
			"WHERE tag = '$tag' ".
			"AND ( search_key LIKE '$search%' OR search_key LIKE '% $search%' ) ".
			"GROUP BY record_id ".
			"ORDER BY min_search_key ".
			"LIMIT $offset, $limit";
		Kohana::$log->add(Log::INFO, $sql);
		$db = Database::instance();
		$results = $db->query(Database::SELECT, $sql);
		return $results;
	}


	static function joined_records_like($search, $offset, $limit, $tag, $table){
		$sql = "SELECT * FROM $table ".
			"JOIN  ( ".
			"SELECT DISTINCT record_id, min(search_key) as min_search_key ".
			"FROM lsearchkeys ".
			"WHERE tag = '$tag' ".
			"AND ( search_key LIKE '$search%' OR search_key LIKE '% $search%' ) ".
			"GROUP BY id ".
			"ORDER BY min_search_key ".
			" ) search_results ON $table.id = search_results.record_id ".
			" LIMIT $offset, $limit ".
			" order_by search_results.min_search_key ";
						
		$db = Database::instance();
		$results = $db->query(Database::SELECT, $sql);
		return $results;

	}

}
