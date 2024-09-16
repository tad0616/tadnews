<{if $show_rss|default:false}>
    <div class="text-right text-end" style="color: #B5C7BB; padding-bottom: 4px; ">
        <img src="images/rss.svg" alt="RSS" style="width: 24px;">
        <span style="border-bottom: 1px solid #B5C7BB;">
            <{$xoops_url}>/modules/tadnews/rss.php<{if $ncsn|default:false}>?ncsn=<{$ncsn}><{/if}>
        </span>
    </div>
<{/if}>