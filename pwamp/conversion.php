<?php
if ( !defined('ABSPATH') )
{
	exit;
}

class PWAMPConversion
{
	public function __construct()
	{
	}

	public function __destruct()
	{
	}


	public function convert($page, $home_url, $data, $theme)
	{
		require_once plugin_dir_path(__FILE__) . 'transcoding.php';
		require_once plugin_dir_path(__FILE__) . 'themes.php';

		if ( is_plugin_active('pwamp-extension/pwamp.php') )
		{
			require_once plugin_dir_path(__FILE__) . '../../pwamp-extension/pwamp/extension.php';

			$transcoding = new PWAMPExtension();
		}
		else
		{
			$transcoding = new PWAMPThemes();
		}


		$transcoding->init($home_url, $data);


		$page = $transcoding->pretranscode_theme($page);

		if ( method_exists($transcoding, 'pretranscode_extension') )
		{
			$page = $transcoding->pretranscode_extension($page);
		}


		$page = $transcoding->transcode_html($page);


		$page = $transcoding->transcode_theme($page);

		if ( method_exists($transcoding, 'transcode_extension') )
		{
			$page = $transcoding->transcode_extension($page);
		}


		$page = $transcoding->transcode_head($page);


		$page = $transcoding->posttranscode_theme($page);

		if ( method_exists($transcoding, 'posttranscode_extension') )
		{
			$page = $transcoding->posttranscode_extension($page);
		}

		return $page;
	}
}
