<?php

$projPath = h5creator_proj::path($this->req->proj);
$projInfo = h5creator_proj::info($this->req->proj);
if (!isset($projInfo['projid'])) {
    die("Bad Request");
}

if (!isset($this->req->data) || strlen($this->req->data) == 0) {
    die("The instance does not exist");
}
list($datasetid, $tableid) = explode("/", $this->req->data);

$fsd = $projPath."/data/{$datasetid}.ds.json";
if (!file_exists($fsd)) {
    die("Bad Request");
}
$dataInfo = file_get_contents($fsd);
$dataInfo = json_decode($dataInfo, true);
if ($projInfo['projid'] != $dataInfo['projid']) {
    die("Permission denied");
}

$fst = $projPath."/data/{$datasetid}_{$tableid}.tbl.json";
if (!file_exists($fst)) {
    die("Bad Request");
}
$tableInfo = file_get_contents($fst);
$tableInfo = json_decode($tableInfo, true);

$fieldtypes = array(
    'varchar' => 'Varchar',
    'string' => 'Text',
    'int' => 'Integer',
    'timestamp' => 'Unix Timestamp',
    'blob' => 'blob',
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (!is_writable($fst)) {
        die("Permission denied, Can not write to ". $fst);
    }

    $schema = array();
    foreach ($this->req->fsname as $k => $v) {

        $v = str_replace(':', '_', $v);
        
        if (strlen($v) == 0) {
            continue;
        }

        if ($v == "id") {
            $this->req->fstype[$k] = 'varchar';
        }
        
        if (in_array($this->req->fstype[$k], array('int', 'varchar'))
            && $this->req->fslen[$k] == 0) {
            die("`$v` Can not be null");
        }
        
        if (!isset($this->req->fsidx[$k])) {
            $this->req->fsidx[$k] = 0;
        }
        
        $schema[] = array(
            'name'  => "{$v}",
            'type'  => "{$this->req->fstype[$k]}",
            'len'   => "{$this->req->fslen[$k]}",
            'idx'   => "{$this->req->fsidx[$k]}",
        );
    }

    $tableInfo['schema']  = $schema;
    $tableInfo['updated'] = time();
    file_put_contents($fst, hwl_Json::prettyPrint($tableInfo));
    
    die("OK");
}
?>

<form id="bhw2j1" action="/h5creator/data/inlet-table-schema-set">
    
<input type="hidden" name="data" value="<?php echo $this->req->data?>" />

<table class="table table-condensed" width="100%">
<thead>
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Length (varchar、integer)</th>
        <th>Index?</th>
        <th>Option</th>
    </tr>
</thead>
<tbody id="field_list">
    <tr>
        <td>
            <input type="text" name="fsname[0]" value="id"  readonly="readonly" class="input-medium"/>
        </td>
        <td>
            <input type="text" name="fstype[0]" value="Varchar" readonly="readonly" class="input-medium"/>
        </td>
        <td>
            <input type="text" name="fslen[0]" value="40" readonly="readonly" class="input-mini"/>
        </td>
        <td>
            <input type="checkbox" name="fsidx[0]" value="1" readonly="readonly" checked />
        </td>
        <td></td>
    </tr>
    <?php
    foreach ($tableInfo['schema'] as $v) {
        if ($v['name'] == 'id') {
            continue;
        }
        $checked = '';
        if ($v['idx'] == 1) {
            $checked = 'checked';
        }
        ?>
        <tr>
            <td>
                <input name="fsname[<?php echo $v['name']?>]" type="text" value="<?php echo $v['name'] ?>" class="input-medium"/>
            </td>
            <td>
                <select name="fstype[<?php echo $v['name']?>]" class="input-medium">
                <?php
                foreach ($fieldtypes as $k2 => $v2) {
                    $select = $v['type'] == $k2 ? 'selected' : '';
                    echo "<option value='{$k2}' {$select}>{$v2}</option>";
                }
                ?>
                </select>
            </td>
            <td>
                <input name="fslen[<?php echo $v['name']?>]" type="text" value="<?php echo  $v['len'] ?>" class="input-mini"/>
            </td>
            <td>
                <input name="fsidx[<?php echo $v['name']?>]" type="checkbox" value="1" <?php echo $checked?> />
            </td>
            <td>
                <a href="javascript:void(0)" onclick="_data_field_del(this)">Delete</a>
            </td>
        </tr>
        <?php
    }
    ?>            
</tbody>
</table>

<input type="submit" class="btn" value="Save" />
<a href="javascript:_data_field_append()" >New Field</a>

</form>

<script>

var data = '<?php echo $this->req->data?>';

function _data_field_del(field)
{
    $(field).parent().parent().remove();
}

function _data_field_append()
{
    sid = Math.random() * 1000000000;
    
    entry = '<tr> \
      <td><input name="fsname['+sid+']" type="text" value="" class="input-medium"/></td> \
      <td> \
        <select name="fstype['+sid+']" class="input-medium"> \
        <?php
        foreach ($fieldtypes as $k => $v) {
            echo "<option value=\"{$k}\">{$v}</option> \\\n";
        }
        ?>
        </select> \
      </td> \
      <td><input name="fslen['+sid+']" type="text" value="" class="input-mini"/></td>\
      <td><input name="fsidx['+sid+']" type="checkbox" value="1" /> </td>\
      <td><a href="javascript:void(0)" onclick="_data_field_del(this)">Delete</a></td> \
    </tr>';
    $("#field_list").append(entry);
}

$("#bhw2j1").submit(function(event) {

    event.preventDefault();
    
    var fs = $('input[name^="fsname"]');
    fs.each(function (i,f) {
        var fn = $(f).val();
        var reg = /^[a-zA-Z][a-zA-Z0-9_:]+$/; 
        if(!reg.test(fn)){
            hdev_header_alert("alert-error", fn+" is invalid");
            return;
        }
    });

    var time = new Date().format("yyyy-MM-dd HH:mm:ss");   
    $.ajax({ 
        type    : "POST",
        url     : $(this).attr('action') +"?_="+ Math.random(),
        data    : $(this).serialize() +'&proj='+ projCurrent,
        success : function(rsp) {
            if (rsp == "OK") {
                hdev_header_alert("alert-success", time +" OK");
            } else {
                alert(rsp);
                hdev_header_alert("alert-error", time +" "+ rsp);
            }
        }
    });
});

</script>
