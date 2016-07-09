<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
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
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    3.0.0
 */
class Version
{

    /**
     * Current version of the core popphp/popphp
     */
    const VERSION = '3.0.0';

    /**
     * Version source from GitHub
     */
    const VERSION_SOURCE_GITHUB = 'GITHUB';

    /**
     * Version source from www.popphp.org
     */
    const VERSION_SOURCE_POP = 'POP';

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
     * @param  string $source
     * @return mixed
     */
    public static function getLatest($source = 'POP')
    {
        return ($source == self::VERSION_SOURCE_GITHUB) ? self::getLatestFromGitHub() : self::getLatestFromPop();
    }

    /**
     * Returns the latest version available from GitHub.
     *
     * @return mixed
     */
    public static function getLatestFromGitHub()
    {
        $latest = null;

        $context = stream_context_create([
            'http' => [
                'user_agent' => sprintf('Pop-Version/%s', self::VERSION),
            ],
        ]);
        $json   = json_decode(
            file_get_contents('https://api.github.com/repos/popphp/popphp-framework/releases/latest', false, $context), true
        );
        $latest = $json['tag_name'];

        return trim($latest);
    }

    /**
     * Returns the latest version available from www.popphp.org.
     *
     * @return mixed
     */
    public static function getLatestFromPop()
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
     * @param  string $source
     * @return mixed
     */
    public static function isLatest($source = 'POP')
    {
        return (self::compareVersion(self::getLatest($source)) >= 0);
    }

    /**
     * Checks the system environment and dependencies and returns the results
     *
     * @param  string $source
     * @return array
     */
    public static function systemCheck($source = 'POP')
    {
        $pdoDrivers = (class_exists('Pdo', false)) ? \PDO::getAvailableDrivers() : [];
        $latest     = self::getLatest($source);

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
                'image' => [
                    'gd'       => (function_exists('getimagesize')),
                    'gmagick'  => (class_exists('Gmagick', false)),
                    'imagick'  => (class_exists('Imagick', false))
                ],
                'ldap'     => (function_exists('ldap_connect')),
                'mcrypt'   => (function_exists('mcrypt_encrypt')),
                'memcache' => (class_exists('Memcache', false)),
                'redis'    => (class_exists('Redis', false)),
                'soap'     => (class_exists('SoapClient', false)),
                'yaml'     => (function_exists('yaml_parse'))
            ]
        ];

        return $system;
    }

}
