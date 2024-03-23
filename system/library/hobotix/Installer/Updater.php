<?php

namespace hobotix\Installer;

final class Updater
{

    private $currentVersion = null;
    private $globalVersion = null;

    public function __construct()
    {
        $this->currentVersion = trim(file_get_contents(DIR_SYSTEM . DIRECTORY_SEPARATOR . 'update' . DIRECTORY_SEPARATOR . 'CURRENT'));
        $this->globalVersion = trim(file_get_contents(DIR_SYSTEM . DIRECTORY_SEPARATOR . 'update' . DIRECTORY_SEPARATOR . 'GLOBAL'));
    }

    public function get_current(): string
    {
        return $this->currentVersion;
    }

    public function get_global(): string
    {
        return $this->globalVersion;
    }

    public function check(): void
    {
        echoLine('[Updater::check] Hello, I am updater', 'd');

        if ($this->compare() < 0) {
            echoLine('[Updater::check] It seems to me that we need to update something', 'e');
            $this->update();
        }
    }

    public function last_commit(): string|bool
    {
        if (function_exists('proc_open') && class_exists('\CzProject\GitPhp\Git')) {
            try {
                $gitObject = new \CzProject\GitPhp\Git;
                $repoObject = $gitObject->open(DIR_SYSTEM . '..' . DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR);

                return $repoObject->getLastCommitId();
            } catch (\CzProject\GitPhp\GitException $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * Compare the current version with the global version.
     *
     * @return int|bool Returns an integer less than, equal to, or greater than zero
     *              if the current version is respectively less than, equal to,
     *              or greater than the global version.
     */
    public function compare(): int|bool
    {
        echoLine('[Updater::compare] Current version is ' . $this->currentVersion, 's');
        echoLine('[Updater::compare] Global version is ' . $this->globalVersion, 's');

        return version_compare($this->currentVersion, $this->globalVersion);
    }

    /**
     * Update the current version to the global version.
     *
     * @return void
     */
    public function current(): void
    {
        echoLine('[Updater::current] Setting current version to ' . $this->globalVersion, 's');
        file_put_contents(DIR_SYSTEM . DIRECTORY_SEPARATOR . 'update' . DIRECTORY_SEPARATOR . 'CURRENT', $this->globalVersion);
    }

    /**
     * Update the application by running composer install, npm install,
     * and applying database structure updates.
     *
     * @return void
     */
    public function update(): void
    {
        if (function_exists('exec')) {
            echoLine('[Updater::update] Executing composer install', 'w');
            exec('composer install');

            echoLine('[Updater::install] Executing npm install', 'w');
            exec('cd ./js && npm install');
        } else {
            echoLine('[Updater::install] exec() function is forbidden, run composer install and npm install manually', 'e');
        }

        require_once(DIR_SYSTEM . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'db.php');
        require_once(DIR_SYSTEM . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'cache.php');

        $db = new \DB(\DB_DRIVER, \DB_HOSTNAME, \DB_USERNAME, \DB_PASSWORD, \DB_DATABASE);

        for ($i = 1; $i <= 3; $i++) {
            if (is_file(DIR_SYSTEM . DIRECTORY_SEPARATOR . 'update' . DIRECTORY_SEPARATOR . 'update-db-structure-' . $i . '.sql')) {
                echoLine('[Updater::update] SQL update file ' . 'update-db-structure-' . $i . '.sql' . ' exists, running queries', 'i');
                try {
                    $db->importSQL(DIR_SYSTEM . DIRECTORY_SEPARATOR . 'update' . DIRECTORY_SEPARATOR . 'update-db-structure-' . $i . '.sql');
                } catch (\Exception $e) {
                    echoLine('[Updater::update] Caught exception, maybe tables already exist!', 'w');

                    if (preg_match('/Table \'(.+)\' already exists/', $e->getMessage(), $matches)) {
                        echoLine('[Updater::update] Yep that is ok, table ' . $matches[1] . ' just exists', 's');
                    } else {
                        echoLine('[Updater::update] Well, this is not table exists message, dying. ' . $e->getMessage(), 'e');
                        die();
                    }
                }
            }
        }


        $this->current();
    }
}