
# Scammer-Alert-DZ

**Scammer-Alert-DZ** is an anti-scam reporting platform for Algeria ($\mathbf{DZ}$). Our mission is to empower the community to report and verify suspicious contacts, ultimately protecting citizens from online and offline fraud.

It is built as a **Full-Stack Single Page Application (SPA)** using Laravel and React via Inertia.js.

-----

## 🚀 Key Features

  * **Fraud Reporting:** Simple, secure system for users to report scammers, providing evidence and details.
  * **Verification Tool:** Allows users to check if a phone number, account, or contact has been flagged as fraudulent.
  * **Community Moderation:** A workflow for verifying, investigating, and resolving reported scam cases.
  * **Internationalization (i18n):** Full support for **Arabic ($\text{ar}$) and French ($\text{fr}$)** with automatic **RTL** (Right-to-Left) for Arabic.
  * **Modern UI:** Built with **React** and **shadcn/ui** components for a fast, accessible, and responsive user experience.

-----

## 🛠️ Technology Stack

This project is a modern **TALL Stack (Tailwind, Alpine, Laravel, Livewire/Vite)** variation:

| Component | Technology | Version | Description |
| :--- | :--- | :--- | :--- |
| **Backend** | **Laravel** | 12 | PHP Framework for API, Routing, and Eloquent ORM. |
| **Frontend** | **React** | 19 | JavaScript library for building the user interface. |
| **Bridge** | **Inertia.js** | ^1.0 | A "monolith" approach for passing server-side data (Laravel) to the client (React). |
| **Styling** | **Tailwind CSS** | ^3.0 | Utility-first CSS framework for rapid UI development. |
| **Tooling** | **Vite** | ^5.0 | Next-generation frontend tooling for asset bundling and HMR. |
| **Language** | **TypeScript** | | Enhanced type safety for the React frontend. |

-----

## ⚙️ Development Setup

To get a local copy up and running, follow these simple steps.

### Prerequisites

  * PHP $\ge 8.3$
  * Composer
  * Node.js $\ge 18$
  * A database (e.g., MySQL, PostgreSQL, or SQLite)

### Installation

1.  **Clone the Repository**

    ```bash
    git clone https://github.com/YourUsername/Scammer-Alert-DZ.git
    cd Scammer-Alert-DZ
    ```

2.  **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

3.  **Configure Environment**

    ```bash
    cp .env.example .env
    php artisan key:generate
    # Edit the .env file with your database credentials
    ```

4.  **Database & Seeding**

    ```bash
    php artisan migrate --seed
    ```

5.  **Run the Servers**

    You will need **two terminal windows** open:

    | Terminal | Command | Description |
    | :--- | :--- | :--- |
    | **1 (Backend)** | `php artisan serve` | Starts the Laravel development server. |
    | **2 (Frontend)** | `npm run dev` | Starts the Vite server for hot module replacement (HMR). |

The application will now be accessible at the address shown by your `php artisan serve` command (usually `http://127.0.0.1:8000`).

-----

## 🤝 Contributing

We welcome contributions\! Please check the **[Contributing Guide](https://www.google.com/search?q=CONTRIBUTING.md)** (create this later) for details on our development workflow, coding standards, and how to submit a Pull Request.

Key areas where help is needed:

  * **Scam Reporting Forms** (Core submission logic)
  * **Search and Verification Tools**
  * **Test Coverage** (Backend and Frontend)
  * **Translation** (Reviewing $\text{ar}$ and $\text{fr}$ keys)

-----

## 📄 License

This project is licensed under the **MIT License**. See the **[LICENSE](https://www.google.com/search?q=LICENSE)** file for details.
