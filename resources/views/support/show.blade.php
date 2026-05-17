<x-user-layout>
    <div class="mb-10 flex flex-col md:flex-row md:items-start md:items-center justify-between gap-6">
        <div>
            <h2 class="text-2xl md:text-3xl font-black text-white tracking-tight leading-tight uppercase">টিকেট: <span
                    class="text-primary-500">#{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span></h2>
            <p class="text-sm text-slate-400 font-medium mt-1">{{ $ticket->subject }}</p>
        </div>
        <div class="flex items-center flex-wrap gap-3">
            <span
                class="px-4 py-2 text-[9px] md:text-[10px] font-black uppercase tracking-widest rounded-xl {{ $ticket->status === 'open' ? 'bg-primary-500/10 text-primary-500 border border-primary-500/20' : 'bg-white/5 text-slate-500 border border-white/5' }}">
                Status: {{ $ticket->status }}
            </span>
            <span
                class="px-4 py-2 text-[9px] md:text-[10px] font-black uppercase tracking-widest rounded-xl bg-slate-900 text-white border border-white/10 shadow-lg">
                Priority: {{ $ticket->priority }}
            </span>
        </div>
    </div>

    {{-- Data Bridge for IDE-friendly JS --}}
    <div id="ticket-context" data-ticket-id="{{ $ticket->id }}" data-status="{{ $ticket->status }}"
        data-initial-messages='{!! json_encode($ticket->messages->map(function ($msg) {
    return [
        ' id' => $msg->id,
        'message' => $msg->message,
        'is_admin_reply' => (bool) $msg->is_admin_reply,
        'sender_name' => $msg->is_admin_reply ? 'Support Team' : (optional($msg->user)->full_name ?? optional($msg->user)->name ?? 'User'),
        'sender_initial' => $msg->is_admin_reply ? 'AD' : substr(optional($msg->user)->full_name ?? optional($msg->user)->name ?? 'U', 0, 1),
        'created_at' => $msg->created_at->format('d M, Y - h:i A'),
    ];
})) !!}' class="hidden"></div>

    <div x-data="ticketSystem()" x-init="initPolling()">
        <div class="space-y-6 mb-12" id="message-container">
            <template x-for="msg in messages" :key="msg.id">
                <div class="flex" :class="msg.is_admin_reply ? 'justify-start' : 'justify-end'">
                    <div class="max-w-[85%] md:max-w-[70%] group">
                        <div class="flex items-center gap-3 mb-2 px-4"
                            :class="msg.is_admin_reply ? 'flex-row' : 'flex-row-reverse text-right'">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center text-[10px] font-black uppercase shadow-sm"
                                :class="msg.is_admin_reply ? 'bg-primary-600 text-white' : 'bg-white/10 text-slate-300'"
                                x-text="msg.sender_initial">
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-white block uppercase tracking-tight"
                                    x-text="msg.sender_name"></span>
                                <span class="text-[9px] font-bold text-slate-500 block" x-text="msg.created_at"></span>
                            </div>
                        </div>

                        <div class="p-4 md:p-6 rounded-[24px] md:rounded-[32px] shadow-sm relative"
                            :class="msg.is_admin_reply ? 'bg-dark-card border border-white/5 text-white rounded-tl-none' : 'bg-primary-600 text-white rounded-tr-none shadow-primary-900/40'">
                            <p class="text-[13px] md:text-sm font-medium leading-relaxed whitespace-pre-wrap"
                                x-text="msg.message"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <template x-if="status !== 'closed'">
            <div
                class="p-6 md:p-10 bg-dark-card border border-white/5 rounded-[32px] md:rounded-[48px] shadow-sm max-w-4xl">
                <h3 class="text-lg font-black text-white mb-8 flex items-center gap-2">
                    প্রতিউত্তর দিন (Reply)
                    <span class="w-1.5 h-1.5 bg-primary-500 rounded-full"
                        :class="isSending ? 'animate-ping' : ''"></span>
                </h3>
                <form @submit.prevent="sendReply" class="space-y-6">
                    <div>
                        <textarea x-model="newMessage" rows="5" placeholder="আপনার কথা বিস্তারিত লিখুন..."
                            class="block w-full px-6 py-5 bg-white/5 border border-white/5 rounded-[32px] font-black text-white focus:bg-white/10 focus:ring-primary-500 focus:border-primary-500 transition-all placeholder:text-slate-600"
                            required></textarea>
                    </div>
                    <div class="flex items-center gap-4">
                        <button type="submit" :disabled="isSending"
                            class="flex-1 px-8 py-5 bg-white text-dark-card rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary-500 hover:text-white transition-all shadow-xl shadow-black/20 disabled:opacity-50">
                            <span x-show="!isSending">মেসেজ পাঠান (Send Reply)</span>
                            <span x-show="isSending" x-cloak>পাঠানো হচ্ছে...</span>
                        </button>
                        <a href="{{ route('support.index') }}"
                            class="px-8 py-5 bg-white/5 text-slate-400 border border-white/5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-white/10 transition-all">
                            ফিরে যান
                        </a>
                    </div>
                </form>
            </div>
        </template>

        <template x-if="status === 'closed'">
            <div class="p-8 md:p-10 bg-white/5 border border-white/5 rounded-[32px] md:rounded-[40px] text-center">
                <div
                    class="w-20 h-20 bg-dark-card rounded-[24px] flex items-center justify-center text-slate-600 mx-auto mb-6 shadow-sm border border-white/5">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <p class="text-lg font-black text-white uppercase tracking-tight">এই টিকেটটি বন্ধ করা হয়েছে (Closed)</p>
                <p class="text-sm text-slate-500 mt-2 font-medium">নতুন কোনো সমস্যা থাকলে দয়া করে নতুন একটি টিকেট খুলুন।
                </p>
                <a href="{{ route('support.create') }}"
                    class="inline-flex mt-8 px-10 py-5 bg-white text-dark-card rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary-500 hover:text-white transition-all shadow-xl shadow-black/20">
                    নতুন টিকেট খুলুন
                </a>
            </div>
        </template>
    </div>

    @push('scripts')
        <script>
            function ticketSystem() {
                const context = document.getElementById('ticket-context');

                return {
                    messages: JSON.parse(context.dataset.initialMessages),
                    status: context.dataset.status,
                    ticketId: context.dataset.ticketId,
                    newMessage: '',
                    isSending: false,

                    initPolling() {
                        setInterval(() => {
                            this.fetchMessages();
                        }, 2000); // Poll every 2 seconds for a snappier feel
                    },

                    async fetchMessages() {
                        try {
                            const res = await fetch(`/api/support/${this.ticketId}/messages`);
                            const data = await res.json();
                            if (data.messages) {
                                this.messages = data.messages;
                                this.status = data.status;
                            }
                        } catch (e) {
                            console.error('Polling failed', e);
                        }
                    },

                    async sendReply() {
                        if (!this.newMessage.trim()) return;
                        this.isSending = true;

                        try {
                            const res = await fetch(`/support/${this.ticketId}/reply`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    message: this.newMessage
                                })
                            });

                            if (res.ok) {
                                this.newMessage = '';
                                await this.fetchMessages();
                            }
                        } catch (e) {
                            console.error('Failed to send reply', e);
                        } finally {
                            this.isSending = false;
                        }
                    }
                }
            }
        </script>
    @endpush
</x-user-layout>