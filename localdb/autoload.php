<?php

require_once __DIR__.'/classes/core/ConnectorResult.php';
require_once __DIR__.'/classes/core/Connector.php';
require_once __DIR__.'/classes/core/Client.php';
require_once __DIR__.'/classes/core/Collection.php';
require_once __DIR__.'/traits/core/TModel.php';
require_once __DIR__.'/abstract/core/Model.php';
require_once __DIR__.'/classes/core/ModelFinder.php';
require_once __DIR__.'/abstract/core/NoSQLSelector.php';
require_once __DIR__.'/classes/selectors/keys.php';
require_once __DIR__.'/classes/selectors/where.php';
require_once __DIR__.'/classes/selectors/group.php';
require_once __DIR__.'/classes/selectors/order_by.php';


NoSQLSelector::requireAll();