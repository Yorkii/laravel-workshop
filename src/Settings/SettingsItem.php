<?php namespace Yorki\Workshop\Settings;

class SettingsItem
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $caption;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $possibleValues = [];

    /**
     * @var string
     */
    protected $helpBlock;

    /**
     * @param string $name
     * @param string $caption
     * @param string $type
     * @param string $value
     * @param array $possibleValues
     */
    public function __construct($name, $caption, $type, $value = null, $possibleValues = [], $helpBlock = null)
    {
        $this->name = $name;
        $this->caption = $caption;
        $this->value = $value;
        $this->type = $type;
        $this->possibleValues = $possibleValues;
        $this->helpBlock = $helpBlock;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $inputName = 'input-' . str_replace(['[', ']'], '', $this->name);
        $html = '<div class="form-group">';
        $html .= '<label class="col-sm-3 control-label" for="' . $inputName . '">' . ($this->type === 'boolean' || $this->type === 'bool' ? '' : $this->caption) . '</label>';
        $html .= '<div class="col-sm-9">';

        if (in_array($this->type, ['string', 'integer', 'int', 'float'])) {
            $value = htmlspecialchars($this->value);

            if ('integer' === $this->type) {
                $value = (int) $value;
            } elseif ('float' === $this->type) {
                $value = number_format((float) $value, 2, '.', '');
            }

            $html .= '<input id="' . $inputName . '" type="text" class="form-control" name="' . $this->name . '" value="' . $value . '"/>';
        }

        if ('text' === $this->type) {
            $html .= '<textarea id="' . $inputName . '" class="form-control" rows="12" name="' . $this->name . '">' . htmlspecialchars($this->value) . '</textarea>';
        }

        if ('select' === $this->type) {
            $html .= '<select id="' . $inputName . '" class="form-control" name="' . $this->name . '">';

            foreach ($this->possibleValues as $key => $possibleValue) {
                $html .= '<option value="' . $key . '"' . ($this->value === $key ? ' selected' : '') . '>' . $possibleValue . '</option>';
            }

            $html .= '</select>';
        }

        if ('boolean' === $this->type || 'bool' === $this->type) {
            $html .= '<input type="hidden" name="' . $this->name . '" value="0" />';
            $html .= '<input id="checkbox-' . $inputName . '" type="checkbox" class="magic-checkbox" name="' . $this->name . '" value="1" ' . ((bool) $this->value ? 'checked' : '') . '/>';
            $html .= '<label for="checkbox-' . $inputName . '">' . $this->caption . '</label>';
        }

        if ('range' === $this->type) {
            $start = (int) $this->value[0];
            $end = (int) $this->value[1];
            $min = (int) $this->possibleValues[0];
            $max = (int) $this->possibleValues[1];
            $html .= '<input type="hidden" name="' . mb_substr($this->name, 0, mb_strlen($this->name) - 1) . '_min]" value="' . $start . '" id="' . $inputName . '-min" />';
            $html .= '<input type="hidden" name="' . mb_substr($this->name, 0, mb_strlen($this->name) - 1) . '_max]" value="' . $end . '" id="' . $inputName . '-max" />';
            $html .= '<div id="' . $inputName . '" style="margin-top: 15px;"></div>';
            $html .= '<br/><div><strong>Value : </strong><span id="' . $inputName . '-step-val"></span></div>';
            $html .= '<script>waitFor([\'noUiSlider\'], function () {';
            $html .= 'var slider = document.getElementById(\'' . $inputName . '\');';
            $html .= 'var sliderValue = document.getElementById(\'' . $inputName . '-step-val\');';
            $html .= 'var sliderValueMin = document.getElementById(\'' . $inputName . '-min\');';
            $html .= 'var sliderValueMax = document.getElementById(\'' . $inputName . '-max\');';
            $html .= 'noUiSlider.create(slider, {';
                $html .=  'start:[' . $start . ',' . $end . '],';
                $html .=  'behaviour: \'drag\',';
                $html .=  'connect: true,';
                $html .=  'step: 1,';
                $html .=  'range:{\'min\':' . $min . ',\'max\':' . $max . '}';
            $html .=  '});';
            $html .=  'slider.noUiSlider.on(\'update\', function(values, handle) {';
                $html .= 'sliderValue.innerHTML = parseFloat(values[0]).toFixed(0) + \' to \' + parseFloat(values[1]).toFixed(0);';
                $html .= 'sliderValueMin.value = parseFloat(values[0]).toFixed(0);';
                $html .= 'sliderValueMax.value = parseFloat(values[1]).toFixed(0);';
            $html .=  '});';
            $html .=  '});</script>';
        }

        if ($this->helpBlock) {
            $html .= '<small class="help-block">' . $this->helpBlock . '</small>';
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}