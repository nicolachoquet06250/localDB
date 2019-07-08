<?php

require_once __DIR__.'/../localdb/autoload.php';
require_once __DIR__.'/models/MyCollection.php';
require_once __DIR__.'/selectors/count.php';

const DB_PATH = __DIR__.'/../db';
NoSQLSelector::requireAll();