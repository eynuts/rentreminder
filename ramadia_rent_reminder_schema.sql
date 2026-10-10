-- ============================================================
-- RAMADIA BOARDING HOUSE — RENT REMINDER SYSTEM
-- Database Schema (MySQL 8.0 / MySQL Workbench compatible)
--
-- Derived from the front-end prototype's screens:
--   Dashboard (login)  ->  users, properties, units
--   Reminders          ->  reminder_logs
--   Reports            ->  rent_payments (summary cards are computed, not stored)
--   Maintenance         ->  maintenance_requests
--   Support             ->  static content in the prototype; no table needed
-- ============================================================

CREATE DATABASE IF NOT EXISTS ramadia_rent_reminder
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE ramadia_rent_reminder;

-- ------------------------------------------------------------
-- USERS
-- Holds tenants, landlords, and admins in one table, distinguished
-- by `role`. A tenant is linked to the unit they occupy.
-- ------------------------------------------------------------
CREATE TABLE users (
    user_id         INT AUTO_INCREMENT PRIMARY KEY,
    role            ENUM('tenant', 'landlord', 'admin') NOT NULL,
    full_name       VARCHAR(150) NOT NULL,
    email           VARCHAR(150) UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    mobile_number   VARCHAR(20),               -- used as the SMS recipient
    unit_id         INT NULL,                  -- set only when role = 'tenant'
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- PROPERTIES
-- Basic property information (Property Information Management).
-- One row per boarding house / building.
-- ------------------------------------------------------------
CREATE TABLE properties (
    property_id     INT AUTO_INCREMENT PRIMARY KEY,
    property_name   VARCHAR(150) NOT NULL,      -- "Ramadia Boarding House"
    address         VARCHAR(255) NOT NULL,      -- "Odiong, Roxas, Oriental Mindoro"
    owner_id        INT NOT NULL,               -- FK -> users (role = 'landlord')
    helpline        VARCHAR(20),
    support_email   VARCHAR(150),
    CONSTRAINT fk_properties_owner
        FOREIGN KEY (owner_id) REFERENCES users(user_id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- UNITS
-- Each rentable room/unit within a property, with its monthly rent.
-- ------------------------------------------------------------
CREATE TABLE units (
    unit_id         INT AUTO_INCREMENT PRIMARY KEY,
    property_id     INT NOT NULL,
    unit_number     VARCHAR(30) NOT NULL,       -- "Unit 2B", "Apt 102"
    monthly_rent    DECIMAL(10,2) NOT NULL,
    status          ENUM('occupied', 'vacant') NOT NULL DEFAULT 'vacant',
    CONSTRAINT fk_units_property
        FOREIGN KEY (property_id) REFERENCES properties(property_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE KEY uq_unit_per_property (property_id, unit_number)
) ENGINE=InnoDB;

-- Now that units exists, link tenants to their unit.
ALTER TABLE users
    ADD CONSTRAINT fk_users_unit
        FOREIGN KEY (unit_id) REFERENCES units(unit_id)
        ON UPDATE CASCADE ON DELETE SET NULL;

-- ------------------------------------------------------------
-- RENT_PAYMENTS
-- One row per tenant per billing period. This is both the
-- payment-status/history record and the source data for the
-- editable Reports tab (monthly & annual).
-- ------------------------------------------------------------
CREATE TABLE rent_payments (
    payment_id      INT AUTO_INCREMENT PRIMARY KEY,
    tenant_id       INT NOT NULL,
    unit_id         INT NOT NULL,
    billing_month   DATE NOT NULL,              -- store as the 1st of the month, e.g. 2026-09-01
    amount_due      DECIMAL(10,2) NOT NULL,
    due_date        DATE NOT NULL,
    paid_date       DATE NULL,
    payment_status  ENUM('Paid', 'Pending', 'Overdue') NOT NULL DEFAULT 'Pending',
    CONSTRAINT fk_payments_tenant
        FOREIGN KEY (tenant_id) REFERENCES users(user_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_payments_unit
        FOREIGN KEY (unit_id) REFERENCES units(unit_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    UNIQUE KEY uq_tenant_billing_month (tenant_id, billing_month)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- REMINDER_LOGS
-- One row per automated SMS/email notification sent, matching
-- the Reminders tab (recipient, channel, trigger type, message,
-- delivery status).
-- ------------------------------------------------------------
CREATE TABLE reminder_logs (
    log_id          INT AUTO_INCREMENT PRIMARY KEY,
    tenant_id       INT NOT NULL,
    payment_id      INT NULL,                   -- the rent period this reminder is about
    channel         ENUM('SMS', 'Email') NOT NULL,
    trigger_type    ENUM('Advance Notice', 'Due Date Alert', 'Overdue Alert') NOT NULL,
    message         TEXT NOT NULL,
    sent_at         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    delivery_status ENUM('Sent', 'Delivered', 'Failed') NOT NULL DEFAULT 'Sent',
    CONSTRAINT fk_reminders_tenant
        FOREIGN KEY (tenant_id) REFERENCES users(user_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_reminders_payment
        FOREIGN KEY (payment_id) REFERENCES rent_payments(payment_id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- MAINTENANCE_REQUESTS
-- One row per maintenance ticket, matching the Maintenance tab
-- (tenant/unit, category, description, status, dates).
-- ------------------------------------------------------------
CREATE TABLE maintenance_requests (
    request_id      INT AUTO_INCREMENT PRIMARY KEY,
    tenant_id       INT NOT NULL,
    unit_id         INT NOT NULL,
    category        ENUM('Plumbing', 'Electrical', 'Appliance', 'Structural') NOT NULL,
    description     TEXT NOT NULL,
    status          ENUM('Pending', 'In Progress', 'Resolved') NOT NULL DEFAULT 'Pending',
    date_reported   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_resolved   DATETIME NULL,
    CONSTRAINT fk_maintenance_tenant
        FOREIGN KEY (tenant_id) REFERENCES users(user_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_maintenance_unit
        FOREIGN KEY (unit_id) REFERENCES units(unit_id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Helpful indexes for the queries the UI actually runs
-- (filtering reminders/payments by tenant, and due-date scans
-- for the reminder scheduler)
-- ------------------------------------------------------------
CREATE INDEX idx_payments_status_due   ON rent_payments (payment_status, due_date);
CREATE INDEX idx_reminders_tenant_date ON reminder_logs (tenant_id, sent_at);
CREATE INDEX idx_maintenance_status    ON maintenance_requests (status);

-- ============================================================
-- SAMPLE DATA — matches the values hard-coded in the prototype,
-- so you can load this and see the same dashboard numbers.
-- ============================================================

INSERT INTO users (role, full_name, email, password_hash, mobile_number) VALUES
('landlord', 'Medelyn Y. Lumbres', 'support@ramadiaboardingshouse.ph', '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '094977973960');

INSERT INTO properties (property_name, address, owner_id, helpline, support_email) VALUES
('Ramadia Boarding House', 'Odiong, Roxas, Oriental Mindoro', 1, '094977973960', 'support@ramadiaboardingshouse.ph');

INSERT INTO units (property_id, unit_number, monthly_rent, status) VALUES
(1, 'Unit 2B', 3500.00, 'occupied'),
(1, 'Apt 102', 4000.00, 'occupied');
-- ... add the remaining units up to the property's 12 active units

INSERT INTO users (role, full_name, email, password_hash, mobile_number, unit_id) VALUES
('tenant', 'Sophia Chen', 'sophia@example.com', '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '+63 917-0129', 1),
('tenant', 'Robert Miller', 'robert@example.com', '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '+63 918-0188', 2);

INSERT INTO rent_payments (tenant_id, unit_id, billing_month, amount_due, due_date, paid_date, payment_status) VALUES
(2, 1, '2026-09-01', 3500.00, '2026-09-16', NULL, 'Overdue'),
(3, 2, '2026-09-01', 4000.00, '2026-09-15', '2026-09-15', 'Paid');

INSERT INTO reminder_logs (tenant_id, payment_id, channel, trigger_type, message, sent_at, delivery_status) VALUES
(2, 1, 'SMS',   'Overdue Alert',  'URGENT: Rent of ₱3,500 is 3 days overdue.',        '2026-09-19 09:00:00', 'Delivered'),
(2, 1, 'Email', 'Overdue Alert',  'Notice of overdue rent payment for Unit 2B.',       '2026-09-19 09:01:00', 'Sent'),
(3, 2, 'SMS',   'Due Date Alert', 'Reminder: Rent of ₱4,000 is due today.',            '2026-09-15 08:30:00', 'Delivered');

INSERT INTO maintenance_requests (tenant_id, unit_id, category, description, status, date_reported) VALUES
(2, 1, 'Plumbing',   'Bathroom sink dripping continuously.',      'In Progress', '2026-09-17 00:00:00'),
(3, 2, 'Electrical', 'Living room wall switch loose.',            'Resolved',    '2026-09-14 00:00:00');
