<form action="response.php" method="post">
    <input type="hidden" name="response" value="<?php echo $response; ?>">
    <input type="submit" name="submit_response" id="submit_response">
</form>
<script type="text/javascript">
    document.getElementById('submit_response').click();
</script>