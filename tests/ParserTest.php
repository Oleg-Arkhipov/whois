<?php declare(strict_types=1);

use Carbon\Carbon;
use Oarkhipov\Whois\Parser\Parser;
use Oarkhipov\Whois\Values\Whois;
use Oarkhipov\Whois\Values\WhoisResponse;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    /** @var  Parser */
    protected $parser;

    /** @var  WhoisResponse */
    protected $response;

    protected function setUp()
    {
        $this->parser = new Parser();
        $this->response = new WhoisResponse();
        $this->response->received = true;
    }

    public function testOnSingleLineFormatResponse()
    {
        $this->response->raw = file_get_contents(__DIR__ . '/assets/responses/single_line.txt');

        $whois = $this->parser->parse($this->response);

        $correctWhois = new Whois();
        $correctWhois->domain = 'BEST.INFO';
        $correctWhois->registryDomainId = 'D2272416-LRMS';
        $correctWhois->whoisServer = null;
        $correctWhois->creationDate = new Carbon('2002-07-13T19:40:10Z');
        $correctWhois->updateDate = new Carbon('2017-07-13T22:21:47Z');
        $correctWhois->expirationDate = new Carbon('2018-07-13T19:40:10Z');
        $correctWhois->registrar->name = 'Ascio Technologies, Inc. Danmark - Filial af Ascio technologies, Inc. USA';
        $correctWhois->registrar->url = 'http://www.ascio.com';
        $correctWhois->registrar->abuseEmail = 'abuse@ascio.com';
        $correctWhois->registrar->abusePhone = '+44.2070159370';
        $correctWhois->registrant->name = 'BEST, a. s.';
        $correctWhois->registrant->organization = 'BEST, a. s.';
        $correctWhois->registrant->street = 'Rybnice 148';
        $correctWhois->registrant->city = 'Kaznejov';
        $correctWhois->registrant->state = 'na';
        $correctWhois->registrant->postalCode = '33151';
        $correctWhois->registrant->country = 'CZ';
        $correctWhois->registrant->phone = '+420.373720111';
        $correctWhois->registrant->phoneExtension = null;
        $correctWhois->registrant->fax = null;
        $correctWhois->registrant->faxExtension = null;
        $correctWhois->registrant->email = 'info@best-as.cz';
        $correctWhois->nameServers = ['ALFA.NS.ACTIVE24.CZ', 'BETA.NS.ACTIVE24.CZ'];

        $this->assertEquals($correctWhois, $whois, '', 0.0, 10, false, true);
    }
}
