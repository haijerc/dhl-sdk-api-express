<?php
/**
 * See LICENSE.md for license details.
 */
namespace Dhl\Express\Api;

use Dhl\Express\Api\Data\RateRequestInterface;
use Dhl\Express\Api\Data\RateResponseInterface;
use Dhl\Express\Api\Data\TrackingRequestInterface;
use Dhl\Express\Api\Data\TrackingResponseInterface;

/**
 * Tracking Service Interface.
 *
 * Access the DHL Express Global Web Services shipment operation "TrackingRequest".
 *
 * @api
 * @package  Dhl\Express\Api
 * @author   Ronny Gertler <ronny.gertler@netresearch.de>
 * @license  https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.netresearch.de/
 */
interface TrackingServiceInterface
{
    /**
     * @param TrackingRequestInterface $request
     * @return TrackingResponseInterface
     */
    public function getTrackingInformation(TrackingRequestInterface $request): TrackingResponseInterface;
}
