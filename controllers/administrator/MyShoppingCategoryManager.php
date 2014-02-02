<?php

namespace modules\datafeed_myshopping\controllers\administrator;

use core\controllers\administrator\CategoryManager;

class MyShoppingCategoryManager extends CategoryManager {

	protected $show_admin_layout = TRUE;
	protected $controller_class = 'administrator/MyShoppingCategoryManager';

	protected $permissions = [
		'index' => ['administrator'],
	];

	public function index($message = NULL) {
		$this->category_manager($message, '\modules\datafeed_myshopping\classes\models\MyShoppingCategory', FALSE);
	}
}