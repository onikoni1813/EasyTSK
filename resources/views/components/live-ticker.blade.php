<div class="ticker-wrap w-full" id="ticker-wrap">
    <span class="ticker-tag">🔴 LIVE</span>
    <div class="ticker-track-container">
        <div class="ticker-track" id="ticker-track" style="--pulse-duration: {{ \App\Models\Setting::get('ticker_speed', '15s') }}">
            <span class="ticker-item">লোড হচ্ছে…</span>
        </div>
    </div>
</div>

<style>
    .ticker-wrap {
        background: rgba(0, 200, 83, .08);
        border-bottom: 1px solid var(--border);
        padding: .75rem 1.5rem;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .ticker-tag {
        flex-shrink: 0;
        background: var(--green);
        color: #000;
        font-size: .7rem;
        font-weight: 900;
        padding: .25rem .75rem;
        border-radius: 6px;
        position: relative;
        z-index: 10;
        box-shadow: 8px 0 20px rgba(10, 15, 30, 0.8);
    }

    .ticker-track-container {
        flex: 1;
        overflow: hidden;
        white-space: nowrap;
        position: relative;
    }

    .ticker-track-container::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 40px;
        background: linear-gradient(to right, var(--dark), transparent);
        z-index: 5;
        pointer-events: none;
    }

    .ticker-track {
        display: inline-block;
        padding-left: 100%;
        animation: tickerScroll var(--pulse-duration, 15s) linear infinite;
    }

    .ticker-item {
        display: inline-block;
        margin-right: 5rem;
        font-size: .85rem;
        color: var(--text);
        font-weight: 500;
    }

    .ticker-item span {
        color: var(--green2);
        font-weight: 800;
    }

    @keyframes tickerScroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-100%);
        }
    }
</style>

<script>
    function loadTicker() {
        const track = document.getElementById('ticker-track');
        if (!track) return;

        fetch('/api/payment-proof')
            .then(r => r.json())
            .then(data => {
                if (!data || !data.length) {
                    track.innerHTML = `<span class="ticker-item">আমাদের সাথে যুক্ত হয়ে আজই আয় শুরু করুন! 🚀</span>`;
                    return;
                }
                const items = data.map(p => {
                    if (p.type === 'custom') {
                        return `<span class="ticker-item font-black text-primary-500 underline decoration-primary-500/30 underline-offset-4">${p.content}</span>`;
                    }
                    return `<span class="ticker-item">${p.name} <span>${p.amount}</span> পেয়েছেন (${p.method}) — ${p.time}</span>`;
                });
                track.innerHTML = items.join('');
            })
            .catch(() => {
                track.innerHTML = `<span class="ticker-item">১০০% বিশ্বস্ততার সাথে পেমেন্ট করা হয়। আজই যোগ দিন!</span>`;
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadTicker();
        setInterval(loadTicker, 60000);
    });
</script>