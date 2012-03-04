<?php
namespace Odl\Solr\Tests\Query;

use PHPUnit_Framework_TestCase;
use Exception;

class MockStatementTest
	extends \PHPUnit_Framework_TestCase
{
	protected $statement;
	public function setUp()
	{	
		$this->statement = new MockStatement('test');
	}
	
	public function testGetTerm()
	{
		$this->assertEquals('test', $this->statement->getTerm());
	}
	
	public function setBoostNumber()
	{
		$this->statement->setBoost(1);
		$this->assertEquals(1, $this->statement->getBoost(), 'Boost set fail');
	}
	
	public function testSetBoostNumber()
	{
		try 
		{
			$this->statement->setBoost(-1);
			$this->fail('Boost positive number test fail');
		}
		catch (Exception $ex)
		{
			//
		}
	}
	
	public function testSetBoostNumeric()
	{
		try 
		{
			$this->statement->setBoost('sdfsdf');
			$this->fail('Boost numeric test fail');
		}
		catch (Exception $ex)
		{
			//
		}
	}
	
	public function setModifierTest()
	{
		$this->statement->setModifier(null);
		$this->assertEquals(null, $this->statement->getModifier(), 'setModifier(null) fail');
		
		$this->statement->setModifier('-');
		$this->assertEquals('-', $this->statement->getModifier(), 'setModifier("-") fail');
		
		$this->statement->setModifier('+');
		$this->assertEquals('+', $this->statement->getModifier(), 'setModifier("+")fail');
		

		try 
		{
			$this->statement->setModifier('sdfsdf');
			$this->fail('setModifier("sdfsdf") fail');
		}
		catch (Exception $ex) {}
	}
	
	public function testGetQueryBoost()
	{
		$expect = 'test^10';
		
		$this->statement->setBoost(10);
		$query = $this->statement->getQuery();
		$this->assertEquals($expect, $query); 
		
		$this->statement->setBoost(10);
		$this->statement->setModifier(null);
		$query = $this->statement->getQuery();
		$this->assertEquals($expect, $query);
		
		$expect = '+test^10';
		$this->statement->setModifier('+');
		$query = $this->statement->getQuery();
		$this->assertEquals($expect, $query);
		
		$expect = '-test^10';
		$this->statement->setModifier('-');
		$query = $this->statement->getQuery();
		$this->assertEquals($expect, $query);
	}
	
	public function testTermEscape()
	{
		$terms = <<<EOD
		+ - & | ! ( ) { } [ ] ^ " ~ * ? : \
EOD;
		$terms = explode(' ', trim($terms));
		
		$this->statement->setTerm('sf:ca:usa');
		$expect = 'sf\\:ca\\:usa';
		$query = $this->statement->getQuery();
		$this->assertEquals($expect, $query);
		
		foreach ($terms as $term)
		{
			$this->statement->setTerm($term);
			$this->assertEquals('\\' . $term, $this->statement->getQuery());
		}
	}
}

