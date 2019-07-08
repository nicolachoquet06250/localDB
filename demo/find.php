<?php

require_once __DIR__.'/autoload.php';

Connector::createDB(DB_PATH)->connect()
	->then(function ($client) {
		/** @var Client $client */
		$collection = $client->collection('my_collection');

		$with_just_name = $collection->find([ '$keys' => [ 'name' ] ], MyCollection::class);
		$count_all = $collection->find([ '$count' => true ], MyCollection::class);

		var_dump($with_just_name->get(), $count_all->get());
	})->catch(function ($error) {
		throw new Exception($error);
	});