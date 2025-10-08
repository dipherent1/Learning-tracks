# SupportHub AI

[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](https://github.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

SupportHub AI is a modern, feature-rich, and `category` (Billing, Technical, etc.).

### 🚀 Modern Front End & Real-Time Experience
*   **Single-Page Application (SPA):** The entire front end is a fast, responsive SPA built with **Inertia.js** and **Vue.js**, providing a native-app feel with no full-page reloads.
*   **Real-Time Updates:** When a new reply is added to a ticket, it appears instantly on the screens of all other team members viewing the page, powered by **Laravel Echo & Broadcasting** via Pusher.

### 💼 Advanced Back-End Architecture
*   **Queues for Background Jobs:** The AI triage process is offloaded to a background queue using **Laravel Queues**, ensuring the user gets an instant response when creating a ticket.
*   **Professional Logging:** A multi-channel logging system that sends routine information to daily log files and critical errors directly to a **Slack channel** for immediate notification.
*   **Clean Code Patterns:** Extensive use of Laravel's best features, including **Form Requests** for validation, Eloquent **Model Relationships**, and **Service Providers**.

---

## 🛠️ Tech Stack

*   **Back End:**
    *   Laravel 11+
    *   PHP 8.2+
    *   PostgreSQL
    *   Pusher (for WebSockets)
    *   Laragent (for AI integration with Gemini)
*   **Front End:**
    *   Vue.js 3 (Composition API)
    *   Inertia.js
    *   Tailwind CSS
    *   Vite

---

## 🚀 Getting Started

Follow these instructions to get a local copy of the project up and running for development and testing purposes.

### Prerequisites

*   PHP >= 8.2
*   Composer
*   Node.js & NPM
*   PostgreSQL

### Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/support-hub-ai.git
    cd support-hub-ai
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Install JavaScript dependencies:**
    ```bash
    npm install
    ```

4.  **Create your environment file:**
    ```bash
    cp .env.example .env
    ```

5.  **Generate an application key:**
    ```bash
    php artisan key:generate
    ```

6.  **Configure your `.env` file:**
    Open the `.env` file and set up your database, Pusher, Gemini, and Slack credentials.

    ```env
    # --- DATABASE ---
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=support_hub_ai
    DB_USERNAME=your_postgres_user
    DB_PASSWORD=your_postgres_password

    # --- BROADCASTING (Pusher) ---
    BROADCAST_CONNECTION=pusher
    PUSHER_APP_ID=...
    PUSHER_APP_KEY=...
    PUSHER_APP_SECRET=...
    PUSHER_APP_CLUSTER=...
    VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
    VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

    # --- AI (Gemini) ---
    GEMINI_API_KEY=YOUR_GOOGLE_AI_API_KEY_HERE

    # --- LOGGING (Slack) ---
    LOG_SLACK_WEBHOOK_URL=YOUR_SLACK_WEBHOOK_URL_HERE
    ```

7.  **Set up the database:**
    Create a new PostgreSQL database named `support_hub_ai`.

8.  **Run migrations and seed the database:**
    This command will build your database structure and populate it with fake data, including an admin, agent, users, tickets, and replies.
    ```bash
    php artisan migrate:fresh --seed
    ```

### Running the Application

You need to run three separate processes in three different terminals.

1.  **Start the Laravel development server:**
    ```bash
    php artisan serve
    ```

2.  **Start the Vite front-end compiler:**
    ```bash
    npm run dev
    ```

3.  **Start the Queue Worker:**
    This is required for the AI ticket triage feature to work.
    ```bash
    php artisan queue:work
    ```

You can now access the application at `http://127.0.0.1:8000`.

*   **Admin Login:** `admin@example.com` / `password`
*   **Agent Login:** `agent@example.com` / `password`

---

## 🧪 Running Tests

The project is set up for automated testing with Pest.

1.  **Create a dedicated testing database:** Create a second PostgreSQL database (e.g., `support_hub_ai_ Triage:** New tickets are automatically analyzed by an AI in the background to set a `priority` (Low, Medium, High) and `category` (Billing, Technical, etc.).

### 🚀 Modern Front End & Real-Time Experience
*   **Single-Page Application (SPA):** The entire front end is a fast, responsive SPA built with **Inertia.js** and **Vue.js**, providing a native-app feel with no full-page reloads.
*   **Real-Time Updates:** When a new reply is added to a ticket, it appears instantly on the screens of all other team members viewing the page, powered by **Laravel Echo & Broadcasting** via Pusher.

### 💼 Advanced Back-End Architecture
*   **Queues for Background Jobs:** The AI triage process is offloaded to a background queue using **Laravel Queues**, ensuring the user gets an instant response when creating a ticket.
*   **Professional Logging:** A multi-channel logging system that sends routine information to daily log files and critical errors directly to a **Slack channel** for immediate notification.
*   **Clean Code Patterns:** Extensive use of Laravel's best features, including **Form Requests** for validation, Eloquent **Model Relationships**, and **Service Providers**.

---

## 🛠️ Tech Stack

*   **Back End:**
    *   Laravel 11+
    *   PHP 8.2+
    *   PostgreSQL
    *   Pusher (for WebSockets)
    *   Laragent (for AI integration with Gemini)
*   **Front End:**
    *   Vue.js 3 (Composition API)
    *   Inertia.js
    *   Tailwind CSS
    *   Vite

---

## 🚀 Getting Started

Follow these instructions to get a local copy of the project up and running for development and testing purposes.

### Prerequisites

*   PHP >= 8.2
*   Composer
*   Node.js & NPM
*   PostgreSQL

### Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/support-hub-ai.git
    cd support-hub-ai
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Install JavaScript dependencies:**
    ```bash
    npm install
    ```

4.  **Create your environment file:**
    ```bash
    cp .env.example .env
    ```

5.  **Generate an application key:**
    ```bash
    php artisan key:generate
    ```

6.  **Configure your `.env` file:**
    Open the `.env` file and set up your database, Pusher, Gemini, and Slack credentials.

    ```env
    # --- DATABASE ---
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=support_hub_ai
    DB_USERNAME=your_postgres_user
    DB_PASSWORD=your_postgres_password

    # --- BROADCASTING (Pusher) ---
    BROADCAST_CONNECTION=pusher
    PUSHER_APP_ID=...
    PUSHER_APP_KEY=...
    PUSHER_APP_SECRET=...
    PUSHER_APP_CLUSTER=...
    VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
    VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

    # --- AI (Gemini) ---
    GEMINI_API_KEY=YOUR_GOOGLE_AI_API_KEY_HERE

    # --- LOGGING (Slack) ---
    LOG_SLACK_WEBHOOK_URL=YOUR_SLACK_WEBHOOK_URL_HERE
    ```

7.  **Set up the database:**
    Create a new PostgreSQL database named `support_hub_ai`.

8.  **Run migrations and seed the database:**
    This command will build your database structure and populate it with fake data, including an admin, agent, users, tickets, and replies.
    ```bash
    php artisan migrate:fresh --seed
    ```

### Running the Application

You need to run three separate processes in three different terminals.

1.  **Start the Laravel development server:**
    ```bash
    php artisan serve
    ```

2.  **Start the Vite front-end compiler:**
    ```bash
    npm run dev
    ```

3.  **Start the Queue Worker:**
    This is required for the AI ticket triage feature to work.
    ```bash
    php artisan queue:work
    ```

You can now access the application at `http://127.0.0.1:8000`.

*   **Admin Login:** `admin@example.com` / `password`
*   **Agent Login:** `agent@example.com` / `password`