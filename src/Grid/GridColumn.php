<?php namespace Yorki\Workshop\Grid;

class GridColumn
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
     * @var GridRenderer $renderer
     */
    protected $renderer;

    /**
     * @var bool
     */
    protected $visible = true;

    /**
     * @param string $caption
     *
     * @return $this
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param GridRenderer $renderer
     *
     * @return $this
     */
    public function setRenderer(GridRenderer $renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * @return GridRenderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }
}