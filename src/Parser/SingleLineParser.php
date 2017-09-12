<?php

declare(strict_types=1);

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
 */
class SingleLineParser
{
    /** @var Mapper */
    private $mapper;

    public function __construct()
    {
        $this->mapper = new Mapper();
    }

    /**
     * @param WhoisResponse $response
     *
     * @return Whois
     */
    public function parse(WhoisResponse $response): Whois
    {
        $lines = preg_split('/\R/', $response->raw);
        $keyValuePairs = array_map([$this, 'extractKeyValue'], $lines);
        $keyValuePairs = array_filter($keyValuePairs, function ($keyValue) {
            return count($keyValue) === 2;
        });
        $whois = $this->mapper->assignKeyValuePairs($keyValuePairs);

        return $whois;
    }

    /**
     * Extracts key-value pair from a given string.
     *
     * @param string $line
     *
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
}
