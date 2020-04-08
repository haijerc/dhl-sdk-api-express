<?php
/**
 * See LICENSE.md for license details.
 */
namespace Dhl\Express\Webservice\Soap\TypeMapper;

use Dhl\Express\Api\Data\RateResponseInterface;
use Dhl\Express\Exception\RateRequestException;
use Dhl\Express\Model\RateResponse;
use Dhl\Express\Model\Response\Rate\Rate;
use Dhl\Express\Webservice\Soap\Type\RateResponse\Provider\Service\Charges\Charge;
use Dhl\Express\Webservice\Soap\Type\SoapRateResponse;

/**
 * Rate Response Mapper.
 *
 * Transform the SOAP response type into rate objects suitable for further processing.
 *
 * @author   Christoph Aßmann <christoph.assmann@netresearch.de>
 * @link     https://www.netresearch.de/
 */
class RateResponseMapper
{
    /**
     * @param SoapRateResponse $rateResponse
     *
     * @return RateResponseInterface
     *
     * @throws RateRequestException
     */
    public function map(SoapRateResponse $rateResponse)
    {
        $rates = [];

        $provider = $rateResponse->getProvider();
        if ($provider !== null) {
            $notification = $provider->getNotification();
            if (\is_array($notification)) {
                $notification = array_shift($notification);
            }

            if ($notification !== null && $notification->isError()) {
                throw new RateRequestException($notification->getMessage(), $notification->getCode());
            }

            if ($provider->getService() !== null) {
                foreach ($provider->getService() as $service) {
                    $serviceCode = $service->getType();
                    $totals = $service->getTotalNet();
                    $charges = $service->getCharges();

                    foreach ($totals as $total) {
	                    if(empty($charges)) {
		                    continue;
	                    }
	                    
	                    $totalCharges = array_filter(
		                    $charges[0]->getCharge(),
		                    function (Charge $charge) {
			                    return ($charge->getChargeAmount() > 0);
		                    }
	                    );

                        if (empty($totalCharges)) {
                            continue;
                        }

                        $label = $totalCharges[0]->getChargeType();

                        $currencyCode = $total->getCurrency();
                        $cost = $total->getAmount();

                        $rate = new Rate($serviceCode, $label, $cost, $currencyCode, $totalCharges);
                        if ($service->getDeliveryTime() instanceof \DateTime) {
                            $rate->setDeliveryTime($service->getDeliveryTime());
                        }
	                    if ($service->getCutoffTime() instanceof \DateTime) {
		                    $rate->setCutoffTime($service->getCutoffTime());
	                    }
	                    
                        $rates[] = $rate;
                    }
                }
            }
        }

        return new RateResponse($rates);
    }
}
