<?php

Class Controller_LKeySearch extends Lattice_Controller_Ajax {

	public function action_keys_for_search($search, $tag, $limit = 20, $offset = 0){
		$results = lkeysearch::search_keys_like($search, $offset, $limit, $tag);
		$keys = array();
		foreach($results as $key){
			$keys[] = $key->search_key;
		}
		$this->response->data(array('search_keys'=>$keys));
	}
}
