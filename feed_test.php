<?php

/*
 *  Feed is generated manually, if you start this script from the Magento root directory
 */

use Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);

$obj = $bootstrap->getObjectManager();

$feed_debug = $obj->get('AV\FeedGenerator2\Cron\Generator');

