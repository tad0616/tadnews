<{if $smarty.session.bootstrap==4}>
<{includeq file="$xoops_rootpath/modules/tadnews/templates/b4/`$this_file`"}>
<{else}>
<{includeq file="$xoops_rootpath/modules/tadnews/templates/b3/`$this_file`"}>
<{/if}>