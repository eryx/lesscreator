
var l9rTab = {
    col_def     : "main",
    pageArray   : {},
    pageCurrent : 0,

    cols       : {
        "main": {
            target  : "main",
            urid    : "",
            actived : false,
            type    : null,
        }
    },

    // pool[urid] = {
    //     "url"	: "string",
    //     "target" : "main",
    //     "data"	: "string",
    //     "type"	: "html/code",
    //     "mime"	: "*",
    //     "hash"	: "*",
    //     "tpluri" : "string",
    //     "jsdata" : "JSON",
    // }
    pool        : {}
}

l9rTab.Open = function(options)
{
    options = options || {};

    if (typeof options.success !== "function") {
        options.success = function(){};
    }

    if (typeof options.error !== "function") {
        options.error = function(){};
    }

    if (!options.target) {
        options.target = l9rTab.col_def;
    }

    var urid = l4iString.CryptoMd5(options.uri);

    if (!l9rTab.cols[options.target]) {
        l9rTab.cols[options.target] = {
            urid   : 0,
            target : options.target,
            editor : null,
            state  : "",
        };
    }

    if (!l9rTab.pool[urid]) {

        l9rTab.pool[urid] = {
            url       : options.uri,
            target    : options.target,
            type      : options.type,
            title     : options.title,
            icon      : options.icon,
            success   : options.success,
            error     : options.error,
            titleOnly : options.titleOnly,
            close     : true,
        }

        if (options.close === false) {
        	l9rTab.pool[urid].close = false;
        }

        if (options.jsdata) {
            l9rTab.pool[urid].jsdata = options.jsdata;
        }

        if (options.tpluri) {
            l9rTab.pool[urid].tpluri = options.tpluri;
        }

        if (options.tplid) {
            l9rTab.pool[urid].tplid = options.tplid;
        }

        if (options.data) {
            l9rTab.pool[urid].data = options.data;
        } 
    }

    if (!document.getElementById("lctab-box"+ options.target)) {

        var tpl = l4iTemplate.RenderByID("lctab-tpl", {tabid: options.target});
        if (tpl == "") {
            return;
        }

        $("#lclay-col"+ options.target).append(tpl);
       
        l9rLayout.ColumnSet({
            id       : options.target,
            callback : function() {
                // $("#"+ options.target).append(tpl);
            },
        });

        // TODO
        $(".lcpg-tab-more").click(function(event) {

            event.stopPropagation();

            l9rTab.TabletMore($(this).attr('href').substr(1));

            $(document).click(function() {
                $("#lctab-openfiles-ol").empty().hide();
                $(document).unbind('click');
            });
        });
    }

    l9rTab.Switch(urid);
}

l9rTab.Switch = function(urid)
{
    var item = l9rTab.pool[urid];
    if (!item) {
        return;
    }

    if (l9rTab.cols[item.target].urid == urid) {
        return;
    }

    // TODO
    // if (l9rTab.cols[item.target].editor != null) {

    //     var prevEditorScrollInfo = l9rTab.cols[item.target].editor.getScrollInfo();
    //     var prevEditorCursorInfo = l9rTab.cols[item.target].editor.getCursor();

    //     l9rData.Get("files", l9rTab.cols[item.target].urid, function(prevEntry) {

    //         if (!prevEntry) {
    //             return;
    //         }

    //         prevEntry.scrlef = prevEditorScrollInfo.left;
    //         prevEntry.scrtop = prevEditorScrollInfo.top;
    //         prevEntry.curlin = prevEditorCursorInfo.line;
    //         prevEntry.curch  = prevEditorCursorInfo.ch;

    //         l9rData.Put("files", prevEntry, function() {
    //             // TODO
    //         });
    //     });
    // }

    if (l9rTab.cols[item.target].urid != urid) {
        //lcEditor.Save(lcEditor.urid, 1);
        l9rTab.cols[item.target].urid = 0;
    }

    l9rTab.TabletTitle(urid, true);

    // console.log(item);
    if (item.titleOnly === true) {
        l9rTab.TabletTitleImage(urid);
        l9rTab.pool[urid].titleOnly = false;
        return;
    }

    $("#lctab-body"+ item.target).removeClass("lctab-body-bg-light");

    switch (item.type) {
    case "apidriven":

        if (item.tpluri !== undefined) {

            l9r.Ajax(item.tpluri, {
                success : function(rsp) {

                    if (item.jsdata !== undefined) {
                        var tempFn = doT.template(rsp);
                        l9rTab.pool[urid].data = tempFn(item.jsdata);
                    } else {
                        l9rTab.pool[urid].data = rsp;
                    }

                    // console.log(item.jsdata);

                    l9rTab.TabletTitleImage(urid);
                    l9rTab.cols[item.target].urid = urid;
                    l9rTab.cols[item.target].type = item.type;

                    $("#lctab-bar"+ item.target).hide();
                    $("#lctab-body"+ item.target).empty().html(l9rTab.pool[urid].data);
                    l9rLayout.Resize();
                    setTimeout(l9rLayout.Resize, 10);

                    $("#lctab-body"+ item.target).addClass("lctab-body-bg-light");

                    l9rTab.cols[item.target].editor = null;
                },
                error: function(xhr, textStatus, error) {
                    l9r.HeaderAlert("error", xhr.responseText);
                }
            });
        }

        break;
    case "html":
    case "webterm":

        if (item.tplid) {
            
            var elem = document.getElementById(item.tplid);
            if (!elem) {
                return l9r.HeaderAlert("error", "tplid can not found");;
            }

            item.data = elem.value || elem.innerHTML;
            l9rTab.pool[urid].data = item.data;
        }

        if (item.data.length < 1) {
            // console.log(item);
            l9r.Ajax(item.url, {
                timeout : 30000,
                success : function(rsp) {

                    l9rTab.pool[urid].data = rsp;
                    l9rTab.TabletTitleImage(urid);
                    l9rTab.cols[item.target].urid = urid;
                    l9rTab.cols[item.target].editor = null;
                    l9rTab.cols[item.target].type = item.type;

                    $("#lctab-bar"+ item.target).hide();
                    $("#lctab-body"+ item.target).empty().html(rsp);
                    l9rLayout.Resize();

                    $("#lctab-body"+ item.target).addClass("lctab-body-bg-light");

                    item.success();
                },
                error: function(xhr, textStatus, error) {
                    l9r.HeaderAlert("error", xhr.responseText);
                }
            });
        } else {
            
            l9rTab.TabletTitleImage(urid);
            l9rTab.cols[item.target].urid = urid;
            l9rTab.cols[item.target].type = item.type;
            
            $("#lctab-bar"+ item.target).empty();
            $("#lctab-body"+ item.target).empty().html(item.data);
            l9rLayout.Resize();

            // console.log("webterm tab.open");

            item.success();
        }
        break;

    case "editor":

        lcEditor.TabletOpen(urid, function(ret) {
            
            if (!ret) {
                return;
            }

            //console.log("lcEditor.TabletOpen OK");
            l9rTab.TabletTitleImage(urid);
            l9rTab.cols[item.target].urid = urid;
            l9rTab.cols[item.target].type = item.type;
            // l4iStorage.Set("tab.fra.urid."+ item.target, urid);
            
            // TODO
            l4iStorage.Set(l4iSession.Get("l9r_pandora_pod_id") +"."+ l4iSession.Get("l9r_proj_name") +".cab."+ item.target, urid);

            // console.log(l4iSession.Get("l9r_pandora_pod_id") +"."+ l4iSession.Get("l9r_proj_name") +".cab."+ item.target +": "+ urid);
        
            item.success();
        });

        break;

    default :
        return;
    }


}

l9rTab.TabletTitleImage = function(urid, imgsrc)
{
    var item = l9rTab.pool[urid];

    if (!imgsrc && item.icon) {

        if (item.icon.slice(0, 1) == "/") {
            imgsrc = item.icon;
        } else {
            imgsrc = l9r.base + "~/lesscreator/img/"+ item.icon +".png";
        }
    }

    if (imgsrc) {
        $("#pgtab"+ urid +" .ico img").attr("src", imgsrc);
    }
}

l9rTab.TabletTitle = function(urid, loading)
{
    var item = l9rTab.pool[urid];
    
    if (!item.target) {
        return;
    }

    if ($("#pgtab"+ urid).length < 1) {

        if (!item.title) {
            item.title = item.url.replace(/^.*[\\\/]/, '');
        }

        var entry  = '<table id="pgtab'+ urid +'" class="pgtab" style="display:none"><tr>';
        
        if (item.icon) {

            if (loading) {
                var imgsrc = l9r.base + "~/lesscreator/img/loading4.gif";
            } else {
                var imgsrc = l9r.base + "~/lesscreator/img/"+ item.icon +".png";
            }

            //
            if (item.icon.slice(0, 1) == '/') {
                imgsrc = item.icon;
            }

            entry += "<td class='ico' onclick=\"l9rTab.Switch('"+ urid +"')\">\
                <img src='"+ imgsrc +"' align='absmiddle' /></td>";
        }

        entry += '<td class="chg">*</td>';
        entry += "<td class=\"pgtabtitle\" onclick=\"l9rTab.Switch('"+ urid +"')\">"+item.title+"</td>";
        
        if (item.close) {
            // entry += '<td><div class="pgtabclose" onclick="l9rTab.Close(\''+ urid +'\', 0)"><div class="pgtabcloseitem">&times;</div></div></td>';
            entry += '<td><span class="pgtabclose" onclick="l9rTab.Close(\''+ urid +'\', 0)"></span></td>';
        }

        entry += '</tr></table>';
        
        $("#lctab-navtabs"+ item.target).append(entry);
        $("#pgtab"+ urid).show(200);
    }

    if (item.titleOnly !== true) {
        $('#lctab-navtabs'+ item.target +' .pgtab.current').removeClass('current');
        $('#pgtab'+ urid).addClass("current");
    }

    var pg = $('#lctab-nav'+ item.target +' .lctab-navm').innerWidth();
    //console.log("h5c-tablet-tabs t*"+ pg);
    
    var tabp = $('#pgtab'+ urid).position();
    if (!tabp) {
        return;
    }
    //console.log("tab pos left:"+ tabp.left);
    
    var mov = tabp.left + $('#pgtab'+ urid).outerWidth(true) - pg;
    if (mov < 0) {
        mov = 0;
    }
    
    var pgl = $('#lctab-navtabs'+ item.target +' .pgtab').last().position().left 
            + $('#lctab-navtabs'+ item.target +' .pgtab').last().outerWidth(true);
    
    if (pgl > pg) {
        //$('#lctab-nav'+ item.target +' .lcpg-tab-more').show();
        $('#lctab-nav'+ item.target +' .lcpg-tab-more').html("»");
    } else {
        //$('#lctab-nav'+ item.target +' .lcpg-tab-more').hide();
        $('#lctab-nav'+ item.target +' .lcpg-tab-more').empty();
    }

    $('#lctab-nav'+ item.target +' .lctab-navs').animate({left: "-"+mov+"px"}); // COOL!
}

l9rTab.TabletMore = function(tg)
{
    // console.log("TabletMore: "+ tg);

    var ol = '';
    for (i in l9rTab.pool) {

        if (l9rTab.pool[i].target != tg) {
            continue;
        }

        var href = "javascript:l9rTab.Switch('"+ i +"')";
        ol += '<div class="ltm-item lctab-nav-moreitem">';
        ol += '<div class="ltm-ico"><img src="'+ l9r.base + '~/lesscreator/img/'+ l9rTab.pool[i].icon +'.png" align="absmiddle" /></div>';
        ol += '<div class="ltm-ctn"><a href="'+ href +'">'+ l9rTab.pool[i].title +'</a></div>';
        ol += '</div>';
    }
    $("#lctab-openfiles-ol").empty().html(ol);
    
    e = l4i.PosGet();
    w = 100;
    h = 100;
    //console.log("event top:"+e.top+", left:"+e.left);
    
    $("#lctab-openfiles-ol").css({
        width: w +'px',
        height: 'auto',
        top: (e.top + 10)+'px',
        left: (e.left - w - 10)+'px'
    }).toggle();

    rw = $("#lctab-openfiles-ol").outerWidth(true);   
    if (rw > 400) {
        $("#lctab-openfiles-ol").css({
            width: '400px',
            left: (e.left - 410) +'px'
        });
    } else if (rw > w) {
        $("#lctab-openfiles-ol").css({
            width: rw+'px',
            left: (e.left - rw - 10)+'px'
        });
    }
    
    rh = $("#lctab-openfiles-ol").height();
    bh = $('body').height();
    hmax = bh - e.top - 30;
    //console.log("hmax: "+hmax);
    if (rh > hmax) {
        $("#lctab-openfiles-ol").css({height: hmax+"px"});
    }
    
    $(".lctab-openfiles-ol").find(".lctab-nav-moreitem").click(function() {
        $("#lctab-openfiles-ol").hide();
    });
}

l9rTab.ScrollTop = function(urid)
{
    var item = l9rTab.pool[urid];
    if (item === undefined || item.target === undefined) {
        return;
    }

    $("#lctab-body"+ item.target +".less_scroll").scrollTop(0);
}

l9rTab.Close = function(urid, force)
{
    var item = l9rTab.pool[urid];

    switch (item.type) {
    case "apidriven":
    case 'html':
        l9rTab.CloseClean(urid);
        break;
    case 'webterm':
        l9rTab.CloseClean(urid);
        l9rWebTerminal.Close(item.url.substr(5));
        // l4iStorage.Set("lcWebTerminal0", "0");
        break;
    case 'editor':

        if (force == 1) {
        
            l9rTab.CloseClean(urid);

        } else {

            lcEditor.IsSaved(urid, function(ret) {
                
                if (ret) {
                    l9rTab.CloseClean(urid);
                    return;
                }

                l4iModal.Open({
                    title        : "Save changes before closing",
                    tpluri       : l9r.base + "-/editor/changes2save.tpl",
                    width        : 500,
                    height       : 180,
                    data         : {urid: urid},
                    position     : "center",
                    buttons      : [
                        {
                            onclick : "lcEditor.DialogChanges2SaveDone(\""+urid+"\")",
                            title   : "Save",
                            style   : "btn-primary"
                        },
                        {
                            onclick : "lcEditor.DialogChanges2SaveSkip(\""+urid+"\")",
                            title   : "Close without Saving",
                        },
                        {
                            onclick : "l4iModal.Close()",
                            title   : "Close"
                        }
                    ]
                });
            });
        }
        break;
    default :
        return;
    }
}

l9rTab.CloseClean = function(urid)
{
    var item = l9rTab.pool[urid];
    if (!item || !item.url) {
        return;
    }

    var j = 0;
    var cleanbody = false;
    for (var i in l9rTab.pool) {

        if (item.target != l9rTab.pool[i].target) {
            continue;
        }

        if (!l9rTab.pool[i].target) {
            delete l9rTab.pool[i];
            continue;
        }

        if (i == urid) {
            
            l9rData.Del("files", urid, function(rs) {
                //console.log("del: "+ rs);
            });

            $('#pgtab'+ urid).hide(200, function() {
                $('#pgtab'+ urid).remove()
            });
            delete l9rTab.pool[urid];

            if (urid != l9rTab.cols[item.target].urid) {
                return;
            }

            cleanbody = true;

            // $("#lctab-body"+ item.target).empty();
            // $("#lctab-bar"+ item.target).empty();

            l9rTab.cols[item.target].urid = 0;
            if (j != 0) {
                break;
            }

        } else {            
            j = i;            
            if (l9rTab.cols[item.target].urid == 0) {
                break;
            }
        }
    }
    
    if (j != 0) {
        l9rTab.Switch(j);
        l9rTab.cols[item.target].urid = j;
    } else if (cleanbody)  {
        // $("#lctab-body"+ item.target).slideUp(200, function() {
        //     $("#lctab-body"+ item.target).empty();
        // });
        $("#lctab-bar"+ item.target).slideUp(100, function() {
            $("#lctab-bar"+ item.target).empty();
        });
        $("#lctab-body"+ item.target).empty();
        // $("#lctab-bar"+ item.target).empty();
    }

    l9rLayout.Resize();
}

l9rTab.LayoutResize = function(options)
{
    for (var i in l9rTab.cols) {

        if (l9rTab.cols[i].target != options.id) {
            continue;
        }

        if ($("#lctab-box"+ i).length < 1) {
            continue;
        }

        var _body_w = $("#lclay-col"+ i).outerWidth(true);

        $("#lctab-box"+ i).css({
            // width  : _body_w +"px",
            height : l9rLayout.height +"px",
        });

        //
        var _body_h = l9rLayout.height - $("#lctab-nav"+ i).height();
        if ($("#lctab-bar"+ i).is(":visible")) {
            _body_h = _body_h - $("#lctab-bar"+ i).height();
        }
        $("#lctab-body"+ i).css({
            width  : _body_w +"px",
            height : _body_h +"px",
        });

        //
        switch (l9rTab.cols[i].type) {

        case "html":

            break;

        case "webterm":
           
            $("#lclay-col"+ options.id).find(".l9r-webterm-item").css({
                // width  : _body_w +"px",
                height : _body_h +"px",
            });

            l9rWebTerminal.Resize(options);

            break;

        case "editor":
            $("#lctab-body"+ i +" > .CodeMirror").css({
                // width  : _body_w +"px",
                height : _body_h +"px",
            });

            break;

        default:
            //
        }
    }
}