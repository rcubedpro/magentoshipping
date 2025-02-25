<?php
/**
 * Shipping Method Delete Controller.
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
use RCubed\ShippingManager\Api\MethodRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Delete shipping method controller
 */
class Delete extends Action
{
    /**
     * Authorization level
     */
    const ADMIN_RESOURCE = 'RCubed_ShippingManager::method_delete';

    /**
     * @var MethodRepositoryInterface
     */
    protected $methodRepository;

    /**
     * @param Context $context
     * @param MethodRepositoryInterface $methodRepository
     */
    public function __construct(
        Context $context,
        MethodRepositoryInterface $methodRepository
    ) {
        $this->methodRepository = $methodRepository;
        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $this->methodRepository->deleteById((int)$id);
                $this->messageManager->addSuccessMessage(__('The shipping method has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('The shipping method no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while deleting the shipping method.'));
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        
        $this->messageManager->addErrorMessage(__('We can\'t find a shipping method to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}