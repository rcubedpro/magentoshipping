<?php
/**
 * Plugin for adding custom shipping methods to carrier list.
 *
 * @category   RCubed
 * @package    RCubed_ShippingManager
 * @author     R-Cubed Solutions <drussell@rcubedpro.com>
 * @copyright  2025 R-Cubed Solutions (https://www.rcubedpro.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 * @link       https://www.rcubedpro.com
 */
declare(strict_types=1);

namespace RCubed\ShippingManager\Plugin\Shipping;

use Magento\Shipping\Model\Config;
use RCubed\ShippingManager\Model\Carrier\CustomShipping;
use Magento\Framework\ObjectManagerInterface;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Rate\ResultFactory;
use Psr\Log\LoggerInterface;
use RCubed\ShippingManager\Model\ResourceModel\Method\CollectionFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Plugin to add custom shipping carrier to active carriers
 */
class CarrierConfig
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;
    
    /**
     * @var LoggerInterface
     */
    private $logger;
    
    /**
     * @var ErrorFactory
     */
    private $errorFactory;
    
    /**
     * @var ResultFactory
     */
    private $rateResultFactory;
    
    /**
     * @var MethodFactory
     */
    private $rateMethodFactory;
    
    /**
     * @var CollectionFactory
     */
    private $methodCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param LoggerInterface $logger
     * @param ErrorFactory $errorFactory
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param CollectionFactory $methodCollectionFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        LoggerInterface $logger,
        ErrorFactory $errorFactory,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        CollectionFactory $methodCollectionFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->objectManager = $objectManager;
        $this->logger = $logger;
        $this->errorFactory = $errorFactory;
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->methodCollectionFactory = $methodCollectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * Add custom shipping carrier to active carriers
     *
     * @param Config $subject
     * @param array $result
     * @return array
     */
    public function afterGetActiveCarriers(Config $subject, $result)
    {
        if (!isset($result['rcubedshipping'])) {
            $scopeConfig = $subject->getScopeConfig();
            $storeId = $this->storeManager->getStore()->getId();
            
            $isActive = (bool)$scopeConfig->getValue(
                'carriers/rcubedshipping/active',
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
            
            // Check if we have any active methods
            $hasActiveMethods = false;
            $methodCollection = $this->methodCollectionFactory->create();
            $methodCollection->addFieldToFilter('is_active', 1);
            if ($methodCollection->getSize() > 0) {
                $hasActiveMethods = true;
            }
            
            if ($isActive || $hasActiveMethods) {
                $result['rcubedshipping'] = $this->objectManager->create(
                    CustomShipping::class,
                    [
                        'scopeConfig' => $scopeConfig,
                        'rateErrorFactory' => $this->errorFactory,
                        'logger' => $this->logger,
                        'rateResultFactory' => $this->rateResultFactory,
                        'rateMethodFactory' => $this->rateMethodFactory,
                        'methodCollectionFactory' => $this->methodCollectionFactory
                    ]
                );
            }
        }
        
        return $result;
    }
}