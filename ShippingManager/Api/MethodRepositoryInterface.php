<?php
/**
 * Shipping Method Repository Interface.
 *
 * @category   RCubed
 * @package    RCubed_ShippingManager
 * @author     R-Cubed Solutions <drussell@rcubedpro.com>
 * @copyright  2025 R-Cubed Solutions (https://www.rcubedpro.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 * @link       https://www.rcubedpro.com
 */
declare(strict_types=1);

namespace RCubed\ShippingManager\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use RCubed\ShippingManager\Api\Data\MethodInterface;

/**
 * Interface for shipping method repository
 * @api
 */
interface MethodRepositoryInterface
{
    /**
     * Save shipping method
     *
     * @param MethodInterface $method
     * @return MethodInterface
     * @throws CouldNotSaveException
     */
    public function save(MethodInterface $method): MethodInterface;

    /**
     * Get method by ID
     *
     * @param int $methodId
     * @return MethodInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $methodId): MethodInterface;

    /**
     * Get method by code
     *
     * @param string $code
     * @return MethodInterface
     * @throws NoSuchEntityException
     */
    public function getByCode(string $code): MethodInterface;

    /**
     * Get list of methods
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * Delete shipping method
     *
     * @param MethodInterface $method
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(MethodInterface $method): bool;

    /**
     * Delete shipping method by ID
     *
     * @param int $methodId
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $methodId): bool;
}