
lcExt.go = {
    cfgDef : {
        state   : 0,
    }
}

lcExt.go.Index = function()
{
    var data = {};

    if (l9rProj.Info.runtime !== undefined 
        && l9rProj.Info.runtime.go !== undefined) {
                    
        data = l9rProj.Info.runtime.go;
                    
        if (l9rProj.Info.runtime.go.state == 1) {
            fn = lcExt.go.StateRefresh;
        }

    } else {
        data = lcExt.go.cfgDef;
    }

    l4iTemplate.Render({
        tplurl : l9r.base +"+/go/set.tpl",
        dstid  : "lcext-go-setform",
        data   : data,
        success : function() {
            if (data.state == 1) {
                lcExt.go.StateRefresh();
            }
        }
    });
}

lcExt.go.StateRefresh = function()
{
    if ($("#lcext-go-setform :input[name=state]").is (':checked')) {
        $("#lcext-go-setinfo").show();
    } else {
        $("#lcext-go-setinfo").hide();
    }
}

lcExt.go.SetSave = function()
{
    var itemset = {state: 0}

    if ($("#lcext-go-setform :input[name=state]").is (':checked')) {
        itemset.state = 1;
    }

    var req = {
        path   : l4iSession.Get("proj_current") +"/lcproject.json",
        encode : "jm",
        data   : JSON.stringify({runtime: {go: itemset}}),
    }

    req.success = function() {
        l4i.InnerAlert("#lcext-go-setmsg", "alert-success", "Successfully Updated");
        l9rProj.Info.runtime.go = itemset;
        
        var tabid = l4iString.CryptoMd5(l9r.base +"+/go/index.tpl");
        l9rTab.ScrollTop(tabid);

        lcExt.NavRefresh(); // TODO SD
    }

    req.error = function(status, message) {
        l4i.InnerAlert("#lcext-go-setmsg", "alert-error", "Error: "+ message);
        l4iModal.ScrollTop();
    }

    l9rPodFs.Post(req);
}
