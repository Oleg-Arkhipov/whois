<?php declare(strict_types=1);

namespace Oarkhipov\Whois\Parser;

use Carbon\Carbon;
use Oarkhipov\Whois\Values\Whois;

/**
 * Class responsible for assigning key-value pairs from response to a WHOIS object.
 * @package Oarkhipov\Whois\Parser
 */
class Mapper
{
    /**
     * Configuration - i.e. how to perceive different things from response.
     *
     * Key - WHOIS record field name - property of the Oarkhipov\Whois\Values\Whois object.
     * Properties inside sub-objects are denoted by a dot: "registrant.name"
     *
     * Value - configuration of a given field. Namely:
     * 'keys' - array of all possible keys, under which field appears in different formats of responses,
     * 'type':
     *   - by default a single field, supposed to appear one time in a response.
     *   'array' - field appears multiple time in a response, all values should be placed in array.
     * 'transformation' - indicates that field's value has to be processed in a certain way after retrieving.
     *   See $this->transformDateTime for an example.
     * @var array
     */
    private $mapping = [
        'domain' => [
            'keys' => [
                'domain-ace',
                'domain',
                'domain name',
            ],
        ],
        'registryDomainId' => [
            'keys' => ['registry domain id']
        ],
        'whoisServer' => [
            'keys' => ['whois', 'whois server', 'registrar whois server'],
        ],
        'creationDate' => [
            'transformation' => 'dateTime',
            'keys' => [
                'creation date',
                'created',
                'domain registration date',
                'created on',
                'record created',
                'record registered',
                'domain created',
            ],
        ],
        'updateDate' => [
            'transformation' => 'dateTime',
            'keys' => [
                'updated date',
                'domain last updated date',
                'last updated on',
                'record last updated',
                'last modified',
                'modified',
            ],
        ],
        'expirationDate' => [
            'transformation' => 'dateTime',
            'keys' => [
                'expiration date',
                'paid-till',
                'registry expiry date',
                'domain expiration date',
                'record expires',
                'expires',
            ],
        ],
        'registrar.name' => [
            'keys' => ['registrar'],
        ],
        'registrar.url' => [
            'keys' => ['registrar url'],
        ],
        'registrar.abuseEmail' => [
            'keys' => ['registrar abuse contact email'],
        ],
        'registrar.abusePhone' => [
            'keys' => ['registrar abuse contact phone'],
        ],
        'registrant.name' => [
            'keys' => ['registrant name', 'person']
        ],
        'registrant.organization' => [
            'keys' => ['registrant organization']
        ],
        'registrant.street' => [
            'keys' => ['registrant street']
        ],
        'registrant.city' => [
            'keys' => ['registrant city']
        ],
        'registrant.state' => [
            'keys' => ['registrant state/province']
        ],
        'registrant.postalCode' => [
            'keys' => ['registrant postal code']
        ],
        'registrant.country' => [
            'keys' => ['registrant country']
        ],
        'registrant.phone' => [
            'keys' => ['registrant phone']
        ],
        'registrant.phoneExtension' => [
            'keys' => ['registrant phone ext']
        ],
        'registrant.fax' => [
            'keys' => ['registrant fax']
        ],
        'registrant.faxExtension' => [
            'keys' => ['registrant fax ext']
        ],
        'registrant.email' => [
            'keys' => ['registrant email']
        ],
        'nameServers' => [
            'type' => 'array',
            'keys' => ['nserver', 'name server']
        ],
    ];

    /**
     * @param string[][] $keyValuePairs Each element is array of the form: [key, value].
     * @return Whois
     */
    public function assignKeyValuePairs(array $keyValuePairs): Whois
    {
        $whois = new Whois();
        foreach ($keyValuePairs as $keyValuePair) {
            list($key, $value) = $keyValuePair;
            $fieldName = $this->findFieldNameByKey($key);
            if ($fieldName && $value !== '') {
                $this->assignValueToField($whois, $fieldName, $value);
            }
        }
        return $whois;
    }

    /**
     * @param string $key
     * @return null|string Null if no field was found by the given key.
     */
    private function findFieldNameByKey(string $key): ?string
    {
        foreach ($this->mapping as $fieldName => $configuration) {
            if (in_array(strtolower($key), $configuration['keys'])) {
                return $fieldName;
            }
        }
        return null;
    }

    /**
     * @param Whois $whois
     * @param string $fieldName
     * @param string $value
     */
    private function assignValueToField(Whois $whois, string $fieldName, string $value)
    {
        $value = $this->applyTransformations($fieldName, $value);
        $ofArrayType = (isset($this->mapping[$fieldName]['type']) && $this->mapping[$fieldName]['type'] == 'array');

        if (strpos($fieldName, '.') === false) {
            if ($ofArrayType) {
                $whois->{$fieldName}[] = $value;
            } else {
                $whois->{$fieldName} = $value;
            }
        } else {
            list ($fieldName, $subFieldName) = explode('.', $fieldName);
            if ($ofArrayType) {
                $whois->{$fieldName}->{$subFieldName}[] = $value;
            } else {
                $whois->{$fieldName}->{$subFieldName} = $value;
            }
        }
    }

    /**
     * Passes given field's value through all required transformations.
     * @param string $fieldName
     * @param string $value
     * @return mixed|string Resulting value (its type could change).
     */
    private function applyTransformations(string $fieldName, string $value)
    {
        if (isset($this->mapping[$fieldName]['transformation'])) {
            $transformationName = $this->mapping[$fieldName]['transformation'];
            $value = call_user_func([$this, 'transform' . ucfirst($transformationName)], $value);
        }
        return $value;
    }

    /**
     * @param string $value
     * @return Carbon
     */
    private function transformDateTime(string $value): Carbon
    {
        return new Carbon($value);
    }
}