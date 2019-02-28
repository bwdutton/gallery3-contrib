<?php defined("SYSPATH") or die("No direct script access.");

class Admin_rss_extra_Controller extends Admin_Controller {

  public function index() {
    $view = new Admin_View("admin.html");
    $view->content = new View("admin_rss_extra.html");
    $view->content->form = $this->_get_setting_form();
    $view->content->help = $this->get_edit_form_help();
    print $view;
  }

  private function get_rss_feeds() {
    $feeds = array();
    $class_name = "rss_extra_rss";
    if (method_exists($class_name, "supported_feeds")) {
      $feeds = array_merge($feeds,
        call_user_func(array($class_name, "supported_feeds"), null, null));
    }
    return $feeds;
  }

  private function save_feed_state($feeds) {
  	if (count($feeds) == 0):
  		module::clear_var("rss_extra", "settings");
  	else:
	    module::set_var("rss_extra", "settings", serialize($feeds));
	  endif;
  }

  private function read_feed_state() {
    $hash = module::get_var("rss_extra", "settings");
    if (isset($hash)):
      return unserialize($hash);
    else:
      return Array();
    endif;
  }

  public function save() {
    access::verify_csrf();

    $form = $this->_get_setting_form();
    if ($form->validate()):
			$state = array();
			$feeds = $this->get_rss_feeds();
			$i = 1;
			foreach($feeds as $url => $title):
			  $item = $form->g_admin_rssextra->__get("rss_feed_" . $i);
			  if (isset($item) and ($item->value)):
			    $state[$url] = 1;
			  endif;
		    $i++;
			endforeach;

  	  $this->save_feed_state($state);

      message::success("Settings have been Saved.");
      url::redirect("admin/rss_extra");
    endif;

    $view = new Admin_View("admin.html");
    $view->content = new View("admin_rss_extra.html");
    $view->content->form = $form;
    $view->content->help = $this->get_edit_form_help();
    print $view;
  }

  private function _get_setting_form() {
    $form = new Forge("admin/rss_extra/save", "", "post", array("id" => "g-admin-rssextra-form"));

    $group = $form->group("g_admin_rssextra")->label(t("Settings - Active RSS Feeds"));

    $state = $this->read_feed_state();
		$feeds = $this->get_rss_feeds();
		$i = 1;
		foreach($feeds as $url => $title):
	    $group->checkbox("rss_feed_" . $i)
	      ->label(t($title))
	      ->checked(array_key_exists($url, $state));
	    $i++;
		endforeach;

    $form->submit("")->value(t("Save"));
    return $form;
  }

  protected function get_edit_form_help() {
    $help = '<fieldset>';
    $help .= '<legend>Help</legend><ul>';
    $help .= '<li><h3>Settings</h3>
      <p>Specify what feeds neet to be made public.</p>
      </li>';

    $help .= '</ul></fieldset>';
    return t($help);
  }
}
