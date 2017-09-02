<?php declare(strict_types=1);

namespace Oarkhipov\Whois\Values;

use Carbon\Carbon;

/**
 * Object containing WHOIS record for a particular domain.
 * @package Oarkhipov\Whois
 */
class Whois
{
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

    public function __construct()
    {
        $this->registrar = new Registrar();
        $this->registrant = new Registrant();
    }
}