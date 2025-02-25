<?php
/**
 * Shipping Method Repository.
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
use RCubed\ShippingManager\Api\Data\MethodInterface;
use RCubed\ShippingManager\Api\Data\MethodSearchResultsInterfaceFactory;
use RCubed\ShippingManager\Api\MethodRepositoryInterface;
use RCubed\ShippingManager\Model\ResourceModel\Method as MethodResource;
use RCubed\ShippingManager\Model\ResourceModel\Method\CollectionFactory;

/**
 * Shipping Method Repository implementation
 */
class MethodRepository implements MethodRepositoryInterface
{
    /**
     * @var MethodResource
     */
    private $methodResource;

    /**
     * @var MethodFactory
     */
    private $methodFactory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var MethodSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param MethodResource $methodResource
     * @param MethodFactory $methodFactory
     * @param CollectionFactory $collectionFactory
     * @param MethodSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        MethodResource $methodResource,
        MethodFactory $methodFactory,
        CollectionFactory $collectionFactory,
        MethodSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->methodResource = $methodResource;
        $this->methodFactory = $methodFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Save shipping method
     *
     * @param MethodInterface $method
     * @return MethodInterface
     * @throws CouldNotSaveException
     */
    public function save(MethodInterface $method): MethodInterface
    {
        try {
            $this->methodResource->save($method);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        
        return $method;
    }

    /**
     * Get method by ID
     *
     * @param int $methodId
     * @return MethodInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $methodId): MethodInterface
    {
        $method = $this->methodFactory->create();
        $this->methodResource->load($method, $methodId);
        
        if (!$method->getId()) {
            throw new NoSuchEntityException(__('The shipping method with ID "%1" does not exist.', $methodId));
        }
        
        return $method;
    }

    /**
     * Get method by code
     *
     * @param string $code
     * @return MethodInterface
     * @throws NoSuchEntityException
     */
    public function getByCode(string $code): MethodInterface
    {
        $method = $this->methodFactory->create();
        $this->methodResource->load($method, $code, 'code');
        
        if (!$method->getId()) {
            throw new NoSuchEntityException(__('The shipping method with code "%1" does not exist.', $code));
        }
        
        return $method;
    }

    /**
     * Get list of methods
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
     * Delete shipping method
     *
     * @param MethodInterface $method
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(MethodInterface $method): bool
    {
        try {
            $this->methodResource->delete($method);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        
        return true;
    }

    /**
     * Delete shipping method by ID
     *
     * @param int $methodId
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $methodId): bool
    {
        return $this->delete($this->getById($methodId));
    }
}