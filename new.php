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


require_once('inc/indowapblog.php');
$page=$_GET['page'];
$total=mysql_result(mysql_query("select count(*) as num from `blog` where `link`!='hallo-dunia' and `draft`='0'"), 0);
if (!ctype_digit($page) || empty($page) || $page == 0 || $page > (ceil($total / $site['num_post_main'])))
$page='1';
$page--;
$max_view=$site['num_post_main'];
$limit=$page*$max_view;
$page++;

$head_title=$LANG['latest_post'];
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
echo '</div><div id="content"><div id="main-content"><ul id="iphone-list">';
if ($total > 0)
{
$req=mysql_query("select `site_id`, `title`, `link`, `time` from `blog` where `link`!='hallo-dunia' and `draft`= '0' order by `time` desc limit $limit,$max_view");
while ($res=mysql_fetch_array($req))
{
$site_post=mysql_fetch_array(mysql_query("SELECT name, url FROM site WHERE id='".$res['site_id']."'"));
echo '<li><a href="'.$site_post['url'].'/'.$res['link'].'.xhtml">'.htmlspecialchars($res['title']).' | '.htmlspecialchars($site_post['name']).' <font color="#666666">'.time_ago($res['time']).'</font></a></li>';
}
}
else
{
echo '<li>'.$LANG['empty'].'</li>';
}
echo '</ul>';
$link='new.php?page=';
$q='';
pagination($page,$max_view,$total,$link,$q);
echo '</div></div>';
require_once('inc/foot.php');
?>