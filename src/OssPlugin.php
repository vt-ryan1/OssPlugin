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

    public function uploadFile(string $localFilePath = null,string $ossFilePath=null)
    {
        $ossBucket = $this->config->get('ossplugin.ALIYUN_OSS_BUCKET');
        $ossEndPoint = $this->config->get('ossplugin.ALIYUN_OSS_ENDPOINT');
        $ossClient = new OssClient($this->config->get('ossplugin.ALIYUN_OSS_KEY'),$this->config->get('ossplugin.ALIYUN_OSS_SEC'),$ossEndPoint);
        if($ossFilePath == null){
            $localFilePath = str_replace('\\','/',$localFilePath);
            $tmp = explode('/',$localFilePath);
            $ossFilePath = $this->config->get('ossplugin.OSS_FOLADER').$tmp[count($tmp)-1];
        }
        $rs = $ossClient->uploadFile($ossBucket,$ossFilePath,$localFilePath);
        $returnUrl = ($this->config->get('ossplugin.ALIYUN_OSS_HOST')==null)?$rs['oss-request-url']:$this->config->get('ossplugin.ALIYUN_OSS_HOST').$ossFilePath;
        return $returnUrl;

    }
}
