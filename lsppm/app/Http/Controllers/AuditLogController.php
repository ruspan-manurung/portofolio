<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuditLogResource;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuditLogController extends Controller
{
    public function index()
    {
        $auditLogs = AuditLog::with('user')->latest()->get();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Accessed Audit Log Page',
            'target' => 'Audit Log Page',
            'status' => 'Success',
            'details' => 'User successfully accessed the audit log page.',
            'ip_address' => request()->ip(),
        ]);

        return Inertia::render('AuditLog', [
            'auditLogs' => AuditLogResource::collection($auditLogs)->resolve(),
        ]);
    }
}