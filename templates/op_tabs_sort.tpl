<{if $cate_set_tool|default:false}>
    <p><{$toolbar|default:''}></p>
<{/if}>

<{if $cate_set_breadcrumbs=='1'}>
    <{$breadcrumb|default:''}>
<{/if}>

<h1 style="text-shadow:1px 1px 1px #aaaaaa;">
    <a href="page.php?ncsn=<{$ncsn|default:''}>&nsn=<{$nsn|default:''}>"><{$page.0.news_title}></a>
</h1>

<div id="sort">
<{foreach from=$tab_div key=data_sort item=tab}>
    <h3 style="text-shadow:1px 1px 1px #aaaaaa; border:1px solid gray; padding:10px; cursor: move;" id="sort_<{$data_sort|default:''}>">
        <span class='badge badge-pill badge-primary'><{$data_sort|default:''}></span>
        <{$tab|default:''}>
    </h3>
<{/foreach}>
</div>
<div id="save_msg"></div>

<div class="text-right text-end">
        <{$page.0.fun}>
</div>
<script>
    $(document).ready(function() {
        $('#sort').sortable({ opacity: 0.6, cursor: 'move', update: function() {
            var order = $(this).sortable('serialize');
            $.post('save_sort.php?op=sort_tabs&nsn=<{$nsn|default:''}>', order, function(theResponse){
                $('#save_msg').html(theResponse);
            });
        }
        });
    });
</script>