<?php

namespace AV\FeedGenerator2\Model;
define('DS', DIRECTORY_SEPARATOR);

class Feed
{
    protected $storeManager;
    protected $urlInterface;
    protected $categoryRepository;
    protected $scopeConfig;
    protected $directorylist;
    protected $io;

    /*
     *  Define the default scope
     */

    const STORESCOPE = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

    /*
     *  Get setup paths
     */

    const DELIVERY_COST = 'av_feedgenerator_setup/general/delivery_cost';
    const FILE_NAME = 'av_feedgenerator_setup/general/file_name';
    const FIELDS = 'av_feedgenerator_setup/general/feed_field';

    /*
     *  Define the default interfaces and set as variables
     */

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Filesystem\DirectoryList $directorylist,
        \Magento\Framework\Filesystem\Io\File $io

    )
    {
        $this->storeManager = $storeManager;
        $this->urlInterface = $urlInterface;
        $this->categoryRepository = $categoryRepository;
        $this->scopeConfig = $scopeConfig;
        $this->directorylist = $directorylist;
        $this->io = $io;
        $this->getProductCollection();
    }

    /*
     *  Get the media url
     */

    public function getMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /*
     *  Get category name from category id
     */

    public function getCategoryName($categoryId)
    {
        $categoryObj = $this->categoryRepository->get($categoryId);
        return $categoryObj->getName();
    }

    /*
     *  Get the actually currency
     */

    public function getCurrency()
    {
        return $this->storeManager->getStore()->getCurrentCurrency()->getCode();
    }

    /*
     *  Get the delivery cost from config
     */

    public function getDeliveryCost()
    {
        $deliveryConfig = $this->scopeConfig->getValue(self::DELIVERY_COST, self::STORESCOPE);
        $deliveryCost = preg_replace("/[^0-9]/", "", $deliveryConfig);
        $error_delivery_msg = 'Please fill correctly the delivery cost setup field';

        if ($deliveryCost) {
            return str_replace(',', '.', $this->scopeConfig->getValue(self::DELIVERY_COST, self::STORESCOPE));
        } else {
            return $error_delivery_msg;
        }
    }

    /*
     *  Get file name from config
     */

    public function getFileName()
    {
        $fileName = $this->scopeConfig->getValue(self::FILE_NAME, self::STORESCOPE);
        $finalPath = $this->directorylist->getPath('var') . DS . 'feed' . DS;
        if (!file_exists($finalPath)) {
            $this->io->mkdir($this->directorylist->getPath('var') . DS . 'feed' . DS, 0775);
        }
        if ($fileName && $finalPath) {
            return $finalPath . (string)$fileName . '.csv';
        } else {
            return $finalPath . 'feed.csv';
        }
    }

    /*
    *  Get every product and filter the collection
    */

    public function getProductCollection()
    {
        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $productCollection = $_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');

        $collection = $productCollection->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', 1)
            ->addAttributeToFilter('visibility', 4)
            ->load();

        return $this->setFeed($collection);
    }

    /*
     *  Get field(s) from config
     */

    public function getFields()
    {
        $feedField = $this->scopeConfig->getValue(self::FIELDS, self::STORESCOPE);
        $fields = explode(",", $feedField);
        return $fields;
    }

    /*
     *  Set a feed from the collection & config and save the csv file
     */

    public function setFeed($collection)
    {
        $csv = new \Magento\Framework\File\Csv(new \Magento\Framework\Filesystem\Driver\File());
        $csvdata = array();
        $file = $this->getFileName();
        $_columns = $this->getFields();
        $csvdata[] = $_columns;

        foreach ($collection as $product) {
            $data = array();

            /*
             *  Prepare CSV contents
             */

            foreach ($_columns as $_column) {
                switch ($_column) {
                    case 'product_url':
                        $data[] = $product->getProductUrl();
                        break;
                    case 'product_title':
                        $data[] = $product->getName();
                        break;
                    case 'product_description':
                        $data[] = strip_tags($product->getDescription());
                        break;
                    case 'price':
                        $data[] = $product->getPriceInfo()->getPrice('final_price')->getValue() . ' ' . $this->getCurrency();
                        break;
                    case 'sku':
                        $data[] = $product->getSku();
                        break;
                    case 'product_image':
                        $data[] = $this->getMediaUrl() . 'catalog/product' . $product->getImage();
                        break;
                    case 'product_category':
                        $product_category = '';
                        foreach ($product->getCategoryIds() as $catIds) {
                            $product_category .= $this->getCategoryName($catIds) . ', ';
                        }
                        $data[] = substr($product_category, 0, -2);
                        break;
                    case 'delivery_cost':
                        $data[] = $this->getDeliveryCost();
                        break;
                }
            }
            $csvdata[] = $data;
        }

        $csv->setDelimiter(',');
        $csv->setEnclosure('"');
        $csv->saveData($file, $csvdata);
    }
}