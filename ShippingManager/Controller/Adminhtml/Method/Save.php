<?php
/**
 * Shipping Method Save Controller.
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
use Magento\Framework\Exception\LocalizedException;
use RCubed\ShippingManager\Api\MethodRepositoryInterface;
use RCubed\ShippingManager\Model\MethodFactory;
use Psr\Log\LoggerInterface;
use Exception;

/**
 * Save Controller for Shipping Methods
 */
class Save extends Action
{
    /**
     * Authorization level
     */
    public const ADMIN_RESOURCE = 'RCubed_ShippingManager::method_save';

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
     * @param MethodRepositoryInterface $methodRepository
     * @param MethodFactory $methodFactory
     * @param DataPersistorInterface $dataPersistor
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        MethodRepositoryInterface $methodRepository,
        MethodFactory $methodFactory,
        DataPersistorInterface $dataPersistor,
        LoggerInterface $logger
    ) {
        $this->methodRepository = $methodRepository;
        $this->methodFactory = $methodFactory;
        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Save shipping method action
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
        
        // Handle the store_ids field properly (it's a multiselect)
        if (isset($data['store_ids']) && is_array($data['store_ids'])) {
            $data['store_ids'] = array_filter($data['store_ids']);
        } else {
            $data['store_ids'] = [0]; // Default store view if not selected
        }
        
        // Remove form key from data
        if (isset($data['form_key'])) {
            unset($data['form_key']);
        }
        
        $id = $this->getRequest()->getParam('method_id');
        
        try {
            if ($id) {
                $model = $this->methodRepository->getById((int)$id);
            } else {
                $model = $this->methodFactory->create();
            }
            
            $model->setData($data);
            
            $this->methodRepository->save($model);
            
            $this->messageManager->addSuccessMessage(__('You saved the shipping method.'));
            $this->dataPersistor->clear('rcubed_shippingmanager_method');
            
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['method_id' => $model->getId()]);
            }
            
            return $resultRedirect->setPath('*/*/');
            
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->dataPersistor->set('rcubed_shippingmanager_method', $data);
            
            if ($id) {
                return $resultRedirect->setPath('*/*/edit', ['method_id' => $id]);
            }
            
            return $resultRedirect->setPath('*/*/new');
            
        } catch (Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(__('An error occurred while saving the shipping method.'));
            $this->dataPersistor->set('rcubed_shippingmanager_method', $data);
            
            if ($id) {
                return $resultRedirect->setPath('*/*/edit', ['method_id' => $id]);
            }
            
            return $resultRedirect->setPath('*/*/new');
        }
    }
}