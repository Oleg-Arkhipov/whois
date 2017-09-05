<?php declare(strict_types=1);

namespace Oarkhipov\Whois;

use Oarkhipov\Whois\Parser\Parser;

/**
 * Class providing functionality to find WHOIS servers with required records.
 * @package Oarkhipov\Whois
 */
class ServerResolver
{
    /** @var string Main root server responsible for a lot of top-level domains. */
    private $IANA_SERVER = 'whois.iana.org';

    private $networkClient;
    private $parser;

    public function __construct(NetworkClient $networkClient, Parser $parser)
    {
        $this->networkClient = $networkClient;
        $this->parser = $parser;
    }

    /**
     * Returns possible WHOIS servers responsible for the given top-level domain.
     *
     * One of these servers may exist and keep WHOIS records about SLDs within given TLD.
     * @param string $domain
     * @return string[]
     */
    public function resolveServersForTld(string $domain): array
    {
        $servers = [];

        $ianaResponse = $this->networkClient->requestWhois($this->IANA_SERVER, $domain);
        $ianaWhois = $this->parser->parse($ianaResponse);
        if ($ianaWhois->whoisServer) {
            $servers[] = $ianaWhois->whoisServer;
        }

        $servers[] = 'whois.nic.' . $domain;
        $servers[] = 'whois.' . $domain;
        $servers[] = $domain . '.whois-servers.net';

        return $servers;
    }

    /**
     * Returns possible WHOIS servers responsible for the given second-Level domain.
     *
     * One of these servers may exist and keep WHOIS records about third level domains within given SLD.
     * @param string $domain
     * @return string[]
     */
    public function resolveServersForSld(string $domain): array
    {
        return [
            'whois.' . $domain,
            'whois.nic.' . $domain
        ];
    }
}