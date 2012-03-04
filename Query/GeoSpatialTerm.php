<?php 
namespace Odl\Solr\Query;

class GeoSpatialTerm
	extends Statement
{
	protected $field;
	protected $lat;
	protected $lng;
	protected $distance;
	
	public function __construct($field, $lat, $lng, $distance)
	{
		$this->field = $field;
		$this->lat = $lat;
		$this->lng = $lng;
		$this->distance = $distance;
	}
	
	public function getTerm()
	{
		$point = "{$this->lat},{$this->lng}";
		return "{!geofilt sfield={$this->field} pt={$point} d={$this->distance}}";
	}
	
	/**
	 * @return the $field
	 */
	public function getField()
	{
		return $this->field;
	}

	/**
	 * @return the $lat
	 */
	public function getLat()
	{
		return $this->lat;
	}

	/**
	 * @return the $lng
	 */
	public function getLng()
	{
		return $this->lng;
	}

	/**
	 * @return the $distance
	 */
	public function getDistance()
	{
		return $this->distance;
	}

	/**
	 * @param field_type $field
	 */
	public function setField($field)
	{
		$this->field = $field;
	}

	/**
	 * @param field_type $lat
	 */
	public function setLat($lat)
	{
		$this->lat = $lat;
	}

	/**
	 * @param field_type $lng
	 */
	public function setLng($lng)
	{
		$this->lng = $lng;
	}

	/**
	 * @param field_type $distance
	 */
	public function setDistance($distance)
	{
		$this->distance = $distance;
	}
}
