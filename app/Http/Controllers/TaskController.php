<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Services\TaskService;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class TaskController extends Controller
{
    /*
     * @var TaskService $service
     */
    protected TaskService $service;

    public function __construct(
        TaskService $service
    ) {
        $this->service = $service;
    }

    public function list()
    {
        return $this->service->all();
    }

    public function create(Request $request)
    {
        try {
            return response()->json(['data' => $this->service->create($request->input())]);
        } catch (InvalidArgumentException $error) {
            return response()->json(['errors' => json_decode($error->getMessage())], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            return response()->json(['data' => $this->service->changeStatus($id, $request->input())]);
        } catch (InvalidArgumentException $error) {
            return response()->json(['errors' => json_decode($error->getMessage())], 422);
        } catch (NotFoundResourceException $error) {
            return response()->json(['errors' => json_decode($error->getMessage())], 404);
        } catch (\ErrorException $error) {
            return response()->json(['errors' => json_decode($error->getMessage())], 400);
        }
    }

    public function delete($id)
    {
        try {
            return response()->json(['data' => $this->service->delete($id)]);
        } catch (InvalidArgumentException $error) {
            return response()->json(['errors' => json_decode($error->getMessage())], 422);
        } catch (NotFoundResourceException $error) {
            return response()->json(['errors' => json_decode($error->getMessage())], 404);
        } catch (\ErrorException $error) {
            return response()->json(['errors' => json_decode($error->getMessage())], 400);
        }
    }
}
