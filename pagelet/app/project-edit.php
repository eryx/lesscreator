<?php

$projbase = H5C_DIR;

if (isset($this->req->projbase)) {
    $projbase = $this->req->projbase;
}

if (!isset($this->req->proj)
    || strlen($this->req->proj) < 1) {
    header("HTTP/1.1 404 Not Found"); die('Page Not Found');
}
$proj  = preg_replace("/\/+/", "/", rtrim($this->req->proj, '/'));
if (substr($proj, 0, 1) == '/') {
    $projpath = $proj;
} else {
    $projpath = "{$projbase}/{$proj}";
}

$status = 200;
$msg    = '';

$item = array(
  'projid' => $proj,
  'name'  => $proj,
  'summary' => '',
  'version' => '1.0.0',
  'release' => '1',
  'depends' => '',
  'props' => '',
);

$title = 'New Project';

$f = "{$projpath}/lcproject.json";
$f = preg_replace(array("/\.+/", "/\/+/"), array(".", "/"), $f);

if (!file_exists($f)) {
    header("HTTP/1.1 404 Not Found"); die('Page Not Found');
}
$title = 'Edit Project';

$t = file_get_contents($f);
$t = json_decode($t, true);
//print_r($t);
$item = array_merge($item, $t);


if ($_SERVER['REQUEST_METHOD'] == 'POST'
    || $_SERVER['REQUEST_METHOD'] == 'PUT') {

    foreach ($item as $k => $v) {
        if (isset($_POST[$k])) {
            $item[$k] = $_POST[$k];
        }
    }
    if (isset($item['props']) && is_array($item['props'])) {
        $item['props'] = implode(",", $item['props']);
    }
    
    $f = "{$projpath}/lcproject.json";
    $f = preg_replace(array("/\.+/", "/\/+/"), array(".", "/"), $f);
    
    $str  = hwl\Yaml\Yaml::encode($item);    
    if (hwl_Fs_Dir::mkfiledir($f, 0755)) {
        $fp = fopen($f, 'w');
        fwrite($fp, $str);
        fclose($fp);
        header("HTTP/1.1 200"); die('OK');
    } else {
        header("HTTP/1.1 500"); die('ERROR');
    }
}


$preSrvs = explode(",", $item['props']);
echo $msg;
?>

<form id="hdev_appedit_form" action="/h5creator/app/project-edit/" method="post" style="padding:5px;">
  <input name="proj" type="hidden" value="<?=$item['projid']?>" />
  <table class="" width="100%" style="padding:5px;" >
    <tr>
      <td width="100px"><strong>AppID</strong></td>
      <td><?=$item['projid']?></td>
    </tr>
    <tr>
      <td><strong>Name</strong></td>
      <td><input name="name" class="input-medium" type="text" value="<?=$item['name']?>" /></td>
    </tr>
    <tr>
      <td>Services</td>
      <td>
        <?php
        $srvs = h5creator_service::listAll();
        foreach ($srvs as $k => $v) {
            $ck = '';
            if (in_array($k, $preSrvs)) {
                $ck = "checked";
            }
            echo "<label class=\"checkbox\">
                <input type=\"checkbox\" name=\"props[]\" value=\"{$k}\" {$ck}/> {$v}
                </label>";       
        }
        ?>
      </td>
    </tr>
    <tr>
      <td><strong>Version</strong></td>
      <td><input name="version" class="input-medium" type="text" value="<?=$item['version']?>" /></td>
    </tr>
    <tr>
      <td><strong>Release</strong></td>
      <td><input name="release" class="input-medium" type="text" value="<?=$item['release']?>" /></td>
    </tr>
    <tr>
      <td valign="top"><strong>Summary</strong></td>
      <td><textarea name="summary" rows="3" style="width:90%;"><?=$item['summary']?></textarea></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="submit" name="submit" value="Save" class="but" /></td>
    </tr>
  </table>
</form>

<script>

$("#hdev_appedit_form").submit(function(event) {

    event.preventDefault();

    $.ajax({ 
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function(data) {
            hdev_header_alert('success', data);
            window.scrollTo(0,0);
        },
        error: function(xhr, textStatus, error) {
            hdev_header_alert('error', textStatus+' '+xhr.responseText);
        }
    });
});
</script>
