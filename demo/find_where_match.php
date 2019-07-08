<?php

require_once __DIR__.'/autoload.php';

Connector::createDB(__DIR__.'/../db')
		 ->connect()
		 ->then(function () {
			$model = new MyCollection();

		 	$myCollectionList = $model->get_from('name', 'collectionItem1');
		 	var_dump($myCollectionList->get());

		 	$myCollectionList = $model->get_match('age', '/[^0-9]/', true);
		 	var_dump($myCollectionList->get());

			$myCollectionList = $model->get_collection()->find([
		 		'$where' => [
		 			'match' => [
		 				'field' 	=> 'age',
						'regex' 	=> '/[^0-9]/',
						'reverse' 	=> true,
					]
		 		],
		 		'$keys' => [ 'name' ] ], MyCollection::class);
			 var_dump($myCollectionList->get());

		 })->catch(function ($error) {
		throw new Exception($error);
	});