<{if $cate.cate_pic|default:false}>
    <img src="<{$cate_img_url}>/<{$cate.cate_pic}>" alt="<{$cate.nc_title}>" title="<{$cate.nc_title}>" class="cate_title_img" style="border-radius: 5px;">
<{else}>
    <img src="../images/no_cover.png" alt="<{$cate.nc_title}>" title="<{$cate.nc_title}>" class="cate_title_img">
<{/if}>