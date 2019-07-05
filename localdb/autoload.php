<?php

require_once __DIR__.'/classes/ConnectorResult.php';
require_once __DIR__.'/classes/Connector.php';
require_once __DIR__.'/classes/Client.php';
require_once __DIR__.'/classes/Collection.php';
require_once __DIR__.'/classes/Model.php';
require_once __DIR__.'/classes/NoSQLSelector.php';
require_once __DIR__.'/classes/selectors/keys.php';



Collection::addSelector(keys::class);