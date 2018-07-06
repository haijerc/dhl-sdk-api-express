<?php
/**
 * See LICENSE.md for license details.
 */
namespace Dhl\Express\Webservice\Soap\TypeMapper;

use Dhl\Express\Api\Data\RateRequestInterface;
use Dhl\Express\Api\Data\Request\PackageInterface;
use Dhl\Express\Model\Request\Package;
use Dhl\Express\Model\Request\ShipmentDetails;
use Dhl\Express\Webservice\Soap\Type\Common\Packages;
use Dhl\Express\Webservice\Soap\Type\Common\Packages\RequestedPackages\Dimensions;
use Dhl\Express\Webservice\Soap\Type\Common\Ship\Address;
use Dhl\Express\Webservice\Soap\Type\Common\SpecialServices;
use Dhl\Express\Webservice\Soap\Type\Common\SpecialServices\Service;
use Dhl\Express\Webservice\Soap\Type\Common\UnitOfMeasurement;
use Dhl\Express\Webservice\Soap\Type\RateRequest;
use Dhl\Express\Webservice\Soap\Type\RateRequest\Packages\RequestedPackages;
use Dhl\Express\Webservice\Soap\Type\RateRequest\RequestedShipment;
use Dhl\Express\Webservice\Soap\Type\RateRequest\Ship;

/**
 * Rate Request Mapper.
 *
 * Transform the rate request object into SOAP types suitable for API communication.
 *
 * @package  Dhl\Express\Webservice
 * @author   Christoph Aßmann <christoph.assmann@netresearch.de>
 * @author   Ronny Gertler <ronny.gertler@netresearch.de>
 * @license  https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.netresearch.de/
 */
class RateRequestMapper
{
    /**
     * @param RateRequestInterface $rateRequest
     *
     * @return RateRequest
     * @throws \InvalidArgumentException
     */
    public function map(RateRequestInterface $rateRequest)
    {
        $this->checkConsistentUOM($rateRequest->getPackages());

        // Since we checked that all packages use the same UOMs, we can just take them from any package
        $weightUOM = $rateRequest->getPackages()[0]->getWeightUOM();
        $dimensionsUOM = $rateRequest->getPackages()[0]->getDimensionsUOM();

        $requestedShipment = new RequestedShipment(
            $this->getDropOfTypeFromShipDetails(
                $rateRequest->getShipmentDetails()->isUnscheduledPickup()
            ),
            new Ship(
                new Address(
                    $rateRequest->getShipperAddress()->getCity(),
                    $rateRequest->getShipperAddress()->getPostalCode(),
                    $rateRequest->getShipperAddress()->getCountryCode()
                ),
                new Address(
                    $rateRequest->getRecipientAddress()->getCity(),
                    $rateRequest->getRecipientAddress()->getPostalCode(),
                    $rateRequest->getRecipientAddress()->getCountryCode()
                )
            ),
            new Packages(
                $this->mapPackages($rateRequest->getPackages())
            ),
            $rateRequest->getShipmentDetails()->getReadyAtTimestamp(),
            $this->mapUOM($weightUOM, $dimensionsUOM)
        );

        // TODO If using Billing, the "account" should be leaved out
        $requestedShipment->setAccount($rateRequest->getShipperAccountNumber());
        $requestedShipment->setPaymentInfo($rateRequest->getShipmentDetails()->getTermsOfTrade());
        $requestedShipment->setContent($rateRequest->getShipmentDetails()->getContentType());

        $specialServicesList = [];
        if ($insurance = $rateRequest->getInsurance()) {
            $insuranceService = new Service(SpecialServices\ServiceType::TYPE_INSURANCE);
            $insuranceService->setServiceValue($insurance->getValue());
            $insuranceService->setCurrencyCode($insurance->getCurrencyCode());
            $specialServicesList[] = $insuranceService;
        }
        $specialServices = new SpecialServices($specialServicesList);
        $requestedShipment->setSpecialServices($specialServices);

        $streetLines = $rateRequest->getRecipientAddress()->getStreetLines();

        if (count($streetLines)) {
            $requestedShipment->getShip()->getRecipient()->setStreetLines($streetLines[0]);
        }

        if (count($streetLines) > 1) {
            $requestedShipment->getShip()->getRecipient()->setStreetLines2($streetLines[1]);
        }

        if (count($streetLines) > 2) {
            $requestedShipment->getShip()->getRecipient()->setStreetLines3($streetLines[2]);
        }

        return new RateRequest($requestedShipment);
    }

    /**
     * @param PackageInterface[] $packages
     *
     * @return RequestedPackages[]
     */
    private function mapPackages(array $packages): array
    {
        $soapRequestedPackages = [];

        foreach ($packages as $package) {
            $soapRequestedPackages[] = new RequestedPackages(
                $package->getWeight(),
                new Dimensions(
                    $package->getLength(),
                    $package->getWidth(),
                    $package->getHeight()
                ),
                $package->getSequenceNumber()
            );
        }

        return $soapRequestedPackages;
    }

    /**
     * Returns whether the pickup is a scheduled one or not.
     *
     * @param bool $isUnscheduledPickup Whether the pickup is a scheduled one or not
     *
     * @return string
     */
    public function getDropOfTypeFromShipDetails(bool $isUnscheduledPickup): string
    {
        if ($isUnscheduledPickup) {
            return ShipmentDetails::UNSCHEDULED_PICKUP;
        }

        return ShipmentDetails::REGULAR_PICKUP;
    }

    /**
     * Check if all packages have the same units of measurement (UOM) for weight and dimensions.
     *
     * @param array $packages The list of packages
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    private function checkConsistentUOM(array $packages): void
    {
        $weightUom     = null;
        $dimensionsUOM = null;

        /** @var Package $package */
        foreach ($packages as $package) {
            if (!$weightUom) {
                $weightUom = $package->getWeightUOM();
            }

            if (!$dimensionsUOM) {
                $dimensionsUOM = $package->getDimensionsUOM();
            }

            if ($weightUom !== $package->getWeightUOM()) {
                throw new \InvalidArgumentException(
                    'All packages weights must have a consistent unit of measurement.'
                );
            }

            if ($dimensionsUOM !== $package->getDimensionsUOM()) {
                throw new \InvalidArgumentException(
                    'All packages dimensions must have a consistent unit of measurement.'
                );
            }
        }
    }

    /**
     * Maps the magento unit of measurement to the DHL express unit of measurement.
     *
     * @param string $weightUOM     The unit of measurement for weight
     * @param string $dimensionsUOM The unit of measurement for dimensions
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    private function mapUOM(string $weightUOM, string $dimensionsUOM): string
    {
        if (($weightUOM === Package::UOM_WEIGHT_KG) && ($dimensionsUOM === Package::UOM_DIMENSION_CM)) {
            return UnitOfMeasurement::SI;
        }

        if (($weightUOM === Package::UOM_WEIGHT_LB) && ($dimensionsUOM === Package::UOM_DIMENSION_IN)) {
            return UnitOfMeasurement::SU;
        }

        throw new \InvalidArgumentException(
            'All units of measurement have to be consistent (either metric system or US system).'
        );
    }
}