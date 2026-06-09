@extends('admin.layout')

@section('page-title', 'Kelola User')
@section('page-subtitle', 'Manajemen akun pengguna BimmerGuide')

@section('content')

<div class="card">
    <table class="tbl">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Bergabung</th>
                <th style="text-align:right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $i => $u)
            <tr>
                <td style="color:#94a3b8; font-size:12px;">{{ $users->firstItem() + $i }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div style="width:30px; height:30px; border-radius:8px; background:{{ $u->role==='admin' ? '#4f46e5' : '#f1f5f9' }}; color:{{ $u->role==='admin' ? '#fff' : '#475569' }}; font-size:12px; font-weight:700; display:flex; align-items:center; justify-content:center; font-family:'Space Grotesk',sans-serif; flex-shrink:0;">{{ strtoupper(substr($u->name,0,1)) }}</div>
                        <span style="font-size:13.5px; font-weight:500;">{{ $u->name }}</span>
                        @if($u->id === auth()->id())
                        <span style="font-size:10px; background:#fef3c7; color:#92400e; padding:1px 6px; border-radius:3px; font-weight:600;">Anda</span>
                        @endif
                    </div>
                </td>
                <td style="font-size:13px; color:#64748b;">{{ $u->email }}</td>
                <td>
                    <span class="badge {{ $u->role==='admin' ? 'badge-blue' : 'badge-gray' }}">
                        {{ $u->role === 'admin' ? '⚡ Admin' : '👤 User' }}
                    </span>
                </td>
                <td style="font-size:12px; color:#64748b;">{{ \Carbon\Carbon::parse($u->created_at)->format('d M Y') }}</td>
                <td style="text-align:right;">
                    <div style="display:flex; gap:6px; justify-content:flex-end; align-items:center;">
                        @if($u->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.user.role', $u->id) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-ghost btn-sm"
                                    onclick="return confirm('Ubah role {{ $u->name }} ke {{ $u->role==='admin'?'user':'admin' }}?')">
                                {{ $u->role === 'admin' ? 'Jadikan User' : 'Jadikan Admin' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.user.destroy', $u->id) }}"
                              onsubmit="return confirm('Hapus akun {{ $u->name }}? Semua riwayat analisisnya ikut terhapus.')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger-ghost btn-sm">Hapus</button>
                        </form>
                        @else
                        <span style="font-size:12px; color:#94a3b8;">—</span>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:48px; text-align:center; color:#94a3b8; font-size:13px;">Belum ada user.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
    <div style="padding:14px 20px; border-top:1px solid #f1f5f9; display:flex; align-items:center; gap:16px;">
        <div style="font-size:12.5px; color:#64748b;">
            {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} user
        </div>
        <div class="pagination" style="margin-left:auto;">{{ $users->links() }}</div>
    </div>
    @endif
</div>

@endsection