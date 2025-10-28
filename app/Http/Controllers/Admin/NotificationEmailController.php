<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\NotificationEmail;
use Illuminate\Http\Request;

class NotificationEmailController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $emails = NotificationEmail::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.notification-emails.index', compact('emails'));
    }

    public function create()
    {
        return view('admin.notification-emails.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:notification_emails,email',
            'name' => 'nullable|string|max:255',
            'type' => 'required|in:order,contact,newsletter',
            'note' => 'nullable|string',
        ]);

        NotificationEmail::create([
            'email' => $request->email,
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
            'type' => $request->type,
            'note' => $request->note,
        ]);

        return redirect()->route('admin.notification-emails.index')
                         ->with('success', 'Email đã được thêm thành công!');
    }

    public function edit(NotificationEmail $notificationEmail)
    {
        return view('admin.notification-emails.edit', compact('notificationEmail'));
    }

    public function update(Request $request, NotificationEmail $notificationEmail)
    {
        $request->validate([
            'email' => 'required|email|unique:notification_emails,email,' . $notificationEmail->id,
            'name' => 'nullable|string|max:255',
            'type' => 'required|in:order,contact,newsletter',
            'note' => 'nullable|string',
        ]);

        $notificationEmail->update([
            'email' => $request->email,
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
            'type' => $request->type,
            'note' => $request->note,
        ]);

        return redirect()->route('admin.notification-emails.index')
                         ->with('success', 'Email đã được cập nhật thành công!');
    }

    public function destroy(NotificationEmail $notificationEmail)
    {
        $notificationEmail->delete();

        return redirect()->route('admin.notification-emails.index')
                         ->with('success', 'Email đã được xóa thành công!');
    }

    public function toggleStatus(NotificationEmail $notificationEmail)
    {
        $notificationEmail->update([
            'is_active' => !$notificationEmail->is_active
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $notificationEmail->is_active,
            'message' => 'Trạng thái đã được cập nhật!'
        ]);
    }
}
