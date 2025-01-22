<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
        $validator = Validator::make($request->all(), [
            'query' => 'required|string',
            'location' => 'nullable|string',
            'job_type' => 'nullable|in:full-time,part-time,contract,remote'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = Job::where('title', 'like', '%' . $request->query . '%')
            ->orWhere('description', 'like', '%' . $request->query . '%');

        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->has('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        return $query->get();
    }
}
