<?php

declare(strict_types=1);

namespace Oarkhipov\Whois;

use Oarkhipov\Whois\Values\WhoisResponse;

/**
 * Class providing functionality to send WHOIS protocol requests to WHOIS servers and grab raw response.
 */
class NetworkClient
{
    /**
     * @var int In seconds
     */
    private $connectionTimeout = 5;

    /**
     * @param string $whoisServer
     * @param string $domain      Domain, which WHOIS record is being requested.
     *
     * @return WhoisResponse
     */
    public function requestWhois(string $whoisServer, string $domain): WhoisResponse
    {
        $response = new WhoisResponse();
        $socketAddress = "tcp://{$whoisServer}:43";

        try {
            $fp = stream_socket_client($socketAddress, $errno, $errstr, $this->connectionTimeout);
        } catch (\Exception $e) {
            $response->received = false;

            return $response;
        }

        if ($fp === false) {
            $response->received = false;

            return $response;
        }

        fwrite($fp, $this->makeRequestString($domain));
        while (!feof($fp)) {
            $response->raw .= fgets($fp, 1024);
        }
        fclose($fp);

        $response->received = true;

        return $response;
    }

    /**
     * Make a string to be sent to WHOIS server.
     *
     * @param string $domain
     *
     * @return string
     */
    private function makeRequestString(string $domain): string
    {
        return $domain."\r\n";
    }
}
