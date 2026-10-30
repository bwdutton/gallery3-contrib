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
class Order_Model extends ORM {
  const WAITING_PAYMENT = 1;
  const PAYMENT_CONFIRMED= 2;

  const PAYMENT_PAYPAL = 1;
  const PAYMENT_OFFLINE = 2;

  public function title(){
    return  basket::getOrderPrefix().$this->id." ".$this->name." ".$this->status();
  }

  public function status(){
    return match ($this->status) {
        1 => "Waiting Payment",
        2 => "Payment Confirmed",
        20 => "Complete",
        default => "Unknown",
    };
  }

  public function payment_method(){
    return match ($this->method) {
        1 => "through Paypal",
        2 => "offline",
        default => "Unknown",
    };
  }
}
