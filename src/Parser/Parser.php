<?php declare(strict_types=1);

namespace Oarkhipov\Whois\Parser;

use Oarkhipov\Whois\Values\Whois;
use Oarkhipov\Whois\Values\WhoisResponse;

/**
 * Parser responsible for converting raw WHOIS response (string) into ready to use object.
 * @package Oarkhipov\Whois\Parser
 */
class Parser
{
    private const SINGLE_LINE_FORMAT = 1;

    /**
     * @param WhoisResponse $response
     * @return Whois
     */
    public function parse(WhoisResponse $response): Whois
    {
        $format = $this->detectResponseFormat($response);
        switch ($format) {
            case self::SINGLE_LINE_FORMAT: {
                $parser = new SingleLineParser();
                break;
            }
            default: {
                $parser = new SingleLineParser();
            }
        }
        $whois = $parser->parse($response);
        return $whois;
    }

    /**
     * Detects response format for the further parsing.
     *
     * @param WhoisResponse $response
     * @return int
     */
    private function detectResponseFormat(WhoisResponse $response): int
    {
        return self::SINGLE_LINE_FORMAT;
    }
}