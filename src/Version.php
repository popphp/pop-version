<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2015 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Version;

/**
 * Version class
 *
 * @category   Pop
 * @package    Pop_Version
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2015 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0
 */
class Version
{

    /**
     * Current version
     */
    const VERSION = '2.0.0';

    /**
     * Compares the local version to the latest version available
     *
     * @param  string $version
     * @return mixed
     */
    public static function compareVersion($version)
    {
        return version_compare(self::VERSION, $version);
    }

    /**
     * Returns the latest version available.
     *
     * @return mixed
     */
    public static function getLatest()
    {
        $latest = null;

        $handle = fopen('http://www.popphp.org/version', 'r');
        if ($handle !== false) {
            $latest = stream_get_contents($handle);
            fclose($handle);
        }

        return trim($latest);
    }

    /**
     * Returns whether or not this is the latest version.
     *
     * @return mixed
     */
    public static function isLatest()
    {
        return (self::compareVersion(self::getLatest()) >= 0);
    }

    /**
     * Checks the system environment and dependencies and returns the results
     *
     * @return array
     */
    public static function systemCheck()
    {
        $pdoDrivers = (class_exists('Pdo', false)) ? \PDO::getAvailableDrivers() : [];
        $latest     = self::getLatest();

        // Define initial system environment
        $system = [
            'pop' => [
                'installed' => self::VERSION,
                'latest'    => $latest,
                'compare'   => version_compare(self::VERSION, $latest)
            ],
            'php' => [
                'installed' => PHP_VERSION,
                'required'  => '5.4.0',
                'compare'   => version_compare(PHP_VERSION, '5.4.0')
            ],
            'windows' => (stripos(PHP_OS, 'win') !== false),
            'environment' => [
                'apc'     => (function_exists('apc_add')),
                'archive' => [
                    'tar'  => (class_exists('Archive_Tar')),
                    'rar'  => (class_exists('RarArchive', false)),
                    'zip'  => (class_exists('ZipArchive', false)),
                    'bz2'  => (function_exists('bzcompress')),
                    'zlib' => (function_exists('gzcompress'))
                ],
                'curl' => (function_exists('curl_init')),
                'db'   => [
                    'mysqli' => (class_exists('mysqli', false)),
                    'oracle' => (function_exists('oci_connect')),
                    'pdo'    => [
                        'mysql'  => (in_array('mysql', $pdoDrivers)),
                        'pgsql'  => (in_array('pgsql', $pdoDrivers)),
                        'sqlite' => (in_array('sqlite', $pdoDrivers)),
                        'sqlsrv' => (in_array('sqlsrv', $pdoDrivers))
                    ],
                    'pgsql'  => (function_exists('pg_connect')),
                    'sqlite' => (class_exists('Sqlite3', false)),
                    'sqlsrv' => (function_exists('sqlsrv_connect'))
                ],
                'dom' => [
                    'dom_document' => (class_exists('DOMDocument', false)),
                    'simple_xml'   => (class_exists('SimpleXMLElement', false))
                ],
                'ftp'   => (function_exists('ftp_connect')),
                'geoip' => (function_exists('geoip_db_get_all_info')),
                'image' => [
                    'gd'       => (function_exists('getimagesize')),
                    'gmagick'  => (class_exists('Gmagick', false)),
                    'imagick'  => (class_exists('Imagick', false))
                ],
                'ldap'     => (function_exists('ldap_connect')),
                'mcrypt'   => (function_exists('mcrypt_encrypt')),
                'memcache' => (class_exists('Memcache', false)),
                'soap'     => (class_exists('SoapClient', false)),
                'yaml'     => (function_exists('yaml_parse'))
            ]
        ];

        return $system;
    }

}
