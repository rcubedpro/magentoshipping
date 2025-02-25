<?php
/**
 * Rule Search Results Interface.
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

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for shipping rule search results
 * @api
 */
interface RuleSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get rules list.
     *
     * @return \RCubed\ShippingManager\Api\Data\RuleInterface[]
     */
    public function getItems();

    /**
     * Set rules list.
     *
     * @param \RCubed\ShippingManager\Api\Data\RuleInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}