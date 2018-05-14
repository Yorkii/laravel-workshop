<?php namespace App\Managers\Contracts;

interface SettingManagerContract extends ManagerContract
{
    /**
     * @param string $group
     * @param string $name
     * @param null $default
     *
     * @return mixed
     */
    public function getSettingValue($group, $name, $default = null);

    /**
     * @param string $group
     * @param string $name
     * @param string|int $value
     */
    public function setSettingValue($group, $name, $value);
}
