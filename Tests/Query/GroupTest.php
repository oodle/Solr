<?php
namespace Odl\Solr\Tests\Query;

use Odl\Solr\Query\FieldGroup;
use Odl\Solr\Query\Term;
use Odl\Solr\Query\Group;
use Odl\Solr\Query\Phrase;
use Exception;

class GroupTest
	extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
	}
	
	public function testGetQuery()
	{
		$statements = array('test', 'one', new Term('two', 'name'));
		$group = new Group($statements);
		
		$actual = $group->getQuery();
		$expected = "(test OR one OR name: two)";
		$this->assertEquals($expected, $actual);
	}
	
	public function testSetOperator()
	{
		$group = new Group(array('foo', 'bar'));
		$group->setOperator('AND');
		$expected = "(foo AND bar)";
		$actual = $group->getQuery();
		$this->assertEquals($expected, $actual);
		
		$group->setOperator('OR');
		$expected = "(foo OR bar)";
		$actual = $group->getQuery();
		$this->assertEquals($expected, $actual);
		
		$group->setOperator('&&');
		$expected = "(foo && bar)";
		$actual = $group->getQuery();
		$this->assertEquals($expected, $actual);
		
		$group->setOperator('||');
		$expected = "(foo || bar)";
		$actual = $group->getQuery();
		$this->assertEquals($expected, $actual);
		
		try {
			$group->setOperator('blah');
			$this->fail("Operator 'blah' should not be supported");
		} catch (\Exception $e) {}
		
	}
	
	public function testEdge()
	{
		$group = new Group(array());
		try {
			$this->assertEquals('', $group->getQuery());
			$this->fail('Should throw exception when Statement is an empty array');
		} catch (\Exception $e) {
		}
	}
	
	public function testComplexGroup()
	{
		$subGroup = new Group(array('foo', 'bar'));
		$statements = array('test', $subGroup);
		$group = new Group($statements, 'AND');
		
		$actual = $group->getQuery();
		$expected = '(test AND (foo OR bar))';
		$this->assertEquals($expected, $actual);
		
		$statements[] = new FieldGroup(array('field', 'group'), 'name');
		$group->setStatements($statements);
		
		$actual = $group->getQuery();
		$expected = '(test AND (foo OR bar) AND name: (field group))';
		$this->assertEquals($expected, $actual);
	}
}