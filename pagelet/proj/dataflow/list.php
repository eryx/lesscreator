<?php

$path = H5C_DIR;

if (strlen($this->req->path)) {
    $path = $this->req->path;
}

$path = preg_replace("/\/\/+/", "/", $path);
$path = rtrim($path, '/');

$pathl = trim(strrchr($path, '/'), '/');
$paths = explode("/", $path);
?>
<style>
a._proj_dataflow_href {
    padding: 3px; width: 100%;
    text-decoration: none;
}
a._proj_dataflow_href:hover {
    background-color: #999;
    color: #fff;
}
a._proj_dataflow_href_click {
    background-color: #0088cc;
    color: #fff;
}
</style>

<div style="padding:0 10px;">
    
<ul class="breadcrumb" style="margin:5px 0;">
    <li><a href="javascript:_proj_dataflow('/', 1)"><i class="icon-folder-open"></i></a> <span class="divider">/</span></li>
    <?php
    $sl = '';
    foreach ($paths as $v) {
        if (strlen($v) == 0) {
            continue;
        }
        $sl .= "/{$v}";
        if ($v == $pathl) {
            echo "<li><a href=\"javascript:_proj_dataflow('{$sl}', 1)\">{$v}</a> </li>";
        } else {
            echo "<li><a href=\"javascript:_proj_dataflow('{$sl}', 1)\">{$v}</a> <span class=\"divider\">/</span></li>";
        }
    }
    ?>
</ul>
</div>

<div id="_proj_dataflow_body" class="h5c_gen_scroll displaynone" style="margin:0 10px;border:1px solid #ccc;">
<table width="100%" sclass="table table-condensed">
<?php
foreach (glob($path."/*", GLOB_ONLYDIR) as $st) {

  $appid = trim(strrchr($st, '/'), '/');
?>
<tr>
  <td valign="middle" width="18">
    <img src="/h5creator/static/img/folder.png" align="absmiddle" />
  </td>
  <td><a href="#<?php echo $appid?>" class="_proj_dataflow_href"><?=$appid?></a></td>
</tr>
<?php } ?>
</table>
</div>

<table id="_proj_dataflow_open_foo" class="h5c_dialog_footer" width="100%">
    <tr> 
        <td width="20px"></td>
        <td>
            <button id="_proj_dataflow_open_btn" class="btn displaynone btn-inverse" onclick="_proj_dataflow_open()">Open Project</button>
        </td>
        <td align="right">
            
            <button class="btn " onclick="h5cDialogClose()">Close</button>
        </td>
        <td width="20px"></td>
    </tr>
</table>

<script>

var _path = <?php echo "'$path'";?>;
var _path_click = null;

$('._proj_dataflow_href').dblclick(function() {
    p = $(this).attr('href').substr(1);
    _proj_dataflow(_path +'/'+ p, 1)
});

$('._proj_dataflow_href').click(function() {

    _path_click = $(this).attr('href').substr(1);

    $('._proj_dataflow_href').removeClass('_proj_dataflow_href_click');
    $(this).addClass('_proj_dataflow_href_click');
    $("#_proj_dataflow_open_btn").show();
});

function _proj_dataflow_open()
{
    h5cProjectOpen(_path +'/'+ _path_click);
    h5cDialogClose();
}
</script>