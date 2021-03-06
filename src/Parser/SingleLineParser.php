<?php declare(strict_types=1);

namespace Oarkhipov\Whois\Parser;

use Oarkhipov\Whois\Values\Whois;
use Oarkhipov\Whois\Values\WhoisResponse;

/**
 * Parser responsible for processing WHOIS responses in specific format.
 *
 * This parser processes responses which:
 * 1) contain one key-value pair per string,
 * 2) key and value are delimited by a colon.
 * See example in: /tests/assets/responses/single_line.txt
 * @package Oarkhipov\Whois\Parser
 */
class SingleLineParser
{
    /** @var  Mapper */
    private $mapper;

    public function __construct()
    {
        $this->mapper = new Mapper();
    }

    /**
     * @param WhoisResponse $response
     * @return Whois
     */
    public function parse(WhoisResponse $response): Whois
    {
        $lines = preg_split('/\R/', $response->raw);

        if (!$this->isRegistered($lines)) {
            return new Whois();
        }

        if ($this->isReserved($lines)) {
            $whois = new Whois();
            $whois->reserved = true;

            return $whois;
        }

        $keyValuePairs = array_map([$this, 'extractKeyValue'], $lines);
        $keyValuePairs = array_filter($keyValuePairs, function ($keyValue) {
            return (count($keyValue) === 2);
        });
        $whois = $this->mapper->assignKeyValuePairs($keyValuePairs);

        return $whois;
    }

    /**
     * Extracts key-value pair from a given string.
     * @param string $line
     * @return string[] Of format: [key, value]
     */
    private function extractKeyValue(string $line): array
    {
        $keyValue = explode(':', $line, 2);
        $keyValue = array_map(function ($el) {
            return trim($el, " \t\n\r\0\x0B");
        }, $keyValue);
        return $keyValue;
    }

    /**
     * @param string[] $lines Lines of response
     * @return bool
     */
    private function isRegistered(array $lines): bool
    {
        return !$this->stringContainsAnyOf($lines[0], [
            'No match for',
            'NOT FOUND',
        ]);
    }

    /**
     * @param string[] $lines Lines of WHOIS response
     * @return bool
     */
    private function isReserved(array $lines): bool
    {
        return $this->stringContainsAnyOf($lines[0], [
            'Reserved by Registry Operator',
        ]);
    }

    /**
     * @param string $haystack
     * @param string[] $needles
     * @return bool
     */
    private function stringContainsAnyOf(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }
}
