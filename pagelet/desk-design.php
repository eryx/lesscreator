<?php
// http://zouyesheng.com/angular.html
?>

<script type="text/javascript">
//angular.bootstrap(document.documentElement);
</script>

<table class="lcx-header">
  <tr>
    <td width="10px"></td>

    <td width="60px" align="left">
      <div class="lcx-logo">
        <img class="icon" src="/lesscreator/static/img/gen/pen0-48.png" />
      </div>      
    </td>

    <td align="left">
      <div class="lcx-hdr-box">
        
        <div id="lcx-hnav-projinfo" class="lcx-hnav-projinfo">
            <div class="lcx-label">Project</div>
            <div class="lcx-title">Demo</div>
            <a class="lcx-set" href="javascript:lcProjSet()" 
                title="<?php echo $this->T('Settings')?>">
                <i class="lcx-ico"></i>
            </a>
        </div>

        <ul class="lcx-topnav">
        <li>
          <a href="javascript:lcProjLaunch('<?php echo $this->T('Run and Deply')?>')" title="<?php echo $this->T('Run')?>">
            <i class="lcx-ico-play"></i>
          </a>          
        </li>
        
        <li>
          <a onclick="_lc_nav_terminal()" title="<?php echo $this->T('Terminal')?> ">
            <i class="lcx-ico-term"></i>
          </a>
        </li>
        </ul>

      </div>
    </td>
    
    <!--<td align="left">
      <ul class="lcx-topnav">
        <li>
          <a href="javascript:lcProjLaunch('<?php echo $this->T('Run and Deply')?>')" title="<?php echo $this->T('Run')?>">
            <i class="lcx-ico-play"></i>
          </a>          
        </li>
        
        <li>
          <a onclick="_lc_nav_terminal()" title="<?php echo $this->T('Terminal')?> ">
            <i class="lcx-ico-term"></i>
          </a>
        </li>

      </ul>
    </td>-->

    <td align="right">
      <div class="lcx-usernav">
        <div><i class="lcx-ico-user"></i></div>
      </div>
    </td>

    <td width="10px"></td>
  </tr>  
</table>

<div style="height:10px;"></div>

<!--
<table id="hdev_header" width="100%" border="0">
  <tr>
    <td width="10px"></td>

    <td class="" width="300px">
      <img class="lc_icon" src="/lesscreator/static/img/for-test/test.png" />
    </td>

    <td align="center">
        <div class="hdev-header-alert border_radius_5 hdev_alert">workspace files, open files, run and debug, deploy, preferences</div>
    </td>

    <td align="right" style="">
       

        <div class="btn-group" >
            
            <div class="btn btn-small dropdown-toggle " data-toggle="dropdown" href="#">
                <i class="icon-folder-open"></i>
                &nbsp;&nbsp;<?php echo $this->T('Project Manage')?>&nbsp;&nbsp;
                <span class="caret" style="margin-top:8px;"></span>
            </div>

            <ul class="dropdown-menu pull-right text-left">
                <li><a href="javascript:lcProjNew()"><?php echo $this->T('Create Project')?></a></li>
                <li><a href="javascript:lcProjOpen()"><?php echo $this->T('Open Project')?></a></li>
            </ul>

        </div>
        

        <div class="btn-group" style="margin-left:0;">
            

            <div class="btn btn-small dropdown-toggle " data-toggle="dropdown" href="#">
                <i class="icon-user"></i>&nbsp;&nbsp;eryx&nbsp;&nbsp;<b class="caret"></b>
            </div>

            <ul class="dropdown-menu pull-right text-left">
                <?php
                /* $menus = Session::NavMenus('ue'); // TODO
                $prev = false;
                foreach ($menus as $menu) {
                    echo "<li><a href=\"/{$menu->projid}\">{$menu->name}</a></li>";
                    $prev = true;
                }
                if ($prev) {
                    echo '<li class="divider"></li>';
                }*/
                ?> 
                <li><a href="/user"><?php echo $this->T('Account Settings')?></a></li>
                <li><a href="/lesscreator"><?php echo $this->T('lessCreator')?></a></li>
                <li class="divider"></li>
                <li><a href="/user/logout"><?php echo $this->T('Logout')?></a></li>
            </ul>

        </div>

    </td>

    <td width="10px"></td>
  </tr>
</table>
-->

<table id="hdev_layout" border="0" cellpadding="0" cellspacing="0" class="">
  <tr>
    <!--
    http://www.daqianduan.com/jquery-drag/
    -->
    
    <td width="10px"></td>

    <td id="lcx-start-lyo" valign="top"></td>
  
    <!-- column blank 2 -->
    <td width="10px" id="h5c-lyo-col-w-ctrl" class="h5c_resize_col"></td>
    
    <td id="h5c-lyo-col-w" valign="top">
      <table width="100%" height="100%">
        <tr>
          <td id="h5c-tablet-framew0" class="hdev-layout-container" valign="top">
            
            <div id="h5c-tablet-tabs-framew0" class="h5c_tablet_tabs_frame">
              <div class="h5c_tablet_tabs_lm">
                <div id="h5c-tablet-tabs-w0" class="h5c_tablet_tabs"></div>
              </div>
              <div class="h5c_tablet_tabs_lr">
                <div class="pgtab_more lc_pgtab_more" href="#w0"></div>
              </div>
            </div>

            <div id="h5c-tablet-toolbar-w0" class="hide"></div>
            <div id="h5c-tablet-body-w0" class="h5c_tablet_body less_scroll"></div>

          </td>
        </tr>

        <tr><td height="10px" id="h5c-resize-roww0" class="h5c_resize_row hide"></td></tr>
        
        <tr>
          <td id="h5c-tablet-framew1" class="hdev-layout-container hide" valign="top">
            
            <div id="h5c-tablet-tabs-framew1" class="h5c_tablet_tabs_frame pgtabs_frame">
              <div class="h5c_tablet_tabs_lm">
                <div id="h5c-tablet-tabs-w1" class="h5c_tablet_tabs"></div>
              </div>
              <div class="h5c_tablet_tabs_lr">
              </div>
            </div>

            <div id="h5c-tablet-body-w1" class="h5c_tablet_body less_scroll"></div>

          </td>
        </tr>
      
      </table>
    </td>

    <td width="10px"></td>

  </tr>
</table>

<div class="pgtab-openfiles-ol hdev-lcmenu less_scroll"></div>

<div id="lc_editor_tools" class="hide">

    <div class="editor_bar hdev-ws hdev-tabs hcr-pgbar-editor">
        
        <div class="tabitem" onclick="lcEditor.SaveCurrent()">
            <div class="ctn"><i class="icon-hdd"></i> <?php echo $this->T('Save')?></div>
        </div>

        <div class="tabitemline"></div>
        <div class="tabitem" onclick="lcEditor.Search()">
            <div class="ctn"><i class="icon-search"></i> <?php echo $this->T('Search')?></div>
        </div>

        <div class="tabitemline"></div>
        <div class="tabitem" onclick="lcEditor.Undo()">
            <div class="ctn"><i class="icon-chevron-left"></i> <?php echo $this->T('Undo')?></div>
        </div>

        <div class="tabitem" onclick="lcEditor.Redo()">
            <div class="ctn"><i class="icon-chevron-right"></i> <?php echo $this->T('Redo')?></div>
        </div>
        
        <!-- <div class="tabitemline"></div>
        <div class="tabitem">
            <div class="ico"><img src="/lesscreator/static/img/disk.png" align="absmiddle" /></div>
            <div class="ctn"><input onclick="lcEditor.ConfigSet('editor_autosave')" type="checkbox" id="editor_autosave" name="editor_autosave" value="on" /> Auto Saving</div>
        </div> -->

        <div class="tabitemline"></div>
        <div class="tabitem" onclick="lcEditor.ConfigEditMode()">
            <div class="ico lc-editor-editmode"><img src="/lesscreator/static/img/editor/mode-win-48.png" class="" ="h5c_icon" /></div>
            <div class="ctn"><?php echo $this->T('Editor Mode')?></div>
        </div>

        <div class="tabitemline"></div>
        <div class="tabitem" onclick="lcEditor.ConfigModal()">
            <div class="ctn"><i class="icon-cog"></i> <?php echo $this->T('Setting')?></div>
        </div>
    </div>

    <div class="lc_editor_searchbar hide form-inline">
        <div class="input-prepend input-append">
            <span class="add-on"><i class="icon-search"></i></span>
            <input class="input-small" type="text" name="find" value="<?php echo $this->T('Find Word')?>" />
            <button class="btn" onclick="lcEditor.SearchNext()"><?php echo $this->T('Search')?></button>
        </div>

        <label class="inline"> <?php echo $this->T('or')?> </label>
        
        <div class="input-append">
            <input class="input-small" name="replace" type="text" value="<?php echo $this->T('Replace with')?>">
            <button class="btn" type="button" onclick="lcEditor.SearchReplace(false)"><?php echo $this->T('Replace')?></button>
            <button class="btn" type="button" onclick="lcEditor.SearchReplace(true)"><?php echo $this->T('Replace All')?></button>
        </div>
        
        <!-- <label class="checkbox inline">
          <input onclick="lcEditor.ConfigSet('editor_search_case')" type="checkbox" id="editor_search_case" name="editor_search_case" value="on" />
          Match case
        </label> -->

        <button type="button" class="close" onclick="lcEditor.Search()">&times;</button>
    </div>
</div>

<script>

var opt = {
    'img': '/lesscreator/static/img/app-t3-16.png',
    'title': 'Quick Start',
    'close': '1',
};
//h5cTabOpen("/lesscreator/app/quick-start?", 'w0', 'html', opt);

function _lc_nav_terminal()
{
    var domobj = document.getElementById("lc-terminal");
    if (!domobj) {
        lcWebTerminal(1);
        return;
    }

    if (!lc_terminal_conn.IsOk()) {
        lcWebTerminal(1);
    } else if (lc_terminal_conn.IsOk()) {
        lc_terminal_conn.CloseAll();
        var urid = lessCryptoMd5("/lesscreator/term/index?");
        lcTabClose(urid, 1);
    }
}
lcWebTerminal(0);

</script>
