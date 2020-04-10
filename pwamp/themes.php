<?php
if ( !defined('ABSPATH') )
{
	exit;
}

class PWAMPThemes extends PWAMPTranscoding
{
	private $hamburger = '';
	private $menu = '';
	private $sidebar = '';
	private $level = 'level';


	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
	}


	private function body_sidebar_callback($matches)
	{
		$match = $matches[1];

		$this->sidebar = trim($this->sidebar);

		$body = '<body' . $match . '>
<amp-sidebar id="header-sidebar" side="left" layout="nodisplay">
<nav>
<div class="controls" [class]="hide ? \'controls hide-parent\' : \'controls\'" aria-label="' . $this->menu . ' controls">
<a tabindex="0" role="button" aria-label="">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
<path d="M16.67 0l2.83 2.829-9.339 9.175 9.339 9.167-2.83 2.829-12.17-11.996z"/>
</svg>
</a>
<span class="truncate">' . $this->menu . '</span>
<a tabindex="0" role="button" on="tap:header-sidebar.toggle" aria-label="Close ' . $this->menu . '">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
<path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"/>
</svg>
</a>
</div>
<div class="main-menu items" role="menu" aria-label="' . $this->menu . ' links" [class]="hide ? \'main-menu items hide-parent\' : \'main-menu items\'">
' . $this->sidebar . '
</div>
</nav>
</amp-sidebar>';

		return $body;
	}

	private function menu_button_callback($matches)
	{
		$match = $matches[1];

		$this->hamburger = $match;

		preg_match('/<span class="toggle-text">(.+)<\/span>/isU', $match, $match2);
		$this->menu = $match2[1];
		$this->menu = trim($this->menu);

		return '';
	}

	private function menu_li_callback($matches)
	{
		$match = $matches[2];

		preg_match('/_(\d+)$/im', $this->level, $match2);
		if ( empty($match2) )
		{
			return '';
		}

		$level = (int)$match2[1];
		$level += 1;
		$this->level = preg_replace('/_\d+$/im', '_' . $level, $this->level);


		preg_match('/<a href="([^"]*)">(.*)<\/a>/iU', $match, $match3);
		if ( empty($match3) )
		{
			return '';
		}

		$link = $match3[1];
		$label = $match3[2];


		if ( !preg_match('/{.+}/is', $match) )
		{
			$this->sidebar .= "\n" . '<a class="link-container truncate" tabindex="0" href="' . $link . '" title="' . $label . '">' . $label . '</a>';

			return '';
		}


		$child = preg_replace('/^level/im', 'hide', $this->level);
		$parent = preg_replace('/_\d+$/im', '', $child);

		$this->sidebar .= "\n" . '<a class="link-container submenu-icon truncate" role="menuitem" tabindex="0" aria-label="' . $label . '" aria-haspopup="true" on="tap:AMP.setState({' . $this->level . ': !' . $this->level . ', ' . $parent . ': !' . $parent . '})" title="' . $label . '">' . $label . '</a>
<div class="submenu hide-submenu" [class]="(' . $this->level . ' ? \'submenu show-submenu\' : \'submenu hide-submenu\') + \' \' + (' . $child . ' ? \'hide-parent\' : \'\')" role="menu" aria-label="' . $label . '">
<div class="controls" [class]="' . $child . ' ? \'controls hide-parent\' : \'controls\'" aria-label="' . $label . ' controls">
<a tabindex="0" role="button" on="tap:AMP.setState({' . $this->level . ': !' . $this->level . ', ' . $parent . ': !' . $parent . '})" aria-label="Return to ' . $this->menu . '">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
<path d="M16.67 0l2.83 2.829-9.339 9.175 9.339 9.167-2.83 2.829-12.17-11.996z"/>
</svg>
</a>
<span class="truncate">' . $label . '</span>
<a tabindex="0" role="button" on="tap:header-sidebar.toggle" aria-label="Close ' . $label . '">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
<path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"/>
</svg>
</a>
</div>';

		$match = preg_replace_callback('/({((?:[^{}]+|(?1))*)})/isU', array($this, 'menu_ul_callback'), $match);

		$this->sidebar .= "\n" . '</div>';

		return '';
	}

	private function menu_sidebar_callback($matches)
	{
		$match = $matches[1];

		$match = str_replace(array('<ul', '/ul>', '<li', '/li>'), array('{', '}', '[', ']'), $match);

		$match = preg_replace_callback('/({((?:[^{}]+|(?1))*)})/isU', array($this, 'menu_ul_callback'), $match);

		return '';
	}

	private function menu_ul_callback($matches)
	{
		$match = $matches[2];

		$this->level .= '_0';

		$match = preg_replace_callback('/(\[((?:[^\[\]]+|(?1))*)\])/isU', array($this, 'menu_li_callback'), $match);

		$this->level = preg_replace('/_\d+$/im', '', $this->level);

		return '';
	}

	private function search_button_callback($matches)
	{
		$hamburger = '<div class="toggle nav-toggle mobile-nav-toggle" role="button" aria-label="mobile menu" on="tap:header-sidebar.toggle" tabindex="0">' . $this->hamburger . '</div>';

		return $hamburger;
	}

	private function search_sidebar_callback($matches)
	{
		$match = $matches[1];

		$this->sidebar .= $match;

		return '';
	}


	public function pretranscode_theme($page, $theme)
	{
		if ( $theme != 'twentytwenty' )
		{
			return $page;
		}

		return $page;
	}

	public function transcode_theme($page, $theme)
	{
		if ( $theme != 'twentytwenty' )
		{
			return $page;
		}

		$page = preg_replace_callback('/<button class="toggle nav-toggle mobile-nav-toggle" data-toggle-target="\.menu-modal"  data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus="\.close-nav-toggle">(.*)<\/button>/isU', array($this, 'menu_button_callback'), $page);
		$page = preg_replace_callback('/<button class="toggle search-toggle mobile-search-toggle" data-toggle-target="\.search-modal" data-toggle-body-class="showing-search-modal" data-set-focus="\.search-modal \.search-field" aria-expanded="false">.*<\/button>/isU', array($this, 'search_button_callback'), $page);

		$page = preg_replace('/<div class="header-navigation-wrapper">.*<nav class="primary-menu-wrapper" aria-label="Horizontal" role="navigation">.*<\/nav>.*<div class="header-toggles hide-no-js">.*<div class="toggle-wrapper search-toggle-wrapper">.*<button class="toggle search-toggle desktop-search-toggle" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false">.*<\/button>.*<\/div>.*<\/div>.*<\/div>/isU', '', $page);

		$page = preg_replace_callback('/<div class="search-modal cover-modal header-footer-group" data-modal-target-string=".search-modal">.*<div class="search-modal-inner modal-inner">.*<div class="section-inner">(.*<form role="search" aria-label="Search for:" method="get" class="search-form" action="[^"]+" target="_top">.*<\/form>).*<button class="toggle search-untoggle close-search-toggle fill-children-current-color" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false">.*<\/button>.*<\/div>.*<\/div>.*<\/div>/isU', array($this, 'search_sidebar_callback'), $page);
		$page = preg_replace_callback('/<div class="menu-modal cover-modal header-footer-group" data-modal-target-string=".menu-modal">.*<div class="menu-modal-inner modal-inner">.*<div class="menu-wrapper section-inner">.*<button class="toggle close-nav-toggle fill-children-current-color" data-toggle-target=".menu-modal" data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".menu-modal">.*<\/button>.*<nav class="mobile-menu" aria-label="Mobile" role="navigation">(.*)<\/nav>.*<div class="menu-bottom">.*<\/div>.*<\/div>.*<\/div>.*<\/div>/isU', array($this, 'menu_sidebar_callback'), $page);

		$page = preg_replace_callback('/<body\b([^>]*)\s*?>/iU', array($this, 'body_sidebar_callback'), $page);

		return $page;
	}

	public function posttranscode_theme($page, $theme)
	{
		if ( $theme != 'twentytwenty' )
		{
			return $page;
		}

		return $page;
	}
}
