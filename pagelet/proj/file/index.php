<?php

$projbase = H5C_DIR;

if ($this->req->proj == null) {
    die('ERROR');
}
$proj  = preg_replace("/\/+/", "/", rtrim($this->req->proj, '/'));
if (substr($proj, 0, 1) == '/') {
    $projpath = $proj;
} else {
    $projpath = "{$projbase}/{$proj}";
}
if (strlen($projpath) < 1) {
    die("ERROR");
}

$path  = preg_replace("/\/+/", "/", $this->req->path);
if (!file_exists($projpath.'/'.$path)) {
    die('ERROR');
}
if (!file_exists($projpath."/hootoapp.yaml")) {
    die('ERROR');
}

$info = hwl\Yaml\Yaml::decode(file_get_contents($projpath."/hootoapp.yaml"));

$ptpath = md5("");
?>

<table class="hdev-proj-section" width="100%">
  <tr>
    <td>
      <div id="hdev-proj-set" class="tabitem hdev-btn-caret florig" >
        <div class="ctn">More</div>
        <span class="caret"></span>
        <div class="hdev-rcmenu displaynone">
            <div class="rcitem" onclick="javascript:hdev_project_setting('<?=$proj?>')">
                <div class="rcico"><img src="/h5creator/static/img/app-t3-16.png" align="absmiddle" /></div>
                <div class="rcctn">Application Setting</div>
            </div>
            <div class="rcsepli"></div>
            <div class="rcitem hdev_rcobj_file">
                <div class="rcico"><img src="/h5creator/static/img/page_white_add.png" align="absmiddle" /></div>
                <div class="rcctn">New File</div>
            </div>
            <div class="rcitem hdev_rcobj_dir">
                <div class="rcico"><img src="/h5creator/static/img/folder_add.png" align="absmiddle" /></div>
                <div class="rcctn">New Folder</div>
            </div>
            <div class="rcitem hdev_rcobj_upload">
                <div class="rcico"><img src="/h5creator/static/img/page_white_get.png" align="absmiddle" /></div>
                <div class="rcctn">Upload</div>
            </div>
        </div>
      </div>
    </td>
  </tr>      
</table>

<!--ProjectFilesManager-->
<div id="pt<?=$ptpath?>" class="hdev-proj-files hdev-scrollbar h5c_gen_scroll"></div>

<div id="hdev-proj-olrcm-std" class="hdev-proj-olrcm border_radius_5">
    <div class="header">
        <span class="title">New Folder</span>
        <span class="close"><a href="javascript:_file_close()">×</a></span>
    </div>
    <div class="sep clearhr"></div>
    <form id="form_file_std_commit" action="/h5creator/app/file/" method="post">
    <div>
        <img src="/h5creator/static/img/folder.png" align="absmiddle" />
        <span class="path"></span> /
        <input type="text" size="30" name="name" class="inputname" value="" />
        <input type="hidden" name="proj" value="<?=$proj?>" />
        <input type="hidden" name="path" class="inputpath" value="" />
        <input type="hidden" name="type" class="inputtype" value="file" />
    </div>
    <div class="clearhr"></div>
    <div><input type="submit" name="submit" value="Save" class="input_button" /></div>
    </form>
</div>

<div id="hdev-proj-olrcm-mv" class="hdev-proj-olrcm border_radius_5">
    <div class="header">
        <span class="title">Rename ...</span>
        <span class="close"><a href="javascript:_file_close()">×</a></span>
    </div>
    <div class="sep clearhr"></div>
    <form id="form_file_mv_commit" action="/h5creator/app/file-mv/" method="post">
    <div>
        <img src="/h5creator/static/img/page_white_copy.png" align="absmiddle" />
        <span class="parfold"></span> /
        <input type="text" size="30" name="name" class="inputname" value="" />
        <input type="hidden" name="proj" value="<?=$proj?>" />
        <input type="hidden" name="path" class="inputpath" value="" />
        <input type="hidden" name="type" class="inputtype" value="file" />
    </div>
    <div class="clearhr"></div>
    <div><input type="submit" name="submit" value="Save" class="input_button" /></div>
    </form>
</div>

<div id="hdev-proj-olrcm-upload" class="hdev-proj-olrcm border_radius_5">
    <div class="header">
        <span class="title">Upload File From Location</span>
        <span class="close"><a href="javascript:_file_close()">×</a></span>
    </div>
    <div class="sep clearhr"></div>
    <form id="form_file_upload_commit" enctype="multipart/form-data" action="/h5creator/app/file-upload" method="post">
    <img src="/h5creator/static/img/page_white_get.png" align="absmiddle" />
    <span class="path"></span> /
    <input id="attachment" name="attachment" size="40" type="file" />
    <input id="proj" name="proj" type="hidden" value="<?=$proj?>"/>
    <input id="path" name="path" type="hidden" class="inputpath" value=""/>
    <div class="clearhr"></div>
    <div><input type="submit" name="submit" value="Save" class="input_button" /></div>
    </form>
</div>

<script type="text/javascript">

function _proj_set_refresh()
{
    $("#hdev-proj-set").bind("click", function(e) {
    
        $(this).find(".hdev-rcmenu").css({
            top: e.pageY+'px',
            left: e.pageX
        }).toggle();
       
        $(this).find(".hdev_rcobj_file").click(function() {
            _file_std_show("file", "");
        });
        $(this).find(".hdev_rcobj_dir").click(function() {
            _file_std_show("dir", "");
        });
        $(this).find(".hdev_rcobj_upload").click(function() {
            _file_upload("");
        });
        $(this).find(".hdev_rcobj_rename").click(function() {
            _file_rename("");
        });
        
        $(document).click(function() {
            $(this).find('.hdev-rcmenu').hide();
        });
        
        return false;
    });
}

$("#form_file_upload_commit").submit(function(event) {

    event.preventDefault(); 

    var files = document.getElementById('attachment').files;
    if (!files.length) {
        alert('Please select a file!');
        return;
    }
    
    var formData = new FormData(this);
            
    for (var i = 0, file; file = files[i]; ++i) {
        formData.append(file.name, file);
    }
  
    var xhr = new XMLHttpRequest();
    xhr.open("POST", $(this).attr('action'), true);
    xhr.onprogress = function(e) {
        //alert('progress');
    };
    xhr.onload = function(e) {
        if (this.status == 200) {
            hdev_header_alert('success', this.responseText);
            _file_std_callback($("#hdev-proj-olrcm-upload").find(".inputpath").val());
            _file_close();
        } else {
            hdev_header_alert('error', this.responseText);
        }
    };

    xhr.send(formData);
});

$("#form_file_mv_commit").submit(function(event) {

    event.preventDefault();
    
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        timeout: 3000,
        success: function(data) {
            hdev_header_alert('success', data);
            _file_std_callback($("#hdev-proj-olrcm-mv .parfold").text());
            _file_close();
        },
        error: function(xhr, textStatus, error) {
            hdev_header_alert('Error: ', textStatus+' '+xhr.responseText);
        }
    });
});


$("#form_file_std_commit").submit(function(event) {

    event.preventDefault();
    
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        timeout: 3000,
        success: function(data) {
            hdev_header_alert('success', data);
            _file_std_callback($("#hdev-proj-olrcm-std").find(".inputpath").val());
            _file_close();
        },
        error: function(xhr, textStatus, error) {
            hdev_header_alert('error', textStatus+' '+xhr.responseText);
        }
    });
});

   
function _file_std_show(type, path)
{
    var p = posFetch();

    w = $("#hdev-proj-olrcm-mv").outerWidth(true);
    bw = $('body').width() - 30;
    l = p.left;
    if (l > (bw - w)) {
        l = bw - w;
    }

    h = $("#hdev-proj-olrcm-std").height();
    t = p.top;
    bh = $('body').height() - 50;        
    if ((t + h) > bh) {
        t = bh - h;
    }
        
    $("#hdev-proj-olrcm-std .path").text(path);
    
    $("#hdev-proj-olrcm-std .inputtype").val(type);
    $("#hdev-proj-olrcm-std .inputpath").val(path);
    
    if (type == 'file') {
        $("#hdev-proj-olrcm-std .title").text('New File');
    } else if (type == 'dir') {
        $("#hdev-proj-olrcm-std .title").text('New Folder');
    }
    
    //console.log("hdev-proj-olrcm-std height: "+$("#hdev-proj-olrcm-std").height());
    //bh = $('body').height();
    
    $("#hdev-proj-olrcm-std").css({
        top: t+'px',
        left: l+'px'
    }).show("fast");
    
    $("#hdev-proj-olrcm-std .inputname").focus();
}

function _file_close()
{
    $("#hdev-proj-olrcm-std .inputname").val('');    
    $("#hdev-proj-olrcm-std").hide();
    
    $("#hdev-proj-olrcm-upload #attachment").val('');   
    $("#hdev-proj-olrcm-upload").hide();
    
    $("#hdev-proj-olrcm-mv").hide();
    
    $("#hdev-proj-set-ol").hide();
}

function _file_std_callback(path)
{
    //console.log(projCurrent+'/'+path);
    _hdev_dir(projCurrent, path, 1);
}

function _file_upload(path)
{
    // Check for the various File API support.
    if (window.File && window.FileReader && window.FileList && window.Blob) {
        // Great success! All the File APIs are supported.
    } else {
        alert('The File APIs are not fully supported in this browser.');
        return;
    }
    
    var p = posFetch();

    w = $("#hdev-proj-olrcm-mv").outerWidth(true);
    bw = $('body').width() - 30;
    l = p.left;
    if (l > (bw - w)) {
        l = bw - w;
    }

    h = $("#hdev-proj-olrcm-upload").height();
    t = p.top;
    bh = $('body').height() - 50;        
    if ((t + h) > bh) {
        t = bh - h;
    }    
    
    $("#hdev-proj-olrcm-upload .path").text(path);
    $("#hdev-proj-olrcm-upload .inputpath").val(path);

    $("#hdev-proj-olrcm-upload").css({
        top: t+'px',
        left: l+'px'
    }).show("fast");
}

function _file_rename(path)
{
    var curname = path.replace(/^.*[\\\/]/, '');
    var parfold = path.substring(0, path.lastIndexOf('/'));
    
    var p = posFetch();

    w = $("#hdev-proj-olrcm-mv").outerWidth(true);
    bw = $('body').width() - 30;
    l = p.left;
    if (l > (bw - w)) {
        l = bw - w;
    }
    
    h = $("#hdev-proj-olrcm-mv").height();
    t = p.top;
    bh = $('body').height() - 50;        
    if ((t + h) > bh) {
        t = bh - h;
    }
    
    $("#hdev-proj-olrcm-mv .parfold").text(parfold);
    $("#hdev-proj-olrcm-mv .inputname").val(curname);
    $("#hdev-proj-olrcm-mv .inputpath").val(path);    

    $("#hdev-proj-olrcm-mv").css({
        top: t+'px',
        left: l+'px'
    }).show("fast");
    
    $("#hdev-proj-olrcm-mv .inputname").focus();
}

/**
    How to use jQuery contextmenu:
    
    1. http://www.webdeveloperjuice.com/demos/jquery/vertical_menu.html
    2. http://www.electrictoolbox.com/jquery-modify-right-click-menu/
 */
function _refresh_tree()
{
    $(".hdev-proj-tree").bind("contextmenu", function(e) {
        
        h = $(this).find(".hdev-rcmenu").height();
        t = e.pageY;
        bh = $('body').height() - 20;        
        if ((t + h) > bh) {
            t = bh - h;
        }
        
        bw = $('body').width() - 20;
        l = e.pageX;
        if (l > (bw - 200)) {
            l = bw - 200;
        }

        $(this).find('.hdev-rcmenu').hide();
        
        $(this).find(".hdev-rcmenu").css({
            top: t +'px',
            left: l +'px'
        }).show();
    
        $(this).find(".hdev-rcmenu").click(function() {
            $(this).find(".hdev-rcmenu").hide();
        });
        
        $(this).find(".hdev_rcobj_file").click(function() {
            p = $(this).position();
            path = $(this).attr('href').substr(1);
            _file_std_show("file", path);
        });
        $(this).find(".hdev_rcobj_dir").click(function() {
            path = $(this).attr('href').substr(1);
            _file_std_show("dir", path);
        });
        $(this).find(".hdev_rcobj_upload").click(function() {
            path = $(this).attr('href').substr(1);
            _file_upload(path);
        });
        $(this).find(".hdev_rcobj_rename").click(function() {
            path = $(this).attr('href').substr(1);
            _file_rename(path);
        });
        
        $(document).click(function() {
            $(this).find('.hdev-rcmenu').hide();
        });
    
        return false;
    });
}


function _page_del(proj, path)
{
    p = Crypto.MD5(path);
    
    $.ajax({
        type: "GET",
        url: '/h5creator/app/file-del/',
        data: 'proj='+proj+'&path='+path,
        success: function() {
            $("#ptp"+p).remove();
            $("#pt"+p).remove();
        }
    });
}
function _hdev_dir(proj, path, force)
{
    p = Crypto.MD5(path);

    if (force != 1 && $("#pt"+p).html() && $("#pt"+p).html().length > 1) {
        $("#pt"+p).empty();
        return;
    }
    
    $.ajax({
        type: "GET",
        url: '/h5creator/app/project-tree/',
        data: 'proj='+proj+'&path='+path,
        success: function(data) {
            $("#pt"+p).html(data);
            h5cLayoutResize();
        }
    });
}

_proj_set_refresh();
_hdev_dir('<?=$proj?>', '', 1);
</script>

<?php
if (!is_writable("{$projbase}/{$proj}")) {
    echo '<script>
        hdev_header_alert("error", "The Project is not Writable");
    </script>';
}
?>
