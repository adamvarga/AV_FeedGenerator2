<?php

namespace AV\FeedGenerator2\Model\Config\Source;

class Field implements \Magento\Framework\Option\ArrayInterface
{
    /*
    *   Add field(s) to csv feed with configuration
    */

    public function toOptionArray()
    {
        return [
            ['value' => 'product_url', 'label' => __('Product URL')],
            ['value' => 'product_title', 'label' => __('Product Title')],
            ['value' => 'product_description', 'label' => __('Product Description')],
            ['value' => 'price', 'label' => __('Price inkl. Tax')],
            ['value' => 'sku', 'label' => __('SKU')],
            ['value' => 'product_image', 'label' => __('Product Image')],
            ['value' => 'product_category', 'label' => __('Product Category')],
            ['value' => 'delivery_cost', 'label' => __('Delivery Cost')]
        ];
    }
}