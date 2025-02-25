<?php
/**
 * Shipping Rule Edit Block.
 *
 * @category   RCubed
 * @package    RCubed_ShippingManager
 * @author     R-Cubed Solutions <drussell@rcubedpro.com>
 * @copyright  2025 R-Cubed Solutions (https://www.rcubedpro.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 * @link       https://www.rcubedpro.com
 */
declare(strict_types=1);

namespace RCubed\ShippingManager\Block\Adminhtml\Rule;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;

/**
 * Shipping Rule Edit Block
 */
class Edit extends Container
{
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize shipping rule edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'rule_id';
        $this->_blockGroup = 'RCubed_ShippingManager';
        $this->_controller = 'adminhtml_rule';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Rule'));
        $this->buttonList->update('delete', 'label', __('Delete Rule'));

        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
            -100
        );
    }

    /**
     * Get edit form container header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        $rule = $this->coreRegistry->registry('rcubed_shippingmanager_rule');
        if ($rule && $rule->getId()) {
            return __("Edit Rule '%1'", $this->escapeHtml($rule->getName()));
        }
        return __('New Rule');
    }

    /**
     * Get form HTML
     *
     * @return string
     */
    public function getFormHtml()
    {
        $formHtml = parent::getFormHtml();
        // Safety check to ensure we're not calling methods on a null object
        $rule = $this->coreRegistry->registry('rcubed_shippingmanager_rule');
        if (!$rule) {
            // Register an empty rule model
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $rule = $objectManager->create(\RCubed\ShippingManager\Model\Rule::class);
            $this->coreRegistry->register('rcubed_shippingmanager_rule', $rule);
        }
        return $formHtml;
    }
}