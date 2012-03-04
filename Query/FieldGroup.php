<?php
namespace Odl\Solr\Query;

class FieldGroup
	extends Statement
{
	protected $terms = array();
	protected $field;
	
	/**
	 * Construct with array of terms and tokens
	 * @param array $terms Term|string|Phrase
	 * @param string $operator
	 */
	public function __construct(array $terms, $field = null)
	{
		foreach ($terms as $term)
		{
			if ($term instanceof TokenStatement)
			{
				$this->terms[] = $term;
			}
			else if (is_scalar($term))
			{
				$this->terms[] = Statement::Create($term, $field);
			}
			else 
			{
				throw new \Exception('Invalid term: ' . $term);
			}
		}
		
		$this->field = $field;
	}
	
	/**
	 * @return the $field
	 */
	public function getField()
	{
		return $this->field;
	}

	/**
	 * @param field_type $terms
	 */
	public function setTerms($terms)
	{
		$this->terms = $terms;
	}

	/**
	 * @param field_type $field
	 */
	public function setField($field)
	{
		$this->field = $field;
	}

	public function getTerms()
	{
		return $this->terms;
	}
	
	public function getTerm()
	{
		$escapedTokens = array();
		foreach ($this->terms as $term)
		{
			$oldField = $term->getField();
			$term->setField(null);
			if ($token = $term->getQuery())
			{
				$escapedTokens[] = $token;
			}
			$term->setField($oldField);
		}
		
		$escapedTokens = array_unique($escapedTokens);
		$term = implode(' ', $escapedTokens);
		$term = "({$term})";
		
		if ($this->field)
		{
			$term = "{$this->field}: $term";
		}
		
		return $term;
	}

	public function getQuery()
	{
		$term = $this->getTerm();
		$query = $this->modifier . $term;
		if ($this->boost)
		{
			$query = "{$query}^{$this->boost}";
		}
		
		return $query;
	}
}