<?php

namespace App\Entity;

class DirectionPool
{
    /**
     * @var array of Direction
     */
    private $pool = [];

    /**
     * @param Direction $direction
     */
    public function set(Direction $direction) {
        $this->pool[] = $direction;
    }

    public function clear() {
        $this->pool = [];
    }

    /**
     * @return array format [avgX, avgY, deviation]
     */
    public function computing() : array {
        // computing avg endpoint coordinates
        $endpoints = [[],[]];
        /* var Direction $direction */
        foreach ($this->pool as $direction) {
            list($x, $y) = $direction->getEndpoint();
            $endpoints[0][] = $x;
            $endpoints[1][] = $y;
        }
        $countEndpoints = count($this->pool);
        if ($countEndpoints) {
            $avgX = array_sum($endpoints[0])/count($this->pool);
            $avgY = array_sum($endpoints[1])/count($this->pool);
        }

        // computing max deviation
        $maxDeviation = 0;
        for ($i = 0; $i < $countEndpoints; $i++) {
            $distanceDeviation = sqrt(pow($avgX - $endpoints[0][$i], 2)
                + pow($avgY - $endpoints[1][$i], 2));
            if ($distanceDeviation > $maxDeviation) {
                $maxDeviation = $distanceDeviation;
            }
        }

        return $countEndpoints
            ? [round($avgX, 4), round($avgY, 4), round($maxDeviation, 5)]
            : [];
    }
}