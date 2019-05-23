<?php

namespace App\Entity;

class Direction
{
    /**
     * commands name
     */
    const START_COMMAND = 'start';
    const TURN_COMMAND = 'turn';
    const WALK_COMMAND = 'walk';

    private $startX = 0;
    private $startY = 0;
    private $commands = [];

    private $direction = 0;
    private $endX = 0;
    private $endY = 0;
    private $isCalculated = false;


    public function setStart(float $x, float $y)
    {
        $this->startX = $x;
        $this->startY = $y;
    }

    public function setCommand(string $name, float $value)
    {
        $this->commands[] = [$name, $value];
    }

    /**
     * @return array format [x, y]
     */
    public function getEndpoint() : array
    {
        if (!$this->isCalculated) {
            $this->computingEndpoint();
        }

        return [$this->endX, $this->endY];
    }

    /**
     * computing endpoint coordinates by start and commands
     * @return void
     */
    private function computingEndpoint()
    {
        $this->endX = $this->startX;
        $this->endY = $this->startY;

        foreach ($this->commands as $command) {
            $commandName = $command[0];
            $value = $command[1];

            switch ($commandName) {
                case self::START_COMMAND:
                    $this->direction = $value;
                    break;
                case self::TURN_COMMAND:
                    $this->direction += $value;
                    if ($this->direction > 270) {
                        $this->direction -= 360;
                    }
                    break;
                case self::WALK_COMMAND:
                    $this->endX += $value * cos(deg2rad($this->direction));
                    $this->endY += $value * sin(deg2rad($this->direction));
                    break;
                default:
                    // incorrect command
            }
        }

        $this->isCalculated = true;
    }
}