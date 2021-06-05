<?php

namespace App\Http\Controllers\Api;

use App\Student;
use App\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\StudentTeacherResource;
use App\Http\Actions\Subscription\ManageSubscription;
use App\Http\Actions\CollegeYear\CollegeYearInProgress;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('role:student');
    }

    public function index(Student $student, Request $request, CollegeYearInProgress $collegeYearInProgress)
    {
        //
        return [];
    }

    public function store(SubscriptionRequest $request, ManageSubscription $manageSubscription)
    {
        $params = $request->only(['teacher', 'matiere', 'classe', 'enseignement', 'student']);
        $subscription = $manageSubscription->create($params);
        $subscription->load(['teacher', 'collegeYear', 'matiere', 'classe', 'enseignement']);
        return $this->createdResponse(new SubscriptionResource($subscription));
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return $this->deletedResponse();
    }
}
