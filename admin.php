<?php

/*

IndoWapBlog-beta-v01.zip Full Editing by : Master Chef IWB
Facebook : http://fb.com/mhozacuplis1
Website : http://cuplascuplis.jw.lt
Website : http://cuplis.tk
Website : http://cuplis.fixi.in

*Nama Script: IndoWapBlog-beta-v01
*Versi: (Lihat VERSION.txt)
*Pembuat: Achunk JealousMan
*Email: achunk17[at]gmail[dot]com
*Situs: http://indowapblog.com
*Facebook: http://www.facebook.com/achunks
*/
define('_IWB_', 1);
$iwb=isset($_GET['iwb']) ? trim($_GET['iwb']) : '';
$iwb=htmlentities($iwb);

include('inc/indowapblog.php');
switch ($iwb)
{
case 'ads':
include('inc/admin/ads.php');
break;
case 'category':
include('inc/admin/category.php');
break;
case 'credit':
include('inc/admin/credit.php');
break;
case 'css_editor':
include('inc/admin/css_editor.php');
break;
case 'domain_parking':
include('inc/admin/domain_parking.php');
break;
case 'following':
include('inc/admin/following.php');
break;
case 'google':
include('inc/admin/google.php');
break;
case 'html_tutorial':
include('inc/admin/html_tutorial.php');
break;
case 'navigation':
include('inc/admin/navigation.php');
break;
case 'settings':
include('inc/admin/settings.php');
break;
case 'stats':
include('inc/admin/stats.php');
break;
case 'subscribe':
include('inc/admin/subscribe.php');
break;
default:
$defined='on';
include('dashboard.php');
break;
}
?>