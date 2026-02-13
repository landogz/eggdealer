@extends('admin.layouts.app')

@section('title', 'Activity Log')
@section('header_title', 'Activity Log')

@section('content')
    <section class="rounded-3xl bg-white/95 p-6 shadow-xl">
        <div class="mb-6">
            <h1 class="text-lg font-semibold text-slate-900 dark:text-slate-50">Activity log</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Audit trail of logins, price changes, inventory edits, and user changes.</p>
        </div>

        <form method="get" action="{{ route('admin.activity-log.index') }}" class="mb-6 flex flex-wrap items-end gap-3 rounded-2xl border border-slate-100 bg-slate-50/80 p-4 dark:border-slate-800 dark:bg-slate-800/50">
            <input type="hidden" name="per_page" value="{{ $perPage ?? 30 }}">
            <div class="min-w-[180px]">
                <label for="filter_action" class="block text-[0.7rem] font-medium text-slate-600 dark:text-slate-400">Action</label>
                <select id="filter_action" name="action" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-800 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200">
                    <option value="">All actions</option>
                    @foreach($actionLabels ?? [] as $actionKey => $label)
                        <option value="{{ $actionKey }}" {{ request('action') == $actionKey ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[140px]">
                <label for="filter_user" class="block text-[0.7rem] font-medium text-slate-600 dark:text-slate-400">User</label>
                <select id="filter_user" name="user_id" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-800 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200">
                    <option value="">All users</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == (string) $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[120px]">
                <label for="filter_from" class="block text-[0.7rem] font-medium text-slate-600 dark:text-slate-400">From date</label>
                <input type="date" id="filter_from" name="date_from" value="{{ request('date_from') }}"
                       class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-800 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200">
            </div>
            <div class="min-w-[120px]">
                <label for="filter_to" class="block text-[0.7rem] font-medium text-slate-600 dark:text-slate-400">To date</label>
                <input type="date" id="filter_to" name="date_to" value="{{ request('date_to') }}"
                       class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-xs text-slate-800 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200">
            </div>
            <button type="submit" class="rounded-xl bg-gold px-4 py-1.5 text-xs font-semibold text-slate-900 shadow-sm hover:bg-gold-soft">
                Filter
            </button>
            <a href="{{ route('admin.activity-log.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-1.5 text-xs font-medium text-slate-600 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300">
                Clear
            </a>
            <div class="ml-auto flex items-center gap-2 text-[0.7rem] text-slate-500 dark:text-slate-400">
                <span>Per page:</span>
                @foreach([10, 20, 30, 50] as $n)
                    <a href="{{ route('admin.activity-log.index', array_merge(request()->query(), ['per_page' => $n])) }}"
                       class="rounded px-2 py-0.5 {{ ($perPage ?? 30) == $n ? 'bg-amber-100 font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-200' : 'hover:bg-slate-200 dark:hover:bg-slate-700' }}">{{ $n }}</a>
                @endforeach
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white dark:border-slate-800 dark:bg-slate-900/50">
            <table class="min-w-full text-left text-xs text-slate-700 dark:text-slate-200">
                <thead class="bg-slate-50 text-[0.65rem] uppercase tracking-wider text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-2.5">Time</th>
                        <th class="px-4 py-2.5">Action</th>
                        <th class="px-4 py-2.5">User</th>
                        <th class="px-4 py-2.5">Details</th>
                        <th class="px-4 py-2.5">IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="border-t border-slate-100 dark:border-slate-800">
                            <td class="whitespace-nowrap px-4 py-2.5 text-slate-500 dark:text-slate-400">
                                {{ $log->created_at->format('M j, Y g:i A') }}
                            </td>
                            <td class="px-4 py-2.5">
                                <span class="font-medium text-slate-900 dark:text-slate-100">{{ $log->readable_action_label }}</span>
                            </td>
                            <td class="px-4 py-2.5">
                                {{ $log->user?->name ?? '—' }}
                                @if($log->user)
                                    <span class="text-slate-400">({{ $log->user->email }})</span>
                                @endif
                            </td>
                            <td class="max-w-[280px] px-4 py-2.5">
                                @if(!empty($log->readable_details))
                                    <ul class="space-y-0.5 text-slate-600 dark:text-slate-300">
                                        @foreach($log->readable_details as $line)
                                            <li>{{ $line }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-2.5 text-slate-500 dark:text-slate-400">{{ $log->ip_address ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">No activity logged yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="mt-4 flex justify-center">
                {{ $logs->links() }}
            </div>
        @endif
    </section>
@endsection
