# Project Setup Instructions

Welcome to the project! Please follow the guidelines below to set up your environment and maintain consistency across the development team.

---

## ⚙️ Requirements

- **Minimum PHP version:** 8.0

Make sure your environment is running PHP 8 or higher before proceeding.

---

## 🔧 Environment Configuration

1. **Creating Your `.env` File**

   - Start by copying the provided `.env.example` file to create your environment configuration file:

     ```bash
     cp .env.example .env
     ```

   - Fill in the necessary values specific to your environment.

2. **Adding New Environment Variables**

   - If you introduce **new data or configuration** to the project:
     - Add a **sample entry** to the `.env.example` file.
     - This ensures all team members are aware of the new required variables.

---

## 🧩 Using Environment Variables in Code

- To access environment variables in your code:

  1. Include the `functions.php` file before calling any config function:

     ```php
     require_once 'functions.php';
     ```

  2. Use the `getConfig()` function to retrieve values:

     ```php
     $dbHost = getConfig('DB_HOST');
     ```

---

## 🗃️ Database Migrations

When making changes to the database schema:

1. **Extract the SQL** command(s) for the change (e.g., `CREATE`, `ALTER`, etc.).

2. Create a new `.txt` file in the `database/migrations/` folder.

3. Name the file using the following format:

YYYYMMDD_HHMMSS-action-description.txt

**Examples:**
- `20250825_130000-add-users-table.txt`
- `20250825_134500-modify-orders-table.txt`

---

## ✅ Setup Checklist

- [ ] PHP 8 or higher installed
- [ ] `.env` created from `.env.example`
- [ ] New env variables added to `.env.example` if needed
- [ ] `functions.php` required before using `getConfig()`
- [ ] SQL changes saved in `database/migrations/` with correct naming

---

Happy coding! 🚀
