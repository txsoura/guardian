<?php

namespace Txsoura\Core\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Txsoura\Core\Http\Requests\QueryParamsRequest;

trait CRUDMethodsController
{
    public function index(QueryParamsRequest $request)
    {
        $models = $this->service
            ->index($request);

        return $this->resource::collection($models);
    }

    public function store(Request $request)
    {
        $model = $this->service
            ->setRequest($request)
            ->store();

        return response()->withCreated($model, $this->resource);
    }

    public function show(Request $request)
    {
        $id = Arr::last(func_get_args());

        $model = $this->service
            ->setRequest($request)
            ->show($id);

        return response()->item($model, $this->resource);
    }


    public function update(Request $request)
    {
        $id = Arr::last(func_get_args());

        $model = $this->service
            ->setRequest($request)
            ->update($id);

        return response()->withUpdated($model, $this->resource);
    }

    public function destroy(Request $request)
    {
        $id = Arr::last(func_get_args());

        return $this->service->destroy($id);
    }
}
