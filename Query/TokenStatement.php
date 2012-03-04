<?php
namespace Odl\Solr\Query;

abstract class TokenStatement
	extends Statement
{
	protected $token;
	protected $autoEscape = true;
	
	/**
	 * @return the $token
	 */
	public function getToken()
	{
		return $this->token;
	}

	/**
	 * @param field_type $token
	 */
	public function setToken($token)
	{
		$this->token = $token;
	}
	
	public function isAutoEscape($autoEscape)
	{
		$this->autoEscape = $autoEscape;
		return $this;
	}
}