<?php

namespace App\Crontab;

use App\Logic\MessageLogic;
use App\Logic\UserLogic;
use App\Model\User;
use App\Services\WechatProtocolService;
use GuzzleHttp\Client;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Coroutine\Coroutine;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;

#[Crontab(rule: "*\/2 * * * * *", name: "MsgNotifyCrontab", callback: "execute", memo: "消息回调", enable: "isEnable")]
class MsgNotifyCrontab
{
    #[Inject]
    public WechatProtocolService $protocolService;

    #[Inject]
    public StdoutLoggerInterface $logger;

    public function execute()
    {
        $users = User::query()->where("notify_url","<>","")->where("is_online",UserLogic::isOnlineYes)->get()->toArray();
        if(!empty($users)){
            foreach ($users as $user){
                Coroutine::create(function () use ($user) {
                    $this->msgNotify($user);
                });
            }
        }
    }

    public function msgNotify($user){
        $requestParams = ["Scene" => 0, "Synckey" => "", "Wxid" => $user["wxid"]];
        $responseData = $this->protocolService->setRoute("/Msg/Sync")->setRequestParams($requestParams)->doRequest()->getResponseData();
        if(empty($responseData)){
            return;
        }
        $responseData = json_decode($responseData,true);
        if(!isset($responseData["Code"]) || $responseData["Code"] != 0){
            return;
        }
        $addMsgs = $responseData["Data"]["AddMsgs"] ?? [];
        if(!empty($addMsgs)){
            foreach ($addMsgs as $addMsg){
                Coroutine::create(function () use($addMsg,$user) {
                    $msgType = $addMsg["MsgType"] ?? -1;
                    $msgId = $addMsg["MsgId"] ?? -1;
                    if($msgType == 1){
                        $this->logger->info(date('Y-m-d H:i:s', time())."消息id".$msgId."消息内容".json_encode($addMsg,JSON_UNESCAPED_UNICODE));
                        $requestParams["Data"] = $addMsg;
                        $requestParams["Wxid"] = $user["wxid"];
                        $requestParams["Appid"] = MessageLogic::CrmAppId;
                        $requestParams["TypeName"] = MessageLogic::CrmTypeName;
                        $response = (new Client())->post(trim($user["notify_url"]),[
                            "json" => $requestParams,
                        ])->getBody()->getContents();
                        $this->logger->info(date('Y-m-d H:i:s', time())."消息id".$msgId."回调结果".$response);
                    }
                });
            }
        }
    }

    public function isEnable(): bool
    {
        return env('APP_ENV', "prod") == "prod";
    }
}
