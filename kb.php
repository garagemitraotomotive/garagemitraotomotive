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

require('inc/indowapblog.php');
$live_chat = "off";
$iwb = isset($_GET['iwb']) ? $_GET['iwb'] : '';

switch ($iwb)
{
case 'add':
if (!$is_admin) {
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
if (isset($_GET['save'])) {
$title = $_POST['title'];
$text = $_POST['text'];
$tag = $_POST['tag'];
if (empty($title) || empty($text)) {
$hasil = "<ol id='error'><li>".$LANG['incorrect_data']."</li></ol>";
}
else {
if (empty($tag)) {
}
else {
$tag = iwb_tags($tag);
}
mysql_query("INSERT INTO `kb` SET `title` = '".mysql_real_escape_string($title)."', `text` = '".mysql_real_escape_string($text)."', `tag` = '".mysql_real_escape_string($tag)."', `time` = '".time()."'");
$id = mysql_insert_id();
header("Location: kb.php?read=".$id);
exit;
}
}
$head_title = "".$LANG['add']." ".$LANG['knownledge_base'];
require_once('inc/head.php');
echo '<div id="message">';
if ($hasil)
echo $hasil;
echo '</div><div id="content"><div id="main-content"><form method="post" action="kb.php?iwb=add&amp;save=true"><h1>'.$LANG['title'].'</h1><input class="iwb-text" type="text" name="title" size="30" value=""/><br /><h1>'.$LANG['text'].'</h1><textarea class="iwb-textarea" name="text" cols="30" rows="15"/></textarea><br /><h1>Tag</h1><input class="iwb-text" type="text" name="tag" size="30" value=""/><br /><br /><input class="iwb-button" type="submit" value="'.$LANG['save'].'"/></form></div></div>';
require_once('inc/foot.php');
break;

case 'edit':
if (!$is_admin) {
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
$id = $_GET['id'];
$q = mysql_query("SELECT * FROM `kb` WHERE `id` = '".mysql_real_escape_string($id)."'");
if (mysql_num_rows($q) == 0) {
header("Location: kb.php");
exit;
}
else {
$kb = mysql_fetch_array($q);
if (isset($_GET['save'])) {
$title = $_POST['title'];
$text = $_POST['text'];
$tag = strtolower($_POST['tag']);
$update_time = $_POST['update_time'];
if ($update_time == "yes")
$time = time();
else
$time = $kb['time'];
if (empty($title) || empty($text)) {
$hasil = "<ol id='error'><li>".$LANG['incorrect_data']."</li></ol>";
}
else {
if (empty($tag)) {
}
else {
$tag = iwb_tags($tag);
}
mysql_query("UPDATE `kb` SET `title` = '".mysql_real_escape_string($title)."', `text` = '".mysql_real_escape_string($text)."', `tag` = '".mysql_real_escape_string($tag)."', `time` = '".$time."' WHERE `id` = '".$kb['id']."'");
header("Location: kb.php?read=".$kb['id']);
exit;
}
}
$head_title = "".$LANG['edit']." ".$LANG['knownledge_base'];
require_once('inc/head.php');
echo '<div id="message">';
if ($hasil)
echo $hasil;
echo '</div><div id="content"><div id="main-content"><form method="post" action="kb.php?iwb=edit&amp;id='.$kb['id'].'&amp;save=true"><h1>'.$LANG['title'].'</h1><input class="iwb-text" type="text" name="title" size="30" value="'.htmlentities($kb['title']).'"/><br /><h1>'.$LANG['text'].'</h1><textarea class="iwb-textarea" name="text" cols="30" rows="15"/>'.htmlentities($kb['text']).'</textarea><br /><h1>Tag</h1><input class="iwb-text" type="text" name="tag" size="30" value="'.htmlentities($kb['tag']).'"/><br /><h1>'.$LANG['update_time'].'</h1><input type="radio" name="update_time" value="no" checked>'.$LANG['no'].'<br /><input type="radio" name="update_time" value="yes">'.$LANG['yes'].'<br /><br /><input class="iwb-button" type="submit" value="'.$LANG['save'].'"/></form></div></div>';
require_once('inc/foot.php');
}
break;

case 'delete':
if (!$is_admin) {
require_once('inc/head.php');
forbidden();
require_once('inc/foot.php');
exit;
}
$id = $_GET['id'];
$q = mysql_query("SELECT * FROM `kb` WHERE `id` = '".mysql_real_escape_string($id)."'");
if (mysql_num_rows($q) == 0) {
header("Location: kb.php");
exit;
}
else {
$kb = mysql_fetch_array($q);
if (isset($_POST['yes'])) {
mysql_query("DELETE FROM `kb` WHERE `id` = '".mysql_real_escape_string($kb['id'])."'");
header("Location: kb.php");
exit;
}
if (isset($_POST['no'])) {
header("Location: kb.php");
exit;
}
$head_title = "".$LANG['delete']." ".$LANG['knownledge_base'];
require_once('inc/head.php');
echo '<div id="message"></div><div id="content"><div id="main-content"><div id="show_bar"><a href="kb.php?iwb=add">'.$LANG['add_new'].'</a></div><form method="post" action="kb.php?iwb=delete&amp;id='.$kb['id'].'"><center>'.$LANG['delete_confirm'].'</center><br /><div class="two-col-btn"><input class="iwb-button" type="submit" name="yes" value="'.$LANG['yes'].'"/><input class="iwb-button" type="submit" name="no" value="'.$LANG['no'].'"/></div></form></div></div>';
require_once('inc/foot.php');
}
break;

case 'tag':
case 'search':
$page = $_GET['page'];
if ($iwb == "tag") {
$name = htmlentities($_GET['name']);
$q = "SELECT `id`, `title` FROM `kb` WHERE `tag` LIKE '%".mysql_real_escape_string($name)."%'";
$head_title = "KB Tags: ".$name;
}
else {
$name = htmlentities($_GET['title']);
$q = "SELECT `id`, `title` FROM `kb` WHERE `title` LIKE '%".mysql_real_escape_string($name)."%'";
$head_title = "".$LANG['search_for'].": ".$name;
}
$total = mysql_num_rows(mysql_query($q));
if (!ctype_digit($page) || empty($page) || $page == 0 || $page > (ceil($total / $site['num_post_main'])))
$page = 1;
$page--;
$max_view = $site['num_post_main'];
$limit = $page * $max_view;
$page++;

require_once('inc/head.php');
echo '<div id="message"></div><div id="content"><div id="main-content"><div id="show_bar"><a href="kb.php">'.$LANG['knowledge_base'].'</a>';
if ($is_admin)
echo ' | <a href="kb.php?iwb=add">'.$LANG['add_new'].'</a>';
echo '</div>';
echo '<form method="get" action="kb.php"><input type="hidden" name="iwb" value="search"><div class="two-col-btn"><input class="iwb-text" name="title" value="'.$name.'"/><input class="iwb-button" type="submit" value="'.$LANG['search_submit'].'"/></div></form><br />';
if ($total == 0) {
echo '<p>' . $head_title . ' ' . $LANG['empty'] . '</p>';
}
else {
$sql = mysql_query("$q ORDER BY `title` ASC LIMIT $limit, $max_view");
while ($kb = mysql_fetch_array($sql))
{
echo $i % 2 ? '<div class="row0">' : '<div class="row1">';
echo '<a href="kb.php?read=' . $kb['id'] . '">' . htmlspecialchars($kb['title']) . '</a>';
++$i;
echo '</div>';
}
$link = "kb.php?iwb=".$iwb."&amp;name=".$name."&amp;page=";
$q = "";
pagination($page,$max_view,$total,$link,$q);
}
echo '</div></div>';
require_once('inc/foot.php');
break;

default:

if (isset($_GET['read']) && ctype_digit($_GET['read'])) {
$id = $_GET['read'];
$q = mysql_query("SELECT * FROM `kb` WHERE `id` = '" . mysql_real_escape_string($id) . "'");
if (mysql_num_rows($q) == 0) {
header("Location: kb.php");
exit;
}
else {
$kb = mysql_fetch_array($q);
$head_title = "KB: ".htmlspecialchars($kb['title']);
require_once('inc/head.php');
echo '<div id="message"></div><div id="content"><div id="main-content"><div id="show_bar"><a href="kb.php">'.$LANG['knowledge_base'].'</a>';
if ($is_admin)
echo ' | <a href="kb.php?iwb=add">'.$LANG['add_new'].'</a>';
echo '</div><form method="get" action="kb.php"><input type="hidden" name="iwb" value="search"><div class="two-col-btn"><input class="iwb-text" name="title" value=""/><input class="iwb-button" type="submit" value="'.$LANG['search_submit'].'"/></div></form><br /><h1>' . htmlspecialchars($kb['title']) . '</h1><p>' . iwb_html($kb['text']);
if ($is_admin)
echo '<br />[<a href="kb.php?iwb=edit&amp;id='.$kb['id'].'">'.$LANG['edit'].'</a>|<a href="kb.php?iwb=delete&amp;id='.$kb['id'].'">'.$LANG['delete'].'</a>]';
echo '</p>';
if (!empty($kb['tag'])) {
echo '<span>Tags: ';
if ($tg = explode(",",$kb['tag'])) {
$count = count($tg) - 1;
for ($i =0; $i <= $count; $i++)
{
echo '<a href="kb.php?iwb=tag&name='.$tg[$i].'">'.str_replace('-',' ',$tg[$i]).'</a> ';
}
$rand = mt_rand(0, $count);
$sql = $tg[$rand];
}
else {
echo '<a href="kb.php?iwb=tag&name='.$kb['tag'].'">'.str_replace('-',' ',$kb['tag']).'</a>';
$sql = $kb['tag'];
}
echo '</span>';
$tags = mysql_query("SELECT `id`, `title` FROM `kb` WHERE `id` != '".$kb['id']."' AND `tag` LIKE '%".mysql_real_escape_string($sql)."%' ORDER BY RAND() ASC LIMIT 10;");
if (mysql_num_rows($tags) > 0) {
while ($tag = mysql_fetch_array($tags)) {
echo $i % 2 ? '<div class="row0">' : '<div class="row1">';
echo '<a href="kb.php?read=' . $tag['id'] . '">' . htmlspecialchars($tag['title']) . '</a>';
++$i;
echo '</div>';
}
}
}
echo '</div></div>';
require_once('inc/foot.php');
}
}
else {
$page=$_GET['page'];
$q = "SELECT `id`, `title` FROM `kb`";
$total = mysql_num_rows(mysql_query($q));
if (!ctype_digit($page) || empty($page) || $page == 0 || $page > (ceil($total / $site['num_post_main'])))
$page = 1;
$page--;
$max_view = $site['num_post_main'];
$limit = $page * $max_view;
$page++;

$head_title = $LANG['knowledge_base'];
require_once('inc/head.php');
echo '<div id="message"></div><div id="content"><div id="main-content">';
if ($is_admin)
echo '<div id="show_bar"><a href="kb.php?iwb=add">'.$LANG['add_new'].'</a></div>';
echo '<form method="get" action="kb.php"><input type="hidden" name="iwb" value="search"><div class="two-col-btn"><input class="iwb-text" name="title" value=""/><input class="iwb-button" type="submit" value="'.$LANG['search_submit'].'"/></div></form><br />';
if ($total == 0) {
echo '<p>' . $LANG['empty'] . '</p>';
}
else {
$sql = mysql_query("$q ORDER BY `title` ASC LIMIT $limit, $max_view");
while ($kb = mysql_fetch_array($sql))
{
echo $i % 2 ? '<div class="row0">' : '<div class="row1">';
echo '<a href="kb.php?read=' . $kb['id'] . '">' . htmlspecialchars($kb['title']) . '</a>';
++$i;
echo '</div>';
}
$link = "kb.php?page=";
$q = "";
pagination($page,$max_view,$total,$link,$q);
}
echo '</div></div>';
require_once('inc/foot.php');
}
break;
}
?>