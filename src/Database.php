<?php
namespace WshopApi;

/**
 * Class Database
 * @package WshopApi
 */
class Database
{
    private $instance = null;
    private $path = '../database.ini';

    /**
     * Return connexion database
     *
     * @return PDO
     */
    public function getPdo(): \PDO
    {
        $arrayConfig = parse_ini_file($this->path, true);


        if (
            !array_key_exists('DB_CONNECTION', $arrayConfig) ||
            !array_key_exists('DB_HOST', $arrayConfig) ||
            !array_key_exists('PORT', $arrayConfig) ||
            !array_key_exists('DB_NAME', $arrayConfig) ||
            !array_key_exists('CHARSET', $arrayConfig) ||
            !array_key_exists('DB_USER', $arrayConfig) ||
            !array_key_exists('DB_PASSWORD', $arrayConfig)
        ) {
            throw new \Exception('Missing config informations');
        }
        if ($this->instance === null) :
            $db_user = (string) $arrayConfig['DB_USER'];
            $db_password = (string) $arrayConfig['DB_PASSWORD'];

            $this->instance = new \PDO($arrayConfig["DB_CONNECTION"] . ':dbname=' . $arrayConfig["DB_NAME"] . ';charset=' . $arrayConfig["CHARSET"] . ';host=' . $arrayConfig["DB_HOST"], $db_user, $db_password);
            $this->instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->instance->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        endif;
        return $this->instance;
    }
}
