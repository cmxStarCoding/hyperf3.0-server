<?php

declare(strict_types=1);

namespace App\Logic;

use App\Exception\LogicException;
use App\Services\WechatProtocolService;
use Hyperf\Di\Annotation\Inject;

class MessageLogic
{
    const CrmAppId = 'wx_SLjAeoK4lx3NjDrxPaHva';
    const CrmTypeName = 'AddMsg';

    #[Inject]
    public WechatProtocolService $protocolService;

    public function postText($params)
    {
        $responseData = $this->protocolService->setRoute("/Msg/SendTxt")
            ->setRequestParams($params)
            ->doRequest()
            ->getResponseData();
        return json_decode($responseData,true);
    }

    public function postImage($params)
    {
        $imgUrl = $params["imgUrl"] ?? '';
        $toWxid = $params["ToWxid"] ?? '';
        $wxid = $params["Wxid"] ?? '';
        $imageData = file_get_contents($imgUrl);
        if($imageData === false){
            throw new LogicException("文件链接参数异常");
        }
        $base64 = base64_encode($imageData);
        $responseData = $this->protocolService->setRoute("/Msg/UploadImg")
            ->setRequestParams([
                "Base64" => $base64,
                "ToWxid" => $toWxid,
                "Wxid" => $wxid,
            ])
            ->doRequest()
            ->getResponseData();
        return json_decode($responseData,true);
    }

    public function postFile($params)
    {
        $wxid = $params["Wxid"] ?? '';
        $fileUrl = $params["fileUrl"] ?? '';
        $fileName = $params["fileName"] ?? '';
        $toWxid = $params["toWxid"] ?? '';

        $imageData = file_get_contents($fileUrl);
        if($imageData === false){
            throw new LogicException("文件链接参数异常");
        }
        $base64 = base64_encode($imageData);

        $responseData = $this->protocolService->setRoute("/Tools/UploadFile")
            ->setRequestParams([
                "Base64" => $base64,
                "Wxid" => $wxid,
            ])
            ->doRequest()
            ->getResponseData();
        $uploadResponseData = json_decode($responseData,true);
        $mediaId = $uploadResponseData["Data"]["mediaId"] ?? "";
        $totalLen = $uploadResponseData["Data"]["totalLen"] ?? "";

        $responseData = $this->protocolService->setRoute("/Msg/SendCDNFile")
            ->setRequestParams([
                "Content" => "<appmsg appid='wxeb7ec651dd0aefa9' sdkver=''><title>$fileName</title><des></des><action></action><type>6</type><content></content><url></url><lowurl></lowurl><appattach><totallen>$totalLen</totallen><attachid>$mediaId</attachid><fileext>xlsx</fileext></appattach><extinfo></extinfo></appmsg>",
                "ToWxid" => $toWxid,
                "Wxid" => $wxid,
            ])
            ->doRequest()
            ->getResponseData();
        return json_decode($responseData,true);
    }

}