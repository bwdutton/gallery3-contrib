<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Extra RSS feeds for Menalto Gallery 3
 * Copyright (C) 2012 Serguei Dosyukov
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */

class rss_extra_rss_Core {

  public static function get_feed_states() {
    $hash = module::get_var("rss_extra", "settings");
    if (isset($hash)):
      return unserialize($hash);
    else:
      return [];
    endif;
	}

  public static function feed_enabled($feed_key, $states) {
  	return (array_key_exists($feed_key, $states));
	}

  public static function check_feed($feed_key, $states) {
  	if (!array_key_exists($feed_key, $states)):
			throw new Exception('Feed not available.');
		endif;
	}

  public static function supported_feeds($item, $tag) {
  	$feeds = [];
  	$feeds["rss_extra/random"] = t("Random photos and movies");
  	$feeds["rss_extra/random/photo"] = t("Random photos");
		$feeds["rss_extra/random/landscape"] = t("Random photos - Landscape"); 
    $feeds["rss_extra/random/portrait"]  = t("Random photos - Portrait"); 
	  $feeds["rss_extra/album_landscape"]  = t("Album - Landscape"); 
	  $feeds["rss_extra/album_portrait"]   = t("Album - Portrait"); 
	  $feeds["rss_extra/latest"] 					 = t("Latest photos"); 
	  $feeds["rss_extra/latest/landscape"] = t("Latest photos - Landscape"); 
	  $feeds["rss_extra/latest/portrait"]  = t("Latest photos - Portrait"); 
	  $feeds["rss_extra/popular"] 				 = t("Popular photos"); 
	  $feeds["rss_extra/popular/landscape"] = t("Popular photos - Landscape"); 
	  $feeds["rss_extra/popular/portrait"]  = t("Popular photos - Portrait"); 
    return $feeds;
  }

  public static function available_feeds($item, $tag) {
  	$state = self::get_feed_states();
  	$feeds = [];

		if (self::feed_enabled("rss_extra/random", $state))
			$feeds["rss_extra/random"] = t("Random photos and movies");
    if (self::feed_enabled("rss_extra/random/photo", $state))
    	$feeds["rss_extra/random/photo"] = t("Random photos");
		if (self::feed_enabled("rss_extra/random/landscape", $state))
			$feeds["rss_extra/random/landscape"] = t("Random photos - Landscape");
		if (self::feed_enabled("rss_extra/random/portrait", $state))
    	$feeds["rss_extra/random/portrait"]  = t("Random photos - Portrait");

		if (self::feed_enabled("rss_extra/album_landscape", $state))
	    $feeds["rss_extra/album_landscape"]  = t("Album - Landscape");
		if (self::feed_enabled("rss_extra/album_portrait", $state))
	    $feeds["rss_extra/album_portrait"]   = t("Album - Portrait");

		if (self::feed_enabled("rss_extra/latest", $state))
	    $feeds["rss_extra/latest"] = t("Latest photos");
		if (self::feed_enabled("rss_extra/latest/landscape", $state))
	    $feeds["rss_extra/latest/landscape"] = t("Latest photos - Landscape");
		if (self::feed_enabled("rss_extra/latest/portrait", $state))
	    $feeds["rss_extra/latest/portrait"]  = t("Latest photos - Portrait");

		if (self::feed_enabled("rss_extra/popular", $state))
	    $feeds["rss_extra/popular"] = t("Popular photos");
		if (self::feed_enabled("rss_extra/popular/landscape", $state))
	    $feeds["rss_extra/popular/landscape"] = t("Popular photos - Landscape");
		if (self::feed_enabled("rss_extra/popular/portrait", $state))
	    $feeds["rss_extra/popular/portrait"]  = t("Popular photos - Portrait");

    return $feeds;
  }

  public static function feed($feed_id, $offset, $limit, $id) {
    $feed = new stdClass();
    $idlist = [];
    $resize_size = module::get_var("gallery", "resize_size");
	 	$state = self::get_feed_states();

    switch ($feed_id):
      case "random":
        if (isset($id)):
          $photoonly = TRUE;
        	switch ($id):
        	  case "landscape":
							self::check_feed("rss_extra/random/landscape", $state);
        			$feed->title = t("Random photos - Landscape");
        	    break;
        	  case "portrait":
							self::check_feed("rss_extra/random/portrait", $state);
        			$feed->title = t("Random photos - Portrait");
        	    break;
        	  default:
							self::check_feed("rss_extra/random/photo", $state);
  			      $feed->title = t("Random photos");
        	    break;
        	endswitch;
        else:
          $photoonly = FALSE;
					self::check_feed("rss_extra/random", $state);
	        $feed->title = t("Random photos and movies");
        endif;
        $feed->description = $feed->title;

        do {
          if ($photoonly):
          	$item = match ($id) {
                  "landscape" => ORM::factory("item")->viewable()
                    ->where("rand_key", "<", random::percent())
                    ->where("type", "=", "photo")
                    ->where("resize_width", ">=", DB::expr('resize_height'))
                    ->order_by("rand_key", "DESC")
                    ->find_all(1)->current(),
                  "portrait" => ORM::factory("item")->viewable()
                    ->where("rand_key", "<", random::percent())
                    ->where("type", "=", "photo")
                    ->where("resize_height", ">=", DB::expr('resize_width'))
                    ->order_by("rand_key", "DESC")
                    ->find_all(1)->current(),
                  default => ORM::factory("item")->viewable()
                    ->where("rand_key", "<", random::percent())
                    ->where("type", "=", "photo")
                    ->order_by("rand_key", "DESC")
                    ->find_all(1)->current(),
              };
          else:
            $item = ORM::factory("item")->viewable()
              ->where("rand_key", "<", random::percent())
              ->where("type", "!=", "album")
              ->order_by("rand_key", "DESC")
              ->find_all(1)->current();
            // $item = item::random_query($where_type)->find_all(1)->current();
          endif;

					if (isset($item)):
            if ($item->loaded()):
              $idlist[] = $item->id;
            else:
            	continue;
          	endif;
          else:
	        	continue;
        	endif;
        } while (count($idlist) < $limit);
        break;

      case "latest":
	      if (isset($id)):
	        switch ($id):
	      	  case "landscape":
							self::check_feed("rss_extra/latest/landscape", $state);
	       			$feed->title = t("Recent updates (Landscape)");
      	     	$feed->items = ORM::factory("item")->viewable()
    		        ->where("type", "=", "photo")
                ->where("resize_width", ">=", DB::expr('resize_height'))
    		        ->order_by("created", "DESC")
    		        ->find_all($limit, $offset);
            	$all_items = ORM::factory("item")->viewable()
    		        ->where("type", "=", "photo")
                ->where("resize_height", ">=", DB::expr('resize_width'))
    		        ->order_by("created", "DESC");
              break;
	       	  case "portrait":
							self::check_feed("rss_extra/latest/portrait", $state);
	       			$feed->title = t("Recent updates (Portrait)");
      	     	$feed->items = ORM::factory("item")->viewable()
    		        ->where("type", "=", "photo")
                ->where("resize_height", ">=", DB::expr('resize_width'))
    		        ->order_by("created", "DESC")
    		        ->find_all($limit, $offset);
            	$all_items = ORM::factory("item")->viewable()
    		        ->where("type", "=", "photo")
                ->where("resize_width", ">=", DB::expr('resize_height'))
    		        ->order_by("created", "DESC");
	       	    break;
	       	  default:
	       	  	return;
	       	    break;
         	endswitch;
		    else:
					self::check_feed("rss_extra/latest", $state);
	   			$feed->title = t("Recent updates");
		     	$feed->items = ORM::factory("item")->viewable()
		        ->where("type", "=", "photo")
		        ->order_by("created", "DESC")
		        ->find_all($limit, $offset);
	      	$all_items = ORM::factory("item")
		        ->viewable()
		        ->where("type", "=", "photo")
		        ->order_by("created", "DESC");
  	    endif;

  	    $feed->max_pages = ceil($all_items->find_all()->count() / $limit);
	      $feed->description = $feed->title;
	      return $feed;

      case "album_portrait":
      case "album_landscape":
        $id ??= 1;
        $item = ORM::factory("item", $id);
        access::required("view", $item);

        if ($feed_id == "album_portrait"):
					self::check_feed("rss_extra/album_portrait", $state);

          $feed->items = $item->viewable()
  	        ->where("type", "=", "photo")
            ->where("resize_height", ">=", DB::expr('resize_width'))
            ->where("left_ptr", ">", $item->left_ptr)
            ->where("right_ptr", "<=", $item->right_ptr)
   	        ->order_by("created", "DESC")
            ->find_all($limit, $offset);
          $count = $item->viewable()
  	        ->where("type", "=", "photo")
            ->where("resize_height", ">=", DB::expr('resize_width'))
            ->where("left_ptr", ">", $item->left_ptr)
            ->where("right_ptr", "<=", $item->right_ptr)
            ->count_all();
          $title_ex = t("(Portrait)");
        else:
  				self::check_feed("rss_extra/album_landscape", $state);

          $feed->items = $item->viewable()
  	        ->where("type", "=", "photo")
            ->where("resize_width", ">=", DB::expr('resize_height'))
            ->where("left_ptr", ">", $item->left_ptr)
            ->where("right_ptr", "<=", $item->right_ptr)
   	        ->order_by("created", "DESC")
            ->find_all($limit, $offset);
          $count = $item->viewable()
  	        ->where("type", "=", "photo")
            ->where("resize_width", ">=", DB::expr('resize_height'))
            ->where("left_ptr", ">", $item->left_ptr)
            ->where("right_ptr", "<=", $item->right_ptr)
            ->count_all();
        	$title_ex = t("(Landscape)");
        endif;

        $feed->max_pages = ceil($count / $limit);

        if ($item->id == item::root()->id):
          $feed->title = html::purify($item->title);
        else:
          $feed->title = t("%site_title - %item_title",
                           ["site_title" => item::root()->title,
                                 "item_title" => $item->title]);
        endif;
        $feed->title .= " " . $title_ex;
        $feed->description = $feed->title;
        return $feed;
        break;

      case "popular":
        if (isset($id)):
	        switch ($id) {
  	    	  case "landscape":
							self::check_feed("rss_extra/popular/landscape", $state);
	       			$feed->title = t("Popular items (Landscape)");
              $feed->items = ORM::factory("item")
    	        	->viewable()
    		        ->where("type", "=", "photo")
                ->where("resize_width", ">=", DB::expr('resize_height'))
    						->order_by("view_count", "DESC")
    						->find_all($limit, $offset);
	       	    break;
	       	  case "portrait":
							self::check_feed("rss_extra/popular/portrait", $state);
	       			$feed->title = t("Popular items (Portrait)");
              $feed->items = ORM::factory("item")
    	        	->viewable()
    		        ->where("type", "=", "photo")
                ->where("resize_height", ">=", DB::expr('resize_width'))
    						->order_by("view_count", "DESC")
    						->find_all($limit, $offset);
	       	    break;
	       	  default:
              return;
	       	    break;
	       	}
				else:
					self::check_feed("rss_extra/popular", $state);
     			$feed->title = t("Popular items");
          $feed->items = ORM::factory("item")
 	        	->viewable()
 		        ->where("type", "=", "photo")
 						->order_by("view_count", "DESC")
 						->find_all($limit, $offset);
        endif;

  	    $feed->max_pages = 1;
	      $feed->description = $feed->title;
	      return $feed;
        break;
      default:
        return;
        break;
    endswitch;

    $feed->items = ORM::factory("item")
        ->viewable()
        ->where("type", "<>", "album")
        ->where("id", "IN", $idlist)
        ->order_by("created", "DESC")
        ->find_all($limit, $offset);
    $feed->max_pages = 1;
    return $feed;
  }
}
