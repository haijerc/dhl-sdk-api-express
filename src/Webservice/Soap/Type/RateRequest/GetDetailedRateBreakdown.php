<?php
/**
 * See LICENSE.md for license details.
 */
namespace Dhl\Express\Webservice\Soap\Type\RateRequest;

use Dhl\Express\Webservice\Soap\Type\Common\YesNo;

/**
 * This option is to receive a breakdown of charges including
 * taxes and discounts.
 * The default value is N, a high level breakdown is provided then.
 *
 * @api
 * @author   Rico Sonntag <rico.sonntag@netresearch.de>
 * @link     https://www.netresearch.de/
 */
class GetDetailedRateBreakdown extends YesNo
{
}
