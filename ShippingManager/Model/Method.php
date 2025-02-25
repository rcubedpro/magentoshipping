<?php
/**
 * Shipping Method Model.
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

use Magento\Framework\Model\AbstractModel;
use RCubed\ShippingManager\Api\Data\MethodInterface;

/**
 * Shipping Method Model
 */
class Method extends AbstractModel implements MethodInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'rcubed_shipping_method';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Method::class);
    }

    /**
     * Before save processing
     *
     * @return $this
     */
    public function beforeSave()
    {
        // Convert store IDs array to string before saving to database
        $storeIds = $this->getStoreIds();
        if (is_array($storeIds)) {
            $this->setData('store_ids', implode(',', $storeIds));
        }
        
        return parent::beforeSave();
    }

    /**
     * After load processing
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        // Convert store IDs string to array after loading from database
        $storeIds = $this->getData('store_ids');
        if (is_string($storeIds) && !empty($storeIds)) {
            $this->setData('store_ids', explode(',', $storeIds));
        } elseif (empty($storeIds)) {
            $this->setData('store_ids', [0]);
        }
        
        return parent::_afterLoad();
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::METHOD_ID) === null ? null : (int)$this->getData(self::METHOD_ID);
    }

    /**
     * Get method ID
     *
     * @return int|null
     */
    public function getMethodId()
    {
        return $this->getData(self::METHOD_ID) === null ? null : (int)$this->getData(self::METHOD_ID);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->getData(self::NAME);
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode(): string
    {
        return (string)$this->getData(self::CODE);
    }

    /**
     * Get price
     *
     * @return float|null
     */
    public function getPrice()
    {
        $price = $this->getData(self::PRICE);
        return $price === null ? null : (float)$price;
    }

    /**
     * Get active status
     *
     * @return bool
     */
    public function getIsActive(): bool
    {
        return (bool)$this->getData(self::IS_ACTIVE);
    }

    /**
     * Get sort order
     *
     * @return int
     */
    public function getSortOrder(): int
    {
        return (int)$this->getData(self::SORT_ORDER);
    }

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }
    
    /**
     * Get store IDs
     *
     * @return array
     */
    public function getStoreIds(): array
    {
        $storeIds = $this->getData(self::STORE_IDS);
        
        if (is_string($storeIds) && !empty($storeIds)) {
            return explode(',', $storeIds);
        }
        
        if (is_array($storeIds)) {
            return $storeIds;
        }
        
        return [0]; // Default store view
    }

    /**
     * Get creation time
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        return (string)$this->getData(self::CREATED_AT);
    }

    /**
     * Get update time
     *
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return (string)$this->getData(self::UPDATED_AT);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return MethodInterface
     */
    public function setId($id)
    {
        return $this->setData(self::METHOD_ID, $id);
    }

    /**
     * Set method ID
     *
     * @param int $methodId
     * @return MethodInterface
     */
    public function setMethodId(int $methodId): MethodInterface
    {
        return $this->setData(self::METHOD_ID, $methodId);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return MethodInterface
     */
    public function setName(string $name): MethodInterface
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Set code
     *
     * @param string $code
     * @return MethodInterface
     */
    public function setCode(string $code): MethodInterface
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * Set price
     *
     * @param float|null $price
     * @return MethodInterface
     */
    public function setPrice($price): MethodInterface
    {
        if ($price !== null) {
            $price = (float)$price;
        }
        return $this->setData(self::PRICE, $price);
    }

    /**
     * Set active status
     *
     * @param bool|int $isActive
     * @return MethodInterface
     */
    public function setIsActive($isActive): MethodInterface
    {
        return $this->setData(self::IS_ACTIVE, (bool)$isActive);
    }

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return MethodInterface
     */
    public function setSortOrder(int $sortOrder): MethodInterface
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * Set title
     *
     * @param string|null $title
     * @return MethodInterface
     */
    public function setTitle($title): MethodInterface
    {
        return $this->setData(self::TITLE, $title);
    }
    
    /**
     * Set store IDs
     *
     * @param array $storeIds
     * @return MethodInterface
     */
    public function setStoreIds(array $storeIds): MethodInterface
    {
        return $this->setData(self::STORE_IDS, $storeIds);
    }

    /**
     * Set creation time
     *
     * @param string $createdAt
     * @return MethodInterface
     */
    public function setCreatedAt(string $createdAt): MethodInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Set update time
     *
     * @param string $updatedAt
     * @return MethodInterface
     */
    public function setUpdatedAt(string $updatedAt): MethodInterface
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}