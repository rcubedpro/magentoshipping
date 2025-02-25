<?php
/**
 * Shipping Method Edit Controller.
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
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use RCubed\ShippingManager\Api\MethodRepositoryInterface;
use RCubed\ShippingManager\Model\Method;
use RCubed\ShippingManager\Model\MethodFactory;
use Psr\Log\LoggerInterface;
use Exception;

/**
 * Edit Controller for Shipping Methods
 */
class Edit extends Action
{
    /**
     * Authorization level
     */
    public const ADMIN_RESOURCE = 'RCubed_ShippingManager::method_edit';

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var MethodRepositoryInterface
     */
    private $methodRepository;

    /**
     * @var MethodFactory
     */
    private $methodFactory;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param MethodRepositoryInterface $methodRepository
     * @param MethodFactory $methodFactory
     * @param DataPersistorInterface $dataPersistor
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        MethodRepositoryInterface $methodRepository,
        MethodFactory $methodFactory,
        DataPersistorInterface $dataPersistor,
        LoggerInterface $logger
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->methodRepository = $methodRepository;
        $this->methodFactory = $methodFactory;
        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Edit shipping method action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        // Get ID and create model
        $id = $this->getRequest()->getParam('method_id');
        $resultPage = $this->resultPageFactory->create();
        
        // Set active menu
        $resultPage->setActiveMenu('RCubed_ShippingManager::shipping_methods');
        
        // Set title
        $resultPage->getConfig()->getTitle()->prepend(__('Shipping Methods'));
        
        // Try to load existing method or create new one
        try {
            /** @var Method $model */
            if ($id) {
                try {
                    $model = $this->methodRepository->getById((int)$id);
                    $resultPage->getConfig()->getTitle()->prepend(__('Edit Shipping Method: %1', $model->getName()));
                } catch (Exception $e) {
                    $this->messageManager->addErrorMessage(__('This shipping method no longer exists.'));
                    $resultRedirect = $this->resultRedirectFactory->create();
                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                $model = $this->methodFactory->create();
                // Default values for a new method
                $model->setData('is_active', true);
                $resultPage->getConfig()->getTitle()->prepend(__('New Shipping Method'));
            }
            
            // Register model to use later in blocks
            $this->coreRegistry->register('rcubed_shippingmanager_method', $model);
            
            return $resultPage;
            
        } catch (Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(__('An error occurred while loading the shipping method.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }
    }
}