<?php
$_MODULE = [
	"name" => "Data Feed - MyShopping",
	"description" => "Adds a MyShopping data feed",
	"namespace" => "\\modules\\datafeed_myshopping",
	"config_controller" => "administrator\\MyShopping",
	"controllers" => [
		"MyShopping",
		"administrator\\MyShopping",
		"administrator\\MyShoppingCategoryManager",
	],
	"default_config" => [
		"default_category" => "",
		"enclosure_escaping" => "normal",
	]
];
