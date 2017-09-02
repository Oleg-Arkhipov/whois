<?php declare(strict_types=1);

namespace Oarkhipov\Whois\Values;

/**
 * Object containing part of WHOIS record about registrar.
 * @package Oarkhipov\Whois\Values
 */
class Registrar
{
    /** @var  string */
    public $name;

    /** @var  string */
    public $url;

    /** @var  string */
    public $abuseEmail;

    /** @var  string */
    public $abusePhone;
}