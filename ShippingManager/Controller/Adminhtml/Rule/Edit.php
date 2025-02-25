<?php
/**
 * Shipping Rule Edit Controller.
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
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use RCubed\ShippingManager\Api\RuleRepositoryInterface;
use RCubed\ShippingManager\Model\Rule;
use RCubed\ShippingManager\Model\RuleFactory;
use Psr\Log\LoggerInterface;
use Exception;

/**
 * Edit Controller for Shipping Rules
 */
class Edit extends Action
{
    /**
     * Authorization level
     */
    public const ADMIN_RESOURCE = 'RCubed_ShippingManager::rule_edit';

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var RuleFactory
     */
    private $ruleFactory;

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
     * @param RuleRepositoryInterface $ruleRepository
     * @param RuleFactory $ruleFactory
     * @param DataPersistorInterface $dataPersistor
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        RuleRepositoryInterface $ruleRepository,
        RuleFactory $ruleFactory,
        DataPersistorInterface $dataPersistor,
        LoggerInterface $logger
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        $this->ruleRepository = $ruleRepository;
        $this->ruleFactory = $ruleFactory;
        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Edit shipping rule action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        // Get ID and create model
        $id = $this->getRequest()->getParam('rule_id');
        $resultPage = $this->resultPageFactory->create();
        
        // Set active menu
        $resultPage->setActiveMenu('RCubed_ShippingManager::shipping_rules');
        
        // Set title
        $resultPage->getConfig()->getTitle()->prepend(__('Shipping Rules'));
        
        // Try to load existing rule or create new one
        try {
            /** @var Rule $model */
            if ($id) {
                try {
                    $model = $this->ruleRepository->getById((int)$id);
                    $resultPage->getConfig()->getTitle()->prepend(__('Edit Shipping Rule: %1', $model->getName()));
                } catch (Exception $e) {
                    $this->messageManager->addErrorMessage(__('This shipping rule no longer exists.'));
                    $resultRedirect = $this->resultRedirectFactory->create();
                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                $model = $this->ruleFactory->create();
                // Default values for a new rule
                $model->setData('is_active', true);
                $resultPage->getConfig()->getTitle()->prepend(__('New Shipping Rule'));
            }
            
            // Register model to use later in blocks
            $this->coreRegistry->register('rcubed_shippingmanager_rule', $model);
            
            return $resultPage;
            
        } catch (Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(__('An error occurred while loading the shipping rule.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }
    }
}