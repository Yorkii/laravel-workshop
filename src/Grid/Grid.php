<?php namespace Yorki\Workshop\Grid;

class Grid
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var GridColumn[]
     */
    protected $columns = [];

    /**
     * @var string
     */
    protected $tableClass;

    /**
     * @param array $items
     *
     * @return $this
     */
    public function setItems(array $items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @param array $columns
     *
     * @return $this
     */
    public function setColumns(array $columns)
    {
        $this->columns = [];

        foreach ($columns as $column) {
            $col = new GridColumn();
            $col->setCaption($column['caption']);
            $col->setName($column['name']);

            if (isset($column['renderer'])) {
                $col->setRenderer($column['renderer']);
            }

            $this->columns[] = $col;
        }

        return $this;
    }

    /**
     * @param array $columns
     *
     * @return $this
     */
    public function makeVisible(array $columns)
    {
        foreach ($this->columns as $column) {
            $makeVisible = false;

            foreach ($columns as $visible) {
                if ($visible === $column->getName()) {
                    $makeVisible = true;
                }
            }

            $column->setVisible($makeVisible);
        }

        return $this;
    }

    /**
     * @param array $columns
     *
     * @return $this
     */
    public function makeHidden(array $columns)
    {
        foreach ($this->columns as $column) {
            $makeHidden = false;

            foreach ($columns as $visible) {
                if ($visible === $column->getName()) {
                    $makeHidden = true;
                }
            }

            if ($makeHidden) {
                $column->setVisible(false);
            }
        }

        return $this;
    }

    /**
     * @param string $tableClass
     *
     * @return $this
     */
    public function setTableClass($tableClass)
    {
        $this->tableClass = $tableClass;

        return $this;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $tableClass = $this->tableClass
            ? $this->tableClass
            : 'table';

        $html = '<table class="' . $tableClass . '">';
        $html .= '<thead><tr>';

        foreach ($this->columns as $column) {
            if (!$column->getVisible()) {
                continue;
            }

            $html .= '<th>' . $column->getCaption() . '</th>';
        }

        $html .= '</tr></thead><tbody>';
        $rows = 0;

        foreach ($this->items as $item) {
            $html .= '<tr>';

            foreach ($this->columns as $column) {
                if (!$column->getVisible()) {
                    continue;
                }

                $renderer = $column->getRenderer();

                if ($renderer) {
                    $html .= $renderer->getFixedWidth()
                        ? '<td width="' . $renderer->getFixedWidth() . '">'
                        : '<td>';

                    $html .= $renderer->render($item) . '</td>';

                    continue;
                }

                $html .= '<td>' . htmlspecialchars($item[$column->getName()]) . '</td>';
            }

            $html .= '</tr>';
            $rows++;
        }

        if (0 === $rows) {
            $visibleCols = 0;

            foreach ($this->columns as $column) {
                if ($column->getVisible()) {
                    $visibleCols++;
                }
            }

            $html .= '<tr>';
            $html .= '<td class="text-center" colspan="' . $visibleCols . '">There is data to show</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }
}