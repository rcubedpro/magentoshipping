<?php
/**
 * Shipping Rule Delete Controller.
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
use RCubed\ShippingManager\Api\RuleRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Delete shipping rule controller
 */
class Delete extends Action
{
    /**
     * Authorization level
     */
    const ADMIN_RESOURCE = 'RCubed_ShippingManager::rule_delete';

    /**
     * @var RuleRepositoryInterface
     */
    protected $ruleRepository;

    /**
     * @param Context $context
     * @param RuleRepositoryInterface $ruleRepository
     */
    public function __construct(
        Context $context,
        RuleRepositoryInterface $ruleRepository
    ) {
        $this->ruleRepository = $ruleRepository;
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
                $this->ruleRepository->deleteById((int)$id);
                $this->messageManager->addSuccessMessage(__('The shipping rule has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('The shipping rule no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while deleting the shipping rule.'));
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        
        $this->messageManager->addErrorMessage(__('We can\'t find a shipping rule to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}