<?php
namespace consultnn\grid\plugins;


interface SettingsStorageInterface
{

    /**
     * Check settings is set
     * @param $name
     * @return bool
     */
    function has($name);

    /**
     * get settings
     * @param $name
     * @return mixed
     */
    function get($name);

    /**
     * set settings
     * @param $name
     * @param $value
     * @return void
     */
    function set($name, $value);

}