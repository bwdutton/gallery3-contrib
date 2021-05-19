<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2013 Bharat Mediratta
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
class basket_item
{
  public $product;

  public $item;

  public $quantity;

  public $cost = 0;

  public $cost_per = 0;

  public $items;

  public function __construct($aProduct, $aItem, $aQuantity){
    // TODO check individual product.
    $this->product = $aProduct;
    $this->item = $aItem;
    $this->quantity = $aQuantity;
    $this->calculate_cost();
  }

  private function calculate_cost(){
    $prod = ORM::factory("product", $this->product);
    $item = $this->getItem();
    $id = $item->id;

    $product_override = ORM::factory("product_override")->where('item_id', "=",  $id)->find();

    if (!$product_override->loaded()){
      // no override found so check parents
      // check parents for product override
      $item = ORM::factory("item",$id);

      $parents = $item->parents();
      foreach ($parents as $parent){
        // check for product override
        $temp_override = ORM::factory("product_override")->where('item_id', "=", $parent->id)->find();
        if ($temp_override ->loaded()){
          $product_override = $temp_override;
          //break;
        }
      }
    }

    $product = $this->getProduct();
    $cost = $product->cost;
    if ($product_override->loaded()){
      $item_product = ORM::factory("item_product")
          ->where('product_override_id', "=", $product_override->id)
          ->where('product_id', "=", $product->id)->find();

      if ($item_product->loaded()){
        $cost = $item_product->cost;
      }
    }

    $this->cost = $cost * $this->quantity;
    $this->cost_per = $cost;
  }

  public function add($quantity){
    $this->quantity += $quantity;
    $this->calculate_cost();
  }

  public function size(){
    return $this->quantity;
  }

  public function getItem(){
     $photo = ORM::factory("item", $this->item);
     return $photo;
  }

  public function product_description(){
     $prod = ORM::factory("product", $this->product);
     return $prod->description;
  }

  public function getProduct(){
     $prod = ORM::factory("product", $this->product);
     return $prod;
   }

  public function getCode(){
     $photo = ORM::factory("item", $this->item);
     $prod = ORM::factory("product", $this->product);
     return $photo->id." - ".$photo->title." - ".$prod->name;
  }

}

class Session_Basket_Core {

  public $contents = array();

  public $name = "";
  public $house = "";
  public $street = "";
  public $suburb = "";
  public $town = "";
  public $postcode = "";
  public $email = "";
  public $phone = "";

  public $ppenabled = true;

  public function clear(){
    if (isset($this->contents)){
      foreach ($this->contents as $key => $item){
        unset($this->contents[$key]);
      }
    }
    $this->ppenabled = true;
  }

  public function enablepp()
  {
    $this->ppenabled = true;
  }

  public function disablepp()
  {
    $this->ppenabled = false;
  }

  public function ispp(){
    return $this->ppenabled;
  }


  private function create_key($product, $id){
    return "$product _ $id";
  }

  public function size(){
    $size = 0;
    if (isset($this->contents)){
      foreach ($this->contents as $product => $basket_item){
        $size += $basket_item->size();
      }
    }
    return $size;
  }

  public function add($id, $product, $quantity){

    $key = $this->create_key($product, $id);
    if (isset($this->contents[$key])){
      $this->contents[$key]->add($quantity);
    }
    else {
      $this->contents[$key] = new basket_item($product, $id, $quantity);
    }
  }

  public function remove($key){
    unset($this->contents[$key]);
  }

  public function postage_cost(){
    $postage_cost = 0;
    $postage_bands = array();
    $postage_quantities = array();
    if (isset($this->contents)){
      // create array of postage bands
      foreach ($this->contents as $product => $basket_item){
        $postage_band = $basket_item->getProduct()->postage_band;
        if (isset($postage_bands[$postage_band->id]))
        {
          $postage_quantities[$postage_band->id] += $basket_item->quantity;
        }
        else
        {
          $postage_quantities[$postage_band->id] = $basket_item->quantity;
          $postage_bands[$postage_band->id] = $postage_band;
        }
      }

      foreach ($postage_bands as $id => $postage_band){
        $postage_cost += $postage_band->flat_rate + ($postage_band->per_item * $postage_quantities[$id]);
      }
    }
    return $postage_cost;
  }

  public function cost(){
    $cost = 0;
    if (isset($this->contents)){
      foreach ($this->contents as $product => $basket_item){
        $cost += $basket_item->cost;
      }
    }
    return $cost;
  }

  public static function get(){
    return Session::instance()->get("basket");
  }

  public static function getOrCreate(){
    $session = Session::instance();

    $basket = $session->get("basket");
    if (!$basket)
    {
      $basket = new Session_Basket();
      $session->set("basket", $basket);
    }
    return $basket;
  }

}
