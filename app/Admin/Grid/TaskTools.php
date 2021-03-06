<?php

namespace App\Admin\Grid;

use Encore\Admin\Grid;
use Encore\Admin\Grid\Tools\AbstractTool;
use Encore\Admin\Grid\Tools\BatchActions;
use Encore\Admin\Grid\Tools\FilterButton;
use Encore\Admin\Grid\Tools\RefreshButton;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

use App\Admin\Grid\Tools\BatchRegistTask;

class TaskTools implements Renderable
{
    /**
     * Parent grid.
     *
     * @var Grid
     */
    protected $grid;

    /**
     * Collection of tools.
     *
     * @var Collection
     */
    protected $tools;

    /**
     * Create a new Tools instance.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;

        $this->tools = new Collection();

        $this->appendDefaultTools();
    }

    /**
     * Append default tools.
     */
    protected function appendDefaultTools()
    {
        $this->batchActions = new BatchActions();
        $this->batchActions->add(new BatchRegistTask(trans('task.register')));
        $this->append($this->batchActions)
            ->append(new RefreshButton())
            ->append(new FilterButton());
    }

    /**
     * Append tools.
     *
     * @param AbstractTool|string $tool
     *
     * @return $this
     */
    public function append($tool)
    {
        $this->tools->push($tool);

        return $this;
    }

    /**
     * Prepend a tool.
     *
     * @param AbstractTool|string $tool
     *
     * @return $this
     */
    public function prepend($tool)
    {
        $this->tools->prepend($tool);

        return $this;
    }

    /**
     * Disable filter button.
     *
     * @return void
     */
    public function disableFilterButton()
    {
        $this->tools = $this->tools->reject(function ($tool) {
            return $tool instanceof FilterButton;
        });
    }

    /**
     * Disable refresh button.
     *
     * @return void
     */
    public function disableRefreshButton()
    {
        $this->tools = $this->tools->reject(function ($tool) {
            return $tool instanceof RefreshButton;
        });
    }

    /**
     * Disable batch actions.
     *
     * @return void
     */
    public function disableBatchActions()
    {
        $this->tools = $this->tools->reject(function ($tool) {
            return $tool instanceof BatchActions;
        });
    }

    /**
     * @param \Closure $closure
     */
    public function batch(\Closure $closure)
    {
        call_user_func($closure, $this->tools->first(function ($tool) {
            return $tool instanceof BatchActions;
        }));
    }

    /**
     * Render header tools bar.
     *
     * @return string
     */
    public function render()
    {
        return $this->tools->map(function ($tool) {
            if ($tool instanceof AbstractTool) {
                return $tool->setGrid($this->grid)->render();
            }

            return (string) $tool;
        })->implode(' ');
    }
}
