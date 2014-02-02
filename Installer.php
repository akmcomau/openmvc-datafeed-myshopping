<?php

namespace modules\datafeed_myshopping;

use ErrorException;
use core\classes\Config;
use core\classes\Database;
use core\classes\Language;
use core\classes\Model;
use core\classes\Menu;

class Installer {
	protected $config;
	protected $database;

	public function __construct(Config $config, Database $database) {
		$this->config = $config;
		$this->database = $database;
	}

	public function install() {
		$model = new Model($this->config, $this->database);

		$table = $model->getModel('\modules\datafeed_myshopping\classes\models\MyShoppingCategory');
		$table->createTable();
		$table->createIndexes();
		$table->createForeignKeys();

		$attribute = $model->getModel('\modules\products\classes\models\ProductAttribute');
		$attribute->site_id = $this->config->siteConfig()->site_id;
		$attribute->type = 'category|\modules\datafeed_myshopping\classes\models\MyShoppingCategory';
		$attribute->name = 'MyShopping Category';
		$attribute->description = '';
		$attribute->required_admin = TRUE;
		$attribute->visible = FALSE;
		$attribute->insert();
	}

	public function uninstall() {
		$model = new Model($this->config, $this->database);

		$table = $model->getModel('\modules\datafeed_myshopping\classes\models\MyShoppingCategory');
		$table->dropTable();

		$attribute = $model->getModel('\modules\products\classes\models\ProductAttribute')->get(['type' => 'category|\modules\datafeed_myshopping\classes\models\MyShoppingCategory']);
		//$attribute->delete();
	}

	public function enable() {
		$language = new Language($this->config);
		$main_menu = new Menu($this->config, $language);

		$main_menu->loadMenu('menu_admin_main.php');
		$main_menu->insert_menu(['checkout', 'checkout_products', 'checkout_products_attributes'], 'checkout_products_myshopping', [
			'controller' => 'administrator/MyShoppingCategoryManager',
			'method' => 'index',
			]
		);
		$main_menu->update();
	}

	public function disable() {
		// Remove some menu items to the admin menu
		$language = new Language($this->config);
		$main_menu = new Menu($this->config, $language);
		$main_menu->loadMenu('menu_admin_main.php');
		$menu = $main_menu->getMenuData();
		unset($menu['checkout']['children']['checkout_products']['children']['checkout_products_myshopping']);
		$main_menu->setMenuData($menu);
		$main_menu->update();
	}
}