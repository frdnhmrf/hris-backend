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
        $responsibilityQuery = Responsibility::query();

        // Get single data
        if ($id) {
            $responsibility = $responsibilityQuery->find($id);

            if ($responsibility) {
                return ResponseFormatter::success($responsibility, 'Responsibility found');
            }

            return ResponseFormatter::error('Responsibility not found', 404);
        }

        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        // Get multiple data
        $responsibilities = $responsibilityQuery->where('role_id', $request->role_id);

        if ($name) {
            $responsibilities->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $responsibilities->paginate($limit),
            'Responsibilitys found'
        );
    }

    public function create(CreateResponsibilityRequest $request)
    {
        try {
            // Create responsibility
            $responsibility = Responsibility::create(
                [
                    'name' => $request->name,
                    'role_id' => $request->role_id
                ]
            );

            if (!$responsibility) {
                throw new Exception('Failed to create responsibility');
            }

            return ResponseFormatter::success($responsibility, 'Responsibility created');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function update(UpdateResponsibilityRequest $request, $id)
    {
        try {
            $responsibility = Responsibility::find($id);

            if (!$responsibility) {
                throw new Exception('responsibility not found');
            }

            // Update responsibility
            $responsibility->update([
                'name' => $request->name,
                'role_id' => $request->role_id
            ]);

            return ResponseFormatter::success($responsibility, 'Responsibility updated');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Get responsibility
            $responsibility = Responsibility::find($id);

            // Check responsibility
            if (!$responsibility) {
                return throw new Exception('Responsibility not found');
            }

            // Delete responsibility
            $responsibility->delete();

            return ResponseFormatter::success('Responsibility deleted');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }
}
