<?php

namespace modules\datafeed_myshopping\controllers;

use core\classes\Model;
use core\classes\renderable\Controller;

class MyShopping extends Controller {
	public function index() {
		$model = new Model($this->config, $this->database);
		$products = $model->getModel('\modules\products\classes\models\Product')->getMulti();

		$myshopping = $model->getModel('\modules\products\classes\models\ProductAttribute')->get([
			'name' => 'MyShopping Category',
		]);

		$data = [[
			'Code', 'Name', 'Description', 'Category', 'Price', 'Product_URL',
			'Image_URL', 'Brand', 'Shipping', 'InStock'
		]];
		foreach ($products as $product) {
			$category = '';
			$attributes = $product->getAttributes();
			if ($attributes[$myshopping->id]) {
				$category_db = $model->getModel('\modules\datafeed_myshopping\classes\models\MyShoppingCategory')->get([
					'id' => $attributes[$myshopping->id]->product_attribute_category_id,
				]);
				$category = $category_db->name;
			}

			$images = $product->getImages();
			$image = '';
			if (isset($images[0])) {
				$image = $this->config->getSiteUrl().$images[0]->getThumbnailUrl();
			}

			$data[] = [
				$product->id,
				$product->name,
				substr(strip_tags($product->description), 0, 255),
				$category,
				$product->sell,
				$image = $this->config->getSiteUrl().$product->getUrl($this->url),
				$image,
				$product->getBrandName(),
				'',
				'Y',
			];
		}

		$csv = $this->response->arrayToCsv($data);
		$this->response->setCsvContent($this, $csv);
	}
}