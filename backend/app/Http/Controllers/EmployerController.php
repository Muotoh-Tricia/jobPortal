<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class EmployerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Employer::with('user', 'jobs')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'company_name' => 'required|string|max:255',
            'company_description' => 'nullable|string',
            'industry' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $employer = Employer::create($request->all());
        return response()->json($employer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employer $employer)
    {
        return $employer->load('user', 'jobs');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employer $employer)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'sometimes|string|max:255',
            'company_description' => 'nullable|string',
            'industry' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $employer->update($request->all());
        return response()->json($employer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employer $employer)
    {
        $employer->delete();
        return response()->json(null, 204);
    }

    /**
     * Get the jobs of the employer.
     */
    public function jobs(Employer $employer)
    {
        return $employer->jobs;
    }

    /**
     * Get the applications of the employer.
     */
    public function applications(Employer $employer)
    {
        return $employer->applications;
    }
}
