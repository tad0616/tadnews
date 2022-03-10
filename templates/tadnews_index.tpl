<{$toolbar}>
<!--TadNews Start-->
<div style="margin-bottom: 30px;">
    <{$path}>
</div>

<{includeq file="$xoops_rootpath/modules/tadnews/templates/op_`$now_op`.tpl"}>


<script type="text/javascript">
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>