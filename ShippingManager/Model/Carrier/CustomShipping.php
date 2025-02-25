<?php
/**
 * Custom Shipping Carrier implementation.
 *
 * @category   RCubed
 * @package    RCubed_ShippingManager
 * @author     R-Cubed Solutions <drussell@rcubedpro.com>
 * @copyright  2025 R-Cubed Solutions (https://www.rcubedpro.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 * @link       https://www.rcubedpro.com
 */
declare(strict_types=1);

namespace RCubed\ShippingManager\Model\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Psr\Log\LoggerInterface;
use RCubed\ShippingManager\Model\ResourceModel\Method\CollectionFactory as MethodCollectionFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Shipping\Model\Rate\Result;

/**
 * Custom shipping carrier
 */
class CustomShipping extends AbstractCarrier implements CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'rcubedshipping';

    /**
     * @var bool
     */
    protected $_isFixed = true;

    /**
     * @var ResultFactory
     */
    private $rateResultFactory;

    /**
     * @var MethodFactory
     */
    private $rateMethodFactory;

    /**
     * @var MethodCollectionFactory
     */
    private $methodCollectionFactory;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param MethodCollectionFactory $methodCollectionFactory
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        MethodCollectionFactory $methodCollectionFactory,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->methodCollectionFactory = $methodCollectionFactory;
    }

    /**
     * Collect rates for this shipping method
     *
     * @param RateRequest $request
     * @return Result|bool
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        /** @var Result $result */
        $result = $this->rateResultFactory->create();

        // Get all active shipping methods
        $methodCollection = $this->methodCollectionFactory->create();
        $methodCollection->addFieldToFilter('is_active', 1);
        
        // Get current store ID
        $storeId = $request->getStoreId();

        foreach ($methodCollection as $method) {
            // Check if method is enabled for this store
            $storeIds = $method->getStoreIds();
            if (!empty($storeIds) && !in_array($storeId, $storeIds) && !in_array('0', $storeIds) && !in_array(0, $storeIds)) {
                continue;
            }

            // TODO: Apply shipping rules here

            /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $rate */
            $rate = $this->rateMethodFactory->create();
            $rate->setCarrier($this->_code);
            $rate->setCarrierTitle($this->getConfigData('title'));
            
            $rate->setMethod($method->getCode());
            $rate->setMethodTitle($method->getTitle() ?: $method->getName());
            
            // Set shipping cost
            $price = $method->getPrice() ?: 0.00;
            $rate->setPrice($price);
            $rate->setCost($price);
            
            $result->append($rate);
        }

        return $result;
    }

    /**
     * Get allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        $allowedMethods = [];
        
        $methodCollection = $this->methodCollectionFactory->create();
        $methodCollection->addFieldToFilter('is_active', 1);
        
        foreach ($methodCollection as $method) {
            $allowedMethods[$method->getCode()] = $method->getName();
        }
        
        return !empty($allowedMethods) ? $allowedMethods : ['rcubedshipping' => $this->getConfigData('title')];
    }

    /**
     * Check if carrier has shipping tracking option available
     *
     * @return bool
     */
    public function isTrackingAvailable()
    {
        return false;
    }

    /**
     * Check if carrier has shipping label option available
     *
     * @return bool
     */
    public function isShippingLabelsAvailable()
    {
        return false;
    }
}