<?php

namespace App\Crontab;

use App\Model\User;
use App\Services\WechatProtocolService;
use Carbon\Carbon;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Coroutine\Coroutine;
use Hyperf\Contract\StdoutLoggerInterface;

#[Crontab(rule: "*\/3 * * * * *", name: "IsOnlineMaintenanceCrontab", callback: "execute", memo: "在线状态维护", enable: "isEnable")]
class IsOnlineMaintenanceCrontab
{
    #[Inject]
    public WechatProtocolService $protocolService;

    #[Inject]
    public StdoutLoggerInterface $logger;

    public function execute()
    {
        $users = User::query()->get()->toArray();
        if(!empty($users)){
            foreach ($users as $user){
                Coroutine::create(function () use ($user) {
                    $isOnline = $this->checkUserIsOnline($user);
                    $this->logger->info(date('Y-m-d H:i:s', time())."用户状态为".($isOnline ? "在线":"离线"));
                    User::query()->where('id', $user['id'])->update(['is_online' => $isOnline ? 1 :0 ,"updated_at" => Carbon::now()->format('Y-m-d H:i:s')]);
                });
            }
        }
    }

    public function checkUserIsOnline($user)
    {
        $responseData = $this->protocolService->setRoute("/Login/HeartBeat?wxid=".$user['wxid'])->setRequestParams([])->doRequest()->getResponseData();
//        $this->logger->info(date('Y-m-d H:i:s', time())."获取到的数据为".$responseData);

        if(empty($responseData)){
            return false;
        }

        $responseData = json_decode($responseData,true);
        if(isset($responseData['Code']) && $responseData['Code'] != 0){
            return false;
        }
        if(isset($responseData['Code']) && $responseData['Code'] === 0){
            return true;
        }
        return false;
    }

    public function isEnable(): bool
    {
        return env('APP_ENV', "prod") == "prod";
    }
}
