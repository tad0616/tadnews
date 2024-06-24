<p>
<{$toolbar}>
</p>

<h1 class="sr-only visually-hidden" style="display: none;"><{$smarty.const._MD_TADNEWS_NEWSPAPER}></h1>

<{if $page}>
    <table class="table table-striped table-hover table-shadow">
        <tbody>
            <{foreach from=$page item=news}>
                <tr>
                    <td class="col-md-3"><{$news.np_date}></td>
                    <td><a href="<{$xoops_url}>/modules/tadnews/newspaper.php?op=preview&npsn=<{$news.allnpsn}>" target="_blank">
                    <{$news.title}></a>
                    </td>
                </tr>
            <{/foreach}>
        </tbody>
    </table>
    <div class="my-5"><{$bar}></div>
<{/if}>