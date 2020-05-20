<?php

namespace App\Http\Controllers;
use App\Student;
use App\Group;
use App\Assignment;
use App\AssignmentStudent;
use App\File;

use Illuminate\Http\Request;

class StudentController extends Controller{
    
    public function initialPage(Student $student){
        $enrolGroups = $student->groups;
        $groupFullNames = [];
        $groupFullID = [];
        $nameExploded = [];
        $groupNameAndIDArr = [];

        foreach($enrolGroups as $group){
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

        return view('***********', $groupNameAndIDArr);
    }

    public function refreshGroupContentComponent(Request $request){
        $student = Student::where('user_id', $request->input('studentID'));
        $group = Group::find($request->input('groupID'));

        $groupAssignmentFullDetails = $student->studentSubmmisionWithCertainGroup($group->id);
        return ['group' => $group,
                'assignments' => $groupAssignmentFullDetails];
    }

    public function assignmentUpload(Request $request){
        $assignmentSubmission = AssignmentStudent::where(['assignment_id', $request->input('assignmentID')], 
                                                         ['student', $request->input('studentID')])->first();

        if($request->file('assignment')->isValid()){
            $uploadedFile = $request->file('assignment');
            $fileHashName = $uploadedFile->hashName();
    
            $uploadedFile->storeAs('assignment', $fileHashName);

            $prevSubmission = null;
            if($assignmentSubmission->handed){
                $prevSubmission = $assignmentSubmission->file_id;
            }

            $file = File::create(['prev_id' => $prevSubmission, 
                          'file_path' => storage_path('app\\assignment\\'.$fileHashName),
                          'file_name' => $uploadedFile->getClientOriginalName()]);
            
            $assignmentSubmission->file_id = $file->id;
            $assignmentSubmission->handed = true;
            $assignmentSubmission->save();

            return response()->json(['data' => [], 'message' => 'successful']);
        }
        return response()->json(['data' =>[], 'message' => 'unable uploading file']);
    }

    public function dowloadAssignmentRequire(Request $request){
        $ass =Assignment::find($request->input('assignmentID'));
        $requireFile = File::find($ass->file_id);

        return response()->download($requireFile->file_path, $requireFile->fileName);
    }


}
