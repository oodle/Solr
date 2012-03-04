<?php
namespace Odl\Solr\Tests\Query;

use Odl\Solr\Query\Statement;

class StatementTest
	extends \PHPUnit_Framework_TestCase
{
	public function testCreate()
	{
		$actual = Statement::Create('term', 'name');
		$this->assertInstanceOf('Odl\Solr\Query\Term', $actual);
		
		$actual = Statement::Create('a phrase', 'name');
		$this->assertInstanceOf('Odl\Solr\Query\Phrase', $actual);
		
		$actual = Statement::Create(array('one', 'two'), 'name');
		$this->assertInstanceOf('Odl\Solr\Query\FieldGroup', $actual);
	}
}