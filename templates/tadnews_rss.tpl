<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <atom:link href="<{$xoops_url}>/modules/tadnews/rss.php" rel="self" type="application/rss+xml" />
    <title><{$channel_title}></title>
    <link><{$channel_link}></link>
    <description><{$channel_desc}></description>
    <lastBuildDate><{$channel_lastbuild}></lastBuildDate>
    <docs>http://backend.userland.com/rss/</docs>
    <generator><{$channel_generator}></generator>
    <category><{$channel_category}></category>
    <language><{$channel_language}></language>
<{if $image_url != ""}>
    <image>
      <title><{$channel_title}></title>
      <url><{$image_url}></url>
      <link><{$channel_link}></link>
      <width><{$image_width}></width>
      <height><{$image_height}></height>
    </image>
<{/if}>

<{foreach item=item from=$items}>
    <item>
      <title><{$item.title}></title>
      <link><{$item.link}></link>
      <description><{$item.description}></description>
      <pubDate><{$item.pubdate}></pubDate>
      <guid><{$item.guid}></guid>
    </item>
<{/foreach}>

  </channel>
</rss>