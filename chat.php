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
$live_chat='off';
$iwb=isset($_GET['iwb']) ? trim($_GET['iwb']) : '';
switch ($iwb)
{
case 'read':
if (!$user_id)
relogin();
$cid=angka($_GET['cid']);
$q=mysql_query("select * from chat where id='".mysql_real_escape_string($cid)."'");
if (mysql_num_rows($q) == 0)
{
require_once('inc/head.php');
page_not_found();
require_once('inc/foot.php');
exit;
}
else
{
$r=mysql_fetch_array($q);
if (isset($_GET['delcom']))
{
$del=htmlentities($_GET['delcom']);
if ($is_admin)
{
$me=mysql_query("select * from chat_comment where id='".mysql_real_escape_string($del)."'");
}
elseif ($r['user_id'] == $user_id)
{
$me=mysql_query("select * from chat_comment where id='".mysql_real_escape_string($del)."' and chat_id='".$r['id']."'");
}
else
{
$me=mysql_query("select * from chat_comment where id='".mysql_real_escape_string($del)."' and chat_id='".$r['id']."' and user_id='".$user_id."'");
}
if (mysql_num_rows($me) != 0)
mysql_query("delete from chat_comment where id='".mysql_real_escape_string($del)."'");
}
elseif (isset($_GET['unlike']))
{
$me=mysql_query("select id from chat_like where chat_id='".$r['id']."' and user_id='".$user_id."'");
if (mysql_num_rows($me) != 0)
mysql_query("delete from chat_like where chat_id='".$r['id']."' and user_id='".$user_id."'");
}
elseif (isset($_GET['like']))
{
$me=mysql_query("select id from chat_like where chat_id='".$r['id']."' and user_id='".$user_id."'");
if (mysql_num_rows($me) == 0)
mysql_query("insert into chat_like set chat_id='".$r['id']."', user_id='".$user_id."', time='".time()."'");
}
elseif (isset($_POST['comment_chat']))
{
$text=$_POST['comment'];
$_flood = ($is_admin) ? 30 : 80;
$flooding = time() - $_flood;
$isflood=mysql_query("select id from chat_comment where user_id='".$user_id."' and time > $flooding");
if (mysql_num_rows($isflood) != 0)
{
$erc=$LANG['flooding'];
}
elseif (mb_strlen($text) > 500)
{
$erc=str_replace('::number::','500',$LANG['text_max']);
}
elseif (empty($text))
{
$erc=$LANG['empty_text'];
}
else
{
mysql_query("insert into chat_comment set chat_id='".$r['id']."', user_id='".$user_id."', text='".mysql_real_escape_string($text)."', time='".time()."'");
if ($r['user_id'] != $user_id)
{
$tm = time();
$token = $tm - $r['user_id'];
$msg=str_replace('::name::',htmlspecialchars($user_name),str_replace('::chat::','<a href="'.$site['url'].'/chat.php?iwb=read&amp;cid='.$r['id'].'&amp;view_comment">chat</a>',$LANG['chat_notification']));
mysql_query("insert into `pm` set `receiver_id`='".mysql_real_escape_string($r['user_id'])."', `sender_id`='2', `name`='NOTIFIKASI', `text`='".mysql_real_escape_string($msg)."', `read`='2', `time`='".$tm."'") or die(mysql_error());
}
else
{}
$kirim=mysql_query("select `user_id` from `chat_comment` where `chat_id`='".$r['id']."' and `user_id`!='".$user_id."' and `user_id`!='".$r['user_id']."' order by `user_id` desc");
if (mysql_num_rows($kirim) != 0)
{
$tm = time();
while ($kirimke=mysql_fetch_array($kirim))
{
if ($kirimke['user_id'] != $uid)
{
$token = $tm - $kirimke['user_id'];
$msg=str_replace('::name::',htmlspecialchars($user_name),str_replace('::chat::','<a href="'.$site['url'].'/chat.php?iwb=read&amp;cid='.$r['id'].'&amp;view_comment">chat</a>',str_replace('::name2::',iwbid($r['user_id']),$LANG['chat_notification2'])));
mysql_query("insert into `pm` set `receiver_id`='".mysql_real_escape_string($kirimke['user_id'])."', `sender_id`='2', `name`='NOTIFIKASI', `text`='".mysql_real_escape_string($msg)."', `read`='2', `time`='".$tm."'") or die(mysql_error());
}
else
{
$uid .= $kirimke['user_id'];
}
}
}
else
{}
}
}
else
{}
if (isset($_GET['view_like']))
$head_title=$LANG['likes'];
else
$head_title=$LANG['comments'];
require_once('inc/head.php');
echo '<div id="message">';
echo '</div>';
echo '<div id="content"><div id="main-content">';
echo '<div id="show_bar"><a href="chat.php?">'.$LANG['chatroom'].'</a> | '.$head_title.'</div>';
echo '<div class="row1"><img src="'.$site['url'].'/img.php?img='.$r['user_id'].'.jpg&amp;w=40&amp;h=40" alt=""/> <a href="'.$site['url'].'/user.php?id='.$r['user_id'].'">';
$usr=mysql_fetch_array(mysql_query("select name, author, admin from user where id='".$r['user_id']."'"));
if (($usr['author'] == '1') && ($usr['admin'] == '0'))
{
echo '<font color="green">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '2') && ($usr['admin'] == '0'))
{
echo '<font color="red">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '3') && ($usr['admin'] == '0'))
{
echo '<font color="blue">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '4') && ($usr['admin'] == '0'))
{
echo '<font color="orange">'.htmlspecialchars($usr['name']).'</font>';
}
elseif ($usr['admin'] == '1')
{
echo '<font color="#731174">'.htmlspecialchars($usr['name']).'</font>';
}
else
{
echo '<font color="black">'.htmlspecialchars($usr['name']).'</font>';
}
echo '</a>: '.bbsm($r['text']).'';
if ($r['user_id'] == $user_id || $is_admin)
echo ' <a href="chat.php?iwb=delete&amp;res='.$r['id'].'">'.$LANG['delete'].'</a>';
echo '<br/><span color="#666666">'.time_ago($r['time']).'</span>';
$like=mysql_query("select id from chat_like where chat_id='".$r['id']."'");
$totallike=mysql_num_rows($like);
$cm = mysql_query("select id from chat_comment where chat_id='".$r['id']."'");
$totalcom=mysql_num_rows($cm);
$itsme=mysql_query("select id from chat_like where chat_id='".$r['id']."' and user_id='".$user_id."'");
echo '<br/>';
if (mysql_num_rows($itsme) != 0)
{
$lk = $totallike - 1;
echo str_replace('::number::',$lk,str_replace('::unlike::','<a href="chat.php?iwb=read&amp;cid='.$r['id'].'&amp;unlike='.$r['id'].'">'.$LANG['unlike'].'</a>',$LANG['likely']));
}
else
{
echo str_replace('::number::',$totallike,str_replace('::likes::','<a href="chat.php?iwb=read&amp;cid='.$r['id'].'&amp;like='.$r['id'].'">'.$LANG['likes'].'</a>',$LANG['likely2']));
}
echo '</div>';
echo '<hr></hr><div id="show_bar"><a href="chat.php?iwb=read&amp;cid='.$r['id'].'&amp;view_like='.$r['id'].'">'.$totallike.'</a> '.$LANG['likes'].' | <a href="chat.php?iwb=read&amp;cid='.$r['id'].'">'.$totalcom.'</a> '.$LANG['comments'].'</div>';
echo '<ol>';
if (isset($_GET['view_like']))
{
if ($totallike == 0)
{
echo '<li class="row0">'.$LANG['empty'].'</li>';
}
else
{
$page=htmlentities($_GET['page']);
$total = $totallike;
$gp = ceil($total / $site['num_post_main']);
if (empty($page) || !ctype_digit($page) || $page > $gp)
$page=$gp;
if ($page=='0')
$page='1';
$page--;
$max_view=$site['num_post_main'];
$limit=$page*$max_view;
$page++;
$lik=mysql_query("select user_id, time from chat_like where chat_id='".$r['id']."' order by time asc limit $limit,$max_view");
while ($res=mysql_fetch_array($lik))
{
echo $i % 2 ? '<li class="row0">' : '<li class="row1">';
$usr=mysql_fetch_array(mysql_query("select name, author, admin from user where id='".$res['user_id']."'"));
echo '<img src="'.$site['url'].'/img.php?img='.$res['user_id'].'.jpg&amp;w=40&amp;h=40" alt=""/> <a href="user.php?id='.$res['user_id'].'">';
if (($usr['author'] == '1') && ($usr['admin'] == '0'))
{
echo '<font color="green">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '2') && ($usr['admin'] == '0'))
{
echo '<font color="red">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '3') && ($usr['admin'] == '0'))
{
echo '<font color="blue">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '4') && ($usr['admin'] == '0'))
{
echo '<font color="orange">'.htmlspecialchars($usr['name']).'</font>';
}
elseif ($usr['admin'] == '1')
{
echo '<font color="#731174">'.htmlspecialchars($usr['name']).'</font>';
}
else
{
echo '<font color="black">'.htmlspecialchars($usr['name']).'</font>';
}
echo '</a><br/><span color="#666666">'.time_ago($res['time']).'</span>';
++$i;
echo '</li>';
}
$link='chat.php?iwb=read&amp;cid='.$r['id'].'&amp;view_like='.$r['id'].'&amp;page=';
$q='';
pagination($page,$max_view,$total,$link,$q);
}
}
else
{
if ($totalcom == 0)
{
echo '<li class="row0">'.$LANG['empty'].'</li>';
}
else
{
$page=htmlentities($_GET['page']);
$total = $totalcom;
$gp = ceil($total / $site['num_post_main']);
if (empty($page) || !ctype_digit($page) || $page > $gp)
$page=$gp;
if ($page=='0')
$page='1';
$page--;
$max_view=$site['num_post_main'];
$limit=$page*$max_view;
$page++;
$com=mysql_query("select * from chat_comment where chat_id='".$r['id']."' order by time asc limit $limit,$max_view");
while ($res=mysql_fetch_array($com))
{
echo $i % 2 ? '<li class="row0">' : '<li class="row1">';
$usr=mysql_fetch_array(mysql_query("select name, author, admin from user where id='".$res['user_id']."'"));
echo '<img src="'.$site['url'].'/img.php?img='.$res['user_id'].'.jpg&amp;w=40&amp;h=40" alt=""/> <a href="user.php?id='.$res['user_id'].'">';
if (($usr['author'] == '1') && ($usr['admin'] == '0'))
{
echo '<font color="green">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '2') && ($usr['admin'] == '0'))
{
echo '<font color="red">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '3') && ($usr['admin'] == '0'))
{
echo '<font color="blue">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '4') && ($usr['admin'] == '0'))
{
echo '<font color="orange">'.htmlspecialchars($usr['name']).'</font>';
}
elseif ($usr['admin'] == '1')
{
echo '<font color="#731174">'.htmlspecialchars($usr['name']).'</font>';
}
else
{
echo '<font color="black">'.htmlspecialchars($usr['name']).'</font>';
}
echo '</a>: '.bbsm($res['text']).'<br/><span color="#666666">'.time_ago($res['time']).'</span>';
if ($r['user_id'] == $user_id || $res['user_id'] == $user_id || $is_admin)
echo ' <a href="'.$site['url'].'/chat.php?iwb=read&amp;cid='.$r['id'].'&amp;delcom='.$res['id'].'">'.$LANG['delete'].'</a>';
++$i;
echo '</li>';
}
$link='chat.php?iwb=read&amp;cid='.$r['id'].'&amp;page=';
$q='';
pagination($page,$max_view,$total,$link,$q);
}
if ($erc)
echo '<li><font color="red">'.$erc.'</font></li>';
echo '<hr></hr><form action="'.$site['url'].'/chat.php?iwb=read&amp;cid='.$r['id'].'" method="post"><table align="center" cellspacing="0 cellpadding="0"><tbody><tr><td><textarea name="msg" rows="2" cols="13"></textarea></td><td><input name="chat" type="submit" value="'.$LANG['save'].'"/><br/><a href="bbsm.php">Smiley</a></td></tr></tbody></table></form>';
}
echo '</ol>';
echo '</div></div>';
require_once('inc/foot.php');
}
break;
case 'delete':
$res=htmlentities($_GET['res']);
if (!$user_id)
relogin();

$ol = time() - 300;

$user_ol=mysql_result(mysql_query("SELECT COUNT(*) FROM user WHERE lastdate > $ol"), 0);
$res=$_GET['res'];
if ($res != 'all')
{
$cek=mysql_query("select * from chat where id='".mysql_real_escape_string($res)."'");
if (mysql_num_rows($cek) == 0)
{
require_once('inc/head.php');
page_not_found();
require_once('inc/foot.php');
exit;
}
$us=mysql_fetch_array($cek);
if ($us['user_id'] != $user_id && 
(!$is_admin))
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
}

if (isset($_POST['no']))
{
header('location: chat.php');
exit;
}
if (isset($_POST['yes']))
{
if ($res == 'all' && $is_admin)
{
mysql_query("TRUNCATE TABLE chat");
mysql_query("TRUNCATE TABLE chat_like");
mysql_query("TRUNCATE TABLE chat_comment");
}
if ($res != 'all')
{
if ($us['user_id'] == $user_id || $is_admin)
{
mysql_query("delete from chat where id='".$us['id']."'");
mysql_query("delete from chat_like where chat_id='".$us['id']."'");
mysql_query("delete from chat_comment where chat_id='".$us['id']."'");
}
}
header('location: chat.php');
exit;
}

$head_title=$LANG['delete'];
require_once('inc/head.php');
echo '<div id="message">';
echo '</div>
<div id="content">
<div id="main-content">';
echo '<div id="show_bar"><a href="chat.php?">'.$LANG['chatroom'].'</a> | <a href="chat.php?write">'.$LANG['write'].'</a> | <a href="online.php">Online</a> ('.$user_ol.') | '.$LANG['delete_all'].'</a>';
echo '</div>';
if ($res == 'all')
{
if (!$is_admin)
{
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
echo '<center>'.$LANG['delete_confirm'].'<br/><form method="post" action="chat.php?iwb=delete&amp;res=all"><div class="two-col-btn"><input class="iwb-buton" type="submit" name="yes" value="'.$LANG['yes'].'"/><input type="submit" name="no" value="'.$LANG['no'].'"/></div></form></center>';
}

if ($res != 'all')
echo '<center>'.$LANG['delete_confirm'].'<br/><form method="post" action="chat.php?iwb=delete&amp;res='.htmlentities($res).'"><div class="two-col-btn"><input type="submit" name="yes" value="'.$LANG['yes'].'"/><input class="iwb-buton" type="submit" name="no" value="'.$LANG['no'].'"/></div></form></center>';
echo '</div></div>';
require_once('inc/foot.php');
break;
default:
if (!$user_id)
relogin();

$page=htmlentities($_GET['page']);
$total=mysql_result(mysql_query("select count(*) as num from chat"), 0);
if (empty($page) || $page == 0 || !ctype_digit($page) || $page > (ceil($total / $site['num_post_main'])))
$page='1';
$page--;
$max_view=$site['num_post_main'];
$limit=$page*$max_view;
$page++;
if (isset($_POST['chat']))
{
if (!$user_id)
relogin();
$msg=$_POST['msg'];
if (mb_strlen($msg) > 500)
$err=str_replace('::number::','500',$LANG['text_max']);
if (empty($msg))
$err=$LANG['empty_text'];
if ($indowapblog['credit'] < 10)
$err=str_replace('::number::','10',str_replace('::more::','<a href="admin.php?iwb=credit">'.$LANG['more'].' &raquo;</a>',$LANG['minim_credit']));
if (empty($err))
{
$kredit = $indowapblog['credit'] - 10;
mysql_query("update user set credit='".$kredit."' where id='".$user_id."'");
mysql_query("insert into chat set user_id='".$user_id."', text='".mysql_real_escape_string($msg)."', time='".time()."'");
header('location: chat.php');
}
else
{
$notif='<ol id="error"><li>'.$err.'</li></ol>';
}
}

$ol = time() - 300;

$user_ol=mysql_result(mysql_query("SELECT COUNT(*) FROM user WHERE lastdate > $ol"), 0);

$head_title=$LANG['chatroom'];
require_once('inc/head.php');
echo '<div id="message">';
if (!empty($notif))
echo $notif;
echo '</div>
<div id="content">
<div id="main-content">';
echo '<div id="show_bar"><a href="chat.php?write">'.$LANG['write'].'</a> | <a href="bbsm.php?iwb=smiley">'.$LANG['smiley'].'</a> | <a href="bbsm.php?iwb=bbcode">'.$LANG['bbcode'].'</a> | <a href="chat.php?'.time().'">'.$LANG['refresh'].'</a> | <a href="online.php">Online</a> ('.$user_ol.')';
if ($is_admin)
echo ' | <a href="chat.php?iwb=delete&amp;res=all">'.$LANG['delete_all'].'</a>';
echo '</div>';
if (isset($_GET['write']))
{
echo '<form action="chat.php?write" method="post"><table align="center" cellspacing="0 cellpadding="0"><tbody><tr><td><textarea name="msg" rows="2" cols="13"></textarea></td><td><input name="chat" type="submit" value="'.$LANG['save'].'"/><br/><a href="bbsm.php">Smiley</a></td></tr></tbody></table></form>';
}
echo '<ol style="list-style: none; margin: 10px 0px 0px 0px; padding: 0px;">';

if ($total != 0)
{
$req=mysql_query("select * from chat order by time desc limit $limit,$max_view");
while ($res=mysql_fetch_array($req))
{
echo $i % 2 ? '<li class="row0">' : '<li class="row1">';
$usr=mysql_fetch_array(mysql_query("select name, author, admin from user where id='".$res['user_id']."'"));
echo '<img src="'.$site['url'].'/img.php?img='.$res['user_id'].'.jpg&amp;w=40&amp;h=40" alt=""/> <a href="user.php?id='.$res['user_id'].'">';
if (($usr['author'] == '1') && ($usr['admin'] == '0'))
{
echo '<font color="green">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '2') && ($usr['admin'] == '0'))
{
echo '<font color="red">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '3') && ($usr['admin'] == '0'))
{
echo '<font color="blue">'.htmlspecialchars($usr['name']).'</font>';
}

elseif (($usr['author'] == '4') && ($usr['admin'] == '0'))
{
echo '<font color="orange">'.htmlspecialchars($usr['name']).'</font>';
}
elseif ($usr['admin'] == '1')
{
echo '<font color="#731174">'.htmlspecialchars($usr['name']).'</font>';
}
else
{
echo '<font color="black">'.htmlspecialchars($usr['name']).'</font>';
}
echo '</a>: ';
if (mb_strlen($res['text']) > 150)
echo ''.bbsm(substr($res['text'],0,150)).' [<a href="'.$site['url'].'/chat.php?iwb=read&amp;cid='.$res['id'].'">...</a>]';
else
echo bbsm($res['text']);
if (($res['user_id'] == $user_id) || ($is_admin))
echo ' <a href="chat.php?iwb=delete&amp;res='.$res['id'].'">Hapus</a>';
echo '<br/><span color="#666666">'.time_ago($res['time']).'</span><br/>';
$like=mysql_query("select * from chat_like where chat_id='".$res['id']."'");
$totallike=mysql_num_rows($like);
$cm = mysql_query("select * from chat_comment where chat_id='".$res['id']."'");
$totalcom=mysql_num_rows($cm);
echo '<small><a href="'.$site['url'].'/chat.php?iwb=read&amp;cid='.$res['id'].'&amp;view_like='.$res['id'].'">'.$totallike.' Suka</a> | <a href="'.$site['url'].'/chat.php?iwb=read&amp;cid='.$res['id'].'">'.$totalcom.' Komentar</a></small>';
++$i;
echo '</li>';
}
}
else
{
echo '<li>'.$LANG['empty'].'</li>';
}
echo '</ol></div>';

$link='chat.php?page=';
$q='';
pagination($page,$max_view,$total,$link,$q);

echo '</div>';
require_once('inc/foot.php');
}
?>