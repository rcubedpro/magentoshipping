<?php
/**
 * Shipping Method Index Controller.
 *
 * @category   RCubed
 * @package    RCubed_ShippingManager
 * @author     R-Cubed Solutions <drussell@rcubedpro.com>
 * @copyright  2025 R-Cubed Solutions (https://www.rcubedpro.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 * @link       https://www.rcubedpro.com
 */
declare(strict_types=1);

namespace RCubed\ShippingManager\Controller\Adminhtml\Method;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Shipping Method Index controller
 */
class Index extends Action
{
    /**
     * Authorization level
     */
    const ADMIN_RESOURCE = 'RCubed_ShippingManager::shipping_methods';

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
        $resultPage->setActiveMenu('RCubed_ShippingManager::shipping_methods');
        $resultPage->addBreadcrumb(__('R-Cubed Solutions'), __('R-Cubed Solutions'));
        $resultPage->addBreadcrumb(__('Shipping Methods'), __('Shipping Methods'));
        $resultPage->getConfig()->getTitle()->prepend(__('Shipping Methods'));
        
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
            'id' => 'add_new_method',
            'label' => __('Add New Method'),
            'class' => 'primary',
            'button_class' => '',
            'onclick' => "location.href = '" . $this->getUrl('rcubed_shippingmanager/method/edit') . "';"
        ];
        
        $buttonList = $resultPage->getLayout()->getBlock('page.actions.toolbar')->getChildBlock('page.actions.toolbar.button');
        if ($buttonList) {
            $buttonList->addButton('add', $addButtonProps);
        }
    }
}