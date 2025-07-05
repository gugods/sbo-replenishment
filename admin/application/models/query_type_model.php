<?php

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class Query_type_model extends CI_Model
{

	var $authen;

	function __construct()
	{
		parent::__construct();
	}

	function query($authen = [], $appversion = 0)
	{
		$this->authen = $authen;
		$this->appversion = $appversion;
		$queryType = new ObjectType([
			'name' => 'Query',
			'fields' => [],
		]);

		return $queryType;
	}
}
