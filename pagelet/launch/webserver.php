<?php

use LessPHP\Encoding\Json;
use LessPHP\LessKeeper\Keeper;
use LessPHP\LessFly\WebServer;

$projPath = lesscreator_proj::path($this->req->proj);
$projInfo = lesscreator_proj::info($this->req->proj);

$kpr = new Keeper();

if ($this->req->apimethod == "launch.web") {

    $ret = array("status" => 200, "message" => null);

    try {

        if (!isset($projInfo['projid'])) {
            throw new \Exception("Bad Request", 400);
        }

        if (!isset($projInfo['runtimes']['nginx']) 
            || $projInfo['runtimes']['nginx']['status'] == 0
            || !isset($projInfo['runtimes']['nginx']['ngx_conf_mode'])) {

            throw new \Exception("You have not enabled WebServer in Runtimes Settings", 400);
        }

        $ngx_conf_mode = $projInfo['runtimes']['nginx']['ngx_conf_mode'];
        $ngx_conf = null;
        if ($ngx_conf_mode == "custom") {
            $ngx_conf = file_get_contents($ProjPath ."/misc/nginx/virtual.custom.conf");
        } else if (in_array($ngx_conf_mode, array("std", "static", "phpmix"))) {
            $ngx_conf = file_get_contents(LESSCREATOR_DIR."/lesscreator/misc/nginx/virtual.{$ngx_conf_mode}.conf");
        } else {
            throw new \Exception("You have not enabled WebServer in Runtimes Settings", 400);
        }
        

        //
        $rs = $kpr->Info();
        $info = json_decode($rs->body, false);
        if (!isset($info->local->id)) {
            throw new \Exception("System Error", 500);
        }
        $localnodeid = $info->local->id;

        //
        $projInst = array();
        $kvPath = "/app/local/{$localnodeid}/u/{$this->req->user}/inst/{$projInfo['projid']}";
        $rs = $kpr->NodeGet($kvPath);
        if (isset($rs->body->projid)) {
            $projInst = json_decode($rs->body, true);
        }

        // TODO if !domain then ip:port
        // 
        $projInst['user'] = $this->req->user;
        $projInst['projid'] = $projInfo['projid'];
        $projInst['projpath'] = $projPath;
        $projInst['status'] = 9;
        
        $rs = $kpr->NodeSet($kvPath, json_encode($projInst));
        //print_r($rs);

        $qid = uniqid();

        $projInst['rt_ngx_conf'] = $ngx_conf;
        $rs = $kpr->LocalNodeSet("/app/local/setup/{$qid}", json_encode($projInst), 9000);

        for ($i = 0; $i < 5; $i++) {

            $rs = $kpr->NodeGet($kvPath);

            //echo $rs->body."<br/>";

            $rs = json_decode($rs->body, false);

            if ($rs->status == 1) {

                $rs2 = $kpr->NodeGet("/app/local/{$localnodeid}/u/{$this->req->user}/conf/base");
                $rs2 = json_decode($rs2->body, false);
                $ret['web_scheme'] = $rs2->web_scheme;
                $ret['web_domain'] = $rs2->web_domain;
                $ret['web_port']   = $rs2->web_port;

                throw new \Exception("OK", 200);
            }

            sleep(1);
        }

        throw new \Exception("Timeout", 500);
        

    } catch (\Exception $e) {
        $ret['status']  = $e->getCode();
        $ret['message'] = $e->getMessage();
    }

    die(json_encode($ret));
}

if (!isset($projInfo['projid'])) {
    die("Bad Request");
}

if (!isset($projInfo['runtimes']['nginx']) 
    || $projInfo['runtimes']['nginx']['status'] == 0) {

    die('<div class="alert alert-error">
        You have not enabled WebServer in Runtimes Settings. Please first configure Runtimes Environment, then perform the current operation.

        <br /><br />
        <button class="btn" onclick="lessModalClose();lcProjSet();">Goto Setting</button>
    </div>');
}

?>

<div id="mc0zzp" class="alert alert-info">
Processing, please wait ...
</div>

<script type="text/javascript">

var projid = '<?php echo $projInfo["projid"]?>';

//lessModalButtonAdd("pfz30w", "Confirm and Save", "_proj_pkg_save()", "btn-inverse");
lessModalButtonAdd("wra50b", "Close", "lessModalClose()", "");


function _proj_launch_webserver_try()
{
    var url = "/lesscreator/launch/webserver";
    url += "?apimethod=launch.web";
    url += "&proj="+ lessSession.Get("ProjPath");
    url += "&user="+ lessSession.Get("SessUser");

    $.ajax({
        url     : url,
        type    : "GET",
        timeout : 30000,
        success : function(rsp) {
            
            try {
                var rsj = JSON.parse(rsp);
            } catch (e) {
                return lessAlert("#mc0zzp", "alert-error", "Error: Service Unavailable");
            }

            if (rsj.status == 200) {
                
                var rdi = rsj.web_scheme +"://"+ rsj.web_domain +":"+ rsj.web_port +"/"+ projid;

                var msg = "Web Server Configuration successful<br /><br />";

                msg += "<a href='"+rdi+"' target='_blank' class='btn'> <i class='icon-share-alt'></i> <strong>Open</strong> "+rdi+"</a>";
                //msg += " -- or -- ";
                //msg += "<button class='btn' onclick='lessModalClose()'>Close</button>";

                lessAlert("#mc0zzp", "alert-success", msg);

            } else {
                lessAlert("#mc0zzp", "alert-error", "Error: "+ rsj.message);
            }
        },
        error: function(xhr, textStatus, error) {
            lessAlert("#mc0zzp", "alert-error", "Error: "+ xhr.responseText);
        }
    });
}

_proj_launch_webserver_try();
</script>