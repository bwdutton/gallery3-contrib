<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2011 Bharat Mediratta
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
class Bp_Order_Log_Model extends ORM {

//order processing log
  const ORDERED = 1;
	const ORDERED_LOG_TEXT = "Order completed";
  const PAID = 2;
  const LATE_PAYMENT = 3;
  const COPY_SENT = 9;
  const DELIVERED_NOTPAID = 10;
  const DELIVERED = 20;
  const EXPIRED = 30;
	const DELAYED = 40;
  const CANCELLED = 99;

  public function status(){
    return match ($this->status) {
        1 => t("Waiting Payment"),
        2 => t("Payment Confirmed"),
        3 => t("Late Payment"),
        10 => t("Delivered,w/o payment"),
        20 => t("Complete"),
        21 => t("Expired"),
        99 => t("Cancelled"),
        default => t("Unknown"),
    };
  }

  public function event(){
    return match ($this->event) {
        self::ORDERED => t("Order confirmation sent"),
        self::PAID => t("Payment confirmation sent"),
        self::LATE_PAYMENT => t("Late Payment notification sent"),
        self::COPY_SENT => t("Order confirmation Copy sent"),
        self::DELIVERED_NOTPAID => t("Delivery without Payment notification sent"),
        self::DELIVERED => t("Delivery notification sent"),
        self::DELAYED => t("Delay notification sent"),
        self::EXPIRED => t("Expiration notification sent"),
        self::CANCELLED => t("Cancellation notification sent"),
        default => t("Unknown"),
    };
  }
}
