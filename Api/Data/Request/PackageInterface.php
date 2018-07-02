<?php
/**
 * See LICENSE.md for license details.
 */
namespace Dhl\Express\Api\Data\Request;

/**
 * Package Interface.
 *
 * DTO that carries relevant package data for booking a shipment.
 *
 * @api
 * @package  Dhl\Express\Api
 * @author   Christoph Aßmann <christoph.assmann@netresearch.de>
 * @author   Ronny Gertler <ronny.gertler@netresearch.de>
 * @license  https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.netresearch.de/
 */
interface PackageInterface
{
    /**
     * @return int
     */
    public function getSequenceNumber(): int;

    /**
     * @return float
     */
    public function getWeight(): float;

    /**
     * @return float
     */
    public function getLength(): float;

    /**
     * @return float
     */
    public function getWidth(): float;

    /**
     * @return float
     */
    public function getHeight(): float;
}
