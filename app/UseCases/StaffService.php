<?php

namespace App\UseCases;
use App\Models\AppointmentOrder;
use App\Models\Compliance;
use App\Models\Diploma;
use App\Models\Files;
use App\Models\InternetProvider;
use App\Models\Network;
use App\Models\ProfessionalDevelopment;
use App\Models\Provider;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;

class StaffService
{
    private $fileService;

    private $appointmentOrderService;

    private $diplomaService;

    private $complianceService;

    private $professionalDevelopmentService;

    public function __construct( FileService $fileService, AppointmentOrderService $appointmentOrderService, DiplomaService $diplomaService, ComplianceService $complianceService, ProfessionalDevelopmentService $professionalDevelopmentService)
    {
        $this->fileService=$fileService;
        $this->appointmentOrderService =$appointmentOrderService;
        $this->diplomaService = $diplomaService;
        $this->complianceService =$complianceService;
        $this->professionalDevelopmentService =$professionalDevelopmentService;

    }

    public function create(Request $request)
    {
        $request->validate([
           'subject_id'=>'required|integer|exists:subjects,id',
           'appointment_order_id'=>'required|integer|exists:appointment_orders,id',
           'diploma_id'=>'nullable|integer|exists:diplomas,id',
           'professional_development'=>'nullable|array|exists:professional_developments,id',
           'compliance_id'=>'nullable|integer|exists:compliances,id',
           'name' =>'required|string',
           'position'=>'nullable|string',
           'definition'=>'nullable|string',
           'phone'=>'required|string'

        ]);

        $staff = Staff::make($request->only(
        'subject_id',
        'appointment_order_id',
        'diploma_id',
        'professional_development',
        'compliance_id',
        'name',
        'position',
        'phone',
        'definition'));
        $staff->save();
        return $staff;
    }

    public function edit(Request $request, Staff $staff)
    {
        $request->validate([
           'subject_id'=>'integer|exists:subjects,id',
           'appointment_order_id'=>'integer|exists:appointment_orders,id',
           'diploma_id'=>'nullable|integer|exists:diplomas,id',
           'professional_development'=>'nullable|array|exists:professional_developments,id',
           'compliance_id'=>'nullable|integer|exists:compliances,id',
           'name' =>'string',
           'position'=>'nullable|string',
           'definition'=>'nullable|string',
           'phone'=>'string'
        ]);
        $staff->update($request->only('subject_id',
        'appointment_order_id',
        'diploma_id',
        'professional_development',
        'compliance_id',
        'name',
        'position',
        'phone',
        'definition'));
        return $staff;

    }
    public function remove(Staff $staff)
    {
        if($file =Files::find($staff->file_id)){
          $this->fileService->delete($file);
        }
        if($item =AppointmentOrder::find($staff->appointment_order_id)){
            $this->appointmentOrderService->remove($item);
        }

        if($diploma =Diploma::find($staff->diploma_id)){
            $this->diplomaService->remove($diploma);
        }
        if($item = Compliance::find($staff->compliance_id)){
            $this->complianceService->remove($item);
        }

        foreach ($staff->professional_development as $id){
            if($item = ProfessionalDevelopment::find($id)){
                $this->professionalDevelopmentService->remove($item);
            }

        }
        $staff->delete();
        return 'deleted';
    }


    public function search(Request $request){
        $query = QueryBuilder::for(Staff::class);
        $checker =0;

        if(!empty($request->get('staff_name'))) {
            $query->where('name', $request->get('staff_name'));
            $checker=1;
        }

        if(!empty($request->get('degree'))){
            $query->whereHas('diploma', function (Builder $q) use ($request){
                $q->where('degree',$request->get('degree'));
            });
            $checker=1;
        }

       if(!empty($request->get('compliance_from')) && !empty($request->get('compliance_to')) ){
           $query->whereHas('compliance', function (Builder $q) use ($request){
               $q->whereBetween('to',[Carbon::createFromFormat('Y-m-d',$request->get('compliance_from')),Carbon::createFromFormat('Y-m-d',$request->get('compliance_to'))]);
           });
           $checker=1;

       }

        $ids = $query->get()->pluck('id')->toArray();

        if($checker) return $ids;
        return null;
    }


}
