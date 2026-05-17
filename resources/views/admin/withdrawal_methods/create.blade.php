<x-admin-layout>
    <div class="mb-10 flex items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">নতুন <span class="text-primary-500">Method</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2">Add new withdrawal channel</p>
        </div>
        <a href="{{ route('admin.withdrawal-methods.index') }}"
            class="px-6 py-2.5 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black text-slate-400 hover:text-white transition-all uppercase tracking-widest">
            ← ফিরে যান
        </a>
    </div>

    <form action="{{ route('admin.withdrawal-methods.store') }}" method="POST">
        @csrf
        @include('admin.withdrawal_methods._form', ['method' => null])
    </form>
</x-admin-layout>
