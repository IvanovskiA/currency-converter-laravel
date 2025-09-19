# Currency Converter API (Laravel 11 + Sail + PostgreSQL)

REST API for currency conversion via [Fixer.io](https://fixer.io), with persistence in a PostgreSQL database and automated tests.

## 🚀 Tech Stack

-   [Laravel 11](https://laravel.com) + Sail (Docker)
-   PostgreSQL
-   Fixer.io API
-   PHPUnit Feature Tests
-   Database Seeders for demo data

---

## ⚙️ Setup & Run

### 1. Clone the project

```bash
git clone https://github.com/<your-account>/currency-converter-laravel.git
cd currency-converter-laravel
```

### 2. Start Docker

```bash
./vendor/bin/sail up -d
```

### 3. Environment configuration

Copy `.env.example` to `.env` and add your Fixer API Key:

```bash
cp .env.example .env
```

In `.env`:

```env
FIXER_API_KEY=your_fixer_key_here
FIXER_BASE_URL=https://data.fixer.io/api
FIXER_BASE_CURRENCY=EUR
```

### Database port

By default, PostgreSQL is exposed on port 5432.  
If you already have a local PostgreSQL running, you can override this by setting:

````env
FORWARD_DB_PORT=5433

### 4. Generate app key & run migrations

```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
````

---

## 🌱 Seeder (demo data)

If you want sample data in the database right away:

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

This will insert 10 demo conversions.

---

## 📡 Endpoints

### `POST /api/convert`

Converts an amount from one currency to another and stores the result in the database.  
**Headers:**  
Key: `Content-Type`  
Value: `application/json`

**Body (JSON):**

```json
{ "from": "EUR", "to": "USD", "amount": 100 }
```

**Response:**

```json
{
    "data": {
        "id": 1,
        "from": "EUR",
        "to": "USD",
        "amount": 100,
        "rate": 1.085431,
        "result": 108.54,
        "created": "2025-01-01T12:34:56.000000Z"
    }
}
```

---

### `GET /api/conversions?limit=20`

Returns the latest `limit` conversions (default 20, max 100).

**Response:**

```json
{
    "data": [
        {
            "id": 1,
            "from": "EUR",
            "to": "USD",
            "amount": 100,
            "rate": 1.085431,
            "result": 108.54,
            "created": "2025-01-01T12:34:56.000000Z"
        },
        {
            "id": 2,
            "from": "USD",
            "to": "MKD",
            "amount": 50,
            "rate": 61.5,
            "result": 3075,
            "created": "2025-01-01T12:40:00.000000Z"
        }
    ]
}
```

---

## 🛡 Error Handling

All API routes return a unified JSON error format.

**Example: Validation error**

```json
{
    "errors": {
        "amount": ["The amount field must be at least 0.01."]
    }
}
```

**Example: Server error**

```json
{
    "error": {
        "message": "Server error"
    }
}
```

---

## ⏳ Rate Limiting

-   `POST /api/convert` → 60 requests/minute
-   `GET /api/conversions` → 30 requests/minute

---

## 🧪 Tests

The project includes PHPUnit Feature tests for:

-   Successful conversion and persistence
-   Input validation
-   Listing recent conversions

### Test database

Create a separate database for testing:

```bash
./vendor/bin/sail exec pgsql psql -U sail -c "CREATE DATABASE currency_converter_test;"
```

In `.env.testing`:

```env
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
FORWARD_DB_PORT=5433
DB_DATABASE=currency_converter_test
DB_USERNAME=sail
DB_PASSWORD=password
```

### Run tests

```bash
./vendor/bin/sail test
```

---

## 👨‍💻 Author

Angel Ivanovski – [GitHub profile](https://github.com/IvanovskiA/)

---

✨ With a single step — `./vendor/bin/sail up -d` — your API is ready.
