<?php
namespace Odl\Solr\Tests\Query;

use Odl\Solr\Query\Term;

class TermTest
	extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
	}

	public function testEscape()
	{
		$term = new Term('*', '*');
		$term->isAutoEscape(false);
		$term->setModifier(null);
		$this->assertEquals('*: *', $term->getQuery());
	}

	public function testQuery()
	{
		$term = new Term('test');
		$this->assertEquals('test', $term->getQuery());

		$term->setField('name');
		$this->assertEquals('name: test', $term->getQuery());

		$term->setModifier('+');
		$this->assertEquals('+name: test', $term->getQuery());

		$term->setModifier('-');
		$this->assertEquals('-name: test', $term->getQuery());

		$term->setFuzzy('.01');
		$this->assertEquals('-name: test~0.01', $term->getQuery());
	}

	public function testSetTerm()
	{
		try {
			$term = new Term('test one two');
			$this->fail('Term should not have more than one word');
		} catch (\Exception $e) {}


		$term = new Term('test');
		try {
			$term->setToken('test one two');
			$this->fail('Term should not have more than one word');
		} catch (\Exception $e) {}
	}

	public function testTermEscape()
	{
		$term = new Term('test:');
		$this->assertEquals('test\\:', $term->getQuery());
	}

	public function testFuzzyEdge()
	{
		$term = new Term('test');
		$term->setFuzzy(1);
		$this->assertEquals('test~1', $term->getQuery());

		$term->setFuzzy(0);
		$this->assertEquals('test~0', $term->getQuery());
	}

	public function testFuzzy()
	{
		$term = new Term('test');
		$term->setFuzzy(.1);
		$this->assertEquals('test~0.1', $term->getQuery());

		$term->setFuzzy('.1');
		$this->assertEquals('test~0.1', $term->getQuery());

		$term->setFuzzy('.111');
		$this->assertEquals('test~0.111', $term->getQuery());

		try {
			$term->setFuzzy(10);
			$this->fail('Fuzzy should not be greater than 1');
		} catch (\Exception $e) {}

		try {
			$term->setFuzzy(-1);
			$this->fail('Fuzzy should not be less than 0');
		} catch (\Exception $e) {}

		try {
			$term->setFuzzy('sdfsdf');
			$this->fail('Fuzzy must be numeric');
		} catch (\Exception $e) {}
	}
}