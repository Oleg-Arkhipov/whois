<?php

declare(strict_types=1);

namespace Oarkhipov\Whois\Values;

/**
 * Object containing part of WHOIS record about registrant.
 */
class Registrant
{
    /** @var string */
    public $name;

    /** @var string */
    public $organization;

    /** @var string */
    public $street;

    /** @var string */
    public $city;

    /** @var string */
    public $state;

    /** @var string */
    public $postalCode;

    /** @var string */
    public $country;

    /** @var string */
    public $phone;

    /** @var string */
    public $phoneExtension;

    /** @var string */
    public $fax;

    /** @var string */
    public $faxExtension;

    /** @var string */
    public $email;
}
