<p>
<{$toolbar}>
</p>

<h1 class="sr-only visually-hidden" style="display: none;"><{$smarty.const._MD_TADNEWS_NEWSPAPER}></h1>

<{if $page}>
    <table class="table table-striped table-hover table-shadow">
        <tbody>
            <{foreach item=page from=$page}>
                <tr>
                    <td class="col-md-3"><{$page.np_date}></td>
                    <td><a href="<{$xoops_url}>/modules/tadnews/newspaper.php?op=preview&npsn=<{$page.allnpsn}>" target="_blank">
                    <{$page.title}></a>
                    </td>
                </tr>
            <{/foreach}>
        </tbody>
    </table>
    <div class="my-5"><{$bar}></div>
<{/if}>