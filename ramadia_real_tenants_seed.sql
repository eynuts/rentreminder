-- ============================================================
-- RAMADIA BOARDING HOUSE — REAL TENANT ROSTER SEED
-- Run this AFTER ramadia_rent_reminder_schema.sql
--
-- 8 rooms, 18 tenants, all paying ₱3,500.00/month.
-- Uses subqueries on unit_number so it works whether or not
-- you've already inserted the earlier placeholder sample data.
-- ============================================================

USE ramadia_rent_reminder;

-- ------------------------------------------------------------
-- CLEANUP — removes existing data before re-inserting so
-- this file can be imported multiple times without errors.
-- ------------------------------------------------------------
DELETE FROM rent_payments;
DELETE FROM reminder_logs;
DELETE FROM maintenance_requests;
DELETE FROM users WHERE role = 'tenant';
DELETE FROM units WHERE property_id = 1;

-- ------------------------------------------------------------
-- UNITS — one row per room, all at ₱3,500.00/month
-- ------------------------------------------------------------
INSERT IGNORE INTO units (property_id, unit_number, monthly_rent, status) VALUES
(1, 'Room 5',  3500.00, 'occupied'),
(1, 'Room 9',  3500.00, 'occupied'),
(1, 'Room 10', 3500.00, 'occupied'),
(1, 'Room 11', 3500.00, 'occupied'),
(1, 'Room 12', 3500.00, 'occupied'),
(1, 'Room 14', 3500.00, 'occupied'),
(1, 'Room 16', 3500.00, 'occupied'),
(1, 'Room 17', 3500.00, 'occupied');

-- ------------------------------------------------------------
-- TENANTS — password_hash is a placeholder until each tenant
-- registers/sets their own password through the portal.
-- ------------------------------------------------------------
INSERT IGNORE INTO users (role, full_name, email, password_hash, mobile_number, unit_id) VALUES
('tenant', 'Chariz V. Lualhati',          'charizlualhati513@gmail.com',            '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09975036678', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 12')),
('tenant', 'Mary Len P. Apalit',          'apalitmarylen23@gmail.com',              '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09553224420', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 12')),
('tenant', 'Ma. Edeliza L. Imbate',       'maedelizaluisimbate@gmail.com',          '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09382910667', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 12')),
('tenant', 'Jessica S. Malanao',          'MalanaoJessica4@gmail.com',              '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09623485860', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 14')),
('tenant', 'Melissa Taneo Glean',         'melissaglean72@gmail.com',               '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09159956968', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 9')),
('tenant', 'Rhiana Lee Malicse Montesa',  'montesarhianalee16@gmail.com',           '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09945039847', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 9')),
('tenant', 'Melanie Ramos',               'melanieandalramos25@gmail.com',          '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09262704657', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 17')),
('tenant', 'Romelyn Gabanan',             'gabanan.romelynsy24@gmail.com',          '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09363952900', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 17')),
('tenant', 'Leslie Fatima E. Jara',       'lesliefatimajara@gmail.com',             '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09637334830', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 17')),
('tenant', 'Wawie Managuit',              'managuitwawie7@gmail.com',               '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09853762128', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 11')),
('tenant', 'Edreanne Falcunit',           'eadreannefalcunit@gmail.com',            '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09666400101', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 11')),
('tenant', 'Alfred Madera',               'alfredmadera@gmail.com',                 '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', NULL,          (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 11')),
('tenant', 'Zephanie Claire Dimayacyac',  'clairedimayacyac34@gmail.com',           '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09930442038', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 11')),
('tenant', 'Welojean G. Fran',            'welojeanf@gmail.com',                    '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09679144636', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 5')),
('tenant', 'Gillene D. Alicpala',         'alicpalagillene@gmail.com',              '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09368639697', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 5')),
('tenant', 'Jenna A. Basco',              'bascojenna@gmail.com',                   '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09613650655', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 5')),
('tenant', 'Razhel Jean B. Festin',       'razheljeanfestin@gmail.com',             '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09648633897', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 16')),
('tenant', 'Maria Mikaela H. Sapallo',    'mariamikaelahernandezsapallo@gmail.com', '$2a$10$9iRubU7R5FJ3Xuv5XtDbK.9ZmvFl/2x5QspbJ8X2Ozl8Dp4eAZfwa', '09812723685', (SELECT unit_id FROM units WHERE property_id=1 AND unit_number='Room 10'));

-- ------------------------------------------------------------
-- CURRENT MONTH'S RENT — seeded as Pending since no payment
-- status or due date was given. Adjust due_date / status per
-- tenant once you have that information, or remove this block
-- if you'd rather enter billing manually through the Reports tab.
-- ------------------------------------------------------------
INSERT IGNORE INTO rent_payments (tenant_id, unit_id, billing_month, amount_due, due_date, payment_status)
SELECT user_id, unit_id, '2026-09-01', 3500.00, '2026-09-05', 'Pending'
FROM users
WHERE role = 'tenant' AND unit_id IS NOT NULL;
