<?php
/**
 * Shipping Rule Edit Form Block.
 *
 * @category   RCubed
 * @package    RCubed_ShippingManager
 * @author     R-Cubed Solutions <drussell@rcubedpro.com>
 * @copyright  2025 R-Cubed Solutions (https://www.rcubedpro.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 * @link       https://www.rcubedpro.com
 */
declare(strict_types=1);

namespace RCubed\ShippingManager\Block\Adminhtml\Rule\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Store\Model\System\Store;
use Magento\Customer\Model\ResourceModel\Group\Collection as CustomerGroupCollection;

/**
 * Shipping Rule Edit Form Block
 */
class Form extends Generic
{
    /**
     * @var Store
     */
    protected $systemStore;

    /**
     * @var CustomerGroupCollection
     */
    protected $customerGroupCollection;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param CustomerGroupCollection $customerGroupCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        CustomerGroupCollection $customerGroupCollection,
        array $data = []
    ) {
        $this->systemStore = $systemStore;
        $this->customerGroupCollection = $customerGroupCollection;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \RCubed\ShippingManager\Model\Rule $model */
        $model = $this->_coreRegistry->registry('rcubed_shippingmanager_rule');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => [
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ]]
        );

        $form->setHtmlIdPrefix('rule_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Rule Information')]
        );

        // Ensure we have a model, even if it's new
        if (!$model) {
            // Create a new empty model if not registered
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $model = $objectManager->create(\RCubed\ShippingManager\Model\Rule::class);
        }

        if ($model->getId()) {
            $fieldset->addField(
                'rule_id',
                'hidden',
                ['name' => 'rule_id']
            );
        }

        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Rule Name'),
                'title' => __('Rule Name'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'description',
            'textarea',
            [
                'name' => 'description',
                'label' => __('Description'),
                'title' => __('Description'),
                'required' => false,
            ]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'name' => 'is_active',
                'label' => __('Status'),
                'title' => __('Status'),
                'required' => true,
                'options' => [
                    '1' => __('Active'),
                    '0' => __('Inactive'),
                ],
            ]
        );

        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name' => 'sort_order',
                'label' => __('Priority'),
                'title' => __('Priority'),
                'required' => false,
                'class' => 'validate-number',
                'note' => __('Higher priority rules are applied first.')
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}