<?php
define("cfpath", __DIR__ );
use Facebook\Facebook;

$config  = array(
array(
		"user"	=> "ammarfaizi2",
		"email" => "ammarfaizi2",
		"pass"	=> "454469123iceteaface",
	)
);

foreach ($config as $key => $value) {
	$config[$key]['token'] = get_token($value);
}
function check_token($token)
{
	if (!is_string($token)) {
		return false;
	}
	$ch = curl_init("https://graph.facebook.com/me?access_token={$token}");
	curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER=>true,
			CURLOPT_SSL_VERIFYPEER=>false,
			CURLOPT_SSL_VERIFYHOST=>false,
			CURLOPT_FOLLOWLOCATION=>true
		]);
	$out = curl_exec($ch);
	$inf = curl_getinfo($ch);
	curl_close($ch);
	return $inf['http_code'] == 200;
}
function get_token($config)
{
	$tokenfile = cfpath. "/".$config['user']."_token.txt";
	file_exists($tokenfile) or file_put_contents($tokenfile, "");
	$a = json_decode(file_get_contents($tokenfile), 1);
	$a = is_array($a) ? $a : array(0,null);
	if ($a[0]<=(time()-60) || !check_token($a[1])) {
		$a = new Facebook($config['email'], $config['pass'], $config['user']);
		if (!$a->check_login()) {
			$x = $a->login();
		}
		$a->get_page("https://www.facebook.com/v1.0/dialog/oauth?redirect_uri=https%3A%2F%2Fwww.instagram.com%2Faccounts%2Fsignup%2Findex%2FMacholiker.Ga&scope=email%2Cpublish_actions%2Cuser_about_me%2Cuser_actions.music%2Cuser_actions.news%2Cuser_actions.video%2Cuser_activities%2Cuser_birthday%2Cuser_education_history%2Cuser_events%2Cuser_games_activity%2Cuser_groups%2Cuser_hometown%2Cuser_interests%2Cuser_likes%2Cuser_location%2Cuser_notes%2Cuser_photos%2Cuser_questions%2Cuser_relationship_details%2Cuser_relationships%2Cuser_religion_politics%2Cuser_status%2Cuser_subscriptions%2Cuser_videos%2Cuser_website%2Cuser_work_history%2Cfriends_about_me%2Cfriends_actions.music%2Cfriends_actions.news%2Cfriends_actions.video%2Cfriends_activities%2Cfriends_birthday%2Cfriends_education_history%2Cfriends_events%2Cfriends_games_activity%2Cfriends_groups%2Cfriends_hometown%2Cfriends_interests%2Cfriends_likes%2Cfriends_location%2Cfriends_notes%2Cfriends_photos%2Cfriends_questions%2Cfriends_relationship_details%2Cfriends_relationships%2Cfriends_religion_politics%2Cfriends_status%2Cfriends_subscriptions%2Cfriends_videos%2Cfriends_website%2Cfriends_work_history%2Cads_management%2Ccreate_event%2Ccreate_note%2Cexport_stream%2Cfriends_online_presence%2Cmanage_friendlists%2Cmanage_notifications%2Cmanage_pages%2Coffline_access%2Cphoto_upload%2Cpublish_checkins%2Cpublish_stream%2Cread_friendlists%2Cread_insights%2Cread_mailbox%2Cread_page_mailboxes%2Cread_requests%2Cread_stream%2Crsvp_event%2Cshare_item%2Csms%2Cstatus_update%2Cuser_online_presence%2Cvideo_upload%2Cxmpp_login&response_type=token&client_id=124024574287414&_rdr");
		$x = $a->curl_info['redirect_url'];
		var_dump($x);
		$x = parse_url($x);
		$x = explode("&",$x['fragment']);
		$token = explode("=", $x[0]);
		$token = $token[1];
		$expired = explode("=", $x[1]);
		$expired = time() + ($expired[1]-120);
		file_put_contents($tokenfile, json_encode([$expired, $token], 128));
		unset($a);
	} else {
		$token = $a[1];
	}

	return $token;
}