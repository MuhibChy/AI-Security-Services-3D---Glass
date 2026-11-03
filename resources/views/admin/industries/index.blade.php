@extends('layouts.app')
@section('page-title', 'Industries')
@section('content')

<x-page-header title="Industries" sys="CONTENT://INDUSTRIES" />

<div class="term-panel p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Preferred industries</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Manage the 20 preferred industries added to the public collection. The original 20 sectors remain
                preserved on the <a href="{{ route('industries') }}" target="_blank" class="underline">public Industries page</a>.
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('industries') }}" target="_blank" class="term-btn term-btn-ghost term-btn-sm">View Public Page</a>
            <a href="{{ route('admin.industries.create') }}" class="term-btn term-btn-sm">Add Industry</a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-200">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto term-table-wrap">
        <table class="data-table min-w-full text-sm term-table">
            <thead>
                <tr class="text-left text-gray-500">
                    <th class="py-2 pr-4">Industry</th>
                    <th class="py-2 pr-4">Category</th>
                    <th class="py-2 pr-4">Status</th>
                    <th class="py-2 pr-4">Homepage</th>
                    <th class="py-2 pr-4">Order</th>
                    <th class="py-2 pr-4">Updated</th>
                    <th class="py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr class="border-t border-gray-100 dark:border-gray-800">
                    <td class="py-2 pr-4 font-semibold" data-label="Industry">
                        {{ $item->name }}
                        <div class="text-xs text-gray-500 font-normal">{{ $item->slug }}</div>
                    </td>
                    <td class="py-2 pr-4" data-label="Category">{{ $item->category ?? '—' }}</td>
                    <td class="py-2 pr-4" data-label="Status">
                        <span class="term-tag">{{ $item->is_active ? 'Active' : 'Hidden' }}</span>
                    </td>
                    <td class="py-2 pr-4" data-label="Homepage">
                        <span class="term-tag">{{ $item->show_on_homepage ? 'Shown' : 'Not shown' }}</span>
                    </td>
                    <td class="py-2 pr-4" data-label="Order">{{ $item->sort_order }}</td>
                    <td class="py-2 pr-4" data-label="Updated">{{ optional($item->updated_at)->format('Y-m-d') }}</td>
                    <td class="py-2" data-label="Actions">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('industries.show', $item->slug) }}" target="_blank" class="text-xs underline">View</a>
                            <a href="{{ route('admin.industries.edit', $item) }}" class="text-xs underline">Edit</a>
                            <form method="POST" action="{{ route('admin.industries.destroy', $item) }}" class="inline" onsubmit="return confirm('Delete this industry? The public page will stop listing it.')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 text-xs">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-gray-500">No managed industries yet. Run the IndustrySeeder or add one.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
</div>
@endsection
