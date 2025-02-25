<?php
/**
 * Shipping Method Edit Block.
 *
 * @category   RCubed
 * @package    RCubed_ShippingManager
 * @author     R-Cubed Solutions <drussell@rcubedpro.com>
 * @copyright  2025 R-Cubed Solutions (https://www.rcubedpro.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 * @link       https://www.rcubedpro.com
 */
declare(strict_types=1);

namespace RCubed\ShippingManager\Block\Adminhtml\Method;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;

/**
 * Shipping Method Edit Block
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
     * Initialize shipping method edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'method_id';
        $this->_blockGroup = 'RCubed_ShippingManager';
        $this->_controller = 'adminhtml_method';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Method'));
        $this->buttonList->update('delete', 'label', __('Delete Method'));

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
        $method = $this->coreRegistry->registry('rcubed_shippingmanager_method');
        if ($method && $method->getId()) {
            return __("Edit Method '%1'", $this->escapeHtml($method->getName()));
        }
        return __('New Method');
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
        $method = $this->coreRegistry->registry('rcubed_shippingmanager_method');
        if (!$method) {
            // Register an empty method model
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $method = $objectManager->create(\RCubed\ShippingManager\Model\Method::class);
            $this->coreRegistry->register('rcubed_shippingmanager_method', $method);
        }
        return $formHtml;
    }
}