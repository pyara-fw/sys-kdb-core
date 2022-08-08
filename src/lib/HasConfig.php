<?php

namespace SysKDB\lib;

trait HasConfig
{
    /**
     * @var array
     */
    protected $config;




    /**
     * Get the value of config
     *
     * @return  array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set the value of config
     *
     * @param  array  $config
     *
     * @return  self
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }


    /**
     * Add a configuration item
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setConfigItem(string $key, mixed $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * Remove a configuration item and return:
     *  - true if the key exists and was removed
     *  - false if the key does not exists
     *
     * @param string $key
     * @return boolean
     */
    public function delConfigItem(string $key): bool
    {
        if (isset($this->config[$key])) {
            unset($this->config[$key]);
            return true;
        }

        return false;
    }

    public function hasConfigItem(string $key): bool
    {
        if (isset($this->config[$key])) {
            return true;
        }

        return false;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getConfigItem(string $key, mixed $default=null)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return $default;
    }
}
