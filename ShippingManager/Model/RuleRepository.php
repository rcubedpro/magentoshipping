<?php
/**
 * Shipping Rule Repository.
 *
 * @category   RCubed
 * @package    RCubed_ShippingManager
 * @author     R-Cubed Solutions <drussell@rcubedpro.com>
 * @copyright  2025 R-Cubed Solutions (https://www.rcubedpro.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 * @link       https://www.rcubedpro.com
 */
declare(strict_types=1);

namespace RCubed\ShippingManager\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use RCubed\ShippingManager\Api\Data\RuleInterface;
use RCubed\ShippingManager\Api\Data\RuleSearchResultsInterfaceFactory;
use RCubed\ShippingManager\Api\RuleRepositoryInterface;
use RCubed\ShippingManager\Model\ResourceModel\Rule as RuleResource;
use RCubed\ShippingManager\Model\ResourceModel\Rule\CollectionFactory;

/**
 * Shipping Rule Repository implementation
 */
class RuleRepository implements RuleRepositoryInterface
{
    /**
     * @var RuleResource
     */
    private $ruleResource;

    /**
     * @var RuleFactory
     */
    private $ruleFactory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var RuleSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param RuleResource $ruleResource
     * @param RuleFactory $ruleFactory
     * @param CollectionFactory $collectionFactory
     * @param RuleSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        RuleResource $ruleResource,
        RuleFactory $ruleFactory,
        CollectionFactory $collectionFactory,
        RuleSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->ruleResource = $ruleResource;
        $this->ruleFactory = $ruleFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Save shipping rule
     *
     * @param RuleInterface $rule
     * @return RuleInterface
     * @throws CouldNotSaveException
     */
    public function save(RuleInterface $rule): RuleInterface
    {
        try {
            $this->ruleResource->save($rule);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        
        return $rule;
    }

    /**
     * Get rule by ID
     *
     * @param int $ruleId
     * @return RuleInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $ruleId): RuleInterface
    {
        $rule = $this->ruleFactory->create();
        $this->ruleResource->load($rule, $ruleId);
        
        if (!$rule->getId()) {
            throw new NoSuchEntityException(__('The shipping rule with ID "%1" does not exist.', $ruleId));
        }
        
        return $rule;
    }

    /**
     * Get list of rules
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        
        return $searchResults;
    }

    /**
     * Delete shipping rule
     *
     * @param RuleInterface $rule
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(RuleInterface $rule): bool
    {
        try {
            $this->ruleResource->delete($rule);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        
        return true;
    }

    /**
     * Delete shipping rule by ID
     *
     * @param int $ruleId
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $ruleId): bool
    {
        return $this->delete($this->getById($ruleId));
    }
}