<?php

require_once __DIR__.'/../localdb/autoload.php';
require_once __DIR__.'/models/MyCollection.php';
require_once __DIR__.'/selectors/count.php';

Collection::addSelectors(count::class);

Connector::createDB(__DIR__.'/../db')
	->connect()
	->then(function ($client) {
		/** @var Client $client */
		$collection = $client->collection('my_collection');

		$collection->find(
			[
				'$keys' => [ 'name' ],
			],
			MyCollection::class);

		$collection->find(
			[
				'$count' => true
			],
			MyCollection::class
		);

//		$model = new MyCollection([
//			'name' => 'collectionItem1',
//			'age' => (new DateTime())->format('Y-m-d'),
//			'toto' => 'hello' ]);
//
//		$model->save($client);
	})->catch(function ($error) {
		throw new Exception($error);
	});