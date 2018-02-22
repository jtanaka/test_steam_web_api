<?php
// phpinfo();
// die();


	function convert_steamid_64bit_to_32bit($id)
	{
	$result = substr($id, 3) - 61197960265728;
	return (string) $result;
	}
	function convert_steamid_32bit_to_64bit($id)
	{
	$result = '765'.($id + 61197960265728);
	return (string) $result;
	}

	$key = "FB222A5D99E6E9AD58EE7AAEFF57B9A7";
	$steamid = 76561197961028586;
	echo "<h2>SteamID : {$steamid}</h2>";
	$accountid = convert_steamid_64bit_to_32bit($steamid);
	echo "<h2>accountid : {$accountid}</h2>";

	// $curl = curl_init("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/?key={$key}&steamids={$steamid}");
	// $curl = curl_init("https://api.steampowered.com/IEconDOTA2_570/GetHeroes/v1?key={$key}");
	$curl = curl_init("https://api.steampowered.com/IDOTA2Match_570/GetMatchHistory/v1?key={$key}&account_id={$account_ids}");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$json = curl_exec($curl);
	$response = json_decode($json);
	foreach ($response["matches"] as $match) {
		echo $match->start_time;
	}

	var_dump($response);
	curl_close($curl);

	die();

	$key = "FB222A5D99E6E9AD58EE7AAEFF57B9A7";
	$steamids = "76561197961028586"; // 76561197961028586
	// $accountids = 76561197961028586 - 76561197960265728;


	// curlライブラリを使って送信します。
	$curl = curl_init("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2/?key={$key}&steamids={$steamids}");
	// $curl = curl_init("https://api.steampowered.com/IDOTA2Match_570/GetMatchHistory/v1?key={$key}&server_steam_id={$steamids}&account_id={$account_ids}");

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_exec($curl);

	// エラーがあればエラー内容を表示
	// if (curl_errno($curl)) {
	//     return array("code" => curl_errno($curl), "message" => "curl : " . curl_error($curl));
	// }

	$response = curl_multi_getcontent($curl);
	var_dump($response);

	curl_close($curl);

