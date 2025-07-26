<?php
declare(strict_types=1);

namespace App\JsonRpc;
interface CalculatorServiceInterface
{
    public function add(int $a, int $b);
}