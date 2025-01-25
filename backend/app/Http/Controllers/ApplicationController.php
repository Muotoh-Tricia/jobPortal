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
        // Validate application submission
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|exists:jobs,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'location' => 'required|string|max:255',
            'cover_letter' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120' // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Get the current authenticated user
            $user = Auth::user();

            // Check if job seeker profile exists, create if not
            $jobSeeker = $user->jobSeeker ?? $user->jobSeeker()->create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'location' => $request->input('location')
            ]);

            // Check for existing application
            $existingApplication = Application::where([
                'job_id' => $request->input('job_id'),
                'user_id' => $user->id
            ])->first();

            if ($existingApplication) {
                return response()->json(['error' => 'You have already applied for this job'], 400);
            }

            // Handle resume upload
            $resumePath = null;
            if ($request->hasFile('resume')) {
                $resume = $request->file('resume');
                $resumeName = time() . '_' . $resume->getClientOriginalName();
                $resumePath = $resume->storeAs('resumes', $resumeName, 'public');
            }

            // Create application
            $application = Application::create([
                'job_id' => $request->input('job_id'),
                'job_seeker_id' => $jobSeeker->id,
                'user_id' => $user->id,
                'cover_letter' => $request->input('cover_letter'),
                'resume_path' => $resumePath,
                'status' => 'pending'
            ]);

            // Log the application
            \Log::info('Job Application Submitted', [
                'application_id' => $application->id,
                'job_id' => $application->job_id,
                'user_id' => $application->user_id,
                'job_seeker_id' => $application->job_seeker_id
            ]);

            return response()->json($application->load('job', 'jobSeeker'), 201);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Job Application Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'An unexpected error occurred while submitting your application',
                'details' => $e->getMessage()
            ], 500);
        }
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
