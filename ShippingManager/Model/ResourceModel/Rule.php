<?php
/**
 * Shipping Rule Resource Model.
 *
 * @category   RCubed
 * @package    RCubed_ShippingManager
 * @author     R-Cubed Solutions <drussell@rcubedpro.com>
 * @copyright  2025 R-Cubed Solutions (https://www.rcubedpro.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 * @link       https://www.rcubedpro.com
 */
declare(strict_types=1);

namespace RCubed\ShippingManager\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Shipping Rule Resource Model
 */
class Rule extends AbstractDb
{
    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @param Context $context
     * @param DateTime $dateTime
     * @param string|null $connectionName
     */
    public function __construct(
        Context $context,
        DateTime $dateTime,
        $connectionName = null
    ) {
        $this->dateTime = $dateTime;
        parent::__construct($context, $connectionName);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('rcubed_shipping_rule', 'rule_id');
    }

    /**
     * Process rule data before saving
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        // Set default timestamps
        if ($object->isObjectNew()) {
            $object->setCreatedAt($this->dateTime->gmtDate());
        }
        
        $object->setUpdatedAt($this->dateTime->gmtDate());
        
        return parent::_beforeSave($object);
    }

    /**
     * Process rule data after saving
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        // Handle store relation
        if ($object->hasStoreIds()) {
            $storeIds = $object->getStoreIds();
            if (is_array($storeIds)) {
                $this->saveStoreRelation($object, $storeIds);
            }
        }
        
        // Handle customer group relation
        if ($object->hasCustomerGroupIds()) {
            $customerGroupIds = $object->getCustomerGroupIds();
            if (is_array($customerGroupIds)) {
                $this->saveCustomerGroupRelation($object, $customerGroupIds);
            }
        }
        
        return parent::_afterSave($object);
    }

    /**
     * Save store relation
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param array $storeIds
     * @return void
     */
    protected function saveStoreRelation($object, array $storeIds)
    {
        $table = $this->getTable('rcubed_shipping_rule_store');
        $oldStoreIds = $this->getStoreIds($object->getId());
        
        $insert = array_diff($storeIds, $oldStoreIds);
        $delete = array_diff($oldStoreIds, $storeIds);
        
        $connection = $this->getConnection();
        
        if (!empty($delete)) {
            $connection->delete(
                $table,
                [
                    'rule_id = ?' => $object->getId(),
                    'store_id IN (?)' => $delete
                ]
            );
        }
        
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    'rule_id' => $object->getId(),
                    'store_id' => (int)$storeId
                ];
            }
            $connection->insertMultiple($table, $data);
        }
    }

    /**
     * Save customer group relation
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param array $customerGroupIds
     * @return void
     */
    protected function saveCustomerGroupRelation($object, array $customerGroupIds)
    {
        $table = $this->getTable('rcubed_shipping_rule_customer_group');
        $oldCustomerGroupIds = $this->getCustomerGroupIds($object->getId());
        
        $insert = array_diff($customerGroupIds, $oldCustomerGroupIds);
        $delete = array_diff($oldCustomerGroupIds, $customerGroupIds);
        
        $connection = $this->getConnection();
        
        if (!empty($delete)) {
            $connection->delete(
                $table,
                [
                    'rule_id = ?' => $object->getId(),
                    'customer_group_id IN (?)' => $delete
                ]
            );
        }
        
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $customerGroupId) {
                $data[] = [
                    'rule_id' => $object->getId(),
                    'customer_group_id' => (int)$customerGroupId
                ];
            }
            $connection->insertMultiple($table, $data);
        }
    }

    /**
     * Get store ids for rule
     *
     * @param int $ruleId
     * @return array
     */
    public function getStoreIds($ruleId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getTable('rcubed_shipping_rule_store'), 'store_id')
            ->where('rule_id = ?', (int)$ruleId);
        
        return $connection->fetchCol($select);
    }

    /**
     * Get customer group ids for rule
     *
     * @param int $ruleId
     * @return array
     */
    public function getCustomerGroupIds($ruleId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getTable('rcubed_shipping_rule_customer_group'), 'customer_group_id')
            ->where('rule_id = ?', (int)$ruleId);
        
        return $connection->fetchCol($select);
    }
}