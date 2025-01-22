<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with('job', 'jobSeeker', 'user');

        // Optional filtering
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        if ($request->has('job_seeker_id')) {
            $query->where('job_seeker_id', $request->job_seeker_id);
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:jobs,id',
            'job_seeker_id' => 'required|exists:job_seekers,id',
            'user_id' => 'required|exists:users,id',
            'cover_letter' => 'nullable|string',
            'status' => 'in:pending,reviewed,accepted,rejected'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if application already exists
        $existingApplication = Application::where([
            'job_id' => $request->job_id,
            'job_seeker_id' => $request->job_seeker_id
        ])->first();

        if ($existingApplication) {
            return response()->json(['error' => 'Application already exists'], 400);
        }

        $application = Application::create($request->all());
        return response()->json($application, 201);
    }

    public function show(Application $application)
    {
        return $application->load('job', 'jobSeeker', 'user');
    }

    public function update(Request $request, Application $application)
    {
        $validator = Validator::make($request->all(), [
            'cover_letter' => 'nullable|string',
            'status' => 'in:pending,reviewed,accepted,rejected'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $application->update($request->all());
        return response()->json($application);
    }

    public function destroy(Application $application)
    {
        $application->delete();
        return response()->json(null, 204);
    }

    public function updateStatus(Request $request, Application $application)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,reviewed,accepted,rejected'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $application->status = $request->status;
        $application->save();

        return response()->json($application);
    }

    public function userApplications(Request $request)
    {
        $user = $request->user();
        return Application::where('user_id', $user->id)
            ->with('job', 'jobSeeker')
            ->get();
    }
}
