<?php

namespace Txsoura\Core\Services;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Arr;
use Txsoura\Core\Helpers;
use Txsoura\Core\Http\Requests\Traits\ValidatesCoreRequests;

abstract class CoreService
{
    use Helpers;

    protected $oldModel = [];
    protected $oldModelKeys = ['*'];

    /**
     * Duplicate model to get dirty on update
     *
     * @param Model $model
     */
    public function setOldData($model)
    {
        if (Arr::get($this->oldDataKeys, '0') === '*') {
            $this->oldData = array_merge($this->oldData, $model->toArray());
            return;
        }

        foreach ($this->oldDataKeys as $k) {
            $this->oldData[$k] = $model->{$k};
        }
    }

    /**
     * Model class for crud.
     *
     * @return string
     */
    abstract protected function getModelClass(): string;
}
