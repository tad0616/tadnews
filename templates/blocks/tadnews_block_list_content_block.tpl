<script type="text/javascript">
    $(document).ready(function(){
        tadnew_list_content<{$block.randStr}>(0);
        <{if $block.searchbar==1}>
            $('#ncsn<{$block.randStr}>').change(function(){
                tadnew_list_content<{$block.randStr}>(0);
            })

            $('#tag_sn<{$block.randStr}>').change(function(){
                tadnew_list_content<{$block.randStr}>(0);
            })
            $('#keyword<{$block.randStr}>').change(function(){
                tadnew_list_content<{$block.randStr}>(0);
            })
            $('#start_day<{$block.randStr}>').focusout(function(){
                tadnew_list_content<{$block.randStr}>(0);
            })
            $('#end_day<{$block.randStr}>').focusout(function(){
                tadnew_list_content<{$block.randStr}>(0);
            })
        <{/if}>
    });


    function tadnew_list_content<{$block.randStr}>(p){
        $.post("<{$block.HTTP_HOST}>/modules/tadnews/ajax2.php", {
            randStr:"<{$block.randStr}>" ,
            num: "<{$block.num}>",
            p: p ,
            summary_length:"<{$block.summary_length}>" ,
            summary_css:"<{$block.summary_css}>" ,
            title_length:"<{$block.title_length}>" ,
            show_cover:"<{$block.show_cover}>" ,
            cover_css:"<{$block.cover_css}>",
            start_from:"<{$block.start_from}>",
            show_ncsn: "<{$block.show_ncsn}>" ,
            display_mode: "<{$block.display_mode}>",
            show_button:"<{$block.show_button}>",
            ncsn:$("#ncsn<{$block.randStr}>").val(),
            tag_sn:$("#tag_sn<{$block.randStr}>").val(),
            keyword:$("#keyword<{$block.randStr}>").val(),
            start_day:$("#start_day<{$block.randStr}>").val(),
            end_day:$("#end_day<{$block.randStr}>").val()
        }, function(data) {
            $('#tadnew_list_content<{$block.randStr}>').html(data);
        });
    }

</script>

<{if $block.searchbar==1}>
    <{includeq file="$xoops_rootpath/modules/tadnews/templates/blocks/searchbar.tpl"}>
<{/if}>
<div id="tadnew_list_content<{$block.randStr}>"><{$smarty.const._MB_TADNEWS_LOADING}></div>