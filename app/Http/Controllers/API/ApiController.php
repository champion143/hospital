<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Middleware\ApiAuth;
use App\Models\Doctor;
use App\Models\Facility;
use App\Models\Hospital;
use App\Models\Member;
use App\Models\Relationship;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected $userId;

    public function __construct(Request $request)
    {
        $headers = getallheaders();
        if(isset($headers['token']) && $headers['token'] != '')
        {
            $check = Member::where('auth_token',$headers['token'])->first();
            if(!isset($check->id))
            {
                echo json_encode(['status'=>'fail','data'=>array(),'message'=>'token mis matched']);
                die;
            }else{
                $this->userId = $check->id;
            }
        }else{
            echo json_encode(['status'=>'fail','data'=>array(),'message'=>'token blanked']);
            die;
        }
    }

    public function getProfile()
    {
        $member = Member::where('id',$this->userId)->first();
        $member->is_name = $this->getBooleanValue($member->is_name);
        $member->is_email = $this->getBooleanValue($member->is_email);
        $userData = array();
        $userData[0] = $member;
        $data = array();
        $data['status'] = 'success';
        $data['messsage'] = 'Get profile Detail Successfully';
        $data['data'] = $userData;
        echo json_encode($data);
    }

    public function firstNameAndEmailSave(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        Member::where('id',$this->userId)
            ->update(
                array(
                    'name' => $name,
                    'email' => $email,
                    'is_name' => true,
                    'is_email' => true
                )
            );
        $member = Member::where('id',$this->userId)->first();
        $member->is_name = $this->getBooleanValue($member->is_name);
        $member->is_email = $this->getBooleanValue($member->is_email);
        $userData = array();
        $userData[0] = $member;
        $data = array();
        $data['status'] = 'success';
        $data['messsage'] = 'Name And Email Updated Successfully';
        $data['data'] = $userData;
        echo json_encode($data);
    }

    public function getBooleanValue($value)
    {
        if($value == 0)
        {
            return false;
        }
        return true;
    }

    public function getHospital(Request $request)
    {
        $searchTerm = $request->input('search_value');
        $page_no = $request->input('page_no',1);
        $page_count = $request->input('page_count',50);
        $skip = ($page_no-1)*$page_count;
        if($searchTerm == "")
        {
            $allHospital = Hospital::query()->skip($skip)->take($page_count)->get();
        } else {
            $allHospital = Hospital::query()
                ->where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('tag', 'LIKE', "%{$searchTerm}%")
                ->skip($skip)->take($page_count)
                ->get();
        }

        foreach($allHospital as $hospital)
        {
            $allDepartmentsArray = array();
            $allDepartments = Relationship::where('hospital_id',$hospital->id)->with('getDepartment')->get();
            foreach($allDepartments as $allDepartment)
            {
                if($allDepartment->toArray()['get_department'] != null)
                {
                    $department = $allDepartment->toArray()['get_department'];
                    $department['image'] = url('images').'/'.$department['image'];
                    $allDoctors = Doctor::whereIn('id',json_decode($allDepartment['doctors_id']))->get();
                    foreach($allDoctors as $doctor)
                    {
                        $doctor->image = url('images').'/'.$doctor->image;
                    }
                    $department['all_doctor'] = $allDoctors;
                    $allFaciltities = Facility::whereIn('id',json_decode($allDepartment['facilitties_id']))->get();
                    foreach($allFaciltities as $facility)
                    {
                        $facility->image = url('images').'/'.$facility->image;
                    }
                    $department['all_allFacilty'] = $allFaciltities;
                    array_push($allDepartmentsArray,$department);
                }
            }
            $hospital->allDepartments = $allDepartmentsArray;
            $hospital->image = url('images').'/'.$hospital->image;
        }
        $data = array();
        $data['status'] = 'success';
        $data['messsage'] = 'Hospital List Get Successfully';
        $data['data'] = $allHospital;
        echo json_encode($data);
    }


}
