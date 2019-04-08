<script type="text/javascript">
  $(document).ready(function(){
    tadnew_list_content<{$block.randStr}>(0);
  });


  function tadnew_list_content<{$block.randStr}>(p){
    $.ajax({
        type: 'POST',
        url: '<{$block.HTTP_HOST}>/modules/tadnews/ajax2.php',
        data:  { randStr:"<{$block.randStr}>" , num: "<{$block.num}>", p: p , summary_length:"<{$block.summary_length}>" , summary_css:"<{$block.summary_css}>" , title_length:"<{$block.title_length}>" , show_cover:"<{$block.show_cover}>" , cover_css:"<{$block.cover_css}>",start_from:"<{$block.start_from}>",show_ncsn: "<{$block.show_ncsn}>" ,display_mode: "<{$block.display_mode}>", show_button:"<{$block.show_button}>"},
        success: function(responseData, textStatus, jqXHR) {
            $("#tadnew_list_content<{$block.randStr}>").html(responseData);
        },
        error: function (responseData, textStatus, errorThrown) {
            $("#tadnew_list_content<{$block.randStr}>").html("<{$block.HTTP_HOST}>");
        }
    });
  }

</script>

<div id="tadnew_list_content<{$block.randStr}>"><{$smarty.const._MB_TADNEWS_LOADING}></div>