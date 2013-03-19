<?php
$projbase = H5C_DIR;

$msg = 'Internal Server Error';

$proj = preg_replace("/\/+/", "/", rtrim($this->req->proj, '/'));
if (substr($proj, 0, 1) == '/') {
    $projpath = $proj;
} else {
    $projpath = "{$projbase}/{$proj}";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $this->req->name;

    if (!strlen($name)) {
        die('Invalid Params');
    }

    $obj = $projpath ."/dataflow";
    $obj = preg_replace(array("/\.+/", "/\/+/"), array(".", "/"), $obj);
    if (!is_writable($obj)) {
        die("'$obj' is not Writable");
    }
    
    $id = hwl_string::rand(8, 2);

    $obj .= "/{$id}.grp.json";
    $obj = preg_replace(array("/\.+/", "/\/+/"), array(".", "/"), $obj);

    $set = array(
        'id'    => $id,
        'name'  => $name,
    );
    hwl_util_dir::mkfiledir($obj);
    file_put_contents($obj, hwl_Json::prettyPrint($set));

    die("OK");
}

die($msg);