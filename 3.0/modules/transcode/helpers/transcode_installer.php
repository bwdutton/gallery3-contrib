<?php defined("SYSPATH") or die("No direct script access.") ?><?php

class transcode_installer {

    private static function getversion() { return 10; }
    private static function setversion() { module::set_version("transcode", self::getversion()); }

    public static function install() {
        $db = Database::instance();
        $db->query("CREATE TABLE IF NOT EXISTS {transcode_resolutions} (
                        `id` int(9) NOT NULL auto_increment,
                        `item_id` int(9) NOT NULL,
                        `resolution` varchar(16) NOT NULL,
                        PRIMARY KEY (`id`)
        ) DEFAULT CHARSET=utf8;");

        @mkdir(VARPATH . "modules/transcode");
        @mkdir(VARPATH . "modules/transcode/log");
        @mkdir(VARPATH . "modules/transcode/flv");

        self::setversion();
    }
    public static function uninstall() {
        Database::instance()->query("DROP TABLE {transcode_resolutions}");
        dir::unlink(VARPATH . "modules/transcode");
    }

    public static function upgrade($version) {
        if ($version < self::getversion())
            self::setversion();
    }

    public static function deactivate() {}
    public static function activate() {}
    public static function can_activate() {
        $messages = [];
        if (!function_exists("exec")) {
            $messages["warn"][] = t("exec() is required to auto-detect the ffmpeg binary. You must specify the path to the ffmpeg binary manually before you can convert videos.");
        }
        return $messages;
    }
}
