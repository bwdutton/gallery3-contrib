<?php defined("SYSPATH") or die("No direct script access.");

class sitemap_installer {
	static function deactivate() {
		module::clear_var("sitemap", "path");
		module::clear_var("sitemap", "base_url");
		module::clear_var("sitemap", "zip");
		module::clear_var("sitemap", "ping_google");
		module::clear_var("sitemap", "ping_bing");
		module::clear_var("sitemap", "ping_ask");
		module::clear_var("sitemap", "robots_txt");
		module::clear_var("sitemap", "albums");
		module::clear_var("sitemap", "albums_freq");
		module::clear_var("sitemap", "albums_prio");
		module::clear_var("sitemap", "photos");
		module::clear_var("sitemap", "photos_freq");
		module::clear_var("sitemap", "photos_prio");
		module::clear_var("sitemap", "movies");
		module::clear_var("sitemap", "movies_freq");
		module::clear_var("sitemap", "movies_prio");
	}
}
