<x-admin-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6"
        x-data="{ editModalOpen: false, currentDomain: {} }">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase italic">Domain <span
                    class="text-primary-500">Manager</span></h1>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-primary-500 rounded-full animate-pulse"></span>
                Task rotation network control center
            </p>
        </div>
    </div>

    @if (session('success'))
        <div
            class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-500 text-sm font-bold animate-pulse">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8"
        x-data="{ editMode: false, editId: null, editDomain: '', editAdCode1: '', editAdCode2: '', editAdCode3: '', editDirectLink: '', editActive: true }">
        <!-- Add / Edit Domain Form -->
        <div class="lg:col-span-1">
            <div class="glass-card p-8">
                <!-- Header (Add Mode) -->
                <div x-show="!editMode" class="flex items-center gap-3 mb-8">
                    <div
                        class="w-10 h-10 bg-primary-500/10 text-primary-500 rounded-xl flex items-center justify-center border border-primary-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Add Network Domain</h3>
                </div>

                <!-- Header (Edit Mode) -->
                <div x-show="editMode" class="flex items-center gap-3 mb-8" x-cloak>
                    <div
                        class="w-10 h-10 bg-amber-500/10 text-amber-500 rounded-xl flex items-center justify-center border border-amber-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Edit Network Domain</h3>
                </div>

                <!-- Create Form -->
                <form x-show="!editMode" action="{{ route('admin.domains.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="domain"
                            class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Domain
                            URL</label>
                        <input type="text" id="domain" name="domain" placeholder="subdomain.example.com"
                            class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-primary-500 transition-all placeholder:text-slate-500 outline-none"
                            required>
                    </div>

                    <div>
                        <label for="ad_code_1"
                            class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Banner
                            Ad 1 Script ({ad_code_1})</label>
                        <textarea id="ad_code_1" name="ad_code_1" rows="3"
                            placeholder="Paste unique Adsterra/Monetag banner 1 code..."
                            class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs focus:bg-white/10 focus:ring-primary-500 transition-all placeholder:text-slate-500 outline-none"></textarea>
                    </div>

                    <div>
                        <label for="ad_code_2"
                            class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Banner
                            Ad 2 Script ({ad_code_2})</label>
                        <textarea id="ad_code_2" name="ad_code_2" rows="3"
                            placeholder="Paste unique Adsterra/Monetag banner 2 code..."
                            class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs focus:bg-white/10 focus:ring-primary-500 transition-all placeholder:text-slate-500 outline-none"></textarea>
                    </div>

                    <div>
                        <label for="ad_code_3"
                            class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Banner
                            Ad 3 Script ({ad_code_3})</label>
                        <textarea id="ad_code_3" name="ad_code_3" rows="3"
                            placeholder="Paste unique Adsterra/Monetag banner 3 code..."
                            class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs focus:bg-white/10 focus:ring-primary-500 transition-all placeholder:text-slate-500 outline-none"></textarea>
                    </div>

                    <div>
                        <label for="direct_link"
                            class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Custom
                            Direct Link ({direct_link})</label>
                        <input type="text" id="direct_link" name="direct_link"
                            placeholder="https://custom-direct-link.com"
                            class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-primary-500 transition-all placeholder:text-slate-500 outline-none">
                    </div>

                    <div class="flex items-center gap-3 bg-white/5 p-4 rounded-2xl border border-white/5">
                        <input type="checkbox" id="is_active" name="is_active" value="1" checked
                            class="w-5 h-5 bg-dark border-white/10 rounded focus:ring-primary-500 text-primary-500">
                        <label for="is_active" class="text-sm font-bold text-white cursor-pointer">Activate
                            Immediately</label>
                    </div>

                    <button type="submit" class="w-full premium-btn py-4 rounded-2xl text-xs">
                        Register Domain
                    </button>
                </form>

                <!-- Update Form -->
                <form x-show="editMode"
                    :action="'{{ route('admin.domains.update', ['domain' => '__ID__']) }}'.replace('__ID__', editId)"
                    method="POST" class="space-y-6" x-cloak>
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="edit_domain_input"
                            class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Domain
                            URL</label>
                        <input type="text" id="edit_domain_input" name="domain" x-model="editDomain"
                            class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-primary-500 transition-all outline-none"
                            required>
                    </div>

                    <div>
                        <label for="edit_ad_code_1_input"
                            class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Banner
                            Ad 1 Script ({ad_code_1})</label>
                        <textarea id="edit_ad_code_1_input" name="ad_code_1" rows="3" x-model="editAdCode1"
                            class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs focus:bg-white/10 focus:ring-primary-500 transition-all outline-none"></textarea>
                    </div>

                    <div>
                        <label for="edit_ad_code_2_input"
                            class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Banner
                            Ad 2 Script ({ad_code_2})</label>
                        <textarea id="edit_ad_code_2_input" name="ad_code_2" rows="3" x-model="editAdCode2"
                            class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs focus:bg-white/10 focus:ring-primary-500 transition-all outline-none"></textarea>
                    </div>

                    <div>
                        <label for="edit_ad_code_3_input"
                            class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Banner
                            Ad 3 Script ({ad_code_3})</label>
                        <textarea id="edit_ad_code_3_input" name="ad_code_3" rows="3" x-model="editAdCode3"
                            class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-mono text-xs focus:bg-white/10 focus:ring-primary-500 transition-all outline-none"></textarea>
                    </div>

                    <div>
                        <label for="edit_direct_link_input"
                            class="block mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 leading-none">Custom
                            Direct Link ({direct_link})</label>
                        <input type="text" id="edit_direct_link_input" name="direct_link" x-model="editDirectLink"
                            class="w-full bg-white/5 border border-white/5 rounded-2xl p-4 text-white font-black text-sm focus:bg-white/10 focus:ring-primary-500 transition-all outline-none">
                    </div>

                    <div class="flex items-center gap-3 bg-white/5 p-4 rounded-2xl border border-white/5">
                        <input type="checkbox" id="edit_is_active_input" name="is_active" value="1"
                            :checked="editActive"
                            class="w-5 h-5 bg-dark border-white/10 rounded focus:ring-primary-500 text-primary-500">
                        <label for="edit_is_active_input" class="text-sm font-bold text-white cursor-pointer">Activate
                            Domain</label>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" @click="editMode = false"
                            class="w-1/2 bg-white/10 border border-white/5 py-4 rounded-2xl text-xs text-white font-bold hover:bg-white/20 transition-all">
                            Cancel
                        </button>
                        <button type="submit"
                            class="w-1/2 bg-amber-500 text-dark font-black hover:bg-amber-400 py-4 rounded-2xl text-xs transition-all">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Domains List -->
        <div class="lg:col-span-2">
            <div class="glass-card overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/5">
                            <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">
                                Network Node (Subdomain)</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest">Unique
                                Ad Config</th>
                            <th
                                class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">
                                Status</th>
                            <th
                                class="px-6 py-5 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($domains as $domain)
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-5">
                                    <div class="font-black text-white text-sm tracking-tight truncate">{{ $domain->domain }}
                                    </div>
                                    <div class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-1">Added:
                                        {{ $domain->created_at->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col gap-1.5">
                                        <div class="flex flex-wrap gap-1">
                                            @if($domain->ad_code_1)
                                                <span
                                                    class="px-1.5 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-[8px] font-black text-emerald-400 tracking-wider rounded">AD
                                                    1</span>
                                            @endif
                                            @if($domain->ad_code_2)
                                                <span
                                                    class="px-1.5 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-[8px] font-black text-emerald-400 tracking-wider rounded">AD
                                                    2</span>
                                            @endif
                                            @if($domain->ad_code_3)
                                                <span
                                                    class="px-1.5 py-0.5 bg-emerald-500/10 border border-emerald-500/20 text-[8px] font-black text-emerald-400 tracking-wider rounded">AD
                                                    3</span>
                                            @endif
                                            @if(!$domain->ad_code_1 && !$domain->ad_code_2 && !$domain->ad_code_3)
                                                <span class="text-[9px] font-bold text-slate-500">NO BANNER ADS</span>
                                            @endif
                                        </div>

                                        @if($domain->direct_link)
                                            <span
                                                class="inline-flex items-center gap-1 text-[9px] font-black text-indigo-400 tracking-wider">
                                                <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-pulse"></span>
                                                DIRECT LINK
                                            </span>
                                        @else
                                            <span class="text-[9px] font-bold text-slate-500">NO DIRECT LINK</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <form action="{{ route('admin.domains.toggle', $domain) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1 bg-white/5 border border-white/5 rounded-full text-[9px] font-black uppercase tracking-widest italic {{ $domain->is_active ? 'text-emerald-400' : 'text-slate-500' }}">
                                            {{ $domain->is_active ? 'ONLINE' : 'OFFLINE' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <!-- Edit Button (AlpineJS Triggers Edit Form with multiple banner codes) -->
                                        <button type="button" data-domain="{{ e(json_encode($domain->domain)) }}"
                                            data-ad-code-1="{{ e(json_encode($domain->ad_code_1)) }}"
                                            data-ad-code-2="{{ e(json_encode($domain->ad_code_2)) }}"
                                            data-ad-code-3="{{ e(json_encode($domain->ad_code_3)) }}"
                                            data-direct-link="{{ e(json_encode($domain->direct_link)) }}"
                                            @click="editMode = true; editId = '{{ $domain->id }}'; editDomain = JSON.parse($el.dataset.domain); editAdCode1 = JSON.parse($el.dataset.adCode1); editAdCode2 = JSON.parse($el.dataset.adCode2); editAdCode3 = JSON.parse($el.dataset.adCode3); editDirectLink = JSON.parse($el.dataset.directLink); editActive = {{ $domain->is_active ? 'true' : 'false' }}; window.scrollTo({top: 0, behavior: 'smooth'});"
                                            class="p-2 bg-amber-500/10 rounded-xl border border-amber-500/10 text-amber-500 hover:bg-amber-500 hover:text-dark transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>

                                        <!-- Delete Button -->
                                        <form action="{{ route('admin.domains.destroy', $domain) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 bg-rose-500/10 rounded-xl border border-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all group/icon"
                                                onclick="return confirm('Remove this domain from the network?')">
                                                <svg class="w-4 h-4 group-hover/icon:scale-110 transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="px-6 py-16 text-center text-slate-500 italic text-xs uppercase tracking-widest">
                                    No domains connected to the network</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($domains->hasPages())
                <div class="mt-6">
                    {{ $domains->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>