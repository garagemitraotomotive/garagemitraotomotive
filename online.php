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

$page=angka($_GET['page']);
$ol = time() - 300;
$user_ol=mysql_result(mysql_query("SELECT COUNT(*) FROM user WHERE lastdate > $ol"), 0);

if (!ctype_digit($page) || empty($page) || $page == 0 || $page > (ceil($user_ol / $site['num_post_main'])))$page='1';$page--;$max_view=$site['num_post_main'];$limit=$page*$max_view;$page++;
$head_title='Member Online';
require_once('inc/head.php');
echo '<div id="message">';
echo '</div>';
echo '<div id="content">
<div id="main-content">';

echo '<ol>';
$req=mysql_query("SELECT * FROM user WHERE lastdate>'".$ol."' ORDER BY 'lastdate' DESC LIMIT $limit,$max_view");
if ($user_ol > 0)
{
while ($res=mysql_fetch_array($req)){
echo $i % 2 ? '<li class="row1">' : '<li class="row0">';
echo '<img src="img.php?img='.$res['id'].'.jpg&amp;w=40&amp;h=40" alt="'.htmlspecialchars($res['name']).'"/> <a href="user.php?id='.$res['id'].'">';

if (($res['author'] == '1') && ($res['admin'] == '0'))
{
echo '<font color="green">'.htmlspecialchars($res['name']).'</font>';
}

elseif (($res['author'] == '2') && ($res['admin'] == '0'))
{
echo '<font color="red">'.htmlspecialchars($res['name']).'</font>';
}

elseif (($res['author'] == '3') && ($res['admin'] == '0'))
{
echo '<font color="blue">'.htmlspecialchars($res['name']).'</font>';
}

elseif (($res['author'] == '4') && ($res['admin'] == '0'))
{
echo '<font color="orange">'.htmlspecialchars($res['name']).'</font>';
}

elseif ($res['admin'] == '1')
{
echo '<font color="#731174">'.htmlspecialchars($res['name']).'</font>';
}
else
{
echo '<font color="black">'.htmlspecialchars($res['name']).'</font>';
}

echo '</a><br/>Online: '.time_ago($res['lastdate']).'';
++$i;
echo '</li>';
}
}
else
{
echo '<li>Tidak ada member yang online</li>';
}
echo '</ol></div>';
$total=$user_ol;
$link='online.php?page=';
$q='';
pagination($page,$max_view,$total,$link,$q);
echo '</div>';
require_once('inc/foot.php');
?>