<?php declare(strict_types=1);

namespace Oarkhipov\Whois\Values;

use Carbon\Carbon;

/**
 * Object containing WHOIS record for a particular domain.
 * @package Oarkhipov\Whois
 */
class Whois
{
    /**
     * Indicates whether domain is already registered by someone (not available).
     * @var bool
     */
    public $registered = false;

    /**
     * Indicates whether domain is reserved by registry operator.
     *
     * Reserved domains are not open for registration by third parties.
     * @var bool
     */
    public $reserved = false;

    /** @var  string */
    public $domain;

    /** @var  string */
    public $registryDomainId;

    /** @var  string */
    public $whoisServer;

    /** @var  Carbon */
    public $creationDate;

    /** @var  Carbon */
    public $updateDate;

    /** @var  Carbon */
    public $expirationDate;

    /** @var  Registrar */
    public $registrar;

    /** @var  Registrant */
    public $registrant;

    /** @var  string[] */
    public $nameServers = [];

    /** @var  boolean[] */
    public $reserved = false;

    public function __construct()
    {
        $this->registrar = new Registrar();
        $this->registrant = new Registrant();
    }
}
