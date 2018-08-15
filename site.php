<?php
	
	use \Hcode\Model\User;
	use \Hcode\Model\Category;
	use \Hcode\Model\Product;
	use \Hcode\Page;
	use \Hcode\PageAdmin;
	

	

	$app->get('/', function() {
	    
		$page = new Page();

		$page->setTpl("index");

	});


?>