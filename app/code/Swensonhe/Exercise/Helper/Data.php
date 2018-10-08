<?php
/**
 * @namespace   Swensonhe
 * @module      Exercise
 * @author      Robert Nicklin
 * @email       nicklin.robert@gmail.com
 * @date        10/08/2018 2:08 PM
 */

namespace Swensonhe\Exercise\Helper;

use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_PRICING_DISABLED_FOR_ATTRIBUTE_SETS = 'catalog/swensonhe/pricing_disabled_for_attribute_sets';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;

        parent::__construct($context);
    }

    /**
     * Check if pricing should be disabled for provided product in admin
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return bool
     */
    public function canDisablePricingForProduct(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $attributeSetIds = explode(",", $this->scopeConfig->getValue(
            self::XML_PATH_PRICING_DISABLED_FOR_ATTRIBUTE_SETS,
            ScopeInterface::SCOPE_STORE
        ));

        if(in_array($product->getAttributeSetId(), $attributeSetIds) !== false) {
            return true;
        }

        return false;
    }

}