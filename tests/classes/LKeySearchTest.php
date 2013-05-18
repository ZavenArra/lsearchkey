<?
Class LKeySearchTest extends Kohana_UnitTest_TestCase {
	public static $keys = array('a', 'ab', 'abc', 'b', 'before', 'd', 'de', 'da', 'dad', 'z');
	public static $record_ids = array(1, 10, 12, 3, 4, 345, 233, 455, 34, 2);

  public static function setUpBeforeClass(){
  }

  public static function tearDownAfterClass(){
		$sql = "DELETE FROM lsearchkeys";
		$db = Database::instance();
		$db->query(Database::DELETE, $sql);
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
		$this->assertEquals(count($all), count(self::$keys));
  }

  /**
   * @depends testAddKeys
   **/
  public function testDropKeys(){
		lkeysearch::drop_keys_with_tag( 'testKeys' );
		$all = ORM::factory('lsearchkey')->find_all();
		$this->assertEquals(count($all), 0);
  }


	/*
  public function testAssociatorPoolWithAll(){

    $a = new Associator('single-association', 'myAssociation');
    $this->assertNotNULL($a->pool);
    $this->assertTrue(count($a->pool) > 0);
    return $a;
  }

  public function testAssociatorPoolWithObjectTypeName(){

    $a = new Associator('single-association', 'myAssociation', $this->articleFilter);
    $this->assertNotNULL($a->pool);
    $this->assertTrue(count($a->pool) > 0);
    return $a;
  }

  /**
   * @depends testNewAssociator
   *o/*/
	/*
  public function testAssociatorRender($a){
    $html = $a->render();
    $this->assertNotNull($html);
  }

  /**
   * @depends testNewAssociator
   */
	/*
  public function testAssociatorPoolRender($a){
    $html = $a->renderPoolItems();
    $this->assertNotNull($html);
  }

  /**
   * @depends testNewAssociatorWithObjectTypeName
   */
	/*
  public function testAssociatorRenderWithObjectTypeFilter($a){
    $html = $a->render();
    $this->assertNotNull($html);
  }




  public function testAssociatorPoolExcludesAssociated(){
    $object1 = Graph::create_object('article', 'test1');
    $object1 = Graph::object($object1);
    $object2 = Graph::create_object('article', 'test2');
    $object2 = Graph::object($object2);
    $object3 = Graph::create_object('article', 'test3');
    $object3 = Graph::object($object3);

    $object1->add_lattice_relationship('testAssociation', $object2->id);

    $a = new Associator($object1->id, 'testAssociation', $this->articleFilter);
    $this->assertTrue($this->resultContainsObjectWithId($a->associated, $object2->id));
    $this->assertFalse($this->resultContainsObjectWithId($a->pool, $object2->id));

    $object1->delete();
    $object2->delete();
    $object3->delete();
  }

  private function resultContainsObjectWithId($result, $id){
    foreach($result as $row){
      if ($row->id == $id){
        return true;
      }
    }
    return false;
  }
	 */

}
