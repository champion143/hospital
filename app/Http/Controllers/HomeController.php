<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\Facility;
use App\Models\Hospital;
use App\Models\Relationship;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use PhpParser\Comment\Doc;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $DoctorCount = Doctor::count();
        $DepartmentCount = Department::count();
        $FacilityCount = Facility::count();
        $HospitalCount = Hospital::count();
        return view('home')->with('DoctorCount',$DoctorCount)->with('DepartmentCount',$DepartmentCount)->with('FacilityCount',$FacilityCount)->with('HospitalCount',$HospitalCount);
    }

    public function hospital()
    {
        $allHospital = Hospital::orderBy('id','desc')->get();
        return view('hospital')->with('allHospital',$allHospital);
    }

    public function addhospital()
    {
        $allDepartment = Department::orderBy('id','desc')->get();
        $allDoctor = Doctor::orderBy('id','desc')->get();
        $allFacility = Facility::orderBy('id','desc')->get();
        $allTahs = Tag::orderBy('id','desc')->get();
        return view('addhospital')->with('allDepartment',$allDepartment)->with('allDoctor',$allDoctor)->with('allFacility',$allFacility)->with('allTahs',$allTahs);
    }

    public function addactionhospital(Request $request)
    {
        $Hospital = new Hospital();
        $Hospital->name = $request->input('name');
        $Hospital->title = $request->input('title');
        $Hospital->location = $request->input('location');
        $Hospital->description = $request->input('description');
        $Hospital->phone = $request->input('phone');
        $Hospital->tag = $request->input('tag');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $Hospital->image = $name;
        }
        $Hospital->save();
        $departments = $request->input('department');
        foreach($departments as $department)
        {
            $doctors = $request->input('doctor'.$department);
            $facilitys = $request->input('facility'.$department);
            $Relation = new Relationship();
            $Relation->department_id = $department;
            $Relation->doctors_id = json_encode($doctors);
            $Relation->facilitties_id = json_encode($facilitys);
            $Relation->hospital_id = $Hospital->id;
            $Relation->save();
        }
        return redirect()->route('hospital');
    }

    public function deletehospital(Request $request)
    {
        $id = $request->input('id');
        Hospital::where('id',$id)->delete();
        return response(array('status'=>'success','message'=>'hospital deleted successfully'), 200);
    }

    public function editHospital($id)
    {
        $data = Hospital::where('id',$id)->first();
        $relationship_data = Relationship::where('hospital_id',$id)->get();
        $allDepartment = Department::orderBy('id','desc')->get();
        $allDoctor = Doctor::orderBy('id','desc')->get();
        $allFacility = Facility::orderBy('id','desc')->get();
        $allTahs = Tag::orderBy('id','desc')->get();
        return view('edithospital')->with('data',$data)->with('allDepartment',$allDepartment)->with('allDoctor',$allDoctor)->with('allFacility',$allFacility)->with('relationship_data',$relationship_data)->with('allTahs',$allTahs);
    }

    public function updatehospital(Request $request)
    {
        $userDetail = array();
        $userDetail['name'] = $request->input('name');
        $userDetail['title'] = $request->input('title');
        $userDetail['location'] = $request->input('location');
        $userDetail['description'] = $request->input('description');
        $userDetail['phone'] = $request->input('phone');
        $userDetail['tag'] = $request->input('tag');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $userDetail['image'] = $name;
        }
        Hospital::where('id',$request->input('id'))->update($userDetail);
        Relationship::where('hospital_id',$request->input('id'))->delete();
        $departments = $request->input('department');
        foreach($departments as $department)
        {
            $doctors = $request->input('doctor'.$department);
            $facilitys = $request->input('facility'.$department);
            $Relation = new Relationship();
            $Relation->department_id = $department;
            $Relation->doctors_id = json_encode($doctors);
            $Relation->facilitties_id = json_encode($facilitys);
            $Relation->hospital_id = $request->input('id');
            $Relation->save();
        }
        return redirect()->route('hospital');
    }

    public function department()
    {
        $allHospital = Hospital::orderBy('id','desc')->get();
        $alldepartment = Department::orderBy('id','desc')->get();
        return view('department')->with('alldepartment',$alldepartment)->with('allHospital',$allHospital);
    }

    public function adddepartment()
    {
        $allHospital = Hospital::orderBy('id','desc')->get();
        return view('adddepartment')->with('allHospital',$allHospital);
    }

    public function addactiondepartment(Request $request)
    {
        $department = new Department();
        $department->description = $request->input('description');
        $department->title = $request->input('title');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $department->image = $name;
        }
        $department->save();
        return redirect()->route('department');
    }

    public function deletedepartment(Request $request)
    {
        $id = $request->input('id');
        Department::where('id',$id)->delete();
        return response(array('status'=>'success','message'=>'department deleted successfully'), 200);
    }

    public function editdepartment($id)
    {
        $allHospital = Hospital::orderBy('id','desc')->get();
        $data = Department::where('id',$id)->first();
        return view('editdepartment')->with('data',$data)->with('allHospital',$allHospital);
    }

    public function updatedepartment(Request $request)
    {
        $userDetail = array();
        $userDetail['description'] = $request->input('description');
        $userDetail['title'] = $request->input('title');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $userDetail['image'] = $name;
        }
        Department::where('id',$request->input('id'))->update($userDetail);
        return redirect()->route('department');

    }

    public function facility()
    {
        $alldepartment = Department::orderBy('id','desc')->get();
        $allfacility = Facility::orderBy('id','desc')->get();
        return view('facility')->with('allfacility',$allfacility)->with('alldepartment',$alldepartment);
    }

    public function addfacility()
    {
        $alldepartment = Department::orderBy('id','desc')->get();
        return view('addfacility')->with('alldepartment',$alldepartment);
    }

    public function addactionfacility(Request $request)
    {
        $facility = new Facility();
        $facility->description = $request->input('description');
        $facility->title = $request->input('title');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $facility->image = $name;
        }
        $facility->save();
        return redirect()->route('facility');
    }

    public function deletefacility(Request $request)
    {
        $id = $request->input('id');
        Facility::where('id',$id)->delete();
        return response(array('status'=>'success','message'=>'facility deleted successfully'), 200);
    }

    public function editfacility($id)
    {
        $alldepartment = Department::orderBy('id','desc')->get();
        $data = Facility::where('id',$id)->first();
        return view('editfacility')->with('data',$data)->with('alldepartment',$alldepartment);
    }

    public function updatefacility(Request $request)
    {
        $userDetail = array();
        $userDetail['description'] = $request->input('description');
        $userDetail['title'] = $request->input('title');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $userDetail['image'] = $name;
        }
        Facility::where('id',$request->input('id'))->update($userDetail);
        return redirect()->route('facility');

    }

    public function doctor()
    {
        $alldepartment = Department::orderBy('id','desc')->get();
        $alldoctor = Doctor::orderBy('id','desc')->get();
        return view('doctor')->with('alldoctor',$alldoctor)->with('alldepartment',$alldepartment);
    }

    public function adddoctor()
    {
        $allDepartment = Department::orderBy('id','desc')->get();
        $allDoctor = Doctor::orderBy('id','desc')->get();
        $allFacility = Facility::orderBy('id','desc')->get();
        return view('adddoctor')->with('allDepartment',$allDepartment)->with('allDoctor',$allDoctor)->with('allFacility',$allFacility);
    }

    public function addactiondoctor(Request $request)
    {
        $doctor = new Doctor();
        $doctor->description = $request->input('description');
        $doctor->first_name = $request->input('first_name');
        $doctor->last_name = $request->input('last_name');
        $doctor->dob = $request->input('dob');
        $doctor->gender = $request->input('gender');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $doctor->image = $name;
        }
        $doctor->save();
        return redirect()->route('doctor');
    }

    public function deletedoctor(Request $request)
    {
        $id = $request->input('id');
        Doctor::where('id',$id)->delete();
        return response(array('status'=>'success','message'=>'doctor deleted successfully'), 200);
    }

    public function editdoctor($id)
    {
        $alldepartment = Department::orderBy('id','desc')->get();
        $data = Doctor::where('id',$id)->first();
        return view('editdoctor')->with('data',$data)->with('alldepartment',$alldepartment);
    }

    public function updatedoctor(Request $request)
    {
        $userDetail = array();
        $userDetail['description'] = $request->input('description');
        $userDetail['first_name'] = $request->input('first_name');
        $userDetail['last_name'] = $request->input('last_name');
        $userDetail['dob'] = $request->input('dob');
        $userDetail['gender'] = $request->input('gender');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $userDetail['image'] = $name;
        }
        Doctor::where('id',$request->input('id'))->update($userDetail);
        return redirect()->route('doctor');

    }

    public function getTag()
    {
        $response = array();
        $alltag = Tag::orderBy('id','desc')->get();
        foreach($alltag as $tag)
        {
            $response[] = array("value"=>$tag->id,"label"=>$tag->name);
        }
        return response()->json($response);
    }

    public function tag()
    {
        $alltag = Tag::orderBy('id','desc')->get();
        return view('tag')->with('alltag',$alltag);
    }

    public function addactiontag(Request $request)
    {
        $tag = new Tag();
        $tag->name = $request->input('name');
        $tag->save();
        return redirect()->route('tag');
    }

    public function deletetag(Request $request)
    {
        $id = $request->input('id');
        Tag::where('id',$id)->delete();
        return response(array('status'=>'success','message'=>'tag deleted successfully'), 200);
    }



}
