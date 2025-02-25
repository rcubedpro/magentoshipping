<?php
/**
 * Shipping Rule Index Controller.
 *
 * @category   RCubed
 * @package    RCubed_ShippingManager
 * @author     R-Cubed Solutions <drussell@rcubedpro.com>
 * @copyright  2025 R-Cubed Solutions (https://www.rcubedpro.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 * @link       https://www.rcubedpro.com
 */
declare(strict_types=1);

namespace RCubed\ShippingManager\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Shipping Rule Index controller
 */
class Index extends Action
{
    /**
     * Authorization level
     */
    const ADMIN_RESOURCE = 'RCubed_ShippingManager::shipping_rules';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('RCubed_ShippingManager::shipping_rules');
        $resultPage->addBreadcrumb(__('R-Cubed Solutions'), __('R-Cubed Solutions'));
        $resultPage->addBreadcrumb(__('Shipping Rules'), __('Shipping Rules'));
        $resultPage->getConfig()->getTitle()->prepend(__('Shipping Rules'));
        
        // Add button using Magento's standard approach
        $this->_addButtonsToToolbar($resultPage);
        
        return $resultPage;
    }
    
    /**
     * Add buttons to toolbar
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return void
     */
    protected function _addButtonsToToolbar($resultPage)
    {
        $addButtonProps = [
            'id' => 'add_new_rule',
            'label' => __('Add New Rule'),
            'class' => 'primary',
            'button_class' => '',
            'onclick' => "location.href = '" . $this->getUrl('rcubed_shippingmanager/rule/edit') . "';"
        ];
        
        $buttonList = $resultPage->getLayout()->getBlock('page.actions.toolbar')->getChildBlock('page.actions.toolbar.button');
        if ($buttonList) {
            $buttonList->addButton('add', $addButtonProps);
        }
    }
}