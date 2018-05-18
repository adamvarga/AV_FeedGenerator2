AV_FeedGenerator2 for Magento 2.x
=====================
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://paypal.me/adamvarga28)


CSV Feed Generator with configuration

Optional field: <br>
 ```
product_url
product_title
product_description
price
sku
product_image
product_category
delivery_cost
 ```

Feed is generated once a day at 0:00 <br> (Change the cron setup, schedule in etc/crontab.xml, if you want)

CRONTAB.GURU: <a href="http://crontab.guru" target='_blank'>Quick and simple editor for your cronjob</a>

CSV Feed is placed in var/feed directory

-------------------------------
Installation Instructions
-------------------------
1, Clone the extension as a composer repository via GitHub 

2, Add the <strong>av/feedgenerator2</strong> composer package to your project. 

3, Require with 
```
composer require av/feedgenerator2
```
4, Clear the cache and upgrade the module environment with
 
 ```
 rm -rf var/cache/*
 rm -rf var/page_cache/*
 rm -rf var/generation/*
 php bin/magento setup:upgrade
 ```
 
5, Logout from the admin panel and then login again.

6, Change the config in System -> Configuration -> AV Feedgenerator2 -> Configuration

7, If you want debug the model OR start manually the feed generation, start your feed_test.php from your Magento root directory

Example setup:

![alt text](https://github.com/adamvarga/AV_FeedGenerator2/blob/master/feed_setup.png)

[CSV Feed example file](https://github.com/adamvarga/AV_FeedGenerator2/blob/master/feed_example.csv)

Uninstallation
--------------
1, Remove all extension files from your Magento installation OR

2, Remove via Composer and clear the caches

```
composer remove av/feedgenerator2
```

Support
-------
If you have any issues with this extension, open an issue on [GitHub](https://github.com/adamvarga).

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------
Adam Varga
