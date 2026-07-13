@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">System Notifications</h2>
            <p class="text-sm text-slate-400 mt-1">Track warranty expiry alerts, salvage thresholds, and system events.</p>
        </div>
        
        @if($notifications->whereNull('read_at')->count() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl transition cursor-pointer">
                    Mark All as Read
                </button>
            </form>
        @endif
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="p-6 rounded-2xl bg-[#0E1325]/80 border border-slate-800/80">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase font-medium">
                    <tr>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Message</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($notifications as $notif)
                        <tr class="hover:bg-slate-800/20 {{ is_null($notif->read_at) ? 'bg-indigo-500/5' : '' }}">
                            <td class="px-6 py-4">
                                @if(is_null($notif->read_at))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400">
                                        Unread
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-800 text-slate-400">
                                        Read
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-full {{ $notif->type === 'warranty' ? 'bg-amber-500/10 text-amber-400' : 'bg-rose-500/10 text-rose-400' }}">
                                    {{ $notif->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-white">
                                {{ $notif->title }}
                            </td>
                            <td class="px-6 py-4 text-xs max-w-sm">
                                {{ $notif->message }}
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-slate-500">
                                {{ $notif->created_at->format('d-m-Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    @if($notif->asset_id)
                                        <a href="{{ route('assets.show', $notif->asset_id) }}" class="px-2.5 py-1.5 bg-indigo-600/10 hover:bg-indigo-600 text-indigo-400 hover:text-white font-medium text-xs rounded-lg transition">
                                            View Asset
                                        </a>
                                    @endif
                                    @if(is_null($notif->read_at))
                                        <button type="button" onclick="markRead({{ $notif->id }})" class="px-2.5 py-1.5 bg-emerald-600/10 hover:bg-emerald-600 text-emerald-400 hover:text-white font-medium text-xs rounded-lg transition cursor-pointer">
                                            Mark Read
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">No notifications in system log.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
</div>

<script>
    async function markRead(id) {
        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch(`/api/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            });
            const data = await res.json();
            if (data.success) {
                window.location.reload();
            } else {
                alert('Could not update notification.');
            }
        } catch (err) {
            console.error(err);
        }
    }
</script>
@endsection
