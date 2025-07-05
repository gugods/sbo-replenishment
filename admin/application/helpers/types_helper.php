<?php

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

function PaginationPayload($prefix)
{
	return new ObjectType([
		'name' => $prefix . 'PaginationPayload',
		'fields' =>  [
			'page' => Type::int(),
			'perPage' => Type::int(),
			'totalCount' => Type::int(),
			'totalPages' => Type::int()
		],
	]);
}

function PaginationAttribute($prefix)
{
	return 	new InputObjectType([
		'name' => $prefix . 'Pagination',
		'fields' =>  [
			'page' => Type::int(),
			'perPage' => Type::int(),
		],
	]);
}
