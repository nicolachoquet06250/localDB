<?php

require_once __DIR__.'/autoload.php';

Connector::createDB(DB_PATH)->connect()
	->then(function () {
		$model = new MyCollection();

		$result = $model->get_collection()->find([ '$group' => [ 'by' => 'age' ] ], MyCollection::class);
		var_dump($result->get());

	})->catch(function ($e) {
		throw new Exception($e);
	});