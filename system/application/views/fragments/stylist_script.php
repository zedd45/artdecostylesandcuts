
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> 
<script type="text/javascript" src="/presentation/js/jquery.colorbox.js"></script>
<script type="text/javascript">
  $(function(){
    $('a.lightbox-trigger-all').click(function(){
      $('a.lightbox-photo').colorbox({rel:'all', open:true});
    });
    
    $('a.lightbox-trigger').click(function(){
      $('a.lightbox-photo').colorbox({rel:false});  
    });
    
    $('a.lightbox-photo').colorbox();
  });
</script>
