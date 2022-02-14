<?php

namespace App\Http\Controllers;

use App\Notifications\MyFirstNotification;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Models\Doctor;

use App\Models\Appointment;

use App\Models\User;

use Notification;

use App\Notifications\SendNotification;


class AdminController extends Controller
{
    public function addview(){
        if (Auth::id()){
            if (Auth::user()->usertype==1){
                return view('admin.add_doctor');
            }else{
                return redirect()->back();
            }
        }else{
            return redirect('login');
        }

    }

    public function upload(Request $request){
        $doctor=new doctor;
        $image=$request->file;
        $imagename=time().'.'.$image->getClientOriginalExtension();
        $request->file->move('doctorimage', $imagename);
        $doctor->image=$imagename;

        $doctor->name=$request->name;
        $doctor->phone=$request->number;
        $doctor->room=$request->room;
        $doctor->speciality=$request->speciality;

        $doctor->save();

        return redirect()->back()->with('message','Doctor Added Successfully');

    }

    public function showappointments(){

        if (Auth::id()){
            if (Auth::user()->usertype==1){
                $data=appointment::all();

                return view('admin.showappointments', compact('data'));
            }else{
                return redirect()->back();
            }
        }else{
            return view('login');
        }



    }

    public function approve($id){
        $data=appointment::find($id);

        $data->status='Approved';

        $data->save();

        return redirect()->back();
    }

    public function cancel($id){
        $data=appointment::find($id);

        $data->status='Cancelled';

        $data->save();

        return redirect()->back();
    }

    public function showdoctors(){

        $data=doctor::all();

        return view('admin.showdoctor', compact('data'));
    }

    public function deletedoctor($id){

        $data=doctor::find($id);

        $data->delete();

        return redirect()->back();
    }

    public function updatedoctor($id){

        $data=doctor::find($id);

        return view('admin.update_doctor', compact('data'));
    }

    public function editdoctor(Request $request, $id){

        $doctor=doctor::find($id);

        $doctor->name=$request->name;
        $doctor->phone=$request->phone;
        $doctor->speciality=$request->speciality;
        $doctor->room=$request->room;

        $image=$request->file;

        if ($image){

            $imagename=time().'.'.$image->getClientOriginalextension();
            $request->file->move('doctorimage', $imagename);
            $doctor->image=$imagename;

        }


        $doctor->save();

        return redirect()->back()->with('message', 'Details Updated Successfully');


    }

    public function emailview($id){

        $data=appointment::find($id);

        return view('admin.email_view', compact('data'));
    }

    public function sendemail(Request $request, $id){

        $data=appointment::find($id);

        $details=[
            'greeting' => $request->greeting,
            'body' =>$request->body,
            'actiontext' => $request->actiontext,
            'actionurl' => $request->actionurl,
            'endpart' => $request->endpart

        ];

        Notification::send($data, new SendNotification($details));

        return redirect()->back()->with('message', 'Mail sent successfully');
    }
}





























