<script type="text/javascript">
  $(document).ready(function(){
    view_content<{$block.randStr}>(0);
  });

  function view_content<{$block.randStr}>(p){
    $.ajax({
        type: 'POST',
        url: '<{$block.HTTP_HOST}>/modules/tadnews/ajax.php',
        //crossDomain: true,
        data:  { num: "<{$block.num}>", p: p , show_button:"<{$block.show_button}>",  "cell[]": ["<{$block.cell1}>", "<{$block.cell2}>", "<{$block.cell3}>", "<{$block.cell4}>", "<{$block.cell5}>"],start_from:"<{$block.start_from}>",show_ncsn: "<{$block.show_ncsn}>",randStr:"<{$block.randStr}>"},
        success: function(responseData, textStatus, jqXHR) {
            $("#msg_results<{$block.randStr}>").html(responseData);
        },
        error: function (responseData, textStatus, errorThrown) {
            $("#msg_results<{$block.randStr}>").html("<{$smarty.const._MB_TADNEWS_AJAX_ERROR}>");
        }
    });
	}

</script>

<div id="msg_results<{$block.randStr}>">
    <{$smarty.const._MB_TADNEWS_LOADING}>
</div>
