<?php
/**
 * Plugin for Magento\Shipping\Model\Config to add custom carriers.
 *
 * @category   RCubed
 * @package    RCubed_ShippingManager
 * @author     R-Cubed Solutions <drussell@rcubedpro.com>
 * @copyright  2025 R-Cubed Solutions (https://www.rcubedpro.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License 3.0 (OSL-3.0)
 * @link       https://www.rcubedpro.com
 */
declare(strict_types=1);

namespace RCubed\ShippingManager\Plugin\Shipping;

use Magento\Shipping\Model\Config;
use RCubed\ShippingManager\Model\Carrier\CustomShipping;

/**
 * Plugin class to add custom shipping carriers to Magento's active carriers list
 */
class CarrierConfig
{
    /**
     * After plugin to add our custom carrier to active carriers list
     *
     * @param Config $subject
     * @param array $result
     * @return array
     */
    public function afterGetActiveCarriers(Config $subject, array $result): array
    {
        if (!array_key_exists(CustomShipping::CARRIER_CODE, $result)) {
            $result[CustomShipping::CARRIER_CODE] = new CustomShipping(
                $subject->getScopeConfig(),
                null,
                null,
                []
            );
        }
        
        return $result;
    }
}