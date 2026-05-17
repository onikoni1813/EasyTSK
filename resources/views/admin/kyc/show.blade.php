<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold dark:text-white">Review KYC: {{ $user->name }}</h2>
            <p class="text-sm text-gray-500">ID Number: {{ $user->id_number }}</p>
        </div>
        <a href="{{ route('admin.kyc.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Back</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="p-4 bg-white border rounded-lg shadow-sm dark:bg-gray-800">
            <h3 class="text-lg font-bold mb-4 dark:text-white text-center">ID Front Side</h3>
            <img src="{{ asset('storage/' . $user->id_front_path) }}" class="w-full rounded-lg cursor-pointer" onclick="window.open(this.src)">
        </div>
        <div class="p-4 bg-white border rounded-lg shadow-sm dark:bg-gray-800">
            <h3 class="text-lg font-bold mb-4 dark:text-white text-center">ID Back Side</h3>
            <img src="{{ asset('storage/' . $user->id_back_path) }}" class="w-full rounded-lg cursor-pointer" onclick="window.open(this.src)">
        </div>
    </div>

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <h3 class="text-xl font-bold mb-4 dark:text-white">Decision</h3>
        <div class="flex gap-4">
            <form action="{{ route('admin.kyc.approve', $user) }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold" onclick="return confirm('Approve this KYC?')">Approve KYC</button>
            </form>

            <button type="button" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-bold" onclick="showRejectModal()">Reject KYC</button>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="rejectModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 dark:bg-gray-800">
            <h3 class="text-xl font-bold mb-4 dark:text-white">Reject KYC</h3>
            <form action="{{ route('admin.kyc.reject', $user) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="kyc_notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Reason for rejection</label>
                    <textarea id="kyc_notes" name="kyc_notes" rows="3" class="w-full p-2.5 bg-gray-50 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-white" placeholder="e.g. Blurry image, ID expired..." required></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 dark:bg-gray-600" onclick="hideRejectModal()">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
</x-admin-layout>
