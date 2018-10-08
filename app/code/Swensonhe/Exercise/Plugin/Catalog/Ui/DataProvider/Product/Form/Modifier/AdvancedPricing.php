<?php
/**
 * @namespace   Swensonhe
 * @module      Exercise
 * @author      Robert Nicklin
 * @email       nicklin.robert@gmail.com
 * @date        10/08/2018 2:08 PM
 */

namespace Swensonhe\Exercise\Plugin\Catalog\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AdvancedPricing as Subject;

/**
 * Class AdvancedPricing
 *
 * @package Swensonhe\Exercise\Plugin\Catalog\Ui\DataProvider\Product\Form\Modifier
 */
class AdvancedPricing
{
    /**
     * @var \Swensonhe\Exercise\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @var \Magento\Catalog\Model\Locator\LocatorInterface
     */
    protected $_locator;

    /**
     * AdvancedPricing constructor.
     *
     * @param \Swensonhe\Exercise\Helper\Data                 $dataHelper
     * @param \Magento\Catalog\Model\Locator\LocatorInterface $locator
     */
    public function __construct(
        \Swensonhe\Exercise\Helper\Data $dataHelper,
        \Magento\Catalog\Model\Locator\LocatorInterface $locator
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_locator = $locator;
    }

    /**
     * Prevent advanced pricing from displaying on admin product edit screen
     *
     * @param Subject  $subject
     * @param \Closure $proceed
     * @param array    $meta
     *
     * @return mixed
     */
    public function aroundModifyMeta(
        Subject $subject,
        \Closure $proceed,
        array $meta
    ) {
        if($this->_dataHelper->canDisablePricingForProduct($this->_locator->getProduct())) {
            unset($meta['advanced-pricing']);
        }

        $return = $proceed($meta);

        return $return;
    }
}