<?php

namespace App\Http\Controllers\user;

use Carbon\Carbon;
use App\Models\User;
use DomainException;
use App\Models\Application;
use Illuminate\Http\Request;
use App\UseCases\CommentService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\UseCases\ApplicationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use League\Flysystem\Plugin\AbstractPlugin;
use App\Http\Requests\application\ApplicationEditRequest;
use App\Http\Requests\application\ApplicationCreateRequest;


class ApplicationController extends Controller
{
    private $service;
    private $commentService;

    public function __construct(ApplicationService $service, CommentService $commentService)
    {
        $this->middleware('role:user')->only('store', 'update', 'destroy');
        $this->service = $service;
        $this->commentService = $commentService;
    }
    public function dash(Request $request)
    {

        return $this->service->dash($request);
    }
    public function index(Request $request)
    {
        return $this->service->list($request);
    }
    public function store(ApplicationCreateRequest $request)
    {

        return $this->service->create($request);
    }
    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(Application::class);
        $app = $query->findOrFail($id);
        if (!empty($request->append)) {
            $app->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $app->load(explode(',', $request->include));
        }
        return $app;
    }
    public function update(ApplicationEditRequest $request, Application $application)
    {
        if (!$application->userActions()) {
            abort(403);
        }
        return $this->service->edit($request, $application);
    }
    public function destroy(Application $application)
    {
        if (!$application->userActions()) {
            abort(403);
        }
        return $this->service->remove($application);
    }


    public function reject(Application $application)
    {
        if ($application->adminActions() && Gate::allows('admin')) {
            $application->status = Application::STATUS_REJECT;
            $application->save();
        } elseif ($application->managerActions() && Gate::allows('manager')) {
            $application->status = Application::STATUS_REJECT;
            $application->save();
        }
        return $application;
    }

    public function success(Application $application)
    {
        if ($application->adminActions() && Gate::allows('admin')) {
            $application->status = Application::STATUS_SUCCESS;
            $application->save();
        } elseif ($application->managerActions() && Gate::allows('manager')) {
            $application->status = Application::STATUS_MANAGER_TO_ADMIN;
            $application->save();
        }

        return $application;
    }

    public function comment(Request $request, Application $application)
    {

        $request->validate([
            'column_*' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {


            for ($n = 1; $n < 15; $n++) {
                if ($request->filled('column_' . $n)) {

                    $this->commentService->create([
                        'application_id' => $application->id,
                        'description' => $request->get('column_' . $n),
                        'column_id' => $n,
                        'author' => Auth::user()->id
                    ]);
                }
            }
            if (Gate::allows('manager') && $request->has('status')) {
                $application->update(['status' => Application::STATUS_MANAGER_TO_USER]);
            } elseif (Gate::allows('admin') && $request->has('status')) {
                $application->update(['status' => Application::STATUS_ADMIN_TO_MANAGER]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }

        return $application;
    }
}
