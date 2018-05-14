<?php namespace App\Managers;

use App\Managers\Contracts\SettingManagerContract;
use App\Repositories\Contracts\SettingRepositoryContract;

class SettingManager extends Manager implements SettingManagerContract
{
    /**
     * @param string $group
     * @param string $name
     * @param null $default
     *
     * @return mixed
     */
    public function getSettingValue($group, $name, $default = null)
    {
        $setting = $this->getSettingRepository()->getOne($group, $name);

        if (null === $setting) {
            return $default;
        }

        if (!is_numeric($setting->value)) {
            try {
                $json = json_decode($setting->value);

                return $json !== null
                    ? $json
                    : $setting->value;
            } catch (\Exception $e) {
                return $setting->value;
            }
        }

        return $setting->value;
    }

    /**
     * @param string $group
     * @param string $name
     * @param string|int $value
     */
    public function setSettingValue($group, $name, $value)
    {
        $setting = $this->getSettingRepository()->getOne($group, $name);

        if (is_array($value)) {
            $value = json_encode($value);
        }

        if (null === $setting) {
            $this->getSettingRepository()->create([
                'group' => $group,
                'name' => $name,
                'value' => (string) $value,
            ]);

            return;
        }

        $this->getSettingRepository()->update($setting->id, [
            'value' => (string) $value,
        ]);
    }
}