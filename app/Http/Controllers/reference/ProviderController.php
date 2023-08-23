<?php

namespace App\Http\Controllers\reference;


use App\Models\Provider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin|manager')->only('store', 'update', 'destroy');
    }

    public function index(Request $request)
    {
        $filters = $request->get('filter');
        $filter = [];
        if (!empty($filters)) {
            foreach ($filters as $k => $provider) {
                $filter[] = AllowedFilter::exact($k);
            }
        }
        $query = QueryBuilder::for(Provider::class);
        if (!empty($request->get('search'))) {
            $query->where('name', 'like', '%' . $request->get('search') . '%');
        }

        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);

        $query->allowedFilters($filter);
        $query->orderBy('updated_at', 'desc');
        return $query->get();
    }


    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(Provider::class);
        $provider=$query->findOrFail($id);
        if (!empty($request->append)) {
            $provider->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $provider->load(explode(',', $request->include));
        }
        return $provider;
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'phone'=>'required|string',
            'email'=>'nullable|email'
         ]);
          $provider =Provider::create($request->only('name','phone','email'));

          return $provider;
    }


    public function update(Request $request, Provider $provider)
    {
        $request->validate([
            'name'=>'string',
            'phone'=>'string',
            'email'=>'nullable|email'

         ]);

         $provider->update($request->only('name','type','email'));
         return $provider;
    }


    public function destroy(Provider $provider)
    {
        $provider->delete();
        return 'deleted';
    }
}
