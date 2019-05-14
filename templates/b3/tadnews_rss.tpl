<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
<channel>
    <title><{$channel_title}></title>
    <link><{$channel_link}></link>
    <description><{$channel_desc}></description>


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