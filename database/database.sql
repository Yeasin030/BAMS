-- Business Account Management System
-- MySQL 8.0+ / InnoDB
-- Based on the supplied system documentation.
-- Review and test in a development database before production use.

CREATE DATABASE IF NOT EXISTS business_account_management
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE business_account_management;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS audit_logs;
DROP TABLE IF EXISTS payroll;
DROP TABLE IF EXISTS stock_movements;
DROP TABLE IF EXISTS payment_allocations;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS purchase_items;
DROP TABLE IF EXISTS purchases;
DROP TABLE IF EXISTS sales_items;
DROP TABLE IF EXISTS sales;
DROP TABLE IF EXISTS expenses;
DROP TABLE IF EXISTS expense_categories;
DROP TABLE IF EXISTS incomes;
DROP TABLE IF EXISTS income_categories;
DROP TABLE IF EXISTS journal_lines;
DROP TABLE IF EXISTS journal_entries;
DROP TABLE IF EXISTS accounts;
DROP TABLE IF EXISTS account_categories;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS suppliers;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS user_roles;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS companies;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE companies (
    company_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NULL,
    phone VARCHAR(30) NULL,
    address TEXT NULL,
    logo VARCHAR(255) NULL,
    currency VARCHAR(10) NOT NULL DEFAULT 'BDT',
    financial_year_start DATE NULL,
    status TINYINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL COMMENT 'Store password_hash() output only',
    status TINYINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_company_email (company_id, email),
    CONSTRAINT fk_users_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE roles (
    role_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    role_name VARCHAR(80) NOT NULL,
    UNIQUE KEY uq_roles_company_name (company_id, role_name),
    CONSTRAINT fk_roles_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE user_roles (
    user_role_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    role_id INT UNSIGNED NOT NULL,
    UNIQUE KEY uq_user_role (user_id, role_id),
    CONSTRAINT fk_user_roles_user FOREIGN KEY (user_id)
        REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_user_roles_role FOREIGN KEY (role_id)
        REFERENCES roles(role_id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE customers (
    customer_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    customer_name VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NULL,
    email VARCHAR(150) NULL,
    address TEXT NULL,
    status TINYINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_customers_company (company_id),
    CONSTRAINT fk_customers_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE suppliers (
    supplier_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    supplier_name VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NULL,
    email VARCHAR(150) NULL,
    address TEXT NULL,
    status TINYINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_suppliers_company (company_id),
    CONSTRAINT fk_suppliers_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE account_categories (
    category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    category_name VARCHAR(100) NOT NULL,
    account_type ENUM('ASSET','LIABILITY','EQUITY','REVENUE','EXPENSE') NOT NULL,
    description TEXT NULL,
    UNIQUE KEY uq_account_category_company_name (company_id, category_name),
    CONSTRAINT fk_account_categories_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE accounts (
    account_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    parent_account_id INT UNSIGNED NULL,
    account_code VARCHAR(30) NOT NULL,
    account_name VARCHAR(120) NOT NULL,
    opening_balance DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    opening_balance_side ENUM('DEBIT','CREDIT') NOT NULL DEFAULT 'DEBIT',
    is_active TINYINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_accounts_company_code (company_id, account_code),
    KEY idx_accounts_category (category_id),
    KEY idx_accounts_parent (parent_account_id),
    CONSTRAINT fk_accounts_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_accounts_category FOREIGN KEY (category_id)
        REFERENCES account_categories(category_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_accounts_parent FOREIGN KEY (parent_account_id)
        REFERENCES accounts(account_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_accounts_opening_balance CHECK (opening_balance >= 0)
) ENGINE=InnoDB;

CREATE TABLE products (
    product_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    product_name VARCHAR(150) NOT NULL,
    product_code VARCHAR(50) NOT NULL,
    purchase_price DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    selling_price DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    current_stock DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    status TINYINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_products_company_code (company_id, product_code),
    CONSTRAINT fk_products_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_products_prices CHECK (purchase_price >= 0 AND selling_price >= 0)
) ENGINE=InnoDB;

CREATE TABLE employees (
    employee_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    employee_name VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NULL,
    designation VARCHAR(100) NULL,
    joining_date DATE NULL,
    basic_salary DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    status TINYINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_employees_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_employees_salary CHECK (basic_salary >= 0)
) ENGINE=InnoDB;

CREATE TABLE journal_entries (
    journal_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    journal_number VARCHAR(50) NOT NULL,
    transaction_date DATE NOT NULL,
    reference_number VARCHAR(100) NULL,
    description TEXT NULL,
    source_type VARCHAR(50) NULL COMMENT 'Example: SALE, EXPENSE, PAYMENT',
    source_id INT UNSIGNED NULL COMMENT 'Polymorphic source; validate in application',
    status ENUM('DRAFT','POSTED','REVERSED') NOT NULL DEFAULT 'DRAFT',
    created_by INT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_journal_company_number (company_id, journal_number),
    KEY idx_journal_company_date (company_id, transaction_date),
    KEY idx_journal_source (source_type, source_id),
    CONSTRAINT fk_journal_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_journal_created_by FOREIGN KEY (created_by)
        REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE journal_lines (
    line_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    journal_id INT UNSIGNED NOT NULL,
    account_id INT UNSIGNED NOT NULL,
    description TEXT NULL,
    debit DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    credit DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    KEY idx_journal_lines_account (account_id),
    CONSTRAINT fk_journal_lines_entry FOREIGN KEY (journal_id)
        REFERENCES journal_entries(journal_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_journal_lines_account FOREIGN KEY (account_id)
        REFERENCES accounts(account_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_journal_line_nonnegative CHECK (debit >= 0 AND credit >= 0),
    CONSTRAINT chk_journal_line_one_side CHECK (NOT (debit > 0 AND credit > 0))
) ENGINE=InnoDB;

CREATE TABLE income_categories (
    income_category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    category_name VARCHAR(100) NOT NULL,
    account_id INT UNSIGNED NOT NULL COMMENT 'Revenue account',
    UNIQUE KEY uq_income_categories_company_name (company_id, category_name),
    CONSTRAINT fk_income_categories_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_income_categories_account FOREIGN KEY (account_id)
        REFERENCES accounts(account_id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE incomes (
    income_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    income_category_id INT UNSIGNED NOT NULL,
    account_id INT UNSIGNED NOT NULL COMMENT 'Cash/bank account',
    amount DECIMAL(15,2) NOT NULL,
    income_date DATE NOT NULL,
    description TEXT NULL,
    journal_id INT UNSIGNED NULL,
    created_by INT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_incomes_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_incomes_category FOREIGN KEY (income_category_id)
        REFERENCES income_categories(income_category_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_incomes_account FOREIGN KEY (account_id)
        REFERENCES accounts(account_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_incomes_journal FOREIGN KEY (journal_id)
        REFERENCES journal_entries(journal_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_incomes_user FOREIGN KEY (created_by)
        REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT chk_incomes_amount CHECK (amount > 0)
) ENGINE=InnoDB;

CREATE TABLE expense_categories (
    expense_category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    category_name VARCHAR(100) NOT NULL,
    account_id INT UNSIGNED NOT NULL COMMENT 'Expense account',
    UNIQUE KEY uq_expense_categories_company_name (company_id, category_name),
    CONSTRAINT fk_expense_categories_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_expense_categories_account FOREIGN KEY (account_id)
        REFERENCES accounts(account_id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE expenses (
    expense_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    expense_category_id INT UNSIGNED NOT NULL,
    account_id INT UNSIGNED NOT NULL COMMENT 'Cash/bank account',
    amount DECIMAL(15,2) NOT NULL,
    expense_date DATE NOT NULL,
    description TEXT NULL,
    receipt_file VARCHAR(255) NULL,
    journal_id INT UNSIGNED NULL,
    created_by INT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_expenses_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_expenses_category FOREIGN KEY (expense_category_id)
        REFERENCES expense_categories(expense_category_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_expenses_account FOREIGN KEY (account_id)
        REFERENCES accounts(account_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_expenses_journal FOREIGN KEY (journal_id)
        REFERENCES journal_entries(journal_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_expenses_user FOREIGN KEY (created_by)
        REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT chk_expenses_amount CHECK (amount > 0)
) ENGINE=InnoDB;

CREATE TABLE sales (
    sale_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    customer_id INT UNSIGNED NOT NULL,
    invoice_number VARCHAR(50) NOT NULL,
    sale_date DATE NOT NULL,
    subtotal DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    discount DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    tax_amount DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    total_amount DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    status ENUM('DRAFT','POSTED','PARTIAL','PAID','CANCELLED') NOT NULL DEFAULT 'DRAFT',
    journal_id INT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_sales_company_invoice (company_id, invoice_number),
    KEY idx_sales_customer_date (customer_id, sale_date),
    CONSTRAINT fk_sales_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_sales_customer FOREIGN KEY (customer_id)
        REFERENCES customers(customer_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_sales_journal FOREIGN KEY (journal_id)
        REFERENCES journal_entries(journal_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_sales_amounts CHECK (
        subtotal >= 0 AND discount >= 0 AND tax_amount >= 0 AND total_amount >= 0
    )
) ENGINE=InnoDB;

CREATE TABLE sales_items (
    sales_item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sale_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity DECIMAL(12,2) NOT NULL,
    unit_price DECIMAL(15,2) NOT NULL,
    total DECIMAL(15,2) NOT NULL,
    CONSTRAINT fk_sales_items_sale FOREIGN KEY (sale_id)
        REFERENCES sales(sale_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_sales_items_product FOREIGN KEY (product_id)
        REFERENCES products(product_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_sales_items_values CHECK (quantity > 0 AND unit_price >= 0 AND total >= 0)
) ENGINE=InnoDB;

CREATE TABLE purchases (
    purchase_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    supplier_id INT UNSIGNED NOT NULL,
    invoice_number VARCHAR(50) NOT NULL,
    purchase_date DATE NOT NULL,
    subtotal DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    discount DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    tax_amount DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    total_amount DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    status ENUM('DRAFT','POSTED','PARTIAL','PAID','CANCELLED') NOT NULL DEFAULT 'DRAFT',
    journal_id INT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_purchases_company_invoice (company_id, invoice_number),
    KEY idx_purchases_supplier_date (supplier_id, purchase_date),
    CONSTRAINT fk_purchases_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_purchases_supplier FOREIGN KEY (supplier_id)
        REFERENCES suppliers(supplier_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_purchases_journal FOREIGN KEY (journal_id)
        REFERENCES journal_entries(journal_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_purchases_amounts CHECK (
        subtotal >= 0 AND discount >= 0 AND tax_amount >= 0 AND total_amount >= 0
    )
) ENGINE=InnoDB;

CREATE TABLE purchase_items (
    purchase_item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    purchase_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity DECIMAL(12,2) NOT NULL,
    unit_cost DECIMAL(15,2) NOT NULL,
    total DECIMAL(15,2) NOT NULL,
    CONSTRAINT fk_purchase_items_purchase FOREIGN KEY (purchase_id)
        REFERENCES purchases(purchase_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_purchase_items_product FOREIGN KEY (product_id)
        REFERENCES products(product_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_purchase_items_values CHECK (quantity > 0 AND unit_cost >= 0 AND total >= 0)
) ENGINE=InnoDB;

CREATE TABLE payments (
    payment_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    party_type ENUM('CUSTOMER','SUPPLIER','OTHER') NOT NULL,
    customer_id INT UNSIGNED NULL,
    supplier_id INT UNSIGNED NULL,
    account_id INT UNSIGNED NOT NULL COMMENT 'Cash/bank account',
    payment_type ENUM('RECEIPT','PAYMENT') NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    reference_number VARCHAR(100) NULL,
    journal_id INT UNSIGNED NULL,
    created_by INT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_payments_company_date (company_id, payment_date),
    CONSTRAINT fk_payments_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_payments_customer FOREIGN KEY (customer_id)
        REFERENCES customers(customer_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_payments_supplier FOREIGN KEY (supplier_id)
        REFERENCES suppliers(supplier_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_payments_account FOREIGN KEY (account_id)
        REFERENCES accounts(account_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_payments_journal FOREIGN KEY (journal_id)
        REFERENCES journal_entries(journal_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_payments_user FOREIGN KEY (created_by)
        REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT chk_payments_amount CHECK (amount > 0),
    CONSTRAINT chk_payments_party CHECK (
        (party_type = 'CUSTOMER' AND customer_id IS NOT NULL AND supplier_id IS NULL)
        OR (party_type = 'SUPPLIER' AND supplier_id IS NOT NULL AND customer_id IS NULL)
        OR (party_type = 'OTHER' AND customer_id IS NULL AND supplier_id IS NULL)
    )
) ENGINE=InnoDB;

CREATE TABLE payment_allocations (
    allocation_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    payment_id INT UNSIGNED NOT NULL,
    invoice_type ENUM('SALE','PURCHASE') NOT NULL,
    invoice_id INT UNSIGNED NOT NULL COMMENT 'Polymorphic invoice ID; validate type and company in application',
    allocated_amount DECIMAL(15,2) NOT NULL,
    KEY idx_payment_allocations_invoice (invoice_type, invoice_id),
    CONSTRAINT fk_payment_allocations_payment FOREIGN KEY (payment_id)
        REFERENCES payments(payment_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_payment_allocated_amount CHECK (allocated_amount > 0)
) ENGINE=InnoDB;

CREATE TABLE stock_movements (
    movement_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    movement_type ENUM('IN','OUT','ADJUSTMENT') NOT NULL,
    quantity DECIMAL(12,2) NOT NULL,
    reference_type VARCHAR(50) NULL,
    reference_id INT UNSIGNED NULL COMMENT 'Source record; validate in application',
    movement_date DATE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_stock_product_date (product_id, movement_date),
    KEY idx_stock_reference (reference_type, reference_id),
    CONSTRAINT fk_stock_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_stock_product FOREIGN KEY (product_id)
        REFERENCES products(product_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_stock_quantity CHECK (quantity > 0)
) ENGINE=InnoDB;

CREATE TABLE payroll (
    payroll_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    employee_id INT UNSIGNED NOT NULL,
    salary_month DATE NOT NULL COMMENT 'Use the first day of the salary month',
    basic_salary DECIMAL(15,2) NOT NULL,
    bonus DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    deductions DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    net_salary DECIMAL(15,2) NOT NULL,
    payment_status ENUM('PENDING','PAID') NOT NULL DEFAULT 'PENDING',
    journal_id INT UNSIGNED NULL,
    UNIQUE KEY uq_payroll_employee_month (employee_id, salary_month),
    CONSTRAINT fk_payroll_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_payroll_employee FOREIGN KEY (employee_id)
        REFERENCES employees(employee_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_payroll_journal FOREIGN KEY (journal_id)
        REFERENCES journal_entries(journal_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT chk_payroll_amounts CHECK (
        basic_salary >= 0 AND bonus >= 0 AND deductions >= 0 AND net_salary >= 0
    )
) ENGINE=InnoDB;

CREATE TABLE audit_logs (
    log_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(100) NOT NULL,
    record_id INT UNSIGNED NULL,
    description TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_audit_company_created (company_id, created_at),
    KEY idx_audit_user (user_id),
    CONSTRAINT fk_audit_company FOREIGN KEY (company_id)
        REFERENCES companies(company_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_audit_user FOREIGN KEY (user_id)
        REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- IMPORTANT APPLICATION-LEVEL VALIDATIONS:
-- 1. Enforce company ownership across related rows (e.g. sale/customer/product,
--    account/category, journal line/account, employee/payroll).
-- 2. payment_allocations.invoice_type + invoice_id is polymorphic and cannot
--    use a normal single-column FK to both sales and purchases.
-- 3. journal_entries.source_type + source_id and stock movement references
--    are polymorphic; validate them in PHP.
-- 4. Before changing a journal to POSTED, require >= 2 lines and SUM(debit)=SUM(credit).
-- 5. Save source records and journal posting in one SQL transaction.
-- 6. Only POSTED journals should feed ledgers and financial reports.
