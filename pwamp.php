<?php
/*
Plugin Name: PWA+AMP
Plugin URI:  https://flexplat.com/pwamp-wordpress/
Description: Converts WordPress into Progressive Web Apps and Accelerated Mobile Pages styles.
Version:     4.2.0
Author:      Rickey Gu
Author URI:  https://flexplat.com
Text Domain: pwamp
Domain Path: /languages
*/

if ( !defined('ABSPATH') )
{
	exit;
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

class PWAMP
{
	private $time = 0;

	private $page = '';

	private $home_url = '';
	private $theme = '';

	private $page_url = '';
	private $permalink = '';
	private $viewport_width = '';
	private $plugin_dir_url = '';

	private $plugin_dir = '';
	private $plugin_dir_path = '';


	public function __construct()
	{
	}

	public function __destruct()
	{
	}


	private function init()
	{
		$this->time = time();

		$this->page = '';

		$this->home_url = home_url();
		$this->theme = get_option('template');

		$parts = parse_url($this->home_url);
		$this->page_url = $parts['scheme'] . '://' . $parts['host'] . add_query_arg();
		$this->permalink = get_option('permalink_structure');
		$this->viewport_width = !empty($_COOKIE['pwamp_viewport_width']) ? $_COOKIE['pwamp_viewport_width'] : '';
		$this->plugin_dir_url = plugin_dir_url(__FILE__);

		$pattern = str_replace(array('/', '.'), array('\/', '\.'), $this->home_url);
		$this->plugin_dir = preg_replace('/^' . $pattern . '(.+)\/$/im', '${1}', $this->plugin_dir_url);
		$this->plugin_dir_path = plugin_dir_path(__FILE__);
	}

	private function divert()
	{
		$pattern = str_replace(array('/', '.'), array('\/', '\.'), $this->home_url);
		if ( preg_match('/^' . $pattern . '\/\??manifest\.webmanifest$/im', $this->page_url) )
		{
			header('Content-Type: application/x-web-app-manifest+json', true);
			echo '{
	"name": "' . get_bloginfo('name') . ' &#8211; ' . get_bloginfo('description') . '",
	"short_name": "' . get_bloginfo('name') . '",
	"start_url": "' . $this->home_url . '",
	"icons": [{
		"src": ".' . $this->plugin_dir . '/pwamp/mf/mf-logo-192.png",
		"sizes": "192x192",
		"type": "image/png"
	}, {
		"src": ".' . $this->plugin_dir . '/pwamp/mf/mf-logo-512.png",
		"sizes": "512x512",
		"type": "image/png"
	}],
	"theme_color": "#ffffff",
	"background_color": "#ffffff",
	"display": "standalone"
}';

			exit();
		}
		elseif ( preg_match('/^' . $pattern . '\/\??pwamp-sw\.html$/im', $this->page_url) )
		{
			header('Content-Type: text/html; charset=utf-8', true);
			echo '<!doctype html>
<html>
<head>
<title>Installing service worker...</title>
<script type=\'text/javascript\'>
	var swsource = \'' . $this->home_url . '/' . ( empty($this->permalink) ? '?' : '' ) . 'pwamp-sw.js\';
	if ( \'serviceWorker\' in navigator ) {
		navigator.serviceWorker.register(swsource).then(function(reg) {
			console.log(\'ServiceWorker scope: \', reg.scope);
		}).catch(function(err) {
			console.log(\'ServiceWorker registration failed: \', err);
		});
	};
</script>
</head>
<body>
</body>
</html>';

			exit();
		}
		elseif ( preg_match('/^' . $pattern . '\/\??pwamp-sw\.js$/im', $this->page_url) )
		{
			header('Content-Type: application/javascript', true);
			echo 'importScripts(\'.' . $this->plugin_dir . '/pwamp/sw/sw-toolbox.js\');
toolbox.router.default = toolbox.cacheFirst;';

			exit();
		}
		elseif ( preg_match('/^' . $pattern . '\/\?pwamp-viewport-width=(\d+)$/im', $this->page_url, $match) )
		{
			$this->viewport_width = $match[1];

			setcookie('pwamp_viewport_width', $this->viewport_width, $this->time + 60*60*24*365, COOKIEPATH, COOKIE_DOMAIN);

			exit();
		}
	}


	private function get_amphtml()
	{
		$parts = parse_url($this->home_url);
		$args = array('desktop' => false, 'amp' => '1');
		$amphtml = $parts['scheme'] . '://' . $parts['host'] . add_query_arg($args);
		$amphtml = htmlspecialchars($amphtml);

		return $amphtml;
	}

	private function get_canonical()
	{
		$parts = parse_url($this->home_url);
		$args = array('amp' => false, 'desktop' => '1');
		$canonical = $parts['scheme'] . '://' . $parts['host'] . add_query_arg($args);
		$canonical = htmlspecialchars($canonical);

		return $canonical;
	}


	public function add_amphtml()
	{
		$amphtml = $this->get_amphtml();

		echo '<link rel="amphtml" href="' . $amphtml . '" />' . "\n";
	}

	public function add_notification_bar()
	{
		$amphtml = $this->get_amphtml();

		echo "\n" . '<script>
	var pwamp_notification_toggle = function() {
		var e = document.getElementById(\'pwamp-notification\');
		if ( e.style.display === \'flex\' || e.style.display === \'\' ) {
			e.style.display = \'none\';
		} else {
			e.style.display = \'flex\'
		}
	}
</script>
<div style="position:fixed!important;bottom:0;left:0;overflow:hidden!important;background:hsla(0,0%,100%,0.7);z-index:1000;width:100%">
	<div id="pwamp-notification" style="display:flex;align-items:center;justify-content:center">' . __('Switch to', 'pwamp') . '&nbsp;<a href="' . $amphtml . '">' . __('mobile version', 'pwamp') . '</a>&nbsp;&nbsp;<input type="button" value="' . __('Continue', 'pwamp') . '" style="min-width:80px" onclick="pwamp_notification_toggle();" /></div>
</div>';
	}


	private function get_page_type()
	{
		global $wp_query;

		$page_type = '';
		if ( $wp_query->is_page )
		{
			$page_type = is_front_page() ? 'front' : 'page';
		}
		elseif ( $wp_query->is_home )
		{
			$page_type = 'home';
		}
		elseif ( $wp_query->is_single )
		{
			$page_type = ( $wp_query->is_attachment ) ? 'attachment' : 'single';
		}
		elseif ( $wp_query->is_category )
		{
			$page_type = 'category';
		}
		elseif ( $wp_query->is_tag )
		{
			$page_type = 'tag';
		}
		elseif ( $wp_query->is_tax )
		{
			$page_type = 'tax';
		}
		elseif ( $wp_query->is_archive )
		{
			if ( $wp_query->is_day )
			{
				$page_type = 'day';
			}
			elseif ( $wp_query->is_month )
			{
				$page_type = 'month';
			}
			elseif ( $wp_query->is_year )
			{
				$page_type = 'year';
			}
			elseif ( $wp_query->is_author )
			{
				$page_type = 'author';
			}
			else
			{
				$page_type = 'archive';
			}
		}
		elseif ( $wp_query->is_search )
		{
			$page_type = 'search';
		}
		elseif ( $wp_query->is_404 )
		{
			$page_type = 'notfound';
		}

		return $page_type;
	}

	private function get_device()
	{
		$user_agent = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$accept = !empty($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';
		$profile = !empty($_SERVER['HTTP_PROFILE']) ? $_SERVER['HTTP_PROFILE'] : '';

		$detection = new PWAMPDetection();

		$device = $detection->get_device($user_agent, $accept, $profile);

		return $device;
	}

	private function transcode_page($page)
	{
		$page = preg_replace('/^[\s\t]*<style type="[^"]+" id="[^"]+"><\/style>$/im', '', $page);

		$language = array(
			'continue' => __('Continue', 'pwamp'),
			'desktop_version' => __('desktop version', 'pwamp'),
			'switch_to' => __('Switch to', 'pwamp')
		);

		$data = array(
			'page_url' => $this->page_url,
			'canonical' => $this->get_canonical(),
			'permalink' => $this->permalink,
			'page_type' => $this->get_page_type(),
			'viewport_width' => $this->viewport_width,
			'plugin_dir_url' => $this->plugin_dir_url,
			'language' => $language
		);

		$conversion = new PWAMPConversion();

		$page = $conversion->convert($page, $this->home_url, $data, $this->theme);

		return $page;
	}


	private function catch_page_callback($page)
	{
		$this->page .= $page;
	}

	public function after_setup_theme()
	{
		if ( empty($_COOKIE['pwamp_message']) )
		{
			ob_start(array($this, 'catch_page_callback'));

			return;
		}


		$message = $_COOKIE['pwamp_message'];
		setcookie('pwamp_message', '', $this->time - 1, COOKIEPATH, COOKIE_DOMAIN);

		$title = '';
		if ( !empty($_COOKIE['pwamp_title']) )
		{
			$title = $_COOKIE['pwamp_title'];
			setcookie('pwamp_title', '', $this->time - 1, COOKIEPATH, COOKIE_DOMAIN);
		}

		$args = array();
		if ( !empty($_COOKIE['pwamp_args']) )
		{
			$args = json_decode(stripslashes($_COOKIE['pwamp_args']));
			setcookie('pwamp_args', '', $this->time - 1, COOKIEPATH, COOKIE_DOMAIN);
		}

		_default_wp_die_handler($message, $title, $args);
	}

	public function shutdown()
	{
		$page = $this->transcode_page($this->page);
		if ( empty($page) )
		{
			echo $this->page;

			return;
		}

		echo $page;
	}


	private function json_redirect($redirection)
	{
		$parts = parse_url($this->home_url);
		$host_url = $parts['scheme'] . '://' . $parts['host'];

		header('Content-type: application/json');
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Allow-Origin: *.ampproject.org');
		header('Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin');
		header('AMP-Access-Control-Allow-Source-Origin: ' . $host_url);
		header('AMP-Redirect-To: ' . $redirection);

		$output = [];
		echo json_encode($output);

		exit();
	}

	public function comment_post_redirect($location, $comment)
	{
		$status = 302;

		$location = wp_sanitize_redirect($location);
		$location = wp_validate_redirect($location, apply_filters('wp_safe_redirect_fallback', admin_url(), $status));

		$location = apply_filters('wp_redirect', $location, $status);
		$status = apply_filters('wp_redirect_status', $status, $location);

		$this->json_redirect($location);
	}

	public function die_handler($message, $title = '', $args = array())
	{
		if ( $title !== 'Comment Submission Failure' )
		{
			_default_wp_die_handler($message, $title, $args);

			return;
		}


		setcookie('pwamp_message', $message, $this->time + 60, COOKIEPATH, COOKIE_DOMAIN);

		if ( !empty($title) )
		{
			setcookie('pwamp_title', $title, $this->time + 60, COOKIEPATH, COOKIE_DOMAIN);
		}
		else
		{
			setcookie('pwamp_title', '', $this->time - 1, COOKIEPATH, COOKIE_DOMAIN);
		}

		if ( !empty($args) )
		{
			setcookie('pwamp_args', json_encode($args), $this->time + 60, COOKIEPATH, COOKIE_DOMAIN);
		}
		else
		{
			setcookie('pwamp_args', '', $this->time - 1, COOKIEPATH, COOKIE_DOMAIN);
		}

		$this->json_redirect($this->home_url);
	}

	public function wp_die_handler($function)
	{
		return array($this, 'die_handler');
	}


	public function plugins_loaded()
	{
		if ( is_admin() || $GLOBALS['pagenow'] === 'wp-login.php' )
		{
			return;
		}


		$this->init();

		$this->divert();


		if ( isset($_GET['amp']) || isset($_GET['desktop']) )
		{
			$device = !isset($_GET['desktop']) ? 'mobile' : 'desktop';
		}
		elseif ( !empty($_COOKIE['pwamp_style']) )
		{
			$device = $_COOKIE['pwamp_style'] != 'desktop' ? 'mobile' : 'desktop';
		}
		else
		{
			require_once $this->plugin_dir_path . 'pwamp/detection.php';

			$device = $this->get_device();

			$device = ( $device != 'desktop' && $device != 'desktop-bot' ) ? 'mobile' : 'desktop';
		}

		setcookie('pwamp_style', $device, $this->time + 60*60*24*365, COOKIEPATH, COOKIE_DOMAIN);


		if ( $device == 'desktop' )
		{
			add_action('wp_head', array($this, 'add_amphtml'), 0);
			add_action('wp_footer', array($this, 'add_notification_bar'), 1000);

			return;
		}


		if ( is_plugin_active('adaptive-images/adaptive-images.php') )
		{
			if ( !empty($this->viewport_width) )
			{
				setcookie('resolution', $this->viewport_width . ',1', 0, '/');
			}
		}


		if ( is_plugin_active('pwamp-online/pwamp.php') )
		{
			require_once $this->plugin_dir_path . '../pwamp-online/pwamp/conversion.php';
		}
		else
		{
			require_once $this->plugin_dir_path . 'pwamp/conversion.php';
		}


		add_action('after_setup_theme', array($this, 'after_setup_theme'));
		add_action('shutdown', array($this, 'shutdown'));

		add_filter('comment_post_redirect', array($this, 'comment_post_redirect'), 10, 2);
		add_filter('wp_die_handler', array($this, 'wp_die_handler'), 10, 1);

		add_filter('show_admin_bar', '__return_false');
	}
}


$pwamp = new PWAMP();

add_action('plugins_loaded', array($pwamp, 'plugins_loaded'), 1);
