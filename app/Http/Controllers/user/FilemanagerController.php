<?php

namespace App\Http\Controllers\user;

use App\Models\Files;
use Illuminate\Http\Request;
use App\UseCases\FileService;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class FilemanagerController extends Controller
{
    private $service;

    public function __construct(FileService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->get('filter');
        $filter = [];
        if (!empty($filters)) {
            foreach ($filters as $k => $item) {
                $filter[] = AllowedFilter::exact($k);
            }
        }

        $query = QueryBuilder::for(Files::class);
        if (!empty($request->title)) {
            $query->where('title', 'LIKE', '%' . $request->title . '%');
        }
        $query->allowedFilters($filter);
        $query->allowedAppends($request->include);
        $query->allowedSorts($request->sort);
        $files=$query->paginate($request->per_page);
        return view('user.images',compact('files'));
    }

    /**
     * Filemanager Uploads
     *
     * @bodyParam files file required File
     */
    public function uploads(Request $request)
    {    
        $request->validate([
            'from'=>'nullable|date',
            'to'=>'nullable|date',
            'definition'=>'nullable|string'
        ]);
        return $this->service->uploads($request);
    }
    public function delete(Files $file)
    {
        $this->service->delete($file);
        $file->delete();
        return 'deleted';
    }
}
