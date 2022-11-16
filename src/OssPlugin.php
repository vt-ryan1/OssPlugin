<?php

namespace Victtech\OssPlugin;

use Illuminate\Config\Repository;
use Illuminate\Session\SessionManager;
use OSS\OssClient;

class OssPlugin
{
    protected $session;
    protected $config;

    public function __construct(SessionManager $session,Repository $config){
        $this->session = $session;
        $this->config = $config;
    }

    public function uploadFile(string $localFilePath,string $ossFilePath=null)
    {
        $ossBucket = $this->config->get('ossplugin.ALIYUN_OSS_BUCKET');
        $ossEndPoint = $this->config->get('ossplugin.ALIYUN_OSS_ENDPOINT');
        $ossClient = new OssClient($this->config->get('ossplugin.ALIYUN_OSS_KEY'),$this->config->get('ossplugin.ALIYUN_OSS_SEC'),$ossEndPoint);
        if($ossFilePath == null){
            $localFilePath = str_replace('\\','/',$localFilePath);
            $tmp = explode('/',$localFilePath);
            $ossFilePath = $tmp[count($tmp)-1];
        }
        $ossClient->uploadFile($ossBucket,$ossFilePath,$localFilePath);

    }
}
