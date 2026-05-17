<x-admin-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Withdrawal <span class="text-primary-500">Methods</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                Manage active payment channels
            </p>
        </div>
        <a href="{{ route('admin.withdrawal-methods.create') }}"
            class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-primary-900/30">
            ＋ নতুন Method যোগ
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-xs font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4" id="methods-list">
        @forelse($methods as $method)
        <div class="glass-card p-6 flex flex-col md:flex-row md:items-center gap-6" id="method-row-{{ $method->id }}">

            {{-- Drag Handle / Order --}}
            <div class="text-slate-600 font-black text-lg w-8 text-center">{{ $method->sort_order }}</div>

            {{-- Icon + Name --}}
            <div class="flex items-center gap-4 flex-1 min-w-0">
                <span class="text-3xl">{{ $method->icon_emoji }}</span>
                <div>
                    <p class="text-sm font-black text-white">{{ $method->label }}</p>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $method->name }}</p>
                </div>
            </div>

            {{-- Min Amount --}}
            <div class="text-center">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Min Amount</p>
                <p class="text-sm font-black text-white">৳ {{ number_format($method->min_amount, 0) }}</p>
            </div>

            {{-- Charge --}}
            <div class="text-center">
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Charge</p>
                <p class="text-sm font-black text-amber-400">{{ $method->charge_label }}</p>
            </div>

            {{-- Active Toggle --}}
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-black uppercase tracking-widest"
                    id="toggle-label-{{ $method->id }}"
                    :class="{}">
                    <span class="{{ $method->is_active ? 'text-emerald-400' : 'text-rose-400' }}" id="toggle-text-{{ $method->id }}">
                        {{ $method->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </span>
                <button
                    onclick="toggleMethod({{ $method->id }}, this)"
                    class="relative w-12 h-6 rounded-full transition-all duration-300 focus:outline-none {{ $method->is_active ? 'bg-emerald-500' : 'bg-white/10' }}"
                    id="toggle-btn-{{ $method->id }}"
                    title="On/Off করুন">
                    <span class="absolute top-1 transition-all duration-300 w-4 h-4 rounded-full bg-white shadow-md {{ $method->is_active ? 'left-7' : 'left-1' }}"
                        id="toggle-knob-{{ $method->id }}"></span>
                </button>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.withdrawal-methods.edit', $method) }}"
                    class="px-4 py-2 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border border-white/5">
                    ✏️ Edit
                </a>
                <form action="{{ route('admin.withdrawal-methods.destroy', $method) }}" method="POST"
                    onsubmit="return confirm('{{ $method->label }} মুছে ফেলবেন?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border border-rose-500/20">
                        🗑️
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="glass-card p-20 text-center">
            <p class="text-slate-500 italic text-sm">কোনো Withdrawal Method নেই।</p>
            <a href="{{ route('admin.withdrawal-methods.create') }}" class="mt-4 inline-block text-primary-500 font-black text-xs uppercase tracking-widest">+ প্রথমটি যোগ করুন</a>
        </div>
        @endforelse
    </div>

    @push('scripts')
    <script>
        async function toggleMethod(id, btn) {
            btn.disabled = true;
            try {
                const res = await fetch(`/{{ env('ADMIN_PREFIX', 'admin') }}/withdrawal-methods/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });
                const data = await res.json();
                if (data.success) {
                    const knob  = document.getElementById(`toggle-knob-${id}`);
                    const text  = document.getElementById(`toggle-text-${id}`);

                    if (data.is_active) {
                        btn.classList.remove('bg-white/10');
                        btn.classList.add('bg-emerald-500');
                        knob.classList.remove('left-1');
                        knob.classList.add('left-7');
                        text.textContent = 'Active';
                        text.className = 'text-emerald-400';
                    } else {
                        btn.classList.remove('bg-emerald-500');
                        btn.classList.add('bg-white/10');
                        knob.classList.remove('left-7');
                        knob.classList.add('left-1');
                        text.textContent = 'Inactive';
                        text.className = 'text-rose-400';
                    }
                }
            } catch(e) { alert('Error!'); }
            btn.disabled = false;
        }
    </script>
    @endpush
</x-admin-layout>
