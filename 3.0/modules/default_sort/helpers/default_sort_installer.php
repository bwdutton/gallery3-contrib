<?php defined("SYSPATH") or die("No direct script access.");

class default_sort_installer {
    private static function getversion() { return 1; }
    private static function setversion() { module::set_version("default_sort", self::getversion()); }

    public static function install() {
        self::setversion();
    }

    public static function upgrade($version) {
        if ($version < self::getversion())
            self::setversion();
    }

    public static function can_activate() {
    }

    public static function activate() {
    }

    public static function deactivate() {
    }

    public static function uninstall() {
    }
}

# vim: tabstop=4 softtabstop=4 shiftwidth=4 expandtab:
