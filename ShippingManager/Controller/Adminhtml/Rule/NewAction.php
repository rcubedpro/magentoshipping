<?php
/**
 * Shipping Rule NewAction Controller.
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
use Magento\Backend\Model\View\Result\Forward;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Controller\ResultInterface;
use Psr\Log\LoggerInterface;

/**
 * New Action controller for shipping rules
 */
class NewAction extends Action
{
    /**
     * Authorization level
     */
    public const ADMIN_RESOURCE = 'RCubed_ShippingManager::rule_create';

    /**
     * @var ForwardFactory
     */
    private $resultForwardFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Context $context
     * @param ForwardFactory $resultForwardFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        ForwardFactory $resultForwardFactory,
        LoggerInterface $logger
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Create new shipping rule action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        // Properly log action using dependency injection instead of ObjectManager
        $this->logger->debug('New Rule creation initiated');
        
        /** @var Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}