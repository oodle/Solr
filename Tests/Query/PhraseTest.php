<?php
namespace Odl\Solr\Tests\Query;

use Odl\Solr\Query\Phrase;

class PhraseTest
	extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
	}
	
	public function testQuery()
	{
		$phrase = new Phrase("testing one two");
		$this->assertEquals('"testing one two"', $phrase->getQuery());
		
		$phrase->setField('name');
		$this->assertEquals('name: "testing one two"', $phrase->getQuery());
		
		$phrase->setModifier('+');
		$this->assertEquals('+name: "testing one two"', $phrase->getQuery());
		
		$phrase->setModifier('-');
		$this->assertEquals('-name: "testing one two"', $phrase->getQuery());
		
		$phrase->setProximity('10');
		$this->assertEquals('-name: "testing one two"~10', $phrase->getQuery());
	}
	
	public function testProximity()
	{
		$phrase = new Phrase("testing one two");
		
		$phrase->setProximity(1);
		$this->assertEquals('"testing one two"~1', $phrase->getQuery());
		
		$phrase->setProximity('10');
		$this->assertEquals('"testing one two"~10', $phrase->getQuery());
		
		$phrase->setProximity('100');
		$this->assertEquals('"testing one two"~100', $phrase->getQuery());
		
		try {
			$phrase->setProximity(-1);
			$this->fail('Proximity should not be less than 0');
		} catch (\Exception $e) {}
		
		try {
			$phrase->setProximity(.5);
			$this->fail('Proximity should not be less than 1');
		} catch (\Exception $e) {}
		
		try {
			$phrase->setProximity('sdfsdf');
			$this->fail('Proximity must be numeric');
		} catch (\Exception $e) {}
	}
}