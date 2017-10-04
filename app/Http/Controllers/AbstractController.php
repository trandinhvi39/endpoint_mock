<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Exceptions\Api\NotOwnerException;

abstract class AbstractController extends Controller
{
    protected $repository;

    protected $repositoryName;

    protected $user;

    protected $compacts;

    public function __construct($repository = null)
    {
        $this->middleware(function ($request, $next) use ($repository) {
            $this->user = Auth::guard($this->getGuard())->user();

            if ($repository) {
                $this->repositorySetup($repository);
            }

            return $next($request);
        });
    }

    public function repositorySetup($repository = null)
    {
        $this->repository = $repository->setGuard($this->getGuard());
        $this->repositoryName = strtolower(class_basename($this->repository->model()));
    }

    protected function getGuard()
    {
        return property_exists($this, 'guard') ? $this->guard : null;
    }

    public function before($method, $object = null, $abort = true)
    {
        switch ($method) {
            case 'index':
            case 'show':
                $action = 'read';
                break;
            case 'create':
            case 'store':
                $action = 'create';
                break;
            case 'edit':
            case 'update':
                $action = 'update';
                break;
            case 'delete':
                $action = 'delete';
                break;
            default:
                $action = $method;
                break;
        }

        if (!$object) {
            $object = $this->repository->model();
        }

        if (!$this->user || $this->user->cannot($action, $object)) {
            if (!$abort) {
                return false;
            }

            throw new NotOwnerException();
        }

        return true;
    }
}
