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

$iwb=isset($_GET['iwb']) ? trim($_GET['iwb']) : '';
switch ($iwb)
{
case 'delete':
$comid=htmlentities($_GET['comid']);
$all=htmlentities($_GET['all']);
$redir=$_GET['redir'];
$back=str_replace('&amp;', '&', htmlentities(base64_decode($redir)));
if (!$user_id)
relogin();
$req=mysql_query("select * from comment where id='".mysql_real_escape_string($comid)."' and site_id='".$user_id."'");
if (mysql_num_rows($req) == 0)
{
require_once('inc/head.php');
page_not_found();
require_once('inc/foot.php');
exit;
}
if (!$is_author)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
$del=mysql_fetch_array($req);
if (isset($_GET['yes']))
{
if ($all == 'approved')
$sts='1';
if ($all == 'unapproved')
$sts='0';
if ($all == 'spam')
$sts='2';

if ($all)
mysql_query("delete from comment where site_id='".$user_id."' and status='".$sts."'")
;
if ($comid)
{
mysql_query("delete from comment where id='".$del['id']."' and site_id='".$user_id."'");
}
header('location: '.$back.'');
}
$head_title=''.$LANG['delete'].' '.$LANG['comments'].'';
require_once('inc/head.php');
echo '<div id="message"><ol id="notice"><li>';

if ($all == 'approved')
echo ''.$LANG['delete_confirm'].'<br/>[<a href="manage_comment.php?iwb=delete&amp;all='.$all.'&amp;yes=&amp;redir='.$redir.'">'.$LANG['yes'].'</a>] [<a href="'.$back.'">'.$LANG['no'].'</a>]';

if ($all == 'unapproved')
echo ''.$LANG['delete_confirm'].'<br/>[<a href="manage_comment.php?iwb=delete&amp;all='.$all.'&amp;yes=&amp;redir='.$redir.'">'.$LANG['yes'].'</a>] [<a href="'.$back.'">'.$LANG['no'].'</a>]';

if ($all == 'spam')
echo ''.$LANG['delete_confirm'].'<br/>[<a href="manage_comment.php?iwb=delete&amp;all='.$all.'&amp;yes=&amp;redir='.$redir.'">'.$LANG['yes'].'</a>] [<a href="'.$back.'">'.$LANG['no'].'</a>]';

if ($comid)
echo ''.$LANG['delete_confirm'].'<br/>[<a href="manage_comment.php?iwb=delete&amp;comid='.$comid.'&amp;yes=&amp;redir='.$redir.'">'.$LANG['yes'].'</a>] [<a href="'.$back.'">'.$LANG['no'].'</a>]';

echo '</li></ol></div>';
require_once('inc/foot.php');
break;

case 'approved':
case 'unapproved':
case 'spam':
if (!$user_id)
relogin();
if (!$is_author)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
$comid=$_GET['comid'];
$in=$_GET['iwb'];
$page=$_GET['page'];

if ($in == 'approved')
$status='1';
if ($in == 'unapproved')
$status='0';
if ($in == 'spam')
$status='2';

$total=mysql_result(mysql_query("select count(*) from comment where site_id='".$user_id."' and status='".$status."'"), 0);
if (!ctype_digit($page) || empty($page) || $page == 0 || $page > (ceil($total / $site['num_post_main'])))
$page='1';
$page--;
$max_view=$site['num_post_main'];
$limit=$page*$max_view;
$page++;
if (isset($_GET['comid']))
{
$redir=str_replace('&amp;', '&', htmlentities(base64_decode($_GET['redir'])));
$Com=mysql_fetch_array(mysql_query("select * from comment where id='".mysql_real_escape_string($comid)."' and site_id='".$user_id."'"));
if ($Com['site_id'] != $user_id)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
else
{
mysql_query("update comment set status='".$status."' where id='".$Com['id']."' and site_id='".$user_id."'");
header('location: '.$redir.'');
exit;
}
}


if ($in == 'unapproved')
$head_title=$LANG['unapproved'];
if ($in == 'approved')
$head_title=$LANG['approved'];
if ($in == 'spam')
$head_title=$LANG['spam'];

require_once('inc/head.php');
echo '<div id="message"></div><div id="content"><div id="main-content">';
echo '<div id="show_bar"><a href="manage_comment.php">'.$LANG['all'].'</a> | ';
if ($in == 'approved')
echo $LANG['approved'];
else
echo '<a href="manage_comment.php?iwb=approved">'.$LANG['approved'].'</a>';
echo ' |
';
if ($in == 'unapproved')
echo $LANG['unapproved'];
else
echo '<a href="manage_comment.php?iwb=unapproved">'.$LANG['unapproved'].'</a>';
echo ' | ';
if ($in == 'spam')
echo $LANG['spam'];
else
echo '<a href="manage_comment.php?iwb=spam">'.$LANG['spam'].'</a>';
echo '</div>';
echo '<ol>';

$req=mysql_query("select * from comment where site_id='".$user_id."' and status='".$status."' order by time desc limit $limit,$max_view");
if ($total > 0)
{
$rd = base64_encode(htmlspecialchars($_SERVER['REQUEST_URI']));

while ($com=mysql_fetch_array($req))
{
$blog=mysql_fetch_array(mysql_query("select title, link from blog where id='".$com['blog_id']."'"));
echo $i % 2 ? '<li class="row1">' : '<li class="row0">';
if ($com['user_id'] != 0)
echo '<a href="'.$site['url'].'/user.php?id='.$com['user_id'].'">'.htmlspecialchars($com['name']).'</a>';
else
echo htmlspecialchars($com['name']);
echo '<br />'.$LANG['on'].' <a href="'.$user_site.'/'.$blog['link'].'.xhtml#comments" accesskey="1">'.htmlspecialchars($blog['title']).'</a><br/>['.waktu($com['time']).']<br/>'.bbsm($com['text']).'<br/><span class="action_links">';
if ($com['status'] == 1)
echo '[<a class="reply" href="manage_comment.php?iwb=reply&amp;comid='.$com['id'].'">'.$LANG['reply'].'</a>] ';

if ($com['status'] == 0)
echo '[<font color="black">'.$LANG['unapproved'].'</font>] ';
else
echo '[<a href="manage_comment.php?iwb=unapproved&amp;comid='.$com['id'].'&amp;redir='.$rd.'">'.$LANG['unapproved'].'</a>] ';

if ($com['status'] == 1)
echo '[<font color="black">'.$LANG['approved'].'</font>] ';
else
echo '[<a href="manage_comment.php?iwb=approved&amp;comid='.$com['id'].'&amp;redir='.$rd.'">'.$LANG['approved'].'</a>] ';

if ($com['status'] == 2)
echo '[<font color="black">'.$LANG['spam'].'</font>] ';
else
echo '[<a href="manage_comment.php?iwb=spam&amp;comid='.$com['id'].'&amp;redir='.$rd.'">'.$LANG['spam'].'</a>] ';

echo '[<a class="delete" href="manage_comment.php?iwb=delete&amp;comid='.$com['id'].'&amp;redir='.$rd.'&amp;yes=ok"><font color="red">'.$LANG['delete'].'</font></a>]</span>';
++$i;
echo '</li>';
}
echo '<p><form method="get" action="manage_comment.php"><input type="hidden" name="iwb" value="delete"/><input type="hidden" name="all" value="'.htmlspecialchars($in).'"/><input type="hidden" name="redir" value="'.$rd.'"/><input class="iwb-button" type="submit" value="'.$LANG['delete_all'].'"/></a></p>';
}
else
{
echo '<li>'.$LANG['empty'].'</li>';
}
echo '</ol></div>';
$link='manage_comment.php?iwb='.htmlspecialchars($in).'&amp;page=';
$q='';
pagination($page,$max_view,$total,$link,$q);
echo '</div>';
require_once('inc/foot.php');
break;

case 'reply':
$comid=$_GET['comid'];
$reply=$_POST['reply'];
if (!$user_id)
relogin();
$req=mysql_query("select * from comment where id='".mysql_real_escape_string($comid)."' and site_id='".$user_id."'");
if (mysql_num_rows($req) == 0)
{
require_once('inc/head.php');
page_not_found();
require_once('inc/foot.php');
exit;
}
else
{
if (!$is_author)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
$res=mysql_fetch_array($req);
if (isset($_POST['send']))
{
if (mb_strlen($reply) > 500)
$hsl='Pesan maksimal 500 karakter';
elseif (empty($reply))
$hsl='Silakan tulis komentar Anda';
if (empty($hsl))
{
mysql_query("insert into comment set site_id='".$user_id."', user_id='".$user_id."', blog_id='".$res['blog_id']."', name='".mysql_real_escape_string($user_name)."', text='".mysql_real_escape_string($reply)."', site='".mysql_real_escape_string($user_site)."', status='1', time='".time()."'");
header('location: manage_comment.php?reply_successfully');
}
}
$head_title=''.$LANG['reply'].' '.$LANG['comments'].'';
require_once('inc/head.php');
echo '<div id="message">
</div>
<div id="content">
<div id="main-content">';
echo '<div id="show_bar"><a href="manage_comment.php?">'.$LANG['all'].'</a> | <a href="manage_comment.php?iwb=approved">'.$LANG['approved'].'</a> |
<a href="manage_comment.php?iwb=unapproved">'.$LANG['unapproved'].'</a> | <a href="manage_comment.php?iwb=spam">'.$LANG['spam'].'</a></div>';

echo '<h4>'.$LANG['reply'].' '.$LANG['comments'].'</h4>
<p class="row0">
<strong>';
if ($res['user_id'] != 0)
echo '<a href="user.php?id='.$res['id'].'">'.htmlspecialchars($res['name']).'</a>';
else
echo htmlspecialchars($res['name']);
echo '</strong><br/>['.waktu($res['time']).']<br/>'.bbsm($res['text']).'</p>
<form action="manage_comment.php?iwb=reply&amp;comid='.$res['id'].'" method="post"><h4>'.$LANG['reply'].'</h4>';
$replay_to=$res['name'];
echo '<textarea class="iwb-textarea" name="reply" rows="5" cols="26">@'.htmlentities($replay_to).',
</textarea><br/>
<input class="iwb-button" name="send" type="submit" value="'.$LANG['send'].'"/>';
echo '</div></div>';
require_once('inc/foot.php');
}
break;

default:
if (!$user_id)
relogin();
if (!$is_author)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}

$page=$_GET['page'];
$bid=$_GET['bid'];

if (isset($_GET['bid']))
{
$total=mysql_result(mysql_query("select count(*) from comment where site_id='".mysql_real_escape_string($user_id)."' and blog_id='".mysql_real_escape_string($bid)."'"), 0);
}
else
{
$total=mysql_result(mysql_query("select count(*) from comment where site_id='".$user_id."'"), 0);
}
if (!ctype_digit($page) || empty($page) || $page == 0 || $page > (ceil($total / $site['num_post_main'])))
$page='1';
$page--;
$max_view=$site['num_post_main'];
$limit=$page*$max_view;
$page++;

if (isset($_GET['bid']))
{
$req=mysql_query("select * from comment where site_id='".$user_id."' and blog_id='".mysql_real_escape_string($bid)."' order by time desc limit $limit,$max_view");
}
else
{
$req=mysql_query("select * from comment where site_id='".$user_id."' order by time desc limit $limit,$max_view");
}

$head_title=$LANG['manage_comments'];
require_once('inc/head.php');
echo '<div id="message"></div><div id="content"><div id="main-content">';
echo '<div id="show_bar">';
if (isset($_GET['bid']))
echo '<a href="manage_comment.php">'.$LANG['all'].'</a>';
else
echo $LANG['all'];
echo ' | <a href="manage_comment.php?iwb=approved">'.$LANG['approved'].'</a> |
<a href="manage_comment.php?iwb=unapproved">'.$LANG['unapproved'].'</a> | <a href="manage_comment.php?iwb=spam">'.$LANG['spam'].'</a></div>';
echo '<ol>';
if ($total > 0)
{
$rd = base64_encode(htmlspecialchars($_SERVER['REQUEST_URI']));

while ($com=mysql_fetch_array($req))
{
$blog=mysql_fetch_array(mysql_query("select title, link from blog where id='".$com['blog_id']."'"));

echo $i % 2 ? '<li class="row1">' : '<li class="row0">';
if ($com['user_id'] != 0)
echo '<a href="'.$site['url'].'/user.php?id='.$com['user_id'].'">'.htmlspecialchars($com['name']).'</a>';
else
echo htmlspecialchars($com['name']);
echo '<br />'.$LANG['on'].' <a href="'.$user_site.'/'.$blog['link'].'.xhtml#comments" accesskey="1">'.htmlspecialchars($blog['title']).'</a><br/>['.waktu($com['time']).']<br/>'.bbsm($com['text']).'<br/><span class="action_links">';
if ($com['status'] == 1)
echo '[<a class="reply" href="manage_comment.php?iwb=reply&amp;comid='.$com['id'].'">'.$LANG['reply'].'</a>] ';
if ($com['status'] == 0)
echo '[<font color="black">'.$LANG['unapproved'].'</font>] ';
else
echo '[<a href="manage_comment.php?iwb=unapproved&amp;comid='.$com['id'].'&amp;redir='.$rd.'">'.$LANG['unapproved'].'</a>] ';

if ($com['status'] == 1)
echo '[<font color="black">'.$LANG['approved'].'</font>] ';
else
echo '[<a href="manage_comment.php?iwb=approved&amp;comid='.$com['id'].'&amp;redir='.$rd.'">'.$LANG['approved'].'</a>] ';

if ($com['status'] == 2)
echo '[<font color="black">'.$LANG['spam'].'</font>] ';
else
echo '[<a href="manage_comment.php?iwb=spam&amp;comid='.$com['id'].'&amp;redir='.$rd.'">'.$LANG['spam'].'</a>] ';
echo '[<a class="delete" href="manage_comment.php?iwb=delete&amp;comid='.$com['id'].'&amp;redir='.$rd.'&amp;yes=ok"><font color="red">'.$LANG['delete'].'</font></a>]</span>';
++$i;
echo '</li>';
}
}
else
{
echo '<li>'.$LANG['empty'].'</li>';
}
echo '</ol></div>';
if (isset($_GET['bid']))
$link='manage_comment.php?bid='.htmlentities($bid).'&amp;page=';
else
$link='manage_comment.php?page=';
$q='';
pagination($page,$max_view,$total,$link,$q);
echo '</div>';
require_once('inc/foot.php');
}
?>