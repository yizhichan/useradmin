#!/bin/sh

#ע�⣡����
#ʹ��ʱ���뽫���ļ��ŵ�����ĿĿ¼ƽ���������������ĿĿ¼������Ӱ��cron��ʱ��������д���

#phpִ��·��
phpdir='/usr/sbin/php';
#����·��
webdir='/usr/local/nginx/wwwroot/tr.chofn.net';
#php�ļ�·��
phpfile=$webdir'/cmd.php';
#����
param='/run/index/';

step=10 #���������  
  
for (( i = 0; i < 60; i=(i+$step) )); do 
    $phpdir $phpfile $param &
    sleep $step  
done  
  
exit 0 
