<?php

namespace Victtech\OssPlugin;

use Illuminate\Config\Repository;
use Illuminate\Http\UploadedFile;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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
        $ossFolader = $this->config->get('ossplugin.OSS_FOLADER');
        $ossClient = new OssClient($this->config->get('ossplugin.ALIYUN_OSS_KEY'),$this->config->get('ossplugin.ALIYUN_OSS_SEC'),$ossEndPoint);
        if($ossFilePath == null){
            $localFilePath = str_replace('\\','/',$localFilePath);
            $tmp = explode('/',$localFilePath);
            $ossFilePath = $tmp[count($tmp)-1];
        }
        $ossFilePath = $ossFolader.$ossFilePath;
        $rs = $ossClient->uploadFile($ossBucket,$ossFilePath,$localFilePath);
        $returnUrl = ($this->config->get('ossplugin.ALIYUN_OSS_HOST')==null)?$rs['oss-request-url']:$this->config->get('ossplugin.ALIYUN_OSS_HOST').$ossFilePath;
        return $returnUrl;

    }

    public function uploadFileByFile(UploadedFile $file,string $ossFilePath=null)
    {
        $ossBucket = $this->config->get('ossplugin.ALIYUN_OSS_BUCKET');
        $ossEndPoint = $this->config->get('ossplugin.ALIYUN_OSS_ENDPOINT');
        $ossFolder = $this->config->get('ossplugin.OSS_FOLADER');
        $ossClient = new OssClient($this->config->get('ossplugin.ALIYUN_OSS_KEY'),$this->config->get('ossplugin.ALIYUN_OSS_SEC'),$ossEndPoint);
        if($ossFilePath==null){
            $ossFilePath = $this->buildFileName($file->getClientOriginalName());
        }
        $ossFilePath = $ossFolder.$ossFilePath;
        $rs = $ossClient->uploadFile($ossBucket,$ossFilePath,$file);
        $returnUrl = ($this->config->get('ossplugin.ALIYUN_OSS_HOST')==null)?$rs['oss-request-url']:$this->config->get('ossplugin.ALIYUN_OSS_HOST').$ossFilePath;
        return $returnUrl;

    }

    /**
     * 删除oss上的文件
     * @param string $ossFile 文件路径
     * @return void
     */
    public function removeFile(string $ossFile)
    {
        $ossBucket = $this->config->get('ossplugin.ALIYUN_OSS_BUCKET');
        $ossEndPoint = $this->config->get('ossplugin.ALIYUN_OSS_ENDPOINT');
        $ossClient = new OssClient($this->config->get('ossplugin.ALIYUN_OSS_KEY'),$this->config->get('ossplugin.ALIYUN_OSS_SEC'),$ossEndPoint);
        if($this->config->get('ossplugin.ALIYUN_OSS_HOST') == null){
            $ossFileArr = explode('/',$ossFile);
            $ossFile = "";
            for($i=3;$i<count($ossFileArr);$i++){
                $ossFile .= $ossFileArr[$i].'/';
            }
            $ossFile = substr($ossFile,0,strlen($ossFile)-1);
        }else{
            $ossFile = str_replace($this->config->get('ossplugin.ALIYUN_OSS_HOST'),'',$ossFile);
        }

        $ossClient->deleteObject($ossBucket,$ossFile);
    }


    public function uploadToLocal(UploadedFile $file,string $filePath = null)
    {
        //计算文件路径，放到public目录下
        $realPath = public_path('/'); //物理真实路径
        $prefix = '';
        if($this->config->get('ossplugin.LOCAL_FOLDER') ==null){
            $prefix .= 'uploads'.'/';
        }else{
            $pathAtt = explode('/',str_replace('\\','/',$this->config->get('ossplugin.LOCAL_FOLDER')));
            foreach($pathAtt as $item){
                if($item !== null && $item !== ''){
                    $prefix .= $item.'/';
                }
            }
        }

        if($filePath==null){
            $filePath = $prefix.$this->buildFileName($file->getClientOriginalName());
        }else{
            $filePath = str_replace('//','/',$prefix.str_replace('\\','/',$filePath)) ;
        }

        $fullPath = $realPath.$filePath;
        //检测路径目录是否存在,并创建目录
        $pathAtt = explode('/',$fullPath);
        $path = '';
        for($i = 0; $i < count($pathAtt)-1 ; $i++){
            if($pathAtt[$i] != ''){
                $path .= '/'.$pathAtt[$i];
                if(is_dir($path)){
                    continue;
                }else{
                    mkdir($path);
                }
            }
        }
        copy($file->path(),$fullPath);
        return $filePath;
    }

    private function buildFileName($originalName){
        return time().'_'.str_replace(' ','_',$originalName);
    }

}
