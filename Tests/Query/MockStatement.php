<?php
namespace Odl\Solr\Tests\Query;

use Odl\Solr\Query\Statement;

class MockStatement
	extends Statement
{
	protected $term;
	
	public function __construct($term)
	{
		$this->term = $term;
	}
	
	public function getTerm()
	{
		return Statement::escapeToken($this->term);
	}
	/**
	 * @param field_type $term
	 */
	public function setTerm($term)
	{
		$this->term = $term;
	}

}
