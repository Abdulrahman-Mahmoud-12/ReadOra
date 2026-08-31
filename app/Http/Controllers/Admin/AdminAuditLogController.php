<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $action = $request->string('action')->trim()->toString();
        $search = $request->string('search')->trim()->toString();

        $logs = AuditLog::query()
            ->with('actor')
            ->when($action !== '', fn ($q) => $q->where('action', 'like', "%{$action}%"))
            ->when($search !== '', function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('actor', fn ($aq) => $aq->where('name', 'like', "%{$search}%"));
            })
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.audit-logs.index', [
            'logs' => $logs,
            'action' => $action,
            'search' => $search,
        ]);
    }
}
