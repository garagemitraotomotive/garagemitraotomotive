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
$rd='http://'.$_SERVER['HTTP_HOST'].htmlspecialchars($_SERVER['REQUEST_URI']).'';
$rd=base64_encode($rd);
$iwb=isset($_GET['iwb']) ? trim($_GET['iwb']) : '';
switch ($iwb)
{
case 'delete':
$comid=htmlentities($_GET['comid']);
$all=htmlentities($_GET['all']);
if ($all == 'spam')
$sts='2';
if ($all == 'unapproved')
$sts='0';
if ($all == 'approved')
$sts='1';
$redir=$_GET['redir'];
$back=str_replace('&amp;', '&', htmlentities(base64_decode($redir)));
if (!$user_id)
relogin();
if (!$is_author)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
if (isset($_GET['yes']))
{
$redir=str_replace('&amp;', '&', htmlentities(base64_decode($_GET['redir'])));
if (!$is_author)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}

if ($all)
mysql_query("delete from guestbook where site_id='".$user_id."' and status='".$sts."'");

if ($comid)
mysql_query("delete from guestbook where id='".mysql_real_escape_string($comid)."' and site_id='".$user_id."'");

header('location: '.$redir.'');
}
$head_title=$LANG['delete'];
require_once('inc/head.php');
echo '<div id="message"></div><div id="content"><div id="main-content">';

if ($all == 'approved')
echo ''.$LANG['delete_confirm'].'<br/>[<a href="manage_guestbook.php?iwb=delete&amp;all='.$all.'&amp;yes=&amp;redir='.$redir.'">'.$LANG['yes'].'</a>] [<a href="'.$back.'">'.$LANG['no'].'</a>]';

if ($all == 'unapproved')
echo ''.$LANG['delete_confirm'].'<br/>[<a href="manage_guestbook.php?iwb=delete&amp;all='.$all.'&amp;yes=&amp;redir='.$redir.'">'.$LANG['yes'].'</a>] [<a href="'.$back.'">'.$LANG['no'].'</a>]';

if ($all == 'spam')
echo ''.$LANG['delete_confirm'].'<br/>[<a href="manage_guestbook.php?iwb=delete&amp;all='.$all.'&amp;yes=&amp;redir='.$redir.'">'.$LANG['yes'].'</a>] [<a href="'.$back.'">'.$LANG['no'].'</a>]';

if ($comid)
echo ''.$LANG['delete_confirm'].'<br/>[<a href="manage_guestbook.php?iwb=delete&amp;comid='.$comid.'&amp;yes=&amp;redir='.$redir.'">'.$LANG['yes'].'</a>] [<a href="'.$back.'">'.$LANG['no'].'</a>]';

echo '</div></div>';
require_once('inc/foot.php');
break;



case 'approved':
case 'unapproved':
case 'spam':
$comid=htmlentities($_GET['comid']);
$in=htmlentities($_GET['iwb']);

if ($in == 'approved')
$status='1';
if ($in == 'unapproved')
$status='0';
if ($in == 'spam')
$status='2';


if (isset($_GET['comid']))
{
if (!$is_author)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
$redir=$_GET['redir'];
$redir=str_replace('&amp;', '&', htmlentities(base64_decode($redir)));

mysql_query("update guestbook set status='".$status."' where id='".mysql_real_escape_string($comid)."' and site_id='".$user_id."'");
header('location: '.$redir.'');
}


if (!$user_id)
relogin();
if (!$is_author)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
$page=htmlentities($_GET['page']);
$count=mysql_result(mysql_query("select count(*) as num from guestbook where site_id='".$user_id."' and status='".$status."'"), 0);
if (!ctype_digit($page) || empty($page) || $page == 0 || $page > (ceil($count / $site['num_post_main'])))
$page='1';
$page--;
$max_view=$site['num_post_main'];
$limit=$page*$max_view;
$page++;

if ($in == 'unapproved')
$head_title=$LANG['unapproved'];
if ($in == 'approved')
$head_title=$LANG['approved'];
if ($in == 'spam')
$head_title=$LANG['spam'];

require_once('inc/head.php');
echo '<div id="message">
</div>
<div id="content">
<div id="main-content">';
echo '<div id="show_bar"><a href="manage_guestbook.php">'.$LANG['all'].'</a> | ';
if ($in == 'approved')
echo $LANG['approved'];
else
echo '<a href="manage_guestbook.php?iwb=approved">'.$LANG['approved'].'</a>';
echo ' |
';
if ($in == 'unapproved')
echo $LANG['unapproved'];
else
echo '<a href="manage_guestbook.php?iwb=unapproved">'.$LANG['unapproved'].'</a>';
echo ' | ';
if ($in == 'spam')
echo $LANG['spam'];
else
echo '<a href="manage_guestbook.php?iwb=spam">'.$LANG['spam'].'</a>';
echo '</div>';
echo '<ol>';
$req=mysql_query("select * from guestbook where site_id='".$user_id."' and status='".$status."' order by time desc limit $limit,$max_view");
if ($count > 0)
{
while ($com=mysql_fetch_array($req))
{
echo $i % 2 ? '<li class="row1">' : '<li class="row0">';
echo '<a href="'.$com['site'].'/" accesskey="1">'.htmlspecialchars($com['name']).'</a><br/>['.waktu($com['time']).']<br/>'.bbsm($com['text']).'<br/><span class="action_links">';
if ($com['status'] == 1)
echo '[<a class="reply" href="manage_guestbook.php?iwb=reply&amp;comid='.$com['id'].'">'.$LANG['reply'].'</a>] ';
if ($com['status'] == 0)
echo '[<font color="black">'.$LANG['unapproved'].'</font>] ';
else
echo '[<a href="manage_guestbook.php?iwb=unapproved&amp;comid='.$com['id'].'&amp;redir='.$rd.'">'.$LANG['unapproved'].'</a>] ';

if ($com['status'] == 1)
echo '[<font color="black">'.$LANG['approved'].'</font>] ';
else
echo '[<a href="manage_guestbook.php?iwb=approved&amp;comid='.$com['id'].'&amp;redir='.$rd.'">'.$LANG['approved'].'</a>] ';

if ($com['status'] == 2)
echo '[<font color="black">'.$LANG['spam'].'</font>] ';
else
echo '[<a href="manage_guestbook.php?iwb=spam&amp;comid='.$com['id'].'&amp;redir='.$rd.'">'.$LANG['spam'].'</a>] ';

echo '[<a class="delete" href="manage_guestbook.php?iwb=delete&amp;comid='.$com['id'].'&amp;redir='.$rd.'"><font color="red">'.$LANG['delete'].'</font></a>]</span>';
++$i;
echo '</li>';
}
echo '<p><form method="get" action="manage_guestbook.php"><input type="hidden" name="iwb" value="delete"/><input type="hidden" name="all" value="'.$in.'"/><input type="hidden" name="redir" value="'.$rd.'"/><input class="iwb-button" type="submit" value="'.$LANG['delete_all'].'"/></form></p>';
}
else
{
echo '<p>'.$LANG['empty'].'</p>';
}
echo '</ol></div>';
$total=$count;
if ($in == 'approved')
$link='manage_guestbook.php?iwb=approved&amp;page=';
elseif ($in == 'unapproved')
$link='manage_guestbook.php?iwb=unapproved&amp;page=';
else
$link='manage_guestbook.php?iwb=spam&amp;page=';
$q='';
pagination($page,$max_view,$total,$link,$q);
echo '</div>';
require_once('inc/foot.php');


break;
case 'reply':
$comid=htmlentities($_GET['comid']);
if (!ctype_digit($comid))
$comid = 1;
$reply=htmlentities($_POST['reply']);
if (!$user_id)
relogin();
$req=mysql_query("select * from guestbook where id='".mysql_real_escape_string($comid)."' and site_id='".$user_id."'");
if (mysql_num_rows($req) == 0)
{
require_once('inc/head.php');
page_not_found();
require_once('inc/foot.php');
exit;
}
$res=mysql_fetch_array($req);
if (!$is_author)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
if (isset($_POST['send']))
{
if (!$is_author)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
if (mb_strlen($reply) > 500)
$hsl='Pesan maksimal 500 karakter';
elseif (empty($reply))
$hsl=$LANG['empty_text'];
if (empty($hsl))
{
mysql_query("insert into guestbook set site_id='".$user_id."', user_id='".$user_id."', name='".mysql_real_escape_string($user_name)."', site='".$site['url']."/user.php?id=".$user_id."', text='".mysql_real_escape_string($reply)."', status='1', time='".time()."'");
header('location: manage_guestbook.php?reply_successfully');
}
}
$head_title=$LANG['reply'];
include('inc/head.php');
echo '<div id="message">
</div>
<div id="content">
<div id="main-content">';
echo '<div id="show_bar"><a href="manage_guestbook.php?">'.$LANG['all'].'</a> | <a href="manage_guestbook.php?iwb=approved">'.$LANG['approved'].'</a> |
<a href="manage_guestbook.php?iwb=unapproved">'.$LANG['unapproved'].'</a> | <a href="manage_guestbook.php?iwb=spam">'.$LANG['spam'].'</a></div>';

echo '<h4>'.$LANG['reply'].'</h4>
<p class="row0">';
if ($res['user_id'] != 0)
{
echo '<strong><a href="'.$site['url'].'/user.php?id='.$res['user_id'].'">'.htmlspecialchars($res['name']).'</a></strong>';
}
else
{
echo '<strong>'.htmlspecialchars($res['name']).'</strong>';
}
echo '<br/>['.waktu($res['time']).']<br/>'.bbsm($res['text']).'</p>
<form action="manage_guestbook.php?iwb=reply&amp;comid='.$res['id'].'" method="post"><h4>'.$LANG['message'].'</h4>
<textarea class="iwb-textarea" name="reply" rows="5" cols="30">@'.htmlentities($res['name']).',
</textarea><br/>
<input class="iwb-button" name="send" type="submit" value="'.$LANG['send'].'"/>';
echo '</div></div>';
include("inc/foot.php");
break;

default:
$page=htmlentities($_GET['page']);
$count=mysql_result(mysql_query("select count(*) from guestbook where site_id='".$user_id."'"), 0);
if (!ctype_digit($page) || empty($page) || $page == 0 || $page > (ceil($count / $site['num_post_main'])))
$page='1';
$page--;
$max_view=$site['num_post_main'];
$limit=$page*$max_view;
$page++;
if (!$user_id)
relogin();
if (!$is_author)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
$req=mysql_query("select * from guestbook where site_id='".$user_id."' order by time desc limit $limit,$max_view");
$head_title=$LANG['manage_guestbook'];
require_once('inc/head.php');
echo '<div id="message">
</div>
<div id="content">
<div id="main-content">';
echo '<div id="show_bar">';
echo $LANG['all'];
echo ' | <a href="manage_guestbook.php?iwb=approved">'.$LANG['approved'].'</a> |
<a href="manage_guestbook.php?iwb=unapproved">'.$LANG['unapproved'].'</a> | <a href="manage_guestbook.php?iwb=spam">'.$LANG['spam'].'</a></div>';
echo '<ol>';
if ($count > 0)
{
while ($com=mysql_fetch_array($req))
{
echo $i % 2 ? '<li class="row1">' : '<li class="row0">';
echo '<a href="'.htmlentities($com['site']).'/">'.htmlspecialchars($com['name']).'</a><br/>['.waktu($com['time']).']<br/>'.bbsm($com['text']).'<br/><span class="action_links">';
if ($com['status'] == 1)
echo '[<a class="reply" href="manage_guestbook.php?iwb=reply&amp;comid='.$com['id'].'">'.$LANG['reply'].'</a>] ';
if ($com['status'] == 0)
echo '[<font color="black">'.$LANG['unapproved'].'</font>] ';
else
echo '[<a href="manage_guestbook.php?iwb=unapproved&amp;comid='.$com['id'].'&amp;redir='.$rd.'">'.$LANG['unapproved'].'</a>] ';

if ($com['status'] == 1)
echo '[<font color="black">'.$LANG['approved'].'</font>] ';
else
echo '[<a href="manage_guestbook.php?iwb=approved&amp;comid='.$com['id'].'&amp;redir='.$rd.'">'.$LANG['approved'].'</a>] ';

if ($com['status'] == 2)
echo '[<font color="black">'.$LANG['spam'].'</font>] ';
else
echo '[<a href="manage_guestbook.php?iwb=spam&amp;comid='.$com['id'].'&amp;redir='.$rd.'">'.$LANG['spam'].'</a>] ';
echo '[<a class="delete" href="manage_guestbook.php?iwb=delete&amp;comid='.$com['id'].'&amp;redir='.$rd.'"><font color="red">'.$LANG['delete'].'</font></a>]</span>';
++$i;
echo '</li>';
}
}
else
{
echo '<li>'.$LANG['guestbook'].' '.$LANG['empty'].'</li>';
}
echo '</ol></div>';
$total=$count;
$link='manage_guestbook.php?page=';
$q='';
pagination($page,$max_view,$total,$link,$q);
echo '</div>';
require_once('inc/foot.php');
}
?>