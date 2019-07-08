<?php

require_once __DIR__.'/autoload.php';

Connector::createDB(DB_PATH)->connect()
	->then(function () {
		$model = new MyCollection();

		$ordered_models = $model->get_collection()->find([ '$order_by' => 'name' ], MyCollection::class);
		var_dump($ordered_models->get());
	})->catch(function ($e) {
		throw new Exception($e);
	});