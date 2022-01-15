<?php

namespace Jinwork;

use Jinwork\Exception\InvalidConfigurationKeyException;
use SplFileObject;
use stdClass;
use Jinwork\Exception\MissingConfigFileException;

/**
 * @since 1.0.0-alpha
 */
class Config
{
    private const VALID_KEYS = [
      'domain',
      'is_ssl',
      'url',
      'site_name'
    ];

    /**
     * @var string
     */
    private string $config_file;

    /**
     * @var stdClass
     */
    private stdClass $config;

    /**
     * @since 1.0.0-alpha
     * @throws MissingConfigFileException
     */
    public function __construct()
    {
        if(isset($_ENV['JINWORK_CONFIG']) && is_string($_ENV['JINWORK_CONFIG'])) {
            $this->config_file = $_ENV['JINWORK_CONFIG'];
        } else {
            throw New MissingConfigFileException('Missing file path to configuration file.');
        }

        $this->parseConfigFile();
    }

    /**
     * Returns the value associated with the given key or <b>NULL</b> if the key does not exist
     *
     * @param string $key
     *
     * @return string|bool|int|float|null
     * @since 1.0.0-alpha
     */
    public function getSafe(string $key): string|bool|int|float|null
    {
        return $this->config->$key ?? null;
    }

    /**
     * Returns the value associated with the given key or throws an error if the key does not exist
     *
     * @param string $key
     *
     * @return string|bool|int|float|null
     * @throws InvalidConfigurationKeyException
     * @since 1.0.0-alpha
     */
    public function get(string $key): string|bool|int|float|null
    {
        if(isset($this->config->$key)) {
            return $this->config->$key;
        }

        throw new InvalidConfigurationKeyException();
    }

    /**
     * @throws MissingConfigFileException
     *
     * @since 1.0.0-alpha
     */
    private function parseConfigFile()
    {
        if(!is_readable($this->config_file)) {
            throw new MissingConfigFileException();
        }

        foreach (new SplFileObject($this->config_file) as $line) {
            $matches = [];
            $matched = preg_match('/(\w+)\s*=\s*(\w+)/', $line, $matches);

            if(!$matched) continue;

            $key = $matches[1];

            $is_valid = $this->isValidKey($key);

            if(!$is_valid) {
                trigger_error("Jinwork Config: Invalid key \"$key\" used in config. Ignoring value.", E_WARNING);
                continue;
            }

            $value = $matches[2];
            $value_length = mb_strlen($value);

            // Check if the value is a boolean TRUE or FALSE
            $is_bool = false;
            if((5 === $value_length || 4 === $value_length)) {
                switch(strtolower($value)) {
                    case 'true':
                        $value = true;
                        $is_bool = true;
                        break;
                    case 'false':
                        $value = false;
                        $is_bool = true;
                }
            }

            // Check if the value is an int or float
            if(!$is_bool && (($is_numeric = is_numeric($value)) || is_float($value))) {
                $value = $is_numeric ? intval($value) : floatval($value);
            }

            $this->config->$key = $value;
        }
    }

    /**
     * Returns <b>TRUE</b> if the key is an allowed and valid key, otherwise returns <b>FALSE</b>
     *
     * @param string $key
     * @return bool
     * @since 1.0.0-alpha
     */
    private function isValidKey(string $key): bool
    {
        return in_array($key, self::VALID_KEYS);
    }
}