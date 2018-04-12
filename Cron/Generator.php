<?php

namespace AV\FeedGenerator2\Cron;

/*
 *  Feed generator from model feed with cronjob
 *  Change the cron setup & schedule in etc/crontab.xml, if you want
 */

class Generator
{
    const STORESCOPE = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
    const ENABLE = 'av_feedgenerator_setup/general/enable';

    public function __construct(
        \Magento\Framework\App\State $appState,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        $name = null
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $enable = $this->_scopeConfig->getValue(self::ENABLE, self::STORESCOPE);
        $appState->setAreaCode('frontend');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        if ($enable) {
            return $objectManager->get('AV\FeedGenerator2\Model\Feed');
        } else {
            return false;
        }
    }
}