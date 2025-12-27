<?php
class Config {
    public static $DB_HOST = "localhost";
    public static $DB_NAME = "realtime_db";
    public static $DB_USER = "root";
    public static $DB_PASS = "";
    public static $DB_CHARSET = "utf8mb4";
    
    public static $APP_NAME = "Idol Management API";
    public static $DEBUG_MODE = true;
    
    // Tambah ini
    public static function getBasePath() {
        return dirname(__DIR__); // C:\laragon\www\application-tier-php
    }
}