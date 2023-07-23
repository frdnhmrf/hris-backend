<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Responsibility;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateResponsibilityRequest;
use App\Http\Requests\UpdateResponsibilityRequest;

class ResponsibilityController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $roleQuery = Responsibility::query();

        // Get single data
        if ($id) {
            $role = $roleQuery->find($id);

            if ($role) {
                return ResponseFormatter::success($role, 'Responsibility found');
            }

            return ResponseFormatter::error('Responsibility not found', 404);
        }

        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        // Get multiple data
        $roles = $roleQuery->where('company_id', $request->company_id);

        if ($name) {
            $roles->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $roles->paginate($limit),
            'Responsibilitys found'
        );
    }

    public function create(CreateResponsibilityRequest $request)
    {
        try {
            // Create role
            $role = Responsibility::create(
                [
                    'name' => $request->name,
                    'company_id' => $request->company_id
                ]
            );

            if (!$role) {
                throw new Exception('Failed to create role');
            }

            return ResponseFormatter::success($role, 'Responsibility created');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function update(UpdateResponsibilityRequest $request, $id)
    {
        try {
            $role = Responsibility::find($id);

            if (!$role) {
                throw new Exception('role not found');
            }

            // Update role
            $role->update([
                'name' => $request->name,
                'team_id' => $request->team_id
            ]);

            return ResponseFormatter::success($role, 'Responsibility updated');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Get role
            $role = Responsibility::find($id);

            // Check role
            if (!$role) {
                return throw new Exception('Responsibility not found');
            }

            // Delete role
            $role->delete();

            return ResponseFormatter::success('Responsibility deleted');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }
}
