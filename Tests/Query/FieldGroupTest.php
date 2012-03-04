<?php
namespace Odl\Solr\Tests\Query;

use Odl\Solr\Query\FieldGroup;

use PHPUnit_Framework_TestCase;

class FieldGroupTest
	extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		
	}
	
	public function testGetTerm()
	{
		$fieldGroup = new FieldGroup(array('test', 'one'));
		$term = $fieldGroup->getTerm();
		$expected = '(test one)';
		
		$this->assertEquals(2, count($fieldGroup->getTerms()));
		$this->assertEquals($expected, $fieldGroup->getTerm());
	}
	
	public function testGetQuery()
	{
		$fieldGroup = new FieldGroup(array('test', 'one'));
		$expected = '(test one)';
		
		$this->assertEquals($expected, $fieldGroup->getQuery());
		
		$fieldGroup->setModifier('+');
		$expected = '+(test one)';
		$this->assertEquals($expected, $fieldGroup->getQuery());
		
		$expected = '+name: (test one)';
		$fieldGroup->setField('name');
		$this->assertEquals($expected, $fieldGroup->getQuery());
	}
	
	public function testGetQueryWithField()
	{
		$fieldGroup = new FieldGroup(array('test', 'one'), 'name');
		$term = $fieldGroup->getTerm();
		$expected = 'name: (test one)';
		
		$this->assertEquals($expected, $fieldGroup->getTerm());
	}
	
	public function testQueryWithModifiedTerm()
	{
		$fieldGroup = new FieldGroup(array('test', 'one'), 'name');
		$terms = $fieldGroup->getTerms();
		$terms[0]->setModifier('+');
		$expected = 'name: (+test one)';
		
		$this->assertEquals($expected, $fieldGroup->getTerm());
		
	}
}