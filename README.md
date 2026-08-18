# Faith Connect

Web-based Church Registry Information Management System for **Holy Cross Parish, Carigara, Leyte**.

Built with plain PHP 8 (PDO) and MySQL/MariaDB so it runs on XAMPP without any build step.

## Modules (based on the approved program flow)

| Flow step | Module | Pages |
|---|---|---|
| 1-2 | Admin login & authentication | `login.php`, `includes/auth.php` |
| 3 | Dashboard | `dashboard.php` |
| 4-5 | Registry Management (Baptismal, Marriage, Confirmation, Death) | `registry/index.php`, `registry/form.php`, `registry/view.php`, `registry/delete.php` |
| 4-5 | Record Management (view all, search & filter, verify & validate, update status, archive) | `records/index.php`, `records/status.php` |
| 4-5 | Reports & Record Summary (reports, statistics, summary, export) | `reports/index.php`, `reports/print.php`, `reports/export.php`, `reports/certificate.php` |
| 4-5 | System & User Management (accounts, roles, activate/deactivate, reset password, settings, backup/restore) | `admin/users.php`, `admin/user_form.php`, `admin/user_action.php`, `admin/settings.php`, `admin/backup.php`, `admin/logs.php` |
| 6 | Admin logout | `logout.php` |

## Installation (XAMPP)

1. Copy this folder into `htdocs/` so it is reachable at `http://localhost/faith-connect`.
2. Create the database and load the tables plus sample data:

   ```
   mysql -u root -p -e "CREATE DATABASE faith_connect CHARACTER SET utf8mb4"
   mysql -u root -p faith_connect < database/schema.sql
   mysql -u root -p faith_connect < database/seed.sql
   ```

3. Adjust the credentials in `includes/config.php` if your MySQL user is not `root` with an empty password.
   Every value can also be supplied through environment variables: `FC_DB_HOST`, `FC_DB_NAME`,
   `FC_DB_USER`, `FC_DB_PASS`, `FC_BASE_URL`, `FC_BACKUP_DIR`.
4. Open `http://localhost/faith-connect` and sign in.

### Default accounts (change immediately)

| Username | Password | Role |
|---|---|---|
| `admin` | `admin123` | Admin |
| `staff1` | `staff123` | Staff |

## Roles

* **Admin** – full access: encode, edit, verify, delete, archive, reports, users, settings, backup/restore.
* **Staff** – encode, edit and archive records, view and export reports.
* **Viewer** – view records and reports only.

## Data model

`records` holds the fields every registry shares (book/page/entry number, date, place, officiating
priest, status, archive flag) and each registry adds its own 1:1 detail table
(`baptismal_details`, `marriage_details`, `confirmation_details`, `death_details`). Every screen for
the four registries is generated from `includes/registries.php`, so adding a field means editing that
one file plus a migration.

## Reports

* **Printable report** (`reports/print.php`) – opens a print-ready sheet; use the browser's
  *Print → Save as PDF*.
* **CSV export** (`reports/export.php`) – UTF-8 with BOM so Excel opens it correctly.
* **Certificate** (`reports/certificate.php`) – printable transcript of a single record.

## Backup & restore

`admin/backup.php` produces a plain-PHP SQL dump (no `mysqldump` needed), can store it under
`storage/backups/`, and can restore a dump produced by the same screen.
