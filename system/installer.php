<?php

namespace hobotix;

final class Installer
{
    private const default_domain = 'store.domain';
    private const admin_directory = 'admin';

    public function __construct()
    {
        if (is_file(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.parts' . DIRECTORY_SEPARATOR . 'INSTALLED')) {
            echoLine('[Installer::_construct] Found installation-ready marker file, exiting', 'e');
            echoLine('[Installer::_construct] If you want to do some reinstallation, remove system/config.parts/INSTALLED file and rerun', 'i');
            die();
        }

        return $this;
    }

    public function preinstall(): void
    {
        if (!is_file(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.php')) {
            echoLine('[Installer::preinstall] Copying config.sample.php to config.php', 'w');
            copy(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.sample.php', dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.php');

            echoLine('[Installer::preinstall] Copying config.parts.sample directory', 'w');
            self::copyDirectory(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.parts.sample', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.parts', false);

            echoLine('[Installer::preinstall] Copying config.sample directory', 'w');
            self::copyDirectory(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.sample', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config', false);
        } else {
            echoLine('[Installer::preinstall] Main config already exists', 's');
        }

        if (!is_file(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . self::admin_directory . DIRECTORY_SEPARATOR . 'config.php')) {
            echoLine('[preinstall] Copying admin/config.sample.php to config.php', 'w');
            copy(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . self::admin_directory . DIRECTORY_SEPARATOR . 'config.sample.php', dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . self::admin_directory . DIRECTORY_SEPARATOR . 'config.php');

            echoLine('[preinstall] Copying admin/config.parts.sample directory', 'w');
            self::copyDirectory(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . self::admin_directory . DIRECTORY_SEPARATOR . 'config.parts.sample', dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . self::admin_directory . DIRECTORY_SEPARATOR . 'config.parts', false);
        }  else {
            echoLine('[Installer::preinstall] Main admin config already exists', 's');
        }

        echoLine('[Installer::preinstall] Now edit your config files and run cli.php catalog config.php install', 'i');
    }

    public function install(): void
    {
        if (is_dir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.parts' . DIRECTORY_SEPARATOR)) {
            if (defined('DIR_PIDS')) {
                self::mkdir_with_echo(DIR_PIDS);
            }

            if (defined('DIR_LOGS')) {
                self::mkdir_with_echo(DIR_LOGS);
            }

            if (defined('DIR_CACHE')) {
                self::mkdir_with_echo(DIR_CACHE);
            }

            if (defined('DIR_CACHE') && defined('PAGECACHE_DIR')) {
                self::mkdir_with_echo(DIR_CACHE . PAGECACHE_DIR);
            }

            if (defined('DIR_IMAGECACHE')) {
                self::mkdir_with_echo(DIR_IMAGECACHE);
            }

            if (defined('DIR_IMAGE')) {
                self::mkdir_with_echo(DIR_IMAGE);
            }

            if (defined('DIR_MINIFIED')) {
                self::mkdir_with_echo(DIR_MINIFIED);
            }

            if (defined('DIR_REFEEDS')) {
                self::mkdir_with_echo(DIR_REFEEDS);
            }

            if (defined('DIR_SITEMAPS_CACHE')) {
                self::mkdir_with_echo(DIR_SITEMAPS_CACHE);
            }

            if (defined('DIR_EXPORT')) {
                self::mkdir_with_echo(DIR_EXPORT);
            }

            if (defined('DIR_SYSTEM')) {
                self::mkdir_with_echo(DIR_SYSTEM . 'temp');
            }

            if (defined('DIR_ENGINE')) {
                self::mkdir_with_echo(DIR_ENGINE . 'pwa');
                self::mkdir_with_echo(DIR_ENGINE . 'pwa' . DIRECTORY_SEPARATOR . 'assetlinks');
                self::mkdir_with_echo(DIR_ENGINE . 'pwa' . DIRECTORY_SEPARATOR . 'icons');
                self::mkdir_with_echo(DIR_ENGINE . 'pwa' . DIRECTORY_SEPARATOR . 'manifest');
                self::mkdir_with_echo(DIR_ENGINE . 'pwa' . DIRECTORY_SEPARATOR . 'screenshots');

                self::mkdir_with_echo(DIR_ENGINE . 'icons');
                self::mkdir_with_echo(DIR_ENGINE . 'ep');

                self::mkdir_with_echo(DIR_ENGINE . self::admin_directory . DIRECTORY_SEPARATOR . 'icon');
                self::mkdir_with_echo(DIR_ENGINE . self::admin_directory . DIRECTORY_SEPARATOR . 'pwa');
                self::mkdir_with_echo(DIR_ENGINE . self::admin_directory . DIRECTORY_SEPARATOR . 'ep');
                self::mkdir_with_echo(DIR_ENGINE . self::admin_directory . DIRECTORY_SEPARATOR . 'app');
            }

        } else {
            echoLine('[Installer::install]Not found configuration files, exiting', 'e');
        }

        echoLine('[Installer::install] Importing main database structure', 'w');
        require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'db.php');
        require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'cache.php');

        if (defined('DB_USERNAME') && DB_USERNAME != 'DB_USERNAME'){
            $db = new \DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

            $query = $db->query("SHOW TABLES LIKE 'setting'");

            if ($query->num_rows){
                echoLine('[Installer::install] Main database is already imported, skipping', 'w');
            } else {
                if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'structure-demo.sql')){
                    echoLine('[Installer::install] Demo data exists, importing it', 'i');
                    $db->importSQL(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'structure-demo.sql');
                } else {
                    echoLine('[Installer::install] Demo data does not exist, importing just structure', 'i');
                    $db->importSQL(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'structure-main.sql');
                }
            }
        } else {
            echoLine('[Installer::install] No database config set, skipping', 'w');
        }

        echoLine('[Installer::install] Importing sessions database structure', 'w');
        if (defined('DB_SESSION_USERNAME') && DB_SESSION_USERNAME != 'DB_SESSION_USERNAME'){
            $db = new \DB(DB_DRIVER, DB_SESSION_HOSTNAME, DB_SESSION_USERNAME, DB_SESSION_PASSWORD, DB_SESSION_DATABASE);

            $query = $db->query("SHOW TABLES LIKE 'sessions'");

            if ($query->num_rows){
                echoLine('[Installer::install] Session database is already imported, skipping', 'w');
            } else {
                $db->importSQL(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'structure-session.sql');
            }
        } else {
            echoLine('[Installer::install] No session database config set, skipping', 'w');
        }

        if (function_exists('exec')) {
            echoLine('[Installer::install] Executing composer install', 'w');
            exec('composer install');

            echoLine('[Installer::install] Executing npm install', 'w');
            exec('cd ./js && npm install');
        } else {
            echoLine('[Installer::install] exec() function is forbidden, run composer install and npm install manually', 'e');
        }
    }

    public function setdomain($domain): void
    {
        echoLine('[Installer::setdomain] Setting domain in config files: ' . $domain, 'w');
        self::replaceStringInFiles(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config', self::default_domain, $domain);
        self::replaceStringInFiles(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.parts', self::default_domain, $domain);
        self::replaceStringInFiles(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . self::admin_directory . DIRECTORY_SEPARATOR . 'config.parts', self::default_domain, $domain);

        touch(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.parts' . DIRECTORY_SEPARATOR . 'INSTALLED');
    }

    private static function mkdir_with_echo($dir)
    {
        echoLine('[Installer::mkdir]' . $dir, 'w');

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        } else {
            echoLine('[Installer::mkdir] directory exists!', 's');
        }
    }

    /**
     * Recursively scan a directory and replace a string in all files
     *
     * @param string $dir Directory path to scan
     * @param string $search String to search for
     * @param string $replace String to replace with
     * @return bool True on success, false on failure
     */
    private static function replaceStringInFiles($dir, $search, $replace)
    {
        $files = array_diff(scandir($dir), array('.', '..'));

        foreach ($files as $file) {
            $filePath = $dir . DIRECTORY_SEPARATOR . $file;

            if (is_dir($filePath)) {
                self::replaceStringInFiles($filePath, $search, $replace);
            } else {
                $contents = file_get_contents($filePath);
                $newContents = str_replace($search, $replace, $contents);

                if ($newContents !== $contents) {
                    echoLine('[Installer::replaceStringInFiles] Replaced domain in ' . $filePath, 'w');
                    file_put_contents($filePath, $newContents);
                }
            }
        }

        return true;
    }

    /**
     * Copy a directory from one location to another recursively
     *
     * @param string $source Source directory path
     * @param string $destination Destination directory path
     * @param bool $overwrite Overwrite existing files and directories
     * @return bool True on success, false on failure
     */
    private static function copyDirectory($source, $destination, $overwrite = false)
    {
        if (!is_dir($source)) {
            return false;
        }

        if (!is_dir($destination)) {
            echoLine('[copyDirectory] Making directory ' . $destination, 'w');
            mkdir($destination, 0755, true);
        }

        foreach (scandir($source) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $sourceFile = $source . DIRECTORY_SEPARATOR . $file;
            $destinationFile = $destination . DIRECTORY_SEPARATOR . $file;

            if (is_dir($sourceFile)) {
                self::copyDirectory($sourceFile, $destinationFile, $overwrite);
            } else {
                if ($overwrite || !file_exists($destinationFile)) {
                    echoLine('[Installer::copyDirectory] Copying file ' . $destinationFile, 'w');
                    copy($sourceFile, $destinationFile);
                } else {
                    echoLine('[Installer::copyDirectory] Skipping file ' . $destinationFile, 's');
                }
            }
        }

        return true;
    }
}