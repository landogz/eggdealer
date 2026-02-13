<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * Display activity log (audit trail) with optional filters.
     */
    public function index(Request $request): View
    {
        $query = AuditLog::with('user')
            ->orderByDesc('created_at');

        if ($request->filled('action')) {
            $actionInput = trim($request->input('action'));
            $labelToAction = array_flip(AuditLog::actionLabels());
            if (isset($labelToAction[$actionInput])) {
                $query->where('action', $labelToAction[$actionInput]);
            } else {
                $query->where('action', 'like', '%' . $actionInput . '%');
            }
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $perPage = in_array($request->input('per_page'), [10, 20, 30, 50], true) ? (int) $request->input('per_page') : 30;
        $logs = $query->paginate($perPage)->withQueryString();
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.activity-log.index', [
            'logs' => $logs,
            'users' => $users,
            'perPage' => $perPage,
            'actionLabels' => AuditLog::actionLabels(),
        ]);
    }
}
