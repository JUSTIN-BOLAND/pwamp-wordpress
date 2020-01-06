<?php
if ( !defined('ABSPATH') )
{
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'lib/get-remote-url-content-data.php';
require_once plugin_dir_path( __FILE__ ) . 'lib/amp_remove_css.class.php';

class PWAMPLibrary
{
	private $font_server_list = array(
		'https://cloud.typography.com',
		'https://fast.fonts.net',
		'https://fonts.googleapis.com',
		'https://use.typekit.net',
		'https://maxcdn.bootstrapcdn.com',
		'https://use.fontawesome.com'
	);

	private $image_style = 'div.pwamp-fixed-height-container>amp-img{position:relative;width:100%;height:300px}amp-img.pwamp-contain>img{object-fit:contain}';
	private $sidebar_style = 'padding:.5rem 1rem;background:#f5f5f5;border:#a7a7a7 1px solid}amp-sidebar,amp-sidebar .submenu{width:100%;height:100%}amp-sidebar .main-menu,amp-sidebar .submenu{overflow:auto}amp-sidebar .submenu{top:0;left:0;position:fixed}amp-sidebar .hide-submenu{visibility:hidden;transform:translateX(-100%)}amp-sidebar .show-submenu{visibility:visible;transform:translateX(0)}amp-sidebar .hide-parent{visibility:hidden}amp-sidebar .truncate{white-space:nowrap;overflow:hidden;text-overflow:ellipsis}amp-sidebar .link-container{display:block;height:44px;line-height:44px;border-bottom:1px solid #f0f0f0;padding:0 1rem}amp-sidebar a{min-width:44px;min-height:44px;text-decoration:none;cursor:pointer}amp-sidebar .submenu-icon{padding-right:44px}amp-sidebar .submenu-icon::after{position:absolute;right:0;height:44px;width:44px;content:\'\';background-size:1rem;background-image:url(\'data:image/svg+xml;utf8, <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M5 3l3.057-3 11.943 12-11.943 12-3.057-3 9-9z"/></svg>\');background-repeat:no-repeat;background-position:center}amp-sidebar .controls{display:flex;height:50px;background:#f0f0f0}amp-sidebar .controls a{display:flex;justify-content:center;align-items:center}amp-sidebar .controls span{line-height:50px;margin:0 auto}amp-sidebar nav>.controls>a:first-of-type{visibility:hidden}amp-sidebar .controls a svg{height:1rem;width:1rem}amp-sidebar .link-icon{float:left;height:44px;margin-right:.75rem}amp-sidebar .link-icon>svg{height:44px}amp-sidebar{background:#fff;color:#232323;fill:#232323;text-transform:uppercase;letter-spacing:.18rem;font-size:.875rem}amp-sidebar a{color:#232323;text-transform:none;letter-spacing:normal}div[class*="-sidebar-mask"]{opacity:.8}amp-sidebar a:hover{text-decoration:underline;fill:#232323}amp-sidebar .view-all{font-style:italic;font-weight:bold}';


	private $style_list = array();
	private $image_list = array();

	private $style = '';


	private $home_url = '';
	private $home_url_pattern = '';
	private $page_url = '';
	private $canonical = '';
	private $permalink = '';
	private $page_type = '';
	private $viewport_width = 0;
	private $plugin_dir_url = '';
	private $themes_url = '';
	private $plugins_url = '';

	private $css = '';
	private $head = '';
	private $body = '';


	public function __construct()
	{
	}

	public function __destruct()
	{
	}


	public function init($home_url, $data)
	{
		$this->home_url = $home_url;
		$this->home_url_pattern = preg_replace('/^https?:\/\//im', 'https?://', $home_url);
		$this->home_url_pattern = str_replace(array('/', '.'), array('\/', '\.'), $this->home_url_pattern);

		if ( !empty($data['page_url']) && is_string($data['page_url']) )
		{
			$this->page_url = $data['page_url'];
		}
		else
		{
			$this->page_url = $home_url . '/';
		}

		if ( !empty($data['canonical']) && is_string($data['canonical']) )
		{
			$this->canonical = $data['canonical'];
		}
		else
		{
			$canonical = preg_replace('/((\?)|(&(amp;)?))((amp)|(desktop))(=1)?$/im', '', $this->page_url);
			if ( is_plugin_active('pwamp-detection/pwamp.php') )
			{
				$canonical .= ( strpos($canonical, '?') !== false ) ? '&desktop=1' : '?desktop=1';
			}
			$this->canonical = htmlspecialchars($canonical);
		}

		if ( !empty($data['permalink']) && is_string($data['permalink']) )
		{
			$this->permalink = $data['permalink'];
		}

		if ( !empty($data['page_type']) && is_string($data['page_type']) )
		{
			$this->page_type = $data['page_type'];
		}

		if ( !empty($data['viewport_width']) && is_string($data['viewport_width']) )
		{
			$this->viewport_width = (int)$data['viewport_width'];
		}

		if ( !empty($data['plugin_dir_url']) && is_string($data['plugin_dir_url']) )
		{
			$this->plugin_dir_url = $data['plugin_dir_url'];
		}

		if ( !empty($data['themes_url']) && is_string($data['themes_url']) )
		{
			$this->themes_url = $data['themes_url'];
		}

		if ( !empty($data['plugins_url']) && is_string($data['plugins_url']) )
		{
			$this->plugins_url = $data['plugins_url'];
		}
	}


	public function set_style_list($style_list)
	{
		$this->style_list = $style_list;
	}

	public function set_image_list($image_list)
	{
		$this->image_list = $image_list;
	}

	private function append_image_style()
	{
		$this->style .= $this->image_style;
	}

	private function append_sidebar_style()
	{
		$this->style .= $this->sidebar_style;
	}

	private function append_style()
	{
		if ( empty($this->style_list) )
		{
			return;
		}

		$url = preg_replace('/^' . $this->home_url_pattern . '\//im', '', $this->page_url);
		$url = md5($url);

		if ( array_key_exists($url, $this->style_list) )
		{
			$this->style .= $this->style_list[$url];
		}
		elseif ( array_key_exists($this->page_type, $this->style_list) )
		{
			$this->style .= $this->style_list[$this->page_type];
		}
		elseif ( array_key_exists('default', $this->style_list) )
		{
			$this->style .= $this->style_list['default'];
		}
	}

	private function minify_css($css, $id = '')
	{
		$css = !empty($id) ? $id . '{' . $css . '}' : $css;

		$css = str_replace('\/\*!', '/*', $css);
		$css = preg_replace('/\/\*[^*]*\*+([^\/][^*]*\*+)*\//', '', $css);
		$css = preg_replace('/[\r\n\s\t]+/', ' ', $css);
		$css = preg_replace('/\s*([{}\(\):;,>])\s*/i', '${1}', $css);
		$css = str_replace(';}', '}', $css);
		$css = trim($css);

		$css = preg_replace('/\s*@charset (("UTF-8")|(\'UTF-8\'));\s*/i', '', $css);
		$css = preg_replace('/\s*@((-ms-viewport)|(viewport)){[^}]+}\s*/i', '', $css);
		$css = preg_replace('/\s*!important\b\s*/i', '', $css);
		$css = preg_replace('/\s*text-rendering:\s*optimizeLegibility;\s*/i', '', $css);

		if ( preg_match('/{}$/im', $css) )
		{
			return;
		}

		return $css;
	}

	private function update_style($theme)
	{
	}

	private function media_callback($matches)
	{
		$match = $matches[1];
		$match2 = $matches[2];

		if ( $this->viewport_width == 0 )
		{
			return '@media(' . $match . '){' . $match2 . '}';
		}

		if ( preg_match('/min-width:\s?(\d+)px/i', $match, $match3) )
		{
			$min_width = (int)$match3[1];
		}
		elseif ( preg_match('/min-width:\s?(\d+(\.\d+)?)em/i', $match, $match3) )
		{
			$min_width = (int)$match3[1] * 16;
		}

		if ( preg_match('/max-width:\s?(\d+)px/i', $match, $match3) )
		{
			$max_width = (int)$match3[1];
		}
		elseif ( preg_match('/max-width:\s?(\d+(\.\d+)?)em/i', $match, $match3) )
		{
			$max_width = (int)$match3[1] * 16;
		}

		if ( isset($min_width) && isset($max_width) )
		{
			if ( $this->viewport_width >= $min_width && $this->viewport_width <= $max_width )
			{
				$this->css .= $match2;
			}
		}
		elseif ( isset($min_width) )
		{
			if ( $this->viewport_width >= $min_width )
			{
				$this->css .= $match2;
			}
		}
		elseif ( isset($max_width) )
		{
			if ( $this->viewport_width <= $max_width )
			{
				$this->css .= $match2;
			}
		}

		return '';
	}


	private function all_callback($matches)
	{
		return '<' . $matches[1] . $matches[5] . $matches[6] . $matches[7] . '>';
	}

	private function all2_callback($matches)
	{
		$css = !empty($matches[3]) ? $matches[4] : $matches[6];
		$css = $this->minify_css($css);

		return '<' . $matches[1] . ' style="' . $css . '"' . $matches[7] . $matches[8] . $matches[9] . '>';
	}

	private function form_callback($matches)
	{
		$match = $matches[1];

		if ( preg_match('/ method=(("post")|(\'post\'))/i', $match) )
		{
			// The attribute 'action' may not appear in tag 'FORM [method=POST]'.
			$match = preg_replace('/ action=(("([^"]*)")|(\'([^\']*)\'))/i', ' action-xhr="${3}${5}"', $match);

			// Invalid URL protocol 'http:' for attribute 'action-xhr' in tag 'FORM [method=POST]'.
			$match = preg_replace('/ action-xhr="http:\/\/([^"]*)"/i', ' action-xhr="https://${1}"', $match);
		}
		else
		{
			// The mandatory attribute 'action' is missing in tag 'FORM [method=GET]'.
			if ( !preg_match('/ action=(("[^"]*")|(\'[^\']*\'))/i', $match) )
			{
				$match .= ' action="' . $this->page_url . '"';
			}

			// The mandatory attribute 'target' is missing in tag 'FORM [method=GET]'.
			if ( !preg_match('/ target=(("[^"]*")|(\'[^\']*\'))/i', $match) )
			{
				$match .= ' target="_top"';
			}
		}

		return '<form' . $match . '>';
	}

	private function iframe_callback($matches)
	{
		$match = $matches[1];

		if ( preg_match('/ class=(("[^"]*")|(\'[^\']*\'))/i', $match) )
		{
			$match = preg_replace('/ class=(("([^"]*)")|(\'([^\']*)\'))/i', ' class="${3}${5} pwamp-contain"', $match);
		}
		else
		{
			$match .= ' class="pwamp-contain"';
		}

		$match = preg_replace('/ sizes=(("[^"]*")|(\'[^\']*\'))/i', '', $match);

		$match = preg_replace('/ layout=(("[^"]*")|(\'[^\']*\'))/i', '', $match);
		if ( !preg_match('/ width=(("[^"]*")|(\'[^\']*\'))/i', $match) || !preg_match('/ height=(("[^"]*")|(\'[^\']*\'))/i', $match) )
		{
			$match .= ' layout="fill"';

			return '<div class="pwamp-fixed-height-container"><amp-iframe' . $match . '><noscript><iframe' . $matches[1] . ' /></noscript></amp-iframe></div>';
		}
		else
		{
			$match .= ' layout="intrinsic"';

			return '<amp-iframe' . $match . '><noscript><iframe' . $matches[1] . ' /></noscript></amp-iframe>';
		}
	}

	private function img_callback($matches)
	{
		$match = $matches[1];

		if ( !empty($this->image_list) )
		{
			if ( preg_match('/ (data-(lazy-)?)?src=(("([^"]*)")|(\'([^\']*)\'))/i', $match, $match2) )
			{
				$url = !empty($match2[4]) ? $match2[5] : $match2[7];
				$url = preg_replace('/^' . $this->home_url_pattern . '\//im', '', $url);
				$image = 'src="' . $url . '"';

				if ( preg_match('/ width=(("([^"]*)")|(\'([^\']*)\'))/i', $match, $match2) )
				{
					$width = !empty($match2[2]) ? $match2[3] : $match2[5];
					$image .= ' width="' . $width . '"';
				}

				if ( preg_match('/ height=(("([^"]*)")|(\'([^\']*)\'))/i', $match, $match2) )
				{
					$height = !empty($match2[2]) ? $match2[3] : $match2[5];
					$image .= ' height="' . $height . '"';
				}

				if ( array_key_exists($image, $this->image_list) )
				{
					$match .= $this->image_list[$image];
				}
			}
		}

		if ( preg_match('/ class=(("[^"]*")|(\'[^\']*\'))/i', $match) )
		{
			$match = preg_replace('/ class=(("([^"]*)")|(\'([^\']*)\'))/i', ' class="${3}${5} pwamp-contain"', $match);
		}
		else
		{
			$match .= ' class="pwamp-contain"';
		}

		$match = preg_replace('/ sizes=(("[^"]*")|(\'[^\']*\'))/i', '', $match);

		$match = preg_replace('/ layout=(("[^"]*")|(\'[^\']*\'))/i', '', $match);
		if ( !preg_match('/ width=(("[^"]*")|(\'[^\']*\'))/i', $match) || !preg_match('/ height=(("[^"]*")|(\'[^\']*\'))/i', $match) )
		{
			$match .= ' layout="fill"';

			return '<div class="pwamp-fixed-height-container"><amp-img' . $match . '><noscript><img' . $matches[1] . ' /></noscript></amp-img></div>';
		}
		else
		{
			$match .= ' layout="intrinsic"';

			return '<amp-img' . $match . '><noscript><img' . $matches[1] . ' /></noscript></amp-img>';
		}
	}

	private function link_callback($matches)
	{
		return '<link' . $matches[1] . ' href="https:' . $matches[4] . $matches[6] . '"' . $matches[7] . ' />';
	}

	private function link2_callback($matches)
	{
		$match = $matches[1];

		if ( !preg_match('/ rel=(("stylesheet")|(\'stylesheet\'))/i', $match) )
		{
			return '<link' . $match . ' />';
		}

		if ( !preg_match('/ media=(("(all|screen)")|(\'(all|screen\')))/i', $match) )
		{
			return '';
		}

		if ( !preg_match('/ href=(("([^"]*)")|(\'([^\']*)\'))/i', $match, $match2) )
		{
			return '<link' . $match . ' />';
		}

		$url = !empty($match2[2]) ? $match2[3] : $match2[5];
		$host = preg_replace('/^https?:\/\/([^\/]+)\/.*$/im', 'https://${1}', $url);
		if ( in_array($host, $this->font_server_list) )
		{
			return '<link' . $match . ' />';
		}
		else
		{
			if ( empty($this->style_list) )
			{
				$css = get_remote_data($url);

				$css = preg_replace_callback('/url\(("|\')?(.*?)("|\')?\)/i', function($matches) use($url) {
						if ( empty($matches[1]) ) $matches[1] = '';
						if ( empty($matches[3]) ) $matches[3] = '';

						$match = $matches[2];

						if ( preg_match('/^data\:((application)|(image))\//im', $match) )
						{
							return 'url(' . $matches[1] . $match . $matches[3] . ')';
						}

						if ( preg_match('/^https?:\/\//im', $match) )
						{
							$match = preg_replace('/^' . $this->home_url_pattern . '\//im', '', $match);
						}
						elseif ( preg_match('/^\.\.\//im', $match) )
						{
							$url2 = preg_replace('/^' . $this->home_url_pattern . '\//im', '', $url);
							$url2 = preg_replace('/[^\/]+\/[^\/]*$/im', '', $url2);

							$match = preg_replace('/^\.\.\//im', '', $match);
							$match = $url2 . $match;
						}
						elseif ( preg_match('/^\.\//im', $match) )
						{
							$url2 = preg_replace('/^' . $this->home_url_pattern . '\//im', '', $url);
							$url2 = preg_replace('/[^\/]*$/im', '', $url2);

							$match = preg_replace('/^\.\//im', '', $match);
							$match = $url2 . $match;
						}
						else
						{
							$url2 = preg_replace('/^' . $this->home_url_pattern . '\//im', '', $url);
							$url2 = preg_replace('/[^\/]*$/im', '', $url2);

							$match = $url2 . $match;
						}

						return 'url(' . $matches[1] . $match . $matches[3] . ')';
					}, $css);

				$this->style .= $this->minify_css($css);
			}

			return '';
		}
	}

	private function style_callback($matches)
	{
		$match = $matches[1];

		if ( empty($this->style_list) )
		{
			$this->style .= $this->minify_css($match);
		}

		return '';
	}

	private function textarea_callback($matches)
	{
		$match = $matches[2];

		$match = str_replace(array("\r\n", "\r", "\n"), '<amp-br />', $match);

		return '<textarea' . $matches[1] . '>' . $match . '</textarea>';
	}

	private function textarea2_callback($matches)
	{
		$match = $matches[2];

		$match = str_replace('<amp-br />', "\n", $match);

		return '<textarea' . $matches[1] . '>' . $match . '</textarea>';
	}

	private function video_callback($matches)
	{
		$match = $matches[1];

		if ( preg_match('/ class=(("[^"]*")|(\'[^\']*\'))/i', $match) )
		{
			$match = preg_replace('/ class=(("([^"]*)")|(\'([^\']*)\'))/i', ' class="${3}${5} pwamp-contain"', $match);
		}
		else
		{
			$match .= ' class="pwamp-contain"';
		}

		$match = preg_replace('/ sizes=(("[^"]*")|(\'[^\']*\'))/i', '', $match);

		$match = preg_replace('/ layout=(("[^"]*")|(\'[^\']*\'))/i', '', $match);
		if ( !preg_match('/ width=(("[^"]*")|(\'[^\']*\'))/i', $match) || !preg_match('/ height=(("[^"]*")|(\'[^\']*\'))/i', $match) )
		{
			$match .= ' layout="fill"';

			return '<div class="pwamp-fixed-height-container"><amp-video' . $match . '><noscript><video' . $matches[1] . ' /></noscript></amp-video></div>';
		}
		else
		{
			$match .= ' layout="intrinsic"';

			return '<amp-video' . $match . '><noscript><video' . $matches[1] . ' /></noscript></amp-video>';
		}
	}

	public function transcode_html($page, $theme)
	{
		$this->append_image_style();

		$this->append_style();


		$page = preg_replace('/<!--.*-->/isU', '', $page);


		/*
			<!doctype>
		*/
		// The attribute '"-//w3c//dtd' may not appear in tag 'html doctype'.
		// The attribute '"http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd"' may not appear in tag 'html doctype'.
		// The attribute '1.0' may not appear in tag 'html doctype'.
		// The attribute 'public' may not appear in tag 'html doctype'.
		// The attribute 'transitional//en"' may not appear in tag 'html doctype'.
		// The attribute 'xhtml' may not appear in tag 'html doctype'.
		$page = preg_replace('/<!DOCTYPE\b[^>]*>/i', '<!doctype html>', $page, 1);


		/*
			<a></a>
		*/
		// The attribute 'alt' may not appear in tag 'a'.
		$page = preg_replace('/<a\b([^>]*) alt=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<a${1}${5}>', $page);


		/*
			<amp-install-serviceworker></amp-install-serviceworker>
		*/
		$page = preg_replace('/<amp-install-serviceworker.+>.*<\/amp-install-serviceworker>/isU', '', $page);


		/*
			<area/>
		*/
		// The tag 'area' is disallowed.
		$page = preg_replace('/<area\b([^>]*)\s*?\/?>/iU', '', $page);


		/*
			<audio></audio>
		*/
		// The tag 'audio' may only appear as a descendant of tag 'noscript'. Did you mean 'amp-audio'?
		$page = preg_replace('/<audio\b([^>]*)>/i', '<amp-audio${1}>', $page);


		/*
			<button></button>
		*/
		// The attribute 'href' may not appear in tag 'button'.
		$page = preg_replace('/<button\b([^>]*) href=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<button${1}${5}>', $page);


		/*
			<canvas></canvas>
		*/
		// The tag 'canvas' is disallowed.
		$page = preg_replace('/<canvas\b[^>]*>.*<\/canvas>/isU', '', $page);


		/*
			<col/>
		*/
		// The attribute 'width' may not appear in tag 'col'.
		$page = preg_replace('/<col\b([^>]*) width=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?\/?>/iU', '<col${1}${5} />', $page);


		/*
			<div></div>
		*/
		// The attribute 'name' may not appear in tag 'div'.
		$page = preg_replace('/<div\b([^>]*) name=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<div${1}${5}>', $page);

		// The attribute 'target' may not appear in tag 'div'.
		$page = preg_replace('/<div\b([^>]*) target=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<div${1}${5}>', $page);


		/*
			<embed/>
		*/
		// The tag 'embed' is disallowed.
		$page = preg_replace('/<embed\b([^>]*)\s*?\/?>/iU', '', $page);


		/*
			<font></font>
		*/
		// The tag 'font' is disallowed.
		$page = preg_replace('/<font[^>]*>(.*)<\/font>/isU', '${1}', $page);


		/*
			<form></form>
		*/
		$page = preg_replace_callback('/<form\b([^>]*)\s*?>/iU', array($this, 'form_callback'), $page);


		/*
			<head></head>
		*/
		$page = preg_replace('/^[\s\t]*<head>/im', '<head>', $page, 1);
		$page = preg_replace('/^[\s\t]*<\/head>/im', '</head>', $page, 1);


		/*
			<hr/>
		*/
		// The attribute 'size' may not appear in tag 'hr'.
		$page = preg_replace('/<hr\b([^>]*) size=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?\/?>/iU', '<hr${1}${5} />', $page);


		/*
			<html>
		*/
		// The attribute 'xml:lang' may not appear in tag 'html'.
		$page = preg_replace('/<html\b([^>]*) xml:lang=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<html${1}${5}>', $page);

		// The attribute 'xmlns' may not appear in tag 'html'.
		$page = preg_replace('/<html\b([^>]*) xmlns=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<html${1}${5}>', $page);

		// The attribute 'xmlns:fb' may not appear in tag 'html'.
		$page = preg_replace('/<html\b([^>]*) xmlns:fb=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<html${1}${5}>', $page);

		// The attribute 'xmlns:og' may not appear in tag 'html'.
		$page = preg_replace('/<html\b([^>]*) xmlns:og=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<html${1}${5}>', $page);

		$page = preg_replace('/<html\b([^>]*) ((amp)|(⚡))\b([^>]*)>/i', '<html${1}${5}', $page, 1);
		$page = preg_replace('/<html\b([^>]*)>/i', '<html amp${1}>', $page, 1);


		/*
			<icon></icon>
		*/
		$page = preg_replace('/<icon class="([^"]+)"><\/icon>/i', '<div class="${1}"></div>', $page);


		/*
			<iframe></iframe>
		*/
		$page = preg_replace('/<iframe\b([^>]*) src=(("[^"]*")|(\'[^\']*\'))([^>]*) data-lazy-src=(("([^"]*)")|(\'([^\']*)\'))([^>]*)\s*?\/?>/iU', '<iframe${1} src="${8}${10}"${5}${11} />', $page);

		// The tag 'iframe' may only appear as a descendant of tag 'noscript'. Did you mean 'amp-iframe'?
		$page = preg_replace_callback('/<iframe\b([^>]*)\s*?\/?>/iU', array($this, 'iframe_callback'), $page);

		// The attribute 'align' may not appear in tag 'amp-iframe'.
		$page = preg_replace('/<amp-iframe\b([^>]*) align=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<amp-iframe${1}${5}>', $page);

		// The attribute 'allowtransparency' in tag 'amp-iframe' is set to the invalid value 'true'.
		$page = preg_replace('/<amp-iframe\b([^>]*) allowtransparency=(("true")|(\'true\'))([^>]*)>/i', '<amp-iframe${1} allowtransparency="allowtransparency"${5}>', $page);

		// The attribute 'frameborder' in tag 'amp-iframe' is set to the invalid value 'no'.
		$page = preg_replace('/<amp-iframe\b([^>]*) frameborder=(("no")|(\'no\'))([^>]*)>/i', '<amp-iframe${1} frameborder="0"${5}>', $page);

		// The attribute 'marginheight' may not appear in tag 'amp-iframe'.
		$page = preg_replace('/<amp-iframe\b([^>]*) marginheight=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<amp-iframe${1}${5}>', $page);

		// The attribute 'marginwidth' may not appear in tag 'amp-iframe'.
		$page = preg_replace('/<amp-iframe\b([^>]*) marginwidth=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<amp-iframe${1}${5}>', $page);

		// The attribute 'mozallowfullscreen' may not appear in tag 'amp-iframe'.
		$page = preg_replace('/<amp-iframe\b([^>]*) mozallowfullscreen=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<amp-iframe${1}${5}>', $page);
		$page = preg_replace('/<amp-iframe\b([^>]*) mozallowfullscreen\b([^>]*)\s*?>/iU', '<amp-iframe${1}${2}>', $page);

		// The attribute 'name' may not appear in tag 'amp-iframe'.
		$page = preg_replace('/<amp-iframe\b([^>]*) name=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<amp-iframe${1}${5}>', $page);

		// Invalid URL protocol 'http:' for attribute 'src' in tag 'amp-iframe'.
		$page = preg_replace('/<amp-iframe\b([^>]*) src=(("http:\/\/([^"]*)")|(\'http:\/\/([^\']*)\'))([^>]*)>/i', '<amp-iframe${1} src="https://${4}${6}"${7}>', $page);

		// The attribute 'webkitallowfullscreen' may not appear in tag 'amp-iframe'.
		$page = preg_replace('/<amp-iframe\b([^>]*) webkitallowfullscreen=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<amp-iframe${1}${5}>', $page);
		$page = preg_replace('/<amp-iframe\b([^>]*) webkitallowfullscreen\b([^>]*)\s*?>/iU', '<amp-iframe${1}${2}>', $page);

		// The attribute 'allow' may not appear in tag 'iframe'.
		$page = preg_replace('/<iframe\b([^>]*) allow=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?\/?>/iU', '<iframe${1}${5} />', $page);

		// The attribute 'allowfullscreen' may not appear in tag 'iframe'.
		$page = preg_replace('/<iframe\b([^>]*) allowfullscreen([^>]*)\s*?\/?>/iU', '<iframe${1}${5} />', $page);


		/*
			<img/>
		*/
		$page = preg_replace('/<img\b([^>]*) src=(("\/?")|(\'\/?\'))([^>]*)\s*?\/?>/iU', '', $page);

		$page = preg_replace('/<img\b([^>]*) src=(("[^"]*")|(\'[^\']*\'))([^>]*) data-lazy-src=(("([^"]*)")|(\'([^\']*)\'))([^>]*)\s*?\/?>/iU', '<img${1} src="${8}${10}"${5}${11} />', $page);
		$page = preg_replace('/<img\b([^>]*) src=(("[^"]+(data:image\/gif;base64,[^"]+)")|(\'[^\']+(data:image\/gif;base64,[^\']+)\'))([^>]*)\s*?\/?>/iU', '<img${1} src="${4}${6}"${7} />', $page);
		$page = preg_replace('/<img\b([^>]*) src=(("\/\/([^"]+)")|(\'\/\/([^\']+)\'))([^>]*)\s*?\/?>/iU', '<img${1} src="https://${4}${6}"${7} />', $page);
		$page = preg_replace('/<img\b([^>]*) src=(("\/([^"]+)")|(\'\/([^\']+)\'))([^>]*)\s*?\/?>/iU', '<img${1} src="' . $this->home_url . '/${4}${6}"${7} />', $page);

		$page = preg_replace('/<img\b([^>]*) height=(("\d+%")|(\'\d+%\'))([^>]*)\s*?\/?>/iU', '<img${1}${5} />', $page);
		$page = preg_replace('/<img\b([^>]*) width=(("\d+%")|(\'\d+%\'))([^>]*)\s*?\/?>/iU', '<img${1}${5} />', $page);

		// The tag 'img' may only appear as a descendant of tag 'noscript'. Did you mean 'amp-img'?
		$page = preg_replace_callback('/<img\b([^>]*)\s*?\/?>/iU', array($this, 'img_callback'), $page);

		// The attribute 'align' may not appear in tag 'amp-img'.
		$page = preg_replace('/<amp-img\b([^>]*) align=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?\/?>/iU', '<amp-img${1}${5} />', $page);

		// The attribute 'async' may not appear in tag 'amp-img'.
		$page = preg_replace('/<amp-img\b([^>]*) async=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?\/?>/iU', '<amp-img${1}${5} />', $page);

		// The attribute 'border' may not appear in tag 'amp-img'.
		$page = preg_replace('/<amp-img\b([^>]*) border=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?\/?>/iU', '<amp-img${1}${5} />', $page);

		// The attribute 'usemap' may not appear in tag 'amp-img'.
		$page = preg_replace('/<amp-img\b([^>]*) usemap=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?\/?>/iU', '<amp-img${1}${5} />', $page);


		/*
			<input>
		*/
		// The attribute 'tooltip' may not appear in tag 'input'.
		$page = preg_replace('/<input\b([^>]*) tooltip=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<input${1}${5}>', $page);


		/*
			<link/>
		*/
		$page = preg_replace('/<link\b[^>]* rel=(("apple-touch-icon")|(\'apple-touch-icon\'))[^>]* href=(("[^"]+")|(\'[^"]+\'))[^>]*\s*?\/?>/iU', '', $page);
		$page = preg_replace('/<link\b[^>]* rel=(("canonical")|(\'canonical\'))[^>]*\s*?\/?>/iU', '', $page);
		$page = preg_replace('/<link\b[^>]* rel=(("manifest")|(\'manifest\'))[^>]* href=(("[^"]+")|(\'[^"]+\'))[^>]*\s*?\/?>/iU', '', $page);

		$page = preg_replace_callback('/<link\b([^>]*)\s? href=(("(\/\/[^"]+)")|(\'(\/\/[^\']+)\'))([^>]*)\s*?\/?>/iU', array($this, 'link_callback'), $page);

		// The attribute 'href' in tag 'link rel=stylesheet for fonts' is set to the invalid value...
		$page = preg_replace_callback('/<link\b([^>]*)\s*?\/?>/iU', array($this, 'link2_callback'), $page);

		$page = preg_replace('/^[\s\t]*<link\b([^>]*)>/im', '<link${1}>', $page);


		/*
			<map></map>
		*/
		// The tag 'map' is disallowed.
		$page = preg_replace('/<map\b[^>]*>.*<\/map>/isU', '', $page);


		/*
			<meta>
		*/
		$page = preg_replace('/<meta\b[^>]* charset=(("utf-8")|(\'utf-8\'))[^>]*\s*?\/?>/iU', '', $page);

		// The attribute 'content' in tag 'meta http-equiv=Content-Type' is set to the invalid value 'text/html;charset=utf-8'.
		$page = preg_replace('/<meta http-equiv="Content-Type" content="text\/html;\s?charset=[^"]*"\s*?\/?>/iU', '', $page);

		// The attribute 'http-equiv' may not appear in tag 'meta name= and content='.
		$page = preg_replace('/<meta\b[^>]* http-equiv=(("refresh")|(\'refresh"\'))[^>]*\s*?\/?>/iU', '', $page);

		$page = preg_replace('/<meta name="pwamp-page-type" content="[^"]+"\s*?\/?>/iU', '', $page);

		// The attribute 'name' in tag 'meta name= and content=' is set to the invalid value 'revisit-after'.
		$page = preg_replace('/<meta\b[^>]* name=(("revisit-after")|(\'revisit-after\'))[^>]*\s*?\/?>/iU', '', $page);

		$page = preg_replace('/<meta\b[^>]* name=(("theme-color")|(\'theme-color\'))[^>]* content=(("[^"]+")|(\'[^"]+\'))[^>]*\s*?\/?>/iU', '', $page);
		$page = preg_replace('/<meta\b[^>]* name=(("viewport")|(\'viewport\'))[^>]*\s*?\/?>/iU', '', $page);

		$page = preg_replace('/^[\s\t]*<meta\b([^>]*)>/im', '<meta${1}>', $page);


		/*
			<object></object>
		*/
		// The tag 'object' is disallowed.
		$page = preg_replace('/<object\b[^>]*>.*<\/object>/isU', '', $page);


		/*
			<script></script>
		*/
		// Custom JavaScript is not allowed.
		$page = preg_replace('/<script\b[^>]*>.*<\/script>/isU', '', $page);


		/*
			<select></select>
		*/
		// The attribute 'value' may not appear in tag 'select'.
		$page = preg_replace('/<select\b([^>]*) value=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<select${1}${5}>', $page);


		/*
			<span></span>
		*/
		// The attribute 'active' may not appear in tag 'span'.
		$page = preg_replace('/<span\b([^>]*) active=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<span${1}${5}>', $page);

		// The attribute 'amount' may not appear in tag 'span'.
		$page = preg_replace('/<span\b([^>]*) amount=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<span${1}${5}>', $page);

		// The attribute 'override' may not appear in tag 'span'.
		$page = preg_replace('/<span\b([^>]*) override=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<span${1}${5}>', $page);

		// The attribute 'temscope' may not appear in tag 'span'.
		$page = preg_replace('/<span\b([^>]*) temscope=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<span${1}${5}>', $page);
		$page = preg_replace('/<span\b([^>]*) temscope\b([^>]*)\s*?>/iU', '<span${1}${2}>', $page);


		/*
			<style></style>
		*/
		// The mandatory attribute 'amp-custom' is missing in tag 'style amp-custom'.
		$page = preg_replace_callback('/<style\b[^>]*>(.*)<\/style>/isU', array($this, 'style_callback'), $page);

		$page = preg_replace('/<noscript>[\s\t\r\n]*<\/noscript>/i', '', $page);


		/*
			<svg></svg>
		*/
		// The attribute 'xmlns:serif' may not appear in tag 'svg'.
		$page = preg_replace('/<svg\b([^>]*) xmlns:serif=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<svg${1}${5}>', $page);


		/*
			<table></table>
		*/
		// The attribute 'frame' may not appear in tag 'table'.
		$page = preg_replace('/<table\b([^>]*) frame=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<table${1}${5}>', $page);

		// The attribute 'rules' may not appear in tag 'table'.
		$page = preg_replace('/<table\b([^>]*) rules=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<table${1}${5}>', $page);


		/*
			<textarea></textarea>
		*/
		// The attribute 'tooltip' may not appear in tag 'textarea'.
		$page = preg_replace('/<textarea\b([^>]*) tooltip=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<textarea${1}${5}>', $page);

		// The attribute 'value' may not appear in tag 'textarea'.
		$page = preg_replace('/<textarea\b([^>]*) value=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<textarea${1}${5}>', $page);


		/*
			<time></time>
		*/
		// The attribute 'pubdate' may not appear in tag 'time'.
		$page = preg_replace('/<time\b([^>]*) pubdate=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<time${1}${5}>', $page);
		$page = preg_replace('/<time\b([^>]*) pubdate\b([^>]*)\s*?>/iU', '<time${1}${2}>', $page);


		/*
			<title></title>
		*/
		$page = preg_replace('/^[\s\t]*<title>(.*)<\/title>/im', '<title>${1}</title>', $page, 1);


		/*
			<ul></ul>
		*/
		// The attribute 'featured_post_id' may not appear in tag 'ul'.
		$page = preg_replace('/<ul\b([^>]*) featured_post_id=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*?>/iU', '<ul${1}${5}>', $page);


		/*
			<video></video>
		*/
		// The tag 'video' may only appear as a descendant of tag 'noscript'. Did you mean 'amp-video'?
		$page = preg_replace_callback('/<video\b([^>]*)\s*?\/?>/iU', array($this, 'video_callback'), $page);


		/*
			Any Tag
		*/
		// The attribute 'onclick' may not appear in tag 'a'.
		$page = preg_replace_callback('/<(\w+\b[^>]*) on\w+=(("[^"]*")|(\'[^\']*\'))([^>]*)\s*(\s?)(\/?)>/iU', array($this, 'all_callback'), $page);

		$page = preg_replace_callback('/<(\w+\b[^>]*) style=(("([^"]*)")|(\'([^\']*)\'))([^>]*)>\s*(\s?)(\/?)/iU', array($this, 'all2_callback'), $page);


		$this->update_style($theme);

		return $page;
	}


	private function link3_callback($matches)
	{
		if ( !preg_match('/ href=(("https:\/\/s\.w\.org\/?")|(\'https:\/\/s\.w\.org\/?\'))/i', $matches[1]) && !preg_match('/ href=(("https:\/\/s\.w\.org\/?")|(\'https:\/\/s\.w\.org\/?\'))/i', $matches[13]) )
		{
			$this->head .= "\n" . '<link' . $matches[1] . ' rel=' . $matches[2] . $matches[13] . ' />';
		}

		return '';
	}

	private function link4_callback($matches)
	{
		$this->head .= '<link' . $matches[1] . ' rel="stylesheet"' . $matches[5] . ' />'. "\n";

		return '';
	}

	private function meta_callback($matches)
	{
		$this->head .= "\n" . '<meta' . $matches[1] . ' />';

		return '';
	}

	private function pixel_callback($matches)
	{
		$this->body .= "\n" . '<amp-pixel src="' . $matches[1] . '" layout="nodisplay"></amp-pixel>';

		return '';
	}

	private function title_callback($matches)
	{
		$this->head .= "\n" . '<title>' . $matches[1] . '</title>';

		return '';
	}

	public function transcode_head($page)
	{
		// Service Workers
		$this->body = '<amp-install-serviceworker
	src="' . $this->home_url . '/' . ( empty($this->permalink) ? '?' : '' ) . 'pwamp-sw.js"
	data-iframe-src="' . $this->home_url . '/' . ( empty($this->permalink) ? '?' : '' ) . 'pwamp-sw.html"
	layout="nodisplay">
</amp-install-serviceworker>';

		// Viewport Width
		$this->body .= "\n" . '<amp-pixel src="' . $this->home_url . '/?pwamp-viewport-width=VIEWPORT_WIDTH" layout="nodisplay"></amp-pixel>';

		// Pixel
		$page = preg_replace_callback('/<noscript><img height="1" width="1"[^>]* src="([^"]+)"[^>]*\s*?\/?><\/noscript>/isU', array($this, 'pixel_callback'), $page);

		$page = preg_replace('/<body\b([^>]*)>/i', '<body${1}>' . "\n" . $this->body, $page, 1);


		// The mandatory tag 'meta charset=utf-8' is missing or incorrect.
		$this->head = '<meta charset="utf-8" />';

		// The mandatory tag 'meta name=viewport' is missing or incorrect.
		$this->head .= "\n" . '<meta name="viewport" content="width=device-width, minimum-scale=1, initial-scale=1" />';

		// The tag 'meta http-equiv=Content-Type' may only appear as a descendant of tag 'head'.
		$page = preg_replace_callback('/<meta\b([^>]*)\s*?\/?>/iU', array($this, 'meta_callback'), $page);

		// pwamp-page-type
		if ( !empty($this->page_type) )
		{
			$this->head .= "\n" . '<meta name="pwamp-page-type" content="' . $this->page_type . '" />';
		}

		// Progressive Web Apps
		$this->head .= "\n" . '<meta name="theme-color" content="#ffffff" />';
		$this->head .= "\n" . '<link rel="manifest" href="' . $this->home_url . '/' . ( !empty($this->permalink) ? 'manifest.webmanifest' : '?manifest.webmanifest' ) . '" />';
		$this->head .= "\n" . '<link rel="apple-touch-icon" href="' . $this->plugin_dir_url . 'pwamp/mf/mf-logo-192.png" />';

		$page = preg_replace_callback('/<title>(.*)<\/title>/iU', array($this, 'title_callback'), $page);

		// The mandatory tag 'amphtml engine v0.js script' is missing or incorrect.
		$this->head .= "\n" . '<link rel="preconnect" href="https://cdn.ampproject.org" />';
		$this->head .= "\n" . '<link rel="dns-prefetch" href="https://s.w.org" />';
		$this->head .= "\n" . '<link rel="preload" as="script" href="https://cdn.ampproject.org/v0.js" />';
		$this->head .= "\n" . '<script async src="https://cdn.ampproject.org/v0.js"></script>';

		$page = preg_replace_callback('/<link\b([^>]*) rel=(("preconnect")|(\'preconnect\')|("dns-prefetch")|(\'dns-prefetch\')|("preload")|(\'preload\')|("prerender")|(\'prerender\')|("prefetch")|(\'prefetch\'))([^>]*)\s*?\/?>/iU', array($this, 'link3_callback'), $page);

		// The tag 'amp-audio' requires including the 'amp-audio' extension JavaScript.
		if ( preg_match('/<amp-audio\b[^>]*>/i', $page) )
		{
			$this->head .= "\n" . '<script async custom-element="amp-audio" src="https://cdn.ampproject.org/v0/amp-audio-0.1.js"></script>';
		}

		// The tag 'amp-fit-text' requires including the 'amp-fit-text' extension JavaScript.
		if ( preg_match('/<amp-fit-text\b[^>]*>/i', $page) )
		{
			$this->head .= "\n" . '<script async custom-element="amp-fit-text" src="https://cdn.ampproject.org/v0/amp-fit-text-0.1.js"></script>';
		}

		// The tag 'FORM [method=POST]' requires including the 'amp-form' extension JavaScript.
		// The tag 'FORM [method=GET]' requires including the 'amp-form' extension JavaScript.
		if ( preg_match('/<form\b[^>]*>/i', $page) )
		{
			$this->head .= "\n" . '<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>';
		}

		// The tag 'amp-iframe' requires including the 'amp-iframe' extension JavaScript.
		if ( preg_match('/<amp-iframe\b[^>]*>/i', $page) )
		{
			$this->head .= "\n" . '<script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>';
		}

		// The tag 'amp-install-serviceworker' requires including the 'amp-install-serviceworker' extension JavaScript.
		if ( preg_match('/<amp-install-serviceworker\b[^>]*>/i', $page) )
		{
			$this->head .= "\n" . '<script async custom-element="amp-install-serviceworker" src="https://cdn.ampproject.org/v0/amp-install-serviceworker-0.1.js"></script>';
		}

		// The tag 'amp-sidebar' requires including the 'amp-sidebar' extension JavaScript.
		if ( preg_match('/<amp-sidebar\b[^>]*>/i', $page) )
		{
			$this->head .= "\n" . '<script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>';
		}

		// The tag 'amp-video' requires including the 'amp-video' extension JavaScript.
		if ( preg_match('/<amp-video\b[^>]*>/i', $page) )
		{
			$this->head .= "\n" . '<script async custom-element="amp-video" src="https://cdn.ampproject.org/v0/amp-video-0.1.js"></script>';
		}

		// amp-custom style
		if ( !empty($this->style) )
		{
			$this->style = preg_replace('/@media\b[^{]+\{\s*\}/i', '', $this->style);
			$this->style = preg_replace_callback('/@media\b\s*?\(\s*?(.+)\s*?\)\s*?\{\s*?(.+\})\s*?\}/isU', array($this, 'media_callback'), $this->style);
			$this->style .= $this->css;

			$this->style = str_replace('\\', '\\\\', $this->style);
			$this->head .= "\n" . '<style amp-custom>' . $this->style . '</style>';
		}

		$page = preg_replace('/<head>/i', '<head>' . "\n" . $this->head, $page, 1);


		$this->head = '';

		// The parent tag of tag 'link rel=stylesheet for fonts' is 'body', but it can only be 'head'.
		$page = preg_replace_callback('/<link\b([^>]*) rel=(("stylesheet")|(\'stylesheet\'))([^>]*)\s*?\/?>/iU', array($this, 'link4_callback'), $page);

		// The mandatory tag 'link rel=canonical' is missing or incorrect.
		$this->head .= '<link rel="canonical" href="' . $this->canonical . '" />';

		// The mandatory tag 'head > style[amp-boilerplate]' is missing or incorrect.
		// The mandatory tag 'noscript > style[amp-boilerplate]' is missing or incorrect.
		// The mandatory tag 'noscript enclosure for boilerplate' is missing or incorrect.
		$this->head .= "\n" . '<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>';

		$page = preg_replace('/<\/head>/i', $this->head . "\n" . '</head>', $page, 1);


		if ( empty($this->style_list) )
		{
			$clarity = new AmpRemoveUnusedCss();
			$clarity->process($page);  //must be full htmlcode, with <style amp-custom> tag and the <body> content
			$page = $clarity->result();

			$page = str_replace('webkit-keyframes mejs-loading-spinner{to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}' . "\n", '@-webkit-keyframes mejs-loading-spinner{to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}' . "\n", $page);
			$page = str_replace('webkit-keyframes buffering-stripes{0%{background-position:0 0}' . "\n" . 'to{background-position:30px 0}' . "\n", '@-webkit-keyframes buffering-stripes{0%{background-position:0 0}to{background-position:30px 0}}' . "\n", $page);
		}


		$page = preg_replace_callback('/<textarea\b([^>]*)>(.*)<\/textarea>/isU', array($this, 'textarea_callback'), $page);

		// Remove blank lines.
		$page = preg_replace('/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', "\n", $page);

		// Remove end line spaces.
		$page = preg_replace('/[\s\t]+[\r\n]/', "\n", $page);

		$page = preg_replace_callback('/<textarea\b([^>]*)>(.*)<\/textarea>/isU', array($this, 'textarea2_callback'), $page);

		return $page;
	}
}
