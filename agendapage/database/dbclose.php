<?php

if (is_bool($result) === false){
    mysqli_free_result($result);
}

mysqli_close($dbaselink);

?>