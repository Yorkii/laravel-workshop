<?php namespace Yorki\Workshop\Grid\Collection;

use Yorki\Workshop\Grid\Grid;
use Yorki\Workshop\Grid\GridRenderer;
use \App\Managers\Contracts\LinkManagerContract;
use \Carbon\Carbon;

class TodoGrid
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
        $linkManager = app(LinkManagerContract::class);
        $idRenderer = new GridRenderer('id');
        $idRenderer->callback(function ($row) {
            return '<a href="' . route('admin.todo.single', ['id' => $row['id']]) . '">' . $row['id'] . '</a>';
        });

        $statusRenderer = new GridRenderer('status');
        $statusRenderer->callback(function ($row) {
            if ($row['status'] === \App\Models\Todo::STATUS_DONE) {
                return '<label class="label label-success">Done</label>';
            } elseif ($row['status'] === \App\Models\Todo::STATUS_SKIPPED) {
                return '<label class="label label-warning">Skipped</label>';
            }
        });

        $priorityRenderer = new GridRenderer('priority');
        $priorityRenderer->callback(function ($row) {
            if ($row['priority'] === \App\Models\Todo::PRIORITY_LOW) {
                return '<label class="label label-info">Low</label>';
            } elseif ($row['priority'] === \App\Models\Todo::PRIORITY_MEDIUM) {
                return '<label class="label label-success">Medium</label>';
            } elseif ($row['priority'] === \App\Models\Todo::PRIORITY_HIGH) {
                return '<label class="label label-warning">High</label>';
            } elseif ($row['priority'] === \App\Models\Todo::PRIORITY_ASAP) {
                return '<label class="label label-danger">ASAP</label>';
            }
        });

        $categoryRenderer = new GridRenderer('category');
        $categoryRenderer->callback(function ($row) {
            if ($row['category'] === \App\Models\Todo::CATEGORY_CODE) {
                return 'Coding';
            } elseif ($row['category'] === \App\Models\Todo::CATEGORY_DESIGN) {
                return 'Design';
            } elseif ($row['category'] === \App\Models\Todo::CATEGORY_OTHER) {
                return 'Other';
            }
        });

        $createdByRenderer = new GridRenderer('created_by');
        $createdByRenderer->callback(function ($row) use ($linkManager) {
            return $linkManager->getUserLink($row['user_id']);
        });

        $doneByRenderer = new GridRenderer('done_by');
        $doneByRenderer->callback(function ($row) use ($linkManager) {
            return $linkManager->getUserLink($row['done_by']);
        });

        $titleRenderer = new GridRenderer('title');
        $titleRenderer->callback(function ($row) {
            return '<a href="' . route('admin.todo.single', ['id' => $row['id']]) . '">' . $row['title'] . '</a>';
        });

        $commentsRenderer = new GridRenderer('comments');
        $commentsRenderer->callback(function ($row) {
            if ($row['comments_count'] > 0) {
                return '<label class="label label-danger">' . (int)$row['comments_count'] . '</label>';
            } else {
                return '<label class="label label-default">0</label>';
            }
        });

        $actionsRenderer = new GridRenderer('actions');
        $actionsRenderer->fixedWidth(200);
        $actionsRenderer->callback(function ($row) use ($linkManager) {
            $html = '<div class="btn-group">'
                . '<button type="button" class="btn btn-xs btn-default">Action</button>'
                . '<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
                    . '<span class="caret"></span>'
                    . '<span class="sr-only">Toggle Dropdown</span>'
                . '</button>'
                . '<ul class="dropdown-menu dropdown-menu-right" role="menu">';

            if ($row['status'] === \App\Models\Todo::STATUS_NEW) {
                $html .= '<li>' . $linkManager->getLinkAsButton(route('admin.todo.done', ['id' => $row['id']]), 'Mark as done', 'dropdown-item') . '</li>'
                    . '<li>' . $linkManager->getLinkAsButton(route('admin.todo.skip', ['id' => $row['id']]), 'Mark as skipped', 'dropdown-item') . '</li>'
                    . '<li class="divider"></li>';

                if ($row['priority'] !== \App\Models\Todo::PRIORITY_LOW) {
                    $html .= '<li>' . $linkManager->getLinkAsButton(route('admin.todo.priority', ['id' => $row['id']]), 'Set low priority', 'dropdown-item', ['priority' => \App\Models\Todo::PRIORITY_LOW]) . '</li>';
                }

                if ($row['priority'] !== \App\Models\Todo::PRIORITY_MEDIUM) {
                    $html .= '<li>' . $linkManager->getLinkAsButton(route('admin.todo.priority', ['id' => $row['id']]), 'Set medium priority', 'dropdown-item', ['priority' => \App\Models\Todo::PRIORITY_MEDIUM]) . '</li>';
                }

                if ($row['priority'] !== \App\Models\Todo::PRIORITY_HIGH) {
                    $html .= '<li>' . $linkManager->getLinkAsButton(route('admin.todo.priority', ['id' => $row['id']]), 'Set high priority', 'dropdown-item', ['priority' => \App\Models\Todo::PRIORITY_HIGH]) . '</li>';
                }

                if ($row['priority'] !== \App\Models\Todo::PRIORITY_ASAP) {
                    $html .= '<li>' . $linkManager->getLinkAsButton(route('admin.todo.priority', ['id' => $row['id']]), 'Set ASAP priority', 'dropdown-item', ['priority' => \App\Models\Todo::PRIORITY_ASAP]) . '</li>';
                }
            } else {
                $html .= '<li>' . $linkManager->getLinkAsButton(route('admin.todo.undone', ['id' => $row['id']]), 'Mark as todo', 'dropdown-item') . '</li>';
            }

            $html .= '</ul>'
            . '</div> '
            . '<a href="' . route('admin.todo.single', ['id' => $row['id']]) . '" class="btn btn-xs btn-info">See details</a>';

            return $html;
        });

        $this->grid = new Grid();
        $this->grid->setColumns([
            ['name' => 'id', 'caption' => 'ID', 'renderer' => $idRenderer],
            ['name' => 'status', 'caption' => 'Status', 'renderer' => $statusRenderer],
            ['name' => 'priority', 'caption' => 'Priority', 'renderer' => $priorityRenderer],
            ['name' => 'category', 'caption' => 'Category', 'renderer' => $categoryRenderer],
            ['name' => 'created_by', 'caption' => 'Created by', 'renderer' => $createdByRenderer],
            ['name' => 'done_by', 'caption' => 'Done by', 'renderer' => $doneByRenderer],
            ['name' => 'done_at', 'caption' => 'Done at'],
            ['name' => 'title', 'caption' => 'Title', 'renderer' => $titleRenderer],
            ['name' => 'comments', 'caption' => 'Comments', 'renderer' => $commentsRenderer],
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

    /**
     * @return Grid
     */
    public function getGrid()
    {
        return $this->grid;
    }
}