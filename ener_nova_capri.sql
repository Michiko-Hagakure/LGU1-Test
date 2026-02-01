-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 31, 2026 at 06:09 AM
-- Server version: 10.11.14-MariaDB-ubu2204
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ener_nova_capri`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `area` varchar(50) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `user_id`, `event_name`, `area`, `timestamp`) VALUES
(92, 88, 'Energy Conservation Awareness ', 'AREA 1', '2026-01-27 14:22:48');

-- --------------------------------------------------------

--
-- Table structure for table `audit_assignments`
--

CREATE TABLE `audit_assignments` (
  `assignment_id` int(11) NOT NULL,
  `resident_user_id` int(11) NOT NULL,
  `staff_user_id` int(11) NOT NULL,
  `assigned_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `scheduled_date` date NOT NULL,
  `status` enum('Pending','Ongoing','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `reason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_feedback`
--

CREATE TABLE `audit_feedback` (
  `feedback_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `resident_user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comments` text DEFAULT NULL,
  `feedback_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_findings`
--

CREATE TABLE `audit_findings` (
  `finding_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `finding_details` text DEFAULT NULL,
  `recommendations` text NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `completed_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barangay_energy_audits`
--

CREATE TABLE `barangay_energy_audits` (
  `audit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `submission_date` datetime NOT NULL,
  `days_at_home_monthly` tinyint(3) UNSIGNED NOT NULL DEFAULT 30 COMMENT 'Number of days the resident is home per month, used for estimated consumption calculation.',
  `appliances_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON array of selected appliances, their default usage/wattage, and calculated monthly kWh.' CHECK (json_valid(`appliances_data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `challenge_participation`
--

CREATE TABLE `challenge_participation` (
  `participation_id` int(11) NOT NULL,
  `challenge_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` enum('image','video') NOT NULL,
  `caption` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `challenge_participation`
--

INSERT INTO `challenge_participation` (`participation_id`, `challenge_id`, `user_id`, `file_path`, `file_type`, `caption`, `submitted_at`) VALUES
(1, 1, 88, 'uploads/challenges/user_88_1769254445.png', 'image', 'ww', '2026-01-24 11:34:05');

-- --------------------------------------------------------

--
-- Table structure for table `correction_requests`
--

CREATE TABLE `correction_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reading_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `requested_kwh` decimal(10,2) DEFAULT NULL,
  `requested_bill_amount` decimal(10,2) DEFAULT NULL,
  `requested_reading_date` date DEFAULT NULL,
  `new_bill_file_path_1` varchar(255) DEFAULT NULL,
  `new_bill_file_path_2` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reviewed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `correction_requests`
--

INSERT INTO `correction_requests` (`request_id`, `user_id`, `reading_id`, `reason`, `requested_kwh`, `requested_bill_amount`, `requested_reading_date`, `new_bill_file_path_1`, `new_bill_file_path_2`, `status`, `requested_at`, `reviewed_at`) VALUES
(1, 88, 10052, 'ewq', 312.00, 321.00, '2026-01-08', 'uploads/bills/88_1769699037_3967_ww.jpg', NULL, 'approved', '2026-01-29 15:03:57', '2026-01-29 15:04:28');

-- --------------------------------------------------------

--
-- Table structure for table `energy_challenges`
--

CREATE TABLE `energy_challenges` (
  `challenge_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','archived') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `energy_challenges`
--

INSERT INTO `energy_challenges` (`challenge_id`, `title`, `description`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, 'weq', 'ewq', '2026-01-24', '2026-02-01', 'active', '2026-01-24 11:33:43');

-- --------------------------------------------------------

--
-- Table structure for table `facility_booking_confirmations`
--

CREATE TABLE `facility_booking_confirmations` (
  `confirmation_id` int(11) NOT NULL,
  `seminar_id` int(11) NOT NULL,
  `public_facilities_tracking_id` varchar(50) DEFAULT NULL COMMENT 'GPR-2025-XXX',
  `request_status` enum('received','under_review','coordinating','confirmed','rejected','cancelled') DEFAULT 'received',
  `assigned_facility_id` int(11) DEFAULT NULL COMMENT 'ID from our facilities table',
  `assigned_facility_name` varchar(255) DEFAULT NULL,
  `assigned_facility_address` text DEFAULT NULL,
  `assigned_facility_capacity` int(11) DEFAULT NULL,
  `assigned_facility_amenities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '["Projector", "Sound System", "AC"]' CHECK (json_valid(`assigned_facility_amenities`)),
  `facility_fee_original` decimal(10,2) DEFAULT NULL COMMENT 'Normal rental fee',
  `facility_fee_charged` decimal(10,2) DEFAULT 0.00 COMMENT 'Always 0 - waived for gov programs',
  `facility_fee_waived` tinyint(1) DEFAULT 1,
  `confirmed_date` date DEFAULT NULL,
  `confirmed_start_time` time DEFAULT NULL,
  `confirmed_end_time` time DEFAULT NULL,
  `setup_time_minutes` int(11) DEFAULT 30,
  `confirmed_speakers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '[\r\n        {\r\n            "name": "Dr. Juan Dela Cruz",\r\n            "title": "DOE Energy Specialist",\r\n            "topic": "Home Energy Conservation Techniques",\r\n            "duration_minutes": 45\r\n        },\r\n        {\r\n            "name": "Eng. Maria Santos", \r\n            "title": "Meralco Senior Engineer",\r\n            "topic": "Understanding Your Electric Bill",\r\n            "duration_minutes": 30\r\n        }\r\n    ]' CHECK (json_valid(`confirmed_speakers`)),
  `equipment_provided` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`equipment_provided`)),
  `seminar_agenda` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '[\r\n        {"time": "14:00", "activity": "Registration"},\r\n        {"time": "14:30", "activity": "Opening Remarks"},\r\n        {"time": "14:45", "activity": "Speaker 1"},\r\n        {"time": "15:30", "activity": "Q&A"},\r\n        {"time": "16:00", "activity": "Speaker 2"},\r\n        {"time": "16:30", "activity": "Closing"}\r\n    ]' CHECK (json_valid(`seminar_agenda`)),
  `requested_amount` decimal(10,2) DEFAULT NULL,
  `approved_amount` decimal(10,2) DEFAULT NULL,
  `fund_approval_status` enum('pending','approved','rejected','partial') DEFAULT 'pending',
  `finance_check_number` varchar(50) DEFAULT NULL,
  `finance_release_date` date DEFAULT NULL,
  `pre_event_budget_breakdown` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '{\r\n        "food_refreshments": {\r\n            "amount": 2500.00,\r\n            "details": "150 Jollibee C1 meals @ 89.00 each",\r\n            "supplier": "Jollibee Caloocan Branch"\r\n        },\r\n        "training_materials": {\r\n            "amount": 2000.00,\r\n            "details": "Handbooks, pens, certificates",\r\n            "supplier": "PrintHub Caloocan"\r\n        },\r\n        "transportation": {\r\n            "amount": 300.00,\r\n            "details": "Speaker travel expenses"\r\n        },\r\n        "miscellaneous": {\r\n            "amount": 200.00,\r\n            "details": "Signage, name tags"\r\n        }\r\n    }' CHECK (json_valid(`pre_event_budget_breakdown`)),
  `post_event_liquidation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '{\r\n        "actual_attendees": 142,\r\n        "total_spent": 4880.00,\r\n        "savings": 120.00,\r\n        "items": [\r\n            {\r\n                "category": "food_refreshments",\r\n                "item": "Jollibee Chickenjoy C1",\r\n                "quantity": 142,\r\n                "unit_price": 89.00,\r\n                "total": 2418.00,\r\n                "supplier": "Jollibee Caloocan Branch",\r\n                "or_number": "123456789",\r\n                "receipt_url": "https://facilities.caloocan.gov.ph/receipts/123.pdf"\r\n            }\r\n        ]\r\n    }' CHECK (json_valid(`post_event_liquidation`)),
  `transparency_report_url` varchar(500) DEFAULT NULL COMMENT 'https://facilities.caloocan.gov.ph/transparency/GPR-2025-456',
  `is_published_publicly` tinyint(1) DEFAULT 1 COMMENT 'Citizens can view transparency',
  `admin_contact_name` varchar(255) DEFAULT NULL,
  `admin_contact_phone` varchar(20) DEFAULT NULL,
  `admin_contact_email` varchar(255) DEFAULT NULL,
  `coordination_notes` text DEFAULT NULL COMMENT 'Internal notes from Public Facilities admin',
  `organizer_call_log` text DEFAULT NULL COMMENT 'Record of phone calls/discussions with organizer',
  `reminders` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT '[\r\n        "Setup starts at 1:30 PM (30 mins before event)",\r\n        "All equipment (projector, sound system) included FREE",\r\n        "Facility cleaning required after event",\r\n        "Liquidation report due within 3 days after event"\r\n    ]' CHECK (json_valid(`reminders`)),
  `received_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'When we received their request',
  `confirmed_at` timestamp NULL DEFAULT NULL COMMENT 'When admin confirmed',
  `liquidation_submitted_at` timestamp NULL DEFAULT NULL COMMENT 'When they submitted actual spending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Stores facility booking confirmations from Public Facilities system';

--
-- Dumping data for table `facility_booking_confirmations`
--

INSERT INTO `facility_booking_confirmations` (`confirmation_id`, `seminar_id`, `public_facilities_tracking_id`, `request_status`, `assigned_facility_id`, `assigned_facility_name`, `assigned_facility_address`, `assigned_facility_capacity`, `assigned_facility_amenities`, `facility_fee_original`, `facility_fee_charged`, `facility_fee_waived`, `confirmed_date`, `confirmed_start_time`, `confirmed_end_time`, `setup_time_minutes`, `confirmed_speakers`, `equipment_provided`, `seminar_agenda`, `requested_amount`, `approved_amount`, `fund_approval_status`, `finance_check_number`, `finance_release_date`, `pre_event_budget_breakdown`, `post_event_liquidation`, `transparency_report_url`, `is_published_publicly`, `admin_contact_name`, `admin_contact_phone`, `admin_contact_email`, `coordination_notes`, `organizer_call_log`, `reminders`, `received_at`, `confirmed_at`, `liquidation_submitted_at`, `created_at`, `updated_at`) VALUES
(2, 31, 'GPR-2026-000004', 'confirmed', 18, 'QC M.I.C.E. Auditorium', 'Quezon City M.I.C.E. Center', 300, NULL, NULL, 0.00, 1, '2026-03-05', '13:00:00', '17:00:00', 30, '{\"1\": {\"name\": \"Engr. Juan Dela Cruz\", \"topic\": \"Energy Conservation\"}}', NULL, NULL, 1500.00, 1500.00, 'approved', NULL, NULL, '{\"1\": {\"item\": \"Jollibee\", \"amount\": \"1500\"}}', NULL, NULL, 1, 'LGU Admin', NULL, NULL, 'Facility assigned by LGU Public Facilities System', NULL, NULL, '2026-01-09 21:07:55', '2026-01-09 21:07:55', NULL, '2026-01-09 21:07:55', '2026-01-09 21:07:55'),
(3, 30, 'GPR-2026-000005', 'confirmed', 18, 'QC M.I.C.E. Auditorium', 'Quezon City M.I.C.E. Center', 300, NULL, NULL, 0.00, 1, '2026-02-20', '14:00:00', '16:30:00', 30, '{\"1\": {\"name\": \"Engr. Juan Dela Cruz\", \"topic\": \"Energy Conservation\"}}', '{\"1\": {\"name\": \"LCD Screen\", \"quantity\": \"1\"}, \"2\": {\"name\": \"Laptop\", \"quantity\": \"1\"}, \"3\": {\"name\": \"Chairs\", \"quantity\": \"50\"}}', NULL, 10000.00, 10000.00, 'approved', NULL, NULL, '{\"1\": {\"item\": \"Catering\", \"amount\": \"5000\"}, \"2\": {\"item\": \"Handbook\", \"amount\": \"1000\"}, \"3\": {\"item\": \"Spoke Person\", \"amount\": \"4000\"}}', NULL, NULL, 1, 'LGU Admin', NULL, NULL, 'Facility assigned by LGU Public Facilities System', NULL, NULL, '2026-01-09 22:41:02', '2026-01-09 22:41:02', NULL, '2026-01-09 22:41:02', '2026-01-09 22:41:02'),
(4, 29, 'GPR-2026-000006', 'confirmed', 17, 'M.I.C.E. Breakout Room 2', 'Quezon City M.I.C.E. Center, Floor 2', 40, NULL, NULL, 0.00, 1, '2026-02-15', '09:00:00', '12:00:00', 30, '{\"1\": {\"name\": \"Engr. Juan Dela Cruz\", \"topic\": \"Energy Conservation\"}}', '{\"1\": {\"name\": \"Projector\", \"quantity\": \"1\"}, \"2\": {\"name\": \"Laptop\", \"quantity\": \"1\"}, \"3\": {\"name\": \"Chairs\", \"quantity\": \"100\"}}', NULL, 6000.00, 6000.00, 'approved', NULL, NULL, '{\"1\": {\"item\": \"Catering\", \"amount\": \"5000\"}, \"2\": {\"item\": \"Handbook\", \"amount\": \"1000\"}}', NULL, NULL, 1, 'LGU Admin', NULL, NULL, 'Facility assigned by LGU Public Facilities System', NULL, NULL, '2026-01-09 22:48:45', '2026-01-09 22:48:45', NULL, '2026-01-09 22:48:45', '2026-01-09 22:48:45');

-- --------------------------------------------------------

--
-- Table structure for table `high_consumption_alerts`
--

CREATE TABLE `high_consumption_alerts` (
  `alert_id` int(11) NOT NULL,
  `reading_id` int(11) NOT NULL COMMENT 'References the specific reading that triggered the alert',
  `user_id` int(11) NOT NULL COMMENT 'User who had the high consumption',
  `assigned_staff_id` int(11) DEFAULT NULL COMMENT 'ID of the staff user assigned for follow-up',
  `alert_reason` varchar(255) NOT NULL,
  `alert_status` enum('pending','assigned','resolved') NOT NULL DEFAULT 'pending',
  `assigned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `lesson_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `source` varchar(255) DEFAULT 'Admin Created',
  `link_url` varchar(255) DEFAULT NULL COMMENT 'Optional link to the original source/article',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`lesson_id`, `title`, `content`, `source`, `link_url`, `created_at`) VALUES
(3, 'Energy Efficiency vs. Energy Conservation', 'Energy Efficiency vs. Energy Conservation \r\n\r\nEnergy Efficiency: Using technology or methods that perform the same service but consume less energy (e.g., using LED or CFL bulbs instead of incandescent). \r\nEnergy Conservation: Changing behavior to reduce energy use (e.g., turning off lights when not needed). \r\nThey note people sometimes confuse the two. For example, taking the stairs instead of the elevator is conservation, not efficiency. \r\n\r\nThe Rebound Effect \r\nEven when energy efficiency improves, total energy usage might not drop proportionally because people may compensate by using more (e.g., buying bigger houses or more appliances). This offsetting is called the rebound effect. \r\n\r\nEnergy Saving Tips \r\nSome of the suggested practices to save energy: \r\nTurn on lights only when needed. \r\nUse natural daylight during daytime, with proper window positioning. \r\nUnplug electronics / appliances when not in use. \r\nKeep appliances and the home clean to ensure they operate efficiently. ', 'Energy Literacy Ph', 'https://www.energyliteracyph.com/learning-materials', '2025-10-18 04:39:53'),
(4, 'Renewable Energy in the Philippines', 'Renewable Energy in the Philippines \r\n\r\n1. Key Concepts \r\nRenewable Energy (RE): Energy from sources that are naturally restored, such as solar, hydro, wind, geothermal, biomass, and ocean energy. \r\nBIG SHOW: An acronym summarizing the Philippines’ main renewable energy sources — Biomass, Geothermal, Solar, Hydro, Ocean, and Wind. \r\nNet Metering: A system that allows consumers who generate their own electricity (e.g., through solar panels) to sell excess power back to the grid. \r\nGreen Energy Option Program (GEOP): Enables consumers to choose renewable energy sources for their electricity supply. \r\n\r\n2. Main Lesson Content \r\nThe Need for Renewable Energy \r\nThe country faces high energy costs and growing demand for electricity. Renewable energy helps reduce reliance on imported fossil fuels and lowers greenhouse gas emissions. Clean energy promotes energy security and supports the fight against climate change. \r\n\r\nRenewable Energy Sources in the Philippines \r\nSolar Energy – Converts sunlight into electricity using solar panels. Wind Energy – Uses turbines to convert wind movement into electricity. Geothermal Energy – Harnesses heat from beneath the earth’s surface to generate electricity. Biomass Energy – Uses organic waste (e.g., rice husks, coconut shells) as fuel for producing energy. Ocean Energy – Captures power from ocean currents and tides, though still under development. \r\n\r\n3. Advantages of Going Renewable \r\nEnvironmental Benefits: Reduces pollution and greenhouse gases. \r\nEconomic Benefits: Lowers electricity bills and encourages local job creation in the energy sector. \r\nEnergy Independence and Long-Term Savings. \r\n\r\n4. Supporting Programs and Policies \r\nThe government supports renewable energy through programs like Net Metering and the Green Energy Option Program, making it easier for individuals and companies to adopt clean energy systems. These programs encourage participation from both households and businesses in the country’s renewable energy transition. ', 'Energy Literacy Ph', 'https://www.energyliteracyph.com/learning-materials', '2025-10-18 04:39:53');

-- --------------------------------------------------------

--
-- Table structure for table `my_fund_requests`
--

CREATE TABLE `my_fund_requests` (
  `id` int(11) NOT NULL,
  `government_id` int(11) NOT NULL COMMENT 'ID returned by Government API',
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `purpose` text NOT NULL,
  `logistics` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `feedback` text DEFAULT NULL,
  `seminar_info` text DEFAULT NULL,
  `seminar_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `my_fund_requests`
--

INSERT INTO `my_fund_requests` (`id`, `government_id`, `user_id`, `amount`, `purpose`, `logistics`, `status`, `created_at`, `feedback`, `seminar_info`, `seminar_image`) VALUES
(25, 26, 82, 50000.00, 'IEC Materials', 'Categories: A. Venue & Physical, B. Audio-Visual, C. Speakers/Services, F. IT Systems | Specifics: chairs, tables, extra computers', 'Approved', '2026-01-29 09:50:34', 'uki', 'Energy Conservation Awareness  (2025-10-23)', 'assets/seminar_img/10.jpg'),
(26, 27, 82, 10000.00, 'Tech Services', 'Categories: A. Venue & Physical, C. Speakers/Services, E. Food & Welfare | Specifics: lahat boss', 'Rejected', '2026-01-29 10:28:06', 'auq', 'Energy Conservation Awareness  (2025-10-23)', 'assets/seminar_img/10.jpg'),
(27, 28, 82, 135.00, 'Seminar Logistics', 'Categories: A. Venue & Physical | Specifics: FIANGE', 'Approved', '2026-01-29 11:21:49', 'dsf', 'Sampol (2026-01-29)', 'assets/seminar_img/9.jpg'),
(28, 31, 82, 55555.00, 'Tech Services', 'Categories: A. Venue & Physical, C. Speakers/Services, D. IEC Materials, E. Food & Welfare, F. IT Systems | Specifics: TEST TEST TEST', 'pending', '2026-01-31 04:49:01', NULL, 'Energy Conservation Awareness  (2025-10-23)', 'assets/seminar_img/10.jpg'),
(29, 32, 82, 1000.00, 'Tech Services', 'Categories: D. IEC Materials, F. IT Systems, L. Contingency | Specifics: TETETESTSTTST', 'Approved', '2026-01-31 04:49:48', 'qweqeqeq', 'Energy Conservation Awareness  (2025-10-23)', 'assets/seminar_img/10.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date_and_venue` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `official_source_url` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `date_and_venue`, `description`, `official_source_url`, `image_url`, `status`) VALUES
(1, 'Off-grid Renewable Energy Solutions: Driver of Agriculture Value Chain Development', '17 July 2019 – Taguig City', 'The seminar titled “Off-grid Renewable Energy Solutions: Driver of Agriculture Value Chain Development” was jointly organized by the Global Green Growth Institute (GGGI) and the Embassy of the Republic of Korea. The event brought together policymakers, investors, and energy sector experts to discuss the investment and policy challenges in delivering off-grid renewable energy solutions to rural communities, particularly those where 7 to 12 million Filipinos still lack access to reliable electricity. Topics focused on how renewable energy can strengthen the agricultural value chain, enhance productivity, create employment opportunities, and reduce poverty in off-grid areas. Speakers also highlighted the growing cost-effectiveness of renewable energy technologies such as solar and biomass in supporting inclusive growth and sustainable rural development. The session aimed to align public and private stakeholders toward accelerating investment in decentralized energy systems to drive both agricultural and economic transformation in the Philippines.', 'https://gggi.org/accelerating-off-grid-renewable-energy-investment-opportunities-in-the-philippines-gggi-and-embassy-of-the-republic-of-korea-co-organized-a-multi-stakeholder-seminar', 'uploads/news/news_68ed420137fc75.78750336.png', 'active'),
(2, 'Philippines Energy and Infrastructure Development Seminar', '21 February 2014 – Manila', 'The Philippines Energy and Infrastructure Development Seminar, held on February 21, 2014, in Manila, was organized by the Economic Research Institute for ASEAN and East Asia (ERIA). The seminar gathered high-level representatives from the Philippine and Japanese governments, private sector executives, and international organizations to exchange views on ASEAN power grid connectivity, infrastructure investment, and regional energy security. Discussions revolved around the need to strengthen bilateral cooperation in energy development, improve the efficiency of infrastructure projects, and promote sustainable growth in the energy sector. The event emphasized the strategic importance of enhancing regional integration in the ASEAN energy market and advancing shared initiatives toward a secure, sustainable, and inclusive energy future.', 'https://www.eria.org/news-and-views/philippines-energy-and-infrastructure-development-seminar/', 'uploads/news/news_68ed421979a1b7.23647563.png', 'active'),
(3, 'The 3rd Philippine Renewable Energy Conference 2025', 'August 7–8, 2025 – City of Dreams Manila', 'The 3rd Philippine Renewable Energy Conference 2025, hosted by e-vents.ph and powered by First Gen, brought together key stakeholders from the renewable energy sector to discuss the country’s clean energy transition. Supported by major sponsors such as San Miguel Global Power, SGV, Green Tiger Markets, Meralco MPower, and Berde Renewables, the event focused on the theme “Balancing Power Supply and Sustainability with Climate Resiliency and Energy Security.” The conference served as a platform to present the Philippines’ National Renewable Energy Plan (2025–2050), which aims for a 35% renewable energy share by 2030 and 50% by 2040. Expert panels tackled issues on offshore and onshore wind power, solar energy technologies, the role of Retail Electricity Suppliers (RES), and policies for strengthening the country’s energy resilience. The event underscored the importance of public-private collaboration in achieving the nation’s clean energy goals.', 'https://e-vents.ph/the-3rd-philippine-renewable-energy-conference-2025/?fbclid=IwY2xjawMr-xhleHRuA2FlbQIxMQABHlDgy0-WOFk51rkhJG6OWP2RPv8o_fXTuBYGMLXdca_iS6_n1JQboze1ZmEe_aem_Fc7AG7ImHEO2mEKrQ5yNow', 'uploads/news/news_68ed42387da759.29236355.png', 'active'),
(12, 'w', 'w', 'w', 'http://localhost/capri/admin-news.php', 'w', 'archived');

-- --------------------------------------------------------

--
-- Table structure for table `seminars`
--

CREATE TABLE `seminars` (
  `seminar_id` int(11) NOT NULL,
  `seminar_title` varchar(255) NOT NULL,
  `seminar_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `target_area` varchar(255) NOT NULL,
  `attachments_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `seminar_image_url` varchar(255) DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seminars`
--

INSERT INTO `seminars` (`seminar_id`, `seminar_title`, `seminar_date`, `start_time`, `end_time`, `description`, `location`, `target_area`, `attachments_path`, `created_at`, `seminar_image_url`, `is_archived`) VALUES
(1, 'w', '2026-01-31', '12:57:00', '00:54:00', 'AA', 'Multi-Purpose Hall', '0', NULL, '2026-01-31 04:57:21', 'assets/seminar_img/9.jpg', 0),
(2, 'zz', '2026-01-06', '12:57:00', '12:57:00', 'zz', 'Multi-Purpose Hall', '0', NULL, '2026-01-31 04:57:35', 'assets/seminar_img/1.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `seminar_joins`
--

CREATE TABLE `seminar_joins` (
  `join_id` int(11) NOT NULL,
  `seminar_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seminar_videos`
--

CREATE TABLE `seminar_videos` (
  `video_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `video_type` enum('youtube','upload') NOT NULL,
  `video_url` varchar(512) NOT NULL,
  `thumbnail_url` varchar(512) DEFAULT NULL,
  `upload_date` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seminar_videos`
--

INSERT INTO `seminar_videos` (`video_id`, `title`, `description`, `video_type`, `video_url`, `thumbnail_url`, `upload_date`, `updated_at`, `is_archived`, `admin_id`) VALUES
(26, 'Energy Efficiency 101', 'This is for educational purposes only', 'youtube', 'D11iFUw_ImU', 'https://img.youtube.com/vi/D11iFUw_ImU/mqdefault.jpg', '2025-10-22 22:55:29', NULL, 0, 82),
(27, 'Lecture 8: Buildings and Energy Efficiency', 'Copy by MIT OpenCourseWare', 'upload', 'uploads/videos/1761173872-68f9617048d94.mp4', 'uploads/thumbnails/1761173872-thumb-68f9617048d97.jpg', '2025-10-22 22:57:52', NULL, 0, 82);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `sex` varchar(50) NOT NULL,
  `civil_status` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile_picture_attachment` varchar(255) DEFAULT NULL,
  `cellphone_number` bigint(11) NOT NULL,
  `house_number` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `residency_status` enum('Owned','Rented') NOT NULL DEFAULT 'Owned',
  `proof_of_residency_type_name` varchar(150) NOT NULL,
  `religion` varchar(255) NOT NULL,
  `meralco_bill_attachment` varchar(255) DEFAULT NULL,
  `rented_proof_attachment` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_role` varchar(50) NOT NULL DEFAULT 'resident',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `password_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `bar_code` varchar(255) DEFAULT NULL,
  `is_verified` int(11) DEFAULT 0,
  `is_face_verified` tinyint(1) DEFAULT 0,
  `face_descriptor` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthdate`, `sex`, `civil_status`, `email`, `profile_picture_attachment`, `cellphone_number`, `house_number`, `street`, `area`, `residency_status`, `proof_of_residency_type_name`, `religion`, `meralco_bill_attachment`, `rented_proof_attachment`, `password`, `user_role`, `created_at`, `status`, `password_token`, `token_expiry`, `bar_code`, `is_verified`, `is_face_verified`, `face_descriptor`) VALUES
(82, 'Christian', 'Arnaldo', 'Cando', NULL, '2003-09-21', 'Female', 'Single', 'piyasigno@gmail.com', 'admin_profile_82_6967ce003eba6.png', 9085919898, 'Block 21', 'Lily', 'AREA 4', 'Owned', '', 'BORN AGAIN CHRISTIAN', '68efb548aabe8.jpg', NULL, '$2y$10$.M4HvdDqfCJpkbZnwtggxOw1cz5MrK6bPj.wIz0/BVxOddhlBm/TK', 'admin', '2025-09-02 17:57:11', 'approved', '27e1c18afafb348a38accd8355a74e7dd82d68b18c223ee1fe135a04c64f7b2c', '2025-09-09 17:25:59', 'BC25ADMIN', 0, 0, NULL),
(88, 'Jhales', 'Arizo', 'Santiago', NULL, '2002-12-16', 'Male', 'Single', 'jeylzuayanokoji@gmail.com', 'resident_profile_88_68f3ce632d473.png', 9303207238, 'Block 20', 'Daisy', 'AREA 1', 'Owned', '', 'BORN AGAIN CHRISTIAN', '68efb548aabe8.jpg', NULL, '$2y$10$e90goS3FEjIfeX2IRaWZMeG8kRlR5.w.WwI122cVk6kaie2B5Co1G', 'resident', '2025-09-05 14:05:02', 'approved', 'c98d32483e26e8d36f65b6b7af6f0107a51d0c79c698dab9cfb095f29f927001', '2025-10-24 06:07:03', 'BC25TRYA', 0, 0, NULL),
(2011, 'Edelyn', 'Bautista', 'Perez', NULL, '2006-01-04', 'Female', 'Single', 'sofiyaloreynnn21@gmail.com', 'staff_profile_2011_68f9667f20190.jpg', 9321312111, 'Block 31', 'Camia', '2', 'Owned', '', 'NO RELIGION', '68f8e00e48746.jpg', NULL, '$2y$10$UZEB5Vxt84J52.2oN3MtM.2ty0EzCkKrzOofxS6RI2jg3Aoj7Zm6C', 'staff', '2025-10-22 13:45:50', 'approved', NULL, NULL, 'BC25STAFF', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_electricity_readings`
--

CREATE TABLE `user_electricity_readings` (
  `reading_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `meter_reading` decimal(10,2) NOT NULL,
  `bill_amount` decimal(10,2) NOT NULL,
  `reading_date` date NOT NULL,
  `bill_file_path_1` varchar(255) DEFAULT NULL,
  `bill_file_path_2` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `correction_status` enum('original','corrected') NOT NULL DEFAULT 'original',
  `is_census` tinyint(1) DEFAULT 0,
  `is_archived` tinyint(1) DEFAULT 0,
  `household_members` int(11) DEFAULT 1,
  `primary_appliances` text DEFAULT NULL,
  `notes_etc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `video_views`
--

CREATE TABLE `video_views` (
  `view_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `view_timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `video_views`
--

INSERT INTO `video_views` (`view_id`, `video_id`, `user_id`, `view_timestamp`) VALUES
(27, 26, 88, '2025-10-23 17:16:42'),
(28, 27, 88, '2026-01-27 17:11:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `audit_assignments`
--
ALTER TABLE `audit_assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `resident_user_id` (`resident_user_id`),
  ADD KEY `staff_user_id` (`staff_user_id`);

--
-- Indexes for table `audit_feedback`
--
ALTER TABLE `audit_feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `assignment_id` (`assignment_id`),
  ADD KEY `resident_user_id` (`resident_user_id`);

--
-- Indexes for table `audit_findings`
--
ALTER TABLE `audit_findings`
  ADD PRIMARY KEY (`finding_id`),
  ADD KEY `assignment_id` (`assignment_id`);

--
-- Indexes for table `challenge_participation`
--
ALTER TABLE `challenge_participation`
  ADD PRIMARY KEY (`participation_id`),
  ADD KEY `challenge_id` (`challenge_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `correction_requests`
--
ALTER TABLE `correction_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `reading_id` (`reading_id`);

--
-- Indexes for table `energy_challenges`
--
ALTER TABLE `energy_challenges`
  ADD PRIMARY KEY (`challenge_id`);

--
-- Indexes for table `facility_booking_confirmations`
--
ALTER TABLE `facility_booking_confirmations`
  ADD PRIMARY KEY (`confirmation_id`),
  ADD KEY `idx_seminar_id` (`seminar_id`),
  ADD KEY `idx_status` (`request_status`),
  ADD KEY `idx_confirmed_date` (`confirmed_date`);

--
-- Indexes for table `high_consumption_alerts`
--
ALTER TABLE `high_consumption_alerts`
  ADD PRIMARY KEY (`alert_id`),
  ADD UNIQUE KEY `unique_reading_alert` (`reading_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `assigned_staff_id` (`assigned_staff_id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`lesson_id`);

--
-- Indexes for table `my_fund_requests`
--
ALTER TABLE `my_fund_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seminars`
--
ALTER TABLE `seminars`
  ADD PRIMARY KEY (`seminar_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_electricity_readings`
--
ALTER TABLE `user_electricity_readings`
  ADD PRIMARY KEY (`reading_id`),
  ADD KEY `user_id_idx` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `correction_requests`
--
ALTER TABLE `correction_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `my_fund_requests`
--
ALTER TABLE `my_fund_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `seminars`
--
ALTER TABLE `seminars`
  MODIFY `seminar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3019;

--
-- AUTO_INCREMENT for table `user_electricity_readings`
--
ALTER TABLE `user_electricity_readings`
  MODIFY `reading_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
