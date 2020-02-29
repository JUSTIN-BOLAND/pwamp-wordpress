<?php
if ( !defined('ABSPATH') )
{
	exit;
}

class PWAMPThemes extends PWAMPTranscoding
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
	}


	public function pretranscode_theme($page)
	{
		return $page;
	}

	public function transcode_theme($page)
	{
		return $page;
	}

	public function posttranscode_theme($page)
	{
		return $page;
	}
}
