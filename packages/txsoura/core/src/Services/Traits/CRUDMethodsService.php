<?php

namespace Txsoura\Core\Services\Traits;

use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Validator;
use Txsoura\Core\Http\Requests\QueryParamsRequest;

trait CrudMethodsService
{
    /**
     * @var QueryParamsRequest
     */
    // protected  $queryParamsRequest =  new QueryParamsRequest;


    /**
     * @var string
     */
    abstract protected function rules();

    public function index(QueryParamsRequest $request)
    {
        return $this->repository->setRequest($request)->all();
    }

    public function store()
    {
        // if (!$this->validationCreate()) {
        //     return false;
        // }

        return $this->repository->setRequest($this->request)->create();
    }

    public function show($id)
    {
        // if (!$this->validationCreate()) {
        //     return false;
        // }

        return $this->repository->setRequest($this->request)->findOrFail($id->id);
    }

    public function update($id)
    {
        // if (!$this->validationUpdate($id)) {
        //     return false;
        // }

        return $this->repository->setRequest($this->request)->update($id);
    }

    public function destroy($id)
    {
        // if (!$this->checkAuthorization($id, self::ACTION_DELETE)) {
        //     return false;
        // }

        $this->repository->delete($id);

        return response()->json(
            ['message' => trans('message.deleted')],
            200
        );
    }
}
