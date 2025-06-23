<{if $block.ztree|default:false}>
  <{$block.ztree|default:''}>
<{/if}>
<style>
  .ztree li a{
    width:<{$block.width|default:160}>px;
    font-size:<{$block.font_size|default:1rem}>;
  }
</style>