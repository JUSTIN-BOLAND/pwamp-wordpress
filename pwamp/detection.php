<?php
if ( !defined('ABSPATH') )
{
	exit;
}

/*
$device_list = array(
	'Android' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; U; Android 2.1-update1; en-us; ADR6300 Build/ERE27) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 Mobile Safari/530.17',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Android Honeycomb' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; U; Android 3.0; en-us; Xoom Build/HRI39) AppleWebKit/534.13 (KHTML, like Gecko) Version/4.0 Safari/534.13',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'tablet'
	),
	'Bingbot' => array(
		'input' => array(
			'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop-bot'
	),
	'BlackBerry 4.x' => array(
		'input' => array(
			'BlackBerry8300/4.2.2 Profile/MIDP-2.0 Configuration/CLDC-1.1 VendorID/107UP.Link/6.2.3.15.0',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'BlackBerry 5.0' => array(
		'input' => array(
			'BlackBerry9550/5.0.0.320 Profile/MIDP-2.1 Configuration/CLDC-1.1 VendorID/105',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'BlackBerry 6.0' => array(
		'input' => array(
			'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en-US) AppleWebKit/534.1+ (KHTML, like Gecko) Version/6.0.0.141 Mobile Safari/534.1+',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'BlackBerry 7.0' => array(
		'input' => array(
			'BlackBerry; U; BlackBerry 9810; xx-xx) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.0.0.313 Mobile Safari/534.11+',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'BlackBerry PlayBook' => array(
		'input' => array(
			'Mozilla/5.0 (PlayBook; U; RIM Tablet OS 2.1.0; en-US) AppleWebKit/536.2+ (KHTML like Gecko) Version/7.2.1.0 Safari/536.2+',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'tablet'
	),
	'BlackBerry Z30' => array(
		'input' => array(
			'Mozilla/5.0 (BB10; Touch) AppleWebKit/537.10+ (KHTML, like Gecko) Version/10.0.9.2372 Mobile Safari/537.10+',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Chrome Desktop 11' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.71 Safari/534.24',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Chrome Desktop 12' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.60 Safari/534.30',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Chrome Desktop 13' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.220 Safari/535.1',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Chrome Desktop 14' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.202 Safari/535.1',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Chrome Desktop 15' => array(
		'input' => array(
			'Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.51 Safari/535.2',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Chrome Desktop 16' => array(
		'input' => array(
			'Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/16.0.874.51 Safari/535.2',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Chrome Desktop 17' => array(
		'input' => array(
			'Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/17.0.874.51 Safari/535.2',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Chrome Desktop 18' => array(
		'input' => array(
			'Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/18.0.874.51 Safari/535.2',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Chrome Desktop 19' => array(
		'input' => array(
			'Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/19.0.874.51 Safari/535.2',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Chrome Desktop 20' => array(
		'input' => array(
			'Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/20.0.874.51 Safari/535.2',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Chrome Desktop 21' => array(
		'input' => array(
			'Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/21.0.874.51 Safari/535.2',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Chrome Desktop 22' => array(
		'input' => array(
			'Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/22.0.874.51 Safari/535.2',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Firefox Desktop 4' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 5.1; rv:2.0) Gecko/20100101 Firefox/4.0',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Firefox Desktop 5' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Firefox Desktop 6' => array(
		'input' => array(
			'Mozilla/5.0 (X11; Linux i686; rv:6.0.2) Gecko/20100101 Firefox/6.0.2',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Firefox Desktop 7' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 5.1; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Firefox Desktop 8' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 5.1; rv:8.0) Gecko/20100101 Firefox/8.0',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Firefox Desktop 9' => array(
		'input' => array(
			'Mozilla/5.0 (Ubuntu; X11; Linux x86_64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Firefox Desktop 10' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0) Gecko/20100101 Firefox/10.0',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Firefox Desktop 11' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 6.1; rv:11.0) Gecko/20100101 Firefox/11.0',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Firefox Desktop 12' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 6.0; rv:12.0) Gecko/20100101 Firefox/12.0',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Firefox Desktop 13' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 6.0; rv:13.0) Gecko/20100101 Firefox/13.0',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Firefox Desktop 14' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 6.0; rv:14.0) Gecko/20100101 Firefox/14.0',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Firefox Desktop 15' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 5.1; rv:15.0) Gecko/20100101 Firefox/15.0.1',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Firefox Desktop 16' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 5.1; rv:16.0) Gecko/20100101 Firefox/16.0.1',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Galaxy Fold' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; Android 8.0; Pixel 2 Build/OPD3.170816.012) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Mobile Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Galaxy Note 3' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; U; Android 4.3; en-us; SM-N900T Build/JSS15J) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Galaxy Note II' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; U; Android 4.1; en-us; GT-N7100 Build/JRO03C) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Galaxy S III' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; U; Android 4.0; en-us; GT-I9300 Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Galaxy S5' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Mobile Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Googlebot' => array(
		'input' => array(
			'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop-bot'
	),
	'Googlebot Smartphone' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.96 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'mobile-bot'
	),
	'Internet Explorer 7' => array(
		'input' => array(
			'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; GTB6.4; .NET CLR 1.1.4322; FDM; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Internet Explorer 8' => array(
		'input' => array(
			'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; chromeframe/13.0.782.218; chromeframe; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Internet Explorer 9' => array(
		'input' => array(
			'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'iPad' => array(
		'input' => array(
			'Mozilla/5.0 (iPad; CPU OS 11_0 like Mac OS X) AppleWebKit/604.1.34 (KHTML, like Gecko) Version/11.0 Mobile/15A5341f Safari/604.1',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'tablet'
	),
	'iPad 2' => array(
		'input' => array(
			'Mozilla/5.0 (iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.10',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'tablet'
	),
	'iPad Mini' => array(
		'input' => array(
			'Mozilla/5.0 (iPad; CPU OS 11_0 like Mac OS X) AppleWebKit/604.1.34 (KHTML, like Gecko) Version/11.0 Mobile/15A5341f Safari/604.1',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'tablet'
	),
	'iPad Pro' => array(
		'input' => array(
			'Mozilla/5.0 (iPad; CPU OS 11_0 like Mac OS X) AppleWebKit/604.1.34 (KHTML, like Gecko) Version/11.0 Mobile/15A5341f Safari/604.1',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'tablet'
	),
	'iPhone 4' => array(
		'input' => array(
			'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1_2 like Mac OS X) AppleWebKit/537.51.2 (KHTML, like Gecko) Version/7.0 Mobile/11D257 Safari/9537.53',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'iPhone 5/SE' => array(
		'input' => array(
			'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'iPhone 6/7/8' => array(
		'input' => array(
			'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'iPhone 6/7/8 Plus' => array(
		'input' => array(
			'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'iPhone X' => array(
		'input' => array(
			'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'iPod' => array(
		'input' => array(
			'Mozilla/5.0 (iPod; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3A101a Safari/419.3',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'JioPhone 2' => array(
		'input' => array(
			'Mozilla/5.0 (Mobile; LYF/F300B/LYF-F300B-001-01-15-130718-i;Android; rv:48.0) Gecko/48.0 Firefox/48.0 KAIOS/2.5',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Kindle 3' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; U; en-US) AppleWebKit/528.5+ (KHTML, like Gecko, Safari/538.5+) Version/4.0 Kindle/3.0 (screen 600x800; rotate)',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'tablet'
	),
	'Kindle Fire' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; U; Android 2.3.4; en-us; Kindle Fire Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'tablet'
	),
	'Kindle Fire HDX' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; U; en-us; KFAPWI Build/JDQ39) AppleWebKit/535.19 (KHTML, like Gecko) Silk/3.13 Safari/535.19 Silk-Accelerated=true',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'tablet'
	),
	'Laptop with HiDPI screen' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Laptop with MDPI screen' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Laptop with touch' => array(
		'input' => array(
			'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'LG Optimus L70' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; U; Android 4.4.2; en-us; LGMS323 Build/KOT49I.MS32310c) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/85.0.4183.121 Mobile Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Macintosh' => array(
		'input' => array(
			'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:15.0) Gecko/20100101 Firefox/15.0.1',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Meego 1.2' => array(
		'input' => array(
			'Mozilla/5.0 (MeeGo; NokiaN950-00/00) AppleWebKit/534.13 (KHTML, like Gecko) NokiaBrowser/8.5.0 Mobile Safari/534.13',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Microsoft Lumia 550' => array(
		'input' => array(
			'Mozilla/5.0 (Windows Phone 10.0; Android 4.2.1; Microsoft; Lumia 550) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Mobile Safari/537.36 Edge/14.14263',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Microsoft Lumia 950' => array(
		'input' => array(
			'Mozilla/5.0 (Windows Phone 10.0; Android 4.2.1; Microsoft; Lumia 950) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Mobile Safari/537.36 Edge/14.14263',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Moto G4' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; Android 6.0.1; Moto G (4)) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Mobile Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Nexus 10' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 10 Build/MOB31T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'tablet'
	),
	'Nexus 4' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; Android 4.4.2; Nexus 4 Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Mobile Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Nexus 5' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Mobile Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Nexus 5X' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; Android 8.0.0; Nexus 5X Build/OPR4.170623.006) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Mobile Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Nexus 6' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; Android 7.1.1; Nexus 6 Build/N6F26U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Mobile Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Nexus 6P' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; Android 8.0.0; Nexus 6P Build/OPP3.170518.006) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Mobile Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Nexus 7' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 7 Build/MOB30X) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'tablet'
	),
	'Nokia Lumia 520' => array(
		'input' => array(
			'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; NOKIA; Lumia 520)',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Nokia N9' => array(
		'input' => array(
			'Mozilla/5.0 (MeeGo; NokiaN9) AppleWebKit/534.13 (KHTML, like Gecko) NokiaBrowser/8.5.0 Mobile Safari/534.13',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Nokia Symbian^3' => array(
		'input' => array(
			'Mozilla/5.0 (Symbian/3; Series60/5.2 NokiaN8-00/013.016; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/525 (KHTML, like Gecko) Version/3.0 BrowserNG/7.2.8.10 3gpp-gba',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Opera Desktop 10' => array(
		'input' => array(
			'Opera/9.80 (X11; Linux x86_64; U; en) Presto/2.2.15 Version/10.00',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Opera Desktop 11' => array(
		'input' => array(
			'Opera/9.80 (X11; Linux x86_64; U; pl) Presto/2.7.62 Version/11.00',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop'
	),
	'Opera Mini 5.0' => array(
		'input' => array(
			'Opera/9.80 (J2ME/MIDP; Opera Mini/5.0/870; U; en) Presto/2.4.15',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Opera Mini 6.0' => array(
		'input' => array(
			'Opera/9.80 (Series 60; Opera Mini/6.0.24095/24.760; U; en) Presto/2.5.25 Version/10.54',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Opera Mobile' => array(
		'input' => array(
			'Opera/9.80 (S60; SymbOS; Opera Mobi/SYB-1104061449; U; da) Presto/2.7.81 Version/11.00',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Palm WebOS 1.4' => array(
		'input' => array(
			'Mozilla/5.0 (webOS/1.4.0; U; en-US) AppleWebKit/532.2 (KHTML, like Gecko) Version/1.0 Safari/532.2 Pixi/1.1',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Palm WebOS 2.0' => array(
		'input' => array(
			'Mozilla/5.0 (webOS/2.1.0; U; xx-xx) AppleWebKit/532.2 (KHTML, like Gecko) Version/1.0 Safari/532.2 Pre/1.2',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Palm WebOS 3.0' => array(
		'input' => array(
			'Mozilla/5.0 (hp-tablet; Linux; hpwOS/3.0.2; U; en-US) AppleWebKit/534.6 (KHTML, like Gecko) wOSBrowser/234.40.1 Safari/534.6 TouchPad/1.0',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'tablet'
	),
	'Pixel 2' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; Android 8.0; Pixel 2 Build/OPD3.170816.012) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Mobile Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Pixel 2 XL' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; Android 8.0.0; Pixel 2 XL Build/OPD1.170816.004) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Mobile Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Surface Duo' => array(
		'input' => array(
			'Mozilla/5.0 (Linux; Android 8.0; Pixel 2 Build/OPD3.170816.012) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Mobile Safari/537.36',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Windows Phone' => array(
		'input' => array(
			'Mozilla/4.0 (compatible; MSIE 4.01; Windows CE; PPC; 240x320)',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Windows Phone 7.x' => array(
		'input' => array(
			'Mozilla/4.0 (compatible: MSIE 7.0; Windows Phone OS 7.0; Trident/3.1; IEMobile/7.0; HTC; 7 Trophy)',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'smartphone'
	),
	'Yahoo! Slurp' => array(
		'input' => array(
			'Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)',
			'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
			''
		),
		'output' => 'desktop-bot'
	),
	'application/vnd.wap.xhtml+xml' => array(
		'input' => array(
			'abcdefg hijklmn opqrst uvwxyz',
			'application/vnd.wap.xhtml+xml',
			''
		),
		'output' => 'feature-phone'
	),
	'http_profile' => array(
		'input' => array(
			'abcdefg hijklmn opqrst uvwxyz',
			'abcdefg hijklmn opqrst uvwxyz',
			'whatever'
		),
		'output' => 'feature-phone'
	),
	'unrecognized' => array(
		'input' => array(
			'abcdefg hijklmn opqrst uvwxyz',
			'abcdefg hijklmn opqrst uvwxyz',
			''
		),
		'output' => 'feature-phone'
	)
);


$user_agent = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
$accept = !empty($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';
$profile = !empty($_SERVER['HTTP_PROFILE']) ? $_SERVER['HTTP_PROFILE'] : '';

echo 'User-Agent: ' . $user_agent . '<br />' . "\n";
echo 'Accept: ' . $accept . '<br />' . "\n";
echo 'Profile: ' . $profile . '<br />' . "\n";


$detection = new PWAMPDetection();

foreach ( $device_list as $key => $value )
{
	echo $key . '<br />' . "\n";

	list($user_agent, $accept, $profile) = $value['input'];
	$device = $detection->get_device($user_agent, $accept, $profile);

	if ( $device != $value['output'] )
	{
		break;
	}
}

if ( $device != $value['output'] )
{
	echo 'Expect: '. $value['output'] . '; Return: '. $device . '<br />' . "\n";
	echo 'Failed.' . '<br />' . "\n";
}
else
{
	echo 'Passed.' . '<br />' . "\n";
}
*/


class PWAMPDetection
{
	private $device_list = array(
		// Google Search
		'Mobile*Googlebot' => 'mobile-bot',
		'Googlebot' => 'desktop-bot',


		// Apple
		'iPad' => 'tablet',
		'iPhone' => 'smartphone',
		'iPod' => 'smartphone',


		// Kindle Fire
		'Kindle' => 'tablet',
		'KFAPWI' => 'tablet',


		// Android
		'Android*Mobile' => 'smartphone',
		'Mobile*Android' => 'smartphone',
		'Android' => 'tablet',


		// Chrome
		'Chrome' => 'desktop',


		// BlackBerry
		'BlackBerry' => 'smartphone',
		'BB10' => 'smartphone',
		'PlayBook' => 'tablet',


		// Firefox
		'Firefox' => 'desktop',


		// Meego
		'MeeGo' => 'smartphone',


		// Nexus
		'Nexus' => 'tablet',


		// Nokia Symbian
		'Symbian' => 'smartphone',


		// Opera
		'Opera Mobi' => 'smartphone',
		'Opera Mini' => 'smartphone',
		'Opera' => 'desktop',


		// Palm WebOS
		'webOS' => 'smartphone',
		'TouchPad' => 'tablet',


		// Windows
		'Windows Phone' => 'smartphone',
		'Windows CE' => 'smartphone',
		'Windows NT' => 'desktop',
		'MSIE' => 'desktop',
		'bingbot' => 'desktop-bot',


		// Yahoo!
		'Yahoo! Slurp' => 'desktop-bot'
	);

	private $accept_list = array(
		// application/vnd.wap.xhtml+xml
		'application/vnd.wap.xhtml+xml' => 'feature-phone'
	);


	public function __construct()
	{
	}

	public function __destruct()
	{
	}


	public function get_device($user_agent, $accept, $profile)
	{
		if ( !empty($user_agent) )
		{
			foreach ( $this->device_list as $key => $value )
			{
				if ( preg_match('#' . str_replace('\*', '.*?', preg_quote($key, '#')) . '#i', $user_agent) )
				{
					return $value;
				}
			}
		}

		if ( !empty($accept) )
		{
			foreach ( $this->accept_list as $key => $value )
			{
				if ( preg_match('#' . str_replace('\*', '.*?', preg_quote($key, '#')) . '#i', $accept) )
				{
					return $value;
				}
			}
		}

		if ( !empty($profile) )
		{
			return 'feature-phone';
		}

		if ( !empty($user_agent) )
		{
			return 'feature-phone';
		}

		return 'mobile';
	}
}
