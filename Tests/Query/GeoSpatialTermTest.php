<?php
namespace Odl\Solr\Tests\Query;

use Odl\Solr\Query\GeoSpatialTerm;

/**
 * Reference: http://wiki.apache.org/solr/SpatialSearch
 *
 */
class GeoSpatialTermTest
	extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
	}
	
	public function testGetTerm()
	{
		$term = new GeoSpatialTerm('lat_lng', 37.7752, -122.4232, 25);
		$expected = '{!geofilt sfield=lat_lng pt=37.7752,-122.4232 d=25}';
		$this->assertEquals($expected, $term->getQuery());
	}
	
	public function testGetQuery()
	{
		$term = new GeoSpatialTerm('lat_lng', 37.7752, -122.4232, 25);
		$expected = '{!geofilt sfield=lat_lng pt=37.7752,-122.4232 d=25}';
		$this->assertEquals($expected, $term->getQuery());
		
		$term->setModifier('+');
		$this->assertEquals('+' . $expected, $term->getQuery());
	}
}
