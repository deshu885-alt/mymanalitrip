/* ============================================================
   MyManaliTrip.com — Frontend JS
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

    // ── Navbar scroll effect ─────────────────────────────────────────────────
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        }, { passive: true });
    }

    // ── Mobile menu ──────────────────────────────────────────────────────────
    const hamburger   = document.getElementById('hamburger');
    const mobileMenu  = document.getElementById('mobileMenu');
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', () => {
            mobileMenu.classList.toggle('open');
            hamburger.classList.toggle('open');
        });
        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.remove('open');
                hamburger.classList.remove('open');
            }
        });
    }

    // ── Counter inputs (+/-) ─────────────────────────────────────────────────
    document.querySelectorAll('[data-counter]').forEach(wrap => {
        const dec   = wrap.querySelector('[data-dec]');
        const inc   = wrap.querySelector('[data-inc]');
        const val   = wrap.querySelector('[data-val]');
        const input = wrap.querySelector('input[type="hidden"]');
        const min   = parseInt(wrap.dataset.min ?? 0);
        const max   = parseInt(wrap.dataset.max ?? 20);

        let count = parseInt(input?.value ?? val?.textContent ?? min);

        const update = (n) => {
            count = Math.max(min, Math.min(max, n));
            if (val)   val.textContent = count;
            if (input) input.value     = count;
            if (dec)   dec.disabled    = count <= min;
            if (inc)   inc.disabled    = count >= max;
            // Trigger price recalculation
            updateTotalPrice();
        };

        dec?.addEventListener('click', () => update(count - 1));
        inc?.addEventListener('click', () => update(count + 1));
        update(count); // init state
    });

    // ── Package detail tabs ───────────────────────────────────────────────────
    document.querySelectorAll('.pkg-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.dataset.tab;
            document.querySelectorAll('.pkg-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.pkg-tab-panel').forEach(p => p.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById('tab-' + target)?.classList.add('active');
        });
    });

    // ── Booking price calculator ─────────────────────────────────────────────
    updateTotalPrice();

    document.querySelectorAll('[name="room_type"]').forEach(r => {
        r.addEventListener('change', updateTotalPrice);
    });
    document.querySelector('[name="travel_date"]')?.addEventListener('change', function () {
        updateTotalPrice();
        fetchSeasonalPrice(this.value);
    });

    // ── Payment option toggle ────────────────────────────────────────────────
    document.querySelectorAll('.pay-option').forEach(opt => {
        opt.querySelector('input')?.addEventListener('change', function () {
            document.querySelectorAll('.pay-option').forEach(o => o.classList.remove('selected'));
            opt.classList.add('selected');
            updateTotalPrice();
        });
        // Click on card itself selects radio
        opt.addEventListener('click', function () {
            const radio = opt.querySelector('input[type="radio"]');
            if (radio) { radio.checked = true; radio.dispatchEvent(new Event('change')); }
        });
    });

    // Mark first payment option selected
    const firstPayOpt = document.querySelector('.pay-option');
    if (firstPayOpt) firstPayOpt.classList.add('selected');

    // ── Smooth scroll for anchor links ───────────────────────────────────────
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ── Gallery lightbox (simple) ────────────────────────────────────────────
    document.querySelectorAll('[data-lightbox]').forEach(img => {
        img.style.cursor = 'pointer';
        img.addEventListener('click', () => openLightbox(img.src, img.alt));
    });

    // ── Animate sections on scroll ────────────────────────────────────────────
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08 });

    document.querySelectorAll('.pkg-card, .blog-card, .why-card, .review-card, .how-step').forEach(el => {
        el.classList.add('animate-on-scroll');
        observer.observe(el);
    });
});

// ── Price calculator ──────────────────────────────────────────────────────────
function updateTotalPrice() {
    const basePrice = parseFloat(document.getElementById('basePrice')?.dataset.price ?? 0);
    if (!basePrice) return;

    const adults   = parseInt(document.querySelector('[data-counter] input[name="adults"]')?.value   ?? document.querySelector('[data-val][data-for="adults"]')?.textContent  ?? 1);
    const children = parseInt(document.querySelector('[data-counter] input[name="children"]')?.value ?? document.querySelector('[data-val][data-for="children"]')?.textContent ?? 0);
    const childPrice = parseFloat(document.getElementById('basePrice')?.dataset.childPrice ?? basePrice * 0.6);

    const roomType = document.querySelector('[name="room_type"]:checked')?.value ?? 'Standard';
    const roomSurcharge = { Standard: 0, Deluxe: 500, Luxury: 1500 };
    const surcharge = roomSurcharge[roomType] ?? 0;

    const total = (adults * (basePrice + surcharge)) + (children * (childPrice + surcharge));

    // Advance (30%)
    const advance  = Math.round(total * 0.30);
    const balance  = total - advance;
    const discount = Math.round(total * 0.05); // 5% full payment discount
    const fullDiscounted = total - discount;

    // Update display elements
    setText('totalPrice',    '₹' + formatINR(total));
    setText('advanceAmount', '₹' + formatINR(advance));
    setText('balanceAmount', '₹' + formatINR(balance));
    setText('fullAmount',    '₹' + formatINR(fullDiscounted));
    setText('fullSaving',    'Save ₹' + formatINR(discount));

    // Update hidden inputs
    setVal('input[name="total_amount"]',   total);
    setVal('input[name="advance_paid"]',   advance);
    setVal('input[name="balance_due"]',    balance);

    // Highlight selected payment option amount
    const payType = document.querySelector('[name="payment_type"]:checked')?.value ?? 'partial';
    const payNow  = payType === 'full' ? fullDiscounted : advance;
    setText('payNowAmount', '₹' + formatINR(payNow));
}

function fetchSeasonalPrice(dateStr) {
    if (!dateStr) return;
    const packageId = document.getElementById('basePrice')?.dataset.packageId;
    if (!packageId) return;

    const month = new Date(dateStr).getMonth() + 1;

    fetch(`/api/package-price?package_id=${packageId}&month=${month}`)
        .then(r => r.json())
        .then(data => {
            if (data.price) {
                document.getElementById('basePrice').dataset.price = data.price;
                updateTotalPrice();
                showPriceNote(data.note ?? '');
            }
        })
        .catch(() => {});
}

function showPriceNote(note) {
    const el = document.getElementById('priceNote');
    if (el && note) { el.textContent = note; el.style.display = 'block'; }
}

// ── Razorpay payment ──────────────────────────────────────────────────────────
function initiatePayment() {
    const form     = document.getElementById('bookingForm');
    const btn      = document.getElementById('payBtn');
    const payType  = document.querySelector('[name="payment_type"]:checked')?.value ?? 'partial';
    const total    = parseFloat(document.querySelector('[name="total_amount"]')?.value ?? 0);
    const advance  = parseFloat(document.querySelector('[name="advance_paid"]')?.value ?? 0);
    const amount   = payType === 'full' ? total * 0.95 : advance;

    if (!amount) { alert('Something went wrong. Please refresh and try again.'); return; }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    fetch('/booking/create-order', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body:    JSON.stringify({ amount, currency: 'INR', payment_type: payType, ...collectFormData() }),
    })
    .then(r => r.json())
    .then(data => {
        if (!data.order_id) throw new Error(data.message ?? 'Order creation failed');

        const options = {
            key:         data.key,
            amount:      data.amount,
            currency:    'INR',
            name:        'MyManaliTrip',
            description: data.package_name,
            order_id:    data.order_id,
            prefill: {
                name:    document.querySelector('[name="full_name"]')?.value,
                email:   document.querySelector('[name="email"]')?.value,
                contact: document.querySelector('[name="phone"]')?.value,
            },
            theme: { color: '#f5a623' },
            handler: function (response) {
                // Verify on server
                fetch('/booking/verify-payment', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body:    JSON.stringify({ ...response, booking_data: collectFormData(), payment_type: payType }),
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        window.location.href = '/booking/confirmation/' + res.booking_ref;
                    } else {
                        alert('Payment verified but booking creation failed. Please contact support.');
                    }
                });
            },
            modal: {
                ondismiss: () => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-lock"></i> Pay Securely';
                }
            }
        };

        const rzp = new Razorpay(options);
        rzp.open();
    })
    .catch(err => {
        console.error(err);
        alert('Failed to initiate payment. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-lock"></i> Pay Securely';
    });
}

function collectFormData() {
    const form = document.getElementById('bookingForm');
    if (!form) return {};
    const data = {};
    new FormData(form).forEach((val, key) => data[key] = val);
    return data;
}

// ── Lightbox ──────────────────────────────────────────────────────────────────
function openLightbox(src, alt) {
    const lb = document.createElement('div');
    lb.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:9999;display:flex;align-items:center;justify-content:center;cursor:pointer;';
    lb.innerHTML = `<img src="${src}" alt="${alt}" style="max-width:90vw;max-height:90vh;border-radius:12px;box-shadow:0 24px 80px rgba(0,0,0,.6)">`;
    lb.addEventListener('click', () => lb.remove());
    document.body.appendChild(lb);
}

// ── Helpers ───────────────────────────────────────────────────────────────────
function formatINR(n) { return Math.round(n).toLocaleString('en-IN'); }
function setText(id, text) { const el = document.getElementById(id); if (el) el.textContent = text; }
function setVal(sel, val) { const el = document.querySelector(sel); if (el) el.value = val; }

// ── Scroll animation CSS injection ───────────────────────────────────────────
const style = document.createElement('style');
style.textContent = `
.animate-on-scroll { opacity: 0; transform: translateY(20px); transition: opacity .5s ease, transform .5s ease; }
.animate-on-scroll.visible { opacity: 1; transform: translateY(0); }
`;
document.head.appendChild(style);
