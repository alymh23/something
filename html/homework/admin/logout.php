<?php
//reference url:https://github.com/MovieTone/police-reporting-system/blob/main/src/logOut.php
require 'common.php';
logger('logout');
unset($_SESSION['user']);

?>
<script>
    window.location.href = "./login.php"
</script>
