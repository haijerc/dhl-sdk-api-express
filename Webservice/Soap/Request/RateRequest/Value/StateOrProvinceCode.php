<?php
/**
 * See LICENSE.md for license details.
 */
namespace Dhl\Express\Webservice\Soap\Request\RateRequest\Value;

use Dhl\Express\Webservice\Soap\ValueInterface;

/**
 * The state or province code type. 2 letter state code for the USA only.
 *
 * @api
 * @package  Dhl\Express\Api
 * @author   Rico Sonntag <rico.sonntag@netresearch.de>
 * @license  https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.netresearch.de/
 */
class StateOrProvinceCode implements ValueInterface
{
    private const NUMBER_OF_CHARS = 2;

    /**
     * The state or province code.
     *
     * @var null|string
     */
    private $value;

    /**
     * Constructor.
     *
     * @param null|string $value The value
     */
    public function __construct(?string $value)
    {
        if (strlen($value) !== self::NUMBER_OF_CHARS) {
            throw new \InvalidArgumentException('The argument must be a two letter state code');
        }

        $this->value = $value;
    }

    /**
     * Returns the value as string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }
}
