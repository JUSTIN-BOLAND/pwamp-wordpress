<?php
if ( !defined('ABSPATH') )
{
	exit;
}

require_once plugin_dir_path(__FILE__) . 'lib.php';

class PWAMPTranscoding
{
	public function __construct()
	{
	}

	public function __destruct()
	{
	}


	public function transcode($page, $home_url, $data, $theme)
	{
		$lib = new PWAMPLibrary();

		if ( is_plugin_active('pwamp-extension/pwamp.php') )
		{
			require_once plugin_dir_path(__FILE__) . '../../pwamp-extension/pwamp/extension.php';

			$extension = new PWAMPExtension();
		}

		$lib->init($home_url, $data);

		if ( !empty($extension) )
		{
			$style_list = $extension->get_style_list();
			$lib->set_style_list($style_list);

			$image_list = $extension->get_image_list();
			$lib->set_image_list($image_list);

			$page = $extension->pretranscode($page);
		}

		$page = $lib->transcode_html($page, $theme);

		if ( !empty($extension) )
		{
			$page = $extension->transcode($page);
		}

		$page = $lib->transcode_head($page);

		if ( !empty($extension) )
		{
			$page = $extension->posttranscode($page);
		}

		return $page;
	}
}
