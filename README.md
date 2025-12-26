## Paralland (RBE) — Laravel DApp MVP

This repository is the **backend + web UI** for Paralland:

- **Wallet login** (sign message / `personal_sign`)
- **Proposal workflow**: user submits proposal → admin accepts / requests revision / rejects
- **Project record** created automatically when a proposal is accepted (foundation for the 24-hour delivery + chat + maintenance billing flows)

Smart contracts (RBE token + minting + escrow/maintenance payments) are intended to be developed in **Remix** and integrated later.

### Tech

- **Laravel 12**
- **MySQL** (recommended for production; `.env.example` is set to MySQL)
- **Sanctum** for API tokens
- **ethers.js** for wallet connect + message signing

### Quick start (local)

1) Install dependencies:

```bash
composer install
npm install
```

2) Create `.env`:

```bash
cp .env.example .env
php artisan key:generate
```

3) Configure DB in `.env` (MySQL), then migrate:

```bash
php artisan migrate
```

4) Build assets:

```bash
npm run build
```

5) Run the app:

```bash
php artisan serve
```

Open the UI at `http://localhost:8000/app`.

### Admin setup

Admin routes are protected by an `admin` middleware. To make a wallet address an admin:

```bash
php artisan paralland:make-admin 0xYourWalletAddressHere
```

### API (MVP)

- **Wallet login**
  - `GET /api/auth/nonce?wallet=0x...`
  - `POST /api/auth/verify` `{ wallet, nonce, signature }` → returns `token`
  - `GET /api/me` (requires `Authorization: Bearer <token>`)

- **Proposals**
  - `GET /api/proposals`
  - `POST /api/proposals`
  - `GET /api/proposals/{proposal}`

- **Admin**
  - `GET /api/admin/proposals`
  - `POST /api/admin/proposals/{proposal}/review` `{ action: accept|revision|reject, note? }`

