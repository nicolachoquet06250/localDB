<?php

require_once __DIR__.'/autoload.php';

Connector::createDB(DB_PATH)->connect()->then(function () {
	var_dump(MyCollection::from('name', 'collectionItem1')->get(), "\n");
	var_dump(MyCollection::match('age', '/[^0-9]/', true)->get(), "\n");
	var_dump(MyCollection::all()->get(), "\n");
});