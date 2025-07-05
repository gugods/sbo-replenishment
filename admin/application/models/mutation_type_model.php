<?php

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Upload\UploadType;

class Mutation_type_model extends CI_Model
{

	var $authen;

	function __construct()
	{
		$this->Upload = new UploadType();
	}

	function mutation($authen = [], $appversion = 0)
	{
		$this->authen = $authen;
		$this->appversion = $appversion;
		$mutationType = new ObjectType([
			'name' => 'Mutation',
			'fields' => [],
		]);

		return $mutationType;
	}
}
