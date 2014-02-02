<?php

namespace modules\datafeed_myshopping\classes\models;

use core\classes\Model;
use core\classes\models\Category;

class MyShoppingCategory extends Category {
	protected $table       = 'myshopping_category';
	protected $primary_key = 'myshopping_category_id';
	protected $columns     = [
		'myshopping_category_id' => [
			'data_type'      => 'int',
			'auto_increment' => TRUE,
			'null_allowed'   => FALSE,
		],
		'site_id' => [
			'data_type'      => 'int',
			'null_allowed'   => FALSE,
		],
		'myshopping_category_name' => [
			'data_type'      => 'text',
			'data_length'    => '128',
			'null_allowed'   => FALSE,
		],
		'myshopping_category_parent_id' => [
			'data_type'      => 'int',
			'null_allowed'   => TRUE,
		],
	];

	protected $indexes = [
		'site_id',
		'myshopping_category_parent_id',
	];

	protected $foreign_keys = [
		'myshopping_category_parent_id' => ['myshopping_category', 'myshopping_category_id'],
	];
}
