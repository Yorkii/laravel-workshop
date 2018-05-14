<?php namespace Yorki\Workshop\Settings;

class SettingsForm
{
    /**
     * @var SettingsItem[]
     */
    protected $settings = [];

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $submitCaption;

    /**
     * @var string
     */
    protected $style;

    /**
     * @param string $name
     * @param string $caption
     * @param string $type
     * @param string $value
     * @param array $possibleValues
     * @param string $helpBlock
     *
     * @return SettingsItem
     */
    public function addSetting($name, $caption, $type, $value = null, $possibleValues = [], $helpBlock = null)
    {
        $setting = new SettingsItem($name, $caption, $type, $value, $possibleValues, $helpBlock);

        $this->settings[] = $setting;

        return $setting;
    }

    /**
     * @param string $caption
     *
     * @return SettingsHeader
     */
    public function addHeader($caption)
    {
        $header = new SettingsHeader($caption);

        $this->settings[] = $header;

        return $header;
    }

    /**
     * @param string $action
     *
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @param string $submitCaption
     *
     * @return $this
     */
    public function setSubmitCaption($submitCaption)
    {
        $this->submitCaption = $submitCaption;

        return $this;
    }

    /**
     * @param string $style
     *
     * @return $this
     */
    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $html = '<form method="POST" action="' . $this->action . '" class="form-horizontal">';
        $html .= csrf_field();
        $html .= '<div class="panel-body">';

        foreach ($this->settings as $setting) {
            $html .= $setting->toHtml();
        }

        $html .= '</div>';
        $html .= '<div class="panel-footer panel-default text-right"><button class="btn btn-' . $this->style . '">' . $this->submitCaption . '</button></div>';
        $html .= '</form>';

        return $html;
    }
}