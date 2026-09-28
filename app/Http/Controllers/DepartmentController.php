<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    /**
     * Check if user is authenticated admin.
     */
    protected function ensureAdmin(): void
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya administrator yang dapat mengelola departemen.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->ensureAdmin();

        $departments = Department::with(['head'])
            ->withCount(['users', 'reports', 'complaints'])
            ->orderBy('name')
            ->get();

        $potentialHeads = User::whereIn('role', ['department_head', 'staff'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.departments', compact('departments', 'potentialHeads'));
    }

    /**
     * Show the form for creating a new resource (Redirects to index modal).
     */
    public function create(): RedirectResponse
    {
        $this->ensureAdmin();

        return redirect()->route('admin.departments');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:departments,code',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'head_id' => 'nullable|exists:users,id',
            'is_active' => 'sometimes|boolean',
        ]);

        $department = Department::create([
            'name' => (string) $request->input('name'),
            'code' => strtoupper(trim((string) $request->input('code'))),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'description' => $request->input('description'),
            'head_id' => $request->input('head_id') ?: null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // If a head of department is assigned, associate their department_id
        if ($department->head_id) {
            User::where('id', $department->head_id)->update([
                'department_id' => $department->id,
            ]);
        }

        return redirect()->route('admin.departments')->with('success', 'Departemen "'.$department->name.'" berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View|RedirectResponse
    {
        $this->ensureAdmin();

        $department = Department::with(['head', 'users'])
            ->withCount(['reports', 'complaints'])
            ->findOrFail($id);

        return view('admin.departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource (Redirects to index modal).
     */
    public function edit(string $id): RedirectResponse
    {
        $this->ensureAdmin();

        return redirect()->route('admin.departments');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $this->ensureAdmin();

        $department = Department::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:departments,code,'.$id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'head_id' => 'nullable|exists:users,id',
            'is_active' => 'sometimes|boolean',
        ]);

        $previousHeadId = $department->head_id;
        $newHeadId = $request->input('head_id') ?: null;

        $department->update([
            'name' => (string) $request->input('name'),
            'code' => strtoupper(trim((string) $request->input('code'))),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'description' => $request->input('description'),
            'head_id' => $newHeadId,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Sync department head
        if ($newHeadId && (int) $newHeadId !== (int) $previousHeadId) {
            User::where('id', $newHeadId)->update([
                'department_id' => $department->id,
            ]);
        }

        return redirect()->route('admin.departments')->with('success', 'Departemen "'.$department->name.'" berhasil diperbarui.');
    }

    /**
     * Toggle active/inactive status of a department.
     */
    public function toggleStatus(string $id): RedirectResponse
    {
        $this->ensureAdmin();

        $department = Department::findOrFail($id);
        $department->is_active = ! $department->is_active;
        $department->save();

        $statusText = $department->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.departments')->with('success', 'Departemen "'.$department->name.'" berhasil '.$statusText.'.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $this->ensureAdmin();

        $department = Department::findOrFail($id);

        // Check if department has associated users, reports, or complaints
        $usersCount = $department->users()->count();
        $reportsCount = $department->reports()->count();
        $complaintsCount = $department->complaints()->count();

        if ($usersCount > 0 || $reportsCount > 0 || $complaintsCount > 0) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus departemen "'.$department->name.'" karena masih memiliki data terkait ('.$usersCount.' staf, '.$reportsCount.' laporan, '.$complaintsCount.' keluhan). Silakan nonaktifkan status departemen jika tidak digunakan.');
        }

        $departmentName = $department->name;
        $department->delete();

        return redirect()->route('admin.departments')->with('success', 'Departemen "'.$departmentName.'" berhasil dihapus.');
    }

    /**
     * API endpoint for active departments (for select dropdowns)
     */
    public function apiIndex(): JsonResponse
    {
        $departments = Department::where('is_active', true)
            ->select('id', 'name', 'code')
            ->orderBy('name')
            ->get();

        return response()->json($departments);
    }
}
