<?php
namespace Odl\Solr\Query;

abstract class Statement
{
	/**
	 * Modifier can be applied to any query
	 *
	 * Possible value: null|+|-
	 */
	protected $modifier;

	/**
	 * Any query could have boost
	 *
	 * Possible value: float/int greater than 0
	 */
	protected $boost;

	/**
	 * @return the $boost
	 */
	public function getBoost()
	{
		return $this->boost;
	}

	/**
	 * @param field_type $boost
	 */
	public function setBoost($boost)
	{
		if (!is_numeric($boost) || $boost < 0)
		{
			throw new \Exception("Boost must be float/int greater than 0");
		}

		$this->boost = $boost;
	}

	abstract function getTerm();

	/**
	 * @return the $modifier
	 */
	public function getModifier()
	{
		return $this->modifier;
	}

	/**
	 * @param field_type $modifier
	 */
	public function setModifier($modifier)
	{
		if ($modifier !== null && $modifier != '-' && $modifier != '+')
		{
			throw new \Exception("Modifier must be null, + or -, got {$modifier} instead");
		}
		$this->modifier = $modifier;
	}

	public function getQuery()
	{
		$query = $this->modifier . $this->getTerm();
		if ($this->boost)
		{
			$query = "{$query}^{$this->boost}";
		}

		return $query;
	}

	/**
	 * Regex that matches all Lucene query syntax reserved chars
	 */
	public static function EscapeToken($token, $force = FALSE)
	{
		if (!is_string($token))
		{
			if ($token instanceof \DateTime) {
				$gmDatetime = clone $token;
				$gmtTimeZone = new \DateTimeZone('GMT');
				$gmDatetime->setTimezone($gmtTimeZone);
				return $gmDatetime->format("Y-m-d\TG:i:s\Z");
			}

			return $token;
		}

		//return \SolrUtils::escapeQueryChars($token);
		$regex = '#["&|\[\\\\+\\-\\!\\(\\)\\:\\^\\]\\{\\}\\~\\*\\?]#';
		return preg_replace($regex, '\\\\\\0', $token);
	}

	/**
	 * Create Term|Phrase|FieldGroup from given token
	 * @param array|scalar $token
	 */
	public static function Create($token, $field)
	{
		if (is_array($token))
		{
			return new FieldGroup($token, $field);
		}
		else if (is_scalar($token))
		{
			if (str_word_count($token) > 1 || strpos($token, '-') !== false)
			{
				return new Phrase($token, $field);
			}
			else
			{
				return new Term($token, $field);
			}
		}
	}
}
