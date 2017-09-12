<?php

declare(strict_types=1);

namespace Oarkhipov\Whois\Values;

/**
 * Object containing raw response from WHOIS server.
 */
class WhoisResponse
{
    /** @var bool Indicates, if any response was received. */
    public $received;

    /** @var string Raw response. It is present only if $this->received is true. */
    public $raw = '';
}
