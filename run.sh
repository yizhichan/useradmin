#!/bin/sh

#注意！！！
#使用时，请将此文件放到与项目目录平级并设置下面的项目目录！否则将影响cron定时任务与队列处理！

#php执行路径
phpdir='/usr/sbin/php';
#代码路径
webdir='/usr/local/nginx/wwwroot/tr.chofn.net';
#php文件路径
phpfile=$webdir'/cmd.php';
#参数
param='/run/index/';

step=10 #间隔的秒数  
  
for (( i = 0; i < 60; i=(i+$step) )); do 
    $phpdir $phpfile $param &
    sleep $step  
done  
  
exit 0 
