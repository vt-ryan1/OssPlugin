# OssPlugin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]


## Installation

Via Composer

``` bash
$ composer require victtech/ossplugin
```

## Usage
### 发布配置文件
```bash
$ php artisan vendor:publish --tag=ossplugin.config 
```

### 配置文件说明
```phpregexp
ALIYUN_OSS_KEY  //OSS key
ALIYUN_OSS_SEC  //OSS secrite
ALIYUN_OSS_BUCKET //OSS bucket
ALIYUN_OSS_ENDPOINT //OSS endpoint 例如：oss-cn-shanghai.aliyuncs.com，oss-cn-beijing.aliyuncs.com
OSS_FOLADER = //文件存放在oss的目录名称，没有则存放在bucket跟目录下，路径格式:"xxx/xxx/"
ALIYUN_OSS_HOST = //OSS 配置的访问路径，一般是CDN路径，如果没有，则使用oss默认路径
LOCAL_FOLDER //上传存储到本地的目录
```

### 使用方法
```phpregexp
//$localFilePath 本地文件路径
//$ossFilePath 存放在oss的文件名,如果带路径，则存到配置下的目录下的路径
OssPlugin::uploadFile($localFilePath,$ossFileName)

//$httpUploadFile 前端上传的文件
OssPlugin::uploadFileByFile($httpUploadFile,$ossFileName)

//$httpUploadFile 前端上传的文件
//$filePath 储存在本地的路径及文件名
//此方法会把所有文件都存放到pulbic目录下
OssPlugin::uploadToLocal($httpUploadFile,&$filePath);
```
