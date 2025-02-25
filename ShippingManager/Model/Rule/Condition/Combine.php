<?php
/**
 * Rule Condition Combine Class.
 *
 * @category   RCubed
 * @package    RCubed_ShippingManager
 * @author     R-Cubed Solutions <drussell@rcubedpro.com>
 * @copyright  2025 R-Cubed Solutions (https://www.rcubedpro.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 * @link       https://www.rcubedpro.com
 */
declare(strict_types=1);

namespace RCubed\ShippingManager\Model\Rule\Condition;

use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\Rule\Model\Condition\Context;

/**
 * Shipping rule conditions combine class
 */
class Combine extends \Magento\Rule\Model\Condition\Combine
{
    /**
     * @var \Magento\SalesRule\Model\Rule\Condition\Address
     */
    private $conditionAddress;

    /**
     * @param Context $context
     * @param \Magento\SalesRule\Model\Rule\Condition\Address $conditionAddress
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magento\SalesRule\Model\Rule\Condition\Address $conditionAddress,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->conditionAddress = $conditionAddress;
        $this->setType(\RCubed\ShippingManager\Model\Rule\Condition\Combine::class);
    }

    /**
     * Get new child select options
     *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        $addressAttributes = $this->conditionAddress->loadAttributeOptions()->getAttributeOption();
        $attributes = [];
        foreach ($addressAttributes as $code => $label) {
            $attributes[] = [
                'value' => 'Magento\SalesRule\Model\Rule\Condition\Address|' . $code,
                'label' => $label,
            ];
        }

        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive(
            $conditions,
            [
                [
                    'value' => \RCubed\ShippingManager\Model\Rule\Condition\Combine::class,
                    'label' => __('Conditions Combination'),
                ],
                ['label' => __('Cart Attribute'), 'value' => $attributes]
            ]
        );
        
        return $conditions;
    }

    /**
     * Get element type for condition
     *
     * @return string
     */
    public function getType()
    {
        return $this->getData('type');
    }

    /**
     * Get HTML of condition string
     *
     * @return string
     */
    public function asHtmlRecursive()
    {
        $html = $this->asHtml() . '<ul id="' . $this->getPrefix() . '__' . $this->getId() . '__children">';
        
        foreach ($this->getConditions() as $condition) {
            $html .= '<li>' . $condition->asHtmlRecursive() . '</li>';
        }
        
        $html .= '</ul>';
        
        return $html;
    }
}