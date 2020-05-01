<?php
/*
Plugin Name: PWA+AMP
Plugin URI:  https://flexplat.com/pwamp-wordpress/
Description: Converts WordPress into Progressive Web Apps and Accelerated Mobile Pages styles.
Version:     4.4.0
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
	private $home_url = '';
	private $theme = '';

	private $page_url = '';
	private $permalink = '';
	private $viewport_width = '';
	private $plugin_dir_url = '';

	private $canonical = '';
	private $amphtml = '';

	private $home_url_pattern = '';
	private $host_url = '';

	private $time_now = 0;
	private $plugin_dir = '';
	private $plugin_dir_path = '';

	private $page = '';


	public function __construct()
	{
	}

	public function __destruct()
	{
	}


	private function init()
	{
		$this->home_url = home_url();
		$this->theme = get_option('template');

		$page_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$this->page_url = preg_replace('/(\?|&)__amp_source_origin=.+$/im', '', $page_url);
		$this->permalink = get_option('permalink_structure');
		$this->viewport_width = !empty($_COOKIE['pwamp_viewport_width']) ? $_COOKIE['pwamp_viewport_width'] : '';
		$this->plugin_dir_url = plugin_dir_url(__FILE__);

		$canonical = htmlspecialchars_decode($this->page_url);
		$canonical = preg_replace('/^(.*)(((\?)|(&(amp;)?))((amp)|(desktop))(=1)?)?(#[^#]*)?$/imU', '${1}${11}', $canonical);
		$amphtml = preg_replace('/^(.*)(#[^#]*)?$/imU', '${1}' . ( ( strpos($canonical, '?') !== false ) ? '&amp=1' : '?amp=1' ) . '${2}', $canonical);
		$this->amphtml = htmlspecialchars($amphtml);
		$canonical = preg_replace('/^(.*)(#[^#]*)?$/imU', '${1}' . ( ( strpos($canonical, '?') !== false ) ? '&desktop=1' : '?desktop=1' ) . '${2}', $canonical);
		$this->canonical = htmlspecialchars($canonical);

		$home_url_pattern = preg_replace('/^https?:\/\//im', 'https?://', $this->home_url);
		$this->home_url_pattern = str_replace(array('/', '.'), array('\/', '\.'), $home_url_pattern);
		$this->host_url = preg_replace('/^https?:\/\/([^\/]*?)\/??.*$/imU', 'https://${1}', $this->home_url);

		$this->time_now = time();
		$this->plugin_dir = preg_replace('/^' . $this->home_url_pattern . '(.+)\/$/im', '${1}', $this->plugin_dir_url);
		$this->plugin_dir_path = plugin_dir_path(__FILE__);
	}

	private function divert()
	{
		if ( preg_match('/^' . $this->home_url_pattern . '\/\??manifest\.webmanifest$/im', $this->page_url) )
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
		elseif ( preg_match('/^' . $this->home_url_pattern . '\/\??pwamp-sw\.html$/im', $this->page_url) )
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
		elseif ( preg_match('/^' . $this->home_url_pattern . '\/\??pwamp-sw\.js$/im', $this->page_url) )
		{
			header('Content-Type: application/javascript', true);
			echo 'importScripts(\'.' . $this->plugin_dir . '/pwamp/sw/sw-toolbox.js\');
toolbox.router.default = toolbox.cacheFirst;';

			exit();
		}
		elseif ( preg_match('/^' . $this->home_url_pattern . '\/\?pwamp-viewport-width=(\d+)$/im', $this->page_url, $match) )
		{
			$this->viewport_width = $match[1];

			setcookie('pwamp_viewport_width', $this->viewport_width, $this->time_now + 60*60*24*365, COOKIEPATH, COOKIE_DOMAIN);

			exit();
		}
	}


	public function add_desktop_amphtml()
	{
		echo '<link rel="amphtml" href="' . $this->amphtml . '" />' . "\n";
	}

	public function add_desktop_notification_bar()
	{
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
	<div id="pwamp-notification" style="display:flex;align-items:center;justify-content:center">Switch to&nbsp;<a href="' . $this->amphtml . '">mobile version</a>&nbsp;&nbsp;<input type="button" value="Continue" style="min-width:80px" onclick="pwamp_notification_toggle();" /></div>
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

	private function get_external_canonical()
	{
		$page_path = preg_replace('/^' . $this->home_url_pattern . '(.+)$/im', '${1}', $this->page_url);

		$external = new PWAMPCanonical();

		$canonical = $external->get_canonical($page_path);

		return $canonical;
	}

	private function transcode_page($page)
	{
		$page = preg_replace('/^[\s\t]*<style type="[^"]+" id="[^"]+"><\/style>$/im', '', $page);

		$data = array(
			'page_url' => $this->page_url,
			'canonical' => $this->canonical,
			'permalink' => $this->permalink,
			'page_type' => $this->get_page_type(),
			'viewport_width' => $this->viewport_width,
			'plugin_dir_url' => $this->plugin_dir_url
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
		setcookie('pwamp_message', '', $this->time_now, COOKIEPATH, COOKIE_DOMAIN);

		$title = '';
		if ( !empty($_COOKIE['pwamp_title']) )
		{
			$title = $_COOKIE['pwamp_title'];
			setcookie('pwamp_title', '', $this->time_now, COOKIEPATH, COOKIE_DOMAIN);
		}

		$args = array();
		if ( !empty($_COOKIE['pwamp_args']) )
		{
			$args = json_decode(stripslashes($_COOKIE['pwamp_args']));
			setcookie('pwamp_args', '', $this->time_now, COOKIEPATH, COOKIE_DOMAIN);
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
		$redirection = preg_replace('/^' . $this->home_url_pattern . '\//im', '', $redirection);
		if ( !preg_match('/^https?:\/\//im', $redirection) )
		{
			$redirection = $this->home_url . '/' . $redirection;
		}

		header('Content-type: application/json');
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Allow-Origin: *.ampproject.org');
		header('Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin');
		header('AMP-Access-Control-Allow-Source-Origin: ' . $this->host_url);
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


		setcookie('pwamp_message', $message, $this->time_now + 5, COOKIEPATH, COOKIE_DOMAIN);

		if ( !empty($title) )
		{
			setcookie('pwamp_title', $title, $this->time_now + 5, COOKIEPATH, COOKIE_DOMAIN);
		}

		if ( !empty($args) )
		{
			setcookie('pwamp_args', json_encode($args), $this->time_now + 5, COOKIEPATH, COOKIE_DOMAIN);
		}

		$this->json_redirect($this->home_url);
	}

	public function wp_die_json_handler($function)
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


		if ( is_plugin_active('pwamp-canonical/pwamp.php') )
		{
			$device = 'mobile';
		}
		elseif ( !empty($_GET['amp']) || !empty($_GET['desktop']) )
		{
			$device = empty($_GET['desktop']) ? 'mobile' : 'desktop';
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

		setcookie('pwamp_style', $device, $this->time_now + 60*60*24*365, COOKIEPATH, COOKIE_DOMAIN);


		if ( $device == 'desktop' )
		{
			add_action('wp_head', array($this, 'add_desktop_amphtml'), 0);
			add_action('wp_footer', array($this, 'add_desktop_notification_bar'), 1000);

			return;
		}


		if ( is_plugin_active('pwamp-canonical/pwamp.php') )
		{
			require_once $this->plugin_dir_path . '../pwamp-canonical/pwamp/canonical.php';

			$this->canonical = $this->get_external_canonical();
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
		add_filter('wp_die_json_handler', array($this, 'wp_die_json_handler'), 10, 1);

		add_filter('show_admin_bar', '__return_false');


		if ( is_plugin_active('adaptive-images/adaptive-images.php') )
		{
			if ( !empty($this->viewport_width) )
			{
				setcookie('resolution', $this->viewport_width . ',1', 0, '/');
			}
		}
	}
}


$pwamp = new PWAMP();

add_action('plugins_loaded', array($pwamp, 'plugins_loaded'), 1);
