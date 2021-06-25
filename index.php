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
require('inc/indowapblog.php');
require_once('inc/mobile_detect.class.php');
$detect = new mobile_detect();

$sess_mode=isset($_SESSION['viewmod']) ? stripslashes($_SESSION['viewmod']) : 'default';

// Template situs
if ($site['theme'] == "default") {
$WapDef = "default";
}
elseif ($site['theme'] == "mywapblog")
{
$WapDef = "mywapblog";
}
else {
$WapDef = "default";
}

// Browser detector
if ($detect->isMobile()) {
if ($sess_mode == 'default' || $sess_mode == 'mobile')
$mode='themes/'.$WapDef;
else
$mode='themes/desktop';
}
else {
if ($sess_mode == 'default' || $sess_mode == 'desktop')
$mode='themes/desktop';
else
$mode='themes/'.$WapDef;
}

$iwb=isset($_GET['iwb']) ? trim($_GET['iwb']) : '';

switch ($iwb)
{
case 'desktop_view':
$_SESSION['viewmod']='desktop';
header('location: '.$site_url.'/home.xhtml');
break;

case 'mobile_view':
$_SESSION['viewmod']='mobile';
header('location: '.$site_url.'/home.xhtml');
break;

case 'index':
require_once(''.$mode.'/index.php');
break;

case 'category':
require_once(''.$mode.'/category.php');
break;
case 'read':
require_once(''.$mode.'/read.php');
break;

case 'comment':
include(''.$mode.'/comment.php');
break;

case 'subscribe':
require_once(''.$mode.'/subscribe.php');
break;

case 'guestbook':
require_once(''.$mode.'/guestbook.php');
break;

case 'follow':
include(''.$mode.'/follow.php');
break;

case 'feedback':
require_once(''.$mode.'/feedback.php');
break;

case 'error':
require_once(''.$mode.'/error.php');
break;

default:
if ($_SERVER['HTTP_HOST'] == $iwb_main_domain || $_SERVER['HTTP_HOST'] == 'www.'.$iwb_main_domain.'')
{
$live_chat='off';
$head_title=$iwb_main_domain;
require_once('inc/head.php');
echo '<style type="text/css">
#iphone-list {margin: 2px; padding: 2px;}
#iphone-list li {list-style-type: none; margin: 2px; padding: 2px;}
#iphone-list .heading {list-style-type: none; margin: 2px; padding: 6px 5px 6px 5px; background-color: #333333; color: #FFFFFF; font-weight: bold;}
#iphone-list li a { display:block; padding: 6px 5px 6px 5px;}
.iphone-list-border li a {border-top: 2px solid #B4B4B4;}
#iphone-list li a:hover
{background-color: #E1E1E1;}</style>';
echo '<div id="message">';
echo '</div><div id="content"><div id="main-content">';
if (!$user_id)
echo ''.$LANG['welcome'].'<br /><li><a href="'.$site['url'].'/home.xhtml">'.$LANG['more'].' &raquo;</a></li><li><a href="'.$site['url'].'/login.php">'.$LANG['login'].' &raquo;</a></li><li><a href="'.$site['url'].'/register.php">'.$LANG['registration'].' &raquo;</a></li></p>';
echo '<h2 class="title">'.$LANG['bookmark'].'</h2><ul class="menu"><li><a href="'.$site['url'].'/home.xhtml">'.$LANG['official_blog'].'</a></li>';
if ($user_id)
echo '<li><a href="'.$site['url'].'/feedback.xhtml">'.$LANG['contact_us'].'</a></li>';
else
echo '<li><a href="http://cuplascuplis.jw.lt/login.php">Free WapMasTer</a></li>';
echo '<li><a href="http://chefile.tk">Free Upload File</a></li><li><a href="'.$site['url'].'/iklan.php">'.$LANG['advertisement'].'</a></li>';
echo '</ul>';
echo '<h2 class="title">Pengelola</h2><ul class="menu">';
$req=mysql_query("SELECT id, name FROM user ORDER BY date_reg DESC LIMIT 5;");
while ($res=mysql_fetch_array($req))
{
echo '<li><a href="'.$site['url'].'/user.php?id='.$res['id'].'">'.htmlspecialchars($res['name']).'</a></li>';
}
echo '</ul><h2 class="title">Posting Wapmaster</h2><ul class="menu">';
$req=mysql_query("SELECT site_id, title, link, time FROM blog WHERE link != 'hallo-dunia' AND draft = '0' ORDER BY time DESC LIMIT 5;");
while ($res=mysql_fetch_array($req))
{
$site_post=mysql_fetch_array(mysql_query("SELECT name, url FROM site WHERE id='".$res['site_id']."'"));
echo '<li><a href="'.$site_post['url'].'/'.$res['link'].'.xhtml">'.htmlspecialchars($res['title']).' | '.htmlspecialchars($site_post['name']).' <br/><font color="#666666">'.time_ago($res['time']).'</font></a></li>';
}
echo '<li><a href="new.php"><font color="#666666">'.$LANG['next'].' &raquo;</font></a></li>';
echo '</ul>';

echo '<center></center></div></div>';
require_once('inc/foot.php');
}
else {
require_once(''.$mode.'/index.php');
}
}
?>