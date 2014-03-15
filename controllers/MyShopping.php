<?php

namespace modules\datafeed_myshopping\controllers;

use core\classes\Model;
use core\classes\renderable\Controller;

class MyShopping extends Controller {

	public function getAllUrls($include_filter = NULL, $exclude_filter = NULL) {
		return [];
	}

	public function index() {
		$module_config = $this->config->moduleConfig('\modules\datafeed_myshopping');
		$model = new Model($this->config, $this->database);
		$products = $model->getModel('\modules\products\classes\models\Product')->getMulti([
			'site_id' => ['type'=>'in', 'value'=>$this->allowedSiteIDs()],
			'active' => TRUE
		]);

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
			if (isset($attributes[$myshopping->id])) {
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

			$description = $product->description;
			$description = str_replace("\n", ' ', $description);
			$description = str_replace("\r", '', $description);
			$description = substr(strip_tags($description), 0, 255);

			$data[] = [
				$product->id,
				$product->name,
				$description,
				$category,
				$product->sell,
				$this->config->getSiteUrl().$product->getUrl($this->url),
				$image,
				$product->getBrandName(),
				'',
				'Y',
			];
		}

		$csv = $this->response->arrayToCsv($data);
		if ($module_config->enclosure_escaping == 'backslash') {
			$csv = str_replace('""', '\\"', $csv);
		}
		$this->response->setCsvContent($this, $csv);
	}
}