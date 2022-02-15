-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 31, 2021 at 01:19 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `glossary`
--

-- --------------------------------------------------------

--
-- Table structure for table `accreditation_providers`
--

CREATE TABLE `accreditation_providers` (
  `id` int(11) UNSIGNED NOT NULL,
  `provider_name` varchar(128) CHARACTER SET utf8mb4 DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `country` varchar(128) CHARACTER SET utf8mb4 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `accreditation_provider_accreditations`
--

CREATE TABLE `accreditation_provider_accreditations` (
  `id` int(11) UNSIGNED NOT NULL,
  `accreditation_provider_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `description` varchar(128) CHARACTER SET utf8mb4 DEFAULT NULL,
  `abbreviation` varchar(15) CHARACTER SET utf8mb4 DEFAULT NULL,
  `direction` enum('English to LOTE','Lote to English') CHARACTER SET utf8mb4 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `address_1` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `address_2` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `suburb` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `state` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `postcode` varchar(10) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `country` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `country_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `associations`
--

CREATE TABLE `associations` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `association_addresses`
--

CREATE TABLE `association_addresses` (
  `id` int(11) UNSIGNED NOT NULL,
  `association_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `address_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `association_requests`
--

CREATE TABLE `association_requests` (
  `id` int(11) UNSIGNED NOT NULL,
  `association_id` int(10) UNSIGNED DEFAULT NULL,
  `request_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `translator_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `file_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `association_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `country` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_addresses`
--

CREATE TABLE `client_addresses` (
  `id` int(11) UNSIGNED NOT NULL,
  `client_id` int(11) UNSIGNED DEFAULT NULL,
  `address_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_documents`
--

CREATE TABLE `client_documents` (
  `id` int(11) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED DEFAULT NULL,
  `document_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_email_address_link`
--

CREATE TABLE `client_email_address_link` (
  `id` int(11) UNSIGNED NOT NULL,
  `client_id` int(11) UNSIGNED DEFAULT NULL,
  `email_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cms_blocks`
--

CREATE TABLE `cms_blocks` (
  `id` int(11) UNSIGNED NOT NULL,
  `cms_system_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(128) NOT NULL DEFAULT '',
  `type` int(11) UNSIGNED DEFAULT NULL,
  `html` mediumtext DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cms_block_item_menu`
--

CREATE TABLE `cms_block_item_menu` (
  `id` int(11) UNSIGNED NOT NULL,
  `cms_system_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `html` text DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cms_block_links`
--

CREATE TABLE `cms_block_links` (
  `id` int(11) UNSIGNED NOT NULL,
  `parent_block_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `child_block_id` int(11) UNSIGNED DEFAULT NULL,
  `order` int(11) UNSIGNED DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cms_block_types`
--

CREATE TABLE `cms_block_types` (
  `id` int(11) UNSIGNED NOT NULL,
  `cms_system_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `type` varchar(128) DEFAULT NULL,
  `column_size` int(10) UNSIGNED DEFAULT NULL,
  `order` int(11) UNSIGNED DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cms_classes`
--

CREATE TABLE `cms_classes` (
  `id` int(11) UNSIGNED NOT NULL,
  `cms_system_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `class_name` varchar(128) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cms_images`
--

CREATE TABLE `cms_images` (
  `id` int(11) UNSIGNED NOT NULL,
  `cms_system_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `src` text DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cms_pages`
--

CREATE TABLE `cms_pages` (
  `id` int(11) UNSIGNED NOT NULL,
  `cms_system_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `cms_template_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(128) DEFAULT NULL,
  `page_content_block` int(10) UNSIGNED DEFAULT NULL,
  `default` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cms_page_content_link`
--

CREATE TABLE `cms_page_content_link` (
  `id` int(11) UNSIGNED NOT NULL,
  `cms_page_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `cms_template_tag_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `cms_block_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cms_placeholders`
--

CREATE TABLE `cms_placeholders` (
  `id` int(11) UNSIGNED NOT NULL,
  `cms_system_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(128) NOT NULL DEFAULT '',
  `placeholder` varchar(150) NOT NULL DEFAULT '',
  `created` datetime DEFAULT current_timestamp(),
  `modiffied` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cms_systems`
--

CREATE TABLE `cms_systems` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL DEFAULT '',
  `url` varchar(250) NOT NULL DEFAULT '',
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cms_templates`
--

CREATE TABLE `cms_templates` (
  `id` int(11) UNSIGNED NOT NULL,
  `cms_system_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(150) NOT NULL DEFAULT '',
  `header` int(11) UNSIGNED DEFAULT NULL,
  `content` int(11) UNSIGNED DEFAULT NULL,
  `footer` int(11) UNSIGNED DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cms_template_blocks`
--

CREATE TABLE `cms_template_blocks` (
  `id` int(11) UNSIGNED NOT NULL,
  `cms_system_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(150) NOT NULL DEFAULT '',
  `type` enum('Header','Content','Footer','Adds Left','Adds Right') DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cms_template_block_tags`
--

CREATE TABLE `cms_template_block_tags` (
  `id` int(11) UNSIGNED NOT NULL,
  `cms_template_block_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `cms_template_tag_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `order` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cms_template_content`
--

CREATE TABLE `cms_template_content` (
  `id` int(11) UNSIGNED NOT NULL,
  `cms_template_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `cms_template_block_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `order` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cms_template_tags`
--

CREATE TABLE `cms_template_tags` (
  `id` int(11) UNSIGNED NOT NULL,
  `cms_system_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `tag` varchar(128) NOT NULL DEFAULT '',
  `user_editable` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `page_content` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cms_template_tags_content_link`
--

CREATE TABLE `cms_template_tags_content_link` (
  `id` int(11) UNSIGNED NOT NULL,
  `parent_cms_template_tag_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `child_cms_template_tag_id` int(11) UNSIGNED DEFAULT NULL,
  `cms_block_id` int(11) UNSIGNED DEFAULT NULL,
  `order` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cms_template_tag_properties`
--

CREATE TABLE `cms_template_tag_properties` (
  `id` int(11) UNSIGNED NOT NULL,
  `cms_template_tag_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `property` varchar(128) NOT NULL DEFAULT '',
  `value` varchar(250) NOT NULL DEFAULT '',
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `code_translations`
--

CREATE TABLE `code_translations` (
  `id` int(11) UNSIGNED NOT NULL,
  `code_word_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `language_id` int(11) UNSIGNED DEFAULT NULL,
  `translation` varchar(250) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `code_words`
--

CREATE TABLE `code_words` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(75) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `term` tinytext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communications`
--

CREATE TABLE `communications` (
  `id` int(11) UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `read` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) UNSIGNED NOT NULL,
  `client_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(200) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `password` varchar(256) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mobile` varchar(25) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `otp` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `access_level` tinyint(3) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_addresses`
--

CREATE TABLE `contact_addresses` (
  `id` int(11) UNSIGNED NOT NULL,
  `contact_id` int(11) UNSIGNED DEFAULT NULL,
  `address_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_email_address_link`
--

CREATE TABLE `contact_email_address_link` (
  `id` int(11) UNSIGNED NOT NULL,
  `contact_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `email_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) UNSIGNED NOT NULL,
  `country` varchar(250) NOT NULL DEFAULT '',
  `iso_3166_1_alpha_2` char(2) DEFAULT NULL,
  `iso_3166_1_alpha_3` char(3) DEFAULT NULL,
  `common` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `csv_import`
--

CREATE TABLE `csv_import` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `csv_import_field_mapping`
--

CREATE TABLE `csv_import_field_mapping` (
  `id` int(11) UNSIGNED NOT NULL,
  `csv_import_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `mapped_from` varchar(200) DEFAULT NULL,
  `mapped_to` varchar(200) DEFAULT NULL,
  `function_call` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) UNSIGNED NOT NULL,
  `type_id` int(11) UNSIGNED DEFAULT NULL,
  `type` varchar(128) CHARACTER SET utf8mb4 DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_languages`
--

CREATE TABLE `document_languages` (
  `id` int(11) UNSIGNED NOT NULL,
  `document_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `language_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `document_name` varchar(256) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `document_language_link`
--

CREATE TABLE `document_language_link` (
  `id` int(11) UNSIGNED NOT NULL,
  `source_document_language` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `target_document_language` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_organisation`
--

CREATE TABLE `document_organisation` (
  `id` int(11) UNSIGNED NOT NULL,
  `document_id` int(11) UNSIGNED DEFAULT NULL,
  `organisation_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_tags`
--

CREATE TABLE `document_tags` (
  `id` int(11) UNSIGNED NOT NULL,
  `tag_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `document_language_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_terms`
--

CREATE TABLE `document_terms` (
  `id` int(11) UNSIGNED NOT NULL,
  `term_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `document_language_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_translators`
--

CREATE TABLE `document_translators` (
  `id` int(11) UNSIGNED NOT NULL,
  `translator_id` int(11) UNSIGNED DEFAULT NULL,
  `document_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_types`
--

CREATE TABLE `document_types` (
  `id` int(11) UNSIGNED NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `lookup` longtext COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`lookup`)),
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `document_url`
--

CREATE TABLE `document_url` (
  `id` int(11) UNSIGNED NOT NULL,
  `document_id` int(11) UNSIGNED NOT NULL,
  `url_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` int(11) UNSIGNED NOT NULL,
  `to` varchar(250) DEFAULT NULL,
  `subject` varchar(128) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `sent` tinyint(1) UNSIGNED DEFAULT NULL,
  `error` tinyint(1) UNSIGNED DEFAULT NULL,
  `errorMessage` varchar(250) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `email_addresses`
--

CREATE TABLE `email_addresses` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `expertise`
--

CREATE TABLE `expertise` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime_type` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ext` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id` int(11) UNSIGNED NOT NULL,
  `staff_id` int(11) UNSIGNED DEFAULT NULL,
  `action` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) UNSIGNED NOT NULL,
  `language` varchar(125) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `ISO_639_1_code` char(2) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `ISO_639_3_code` char(3) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `word_press_code` varchar(10) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `right_to_left` tinyint(1) UNSIGNED DEFAULT 0,
  `native_name` varchar(256) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `include_in_tiles` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `include_in_dropdown` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `language_link`
--

CREATE TABLE `language_link` (
  `id` int(11) UNSIGNED NOT NULL,
  `main_language_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `alternate_language_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `language_pair`
--

CREATE TABLE `language_pair` (
  `id` int(11) UNSIGNED NOT NULL,
  `translator_id` int(11) UNSIGNED DEFAULT NULL,
  `source_translator_language_id` int(11) UNSIGNED DEFAULT NULL,
  `target_translator_language_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `language_pair_accreditation`
--

CREATE TABLE `language_pair_accreditation` (
  `id` int(11) UNSIGNED NOT NULL,
  `language_pair_id` int(11) UNSIGNED DEFAULT NULL,
  `accreditation_provider_accreditations_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `language_pair_expertise`
--

CREATE TABLE `language_pair_expertise` (
  `id` int(11) UNSIGNED NOT NULL,
  `language_pair_id` int(11) UNSIGNED DEFAULT NULL,
  `expertise_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organisation`
--

CREATE TABLE `organisation` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organisation_address_link`
--

CREATE TABLE `organisation_address_link` (
  `id` int(11) UNSIGNED NOT NULL,
  `organisation_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `address_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organisation_contacts`
--

CREATE TABLE `organisation_contacts` (
  `id` int(11) UNSIGNED NOT NULL,
  `organisation_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `mobile` varchar(25) DEFAULT NULL,
  `default` tinyint(1) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organisation_contact_email_link`
--

CREATE TABLE `organisation_contact_email_link` (
  `id` int(11) UNSIGNED NOT NULL,
  `organisation_contact_id` int(11) UNSIGNED NOT NULL,
  `email_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organisation_email_link`
--

CREATE TABLE `organisation_email_link` (
  `id` int(11) UNSIGNED NOT NULL,
  `organisation_id` int(11) UNSIGNED NOT NULL,
  `email_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updasted_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organisation_glossaries`
--

CREATE TABLE `organisation_glossaries` (
  `id` int(11) UNSIGNED NOT NULL,
  `organisation_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(250) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organisation_glossaries_terms_link`
--

CREATE TABLE `organisation_glossaries_terms_link` (
  `id` int(11) UNSIGNED NOT NULL,
  `organisation_glossary_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `terms_link_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `english_definition` text DEFAULT NULL,
  `translated_definition` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('New','Processing','Approved','Disapproved') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `searches`
--

CREATE TABLE `searches` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip4_address` char(15) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `search_criteria`
--

CREATE TABLE `search_criteria` (
  `id` int(11) UNSIGNED NOT NULL,
  `search_id` int(11) UNSIGNED NOT NULL,
  `search_criteria` varchar(128) NOT NULL DEFAULT '',
  `entered_value` varchar(256) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `search_documents`
--

CREATE TABLE `search_documents` (
  `id` int(11) UNSIGNED NOT NULL,
  `search_id` int(11) UNSIGNED NOT NULL,
  `document_view_short_url_id` int(10) UNSIGNED DEFAULT NULL,
  `document_language_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `search_document_short_url_ids`
--

CREATE TABLE `search_document_short_url_ids` (
  `id` int(11) UNSIGNED NOT NULL,
  `search_document_id` int(11) UNSIGNED NOT NULL,
  `short_url_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `otp_template` int(11) UNSIGNED DEFAULT NULL,
  `broken_link_template` int(11) UNSIGNED DEFAULT NULL,
  `forgot_passwrod_template` int(11) UNSIGNED DEFAULT NULL,
  `new_account_template` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `email` varchar(250) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `password` varchar(256) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `mobile` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `otp` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) UNSIGNED NOT NULL,
  `tag` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tag_translations`
--

CREATE TABLE `tag_translations` (
  `id` int(11) UNSIGNED NOT NULL,
  `source_tag_id` int(11) UNSIGNED DEFAULT NULL,
  `target_tag_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id` int(11) UNSIGNED NOT NULL,
  `type` enum('Broken Link','OTP','Forgot Password','New Account') DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `template` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `template_variables`
--

CREATE TABLE `template_variables` (
  `id` int(11) UNSIGNED NOT NULL,
  `template_type` enum('Broken Link','OTP','Forgot Password','New Account') DEFAULT NULL,
  `variable` varchar(250) NOT NULL DEFAULT '',
  `display` varchar(128) NOT NULL DEFAULT '',
  `is_expression` tinyint(1) UNSIGNED DEFAULT 0,
  `opening` varchar(5) NOT NULL DEFAULT '{{',
  `closing` varchar(5) NOT NULL DEFAULT '}}'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `id` int(11) UNSIGNED NOT NULL,
  `language_id` int(11) UNSIGNED DEFAULT NULL,
  `text` varchar(200) CHARACTER SET utf8mb4 DEFAULT NULL,
  `definition` text CHARACTER SET utf8mb4 DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `terms_link`
--

CREATE TABLE `terms_link` (
  `id` int(11) UNSIGNED NOT NULL,
  `source_term_id` int(11) UNSIGNED DEFAULT NULL,
  `translated_term_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `term_tags`
--

CREATE TABLE `term_tags` (
  `id` int(11) UNSIGNED NOT NULL,
  `term_id` int(11) UNSIGNED DEFAULT NULL,
  `tag_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `term_translator`
--

CREATE TABLE `term_translator` (
  `id` int(11) UNSIGNED NOT NULL,
  `translator_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `term_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translators`
--

CREATE TABLE `translators` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `surname` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `email` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `mobile` varchar(25) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `otp` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `address_1` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `address_2` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `suburb` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `city` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `postcode-zip` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `country` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `machine_translator` varchar(250) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translator_accreditation_providers`
--

CREATE TABLE `translator_accreditation_providers` (
  `id` int(11) UNSIGNED NOT NULL,
  `translator_id` int(11) UNSIGNED DEFAULT NULL,
  `accreditation_provider_id` int(11) UNSIGNED DEFAULT NULL,
  `accreditation_number` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translator_addresses`
--

CREATE TABLE `translator_addresses` (
  `id` int(11) UNSIGNED NOT NULL,
  `translator_id` int(11) UNSIGNED DEFAULT NULL,
  `address_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translator_associations`
--

CREATE TABLE `translator_associations` (
  `id` int(11) UNSIGNED NOT NULL,
  `translator_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `association_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `member_number` varchar(128) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `translator_languages`
--

CREATE TABLE `translator_languages` (
  `id` int(11) UNSIGNED NOT NULL,
  `translator_id` int(11) UNSIGNED DEFAULT NULL,
  `language_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translator_messages`
--

CREATE TABLE `translator_messages` (
  `id` int(11) UNSIGNED NOT NULL,
  `communications_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `translator_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translator_message_request`
--

CREATE TABLE `translator_message_request` (
  `id` int(11) UNSIGNED NOT NULL,
  `translator_message_id` int(11) UNSIGNED DEFAULT NULL,
  `request_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translator_native_languages`
--

CREATE TABLE `translator_native_languages` (
  `id` int(11) UNSIGNED NOT NULL,
  `translator_id` int(11) UNSIGNED DEFAULT NULL,
  `language_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translator_profile_photos`
--

CREATE TABLE `translator_profile_photos` (
  `id` int(11) UNSIGNED NOT NULL,
  `translator_id` int(11) UNSIGNED DEFAULT NULL,
  `file_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `urls`
--

CREATE TABLE `urls` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(75) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_url_id` int(11) UNSIGNED DEFAULT NULL,
  `shorturl` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `urls`
--
DELIMITER $$
CREATE TRIGGER `urls_b4_insert` AFTER INSERT ON `urls` FOR EACH ROW BEGIN

	if( not empty( NEW.url ) )  then 
	
		insert into url_history set url = NEW.url, url_id = NEW.id ;
	
	end if ;


END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `urls_b4_update` BEFORE UPDATE ON `urls` FOR EACH ROW BEGIN

	if( not empty( NEW.url ) and NEW.url != OLD.url ) then 
	
		insert into url_history set url_id = NEW.id, url = NEW.url ;
	
	end if ;


END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `url_check`
--

CREATE TABLE `url_check` (
  `id` int(11) UNSIGNED NOT NULL,
  `url_id` int(11) UNSIGNED DEFAULT NULL,
  `last_run` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `pass` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `url_check_email`
--

CREATE TABLE `url_check_email` (
  `id` int(11) UNSIGNED NOT NULL,
  `url_check_id` int(11) UNSIGNED DEFAULT NULL,
  `email_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `url_history`
--

CREATE TABLE `url_history` (
  `id` int(11) UNSIGNED NOT NULL,
  `url_id` int(10) UNSIGNED NOT NULL,
  `url` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accreditation_providers`
--
ALTER TABLE `accreditation_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `accreditation_provider_accreditations`
--
ALTER TABLE `accreditation_provider_accreditations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_accreditation_provider_id` (`accreditation_provider_id`) USING BTREE;

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_unnamed` (`country_id`);

--
-- Indexes for table `associations`
--
ALTER TABLE `associations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `association_addresses`
--
ALTER TABLE `association_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `association_addresses_ibfk_1` (`address_id`),
  ADD KEY `association_addresses_ibfk_2` (`association_id`);

--
-- Indexes for table `association_requests`
--
ALTER TABLE `association_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `association_requests_ibfk_2` (`translator_id`),
  ADD KEY `association_requests_ibfk_3` (`file_id`),
  ADD KEY `association_requests_ibfk_4` (`request_id`),
  ADD KEY `association_requests_ibfk_1` (`association_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_addresses`
--
ALTER TABLE `client_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_addresses_ibfk_1` (`client_id`),
  ADD KEY `client_addresses_ibfk_2` (`address_id`);

--
-- Indexes for table `client_documents`
--
ALTER TABLE `client_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_documents_ibfk_1` (`document_id`),
  ADD KEY `client_documents_ibfk_2` (`client_id`);

--
-- Indexes for table `client_email_address_link`
--
ALTER TABLE `client_email_address_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_email_address_link_ibfk_2` (`email_id`),
  ADD KEY `client_email_address_link_ibfk_1` (`client_id`);

--
-- Indexes for table `cms_blocks`
--
ALTER TABLE `cms_blocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_blocks_ibfk_1` (`cms_system_id`);

--
-- Indexes for table `cms_block_item_menu`
--
ALTER TABLE `cms_block_item_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_block_links`
--
ALTER TABLE `cms_block_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_block_types`
--
ALTER TABLE `cms_block_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_block_types_ibfk_1` (`cms_system_id`);

--
-- Indexes for table `cms_classes`
--
ALTER TABLE `cms_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_classes_ibfk_1` (`cms_system_id`);

--
-- Indexes for table `cms_images`
--
ALTER TABLE `cms_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_images_ibfk_1` (`cms_system_id`);

--
-- Indexes for table `cms_pages`
--
ALTER TABLE `cms_pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_pages_ibfk_1` (`cms_system_id`),
  ADD KEY `cms_pages_ibfk_2` (`cms_template_id`);

--
-- Indexes for table `cms_page_content_link`
--
ALTER TABLE `cms_page_content_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_page_content_link_ibfk_1` (`cms_page_id`),
  ADD KEY `cms_page_content_link_ibfk_2` (`cms_block_id`),
  ADD KEY `cms_page_content_link_ibfk_3` (`cms_template_tag_id`);

--
-- Indexes for table `cms_placeholders`
--
ALTER TABLE `cms_placeholders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_placeholders_ibfk_1` (`cms_system_id`);

--
-- Indexes for table `cms_systems`
--
ALTER TABLE `cms_systems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_templates`
--
ALTER TABLE `cms_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_templates_ibfk_1` (`cms_system_id`);

--
-- Indexes for table `cms_template_blocks`
--
ALTER TABLE `cms_template_blocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_template_blocks_ibfk_1` (`cms_system_id`);

--
-- Indexes for table `cms_template_block_tags`
--
ALTER TABLE `cms_template_block_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_template_block_tags_ibfk_1` (`cms_template_block_id`),
  ADD KEY `cms_template_block_tags_ibfk_2` (`cms_template_tag_id`);

--
-- Indexes for table `cms_template_content`
--
ALTER TABLE `cms_template_content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_template_content_ibfk_1` (`cms_template_id`),
  ADD KEY `cms_template_content_ibfk_2` (`cms_template_block_id`);

--
-- Indexes for table `cms_template_tags`
--
ALTER TABLE `cms_template_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_template_tags_ibfk_1` (`cms_system_id`);

--
-- Indexes for table `cms_template_tags_content_link`
--
ALTER TABLE `cms_template_tags_content_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_template_tags_content_link_ibfk_1` (`parent_cms_template_tag_id`),
  ADD KEY `cms_template_tags_content_link_ibfk_2` (`child_cms_template_tag_id`),
  ADD KEY `cms_template_tags_content_link_ibfk_3` (`cms_block_id`);

--
-- Indexes for table `cms_template_tag_properties`
--
ALTER TABLE `cms_template_tag_properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cms_template_tag_properties_ibfk_1` (`cms_template_tag_id`);

--
-- Indexes for table `code_translations`
--
ALTER TABLE `code_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code_translations_ibfk_1` (`code_word_id`),
  ADD KEY `code_translations_ibfk_2` (`language_id`);

--
-- Indexes for table `code_words`
--
ALTER TABLE `code_words`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `communications`
--
ALTER TABLE `communications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_ibfk_1` (`client_id`);

--
-- Indexes for table `contact_addresses`
--
ALTER TABLE `contact_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_addresses_ibfk_2` (`address_id`),
  ADD KEY `contact_addresses_ibfk_1` (`contact_id`);

--
-- Indexes for table `contact_email_address_link`
--
ALTER TABLE `contact_email_address_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_email_address_link_ibfk_1` (`contact_id`),
  ADD KEY `contact_email_address_link_ibfk_2` (`email_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `csv_import`
--
ALTER TABLE `csv_import`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `csv_import_field_mapping`
--
ALTER TABLE `csv_import_field_mapping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `csv_import_field_mapping_ibfk_1` (`csv_import_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_type` (`type`) USING BTREE;

--
-- Indexes for table `document_languages`
--
ALTER TABLE `document_languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_document_id_language_id` (`document_id`,`language_id`) USING BTREE,
  ADD KEY `document_languages_ibfk_2` (`language_id`);

--
-- Indexes for table `document_language_link`
--
ALTER TABLE `document_language_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_language_link_ibfk_3` (`source_document_language`),
  ADD KEY `document_language_link_ibfk_4` (`target_document_language`);

--
-- Indexes for table `document_organisation`
--
ALTER TABLE `document_organisation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_organisation_ibfk_1` (`document_id`),
  ADD KEY `document_organisation_ibfk_2` (`organisation_id`);

--
-- Indexes for table `document_tags`
--
ALTER TABLE `document_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_tags_ibfk_2` (`tag_id`),
  ADD KEY `document_tags_ibfk_3` (`document_language_id`);

--
-- Indexes for table `document_terms`
--
ALTER TABLE `document_terms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_definition_id` (`term_id`) USING BTREE,
  ADD KEY `document_terms_ibfk_3` (`document_language_id`);

--
-- Indexes for table `document_translators`
--
ALTER TABLE `document_translators`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_translator_id` (`translator_id`) USING BTREE,
  ADD KEY `idx_document_id` (`document_id`) USING BTREE;

--
-- Indexes for table `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_url`
--
ALTER TABLE `document_url`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_url_ibfk_1` (`document_id`),
  ADD KEY `document_url_ibfk_2` (`url_id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_addresses`
--
ALTER TABLE `email_addresses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_email` (`email`) USING BTREE;

--
-- Indexes for table `expertise`
--
ALTER TABLE `expertise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `history_ibfk_1` (`staff_id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_ISO-639-3-code` (`ISO_639_3_code`) USING BTREE,
  ADD UNIQUE KEY `idx_language` (`language`) USING BTREE,
  ADD UNIQUE KEY `idx_ISO_639_1_code` (`ISO_639_1_code`) USING BTREE,
  ADD UNIQUE KEY `idx_word_press_code` (`word_press_code`) USING BTREE;

--
-- Indexes for table `language_link`
--
ALTER TABLE `language_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `language_link_ibfk_1` (`main_language_id`),
  ADD KEY `language_link_ibfk_2` (`alternate_language_id`);

--
-- Indexes for table `language_pair`
--
ALTER TABLE `language_pair`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_source_language_id` (`source_translator_language_id`) USING BTREE,
  ADD KEY `idx_target_language_id` (`target_translator_language_id`) USING BTREE;

--
-- Indexes for table `language_pair_accreditation`
--
ALTER TABLE `language_pair_accreditation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_accreditation_provider_accreditations_id_language_pair_id` (`accreditation_provider_accreditations_id`,`language_pair_id`) USING BTREE,
  ADD KEY `idx_accreditation_provider_accreditations_id` (`accreditation_provider_accreditations_id`) USING BTREE,
  ADD KEY `idx_language_pair_id` (`language_pair_id`) USING BTREE;

--
-- Indexes for table `language_pair_expertise`
--
ALTER TABLE `language_pair_expertise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_language_pair_id` (`language_pair_id`) USING BTREE,
  ADD KEY `idx_expertise_id` (`expertise_id`) USING BTREE;

--
-- Indexes for table `organisation`
--
ALTER TABLE `organisation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organisation_address_link`
--
ALTER TABLE `organisation_address_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organisation_address_link_ibfk_2` (`address_id`),
  ADD KEY `organisation_address_link_ibfk_1` (`organisation_id`);

--
-- Indexes for table `organisation_contacts`
--
ALTER TABLE `organisation_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organisation_contacts_ibfk_1` (`organisation_id`);

--
-- Indexes for table `organisation_contact_email_link`
--
ALTER TABLE `organisation_contact_email_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organisation_contact_email_link_ibfk_2` (`email_id`),
  ADD KEY `organisation_contact_email_link_ibfk_1` (`organisation_contact_id`);

--
-- Indexes for table `organisation_email_link`
--
ALTER TABLE `organisation_email_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organisation_email_link_ibfk_2` (`email_id`),
  ADD KEY `organisation_email_link_ibfk_1` (`organisation_id`);

--
-- Indexes for table `organisation_glossaries`
--
ALTER TABLE `organisation_glossaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organisation_glossaries_ibfk_1` (`organisation_id`);

--
-- Indexes for table `organisation_glossaries_terms_link`
--
ALTER TABLE `organisation_glossaries_terms_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organisation_glossaries_terms_link_ibfk_1` (`organisation_glossary_id`),
  ADD KEY `organisation_glossaries_terms_link_ibfk_2` (`terms_link_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `searches`
--
ALTER TABLE `searches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `search_criteria`
--
ALTER TABLE `search_criteria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `saerch_criteria_ibfk_1` (`search_id`);

--
-- Indexes for table `search_documents`
--
ALTER TABLE `search_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `search_documents_ibfk_1` (`search_id`),
  ADD KEY `search_documents_ibfk_2` (`document_language_id`);

--
-- Indexes for table `search_document_short_url_ids`
--
ALTER TABLE `search_document_short_url_ids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `search_document_short_url_ids_ibfk_1` (`search_document_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tag` (`tag`) USING BTREE,
  ADD KEY `tags_ibfk_1` (`language_id`);

--
-- Indexes for table `tag_translations`
--
ALTER TABLE `tag_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tag_translations_ibfk_1` (`source_tag_id`),
  ADD KEY `tag_translations_ibfk_2` (`target_tag_id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `template_variables`
--
ALTER TABLE `template_variables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_template_type` (`template_type`) USING BTREE,
  ADD KEY `idx_display` (`display`) USING BTREE;
ALTER TABLE `template_variables` ADD FULLTEXT KEY `idx_variable` (`variable`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_english_text` (`text`,`language_id`) USING BTREE,
  ADD KEY `terms_ibfk_1` (`language_id`);

--
-- Indexes for table `terms_link`
--
ALTER TABLE `terms_link`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_source_term_id` (`source_term_id`) USING BTREE,
  ADD KEY `idx_translatted_term_id` (`translated_term_id`) USING BTREE;

--
-- Indexes for table `term_tags`
--
ALTER TABLE `term_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `term_tags_ibfk_1` (`tag_id`),
  ADD KEY `term_tags_ibfk_2` (`term_id`);

--
-- Indexes for table `term_translator`
--
ALTER TABLE `term_translator`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_term_id` (`term_id`) USING BTREE,
  ADD KEY `idx_translator_id` (`translator_id`) USING BTREE;

--
-- Indexes for table `translators`
--
ALTER TABLE `translators`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `translator_accreditation_providers`
--
ALTER TABLE `translator_accreditation_providers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_translator_id_accreditation_provider_id` (`translator_id`,`accreditation_provider_id`) USING BTREE,
  ADD KEY `idx_accreditation_provider_id` (`accreditation_provider_id`) USING BTREE,
  ADD KEY `idx_translator_id` (`translator_id`) USING BTREE;

--
-- Indexes for table `translator_addresses`
--
ALTER TABLE `translator_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `translator_addresses_ibfk_1` (`translator_id`),
  ADD KEY `translator_addresses_ibfk_2` (`address_id`);

--
-- Indexes for table `translator_associations`
--
ALTER TABLE `translator_associations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `translator_associations_ibfk_1` (`association_id`),
  ADD KEY `translator_associations_ibfk_2` (`translator_id`);

--
-- Indexes for table `translator_languages`
--
ALTER TABLE `translator_languages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_language_id` (`language_id`) USING BTREE,
  ADD KEY `idx_translator_id` (`translator_id`) USING BTREE;

--
-- Indexes for table `translator_messages`
--
ALTER TABLE `translator_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `translator_messages_ibfk_1` (`translator_id`),
  ADD KEY `translator_messages_ibfk_2` (`communications_id`);

--
-- Indexes for table `translator_message_request`
--
ALTER TABLE `translator_message_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `translator_message_request_ibfk_1` (`translator_message_id`),
  ADD KEY `translator_message_request_ibfk_2` (`request_id`);

--
-- Indexes for table `translator_native_languages`
--
ALTER TABLE `translator_native_languages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_language_id` (`language_id`) USING BTREE,
  ADD KEY `translator_native_languages_ibfk_2` (`translator_id`);

--
-- Indexes for table `translator_profile_photos`
--
ALTER TABLE `translator_profile_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `translator_profile_photos_ibfk_2` (`file_id`),
  ADD KEY `translator_profile_photos_ibfk_1` (`translator_id`);

--
-- Indexes for table `urls`
--
ALTER TABLE `urls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_url` (`url`(256)) USING BTREE;

--
-- Indexes for table `url_check`
--
ALTER TABLE `url_check`
  ADD PRIMARY KEY (`id`),
  ADD KEY `url_check_ibfk_2` (`url_id`);

--
-- Indexes for table `url_check_email`
--
ALTER TABLE `url_check_email`
  ADD PRIMARY KEY (`id`),
  ADD KEY `url_check_email_ibfk_2` (`email_id`),
  ADD KEY `url_check_email_ibfk_1` (`url_check_id`);

--
-- Indexes for table `url_history`
--
ALTER TABLE `url_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `url_history_ibfk_1` (`url_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accreditation_providers`
--
ALTER TABLE `accreditation_providers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `accreditation_provider_accreditations`
--
ALTER TABLE `accreditation_provider_accreditations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `associations`
--
ALTER TABLE `associations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `association_addresses`
--
ALTER TABLE `association_addresses`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `association_requests`
--
ALTER TABLE `association_requests`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `client_addresses`
--
ALTER TABLE `client_addresses`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `client_documents`
--
ALTER TABLE `client_documents`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `client_email_address_link`
--
ALTER TABLE `client_email_address_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_blocks`
--
ALTER TABLE `cms_blocks`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `cms_block_item_menu`
--
ALTER TABLE `cms_block_item_menu`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cms_block_links`
--
ALTER TABLE `cms_block_links`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cms_block_types`
--
ALTER TABLE `cms_block_types`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cms_classes`
--
ALTER TABLE `cms_classes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cms_images`
--
ALTER TABLE `cms_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cms_pages`
--
ALTER TABLE `cms_pages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cms_page_content_link`
--
ALTER TABLE `cms_page_content_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cms_placeholders`
--
ALTER TABLE `cms_placeholders`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cms_systems`
--
ALTER TABLE `cms_systems`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cms_templates`
--
ALTER TABLE `cms_templates`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cms_template_blocks`
--
ALTER TABLE `cms_template_blocks`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `cms_template_block_tags`
--
ALTER TABLE `cms_template_block_tags`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cms_template_content`
--
ALTER TABLE `cms_template_content`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cms_template_tags`
--
ALTER TABLE `cms_template_tags`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `cms_template_tags_content_link`
--
ALTER TABLE `cms_template_tags_content_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `cms_template_tag_properties`
--
ALTER TABLE `cms_template_tag_properties`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `code_translations`
--
ALTER TABLE `code_translations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2354;

--
-- AUTO_INCREMENT for table `code_words`
--
ALTER TABLE `code_words`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `communications`
--
ALTER TABLE `communications`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contact_addresses`
--
ALTER TABLE `contact_addresses`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_email_address_link`
--
ALTER TABLE `contact_email_address_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `csv_import`
--
ALTER TABLE `csv_import`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `csv_import_field_mapping`
--
ALTER TABLE `csv_import_field_mapping`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32364;

--
-- AUTO_INCREMENT for table `document_languages`
--
ALTER TABLE `document_languages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35251;

--
-- AUTO_INCREMENT for table `document_language_link`
--
ALTER TABLE `document_language_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30022;

--
-- AUTO_INCREMENT for table `document_organisation`
--
ALTER TABLE `document_organisation`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33310;

--
-- AUTO_INCREMENT for table `document_tags`
--
ALTER TABLE `document_tags`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14769;

--
-- AUTO_INCREMENT for table `document_terms`
--
ALTER TABLE `document_terms`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12215;

--
-- AUTO_INCREMENT for table `document_translators`
--
ALTER TABLE `document_translators`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `document_types`
--
ALTER TABLE `document_types`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `document_url`
--
ALTER TABLE `document_url`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32595;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `email_addresses`
--
ALTER TABLE `email_addresses`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `expertise`
--
ALTER TABLE `expertise`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=409;

--
-- AUTO_INCREMENT for table `language_link`
--
ALTER TABLE `language_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `language_pair`
--
ALTER TABLE `language_pair`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `language_pair_accreditation`
--
ALTER TABLE `language_pair_accreditation`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `language_pair_expertise`
--
ALTER TABLE `language_pair_expertise`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `organisation`
--
ALTER TABLE `organisation`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT for table `organisation_address_link`
--
ALTER TABLE `organisation_address_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT for table `organisation_contacts`
--
ALTER TABLE `organisation_contacts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `organisation_contact_email_link`
--
ALTER TABLE `organisation_contact_email_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `organisation_email_link`
--
ALTER TABLE `organisation_email_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organisation_glossaries`
--
ALTER TABLE `organisation_glossaries`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organisation_glossaries_terms_link`
--
ALTER TABLE `organisation_glossaries_terms_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `searches`
--
ALTER TABLE `searches`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5523;

--
-- AUTO_INCREMENT for table `search_criteria`
--
ALTER TABLE `search_criteria`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7454;

--
-- AUTO_INCREMENT for table `search_documents`
--
ALTER TABLE `search_documents`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=850;

--
-- AUTO_INCREMENT for table `search_document_short_url_ids`
--
ALTER TABLE `search_document_short_url_ids`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=765;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16283;

--
-- AUTO_INCREMENT for table `tag_translations`
--
ALTER TABLE `tag_translations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `template_variables`
--
ALTER TABLE `template_variables`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4360;

--
-- AUTO_INCREMENT for table `terms_link`
--
ALTER TABLE `terms_link`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `term_tags`
--
ALTER TABLE `term_tags`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29242;

--
-- AUTO_INCREMENT for table `term_translator`
--
ALTER TABLE `term_translator`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `translators`
--
ALTER TABLE `translators`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `translator_accreditation_providers`
--
ALTER TABLE `translator_accreditation_providers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `translator_addresses`
--
ALTER TABLE `translator_addresses`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `translator_associations`
--
ALTER TABLE `translator_associations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `translator_languages`
--
ALTER TABLE `translator_languages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `translator_messages`
--
ALTER TABLE `translator_messages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `translator_message_request`
--
ALTER TABLE `translator_message_request`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `translator_native_languages`
--
ALTER TABLE `translator_native_languages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `translator_profile_photos`
--
ALTER TABLE `translator_profile_photos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `urls`
--
ALTER TABLE `urls`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30815;

--
-- AUTO_INCREMENT for table `url_check`
--
ALTER TABLE `url_check`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29133;

--
-- AUTO_INCREMENT for table `url_check_email`
--
ALTER TABLE `url_check_email`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `url_history`
--
ALTER TABLE `url_history`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30929;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accreditation_provider_accreditations`
--
ALTER TABLE `accreditation_provider_accreditations`
  ADD CONSTRAINT `accreditation_provider_accreditations_ibfk_1` FOREIGN KEY (`accreditation_provider_id`) REFERENCES `accreditation_providers` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `fk_unnamed` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `association_addresses`
--
ALTER TABLE `association_addresses`
  ADD CONSTRAINT `association_addresses_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `association_addresses_ibfk_2` FOREIGN KEY (`association_id`) REFERENCES `associations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `association_requests`
--
ALTER TABLE `association_requests`
  ADD CONSTRAINT `association_requests_ibfk_1` FOREIGN KEY (`association_id`) REFERENCES `associations` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `association_requests_ibfk_2` FOREIGN KEY (`translator_id`) REFERENCES `translators` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `association_requests_ibfk_3` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `association_requests_ibfk_4` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `client_addresses`
--
ALTER TABLE `client_addresses`
  ADD CONSTRAINT `client_addresses_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `client_addresses_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `client_documents`
--
ALTER TABLE `client_documents`
  ADD CONSTRAINT `client_documents_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `client_documents_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `client_email_address_link`
--
ALTER TABLE `client_email_address_link`
  ADD CONSTRAINT `client_email_address_link_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `client_email_address_link_ibfk_2` FOREIGN KEY (`email_id`) REFERENCES `email_addresses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cms_blocks`
--
ALTER TABLE `cms_blocks`
  ADD CONSTRAINT `cms_blocks_ibfk_1` FOREIGN KEY (`cms_system_id`) REFERENCES `cms_systems` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cms_block_types`
--
ALTER TABLE `cms_block_types`
  ADD CONSTRAINT `cms_block_types_ibfk_1` FOREIGN KEY (`cms_system_id`) REFERENCES `cms_systems` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cms_classes`
--
ALTER TABLE `cms_classes`
  ADD CONSTRAINT `cms_classes_ibfk_1` FOREIGN KEY (`cms_system_id`) REFERENCES `cms_systems` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cms_images`
--
ALTER TABLE `cms_images`
  ADD CONSTRAINT `cms_images_ibfk_1` FOREIGN KEY (`cms_system_id`) REFERENCES `cms_systems` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cms_pages`
--
ALTER TABLE `cms_pages`
  ADD CONSTRAINT `cms_pages_ibfk_1` FOREIGN KEY (`cms_system_id`) REFERENCES `cms_systems` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cms_pages_ibfk_2` FOREIGN KEY (`cms_template_id`) REFERENCES `cms_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cms_page_content_link`
--
ALTER TABLE `cms_page_content_link`
  ADD CONSTRAINT `cms_page_content_link_ibfk_1` FOREIGN KEY (`cms_page_id`) REFERENCES `cms_pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cms_page_content_link_ibfk_2` FOREIGN KEY (`cms_block_id`) REFERENCES `cms_blocks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cms_page_content_link_ibfk_3` FOREIGN KEY (`cms_template_tag_id`) REFERENCES `cms_template_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cms_placeholders`
--
ALTER TABLE `cms_placeholders`
  ADD CONSTRAINT `cms_placeholders_ibfk_1` FOREIGN KEY (`cms_system_id`) REFERENCES `cms_systems` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `cms_templates`
--
ALTER TABLE `cms_templates`
  ADD CONSTRAINT `cms_templates_ibfk_1` FOREIGN KEY (`cms_system_id`) REFERENCES `cms_systems` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `cms_template_blocks`
--
ALTER TABLE `cms_template_blocks`
  ADD CONSTRAINT `cms_template_blocks_ibfk_1` FOREIGN KEY (`cms_system_id`) REFERENCES `cms_systems` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `cms_template_block_tags`
--
ALTER TABLE `cms_template_block_tags`
  ADD CONSTRAINT `cms_template_block_tags_ibfk_1` FOREIGN KEY (`cms_template_block_id`) REFERENCES `cms_template_blocks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cms_template_block_tags_ibfk_2` FOREIGN KEY (`cms_template_tag_id`) REFERENCES `cms_template_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cms_template_content`
--
ALTER TABLE `cms_template_content`
  ADD CONSTRAINT `cms_template_content_ibfk_1` FOREIGN KEY (`cms_template_id`) REFERENCES `cms_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cms_template_content_ibfk_2` FOREIGN KEY (`cms_template_block_id`) REFERENCES `cms_template_blocks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cms_template_tags`
--
ALTER TABLE `cms_template_tags`
  ADD CONSTRAINT `cms_template_tags_ibfk_1` FOREIGN KEY (`cms_system_id`) REFERENCES `cms_systems` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cms_template_tags_content_link`
--
ALTER TABLE `cms_template_tags_content_link`
  ADD CONSTRAINT `cms_template_tags_content_link_ibfk_1` FOREIGN KEY (`parent_cms_template_tag_id`) REFERENCES `cms_template_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cms_template_tags_content_link_ibfk_2` FOREIGN KEY (`child_cms_template_tag_id`) REFERENCES `cms_template_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cms_template_tags_content_link_ibfk_3` FOREIGN KEY (`cms_block_id`) REFERENCES `cms_blocks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cms_template_tag_properties`
--
ALTER TABLE `cms_template_tag_properties`
  ADD CONSTRAINT `cms_template_tag_properties_ibfk_1` FOREIGN KEY (`cms_template_tag_id`) REFERENCES `cms_template_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `code_translations`
--
ALTER TABLE `code_translations`
  ADD CONSTRAINT `code_translations_ibfk_1` FOREIGN KEY (`code_word_id`) REFERENCES `code_words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `code_translations_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contact_addresses`
--
ALTER TABLE `contact_addresses`
  ADD CONSTRAINT `contact_addresses_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contact_addresses_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contact_email_address_link`
--
ALTER TABLE `contact_email_address_link`
  ADD CONSTRAINT `contact_email_address_link_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contact_email_address_link_ibfk_2` FOREIGN KEY (`email_id`) REFERENCES `email_addresses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `csv_import_field_mapping`
--
ALTER TABLE `csv_import_field_mapping`
  ADD CONSTRAINT `csv_import_field_mapping_ibfk_1` FOREIGN KEY (`csv_import_id`) REFERENCES `csv_import` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `document_languages`
--
ALTER TABLE `document_languages`
  ADD CONSTRAINT `document_languages_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `document_languages_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `document_language_link`
--
ALTER TABLE `document_language_link`
  ADD CONSTRAINT `document_language_link_ibfk_3` FOREIGN KEY (`source_document_language`) REFERENCES `document_languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `document_language_link_ibfk_4` FOREIGN KEY (`target_document_language`) REFERENCES `document_languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `document_organisation`
--
ALTER TABLE `document_organisation`
  ADD CONSTRAINT `document_organisation_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `document_organisation_ibfk_2` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `document_tags`
--
ALTER TABLE `document_tags`
  ADD CONSTRAINT `document_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `document_tags_ibfk_3` FOREIGN KEY (`document_language_id`) REFERENCES `document_languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `document_terms`
--
ALTER TABLE `document_terms`
  ADD CONSTRAINT `document_terms_ibfk_2` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `document_terms_ibfk_3` FOREIGN KEY (`document_language_id`) REFERENCES `document_languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `document_translators`
--
ALTER TABLE `document_translators`
  ADD CONSTRAINT `document_translators_ibfk_1` FOREIGN KEY (`translator_id`) REFERENCES `translators` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `document_translators_ibfk_2` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `document_url`
--
ALTER TABLE `document_url`
  ADD CONSTRAINT `document_url_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `document_url_ibfk_2` FOREIGN KEY (`url_id`) REFERENCES `urls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `history_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `language_link`
--
ALTER TABLE `language_link`
  ADD CONSTRAINT `language_link_ibfk_1` FOREIGN KEY (`main_language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `language_link_ibfk_2` FOREIGN KEY (`alternate_language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `language_pair`
--
ALTER TABLE `language_pair`
  ADD CONSTRAINT `language_pair_ibfk_1` FOREIGN KEY (`source_translator_language_id`) REFERENCES `translator_languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `language_pair_ibfk_2` FOREIGN KEY (`target_translator_language_id`) REFERENCES `translator_languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `language_pair_accreditation`
--
ALTER TABLE `language_pair_accreditation`
  ADD CONSTRAINT `language_pair_accreditation_ibfk_1` FOREIGN KEY (`language_pair_id`) REFERENCES `language_pair` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `language_pair_accreditation_ibfk_2` FOREIGN KEY (`accreditation_provider_accreditations_id`) REFERENCES `accreditation_provider_accreditations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `language_pair_expertise`
--
ALTER TABLE `language_pair_expertise`
  ADD CONSTRAINT `language_pair_expertise_ibfk_1` FOREIGN KEY (`language_pair_id`) REFERENCES `language_pair` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `language_pair_expertise_ibfk_2` FOREIGN KEY (`expertise_id`) REFERENCES `expertise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `organisation_address_link`
--
ALTER TABLE `organisation_address_link`
  ADD CONSTRAINT `organisation_address_link_ibfk_1` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `organisation_address_link_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `organisation_contacts`
--
ALTER TABLE `organisation_contacts`
  ADD CONSTRAINT `organisation_contacts_ibfk_1` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `organisation_contact_email_link`
--
ALTER TABLE `organisation_contact_email_link`
  ADD CONSTRAINT `organisation_contact_email_link_ibfk_1` FOREIGN KEY (`organisation_contact_id`) REFERENCES `organisation_contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `organisation_contact_email_link_ibfk_2` FOREIGN KEY (`email_id`) REFERENCES `email_addresses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `organisation_email_link`
--
ALTER TABLE `organisation_email_link`
  ADD CONSTRAINT `organisation_email_link_ibfk_1` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `organisation_email_link_ibfk_2` FOREIGN KEY (`email_id`) REFERENCES `email_addresses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `organisation_glossaries`
--
ALTER TABLE `organisation_glossaries`
  ADD CONSTRAINT `organisation_glossaries_ibfk_1` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `organisation_glossaries_terms_link`
--
ALTER TABLE `organisation_glossaries_terms_link`
  ADD CONSTRAINT `organisation_glossaries_terms_link_ibfk_1` FOREIGN KEY (`organisation_glossary_id`) REFERENCES `organisation_glossaries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `organisation_glossaries_terms_link_ibfk_2` FOREIGN KEY (`terms_link_id`) REFERENCES `terms_link` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `search_criteria`
--
ALTER TABLE `search_criteria`
  ADD CONSTRAINT `search_criteria_ibfk_1` FOREIGN KEY (`search_id`) REFERENCES `searches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `search_documents`
--
ALTER TABLE `search_documents`
  ADD CONSTRAINT `search_documents_ibfk_1` FOREIGN KEY (`search_id`) REFERENCES `searches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `search_documents_ibfk_2` FOREIGN KEY (`document_language_id`) REFERENCES `document_languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `search_document_short_url_ids`
--
ALTER TABLE `search_document_short_url_ids`
  ADD CONSTRAINT `search_document_short_url_ids_ibfk_1` FOREIGN KEY (`search_document_id`) REFERENCES `search_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `tags_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tag_translations`
--
ALTER TABLE `tag_translations`
  ADD CONSTRAINT `tag_translations_ibfk_1` FOREIGN KEY (`source_tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tag_translations_ibfk_2` FOREIGN KEY (`target_tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `terms`
--
ALTER TABLE `terms`
  ADD CONSTRAINT `terms_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `terms_link`
--
ALTER TABLE `terms_link`
  ADD CONSTRAINT `terms_link_ibfk_1` FOREIGN KEY (`source_term_id`) REFERENCES `terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `terms_link_ibfk_2` FOREIGN KEY (`translated_term_id`) REFERENCES `terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `term_tags`
--
ALTER TABLE `term_tags`
  ADD CONSTRAINT `term_tags_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `term_tags_ibfk_2` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `term_translator`
--
ALTER TABLE `term_translator`
  ADD CONSTRAINT `term_translator_ibfk_1` FOREIGN KEY (`translator_id`) REFERENCES `translators` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `term_translator_ibfk_2` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `translator_accreditation_providers`
--
ALTER TABLE `translator_accreditation_providers`
  ADD CONSTRAINT `translator_accreditation_providers_ibfk_1` FOREIGN KEY (`translator_id`) REFERENCES `translators` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `translator_accreditation_providers_ibfk_2` FOREIGN KEY (`accreditation_provider_id`) REFERENCES `accreditation_providers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `translator_addresses`
--
ALTER TABLE `translator_addresses`
  ADD CONSTRAINT `translator_addresses_ibfk_1` FOREIGN KEY (`translator_id`) REFERENCES `translators` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `translator_addresses_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `translator_associations`
--
ALTER TABLE `translator_associations`
  ADD CONSTRAINT `translator_associations_ibfk_1` FOREIGN KEY (`association_id`) REFERENCES `associations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `translator_associations_ibfk_2` FOREIGN KEY (`translator_id`) REFERENCES `translators` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `translator_languages`
--
ALTER TABLE `translator_languages`
  ADD CONSTRAINT `translator_languages_ibfk_1` FOREIGN KEY (`translator_id`) REFERENCES `translators` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `translator_languages_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `translator_messages`
--
ALTER TABLE `translator_messages`
  ADD CONSTRAINT `translator_messages_ibfk_1` FOREIGN KEY (`translator_id`) REFERENCES `translators` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `translator_messages_ibfk_2` FOREIGN KEY (`communications_id`) REFERENCES `communications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `translator_message_request`
--
ALTER TABLE `translator_message_request`
  ADD CONSTRAINT `translator_message_request_ibfk_1` FOREIGN KEY (`translator_message_id`) REFERENCES `translator_messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `translator_message_request_ibfk_2` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `translator_native_languages`
--
ALTER TABLE `translator_native_languages`
  ADD CONSTRAINT `translator_native_languages_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `translator_native_languages_ibfk_2` FOREIGN KEY (`translator_id`) REFERENCES `translators` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `translator_profile_photos`
--
ALTER TABLE `translator_profile_photos`
  ADD CONSTRAINT `translator_profile_photos_ibfk_1` FOREIGN KEY (`translator_id`) REFERENCES `translators` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `translator_profile_photos_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `url_check`
--
ALTER TABLE `url_check`
  ADD CONSTRAINT `url_check_ibfk_2` FOREIGN KEY (`url_id`) REFERENCES `urls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `url_check_email`
--
ALTER TABLE `url_check_email`
  ADD CONSTRAINT `url_check_email_ibfk_1` FOREIGN KEY (`url_check_id`) REFERENCES `url_check` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `url_check_email_ibfk_2` FOREIGN KEY (`email_id`) REFERENCES `emails` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `url_history`
--
ALTER TABLE `url_history`
  ADD CONSTRAINT `url_history_ibfk_1` FOREIGN KEY (`url_id`) REFERENCES `urls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
