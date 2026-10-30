<?php

class user_rest_Core {
	public static function get($request) {
		$user = rest::resolve($request->url);

		return [
			"url" => $request->url,
			"entity" => [
				"display_name" => $user->display_name()
			]
		];
	}
	
	
	public static function resolve($id) {
		$user = identity::lookup_user($id);
		
		if (!self::_can_view_profile_pages($user)) {
			throw new Kohana_404_Exception();
		}
		return $user;
	}
	
	
	public static function _can_view_profile_pages($user) {
		if (!$user->loaded()) {
		  return false;
		}

		if ($user->id == identity::active_user()->id) {
			// You can always view your own profile
			return true;
		}

		return match (module::get_var("gallery", "show_user_profiles_to")) {
            "admin_users" => identity::active_user()->admin,
            "registered_users" => !identity::active_user()->guest,
            "everybody" => true,
            // Fail in private mode on an invalid setting
            default => false,
        };
	}
}
  
 ?>