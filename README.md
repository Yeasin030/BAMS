# Business Account Management System

A multi-company business accounting and management application built
with **Core PHP**, **MySQL 8.0**, **MySQLi**, HTML, CSS, Bootstrap, and
JavaScript.

> This repository documentation and database script are based on the
> supplied Business Account Management System specification. The SQL is
> a starting schema; application-level accounting validations are still
> required.

## Project information

  Item                Technology / Description
  ------------------- ------------------------------------------
  Project type        Business accounting software
  Backend             Core PHP (without Laravel)
  Database            MySQL 8.0 / InnoDB
  Database access     MySQLi prepared statements (without PDO)
  Frontend            HTML, CSS, JavaScript, Bootstrap
  Architecture        Modular, multi-company, multi-user
  Accounting method   Double-entry accounting

## Main modules

1.  Dashboard
2.  Company management
3.  User and role management
4.  Chart of accounts
5.  Account management and ledger
6.  Income management
7.  Expense management
8.  Sales management
9.  Purchase management
10. Customer and supplier management
11. Financial transactions and journal posting
12. Financial reports
13. Employee and payroll management
14. Product and inventory management
15. Tax management (planned extension)
16. Audit logs

## Main accounting workflow

``` text
Sales / Purchases / Income / Expenses / Payroll / Transfers
                         |
                         v
                  Journal Entries
                         |
                         v
                   Journal Lines
                         |
                         v
                 Chart of Accounts
                         |
                         v
                  Account Ledger
                         |
                         v
                Financial Reports
```

Every posted journal entry must have equal total debits and credits.
Draft entries must not affect balances or financial reports.

## Database tables

The SQL script creates the following tables:

-   `companies`
-   `users`
-   `roles`
-   `user_roles`
-   `customers`
-   `suppliers`
-   `account_categories`
-   `accounts`
-   `journal_entries`
-   `journal_lines`
-   `income_categories`
-   `incomes`
-   `expense_categories`
-   `expenses`
-   `sales`
-   `sales_items`
-   `purchases`
-   `purchase_items`
-   `payments`
-   `payment_allocations`
-   `products`
-   `stock_movements`
-   `employees`
-   `payroll`
-   `audit_logs`

## Requirements

-   PHP 8.0+ (recommended)
-   MySQL 8.0+
-   Apache / XAMPP or another PHP-capable web server
-   MySQLi PHP extension

## Installation

1.  Install and start Apache and MySQL (for example, using XAMPP).

2.  Create a database, or import `database.sql` directly into
    phpMyAdmin.

3.  Alternatively, run the SQL script from the MySQL command line:

    ``` bash
    mysql -u root -p < database.sql
    ```

4.  Configure your PHP database connection with the database name,
    username, and password.

5.  Build the application modules and connect them to the schema using
    MySQLi prepared statements.

The SQL script creates and selects a database named
`business_account_management`. Change the database name at the top of
the script if needed.

## Important implementation rules

-   Use `password_hash()` when storing passwords and `password_verify()`
    when authenticating users.
-   Use MySQLi prepared statements for every user-supplied database
    value.
-   Validate the authenticated user's `company_id` on the server for
    every company-owned operation.
-   Use InnoDB foreign keys and database transactions.
-   Use `DECIMAL(15,2)` for monetary values; do not use floating-point
    types for money.
-   Only posted journal entries should affect account balances and
    reports.
-   Validate that each posted journal has at least two lines and total
    debits equal total credits.
-   Do not edit or delete posted financial records directly. Use
    reversal entries for corrections.
-   A transfer between cash/bank accounts is not income or expense.
-   A receipt against an existing invoice reduces receivables; it is not
    new revenue.
-   Validate that payment allocations refer to an invoice of the correct
    type and company. The supplied design uses `invoice_type` +
    `invoice_id`, so this relationship needs application-level
    validation.
-   Validate polymorphic journal source references (`source_type` +
    `source_id`) in the application.
-   Save a source transaction and its journal entry in one database
    transaction.
-   Add authorization checks, CSRF protection, output escaping, session
    security, backups, and audit logging.

## Reports

Reports should be calculated from posted journal data:

-   Account Ledger
-   Profit & Loss
-   Balance Sheet
-   Cash Flow
-   Trial Balance
-   Customer Ledger
-   Supplier Ledger
-   Sales, Purchase, and Expense reports

## Suggested development sequence

1.  Project setup, database connection, authentication, and company
    isolation.
2.  Roles, permissions, chart of accounts, and account management.
3.  Income, expenses, journal engine, and account ledger.
4.  Customers, suppliers, sales, purchases, and payments.
5.  Dashboard and financial reports.
6.  Inventory, payroll, tax, audit logs, testing, and deployment.

## Files

-   `README.md` --- project overview and setup guide.
-   `database.sql` --- MySQL database schema.

## Notes

This is a logical database design, not a complete application. The
schema does not implement PHP workflows, permission checks, journal
balancing, invoice allocation validation, or report calculations. Those
must be implemented and tested in the application.
"# BAMS" 
