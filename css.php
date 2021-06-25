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

$iwb_redirecting='off';

require_once('inc/indowapblog.php');
$folder='themes/';
$theme=htmlentities($_GET['theme']);
$css='/css/'.$site['id'].'.css';
$open_file = $folder . $theme . $css;
switch ($theme)
{
case 'mywapblog':
if (file_exists($open_file))
{
$file_location=$open_file;
$size = filesize($open_file);
$file_name='style.css';
}
else
{
$file_location='themes/mywapblog/css/default.css';
$size = filesize($file_location);
$file_name='default.css';
}
break;

case 'desktop':
if (file_exists($open_file))
{
$file_location=$open_file;
$size = filesize($open_file);
$file_name='style.css';
}
else
{
$file_location='themes/desktop/css/default.css';
$size = filesize($file_location);
$file_name='default.css';
}
break;

case 'default':
default:
if (file_exists($open_file))
{
$file_location=$open_file;
$size = filesize($open_file);
$file_name='style.css';
}
else {
$file_location='themes/default/css/default.css';
$size = filesize($file_location);
$file_name='default.css';
}
}
header('Content-Description: File Transfer');
header('Content-Type: text/css');
header('Content-Disposition: attachment; filename=' . $file_name);
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . $size);
ob_clean();
flush();
readfile($file_location);
exit;
mysql_close($iwb_connect);
?>