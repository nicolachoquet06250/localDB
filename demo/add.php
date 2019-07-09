<?php

require_once __DIR__.'/autoload.php';

Connector::createDB(DB_PATH)->connect()
	->then(function (/*$client*/) {
		(new MyCollection([
			'name' => 'collectionItem1',
			'age' => (new DateTime())->format('Y-m-d'),
			'toto' => 'hello' ]))->save();

		(new MyCollection([
			'name' => 'collectionItem1',
			'age' => (new DateTime())->format('Y-m-d'),
			'toto' => 'hello' ]))->save();

		(new MyCollection([ 'name' => 'MyConnection2', 'age' => '24' ]))->save();

		(new MyCollection([ 'name' => 'toto', 'age' => '34' ]))->save();

		// is equal:
		// /** @var Client $client */
		// $collection = $client->collection('my_collection');
		// $collection->add($model);
	})->catch(function ($error) {
		throw new Exception($error);
	});