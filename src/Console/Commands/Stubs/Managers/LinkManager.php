<?php namespace App\Managers;

use App\Managers\Contracts\LinkManagerContract;
use App\User;
use Lang;
use Auth;
use App;
use Cache;

class LinkManager extends Manager implements LinkManagerContract
{
    /**
     * @param int $steamId
     *
     * @return string
     */
    public function getUserLinkBySteamId($steamId)
    {
        return $this->getUserLink($this->getUserRepository()->getBySteamId($steamId));
    }

    /**
     * @param User|array $user
     * @param string $additionalCssClass
     *
     * @return string
     */
    public function getUserLink($user, $additionalCssClass = null)
    {
        if ($user instanceof User) {
            $user = $user->toArray();
        }

        if (null !== $user && !is_array($user)) {
            $user = $this->getUserRepository()->find($user);
            $user = null !== $user
                ? $user->toArray()
                : null;
        }

        $class = '';

        if ($additionalCssClass) {
            $class = 'class="' . $additionalCssClass . '"';
        }

        if (!isset($user['id']) || !isset($user['name'])) {
            return '<a ' . $class . ' href="#">Unknown user</a>';
        }

        return '<a ' . $class . ' href="' . route('admin.users.single', ['id' => $user['id']]) . '">' . $user['name'] . '</a>';
    }

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
    public function getLinkAsButton($link, $caption, $btnClass, array $additionalData = [], $requireConfirmation = false, $prompt = [])
    {
        $inputs = '';

        if (!empty($additionalData)) {
            foreach ($additionalData as $key => $value) {
                $inputs .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />';
            }
        }

        $button = '<button class="' . $btnClass . '">' . $caption . '</button>';

        if (!$requireConfirmation && empty($prompt)) {
            return '<form style="display: inline-block;" method="post" action="' . $link . '">' . csrf_field() . $inputs . $button . '</form>';
        }

        $id = uniqid();
        $confirm = '';
        $prompts = '';
        $index = 0;

        foreach ($prompt as $prom) {
            $inputs .= '<input type="hidden" name="' . $prom['name'] . '" id="' . $id . '-' . $prom['name'] . '"/>';
            $prompts .= 'var prompt_' . $index . ' = prompt("' . $prom['caption'] . '"); jQuery("#' . $id . '-' . $prom['name'] . '").val(prompt_' . $index . ');';
        }

        if ($requireConfirmation) {
            $confirm = 'if (!confirm(\'Are you sure?\')) {'
                . 'e.preventDefault();'
            . '}';
        }

        return '<form style="display: inline-block;" method="post" action="' . $link . '" id="form_' . $id . '">' . csrf_field() . $inputs . $button . '</form>'
            . '<script>'
            . 'waitFor(\'jQuery\', function () {'
                . 'jQuery(document).ready(function () {'
                    . 'jQuery(\'#form_' . $id . '\').submit(function (e) {'
                        . $confirm
                        . $prompts
                    . '});'
                . '});'
            . '});'
            . '</script>';
    }

    /**
     * @param string $name
     * @param string $caption
     * @param bool $isChecked
     *
     * @return string
     */
    public function getCheckbox($name, $caption, $isChecked = false)
    {
        return '<input type="hidden" class="flat-blue" name="' . $name . '" value="0" />'
            . '<input id="checkbox-' . $name . '" type="checkbox" class="magic-checkbox" name="' . $name . '" value="1" ' . ($isChecked ? 'checked' : '') . '/>'
            . '<label for="checkbox-' . $name . '">'
            . $caption
            . '</label>';
    }

    /**
     * @return array
     */
    public function getTranslations()
    {
        $translations = [
            'auth',
            'pagination',
            'passwords',
            'validation',
        ];
        $result = [];

        foreach ($translations as $translation) {
            $strings = Lang::get($translation, [], 'en');
            $strings = array_merge($strings, Lang::get($translation));

            $result[$translation] = $strings;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getHeaderScript()
    {
        $user = Auth::user();
        $analyticsId = $this->getSettingManager()->getSettingValue('general', 'analytics_id', '');
        $isDebug = (bool) (int) $this->getSettingManager()->getSettingValue('general', 'js_debug', 0);

        $application = [
            'locale' => App::getLocale(),
            'translations' => $this->getTranslations(),
            'isDebug' => $isDebug,
        ];

        $script = '<!-- Header Script -->';
        $script .= '<script>';
        $script .= 'window.csrf_token=\'' . csrf_token() . '\';';

        if ($user) {
            $application['user'] = $user->toArray();
        }

        $script .= 'window.application=' . json_encode($application) . ';';

        $script .= 'function waitFor(t,i){var n=function(t,i){return void 0!==t[i[0]]&&(!(i.length>1)||n(t[i[0]],i.slice(1)))},o=!1;if("string"==typeof t&&(o=!n(window,t.split("."))),"object"==typeof t)for(var e=0;e<t.length;e++)n(window,t[e].split("."))||(o=!0);if(o)return setTimeout(function(){waitFor(t,i)},10);"function"==typeof i&&i()}';
        $script .= '</script>';

        if ($analyticsId) {
            $script .= '<!-- Global site tag (gtag.js) - Google Analytics -->';
            $script .= '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $analyticsId . '"></script>';
            $script .= '<script>';
            $script .= 'window.dataLayer = window.dataLayer || [];';
            $script .= 'function gtag(){dataLayer.push(arguments);}';
            $script .= 'gtag(\'js\', new Date());';
            $script .= 'gtag(\'config\', \'' . $analyticsId . '\');';
            $script .= '</script>';
        }

        return $script;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function wrapLinks($text)
    {
        $rexProtocol = '(https?://)?';
        $rexDomain   = '((?:[-a-zA-Z0-9]{1,63}\.)+[-a-zA-Z0-9]{2,63}|(?:[0-9]{1,3}\.){3}[0-9]{1,3})';
        $rexPort     = '(:[0-9]{1,5})?';
        $rexPath     = '(/[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]*?)?';
        $rexQuery    = '(\?[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
        $rexFragment = '(#[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
        $validTlds = array_fill_keys(explode(' ', '.aero .asia .biz .cat .com .coop .edu .gov .info .int .jobs .mil .mobi .museum .name .net .org .pro .tel .travel .ac .ad .ae .af .ag .ai .al .am .an .ao .aq .ar .as .at .au .aw .ax .az .ba .bb .bd .be .bf .bg .bh .bi .bj .bm .bn .bo .br .bs .bt .bv .bw .by .bz .ca .cc .cd .cf .cg .ch .ci .ck .cl .cm .cn .co .cr .cu .cv .cx .cy .cz .de .dj .dk .dm .do .dz .ec .ee .eg .er .es .et .eu .fi .fj .fk .fm .fo .fr .ga .gb .gd .ge .gf .gg .gh .gi .gl .gm .gn .gp .gq .gr .gs .gt .gu .gw .gy .hk .hm .hn .hr .ht .hu .id .ie .il .im .in .io .iq .ir .is .it .je .jm .jo .jp .ke .kg .kh .ki .km .kn .kp .kr .kw .ky .kz .la .lb .lc .li .lk .lr .ls .lt .lu .lv .ly .ma .mc .md .me .mg .mh .mk .ml .mm .mn .mo .mp .mq .mr .ms .mt .mu .mv .mw .mx .my .mz .na .nc .ne .nf .ng .ni .nl .no .np .nr .nu .nz .om .pa .pe .pf .pg .ph .pk .pl .pm .pn .pr .ps .pt .pw .py .qa .re .ro .rs .ru .rw .sa .sb .sc .sd .se .sg .sh .si .sj .sk .sl .sm .sn .so .sr .st .su .sv .sy .sz .tc .td .tf .tg .th .tj .tk .tl .tm .tn .to .tp .tr .tt .tv .tw .tz .ua .ug .uk .us .uy .uz .va .vc .ve .vg .vi .vn .vu .wf .ws .ye .yt .yu .za .zm .zw .xn--0zwm56d .xn--11b5bs3a9aj6g .xn--80akhbyknj4f .xn--9t4b11yi5a .xn--deba0ad .xn--g6w251d .xn--hgbk6aj7f53bba .xn--hlcj6aya9esc7a .xn--jxalpdlp .xn--kgbechtv .xn--zckzah .arpa'), true);

        $position = 0;
        $strippedMessage = $text;

        while (preg_match("{\\b$rexProtocol$rexDomain$rexPort$rexPath$rexQuery$rexFragment(?=[?.!,;:\"]?(\s|$))}", $text, $match, PREG_OFFSET_CAPTURE, $position)) {
            list($url, $urlPosition) = $match[0];
            $domain = $match[2][0];
            $tld = strtolower(strrchr($domain, '.'));

            if (preg_match('{\.[0-9]{1,3}}', $tld) || isset($validTlds[$tld])) {
                $completeUrl = $match[1][0] ? $url : "http://$url";
                $strippedMessage = str_replace($url, sprintf('<a target="_blank" href="%1$s">%2$s</a>', $completeUrl, $url), $strippedMessage);
            }

            $position = $urlPosition + strlen($url);
        }

        return $strippedMessage;
    }

    /**
     * @return string
     */
    public function getBuildQuery()
    {
        $build = Cache::get('build_version');

        return $build
            ? '?build=' . $build
            : '';
    }
}