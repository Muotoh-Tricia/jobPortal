<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $validator = Validator::make($request->all(), [
            'employer_id' => 'required|exists:employers,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'salary_range' => 'nullable|string',
            'location' => 'required|string',
            'job_type' => 'required|in:full-time,part-time,contract,remote',
            'status' => 'in:active,closed,draft'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
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
}
