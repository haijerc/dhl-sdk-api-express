<?php
/**
 * See LICENSE.md for license details.
 */

namespace Dhl\Express\Webservice\Soap\Type\RateResponse\Provider\Service\Charges\ChargeBreakdown;

class ChargeBreakdown
{
	/**
	 * @var array
	 */
	private $Breakdown;

	/**
	 * @return array
	 */
	public function getBreakdown()
	{
		return $this->Breakdown;
	}
}