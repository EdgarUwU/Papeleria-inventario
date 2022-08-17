<html>

<div class="alert warning-alert">
  <h3>Warning Alert Message</h3>
  <a class="close">&times;</a>
</div>

<script>
  $(document).ready(function(){
    $(".alert .close").click(function(){
      $(this).parent().fadeOut(500);
    });
  });
</script>

</html>

