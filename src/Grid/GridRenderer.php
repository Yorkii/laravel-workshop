<?php namespace Yorki\Workshop\Grid;

class GridRenderer
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $fixedWidth;

    /**
     * @var \Closure
     */
    protected $callbackFunc;

    /**
     * @param string $forName
     */
    public function __construct($forName)
    {
        $this->name = $forName;
    }

    /**
     * @param \Closure $closure
     */
    public function callback(\Closure $closure)
    {
        $this->callbackFunc = $closure;
    }

    /**
     * @param int $width
     *
     * @return $this
     */
    public function fixedWidth($width)
    {
        $this->fixedWidth = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getFixedWidth()
    {
        return $this->fixedWidth;
    }

    /**
     * @param array $row
     *
     * @return mixed
     */
    public function render(array $row)
    {
        $result = $this->callbackFunc->call($this, $row);

        if ($result instanceof \Generator) {
            $html = '';

            foreach ($result as $value) {
                $html .= $value;
            }

            return $html;
        }

        return $result;
    }
}