<?php

declare(strict_types=1);

namespace App\Logic;

use App\Model\User;
use App\Services\WechatProtocolService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;

class UserLogic{
    const isOnlineYes = 1;
    const isOnlineNo = 0;

    #[Inject]
    public Redis $redis; // 自动注入 Redis 客户端（默认连接）

    #[Inject]
    public WechatProtocolService $protocolService; // 自动注入 Redis 客户端（默认连接）

    public function userList($params)
    {
        $users = User::query()->get()->toArray();
        if(!empty($users)){
            foreach ($users as &$user) {
                $user["HeadUrl"] = '';
                $user["NickName"] = '';
                $userData = $this->redis->get($user["wxid"]);
                if(!empty($userData)){
                    $userData = json_decode($userData,true);
                    $user["HeadUrl"] = $userData["HeadUrl"] ?? "";
                    $user["NickName"] =  $userData["NickName"] ?? "";
                }
            }
        }
        return $users;
    }

    public function getIpadQrcode($params){
        $responseData = $this->protocolService->setRoute("/VXAPI/Login/GetQRMac")
            ->setRequestParams($params)
            ->doRequest()
            ->getResponseData();
        return json_decode($responseData,true);
    }

    public function checkQr($params){
        $responseData = $this->protocolService->setRoute("/VXAPI/Login/CheckQR?uuid=".$params['uuid'])->setRequestParams($params)->doRequest()->getResponseData();
        $responseData = json_decode($responseData,true);
        if(isset($responseData["Code"]) && $responseData["Code"] == 0){
            User::query()->create([
                "wxid" => $responseData['Data']['acctSectResp']['userName'] ?? '',
                "is_online" => self::isOnlineYes,
            ]);
        }
        return $responseData;
    }

}

