<?php
/**
 * Shipping Method Interface.
 *
 * @category   RCubed
 * @package    RCubed_ShippingManager
 * @author     R-Cubed Solutions <drussell@rcubedpro.com>
 * @copyright  2025 R-Cubed Solutions (https://www.rcubedpro.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 * @link       https://www.rcubedpro.com
 */
declare(strict_types=1);

namespace RCubed\ShippingManager\Api\Data;

/**
 * Interface for shipping method model
 * @api
 */
interface MethodInterface
{
    /**#@+
     * Constants for keys of data array
     */
    const METHOD_ID = 'method_id';
    const NAME = 'name';
    const CODE = 'code';
    const PRICE = 'price';
    const IS_ACTIVE = 'is_active';
    const SORT_ORDER = 'sort_order';
    const TITLE = 'title';
    const STORE_IDS = 'store_ids';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**#@-*/

    /**
     * Get method ID
     *
     * @return int|null
     */
    public function getMethodId();

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get code
     *
     * @return string
     */
    public function getCode(): string;

    /**
     * Get price
     *
     * @return float|null
     */
    public function getPrice();

    /**
     * Get active status
     *
     * @return bool
     */
    public function getIsActive(): bool;

    /**
     * Get sort order
     *
     * @return int
     */
    public function getSortOrder(): int;

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();
    
    /**
     * Get store IDs
     *
     * @return array
     */
    public function getStoreIds(): array;

    /**
     * Get creation time
     *
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * Get update time
     *
     * @return string
     */
    public function getUpdatedAt(): string;

    /**
     * Set method ID
     *
     * @param int $methodId
     * @return MethodInterface
     */
    public function setMethodId(int $methodId): MethodInterface;

    /**
     * Set name
     *
     * @param string $name
     * @return MethodInterface
     */
    public function setName(string $name): MethodInterface;

    /**
     * Set code
     *
     * @param string $code
     * @return MethodInterface
     */
    public function setCode(string $code): MethodInterface;

    /**
     * Set price
     *
     * @param float|null $price
     * @return MethodInterface
     */
    public function setPrice($price): MethodInterface;

    /**
     * Set active status
     *
     * @param bool|int $isActive
     * @return MethodInterface
     */
    public function setIsActive($isActive): MethodInterface;

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return MethodInterface
     */
    public function setSortOrder(int $sortOrder): MethodInterface;

    /**
     * Set title
     *
     * @param string|null $title
     * @return MethodInterface
     */
    public function setTitle($title): MethodInterface;
    
    /**
     * Set store IDs
     *
     * @param array $storeIds
     * @return MethodInterface
     */
    public function setStoreIds(array $storeIds): MethodInterface;

    /**
     * Set creation time
     *
     * @param string $createdAt
     * @return MethodInterface
     */
    public function setCreatedAt(string $createdAt): MethodInterface;

    /**
     * Set update time
     *
     * @param string $updatedAt
     * @return MethodInterface
     */
    public function setUpdatedAt(string $updatedAt): MethodInterface;
}