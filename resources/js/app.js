import './bootstrap';
import { BrowserProvider } from 'ethers';

const tokenKey = 'paralland_token';

function qs(id) {
    return document.getElementById(id);
}

function setStatus(text) {
    const el = qs('statusText');
    if (el) el.textContent = text;
}

function setWallet(text) {
    const el = qs('walletText');
    if (el) el.textContent = text ? `Wallet: ${text}` : '';
}

function setRole(text) {
    const el = qs('roleText');
    if (el) el.textContent = text ? `Role: ${text}` : '';
}

function getToken() {
    return localStorage.getItem(tokenKey);
}

function setToken(token) {
    if (token) localStorage.setItem(tokenKey, token);
    else localStorage.removeItem(tokenKey);
}

async function apiFetch(path, options = {}) {
    const headers = new Headers(options.headers || {});
    headers.set('Accept', 'application/json');
    headers.set('Content-Type', 'application/json');

    const token = getToken();
    if (token) {
        headers.set('Authorization', `Bearer ${token}`);
    }

    const res = await fetch(path, {
        ...options,
        headers,
    });

    const text = await res.text();
    const json = text ? JSON.parse(text) : null;

    if (!res.ok) {
        const message = json?.message || `Request failed (${res.status})`;
        throw new Error(message);
    }

    return json;
}

function renderProposals(container, proposals) {
    container.innerHTML = '';
    if (!proposals.length) {
        container.innerHTML = '<div class="text-zinc-400">No proposals yet.</div>';
        return;
    }

    for (const p of proposals) {
        const div = document.createElement('div');
        div.className = 'rounded-md border border-zinc-800 bg-zinc-950 p-3';
        div.innerHTML = `
            <div class="flex items-center justify-between gap-3">
              <div class="font-medium">${escapeHtml(p.title || '(no title)')}</div>
              <div class="text-xs text-zinc-400">${escapeHtml(p.status)}</div>
            </div>
            <div class="mt-2 text-xs text-zinc-500">Price: ${p.development_price_rbe} RBE</div>
            <div class="mt-2 text-sm text-zinc-200 whitespace-pre-wrap">${escapeHtml(p.description || '')}</div>
        `;
        container.appendChild(div);
    }
}

function escapeHtml(s) {
    return String(s)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

async function loadMyProposals() {
    const el = qs('proposalsList');
    if (!el) return;
    const proposals = await apiFetch('/api/proposals');
    renderProposals(el, proposals);
}

async function loadAdminProposals() {
    const panel = qs('adminPanel');
    const list = qs('adminProposalsList');
    if (!panel || !list) return;

    const proposals = await apiFetch('/api/admin/proposals');
    list.innerHTML = '';

    if (!proposals.length) {
        list.innerHTML = '<div class="text-zinc-400">No pending proposals.</div>';
        return;
    }

    for (const p of proposals) {
        const div = document.createElement('div');
        div.className = 'rounded-md border border-zinc-800 bg-zinc-950 p-3';
        div.innerHTML = `
            <div class="flex items-center justify-between gap-3">
              <div>
                <div class="font-medium">${escapeHtml(p.title || '(no title)')}</div>
                <div class="mt-1 text-xs text-zinc-500">From: ${escapeHtml(p.user?.wallet_address || 'unknown')}</div>
              </div>
              <div class="text-xs text-zinc-400">${escapeHtml(p.status)}</div>
            </div>
            <div class="mt-2 text-xs text-zinc-500">Price: ${p.development_price_rbe} RBE</div>
            <div class="mt-2 text-sm text-zinc-200 whitespace-pre-wrap">${escapeHtml(p.description || '')}</div>
            <div class="mt-3 flex flex-wrap gap-2">
              <button data-action="accept" class="rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-medium hover:bg-emerald-500">Accept</button>
              <button data-action="revision" class="rounded-md bg-amber-600 px-3 py-1.5 text-xs font-medium hover:bg-amber-500">Request revision</button>
              <button data-action="reject" class="rounded-md bg-rose-600 px-3 py-1.5 text-xs font-medium hover:bg-rose-500">Reject</button>
            </div>
        `;

        div.querySelectorAll('button[data-action]').forEach((btn) => {
            btn.addEventListener('click', async () => {
                const action = btn.getAttribute('data-action');
                const note = prompt(`Admin note for "${action}" (optional):`) || '';
                setStatus(`Admin: ${action}...`);
                try {
                    await apiFetch(`/api/admin/proposals/${p.id}/review`, {
                        method: 'POST',
                        body: JSON.stringify({ action, note }),
                    });
                    await loadAdminProposals();
                    setStatus('Admin action completed.');
                } catch (e) {
                    setStatus(e.message);
                }
            });
        });

        list.appendChild(div);
    }
}

async function refreshMe() {
    const connectBtn = qs('connectBtn');
    const logoutBtn = qs('logoutBtn');
    const adminPanel = qs('adminPanel');

    const token = getToken();
    if (!token) {
        if (connectBtn) connectBtn.classList.remove('hidden');
        if (logoutBtn) logoutBtn.classList.add('hidden');
        if (adminPanel) adminPanel.classList.add('hidden');
        setStatus('Not connected');
        setWallet('');
        setRole('');
        return null;
    }

    try {
        const me = await apiFetch('/api/me');
        if (connectBtn) connectBtn.classList.add('hidden');
        if (logoutBtn) logoutBtn.classList.remove('hidden');
        setStatus('Connected');
        setWallet(me.wallet_address);
        setRole(me.role);

        if (adminPanel) {
            if (me.role === 'admin') adminPanel.classList.remove('hidden');
            else adminPanel.classList.add('hidden');
        }

        return me;
    } catch (e) {
        setToken(null);
        setStatus('Session expired. Please connect again.');
        return null;
    }
}

async function connectWalletAndLogin() {
    if (!window.ethereum) {
        setStatus('No wallet found. Install MetaMask / WalletConnect compatible wallet.');
        return;
    }

    setStatus('Connecting wallet...');
    const provider = new BrowserProvider(window.ethereum);
    const accounts = await provider.send('eth_requestAccounts', []);
    const wallet = String(accounts?.[0] || '').toLowerCase();
    if (!wallet) {
        setStatus('No account selected.');
        return;
    }

    setStatus('Requesting nonce...');
    const nonceResp = await apiFetch(`/api/auth/nonce?wallet=${encodeURIComponent(wallet)}`, { method: 'GET' });

    setStatus('Signing message...');
    const signer = await provider.getSigner();
    const signature = await signer.signMessage(nonceResp.message);

    setStatus('Verifying signature...');
    const verifyResp = await apiFetch('/api/auth/verify', {
        method: 'POST',
        body: JSON.stringify({
            wallet,
            nonce: nonceResp.nonce,
            signature,
        }),
    });

    setToken(verifyResp.token);
    await refreshMe();
    await loadMyProposals();
    if (verifyResp.user?.role === 'admin') {
        await loadAdminProposals();
    }
}

async function logout() {
    try {
        await apiFetch('/api/auth/logout', { method: 'POST', body: JSON.stringify({}) });
    } catch {
        // ignore
    }
    setToken(null);
    await refreshMe();
}

function parseRequirements(raw) {
    const trimmed = (raw || '').trim();
    if (!trimmed) return null;
    try {
        return JSON.parse(trimmed);
    } catch {
        return { notes: trimmed };
    }
}

async function setup() {
    const connectBtn = qs('connectBtn');
    const logoutBtn = qs('logoutBtn');
    const refreshBtn = qs('refreshBtn');
    const adminRefreshBtn = qs('adminRefreshBtn');
    const form = qs('proposalForm');

    if (connectBtn) connectBtn.addEventListener('click', () => connectWalletAndLogin().catch((e) => setStatus(e.message)));
    if (logoutBtn) logoutBtn.addEventListener('click', () => logout().catch((e) => setStatus(e.message)));
    if (refreshBtn) refreshBtn.addEventListener('click', () => loadMyProposals().catch((e) => setStatus(e.message)));
    if (adminRefreshBtn) adminRefreshBtn.addEventListener('click', () => loadAdminProposals().catch((e) => setStatus(e.message)));

    if (form) {
        form.addEventListener('submit', async (ev) => {
            ev.preventDefault();
            setStatus('Submitting proposal...');
            try {
                const title = qs('proposalTitle')?.value || '';
                const description = qs('proposalDescription')?.value || '';
                const price = Number(qs('proposalPrice')?.value || 2);
                const requirements = parseRequirements(qs('proposalRequirements')?.value || '');

                await apiFetch('/api/proposals', {
                    method: 'POST',
                    body: JSON.stringify({
                        title,
                        description,
                        development_price_rbe: price,
                        requirements,
                    }),
                });

                setStatus('Proposal submitted.');
                await loadMyProposals();
            } catch (e) {
                setStatus(e.message);
            }
        });
    }

    const me = await refreshMe();
    if (me) {
        await loadMyProposals();
        if (me.role === 'admin') {
            await loadAdminProposals();
        }
    }
}

setup().catch((e) => setStatus(e.message));
