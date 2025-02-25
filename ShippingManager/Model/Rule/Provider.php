<?php
/**
 * Shipping Rule Provider.
 *
 * @category   RCubed
 * @package    RCubed_ShippingManager
 * @author     R-Cubed Solutions <drussell@rcubedpro.com>
 * @copyright  2025 R-Cubed Solutions (https://www.rcubedpro.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 * @link       https://www.rcubedpro.com
 */
declare(strict_types=1);

namespace RCubed\ShippingManager\Model\Rule;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Quote\Model\Quote\Address;
use RCubed\ShippingManager\Api\Data\RuleInterface;
use RCubed\ShippingManager\Api\RuleRepositoryInterface;

/**
 * Provider class for shipping rules
 */
class Provider
{
    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var array
     */
    private $activeRules = null;

    /**
     * @param RuleRepositoryInterface $ruleRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        RuleRepositoryInterface $ruleRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->ruleRepository = $ruleRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Get active rules
     *
     * @return RuleInterface[]
     */
    public function getActiveRules()
    {
        if ($this->activeRules === null) {
            $this->searchCriteriaBuilder->addFilter('is_active', 1);
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $searchResults = $this->ruleRepository->getList($searchCriteria);
            $this->activeRules = $searchResults->getItems();
        }
        
        return $this->activeRules;
    }

    /**
     * Get allowed methods for the given shipping address
     *
     * @param Address $address
     * @return array
     */
    public function getAllowedMethods(Address $address)
    {
        // This will be implemented to return the list of allowed shipping methods
        // based on shipping rules that match the given address
        return [];
    }

    /**
     * Reset cached rules
     *
     * @return void
     */
    public function resetRules()
    {
        $this->activeRules = null;
    }
}