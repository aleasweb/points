<?php

namespace App\Controller;

use App\Entity\Direction;
use App\Entity\DirectionPool;
use App\Entity\InputLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PointsController extends AbstractController
{
    /**
     * @Route("/points", name="points")
     */
    public function index()
    {
        $data = (new InputLoader())->parseFromFile(__DIR__ . '/../Sample/final.dat');

        $pool = new DirectionPool();

        $result = [];
        $countLine = 0;
        foreach ($data as $line) {
            $arr = explode(' ', $line);

            if (count($arr) == 1) {
                $countLine = (int)$arr[0];
            } else {
                $countDirection = count($arr);
                if ($countLine && $countDirection >= 2) {
                    $direction = new Direction();
                    $direction->setStart((float)$arr[0], (float)$arr[1]);
                    $i = 2;
                    while ($i < $countDirection - 1) {
                        $direction->setCommand($arr[$i], (float)$arr[$i+1]);
                        $i += 2;
                    }
                    $pool->set($direction);
                    $countLine--;
                }
            }
            if (!$countLine) {
                $result[] = implode(' ', $pool->computing());
                $pool->clear();
            }
        }

        return new Response(implode('<br />', $result));
    }
}
