<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\ActivityLog;
use App\Http\Requests\MemberStoreRequest;
use App\Http\Requests\MemberUpdateRequest;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $members = Member::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
        })->latest()->paginate(10);

        // PERBAIKAN: Memilih view berdasarkan peran pengguna
        if (Auth::user()->isAdmin()) {
            return view('members.index', compact('members', 'search'));
        }

        // Default untuk kasir
        return view('kasir.members.index', compact('members', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // PERBAIKAN: Memilih view berdasarkan peran pengguna
        if (Auth::user()->isAdmin()) {
            return view('members.create');
        }
        
        return view('kasir.members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MemberStoreRequest $request)
    {
        $member = Member::create($request->validated());
        
        ActivityLog::addLog('CREATE_MEMBER', "Membuat member baru: {$member->name}");

        $redirectRoute = Auth::user()->isAdmin() ? 'admin.members.index' : 'kasir.members.index';

        return redirect()->route($redirectRoute)->with('success', 'Member berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MemberUpdateRequest $request, Member $member)
    {
        // Validasi sudah ditangani oleh UpdateMemberRequest
        $member->update($request->validated());
        
        ActivityLog::addLog('UPDATE_MEMBER', "Memperbarui member: {$member->name}");

        return redirect()->route('admin.members.index')->with('success', 'Member berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $memberName = $member->name;
        $member->delete();
        ActivityLog::addLog('DELETE_MEMBER', "Menghapus member: {$memberName}");

        return redirect()->route('admin.members.index')->with('success', 'Member berhasil dihapus.');
    }

    /**
     * 
     * Menampilkan riwayat poin untuk satu member.
     */
    public function pointHistory(Member $member)
    {
        $histories = $member->pointHistories()->latest()->paginate(20);
        return view('members.point-history', compact('member', 'histories'));
    }
}
