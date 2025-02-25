<?php
/**
 * Shipping Method Edit Form Block.
 *
 * @category   RCubed
 * @package    RCubed_ShippingManager
 * @author     R-Cubed Solutions <drussell@rcubedpro.com>
 * @copyright  2025 R-Cubed Solutions (https://www.rcubedpro.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 * @link       https://www.rcubedpro.com
 */
declare(strict_types=1);

namespace RCubed\ShippingManager\Block\Adminhtml\Method\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Store\Model\System\Store;

/**
 * Shipping Method Edit Form Block
 */
class Form extends Generic
{
    /**
     * @var Store
     */
    private $systemStore;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        array $data = []
    ) {
        $this->systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \RCubed\ShippingManager\Model\Method $model */
        $model = $this->_coreRegistry->registry('rcubed_shippingmanager_method');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => [
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ]]
        );

        $form->setHtmlIdPrefix('method_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Method Information')]
        );

        // Ensure we have a model, even if it's new
        if (!$model) {
            // Create a new empty model if not registered
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $model = $objectManager->create(\RCubed\ShippingManager\Model\Method::class);
        }

        if ($model->getId()) {
            $fieldset->addField(
                'method_id',
                'hidden',
                ['name' => 'method_id']
            );
        }

        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Method Name'),
                'title' => __('Method Name'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'code',
            'text',
            [
                'name' => 'code',
                'label' => __('Method Code'),
                'title' => __('Method Code'),
                'required' => true,
                'class' => 'validate-code',
                'note' => __('Unique identifier for the shipping method. Use only letters, numbers, and underscores.')
            ]
        );

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Method Title'),
                'title' => __('Method Title'),
                'required' => false,
                'note' => __('Title shown to customers during checkout. If empty, the Method Name will be used.')
            ]
        );

        $fieldset->addField(
            'price',
            'text',
            [
                'name' => 'price',
                'label' => __('Price'),
                'title' => __('Price'),
                'required' => false,
                'class' => 'validate-number',
                'note' => __('Leave empty for free shipping')
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
                'label' => __('Sort Order'),
                'title' => __('Sort Order'),
                'required' => false,
                'class' => 'validate-number',
            ]
        );

        // Add store view selection
        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store_ids',
                'multiselect',
                [
                    'name' => 'store_ids[]',
                    'label' => __('Store Views'),
                    'title' => __('Store Views'),
                    'required' => false,
                    'values' => $this->systemStore->getStoreValuesForForm(false, true),
                    'note' => __('Leave empty or select all to apply to all store views.')
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                \Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element::class
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'store_ids',
                'hidden',
                ['name' => 'store_ids[]', 'value' => 0]
            );
            $model->setStoreIds([0]);
        }

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}