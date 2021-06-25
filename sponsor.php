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
$id=isset($_GET['id']) ? trim($_GET['id']) : '';
require('inc/indowapblog.php');
if (!ctype_digit($id))
{
$bz="77202";
ads_buzzcity($bz);
ads_buzzcity($bz);
ads_buzzcity($bz);
ads_buzzcity($bz);
}
else
{
$cek=mysql_query("select * from `sponsor` where `id`='".mysql_real_escape_string($id)."' and `expired` > '".time()."'");
if (mysql_num_rows($cek) == 0)
{
$bz="77202";
ads_buzzcity($bz);
ads_buzzcity($bz);
ads_buzzcity($bz);
ads_buzzcity($bz);
}
else
{
$ad=mysql_fetch_array($cek);
$klik = $ad['click'] + 1;
mysql_query("update sponsor set click='".$klik."' where id='".$ad['id']."'");
header("location: ".str_replace('&amp;','&',htmlspecialchars($ad['url'])));
}
}
mysql_close($iwb_connect);
?>