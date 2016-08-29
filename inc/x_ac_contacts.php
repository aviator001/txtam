<?
	$connected = false;
	include('utils.class.php');
	$c=new utils;
	$c->connect("199.91.65.82","finggr");
	$connected = true;
	$q = strtolower($_GET["term"]);
	$items = array();
	$vSQL = "select name as label, phone as value from contacts where ((name like '%".$q."%') or (phone like '%".$q."%'))";
	$result = $c->query($vSQL);
	for ($i=0;$i<count($result)-1;$i++){
    	$items[] = $result[$i];
	}
	$c->close();
echo json_encode($items);