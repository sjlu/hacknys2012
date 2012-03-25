<?php

class Sentence extends CI_Model {

    function tokenize ($input) {
        $python = "sentence-split";

        $id = uniqid();
        $handle = fopen("/tmp/$id", "w");
        fwrite($handle, $input);
        fclose($handle);

        $input = escapeshellcmd($input);

        $ret = shell_exec($python . " < /tmp/$id");

        return json_decode($ret, false);
    }

}
