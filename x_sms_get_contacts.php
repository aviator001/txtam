<?
	include('inc/utils.class.php');
	$c=new utils;
	$c->connect("199.91.65.94","finggr");
	$mobile = $_REQUEST["mobile"];
	$str="";
	$str .= "<div style='width:100%'>";
	$ctr = 1;
	$arr_a = array(' ');
	$arr_b = array('_');
	echo "select * from contacts where owner_number = '$mobile' and phone !='' order by cat asc";
	$Q = $c->query("select * from contacts where owner_number = '$mobile' and phone !='' order by cat asc");
	if (count($Q) > 0) {
		for ($i=0;$i<count($Q);$i++) {
			$phone =  $Q[$i][phone];
			$p =  $Q[$i][phone];
			if ($phone) {
			$name =  $Q[$i][name];
				$del_favs = $phone;
				$send_sms = "send_sms('$p')";
				$phone = '('.substr($phone,1,3).') ' . substr($phone,4,3) . ' - ' . substr($phone,7,4); 
				$str .= "
				<div class='deep-dark-bg' style='font-family:Lane; border-radius: 10px; max-width: 500px; margin: auto; padding: 15px; margin-bottom: 20px;'>
					<h2><font style='color:skyblue;font-size:1.2em'>Contact</font></h2><hr>
					<input type='hidden' id='contact_number_$i' value='$phone'>
					<div>
						<span style='color:skyblue' class='glyphicon glyphicon-pencil'></span>&nbsp;&nbsp; <span title='Click to Edit Name and auto save to contacts' id='con_name_$i' onclick='pause_edit(this, $i)' onblur='edit_contact(this, $i)' style='font-family: Lane;color:#fff;font-size:1.9em'>
							$name
						</span>
					</div>
										<p class='main-color' style='font-family:Lane;font-size:1.5em'>$phone</p>

					<p style='font-size:1.3em;color:#fff'>Shadow Number $phone <font style='color:#f0f0f0'><br> Total Calls to User $total_calls</font> </p>
				";
$str .=
<<<FAVORITE
	<p style='font-size:1.4em'>
	  <a title='Delete Conversations with this User' href='#' onclick="del_call_log('$p')"><span style='padding: 10px;' class='glyphicon glyphicon-trash'></span></a>
	  <a title='Add to Favorites' href='#' onclick="add_fav('$p')"><span style='padding: 10px;'class='glyphicon glyphicon-heart'></span></a>
	  <a title='Delete Contact' href='#' onclick="del_contact('$p')"><span style='padding: 10px;'class='glyphicon glyphicon-edit'></span></a>
	  <a title='Block User' href='#' onclick="add_block('$p')"><span style='padding: 10px;'class='glyphicon glyphicon-ban-circle'></span></a>
	  <a title='View Call History with user' href='#' onclick='location.href="send.php?to=$p"'><span style='padding: 10px;'class='glyphicon glyphicon-comment'></span></a><br><br>
	  <a class='btn btn-lg btn-primary' href='#' onclick='location.href="send.php?to=$p"' role='button'>Send Message</a>
  </p>
FAVORITE;
$str .="</div>";
$ctr++;
		}
		}
		echo $str;
	}	else	{
			echo "<div align=center style='border:3px solid #f0f0f0; background: #fcfcfc; border-radius: 8px; margin:25px; margin-top:40px; padding: 20px; color: #000; text-shadow: 1px 1px 0px #fff;font-family:Lane;opacity:0.8'><br><br><h1>NO CONTACTS FOUND</h1></div><br><br>";
	}
?>
