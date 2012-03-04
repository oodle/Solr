<?php
namespace Odl\Solr\Query;

class RangedTerm
	extends Statement
{
	protected $field;
	protected $lowerToken;
	protected $upperToken;
	protected $upperInclude;
	protected $lowerInclude;

	/**
	 * @return the $field
	 */
	public function getField()
	{
		return $this->field;
	}

	/**
	 * @return the $lowerToken
	 */
	public function getLowerToken()
	{
		return $this->lowerToken;
	}

	/**
	 * @return the $upperToken
	 */
	public function getUpperToken()
	{
		return $this->upperToken;
	}

	/**
	 * @return the $upperInclude
	 */
	public function getUpperInclude()
	{
		return $this->upperInclude;
	}

	/**
	 * @return the $lowerInclude
	 */
	public function getLowerInclude()
	{
		return $this->lowerInclude;
	}

	/**
	 * @param field_type $field
	 */
	public function setField($field)
	{
		$this->field = $field;
	}

	/**
	 * @param field_type $lowerToken
	 */
	public function setLowerToken($lowerToken)
	{
		$this->lowerToken = $lowerToken;
	}

	/**
	 * @param field_type $upperToken
	 */
	public function setUpperToken($upperToken)
	{
		$this->upperToken = $upperToken;
	}

	/**
	 * @param field_type $upperInclude
	 */
	public function setUpperInclude($upperInclude)
	{
		$this->upperInclude = $upperInclude;
	}

	/**
	 * @param field_type $lowerInclude
	 */
	public function setLowerInclude($lowerInclude)
	{
		$this->lowerInclude = $lowerInclude;
	}

	public function __construct($lowerToken, $upperToken, $field = null, $lowerInclude = true, $upperInclude = true)
	{
		$this->lowerToken = $lowerToken;
		$this->upperToken = $upperToken;
		$this->field = $field;
		$this->lowerInclude = $lowerInclude;
		$this->upperInclude = $upperInclude;
	}

	public function getTerm()
	{
		$lowerToken = Statement::escapeToken($this->lowerToken);
		$upperToken = Statement::escapeToken($this->upperToken);
		$lowerBound = ($this->lowerInclude) ? '[' : '[';
		$upperBound = ($this->upperInclude) ? ']' : ']';

		$lowerToken = (!$lowerToken) ? '*' : $lowerToken;
		$upperToken = (!$upperToken) ? '*' : $upperToken;

		$term = "{$lowerBound}{$lowerToken} TO {$upperToken}{$upperBound}";
		if ($this->field)
		{
			$term = "{$this->field}: {$term}";
		}

		return $term;
	}
}
