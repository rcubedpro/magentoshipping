<?php
/**
 * Method Search Results Interface.
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
 * Interface for shipping method search results
 * @api
 */
interface MethodSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get methods list.
     *
     * @return \RCubed\ShippingManager\Api\Data\MethodInterface[]
     */
    public function getItems();

    /**
     * Set methods list.
     *
     * @param \RCubed\ShippingManager\Api\Data\MethodInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}