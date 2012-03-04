<?php
namespace Odl\Solr\Query;

class Group
	extends Statement
{
	protected $statements;
	protected $operator;
	static public function AllowedOperators()
	{
		return array('AND', 'OR', '&&', '||'); 
	}
	
	public function __construct(array $statements, $operator = 'OR')
	{
		$this->setOperator($operator);
		$this->setStatements($statements);
	}
	
	/**
	 * @return the $statements
	 */
	public function getStatements()
	{
		return $this->statements;
	}

	/**
	 * @return the $operator
	 */
	public function getOperator()
	{
		return $this->operator;
	}

	/**
	 * @param field_type $statements
	 */
	public function setStatements(array $statements)
	{
		$this->statements = array();
		foreach ($statements as $statement)
		{
			$this->addStatement($statement);
		}
	}
	
	public function addStatement($statement)
	{
		if ($statement instanceof Statement)
		{
			$this->statements[] = $statement;
		}
		else if (is_scalar($statement))
		{
			$this->statements[] = new Term($statement);
		}
		else
		{
			throw new \Exception('Expecting array of class: Statement');
		}
	}

	/**
	 * @param field_type $operator
	 */
	public function setOperator($operator)
	{
		$allowed = self::AllowedOperators();
		if (!in_array($operator, $allowed))
		{
			throw new \Exception("$operator is not allowed.");
		}
		
		$this->operator = $operator;
	}

	public function getTerm()
	{
		if (count($this->statements) == 0)
		{
			throw new \Exception('Must have at least one statement to getTerm');
		}
		
		$query = array();
		foreach ($this->statements as $statement)
		{
			$query[] = $statement->getQuery();
		}
		
		$operator = " {$this->operator} ";
		return '(' . implode($operator, $query) . ')';
	}
}