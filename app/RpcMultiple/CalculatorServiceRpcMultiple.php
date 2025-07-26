<?php

namespace App\RpcMultiple;

use App\JsonRpc\CalculatorServiceInterface;
use Hyperf\RpcMultiplex\Constant;
use Hyperf\RpcServer\Annotation\RpcService;

#[RpcService(name: "CalculatorServiceRpcMultiple", server: "rpc", protocol: Constant::PROTOCOL_DEFAULT)]
class CalculatorServiceRpcMultiple implements CalculatorServiceInterface
{
    public function add($a, $b)
    {
        var_dump("进入hyperf1服务");
        return $a + $b;
    }
}
