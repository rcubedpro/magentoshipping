<?php
/**
 * Shipping Rule Interface.
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
 * Interface for shipping rule model
 * @api
 */
interface RuleInterface
{
    /**#@+
     * Constants for keys of data array
     */
    const RULE_ID = 'rule_id';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const CONDITIONS_SERIALIZED = 'conditions_serialized';
    const IS_ACTIVE = 'is_active';
    const SORT_ORDER = 'sort_order';
    const STORE_IDS = 'store_ids';
    const CUSTOMER_GROUP_IDS = 'customer_group_ids';
    const RESTRICTED_METHOD_IDS = 'restricted_method_ids';
    /**#@-*/

    /**
     * Get rule ID
     *
     * @return int|null
     */
    public function getRuleId(): ?int;

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Get serialized conditions
     *
     * @return string|null
     */
    public function getConditionsSerialized(): ?string;

    /**
     * Get active status
     *
     * @return bool
     */
    public function getIsActive(): bool;

    /**
     * Get sort order
     *
     * @return int|null
     */
    public function getSortOrder(): ?int;

    /**
     * Get store IDs
     *
     * @return array
     */
    public function getStoreIds(): array;

    /**
     * Get customer group IDs
     *
     * @return array
     */
    public function getCustomerGroupIds(): array;

    /**
     * Get restricted method IDs
     *
     * @return array
     */
    public function getRestrictedMethodIds(): array;

    /**
     * Set rule ID
     *
     * @param int|null $ruleId
     * @return RuleInterface
     */
    public function setRuleId(?int $ruleId): RuleInterface;

    /**
     * Set name
     *
     * @param string|null $name
     * @return RuleInterface
     */
    public function setName(?string $name): RuleInterface;

    /**
     * Set description
     *
     * @param string|null $description
     * @return RuleInterface
     */
    public function setDescription(?string $description): RuleInterface;

    /**
     * Set serialized conditions
     *
     * @param string|null $conditionsSerialized
     * @return RuleInterface
     */
    public function setConditionsSerialized(?string $conditionsSerialized): RuleInterface;

    /**
     * Set active status
     *
     * @param bool $isActive
     * @return RuleInterface
     */
    public function setIsActive($isActive): RuleInterface;

    /**
     * Set sort order
     *
     * @param int|null $sortOrder
     * @return RuleInterface
     */
    public function setSortOrder(?int $sortOrder): RuleInterface;

    /**
     * Set store IDs
     *
     * @param array $storeIds
     * @return RuleInterface
     */
    public function setStoreIds(array $storeIds): RuleInterface;

    /**
     * Set customer group IDs
     *
     * @param array $customerGroupIds
     * @return RuleInterface
     */
    public function setCustomerGroupIds(array $customerGroupIds): RuleInterface;

    /**
     * Set restricted method IDs
     *
     * @param array $methodIds
     * @return RuleInterface
     */
    public function setRestrictedMethodIds(array $methodIds): RuleInterface;
}