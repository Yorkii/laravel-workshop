<?php namespace Yorki\Workshop\Settings;

class SettingsHeader
{
    /**
     * @var string
     */
    protected $caption;

    /**
     * @param string $caption
     */
    public function __construct($caption)
    {
        $this->caption = $caption;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return '<div class="form-header"><div class="col-sm-3"> </div><div class="col-sm-9"><h4>' . $this->caption . '</h4></div></div>';
    }
}