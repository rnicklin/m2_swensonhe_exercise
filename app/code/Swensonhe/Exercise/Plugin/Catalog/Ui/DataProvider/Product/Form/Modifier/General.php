<?php
/**
 * @namespace   Swensonhe
 * @module      Exercise
 * @author      Robert Nicklin
 * @email       nicklin.robert@gmail.com
 * @date        10/08/2018 2:08 PM
 */

namespace Swensonhe\Exercise\Plugin\Catalog\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\General as Subject;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;

/**
 * Class General
 *
 * @package Swensonhe\Exercise\Plugin\Catalog\Ui\DataProvider\Product\Form\Modifier
 */
class General
{
    /**
     * @var \Magento\Framework\Stdlib\ArrayManager
     */
    protected $_arrayManager;

    /**
     * @var \Swensonhe\Exercise\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @var \Magento\Catalog\Model\Locator\LocatorInterface
     */
    protected $_locator;

    /**
     * General constructor.
     *
     * @param \Magento\Framework\Stdlib\ArrayManager          $arrayManager
     * @param \Swensonhe\Exercise\Helper\Data                 $dataHelper
     * @param \Magento\Catalog\Model\Locator\LocatorInterface $locator
     */
    public function __construct(
        \Magento\Framework\Stdlib\ArrayManager $arrayManager,
        \Swensonhe\Exercise\Helper\Data $dataHelper,
        \Magento\Catalog\Model\Locator\LocatorInterface $locator
    ) {
        $this->_arrayManager = $arrayManager;
        $this->_dataHelper = $dataHelper;
        $this->_locator = $locator;
    }

    /**
     * Automatically set product pricing to 0.00
     *
     * @param Subject  $subject
     * @param \Closure $proceed
     * @param array    $data
     *
     * @return array|mixed
     */
    public function aroundModifyData(
        Subject $subject,
        \Closure $proceed,
        array $data
    ) {
        $data = $proceed($data);

        if(!$this->_dataHelper->canDisablePricingForProduct($this->_locator->getProduct())) {
            return $data;
        }

        $modelId = $this->_locator->getProduct()->getId();

        $data[$modelId][AbstractModifier::DATA_SOURCE_DEFAULT][ProductAttributeInterface::CODE_PRICE] = '0.00';

        if (isset($data[$modelId][AbstractModifier::DATA_SOURCE_DEFAULT][ProductAttributeInterface::CODE_SPECIAL_PRICE])) {
            $data[$modelId][AbstractModifier::DATA_SOURCE_DEFAULT][ProductAttributeInterface::CODE_SPECIAL_PRICE] = '0.00';
        }

        return $data;
    }

    /**
     * Hide product pricing fields from product edit form
     *
     * @param Subject  $subject
     * @param \Closure $proceed
     * @param array    $meta
     *
     * @return array|mixed
     */
    public function aroundModifyMeta(
        Subject $subject,
        \Closure $proceed,
        array $meta
    ) {
        $meta = $proceed($meta);

        if(!$this->_dataHelper->canDisablePricingForProduct($this->_locator->getProduct())) {
            return $meta;
        }

        $path = $this->_arrayManager->findPath(ProductAttributeInterface::CODE_PRICE, $meta, null, 'children');
        $meta = $this->_arrayManager->merge($path . AbstractModifier::META_CONFIG_PATH, $meta, [
            'visible' => false
        ]);

        return $meta;
    }
}