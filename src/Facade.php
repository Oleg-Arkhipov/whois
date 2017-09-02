<?php declare(strict_types=1);

namespace Oarkhipov\Whois;

use Oarkhipov\Whois\Parser\Parser;

/**
 * Facade of the package.
 * @package Oarkhipov\Whois
 */
class Facade
{
    /**
     * Instantiates object which exposes package functionality.
     * @return Fetcher
     */
    public static function create(): Fetcher
    {
        $fetcher = new Fetcher(
            new NetworkClient(),
            new ServerResolver(
                new NetworkClient(),
                new Parser()
            ),
            new Parser()
        );
        return $fetcher;
    }
}