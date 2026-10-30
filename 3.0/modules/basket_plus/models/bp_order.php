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
class Bp_Order_Model extends ORM {
  const WAITING_PAYMENT = 1;
  const PAYMENT_CONFIRMED = 2;
  const DELIVERED_NOTPAID = 10;
  const DELIVERED = 20;
  const EXPIRED = 21;
  const CANCELLED = 99;

// Payment methods
  const PAYMENT_OFFLINE = 1;
  const PAYMENT_PAYPAL = 2;

// Delivery methods
  const DELIVERY_MAIL = 2;
  const DELIVERY_EMAIL = 3;
  const DELIVERY_PICKUP = 4;

	//Fixed texts for order screens and order e-mail
  const DELIVERY_MAIL_TEXT = "Delivery at home address by mail";
  const DELIVERY_EMAIL_TEXT = "Delivery by e-mail";
  const DELIVERY_PICKUP_TEXT = "Pickup at";
	
/*
 * USED IN: view order (admin)
 * DESCRIPTION: shows order title
 */
  public function title(){
    return  basket_plus::getBasketVar(ORDER_PREFIX).$this->id." ".$this->name." ".t($this->status());
  }

/*
 * USED IN: views orders and order_logs (admin)
 * DESCRIPTION: shows order status
 */
  public function status(){
//@TODO add completed+delivery method
    return match ($this->status) {
        self::WAITING_PAYMENT => "Waiting Payment",
        self::PAYMENT_CONFIRMED => "Payment confirmed",
        self::DELIVERED_NOTPAID => "Delivered without payment",
        self::DELIVERED => "Delivered",
        self::EXPIRED => "Expired",
        self::CANCELLED => "Cancelled",
        default => "Unknown",
    };
  }

/*
 * USED IN: view order (admin)
 * DESCRIPTION: shows order payment method
 */
  public function payment_method(){
    return match ($this->payment_method) {
        Bp_Order_Model::PAYMENT_OFFLINE => t("Offline Payment"),
        Bp_Order_Model::PAYMENT_PAYPAL => t("Paypal Payment"),
        default => t("Unknown Payment method"),
    };
  }

/*
 * USED IN: view order (admin)
 * DESCRIPTION: shows order delivery method
 */
  public function delivery_method(){
    return match ($this->delivery_method) {
        Bp_Order_Model::DELIVERY_MAIL => t("Mail"),
        Bp_Order_Model::DELIVERY_EMAIL => t("Email"),
        Bp_Order_Model::DELIVERY_PICKUP => t("Pickup"),
        default => t("Unknown Delivery method"),
    };
  }
}
