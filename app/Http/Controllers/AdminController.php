<?php

namespace App\Http\Controllers;
namespace App;


use Illuminate\Http\Request;


class AdminController extends Controller
{

    public function initialPage(Request $request, Admin $admin){

        $users = User::all();


        $groupFullNames = [];
        $groupFullID = [];
        $nameExploded = [];
        $groupNameAndIDArr = [];

        foreach(Group::orderBy('group_name', 'desc')->get() as $group){
            array_push($groupFullNames, $group->group_name);
            array_push($groupFullID, $group->id);
            array_push($nameExploded, explode('_', $group->group_name, 2)[0]);
        }

        $counts = array_count_values($nameExploded);

        foreach($counts as $year => $count){
            $offset = 0;
            $groupNames = array_slice($groupFullNames, $offset, $count);
            $groupIDs = array_slice($groupFullID, $offset, $count);

            $groups = ['year' => $year, 
                      'groupNames' => $groupNames,
                      'groupIDS' => $groupIDs];

            $offset = $offset + $count;
            array_push($groupNameAndIDArr, $groups);
        }

        return view('***********', ['users' => $users, 'GroupInfo' => $groupNameAndIDArr]);

    }
    //
    public function refreshUserContentComponent (Request $request){
        $component = $request->input('component');
        switch ($component) {
            case 'users':
                return User::all();
            
            case 'students':
                return Student::all();

            case 'teacher':
                return Teacher::all();
            
            case 'admins':
                return Admin::all();
        }
    }

    public function refreshAssignmentContentComponent (Request $request){
        $group = Group::find($request->input('groupID'));
        $assignments = [];

        foreach($group->assignments as $assignment){
            array_push($assignments, ['assignmentName' => $assignment->name,
                                      'assignmentDetail' => $assignment->getFullDetail()]);
        }

        $data = ['group' => $group,
                 'assignmentFulldetail' => $assignments];

        return $data;
    }

    public function refreshGroupContentComponent(){
        $groupsFulldetail = [];
        
        foreach(Group::all() as $group){
            $students = $group->students;
            $temp = ['students' => $students,
                    'group' => $group];
            array_push($groupsFulldetail, $temp);
        }

        return $groupsFulldetail;
    }

    public function userUpdateOrCreate(Request $request){
        $checkExist = ['id' => $request->input('userID', 'null')];
        $date = $request->only(['account', 'password', 'person_type', 'first_name', 'last_name', 'email', 'other_contact']);
        modelUpdateOrCreate(User, $checkExist, $data);
    }

    public function userDeleting(Request $request){
        return User::find($request->input('userID'))->delete();
    }

    public function groupUpdateOrCreate(Request $request){
        $checkExist = ['id' => $request->input('groupID')];
        $date = $request->only(['teacherID', 'assistantID', 'groupName']);
        modelUpdateOrCreate(Group, $checkExist, $data);
    }

    public function groupDeleting(Request $request){
        return Group::find($request->input('groupID'))->delete();
    }

    public function group_studentEditing(Request $request){
        $group = Group::find($request->input('groupID'));

        $addedStudents = $request->input('addedStudent', null);
        $deletedStudent = $request->input('deletedStudent', null);

        if(!$addedStudents){
            foreach ($addedStudents as $student) {
                GroupStudent::create(['student_id' => $student,
                                        'group_id' => $group->id]);
            }
        }

        if(!$deletedStudent){
            foreach ($deletedStudent as $student) {
                GroupStudent::where(['student_id', $student], 
                                        ['group_id', $group->id])->delete();
            }
        }
    }

    private function modelUpdateOrCreate($class, array $checkExist, array $data){
        return $class::updateOrCreate($checkExist, $data);
    }
}
