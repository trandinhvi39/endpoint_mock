<?php

namespace App\Http\Controllers\Api\Auth;

use App\Contracts\Repositories\UserRepository;
use App\Exceptions\Api\ActionException;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Contracts\Services\PassportInterface;
use Illuminate\Database\QueryException;
use App\Exceptions\Api\NotFoundErrorException;

class RegisterController extends ApiController
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    public function create(RegisterRequest $request, PassportInterface $service)
    {
        $data = $request->only($this->repository->getFillable());

        try {
            $this->repository->register($data);
            $response = $service->passwordGrantToken($data);

            if (isset($response->error)) {
                throw new NotFoundErrorException($response->message, 404);
            } elseif (isset($response->access_token)) {
                $this->compacts['passport'] = $response;
            }
        } catch (QueryException $e) {
            throw new ActionException($data, 'store');
        } catch (\Exception $e) {
            throw new NotFoundErrorException($e->getMessage(), $e->getCode());
        }

        return $this->jsonRender();
    }
}
