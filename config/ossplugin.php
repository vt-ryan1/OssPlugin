<?php

return [
    "ALIYUN_OSS_KEY"=>env('ALIYUN_OSS_KEY',null),
    "ALIYUN_OSS_SEC"=>env('ALIYUN_OSS_SEC',null),
    "ALIYUN_OSS_BUCKET"=>env('ALIYUN_OSS_BUCKET',null),
    "ALIYUN_OSS_ENDPOINT"=>env('ALIYUN_OSS_ENDPOINT',null),
    "ALIYUN_OSS_HOST"=>env('ALIYUN_OSS_HOST',null), //oss访问域名，如果没有，就是oss原始域名
    "ALIYUN_OSS_REGION"=>env('ALIYUN_OSS_REGION',null),
    "OSS_FOLADER"=>env('OSS_FOLADER',''),//oss上文件存放路径
    "LOCAL_FOLDER" => env('LOCAL_FOLDER', null), //文件存放本地的路径
];
