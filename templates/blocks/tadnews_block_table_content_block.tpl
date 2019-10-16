<script type="text/javascript">
    $(document).ready(function(){
            view_content<{$block.randStr}>(0);

            $('#ncsn<{$block.randStr}>').change(function(){
                view_content<{$block.randStr}>(0);
            })

            $('#tag_sn<{$block.randStr}>').change(function(){
                view_content<{$block.randStr}>(0);
            })
            $('#keyword<{$block.randStr}>').change(function(){
                view_content<{$block.randStr}>(0);
            })
            $('#start_day<{$block.randStr}>').focusout(function(){
                view_content<{$block.randStr}>(0);
            })
            $('#end_day<{$block.randStr}>').focusout(function(){
                view_content<{$block.randStr}>(0);
            })
    });

    function view_content<{$block.randStr}>(p){
        $.post("<{$block.HTTP_HOST}>/modules/tadnews/ajax.php", {
            num: "<{$block.num}>",
            p: p ,
            show_button:"<{$block.show_button}>",
            "cell[]": [
                "<{$block.cell1}>",
                "<{$block.cell2}>",
                "<{$block.cell3}>",
                "<{$block.cell4}>",
                "<{$block.cell5}>"
            ],
            start_from:"<{$block.start_from}>",
            show_ncsn: "<{$block.show_ncsn}>",
            randStr:"<{$block.randStr}>",
            ncsn:$("#ncsn<{$block.randStr}>").val(),
            tag_sn:$("#tag_sn<{$block.randStr}>").val(),
            keyword:$("#keyword<{$block.randStr}>").val(),
            start_day:$("#start_day<{$block.randStr}>").val(),
            end_day:$("#end_day<{$block.randStr}>").val()
        }, function(data) {
            $('#msg_results<{$block.randStr}>').html(data);
        });

        }

</script>

<{if $block.searchbar==1}>
    <{includeq file="$xoops_rootpath/modules/tadnews/templates/blocks/searchbar.tpl"}>
<{/if}>

<div id="msg_results<{$block.randStr}>">
    <{$smarty.const._MB_TADNEWS_LOADING}>
</div>
