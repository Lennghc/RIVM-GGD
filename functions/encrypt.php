<?php
    function encrypt($password){
        $salted=SALTHEADER . $password . SALTTRAILER;
        return hash('ripemd160',$salted);
    }

?>