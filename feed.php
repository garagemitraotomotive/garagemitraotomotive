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
$iwb=isset($_GET['iwb']) ? trim($_GET['iwb']) : '';
switch ($iwb)
{
case 'rss_post_comments':
$t=isset($_GET['t']) ? trim($_GET['t']) : '';
$blog=mysql_fetch_array(mysql_query("select * from blog where site_id='".$site['id']."' and link='" . mysql_real_escape_string(htmlentities($t)) . "' and draft='0'"));
header("Content-Type: application/xml");
echo '<?xml version="1.0" encoding="iso-8859-1"?> <rss version="2.0"
xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/"
><channel>
<title>Komentar: '.htmlentities(strip_tags($blog['title']), ENT_QUOTES).'</title>
<link>'.$site['url'].'</link>
<description><![CDATA['.htmlentities(strip_tags($blog['description']), ENT_QUOTES).']]></description><pubDate>'.date('r', $blog['time']).'</pubDate>';
$comment=mysql_query("select * from comment where site_id='".$site['id']."' and blog_id='".$blog['id']."' and status='1' order by time desc limit 10;");
while ($comments=mysql_fetch_array($comment))
{
echo '<item><title>'.htmlspecialchars(strip_tags($comments['name']), ENT_QUOTES).'</title><link>'.$site['url'].'/'.$blog['link'].'.xhtml</link><pubDate>'.date('r', $comments['time']).'</pubDate>';
if ($blog['private'] == 1)
{
if ($user_id)
echo '<description><![CDATA['.htmlentities(strip_tags($comments['text']), ENT_QUOTES).']]></description>';
else
echo '<description><![CDATA[Komentar disembunyikan.]]></description>';
}
elseif ($blog['private'] == 2)
{
if ($is_author)
echo '<description><![CDATA['.htmlentities(strip_tags($comments['text']), ENT_QUOTES).']]></description>';
else
echo '<description><![CDATA[Komentar disembunyikan.]]></description>';
}
else
{
echo '<description><![CDATA['.htmlentities(strip_tags($comments['text']), ENT_QUOTES).']]></description>';
}
echo '</item>';
}
echo '</channel></rss>';

break;
case 'rss_new_comments':
header("Content-Type: application/xml");
echo '<?xml version="1.0" encoding="iso-8859-1"?> <rss version="2.0"
xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/"
><channel>
<title>Komentar '.htmlentities(strip_tags($site['name']), ENT_QUOTES).'</title>
<link>'.$site['url'].'</link>
<description><![CDATA['.htmlentities(strip_tags($site['description']), ENT_QUOTES).']]></description><pubDate>'.date('r', time()).'</pubDate>';
$comment=mysql_query("select * from comment where site_id='".$site['id']."' and status='1' order by time desc limit 10;");
while ($comments=mysql_fetch_array($comment))
{
$blogs=mysql_fetch_array(mysql_query("select * from blog where id='".$comments['blog_id']."'"));
echo '<item><title>By: '.htmlspecialchars(strip_tags($comments['name']), ENT_QUOTES).'</title><link>'.$site['url'].'/'.$blogs['link'].'.xhtml#comments</link><pubDate>'.date('r', $comments['time']).'</pubDate>';
if ($blog['private'] == 1)
{
if ($user_id)
echo '<description><![CDATA['.htmlentities(strip_tags($comments['text']), ENT_QUOTES).']]></description>';
else
echo '<description><![CDATA[Komentar disembunyikan.]]></description>';
}
elseif ($blog['private'] == 2)
{
if ($is_author)
echo '<description><![CDATA['.htmlentities(strip_tags($comments['text']), ENT_QUOTES).']]></description>';
else
echo '<description><![CDATA[Komentar disembunyikan.]]></description>';
}
else
{
echo '<description><![CDATA['.htmlentities(strip_tags($comments['text']), ENT_QUOTES).']]></description>';
}

echo '</item>';
}
echo '</channel></rss>';
break;
case 'rss_new_posts':
default:
header("Content-Type: application/xml");
echo '<?xml version="1.0" encoding="iso-8859-1"?> <rss version="2.0"
xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/"
><channel>
<title>'.htmlentities(strip_tags($site['name']), ENT_QUOTES).'</title>
<link>'.$site['url'].'</link>
<description><![CDATA['.htmlentities(strip_tags($site['description']), ENT_QUOTES).']]></description><pubDate>'.date('r', time()).'</pubDate>';
$blog=mysql_query("select * from blog where site_id='".$site['id']."' and draft='0' order by time desc limit 10;");
while ($blogs=mysql_fetch_array($blog))
{
echo '<item><title>'.htmlspecialchars(strip_tags($blogs['title']), ENT_QUOTES).'</title><link>'.$site['url'].'/'.$blogs['link'].'.xhtml</link><pubDate>'.date('r', $blogs['time']).'</pubDate>';

if ($blogs['private'] == 1)
{
if ($user_id)
echo '<description><![CDATA['.html_entity_decode(htmlentities($blogs['description'])).']]></description>';
else
echo '<description><![CDATA[Postingan ini hanya untuk Member <b>'.htmlspecialchars($site['name']).'</b>. Untuk melihat postingan ini silakan login atau register terlebih dahulu.]]></description>';
}
elseif ($blogs['private'] == 2)
{
if ($is_author)
echo '<description><![CDATA['.html_entity_decode(htmlentities($blogs['description'])).']]></description>';
else
echo '<description><![CDATA[Postingan ini hanya untuk Penulis (Author) <b>'.htmlspecialchars($site['name']).'</b>.]]></description>';
}
else
{
echo '<description><![CDATA['.html_entity_decode(htmlentities($blogs['description'])).']]></description>';
}
echo '</item>';
}
echo '</channel></rss>';
}
mysql_close($iwb_connect);
?>