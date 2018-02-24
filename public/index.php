<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
include_once("../vendor/autoload.php");

session_start();
if (!isset($_SESSION['steam_id'])) {
	// Require sign in.
	echo "
	<div style='text-align:center'>
		<p><a href='/auth.php'><img src='http://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_02.png' width='109' height='66' border='0'></a></p>
		<p>Please sign in.</p>
	</div>
	";
} else {

	// https://gist.github.com/almirsarajcic/4664387
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

	$key = "YOUR_API_KEY"; // Set Your API key here.
	$steamid = $_SESSION['steam_id'];	// Set target's steam id here.
	echo "<h2>SteamID : {$steamid}</h2>";
	$accountid = convert_steamid_64bit_to_32bit($steamid);
	echo "<h2>accountid : {$accountid}</h2>";

	$curl = curl_init();
	
	// ヒーローリストを取得する
	curl_setopt($curl, CURLOPT_URL, "https://api.steampowered.com/IEconDOTA2_570/GetHeroes/v1?key={$key}");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$json = curl_exec($curl);
	$response = json_decode($json);
	$heroes = [];
	foreach ($response->result->heroes as $hero) {
		$heroes[$hero->id] = str_replace("npc_dota_hero_", "", $hero->name);
	}

	// 対戦履歴を取得する
	curl_setopt($curl, CURLOPT_URL, "https://api.steampowered.com/IDOTA2Match_570/GetMatchHistory/v1?key={$key}&account_id={$accountid}");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$json = curl_exec($curl);
	$response = json_decode($json);

	curl_close($curl);

	// 対戦履歴一覧を表示する
	foreach ($response->result->matches as $match) {
		$start_time = new DateTime('@' . $match->start_time);
		foreach ($match->players as $player) {
			if ($player->account_id == $accountid) {
				$hero_name = $heroes[$player->hero_id];
				$img = "<img src='http://cdn.dota2.com/apps/dota2/images/heroes/{$hero_name}_sb.png'>";
			}
		}

		echo "
		<div>
		{$start_time->format('Y/m/d H:i:s')}
		{$img}
		</div>
		";
	}

}