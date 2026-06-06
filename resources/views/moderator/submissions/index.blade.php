<x-user-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold dark:text-white">Moderator Panel: Pending Submissions</h2>
    </div>

    <!-- Stats for Moderator -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="p-4 bg-green-50 border border-green-200 rounded-lg dark:bg-gray-800 dark:border-green-800">
            <h5 class="text-sm font-medium text-green-800 dark:text-green-400">Total Reviews Done</h5>
            <p class="text-2xl font-bold text-green-900 dark:text-white">{{ auth()->user()->total_reviews_done ?? 0 }}</p>
        </div>
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg dark:bg-gray-800 dark:border-blue-800">
            <h5 class="text-sm font-medium text-blue-800 dark:text-blue-400">Moderator Earnings</h5>
            <p class="text-2xl font-bold text-blue-900 dark:text-white">{{ number_format(auth()->user()->moderator_earnings_bdt ?? 0, 2) }} BDT</p>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3">Task & User</th>
                    <th class="px-6 py-3">Proof</th>
                    <th class="px-6 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $sub)
                <tr class="bg-white border-b dark:bg-gray-800 dark:hover:bg-gray-700">
                    <td class="px-6 py-4">
                        <div class="font-bold text-blue-600">{{ optional($sub->task)->title ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">By: {{ optional($sub->user)->name ?? 'Deleted User' }}</div>
                        <div class="text-xs text-gray-400">Submitted: {{ $sub->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ asset('storage/' . $sub->proof_image) }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View Proof
                        </a>
                    </td>
                    <td class="px-6 py-4 flex justify-center gap-2">
                        <form action="{{ route('admin.submissions.approve', $sub) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-xs">Approve</button>
                        </form>
                        <form action="{{ route('admin.submissions.reject', $sub) }}" method="POST">
                            @csrf
                            <input type="hidden" name="admin_notes" value="Rejected by Moderator">
                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-xs">Reject</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center">No pending submissions for review.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $submissions->links() }}
    </div>
</x-user-layout>