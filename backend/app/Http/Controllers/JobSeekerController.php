<?php

namespace App\Http\Controllers;

use App\Models\JobSeeker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class JobSeekerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return JobSeeker::with('user', 'applications')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'skills' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'education_level' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jobSeeker = new JobSeeker($request->except('resume'));

        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
            $jobSeeker->resume_path = $resumePath;
        }

        $jobSeeker->save();
        return response()->json($jobSeeker, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(JobSeeker $jobSeeker)
    {
        return $jobSeeker->load('user', 'applications');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobSeeker $jobSeeker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobSeeker $jobSeeker)
    {
        $validator = Validator::make($request->all(), [
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'skills' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'education_level' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('resume')) {
            // Delete old resume if exists
            if ($jobSeeker->resume_path) {
                Storage::disk('public')->delete($jobSeeker->resume_path);
            }

            $resumePath = $request->file('resume')->store('resumes', 'public');
            $jobSeeker->resume_path = $resumePath;
        }

        $jobSeeker->update($request->except('resume'));
        return response()->json($jobSeeker);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobSeeker $jobSeeker)
    {
        // Delete resume file if exists
        if ($jobSeeker->resume_path) {
            Storage::disk('public')->delete($jobSeeker->resume_path);
        }

        $jobSeeker->delete();
        return response()->json(null, 204);
    }

    public function applications(JobSeeker $jobSeeker)
    {
        return $jobSeeker->applications;
    }

    public function downloadResume(JobSeeker $jobSeeker)
    {
        if (!$jobSeeker->resume_path) {
            return response()->json(['error' => 'No resume found'], 404);
        }

        return Storage::disk('public')->download($jobSeeker->resume_path);
    }
}
