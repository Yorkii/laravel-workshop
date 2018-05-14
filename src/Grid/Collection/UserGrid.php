<?php namespace Yorki\Workshop\Grid\Collection;

use Yorki\Workshop\Grid\Grid;
use Yorki\Workshop\Grid\GridRenderer;
use \Carbon\Carbon;

class UserGrid
{
    /**
     * @var Grid
     */
    protected $grid;

    public function __construct()
    {
        $this->defaults();
    }

    public function defaults()
    {
        $idRenderer = new GridRenderer('id');
        $idRenderer->callback(function ($row) {
            return '<a href="' . route('admin.users.single', ['id' => $row['id']]) . '">' . $row['id'] . '</a>';
        });

        $nameRenderer = new GridRenderer('name');
        $nameRenderer->callback(function ($row) {
            return '<a class="text-main text-semibold" href="' . route('admin.users.single', ['id' => $row['id']]) . '">' . $row['name'] . '</a>'
                . '<br/>'
                . '<i class="pli-st"></i> <a class="text-muted" style="font-size: 85%;" href="' . route('admin.users.single', ['id' => $row['id']]) . '">' . $row['email'] . '</a>';
        });

        $actionsRenderer = new GridRenderer('actions');
        $actionsRenderer->callback(function ($row) {
            return '<a href="' . route('admin.users.single', ['id' => $row['id']]) . '" class="btn btn-xs btn-info">See details</a>';
        });

        $this->grid = new Grid();
        $this->grid->setColumns([
            ['name' => 'id', 'caption' => 'ID', 'renderer' => $idRenderer],
            ['name' => 'name', 'caption' => 'User', 'renderer' => $nameRenderer],
            ['name' => 'created_at', 'caption' => 'Register date'],
            ['name' => 'actions', 'caption' => 'Actions', 'renderer' => $actionsRenderer],
        ]);
        $this->grid->setTableClass('table table-hover table-vcenter');
    }

    /**
     * @param array $columns
     *
     * @return $this
     */
    public function setColumns(array $columns)
    {
        $this->grid->makeVisible($columns);

        return $this;
    }

    /**
     * @param array $items
     *
     * @return $this
     */
    public function setItems(array $items)
    {
        $this->grid->setItems($items);

        return $this;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return $this->grid->toHtml();
    }
}