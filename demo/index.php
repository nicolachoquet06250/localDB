<?php

require_once __DIR__.'/../localdb/autoload.php';
require_once __DIR__.'/models/MyCollection.php';

Connector::createDB(__DIR__.'/../db')
	->connect()
	->then(function ($client) {
		/** @var Client $client */
		$collection = $client->collection('my_collection');

		$collection->find([], MyCollection::class);

		$model = new MyCollection([
			'name' => 'collectionItem1',
			'age' => (new DateTime())->format('Y-m-d'),
			'toto' => 'hello' ]);

//		$model->save($client);
	})->catch(function ($error) {
		throw new Exception($error);
	});