<x-user-layout>
    <div class="mb-10">
        <h2 class="text-3xl font-black text-white tracking-tight leading-tight uppercase">আইডি <span class="text-primary-500">ভেরিফিকেশন</span></h2>
        <p class="text-sm text-slate-400 font-medium mt-1">পেমেন্ট উইথড্র করার জন্য আপনার পরিচয় যাচাই করা বাধ্যতামূলক।</p>
    </div>

    @if($user->kyc_status === 'approved')
    <div class="p-8 mb-10 bg-emerald-500/10 border border-emerald-500/20 rounded-[32px] flex items-center gap-6 shadow-sm">
        <div class="w-16 h-16 bg-emerald-500 text-white rounded-2xl flex items-center justify-center shadow-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-black text-emerald-500 uppercase tracking-tight">অভিনন্দন! আপনার আইডি ভেরিফাইড</h3>
            <p class="text-[10px] font-bold text-emerald-500/60 mt-1 uppercase tracking-widest">You are now a verified member of our platform.</p>
        </div>
    </div>
    @elseif($user->kyc_status === 'pending')
    <div class="p-8 mb-10 bg-amber-500/10 border border-amber-500/20 rounded-[32px] flex items-center gap-6 shadow-sm">
        <div class="w-16 h-16 bg-amber-500 text-white rounded-2xl flex items-center justify-center shadow-lg animate-pulse">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-black text-amber-500 uppercase tracking-tight">আপনার তথ্য যাচাই করা হচ্ছে...</h3>
            <p class="text-[10px] font-bold text-amber-500/60 mt-1 uppercase tracking-widest">Please wait while our team reviews your documents (24-48h).</p>
        </div>
    </div>
    @elseif($user->kyc_status === 'rejected')
    <div class="p-8 mb-10 bg-rose-500/10 border border-rose-500/20 rounded-[32px] flex items-center gap-6 shadow-sm">
        <div class="w-16 h-16 bg-rose-500 text-white rounded-2xl flex items-center justify-center shadow-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-black text-rose-500 uppercase tracking-tight">আবেদনটি বাতিল করা হয়েছে</h3>
            <p class="text-[10px] font-bold text-rose-500/60 mt-1 uppercase tracking-widest">Reason: {{ $user->kyc_notes }}</p>
        </div>
    </div>
    @endif

    @if($user->kyc_status !== 'approved' && $user->kyc_status !== 'pending')
    <div class="p-10 bg-dark-card border border-white/5 rounded-[48px] shadow-sm max-w-4xl">
        <form action="{{ route('kyc.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-10">
                <label for="id_number" class="block mb-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">ন্যাশনাল আইডি বা জন্ম নিবন্ধন নম্বর</label>
                <input type="text" name="id_number" id="id_number" placeholder="Enter NID/Birth Reg Number"
                    class="block w-full px-6 py-5 bg-white/5 border border-white/5 rounded-2xl font-black text-white focus:bg-white/10 focus:ring-primary-500 focus:border-primary-500 transition-all placeholder:text-slate-600" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div class="space-y-3">
                    <label for="id_front" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">আইডির সামনের দিকের ছবি</label>
                    <div class="relative group">
                        <label class="flex flex-col items-center justify-center w-full h-48 border border-white/10 border-dashed rounded-[32px] cursor-pointer bg-white/5 hover:bg-white/10 hover:border-primary-500 transition-all relative overflow-hidden">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-slate-500 group-hover:text-primary-500 group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                </svg>
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">FRONT VIEW (UPLOAD)</p>
                            </div>
                            <input name="id_front" type="file" id="id_front" class="hidden" required onchange="previewKyc(this, 'preview-front')" />
                            <div id="preview-front" class="hidden absolute inset-0 rounded-[32px] overflow-hidden bg-dark-card border border-primary-500">
                                <img class="w-full h-full object-cover">
                            </div>
                        </label>
                    </div>
                </div>

                <div class="space-y-3">
                    <label for="id_back" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">আইডির পিছনের দিকের ছবি</label>
                    <div class="relative group">
                        <label class="flex flex-col items-center justify-center w-full h-48 border border-white/10 border-dashed rounded-[32px] cursor-pointer bg-white/5 hover:bg-white/10 hover:border-primary-500 transition-all relative overflow-hidden">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-slate-500 group-hover:text-primary-500 group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                </svg>
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">BACK VIEW (UPLOAD)</p>
                            </div>
                            <input name="id_back" type="file" id="id_back" class="hidden" required onchange="previewKyc(this, 'preview-back')" />
                            <div id="preview-back" class="hidden absolute inset-0 rounded-[32px] overflow-hidden bg-dark-card border border-primary-500">
                                <img class="w-full h-full object-cover">
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="flex items-center justify-center w-full py-6 bg-white text-dark-card border-0 rounded-[32px] text-sm font-black uppercase tracking-widest hover:bg-primary-500 hover:text-white transition-all shadow-xl shadow-black/20">
                তথ্য জমা দিন (SUBMIT VERIFICATION)
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </button>
        </form>
    </div>
    @endif

    @push('scripts')
    <script>
        function previewKyc(input, previewId) {
            const preview = document.getElementById(previewId);
            const img = preview.querySelector('img');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush
</x-user-layout>