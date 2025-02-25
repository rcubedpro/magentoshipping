<?php
/**
 * Custom shipping carrier model.
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
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Psr\Log\LoggerInterface;
use RCubed\ShippingManager\Api\MethodRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use RCubed\ShippingManager\Model\Rule\Provider as RuleProvider;

/**
 * Custom shipping carrier implementation
 */
class CustomShipping extends AbstractCarrier implements CarrierInterface
{
    /**
     * Carrier code
     */
    const CARRIER_CODE = 'rcubed_custom';

    /**
     * @var string
     */
    protected $_code = self::CARRIER_CODE;

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
     * @var MethodRepositoryInterface
     */
    private $methodRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var RuleProvider
     */
    private $ruleProvider;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param MethodRepositoryInterface $methodRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RuleProvider $ruleProvider
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger = null,
        ResultFactory $rateResultFactory = null,
        MethodFactory $rateMethodFactory = null,
        MethodRepositoryInterface $methodRepository = null,
        SearchCriteriaBuilder $searchCriteriaBuilder = null,
        RuleProvider $ruleProvider = null,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->methodRepository = $methodRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->ruleProvider = $ruleProvider;
    }

    /**
     * Collect shipping rates
     *
     * @param RateRequest $request
     * @return \Magento\Shipping\Model\Rate\Result|bool
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $result = $this->rateResultFactory->create();

        // We'll implement the method retrieval and rule application logic here
        // in future implementations
        
        return $result;
    }

    /**
     * Get allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        $methods = [];
        
        // We'll implement the retrieval of active methods here
        // in future implementations
        
        return $methods;
    }
}