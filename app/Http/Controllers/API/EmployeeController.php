<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use GuzzleHttp\Psr7\Response;

class EmployeeController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $age = $request->input('age');
        $gender = $request->input('gender');
        $phone = $request->input('phone');
        $team_id = $request->input('team_id');
        $role_id = $request->input('role_id');
        $company_id = $request->input('company_id');
        $limit = $request->input('limit', 10);

        $employeeQuery = Employee::query();

        // Get single data
        if ($id) {
            $employee = $employeeQuery->with(['team', 'role'])->find($id);

            if ($employee) {
                return ResponseFormatter::success($employee, 'Employee found');
            }

            return ResponseFormatter::error('Employee not found', 404);
        }



        // Get multiple data
        $employees = $employeeQuery;

        if ($name) {
            $employees->where('name', 'like', '%' . $name . '%');
        }

        if ($email) {
            $employees->where('email',  $email);
        }

        if ($age) {
            $employees->where('age',  $age);
        }

        if ($phone) {
            $employees->where('phone', 'like', '%' . $phone . '%');
        }

        if ($gender) {
            $employees->where('gender', $gender);
        }

        if ($team_id) {
            $employees->where('team_id',  $team_id);
        }

        if ($role_id) {
            $employees->where('role_id',  $role_id);
        }

        if ($company_id) {
            $employees->whereHas('team', function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            });
        }


        return ResponseFormatter::success(
            $employees->paginate($limit),
            'Employees found'
        );
    }

    public function create(CreateEmployeeRequest $request)
    {
        try {
            // Upload photo
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('public/photos');
            }

            // Create employee
            $employee = Employee::create(
                [
                    'name' => $request->name,
                    'photo' => $path,
                    'age' => $request->age,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'team_id' => $request->team_id,
                    'role_id' => $request->role_id,
                    'gender' => $request->gender,
                ]
            );

            if (!$employee) {
                throw new Exception('Failed to create employee');
            }

            return ResponseFormatter::success($employee, 'Employee created');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        try {
            $employee = Employee::find($id);

            if (!$employee) {
                throw new Exception('Employee not found');
            }

            // Upload photo
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('public/photos');
            }

            // Update employee
            $employee->update([
                'name' => $request->name,
                'photo' => isset($path) ? $path : $employee->photo,
                'age' => $request->age,
                'email' => $request->email,
                'phone' => $request->phone,
                'team_id' => $request->team_id,
                'role_id' => $request->role_id,
                'gender' => $request->gender,
            ]);

            return ResponseFormatter::success($employee, 'Employee updated');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Get employee
            $employee = Employee::find($id);

            // Check employee
            if (!$employee) {
                return throw new Exception('Employee not found');
            }

            // Delete employee
            $employee->delete();

            return ResponseFormatter::success('Employee deleted');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }
}
