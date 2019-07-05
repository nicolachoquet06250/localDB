<?php

require_once __DIR__.'/classes/core/ConnectorResult.php';
require_once __DIR__.'/classes/core/Connector.php';
require_once __DIR__.'/classes/core/Client.php';
require_once __DIR__.'/classes/core/Collection.php';
require_once __DIR__.'/classes/core/Model.php';
require_once __DIR__.'/classes/core/NoSQLSelector.php';
require_once __DIR__.'/classes/selectors/keys.php';



Collection::addSelector(keys::class);