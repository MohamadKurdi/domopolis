<?php

namespace hobotix;

final class Updater
{

    private $CURRENT_VERSION = null;
    private $GLOBAL_VERSION = null;

    public function __construct(){
        echoLine('[Updater::_construct] Hello, I am updater', 'd');

        $this->CURRENT_VERSION = trim(file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'update' . DIRECTORY_SEPARATOR . 'CURRENT'));
        $this->GLOBAL_VERSION = trim(file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'update' . DIRECTORY_SEPARATOR . 'GLOBAL'));

        if ($this->compare() < 0){
            echoLine('[Updater::_construct] It seems to me that we need to update something', 'e');
            $this->update();
        }
    }

    public function compare()
    {
        echoLine('[Updater::compare] Current version is ' . $this->CURRENT_VERSION, 's');
        echoLine('[Updater::compare] Global version is ' . $this->GLOBAL_VERSION, 's');

        return version_compare($this->CURRENT_VERSION, $this->GLOBAL_VERSION);
    }

    public function current(){
        echoLine('[Updater::current] Setting current version to ' . $this->GLOBAL_VERSION, 's');
        file_put_contents(dirname(__FILE__) . '/update/CURRENT', $this->GLOBAL_VERSION);
    }

    public function update(){
        if (function_exists('exec')) {
            echoLine('[Updater::update] Executing composer install', 'w');
            exec('composer install');

            echoLine('[Updater::install] Executing npm install', 'w');
            exec('cd ./js && npm install');
        } else {
            echoLine('[Updater::install] exec() function is forbidden, run composer install and npm install manually', 'e');
        }

        if (is_file(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'update' . DIRECTORY_SEPARATOR . 'update-db-structure.sql')){
            require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'db.php');
            require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR . 'cache.php');

            $db = new \DB(\DB_DRIVER, \DB_HOSTNAME, \DB_USERNAME, \DB_PASSWORD, \DB_DATABASE);

            echoLine('[Updater::update] SQL update file exists, running queries', 'i');
            try {
                $db->importSQL(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'update' . DIRECTORY_SEPARATOR . 'update-db-structure.sql');
            } catch (\Exception $e){
                echoLine('[Updater::update] Caught exception, maybe tables already exist!', 'w');

                if (preg_match('/Table \'(.+)\' already exists/', $e->getMessage(), $matches)) {
                    echoLine('[Updater::update] Yep that is ok, table ' . $matches[1] . ' just exists', 's');
                } else {
                    echoLine('[Updater::update] Well, this is not table exists message, dying. ' . $e->getMessage(), 'e');
                    die();
                }
            }
        }

        $this->current();
    }
}