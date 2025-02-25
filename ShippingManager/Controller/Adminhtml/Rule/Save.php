<?php
/**
 * Shipping Rule Save Controller.
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
use Magento\Framework\Exception\LocalizedException;
use RCubed\ShippingManager\Api\RuleRepositoryInterface;
use RCubed\ShippingManager\Model\RuleFactory;
use Psr\Log\LoggerInterface;
use Exception;

/**
 * Save Controller for Shipping Rules
 */
class Save extends Action
{
    /**
     * Authorization level
     */
    public const ADMIN_RESOURCE = 'RCubed_ShippingManager::rule_save';

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
     * @param RuleRepositoryInterface $ruleRepository
     * @param RuleFactory $ruleFactory
     * @param DataPersistorInterface $dataPersistor
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        RuleRepositoryInterface $ruleRepository,
        RuleFactory $ruleFactory,
        DataPersistorInterface $dataPersistor,
        LoggerInterface $logger
    ) {
        $this->ruleRepository = $ruleRepository;
        $this->ruleFactory = $ruleFactory;
        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Save shipping rule action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        
        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }
        
        // Handle multiselect fields properly
        if (isset($data['store_ids']) && is_array($data['store_ids'])) {
            $data['store_ids'] = array_filter($data['store_ids']);
        } else {
            $data['store_ids'] = [0]; // Default store view if not selected
        }
        
        if (isset($data['customer_group_ids']) && is_array($data['customer_group_ids'])) {
            $data['customer_group_ids'] = array_filter($data['customer_group_ids']);
        } else {
            $data['customer_group_ids'] = [];
        }
        
        if (isset($data['restricted_method_ids']) && is_array($data['restricted_method_ids'])) {
            $data['restricted_method_ids'] = array_filter($data['restricted_method_ids']);
        } else {
            $data['restricted_method_ids'] = [];
        }
        
        // Remove form key from data
        if (isset($data['form_key'])) {
            unset($data['form_key']);
        }
        
        $id = $this->getRequest()->getParam('rule_id');
        
        try {
            if ($id) {
                $model = $this->ruleRepository->getById((int)$id);
            } else {
                $model = $this->ruleFactory->create();
            }
            
            $model->setData($data);
            
            $this->ruleRepository->save($model);
            
            $this->messageManager->addSuccessMessage(__('You saved the shipping rule.'));
            $this->dataPersistor->clear('rcubed_shippingmanager_rule');
            
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['rule_id' => $model->getId()]);
            }
            
            return $resultRedirect->setPath('*/*/');
            
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->dataPersistor->set('rcubed_shippingmanager_rule', $data);
            
            if ($id) {
                return $resultRedirect->setPath('*/*/edit', ['rule_id' => $id]);
            }
            
            return $resultRedirect->setPath('*/*/new');
            
        } catch (Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(__('An error occurred while saving the shipping rule.'));
            $this->dataPersistor->set('rcubed_shippingmanager_rule', $data);
            
            if ($id) {
                return $resultRedirect->setPath('*/*/edit', ['rule_id' => $id]);
            }
            
            return $resultRedirect->setPath('*/*/new');
        }
    }
}