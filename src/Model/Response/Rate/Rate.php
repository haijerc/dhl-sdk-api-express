<?php
/**
 * See LICENSE.md for license details.
 */
namespace Dhl\Express\Model\Response\Rate;

use DateTime;
use Dhl\Express\Api\Data\Response\Rate\RateInterface;
use Dhl\Express\Webservice\Soap\Type\RateResponse\Provider\Service\Charges\Charge;

/**
 * Rate response item.
 *
 * @api
 * @author   Rico Sonntag <rico.sonntag@netresearch.de>
 * @link     https://www.netresearch.de/
 */
class Rate implements RateInterface
{
    /**
     * The service code.
     *
     * @var string
     */
    private $serviceCode;

    /**
     * The label.
     *
     * @var string
     */
    private $label;

    /**
     * The amount.
     *
     * @var float
     */
    private $amount;

    /**
     * The currency code.
     *
     * @var string
     */
    private $currencyCode;

    /**
     * The delivery time.
     *
     * @var DateTime|null
     */
    private $deliveryTime;

	/**
	 * The cutoff time.
	 *
	 * @var DateTime|null
	 */
	private $cutoffTime;

	/**
	 * @var Charge[]
	 */
	private $appliedCharges;

	/**
	 * Rate constructor.
	 *
	 * @param string   $serviceCode    The service code
	 * @param string   $label          The label
	 * @param float    $amount         The amount
	 * @param string   $currencyCode   The currency code
	 * @param Charge[] $appliedCharges Applied charges
	 */
	public function __construct($serviceCode, $label, $amount, $currencyCode, $appliedCharges)
	{
		$this->serviceCode      = $serviceCode;
		$this->label            = $label;
		$this->amount           = $amount;
		$this->currencyCode     = $currencyCode;
		$this->appliedCharges   = $appliedCharges;
	}

    public function getServiceCode()
    {
        return (string) $this->serviceCode;
    }

    public function getLabel()
    {
        return (string) $this->label;
    }

    public function getAmount()
    {
        return (float) $this->amount;
    }

    public function getCurrencyCode()
    {
        return (string) $this->currencyCode;
    }

    public function getDeliveryTime()
    {
        return $this->deliveryTime;
    }

	public function getCutoffTime()
	{
		return $this->cutoffTime;
	}

	/**
	 * @return Charge[]
	 */
	public function getAppliedCharges()
	{
		return $this->appliedCharges;
	}

    /**
     * Sets the delivery date/time.
     *
     * @param DateTime $deliveryTime The delivery date/time
     *
     * @return Rate
     */
    public function setDeliveryTime(DateTime $deliveryTime)
    {
        $this->deliveryTime = $deliveryTime;
        return $this;
    }

	/**
	 * Sets the cutoff date/time.
	 *
	 * @param DateTime $cutoffTIme The cutoff date/time
	 *
	 * @return Rate
	 */
	public function setCutoffTime(DateTime $cutoffTIme)
	{
		$this->cutoffTime = $cutoffTIme;

		return $this;
	}
}
