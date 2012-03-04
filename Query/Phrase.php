<?php
namespace Odl\Solr\Query;

class Phrase
	extends TokenStatement
{
	protected $proximity;
	protected $field;
	
	public function __construct($token, $field = null, $proximity = null)
	{
		$this->field = $field;
		$this->setProximity($proximity);
		$this->setToken($token);
	}

	public function getTerm()
	{
		$term = Statement::escapeToken($this->token);
		$term = "\"{$term}\"";
		
		if ($this->proximity)
		{
			$term = "{$term}~{$this->proximity}";
		}
		
		if ($this->field)
		{
			$term = "{$this->field}: {$term}";
		}
		
		return $term;
	}
	
	/**
	 * @return the $proximity
	 */
	public function getProximity()
	{
		return $this->proximity;
	}

	/**
	 * @return the $field
	 */
	public function getField()
	{
		return $this->field;
	}

	/**
	 * @param field_type $proximity
	 */
	public function setProximity($proximity)
	{
		if ($proximity !== null && $proximity < 1)
		{
			throw new \Exception("Proximity must be greater than 1");
		}
		
		$this->proximity = $proximity;
	}

	/**
	 * @param field_type $field
	 */
	public function setField($field)
	{
		$this->field = $field;
	}
}