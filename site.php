<?php
	
	use \Hcode\Model\User;
	use \Hcode\Model\Category;
	use \Hcode\Model\Product;
	use \Hcode\Page;
	use \Hcode\PageAdmin;
	

	

	$app->get('/', function() {
		$products = Product::listAll();
		$page = new Page();
		$page->setTpl("index", [
			'products'=>Product::checkList($products)
		]);
	});




?>