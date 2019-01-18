<?php defined("SYSPATH") or die("No direct script access.");

DEFINE("SITEMAP_FILENAME", "sitemap.xml");
DEFINE("STYLE_SHEET_FILENAME", "sitemap.xsl");

class Admin_Sitemap_Controller extends Admin_Controller {

	public function index() {
		print $this->_get_view();
	}

	public function handler() {
		access::verify_csrf();

		$form = $this->_get_form();

		if ($form->validate()) {
			module::set_var("sitemap", "path", $form->sitemap->sitemap_path->value);
			module::set_var("sitemap", "base_url", $form->sitemap->sitemap_base_url->value);
			module::set_var("sitemap", "zip", $form->sitemap->sitemap_zip->value);
			module::set_var("sitemap", "ping_yandex", $form->sitemap->sitemap_ping_yandex->value);
			module::set_var("sitemap", "ping_google", $form->sitemap->sitemap_ping_google->value);
			module::set_var("sitemap", "ping_bing", $form->sitemap->sitemap_ping_bing->value);
			module::set_var("sitemap", "ping_ask", $form->sitemap->sitemap_ping_ask->value);
			module::set_var("sitemap", "robots_txt", $form->sitemap->sitemap_robots_txt->value);
			module::set_var("sitemap", "albums", $form->albums->sitemap_albums->value);
			module::set_var("sitemap", "albums_images", $form->albums->sitemap_albums_images->value);
			module::set_var("sitemap", "albums_freq", $form->albums->sitemap_albums_freq->value);
			module::set_var("sitemap", "albums_prio", $form->albums->sitemap_albums_prio->value);
			module::set_var("sitemap", "photos", $form->photos->sitemap_photos->value);
			module::set_var("sitemap", "photos_freq", $form->photos->sitemap_photos_freq->value);
			module::set_var("sitemap", "photos_prio", $form->photos->sitemap_photos_prio->value);
			module::set_var("sitemap", "movies", $form->movies->sitemap_movies->value);
			module::set_var("sitemap", "movies_freq", $form->movies->sitemap_movies_freq->value);
			module::set_var("sitemap", "movies_prio", $form->movies->sitemap_movies_prio->value);
			if ($form->build_sitemap->sitemap_build->value) {
				if ($status = $this->_build_sitemap()) {
					message::info($status);
				}
			}
			message::success(t("Settings have been saved"));
			url::redirect("admin/sitemap");
		}

		print $this->_get_view($form);
	}

	private function _get_view($form=null) {
		$v = new Admin_View("admin.html");
		$v->page_title = t("Gallery 3 :: Manage XML sitemap");
		$v->content = new View("admin_sitemap.html");
		$v->content->form = empty($form) ? $this->_get_form() : $form;
		return $v;
	}

	private function _get_form() {
		$freq_range = array("always"=>t("Always"), "hourly"=>t("Hourly"), "daily"=>t("Daily"), "weekly"=>t("Weekly"),
				"monthly"=>t("Monthly"), "yearly"=>t("Yearly"), "never"=>t("Never"));

		$prio_range = array("0"=>0, "0.1"=>0.1, "0.2"=>0.2, "0.3"=>0.3, "0.4"=>0.4, "0.5"=>0.5, "0.6"=>0.6,
			"0.7"=>0.7, "0.8"=>0.8, "0.9"=>0.9, "1.0"=>1.0);

		$form = new Forge("admin/sitemap/handler", "", "post", array("id" => "g-admin-form"));
		$group = $form->group("sitemap")->label(t("Sitemap"));
		$group->input("sitemap_path")->label(t("Enter physical file system path for sitemap. This should be %dirr or below, since sitemaps are not valid outside their scope.", array("dirr" => DOCROOT)))
			->value(module::get_var("sitemap", "path", DOCROOT));
		$group->input("sitemap_base_url")->label(t("Enter base URL for your Gallery 3 installation on the form http://your.domain.com/, http://your.domain.com/gallery3/, https://your.domain.com/index.php/ or similar."))
			->value(module::get_var("sitemap", "base_url", "http://your.domain.com/"));
		if (function_exists("gzencode")) {
			$group->checkbox("sitemap_zip")->label(t("Gzip sitemap"))
				->checked(module::get_var("sitemap", "zip"));
		} else {
			$group->checkbox("sitemap_zip")->label(t("Gzip sitemap [Zlib not available]"))
				->checked(module::get_var("sitemap", "zip", false))
				->disabled("disabled", "disabled");
		}
		$group->checkbox("sitemap_ping_yandex")->label(t("Notify Yandex about sitemap"))
			->checked(module::get_var("sitemap", "ping_yandex"));
		$group->checkbox("sitemap_ping_google")->label(t("Notify Google about sitemap"))
			->checked(module::get_var("sitemap", "ping_google"));
		$group->checkbox("sitemap_ping_bing")->label(t("Notify Bing about sitemap"))
			->checked(module::get_var("sitemap", "ping_bing"));
		$group->checkbox("sitemap_ping_ask")->label(t("Notify Ask about sitemap"))
			->checked(module::get_var("sitemap", "ping_ask"));

		if (is_writable($_SERVER['DOCUMENT_ROOT'])) {
			$group->checkbox("sitemap_robots_txt")->label(t("Add sitemap location to robots.txt"))
				->checked(module::get_var("sitemap", "robots_txt"));
		} else {
			$dir = $_SERVER['DOCUMENT_ROOT'];
			$group->checkbox("sitemap_robots_txt")->label(t("Add sitemap location to robots.txt [ %dird ] not writable.", array("dird" => DOCROOT)))
				->checked(module::get_var("sitemap", "robots_txt", false))
				->disabled("disabled", "disabled");
		}

		$group = $form->group("albums")->label(t("Albums"));
		$group->checkbox("sitemap_albums")->label(t("Include albums"))
			->checked(module::get_var("sitemap", "albums"));
		$group->checkbox("sitemap_albums_images")->label(t("Include album images"))
			->checked(module::get_var("sitemap", "albums_images"));
		$group->dropdown("sitemap_albums_freq")->label(t("Frequency"))
			->options($freq_range)
			->selected(module::get_var("sitemap", "albums_freq", "weekly"));
		$group->dropdown("sitemap_albums_prio")->label(t("Priority"))
			->options($prio_range)
			->selected(module::get_var("sitemap", "albums_prio", "0.7"));

		$group = $form->group("photos")->label(t("Photos"));
		$group->checkbox("sitemap_photos")->label(t("Include photo pages"))
			->checked(module::get_var("sitemap", "photos"));
		$group->dropdown("sitemap_photos_freq")->label(t("Frequency"))
			->options($freq_range)
			->selected(module::get_var("sitemap", "photos_freq", "monthly"));
		$group->dropdown("sitemap_photos_prio")->label(t("Priority"))
			->options($prio_range)
			->selected(module::get_var("sitemap", "photos_prio", "0.3"));

		$group = $form->group("movies")->label(t("Movies"));
		$group->checkbox("sitemap_movies")->label(t("Include movie pages"))
			->checked(module::get_var("sitemap", "movies"));
		$group->dropdown("sitemap_movies_freq")->label(t("Frequency"))
			->options($freq_range)
			->selected(module::get_var("sitemap", "movies_freq", "monthly"));
		$group->dropdown("sitemap_movies_prio")->label(t("Priority"))
			->options($prio_range)
			->selected(module::get_var("sitemap", "movies_prio", "0.3"));

		$group = $form->group("build_sitemap")->label(t("Build"));
		$group->checkbox("sitemap_build")->label(t("Build sitemap now"))
			->checked(false);

		$form->submit("submit")->value(t("Save"));

		return $form;
	}

	private function _build_sitemap() {
		$sitemap_path = module::get_var("sitemap", "path");
		if (!is_writable($sitemap_path)) {
			message::error(t("$sitemap_path is not writable!"));
			return t("Cancelled sitemap building");
		}

		$zip = module::get_var("sitemap", "zip");
		$ping_yandex = module::get_var("sitemap", "ping_yandex");
		$ping_google = module::get_var("sitemap", "ping_google");
		$ping_bing = module::get_var("sitemap", "ping_bing");
		$ping_ask = module::get_var("sitemap", "ping_ask");
		$robots_txt = module::get_var("sitemap", "robots_txt");
		$albums = module::get_var("sitemap", "albums");
		$albums_freq = module::get_var("sitemap", "albums_freq");
		$albums_prio = module::get_var("sitemap", "albums_prio");
		$photos = module::get_var("sitemap", "photos");
		$photos_freq = module::get_var("sitemap", "photos_freq");
		$photos_prio = module::get_var("sitemap", "photos_prio");
		$movies = module::get_var("sitemap", "movies");
		$movies_freq = module::get_var("sitemap", "movies_freq");
		$movies_prio = module::get_var("sitemap", "movies_prio");
		$base_url = module::get_var("sitemap", "base_url");
		$base_path1 = str_replace("index.php", '', $base_url);
		$base_path = rtrim($base_path1, "/");

		$locations = '';
		if ($albums) $locations .= $this->_add_to_sitemap("album", $albums_freq, $albums_prio, $base_url);
		if ($photos) $locations .= $this->_add_to_sitemap("photo", $photos_freq, $photos_prio, $base_url);
		if ($movies) $locations .= $this->_add_to_sitemap("movie", $movies_freq, $movies_prio, $base_url);
		$date = '<!-- generated-on="' . date(DATE_ATOM) . '" -->';
		$sitemap_xsl_path = $base_url . STYLE_SHEET_FILENAME;
		$sitemap = <<< EOT
<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="$sitemap_xsl_path"?>
$date
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
$locations</urlset>

EOT;
		$sitemap_filename = ($zip) ? SITEMAP_FILENAME . '.gz' : SITEMAP_FILENAME;
		if ($zip) {
			$filesize = file_put_contents($sitemap_path . $sitemap_filename, gzencode($sitemap));
			@unlink($sitemap_path . SITEMAP_FILENAME);
		} else {
			$filesize = file_put_contents($sitemap_path . $sitemap_filename, $sitemap);
			@unlink($sitemap_path . SITEMAP_FILENAME . '.gz');
		}
		if ($filesize) {
			message::success(t("Sitemap has been saved to $sitemap_path$sitemap_filename ($filesize bytes)."));
		} else {
			message::error(t("Sitemap could not be saved. Check file system permissions."));
		}
		$copy = copy(MODPATH . "sitemap/" . STYLE_SHEET_FILENAME, $sitemap_path . STYLE_SHEET_FILENAME);
		if ($copy) {
			message::success(t("Sitemap stylesheet " . MODPATH . "sitemap/" . STYLE_SHEET_FILENAME . " copied to " . $sitemap_path . STYLE_SHEET_FILENAME));
		} else {
			message::error(t("Sitemap style sheet could not be copied. Check file system permissions."));
		}
		if ($ping_yandex) $this->_ping("Yandex", $base_path1 . $sitemap_filename);
		if ($ping_google) $this->_ping("Google", $base_path1 . $sitemap_filename);
		if ($ping_bing) $this->_ping("Bing", $base_path1 . $sitemap_filename);
		if ($ping_ask) $this->_ping("Ask", $base_path1 . $sitemap_filename);
		if ($robots_txt) {
			$this->_robots_txt($base_path . $sitemap_filename);
		} else {
			$this->_robots_txt($base_path . $sitemap_filename, true);
		}
		return;
	}

	/*
	 * does this need to do any kind of permissions check for public items only? this is problem using the
	 * admin permissions which will have access to everything?
	 */
	private function _add_to_sitemap($type, $freq, $prio, $base_url) {
		$locations = '';
		foreach (ORM::factory("item")
			->viewable()
			->where("type", "=", "$type")
			->find_all()
		as $item) {
			$relative_url_cache = $item->relative_url();
			$url = "$base_url" . $relative_url_cache;
			if ($relative_url_cache)
				$url .= Kohana::config('core.url_suffix');
			else
				$url = str_replace("/index.php", '', $url);
			$updated = date(DATE_ATOM, $item->updated);

			$images = null;
			$image_xml = '';

			if ($type == 'album' && module::get_var("sitemap", "albums_images")) {
				$images = $item
					->viewable()
					->where("type", "!=", "album")
					->limit(999) // google doesn't want any more than this
					->children();
			} elseif ($type == 'photo') {
				$images = array($item);
			}

			if ($images) {
				foreach ($images as $image) {
					$resize_url = $image->resize_url(true);
					// remove the cache buster stuff
					$image_url = preg_replace('/\?.*$/', '', $resize_url);

					$image_xml .= <<< EOT
		<image:image>
			<image:loc>$image_url</image:loc>
		</image:image>

EOT;
				}
			}

			$locations .= <<< EOT
	<url>
		<loc>$url</loc>
		<lastmod>$updated</lastmod>
		<changefreq>$freq</changefreq>
		<priority>$prio</priority>
$image_xml
	</url>

EOT;
		}
		return $locations;
	}

	private function _robots_txt($url, $remove = false) {
		if ($remove) {
		$text = <<< EOT

EOT;
			
		} else {
		$text = <<< EOT
# BEGIN XML Sitemap
Sitemap: $url
# END XML Sitemap
EOT;
		}

		$file = $_SERVER['DOCUMENT_ROOT'] . 'robots.txt';

		$robots_txt = (file_exists($file)) ? file_get_contents($file) : '';

		$def_begin = "# BEGIN XML Sitemap";
		$def_end = "# END XML Sitemap";

		$before = substr($robots_txt, 0, strpos($robots_txt, $def_begin));
//		$before = strstr($robots_txt, $def_begin, true);
		if ($before) {
			$split = explode($def_end, $robots_txt);
			$after = $split[1];
			$newtext = <<< EOT
$before

$text

$after
EOT;
		} else {
			$newtext = <<< EOT
$robots_txt

$text
EOT;
		}
		$newtext = preg_replace('/\n\n\n+/', "\n\n", $newtext);
		if (is_writable(dirname($file))) {
			$res = file_put_contents($file, $newtext);
			message::success(t("Updated $file ($res bytes)"));
		} else {
			message::error(t("Could not update $file"));
		}
	}

	private function _ping($service, $url) {
		switch ($service) {
			case "Yandex":
				$ping_url = "http://webmaster.yandex.ru/wmconsole/sitemap_list.xml?host=" . $url;
				break;
			case "Google":
				$ping_url = "http://www.google.com/webmasters/sitemaps/ping?sitemap=" . $url;
				break;
			case "Bing":
				$ping_url = "http://www.bing.com/webmaster/ping.aspx?siteMap=" . $url;
				break;
			case "Ask":
				$ping_url = "http://submissions.ask.com/ping?sitemap=" . $url;
				break;
			default:
				return;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ping_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		$info = curl_getinfo($ch); 
		$http_url = $info['url'];
		$http_code = $info['http_code'];
		curl_close($ch);
		if ($http_code == "200") {
			message::success(t("$service has been notified about your sitemap $http_url"));
		} else {
			message::error(t("$service could not access your sitemap $http_url – is it public and readable?"));
		}
	}

}
