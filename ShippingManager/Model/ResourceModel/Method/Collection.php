<?php
/**
 * Shipping Method Collection.
 *
 * @category   RCubed
 * @package    RCubed_ShippingManager
 * @author     R-Cubed Solutions <drussell@rcubedpro.com>
 * @copyright  2025 R-Cubed Solutions (https://www.rcubedpro.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 * @link       https://www.rcubedpro.com
 */
declare(strict_types=1);

namespace RCubed\ShippingManager\Model\ResourceModel\Method;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use RCubed\ShippingManager\Model\Method;
use RCubed\ShippingManager\Model\ResourceModel\Method as MethodResource;

/**
 * Shipping Method Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'method_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            Method::class,
            MethodResource::class
        );
    }
}