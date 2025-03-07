<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Application;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with('employer');

        // Optional filtering
        if ($request->has('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        // Log the incoming request details
        \Log::info('Job Creation Request', [
            'user_id' => $request->user()->id,
            'user_email' => $request->user()->email,
            'user_role' => $request->user()->role,
            'request_data' => $request->all()
        ]);

        $validator = Validator::make($request->all(), [
            'employer_id' => 'required|exists:employers,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'salary' => 'nullable|numeric',
            'location' => 'required|string',
            'job_type' => 'required|in:full-time,part-time,contract,remote',
            'application_deadline' => 'required|date',
            'status' => 'in:active,closed,draft'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Additional authorization check
        $employer = Employer::find($request->input('employer_id'));
        if (!$employer || $employer->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized to create job for this employer'], 403);
        }

        $job = Job::create($request->all());
        return response()->json($job, 201);
    }

    public function show(Job $job)
    {
        return $job->load('employer', 'applications');
    }

    public function update(Request $request, Job $job)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'requirements' => 'nullable|string',
            'salary_range' => 'nullable|string',
            'location' => 'sometimes|string',
            'job_type' => 'sometimes|in:full-time,part-time,contract,remote',
            'status' => 'sometimes|in:active,closed,draft'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $job->update($request->all());
        return response()->json($job);
    }

    public function destroy(Job $job)
    {
        $job->delete();
        return response()->json(null, 204);
    }

    public function applications(Job $job)
    {
        return $job->applications;
    }

    public function search(Request $request)
    {
        try {
            // Log the entire request for debugging
            \Log::info('Full Job Search Request:', [
                'all_params' => $request->all(),
                'query_params' => $request->query(),
                'input_params' => $request->input(),
                'headers' => $request->headers->all()
            ]);

            $validator = Validator::make($request->all(), [
                'query' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'job_type' => 'nullable|in:full-time,part-time,contract,remote'
            ]);

            if ($validator->fails()) {
                \Log::warning('Job Search Validation Failed', [
                    'errors' => $validator->errors(),
                    'input' => $request->all()
                ]);
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Retrieve parameters safely
            $searchTerm = $request->input('query', '');
            $location = $request->input('location', '');
            $jobType = $request->input('job_type', '');

            \Log::info('Parsed Job Search Parameters:', [
                'query' => $searchTerm,
                'location' => $location,
                'job_type' => $jobType
            ]);

            $query = Job::with('employer');

            // Search by query in title or description if provided
            if (!empty($searchTerm)) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });
            }

            // Filter by location if provided
            if (!empty($location)) {
                $query->where('location', 'like', '%' . $location . '%');
            }

            // Filter by job type if provided
            if (!empty($jobType)) {
                $query->where('job_type', $jobType);
            }

            $results = $query->get();

            // Log results for debugging
            \Log::info('Job Search Results:', [
                'count' => $results->count(),
                'results' => $results->map(function($job) {
                    return [
                        'id' => $job->id,
                        'title' => $job->title,
                        'location' => $job->location,
                        'job_type' => $job->job_type
                    ];
                })->toArray()
            ]);

            return response()->json($results);
        } catch (\Exception $e) {
            // Log the full error for debugging
            \Log::error('Job Search Error: ', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            // Return a generic error response
            return response()->json([
                'error' => 'An unexpected error occurred during job search.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function getEmployerJobs($employerId)
    {
        try {
            // Validate that the authenticated user owns this employer profile
            $employer = Employer::findOrFail($employerId);
            
            // Ensure the authenticated user matches the employer's user_id
            if ($employer->user_id !== auth()->id()) {
                return response()->json(['message' => 'Unauthorized to view these jobs'], 403);
            }

            // Fetch jobs with application count
            $jobs = Job::where('employer_id', $employerId)
                ->withCount('applications')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($jobs);
        } catch (\Exception $e) {
            \Log::error('Error fetching employer jobs: ' . $e->getMessage());
            return response()->json([
                'message' => 'Unable to fetch jobs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkApplicationEligibility(Request $request, $jobId)
    {
        // Detailed logging
        \Log::info('Eligibility Check Request', [
            'job_id' => $jobId,
            'user_id' => $request->user()->id,
            'user_email' => $request->user()->email,
            'full_request' => $request->all()
        ]);

        try {
            // Get the authenticated user
            $user = $request->user();

            // Find the job
            $job = Job::findOrFail($jobId);

            // Detailed job logging
            \Log::info('Job Details for Eligibility Check', [
                'job_id' => $job->id,
                'job_title' => $job->title,
                'job_status' => $job->status
            ]);

            // Check if job is active
            if ($job->status !== 'active') {
                \Log::warning('Job not active for application', [
                    'job_id' => $jobId,
                    'current_status' => $job->status
                ]);

                return response()->json([
                    'eligible' => false,
                    'message' => 'This job is no longer accepting applications.'
                ], 400);
            }

            // Check if user has already applied
            $existingApplication = Application::where('job_id', $jobId)
                ->where('user_id', $user->id)
                ->first();

            if ($existingApplication) {
                \Log::warning('Duplicate job application attempt', [
                    'job_id' => $jobId,
                    'user_id' => $user->id,
                    'existing_application_id' => $existingApplication->id
                ]);

                return response()->json([
                    'eligible' => false,
                    'message' => 'You have already applied for this job.'
                ], 400);
            }

            // Check application deadline
            if ($job->application_deadline && now() > $job->application_deadline) {
                \Log::warning('Application deadline passed', [
                    'job_id' => $jobId,
                    'application_deadline' => $job->application_deadline,
                    'current_time' => now()
                ]);

                return response()->json([
                    'eligible' => false,
                    'message' => 'Application deadline has passed.'
                ], 400);
            }

            // All checks passed
            \Log::info('Job application eligibility confirmed', [
                'job_id' => $jobId,
                'user_id' => $user->id
            ]);

            return response()->json([
                'eligible' => true,
                'message' => 'You are eligible to apply for this job.'
            ]);
        } catch (\Exception $e) {
            // Comprehensive error logging
            \Log::error('Eligibility Check Error', [
                'message' => $e->getMessage(),
                'job_id' => $jobId,
                'user_id' => $request->user()->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'eligible' => false,
                'message' => 'An error occurred while checking job eligibility.'
            ], 500);
        }
    }
}
