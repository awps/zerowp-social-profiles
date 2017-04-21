<?php
namespace SocialProfiles;

class TestIcons{

	public function __construct(){
		add_action( 'theme:before_articles_container', array( $this, 'test' ) );
	}
	
	public function test() {
		$brands = ZSP()->brands;

		foreach ($brands as $brand => $v) {
			$name = !empty( $v[1] ) ? esc_attr( $v[1] ) : ucfirst( $brand );

			echo "<span  class='zsp-{$brand}' title='{$name}'><i></i></span>";
		}

		// $out = '';
		// foreach ($brands as $brand => $v) {
		// 	$name = !empty( $v[1] ) ? esc_attr( $v[1] ) : ucfirst( $brand );

		// 	$out .= ".zsp-block-{$brand}{ background: {$v[0]}; }<br>";
		// }

		// echo '<pre>';
		// print_r( $out );
		// echo '</pre>';

// 		$brands = [
// 			'tunein'          => "903",
// 			'gamejolt'        => "901",
// 			'trello'          => "902",
// 			'spreadshirt'     => "900",
// 			'500px'           => "000",
// 			'8tracks'         => "001",
// 			'airbnb'          => "002",
// 			'alliance'        => "003",
// 			'amazon'          => "004",
// 			'amplement'       => "005",
// 			'android'         => "006",
// 			'angellist'       => "007",
// 			'apple'           => "008",
// 			'appnet'          => "009",
// 			'baidu'           => "00a",
// 			'bandcamp'        => "00b",
// 			'battlenet'       => "00c",
// 			'beam'            => "00d",
// 			'bebee'           => "00e",
// 			'bebo'            => "00f",
// 			'behance'         => "010",
// 			'blizzard'        => "011",
// 			'blogger'         => "012",
// 			'buffer'          => "013",
// 			'chrome'          => "014",
// 			'coderwall'       => "015",
// 			'curse'           => "016",
// 			'dailymotion'     => "017",
// 			'deezer'          => "018",
// 			'delicious'       => "019",
// 			'deviantart'      => "01a",
// 			'diablo'          => "01b",
// 			'digg'            => "01c",
// 			'discord'         => "01d",
// 			'disqus'          => "01e",
// 			'douban'          => "01f",
// 			'draugiem'        => "020",
// 			'dribbble'        => "021",
// 			'drupal'          => "022",
// 			'ebay'            => "023",
// 			'ello'            => "024",
// 			'endomodo'        => "025",
// 			'envato'          => "026",
// 			'etsy'            => "027",
// 			'facebook'        => "028",
// 			'feedburner'      => "029",
// 			'filmweb'         => "02a",
// 			'firefox'         => "02b",
// 			'flattr'          => "02c",
// 			'flickr'          => "02d",
// 			'formulr'         => "02e",
// 			'forrst'          => "02f",
// 			'foursquare'      => "030",
// 			'friendfeed'      => "031",
// 			'github'          => "032",
// 			'goodreads'       => "033",
// 			'google'          => "034",
// 			'googlescholar'   => "035",
// 			'googlegroups'    => "036",
// 			'googlephotos'    => "037",
// 			'googleplus'      => "038",
// 			'grooveshark'     => "039",
// 			'hackerrank'      => "03a",
// 			'hearthstone'     => "03b",
// 			'hellocoton'      => "03c",
// 			'heroes'          => "03d",
// 			'hitbox'          => "03e",
// 			'horde'           => "03f",
// 			'houzz'           => "040",
// 			'icq'             => "041",
// 			'identica'        => "042",
// 			'imdb'            => "043",
// 			'instagram'       => "044",
// 			'issuu'           => "045",
// 			'istock'          => "046",
// 			'itunes'          => "047",
// 			'keybase'         => "048",
// 			'lanyrd'          => "049",
// 			'lastfm'          => "04a",
// 			'line'            => "04b",
// 			'linkedin'        => "04c",
// 			'livejournal'     => "04d",
// 			'lyft'            => "04e",
// 			'macos'           => "04f",
// 			'mail'            => "050",
// 			'medium'          => "051",
// 			'meetup'          => "052",
// 			'mixcloud'        => "053",
// 			'modelmayhem'     => "054",
// 			'mumble'          => "055",
// 			'myspace'         => "056",
// 			'newsvine'        => "057",
// 			'nintendo'        => "058",
// 			'npm'             => "059",
// 			'odnoklassniki'   => "05a",
// 			'openid'          => "05b",
// 			'opera'           => "05c",
// 			'outlook'         => "05d",
// 			'overwatch'       => "05e",
// 			'patreon'         => "05f",
// 			'paypal'          => "060",
// 			'periscope'       => "061",
// 			'persona'         => "062",
// 			'pinterest'       => "063",
// 			'play'            => "064",
// 			'player'          => "065",
// 			'playstation'     => "066",
// 			'pocket'          => "067",
// 			'qq'              => "068",
// 			'quora'           => "069",
// 			'raidcall'        => "06a",
// 			'ravelry'         => "06b",
// 			'reddit'          => "06c",
// 			'renren'          => "06d",
// 			'researchgate'    => "06e",
// 			'residentadvisor' => "06f",
// 			'reverbnation'    => "070",
// 			'rss'             => "071",
// 			'sharethis'       => "072",
// 			'skype'           => "073",
// 			'slideshare'      => "074",
// 			'smugmug'         => "075",
// 			'snapchat'        => "076",
// 			'songkick'        => "077",
// 			'soundcloud'      => "078",
// 			'spotify'         => "079",
// 			'stackexchange'   => "07a",
// 			'stackoverflow'   => "07b",
// 			'starcraft'       => "07c",
// 			'stayfriends'     => "07d",
// 			'steam'           => "07e",
// 			'storehouse'      => "07f",
// 			'strava'          => "080",
// 			'streamjar'       => "081",
// 			'stumbleupon'     => "082",
// 			'swarm'           => "083",
// 			'teamspeak'       => "084",
// 			'teamviewer'      => "085",
// 			'technorati'      => "086",
// 			'telegram'        => "087",
// 			'tripadvisor'     => "088",
// 			'tripit'          => "089",
// 			'triplej'         => "08a",
// 			'tumblr'          => "08b",
// 			'twitch'          => "08c",
// 			'twitter'         => "08d",
// 			'uber'            => "08e",
// 			'ventrilo'        => "08f",
// 			'viadeo'          => "090",
// 			'viber'           => "091",
// 			'viewbug'         => "092",
// 			'vimeo'           => "093",
// 			'vine'            => "094",
// 			'vkontakte'       => "095",
// 			'warcraft'        => "096",
// 			'wechat'          => "097",
// 			'weibo'           => "098",
// 			'whatsapp'        => "099",
// 			'wikipedia'       => "09a",
// 			'windows'         => "09b",
// 			'wordpress'       => "09c",
// 			'wykop'           => "09d",
// 			'xbox'            => "09e",
// 			'xing'            => "09f",
// 			'yahoo'           => "0a0",
// 			'yammer'          => "0a1",
// 			'yandex'          => "0a2",
// 			'yelp'            => "0a3",
// 			'younow'          => "0a4",
// 			'youtube'         => "0a5",
// 			'zapier'          => "0a6",
// 			'zerply'          => "0a7",
// 			'zomato'          => "0a8",
// 			'zynga'           => "0a9",
// 		];

// 		ksort( $brands );
// 		$all = ZSP()->brands;

// 		$out = '';
// 		foreach ($brands as $brand => $unicode) {
// 			$out .= ".zsp-{$brand}{ 
// 	background: \"" . $all[ $brand ][0] . "\"; 
// 	i:before{ content: \"\\e" . $unicode . "\"; }
// }<br>";
// 			// $out .= ".zsp-{$brand} > span:before{ content: \"\\e" . $unicode . "\"; }<br>";
// 		}

// 		echo '<pre>';
// 		print_r( $out );
// 		echo '</pre>';
	}

}