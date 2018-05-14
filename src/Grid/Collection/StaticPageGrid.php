<?php namespace Yorki\Workshop\Grid\Collection;

use App\Library\Grid\Grid;
use App\Library\Grid\GridRenderer;
use App\Managers\Contracts\ChatManagerContract;
use App\Managers\Contracts\LinkManagerContract;
use Carbon\Carbon;

class StaticPageGrid
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
            return '<a href="' . route('admin.static.single', ['id' => $row['id']]) . '">' . $row['id'] . '</a>';
        });

        $linkManager = app(LinkManagerContract::class);
        $actionsRenderer = new GridRenderer('actions');
        $actionsRenderer->callback(function ($row) use ($linkManager) {
            return '<div class="btn-group">'
                . '<button type="button" class="btn btn-xs btn-default">Action</button>'
                . '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
                    . '<span class="caret"></span>'
                    . '<span class="sr-only">Toggle Dropdown</span>'
                . '</button>'
                . '<ul class="dropdown-menu dropdown-menu-right" role="menu">'
                    . '<li>' . $linkManager->getLinkAsButton(route('admin.static.delete', ['id' => $row['id']]), 'Delete', 'dropdown-item') . '</li>'
                . '</ul>'
            . '</div> '
            . '<a href="' . route('admin.static.single', ['id' => $row['id']]) . '" class="btn btn-xs btn-info">See details</a>';
        });

        $this->grid = new Grid();
        $this->grid->setColumns([
            ['name' => 'id', 'caption' => 'ID', 'renderer' => $idRenderer],
            ['name' => 'slug', 'caption' => 'Slug'],
            ['name' => 'title', 'caption' => 'Title'],
            ['name' => 'actions', 'caption' => 'Actions', 'renderer' => $actionsRenderer],
        ]);
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