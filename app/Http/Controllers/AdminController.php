<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function toggleStatus(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->status = $request->status;
        $admin->save();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة المستخدم بنجاح'
        ]);
    }
}
