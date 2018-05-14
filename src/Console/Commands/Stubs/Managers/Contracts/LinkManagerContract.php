<?php namespace App\Managers\Contracts;

use App\User;

interface LinkManagerContract extends ManagerContract
{
    /**
     * @param int $steamId
     *
     * @return string
     */
    public function getUserLinkBySteamId($steamId);

    /**
     * @param User|array $user
     * @param string $additionalCssClass
     *
     * @return string
     */
    public function getUserLink($user, $additionalCssClass = null);

    /**
     * @param string $link
     * @param string $btnClass
     * @param string $caption
     * @param array $additionalData
     * @param bool $requireConfirmation
     * @param array $prompt
     *
     * @return string
     */
    public function getLinkAsButton($link, $caption, $btnClass, array $additionalData = [], $requireConfirmation = false, $prompt = []);

    /**
     * @param string $name
     * @param string $caption
     * @param bool $isChecked
     *
     * @return string
     */
    public function getCheckbox($name, $caption, $isChecked = false);

    /**
     * @return array
     */
    public function getTranslations();

    /**
     * @param string $text
     *
     * @return string
     */
    public function wrapLinks($text);

    /**
     * @return string
     */
    public function getBuildQuery();
}
