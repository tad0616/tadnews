<style>
    #news_marquee_<{$block.randStr}> {
        overflow: hidden;
        width: 100%;
        height: 4rem;
        line-height: 1.8;
        border: 1px solid #cfcfcf;
        background-color: #FCFCFC;
        box-shadow: 0px 1px 2px 1px #cfcfcf inset;
        <{$block.css|default:''}>
    }
    #news_marquee_<{$block.randStr}> .tad-marquee-item {
        line-height: 1.4;
        <{$block.item_css|default:''}>
    }
</style>

<{if $block.page|default:false}>
    <div id="news_marquee_<{$block.randStr}>"></div>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof TadMarquee === 'undefined') {
                return;
            }
            new TadMarquee('news_marquee_<{$block.randStr}>', {
                direction: '<{$block.direction|escape:"javascript"}>',
                speed: <{$block.speed|default:50}>,
                gap: 20,
                pauseOnHover: true,
                pauseOnFocus: true,
                pauseButtonPosition: 'left',
                items: <{$block.marquee_items_json|default:'[]'}>
            });
        });
    </script>
<{/if}>
