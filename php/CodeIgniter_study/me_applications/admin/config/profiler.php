<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Profiler Sections
| -------------------------------------------------------------------------
| This file lets you determine whether or not various sections of Profiler
| data are displayed when the Profiler is enabled.
| Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/profiling.html
|
*/


/*所有分析项列表及说明：
benchmarks	在各个计时点花费的时间以及总时间 TRUE
config	CodeIgniter 配置变量 TRUE
controller_info	被请求的控制器类和调用的方法 TRUE
get	请求中的所有 GET 数据 TRUE
http_headers	本次请求的 HTTP 头部 TRUE
memory_usage	本次请求消耗的内存（单位字节） TRUE
post	请求中的所有 POST 数据 TRUE
queries	列出所有执行的数据库查询，以及执行时间 TRUE
uri_string	本次请求的 URI TRUE
session_data	当前会话中存储的数据 TRUE
query_toggle_count	指定显示多少个数据库查询，剩下的则默认折叠起来 25

全局设置使用规则：$config['分析项']= FALSE/TRUE;    FALSE-关闭  TRUE-开启
关闭举例：$config['config']= FALSE;
说明：空文件默认都开启
*/


