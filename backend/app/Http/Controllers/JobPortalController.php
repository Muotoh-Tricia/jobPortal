<?php

namespace App\Http\Controllers;

use App\Models\JobPortal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobPortalController extends Controller
{
    /**
     * Display a listing of the jobs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        Log::info('Accessing index method');
        try {
            $jobs = JobPortal::all();
            return response()->json([
                'status' => 'success',
                'data' => $jobs
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in index method: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch jobs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created job in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'companyLogo' => 'nullable|string',
                'companyName' => 'required|string|max:255',
                'Description' => 'required|string',
                'Address' => 'required|string|max:255',
                'Phone' => 'required|string|max:20',
                'Email' => 'required|email',
                'Salary' => 'required|numeric',
                'Level' => 'nullable|string|max:100',
                'Language' => 'nullable|string|max:100',
                'Country' => 'nullable|string|max:100',
                'Responsibility' => 'nullable|string',
                'Location' => 'nullable|string|max:255',
                'job_type' => 'nullable|string|max:50',
                'application_deadline' => 'nullable|date'
            ]);

            $job = JobPortal::create($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Job created successfully',
                'data' => $job
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create job',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified job.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        Log::info('Accessing show method for ID: ' . $id);
        try {
            $job = JobPortal::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $job
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in show method: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch job',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified job in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $job = JobPortal::findOrFail($id);
            $validatedData = $request->validate([
                'companyLogo' => 'nullable|string',
                'companyName' => 'required|string|max:255',
                'Description' => 'required|string',
                'Address' => 'required|string|max:255',
                'Phone' => 'required|string|max:20',
                'Email' => 'required|email',
                'Salary' => 'required|numeric',
                'Level' => 'nullable|string|max:100',
                'Language' => 'nullable|string|max:100',
                'Country' => 'nullable|string|max:100',
                'Responsibility' => 'nullable|string',
                'Location' => 'nullable|string|max:255',
                'job_type' => 'nullable|string|max:50',
                'application_deadline' => 'nullable|date'
            ]);

            $job->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Job updated successfully',
                'data' => $job
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update job',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified job from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $job = JobPortal::findOrFail($id);
            $job->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Job deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete job',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search for jobs based on criteria.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $query = JobPortal::query();

            if ($request->has('keyword')) {
                $keyword = $request->keyword;
                $query->where(function($q) use ($keyword) {
                    $q->where('companyName', 'like', "%{$keyword}%")
                      ->orWhere('Description', 'like', "%{$keyword}%")
                      ->orWhere('Location', 'like', "%{$keyword}%");
                });
            }

            if ($request->has('location')) {
                $query->where('Location', 'like', "%{$request->location}%");
            }

            if ($request->has('job_type')) {
                $query->where('job_type', $request->job_type);
            }

            if ($request->has('salary_min')) {
                $query->where('Salary', '>=', $request->salary_min);
            }

            if ($request->has('salary_max')) {
                $query->where('Salary', '<=', $request->salary_max);
            }

            $jobs = $query->get();

            return response()->json([
                'status' => 'success',
                'data' => $jobs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to search jobs',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
