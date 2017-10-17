<?php

namespace Oarkhipov\Whois;

use Carbon\Carbon;
use Oarkhipov\Whois\Values\Whois;

/**
 * Class providing functionality to merge two WHOIS objects into one.
 *
 * This is required because multiple WHOIS records may exist for one domain,
 * each of them containing pieces of unique information.
 * @package Oarkhipov\Whois
 */
class WhoisMerger
{
    /**
     * Merge two WHOIS objects.
     *
     * @param Whois $first
     * @param Whois $second
     * @return Whois
     */
    public function merge(Whois $first, Whois $second): Whois
    {
        return $this->mergeObjects($first, $second);
    }

    /**
     * Merge arbitrary values.
     *
     * @param $first
     * @param $second
     * @return Carbon|mixed
     */
    private function mergeValues($first, $second)
    {
        if (is_null($first) && !is_null($second)) {
            return $second;
        }
        if (!is_null($first) && is_null($second)) {
            return $first;
        }

        if ($first instanceof Carbon) {
            $result = $this->mergeDates($first, $second);
        } elseif (is_object($first)) {
            $result = $this->mergeObjects($first, $second);
        } else {
            $result = $first;
        }
        return $result;
    }

    /**
     * Merge two arbitrary objects.
     *
     * @param $first
     * @param $second
     * @return mixed
     */
    private function mergeObjects($first, $second)
    {
        $className = get_class($first);
        $merged = new $className;

        foreach ($first as $key => $value) {
            $firstValue = $first->{$key};
            $secondValue = $second->{$key};
            $merged->{$key} = $this->mergeValues($firstValue, $secondValue);
        }

        return $merged;
    }

    /**
     * Merge two Carbon dates.
     *
     * @param Carbon $first
     * @param Carbon $second
     * @return Carbon
     */
    private function mergeDates(Carbon $first, Carbon $second): Carbon
    {
        if ($first->greaterThan($second)) {
            return $first;
        }
        return $second;
    }
}