<?php
namespace Odl\Solr\Query;

class Term
	extends TokenStatement
{
	protected $fuzzy;
	protected $field;

	public function __construct($token, $field = null, $fuzzy = null)
	{
		$this->field = $field;
		$this->setFuzzy($fuzzy);
		$this->setToken($token);
	}

	public function getTerm()
	{
		$token = $this->autoEscape ? Statement::escapeToken($this->token) : $this->token;
		if ($this->fuzzy !== null)
		{
			$token = $token . '~' . $this->fuzzy;
		}

		if ($this->field)
		{
			return $this->field . ': ' . $token;
		}

		return $token;
	}

	public function setToken($token)
	{
		$words = explode(' ', $token);
		if (count($words) > 1)
		{
			throw new \Exception('Term should not have more than one word');
		}

		$this->token = $token;
	}

	/**
	 * @return the $fuzzy
	 */
	public function getFuzzy()
	{
		return $this->fuzzy;
	}

	/**
	 * @return the $field
	 */
	public function getField()
	{
		return $this->field;
	}

	/**
	 * @param field_type $fuzzy
	 */
	public function setFuzzy($fuzzy)
	{
		if ($fuzzy !== null && (!is_numeric($fuzzy) || $fuzzy > 1 || $fuzzy < 0))
		{
			throw new \Exception("Fuzzy must be in between 0 and 1");
		}

		if ($fuzzy !== null)
		{
			$this->fuzzy = (float) $fuzzy;
		}
		else
		{
			$this->fuzzy = null;
		}
	}

	/**
	 * @param field_type $field
	 */
	public function setField($field)
	{
		$this->field = $field;
	}
}
