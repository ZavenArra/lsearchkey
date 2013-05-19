<?
Class LKeySearchTest extends Kohana_UnitTest_TestCase {
	public static $keys = array('a', 'ab', 'abc', 'b', 'before', 'd', 'd', 'da', 'dad', 'a');
	public static $record_ids = array(1, 10, 12, 3, 4, 345, 233, 455, 34, 2);

	public static $keys_many_to_many = array('a', 'ab', 'abc', 'b', 'before', 'd', 'd', 'da', 'dad', 'a');
	public static $record_ids_many_to_many = array(1, 1, 1, 2, 2, 2, 3, 455, 3, 3);

  public static function setUpBeforeClass(){
		$sql = "DELETE FROM lsearchkeys";
		$db = Database::instance();
		$db->query(Database::DELETE, $sql);
  }

  public static function tearDownAfterClass(){
  }

  public function testAddKeysFunctionExists(){
		lkeysearch::add_keys_with_tag( 'dummy', array('one'), array(1) );
  }

  public function testDropKeysFunctionExists(){
		lkeysearch::drop_keys_with_tag( 'dummy' );
  }


  public function testAddKeys(){
		lkeysearch::add_keys_with_tag( 'testKeys', self::$keys, self::$record_ids );
		$all = ORM::factory('lsearchkey')->where('tag', '=', 'testKeys')->find_all();
		$this->assertEquals(count(self::$keys), count($all) );
  }

  /**
   * @depends testAddKeys
   **/
  public function testDropKeys(){
		lkeysearch::drop_keys_with_tag( 'testKeys' );
		$all = ORM::factory('lsearchkey')->find_all();
		$this->assertEquals(0, count($all));
  }

	public function testDistinctKeys(){
		$tag = 'distinctTest';
		lkeysearch::add_keys_with_tag( $tag, self::$keys, self::$record_ids );
		$results = lkeysearch::distinct_search_keys_with_tag($tag, $sort = 'ASC');
		lkeysearch::drop_keys_with_tag( $tag );
		$this->assertEquals(8, count($results));
	}

	public function testSearchLike(){
		$tag = 'likeTest';
		lkeysearch::add_keys_with_tag( $tag, self::$keys, self::$record_ids );
		$r1 = lkeysearch::search_keys_like('a', 0, 100, $tag);
		$r2 = lkeysearch::search_keys_like('ab', 0, 100, $tag);
		lkeysearch::drop_keys_with_tag( $tag);

		$this->assertEquals(4, $r1->count());
		$this->assertEquals(2, $r2->count());
	}


	public function testSearchLikeManyToMany(){
		$tag = 'likeTestManyToMany';
		lkeysearch::add_keys_with_tag( $tag, self::$keys_many_to_many, self::$record_ids_many_to_many );
		$r1 = lkeysearch::search_keys_like_distinct('a', 0, 100, $tag);
		$r2 = lkeysearch::search_keys_like_distinct('', 0, 100, $tag);
		lkeysearch::drop_keys_with_tag( $tag);

		$this->assertEquals(2, count($r1));
		$this->assertEquals(4, count($r2));
	}


}
