
<table width="100%" class="table_list" cellspacing="0">
<thead>
  <tr>
    <th><b>NAME</b></th>
    <th><b>APP ID</b></th>
    <th><b>VERSION</b></th>
    <th><b>RELEASE</b></th>
    <th></th>
  </tr>
</thead>

<?php
$patt = SYS_ROOT.'app/*';
$def  = array(
  'id'    => '',
  'name'  => '',
  'type'  => '0',
  'version' => '1.0.0',
);

foreach (glob($patt, GLOB_ONLYDIR) as $st) {

  $projid = trim(strrchr($st, '/'), '/');
  
  if (in_array($projid, array('h5creator', 'hww', 'Zend'))) {
    continue;
  }

  if (file_exists($st."/lcproject.json")) {
    $val = json_decode(file_get_contents($st."/lcproject.json"), true);
  } else {
    
    continue;
    
    $val = array(
      'name'  => $projid,
      'projid'  => $projid,
    );
  }
  
  $val = array_merge($def, $val);
?>
<tr>
  <td><b><a href="javascript:hdev_project('<?=$projid?>')"><?=$val['name']?></a></b></td>
  <td><?=$val['projid']?></td>
  <td><?=$val['version']?></td>
  <td>
    <a href="#">Setting</a>
  </td>
</tr>
<?php } ?>
</table>


