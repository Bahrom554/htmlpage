<?php

namespace App\Http\Resources;

use App\Models\Subject;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
             'id'=>$this->id,
            'name' => $this->name,
            'email' => $this->email,
            'subject'=>$this->subject,
            'phone'=>$this->phone,
            'created_at' => $this->created_at,
            'roles'=>$this->roles
        ];
    }
}
