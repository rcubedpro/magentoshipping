<?php
/**
 * Shipping Rule Model.
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
use RCubed\ShippingManager\Api\Data\RuleInterface;
use RCubed\ShippingManager\Model\ResourceModel\Rule as ResourceModel;

/**
 * Shipping Rule Model
 */
class Rule extends AbstractModel implements RuleInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'rcubed_shipping_rule';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Before save processing
     *
     * @return $this
     */
    public function beforeSave()
    {
        // Convert arrays to strings before saving to database
        $storeIds = $this->getStoreIds();
        if (is_array($storeIds)) {
            $this->setData('store_ids', implode(',', $storeIds));
        }
        
        $customerGroupIds = $this->getCustomerGroupIds();
        if (is_array($customerGroupIds)) {
            $this->setData('customer_group_ids', implode(',', $customerGroupIds));
        }
        
        $restrictedMethodIds = $this->getRestrictedMethodIds();
        if (is_array($restrictedMethodIds)) {
            $this->setData('restricted_method_ids', implode(',', $restrictedMethodIds));
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
        // Convert strings to arrays after loading from database
        $storeIds = $this->getData('store_ids');
        if (is_string($storeIds) && !empty($storeIds)) {
            $this->setData('store_ids', explode(',', $storeIds));
        } elseif (empty($storeIds)) {
            $this->setData('store_ids', [0]);
        }
        
        $customerGroupIds = $this->getData('customer_group_ids');
        if (is_string($customerGroupIds) && !empty($customerGroupIds)) {
            $this->setData('customer_group_ids', explode(',', $customerGroupIds));
        } elseif (empty($customerGroupIds)) {
            $this->setData('customer_group_ids', []);
        }
        
        $restrictedMethodIds = $this->getData('restricted_method_ids');
        if (is_string($restrictedMethodIds) && !empty($restrictedMethodIds)) {
            $this->setData('restricted_method_ids', explode(',', $restrictedMethodIds));
        } elseif (empty($restrictedMethodIds)) {
            $this->setData('restricted_method_ids', []);
        }
        
        return parent::_afterLoad();
    }

    /**
     * @inheritdoc
     */
    public function getRuleId(): ?int
    {
        return $this->getData(self::RULE_ID) === null ? null : (int)$this->getData(self::RULE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setRuleId(?int $ruleId): RuleInterface
    {
        return $this->setData(self::RULE_ID, $ruleId);
    }

    /**
     * @inheritdoc
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritdoc
     */
    public function setName(?string $name): RuleInterface
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * @inheritdoc
     */
    public function setDescription(?string $description): RuleInterface
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @inheritdoc
     */
    public function getConditionsSerialized(): ?string
    {
        return $this->getData(self::CONDITIONS_SERIALIZED);
    }

    /**
     * @inheritdoc
     */
    public function setConditionsSerialized(?string $conditionsSerialized): RuleInterface
    {
        return $this->setData(self::CONDITIONS_SERIALIZED, $conditionsSerialized);
    }

    /**
     * @inheritdoc
     */
    public function getIsActive(): bool
    {
        return (bool)$this->getData(self::IS_ACTIVE);
    }

    /**
     * @inheritdoc
     */
    public function setIsActive($isActive): RuleInterface
    {
        return $this->setData(self::IS_ACTIVE, (bool)$isActive);
    }

    /**
     * @inheritdoc
     */
    public function getSortOrder(): ?int
    {
        return $this->getData(self::SORT_ORDER) === null ? null : (int)$this->getData(self::SORT_ORDER);
    }

    /**
     * @inheritdoc
     */
    public function setSortOrder(?int $sortOrder): RuleInterface
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * @inheritdoc
     */
    public function getStoreIds(): array
    {
        $storeIds = $this->getData(self::STORE_IDS);
        if (is_string($storeIds) && !empty($storeIds)) {
            return explode(',', $storeIds);
        } elseif (is_array($storeIds)) {
            return $storeIds;
        }
        return [0]; // Default store
    }

    /**
     * @inheritdoc
     */
    public function setStoreIds(array $storeIds): RuleInterface
    {
        return $this->setData(self::STORE_IDS, $storeIds);
    }

    /**
     * @inheritdoc
     */
    public function getCustomerGroupIds(): array
    {
        $customerGroupIds = $this->getData(self::CUSTOMER_GROUP_IDS);
        if (is_string($customerGroupIds) && !empty($customerGroupIds)) {
            return explode(',', $customerGroupIds);
        } elseif (is_array($customerGroupIds)) {
            return $customerGroupIds;
        }
        return [];
    }

    /**
     * @inheritdoc
     */
    public function setCustomerGroupIds(array $customerGroupIds): RuleInterface
    {
        return $this->setData(self::CUSTOMER_GROUP_IDS, $customerGroupIds);
    }

    /**
     * @inheritdoc
     */
    public function getRestrictedMethodIds(): array
    {
        $methodIds = $this->getData(self::RESTRICTED_METHOD_IDS);
        if (is_string($methodIds) && !empty($methodIds)) {
            return explode(',', $methodIds);
        } elseif (is_array($methodIds)) {
            return $methodIds;
        }
        return [];
    }

    /**
     * @inheritdoc
     */
    public function setRestrictedMethodIds(array $methodIds): RuleInterface
    {
        return $this->setData(self::RESTRICTED_METHOD_IDS, $methodIds);
    }
}