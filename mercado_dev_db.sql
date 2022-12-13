-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2022-11-09 03:59:18
-- サーバのバージョン： 10.4.24-MariaDB
-- PHP のバージョン: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `mercado_dev_db`
--

DELIMITER $$
--
-- プロシージャ
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_CheckMailaddressAndSerial` (IN `MailAddressValue` VARCHAR(255), IN `SerialValue` VARCHAR(255))   BEGIN
    SELECT id, mail_address FROM member_applications WHERE mail_address = MailAddressValue AND serial = SerialValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_CheckReissu` (IN `SerialValue` VARCHAR(255))   BEGIN
    SELECT id, mail_address, insert_datetime FROM reissues WHERE serial = SerialValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_CheckSerialMailAddress` (IN `MailaddressValue` VARCHAR(255), IN `SerialValue` VARCHAR(255))   BEGIN
    SELECT id FROM reissues WHERE serial = SerialValue AND mail_address = MailaddressValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_CheckSingupMember` (IN `SerialValue` VARCHAR(255))   BEGIN
    SELECT id, mail_address, insert_datetime FROM member_applications WHERE serial = SerialValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteAddressee` (IN `IdValue` INT(6), IN `MemberIdValue` INT(6))   BEGIN
SELECT @res := id FROM addressees WHERE id = IdValue;
  IF @res != '' THEN
    DELETE FROM addressees WHERE id = IdValue AND member_id = MemberIdValue LIMIT 1;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteAdmin` (IN `IdValue` INT(6))   BEGIN
SELECT @res := id FROM admins WHERE id = IdValue;
  IF @res != '' THEN
    DELETE FROM admins WHERE id = IdValue LIMIT 1;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteCart` (IN `MemberIdValue` INT(6), IN `IdValue` INT(6))   BEGIN
SELECT @res := id FROM carts WHERE id = IdValue;
  IF @res != '' THEN
    DELETE FROM carts WHERE id = IdValue AND member_id = MemberIdValue LIMIT 1;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteCartAfterOrderCompleted` (IN `MemberIdValue` INT(6), IN `ItemIdValue` INT(6))   BEGIN
SELECT @res := id FROM carts WHERE member_id = MemberIdValue;
  IF @res != '' THEN
    DELETE FROM carts WHERE member_id = MemberIdValue AND item_id = ItemIdValue LIMIT 1;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteCartAfterOrderInsert` (IN `MemberIdValue` INT(6))   BEGIN
  DELETE FROM carts WHERE member_id = MemberIdValue;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteCategory` (IN `CategoryIdValue` INT(6))   BEGIN
SELECT @res := id FROM categories WHERE id = CategoryIdValue;
  IF @res != '' THEN
    DELETE FROM categories WHERE id = CategoryIdValue LIMIT 1;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteDeliveryAddress` (IN `MemberIdValue` INT(6), IN `IdValue` INT(6))   BEGIN
SELECT @res := id FROM addressees WHERE id = IdValue;
  IF @res != '' THEN
    DELETE FROM addressees WHERE id = IdValue AND member_id = MemberIdValue LIMIT 1;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteDepartment` (IN `DepartmentIdValue` INT(6))   BEGIN
SELECT @res := id FROM departments WHERE id = DepartmentIdValue;
  IF @res != '' THEN
    DELETE FROM departments WHERE id = DepartmentIdValue LIMIT 1;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteFavorite` (IN `MemberIdValue` INT(6), IN `ItemIdValue` INT(6))   BEGIN
SELECT @res := id FROM favorites WHERE item_id = ItemIdValue AND member_id = MemberIdValue;
  IF @res != '' THEN
    DELETE FROM favorites WHERE item_id = ItemIdValue AND member_id = MemberIdValue LIMIT 1;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteItem` (IN `IdValue` INT(6), IN `DepartmentIdValue` VARCHAR(6))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    SELECT path FROM item_images WHERE item_id = IdValue;
    DELETE II FROM item_images AS II INNER JOIN items AS I ON II.item_id = I.id WHERE II.item_id = IdValue;
    DELETE FROM items WHERE id = IdValue AND department_id REGEXP DepartmentIdValue LIMIT 1;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteItemImage` (IN `IdValue` INT(6))   BEGIN
SELECT @res := id FROM item_images WHERE id = IdValue;
  IF @res != '' THEN
    DELETE FROM item_images WHERE id = IdValue LIMIT 1;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteMail` (IN `IdValue` INT(6))   BEGIN
DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
  BEGIN
		ROLLBACK;
	END;
  START TRANSACTION;
    SELECT @res := id FROM mails WHERE id = IdValue;
    IF @res IS NOT NULL THEN
      DELETE FROM mails WHERE id = IdValue LIMIT 1;
      SELECT @res2 := id FROM mail_recipients WHERE mail_id = IdValue;
      IF @res2 IS NOT NULL THEN
        DELETE FROM mail_recipients WHERE mail_id = IdValue;
      END IF;
    END IF;
  COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteMailingList` (IN `IdValue` INT(6))   BEGIN
SELECT @res := id FROM mailing_lists WHERE id = IdValue;
  IF @res != '' THEN
    DELETE FROM mailing_list_members WHERE mailing_list_id = IdValue;
    DELETE FROM mailing_lists WHERE id = IdValue LIMIT 1;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteMailingListMember` (IN `IdValue` INT(6))   BEGIN
SELECT @res := id FROM mailing_list_members WHERE id = IdValue;
  IF @res != '' THEN
    DELETE FROM mailing_list_members WHERE id = IdValue LIMIT 1;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteMailRecipient` (IN `MailIdValue` INT(6))   BEGIN
SELECT @res := id FROM mail_recipients WHERE mail_id = MailIdValue;
  IF @res != '' THEN
    DELETE FROM mail_recipients WHERE mail_id = MailIdValue;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteMainNewsImage` (IN `NewsIdValue` INT(6))   BEGIN
SELECT @res := id FROM news_images WHERE news_id = NewsIdValue AND kinds = 1;
  IF @res != '' THEN
    DELETE FROM news_images WHERE news_id = NewsIdValue AND kinds = 1 LIMIT 1;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteMember` (IN `IdValue` INT(6))   BEGIN
SELECT @res := id FROM members WHERE id = IdValue;
  IF @res != '' THEN
    DELETE FROM members WHERE id = IdValue LIMIT 1;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteMemberApplicationSerial` (IN `MailAddressValue` VARCHAR(255), IN `SerialValue` VARCHAR(255))   BEGIN
  SELECT @res := id FROM member_applications WHERE mail_address = MailAddressValue AND serial = SerialValue;
  IF @res != '' THEN
    DELETE FROM member_applications WHERE mail_address = MailAddressValue AND serial = SerialValue;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteNews` (IN `IdValue` INT(6))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    SELECT id, kinds, path FROM news_images WHERE news_id = IdValue;
    DELETE NI FROM news_images AS NI INNER JOIN news AS N ON NI.news_id = N.id WHERE NI.news_id = IdValue;
    DELETE FROM news WHERE id = IdValue LIMIT 1;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteNewsImage` (IN `IdValue` INT(6))   BEGIN
SELECT @res := id FROM news_images WHERE id = IdValue;
  IF @res != '' THEN
    DELETE FROM news_images WHERE id = IdValue LIMIT 1;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteOrder` (IN `IdValue` INT(6), IN `MemberIdValue` INT(6))   BEGIN
SELECT @res := id FROM orders WHERE id = IdValue;
  IF @res != '' THEN
    DELETE FROM orders WHERE id = IdValue AND member_id = MemberIdValue LIMIT 1;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteOrderPostAddress` (IN `IdValue` INT(6), IN `MemberIdValue` INT(6))   BEGIN
SELECT @res := id FROM order_post_addresses WHERE id = IdValue;
  IF @res != '' THEN
    DELETE FROM order_post_addresses WHERE id = IdValue AND member_id = MemberIdValue LIMIT 1;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteOrderPostAddressAfterOrderInser` (IN `MemberIdValue` INT(6))   BEGIN
  DELETE FROM order_post_addresses WHERE member_id = MemberIdValue;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteReissueSerial` (IN `MailAddressValue` VARCHAR(255), IN `SerialValue` VARCHAR(255), IN `AdminFlagValue` INT(1))  COMMENT 'パスワード再発行用シリアルを削除' BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
	START TRANSACTION;
		DELETE FROM reissues WHERE mail_address = MailAddressValue AND serial = SerialValue AND admin_flag = AdminFlagValue;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertAddressee` (IN `MemberIdValue` INT(6), IN `FamilyNameValue` VARCHAR(255), IN `FirstNameValue` VARCHAR(255), IN `FamilyNameFuriganaValue` VARCHAR(255), IN `FirstNameFuriganaValue` VARCHAR(255), IN `PostalCodeValue` VARCHAR(10), IN `AddressValue` VARCHAR(255), IN `TelnumberValue` VARCHAR(20))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO addressees (member_id, family_name, first_name, family_name_furigana, first_name_furigana, postal_code, address, telnumber, insert_datetime, update_datetime) VALUES (MemberIdValue, FamilyNameValue, FirstNameValue, FamilyNameFuriganaValue, FirstNameFuriganaValue, PostalCodeValue, AddressValue, TelnumberValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertAdmin` (IN `NameValue` VARCHAR(255), IN `MailAddressValue` VARCHAR(255), IN `PasswordValue` VARCHAR(255), IN `EnckeyValue` VARCHAR(50), IN `DepartmentIdValue` INT(6), IN `AuthorityIdValue` INT(2), IN `StatusValue` INT(1))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO admins (name, mail_address, password, department_id, authority_id, status, insert_datetime, update_datetime) VALUES (NameValue, MailAddressValue, AES_ENCRYPT(PasswordValue, EnckeyValue), DepartmentIdValue, AuthorityIdValue, StatusValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertCart` (IN `MemberIdValue` INT(6), IN `ItemIdValue` INT(6), IN `QuantityValue` INT(6))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO carts ( member_id, item_id, quantity, insert_datetime, update_datetime )
    VALUES ( MemberIdValue, ItemIdValue, QuantityValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertCategory` (IN `NameValue` VARCHAR(50))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO categories (name, insert_datetime, update_datetime) VALUES (NameValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertDelivereAddress` (IN `MemberIdValue` INT(6), IN `FamilyNameValue` VARCHAR(255), IN `FirstNameValue` VARCHAR(255), IN `FamilyNameFuriganaValue` VARCHAR(255), IN `FirstNameFuriganaValue` VARCHAR(255), IN `PostalCodeValue` VARCHAR(10), IN `AddressValue` VARCHAR(255), IN `TelnumberValue` VARCHAR(20))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO addressees (member_id, family_name, first_name, family_name_furigana, first_name_furigana, postal_code, address, telnumber, insert_datetime, update_datetime) VALUES (MemberIdValue, FamilyNameValue, FirstNameValue, FamilyNameFuriganaValue, FirstNameFuriganaValue, PostalCodeValue, AddressValue, TelnumberValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertDepartment` (IN `NameValue` VARCHAR(50))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO departments (name, insert_datetime, update_datetime) VALUES (NameValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertFavorite` (IN `MemberIdValue` INT(6), IN `ItemIdValue` INT(6))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO favorites ( member_id, item_id, insert_datetime, update_datetime )
    VALUES ( MemberIdValue, ItemIdValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertItem` (IN `NameValue` VARCHAR(255), IN `CategoryIdValue` INT(6), IN `RecommendFlagValue` INT(1), IN `ItemSerialValue` VARCHAR(255), IN `StandardValue` VARCHAR(50), IN `DiscriptionValue` LONGTEXT, IN `PriceValue` INT(6), IN `TaxIdValue` INT(6), IN `PostageValue` INT(6), IN `StockQuantityValue` INT(6), IN `DepartmentIdValue` INT(6), IN `StatusValue` VARCHAR(50))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO items (name, category_id, recommend_flag, item_serial, standard, description, price, tax_id, postage, stock_quantity, department_id, status, insert_datetime, update_datetime) VALUES (NameValue, CategoryIdValue, RecommendFlagValue, ItemSerialValue, StandardValue, DiscriptionValue, PriceValue, TaxIdValue, PostageValue, StockQuantityValue, DepartmentIdValue, StatusValue, now(), now());
	COMMIT;
	SELECT LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertItemImage` (IN `ItemIdValue` INT(6), IN `KindsValue` INT(1), IN `PathValue` VARCHAR(255))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO item_images ( item_id, kinds, path, insert_datetime, update_datetime )
    VALUES ( ItemIdValue, KindsValue, PathValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertItemMainImage` (IN `ItemIdValue` INT(6), IN `KindsValue` INT(1), IN `PathValue` VARCHAR(255))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO item_images ( item_id, kinds, path, insert_datetime, update_datetime )
    VALUES ( ItemIdValue, KindsValue, PathValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertMail` (IN `DestinationTypeValue` INT(2), IN `TitleValue` VARCHAR(255), IN `BodyValue` LONGTEXT, IN `StatusValue` VARCHAR(50))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO mails (destination_type, title, body, status,insert_datetime, update_datetime) VALUES (DestinationTypeValue, TitleValue, BodyValue, StatusValue, now(), now());
	COMMIT;
	SELECT LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertMailingList` (IN `TitleValue` VARCHAR(255))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO mailing_lists (title, insert_datetime, update_datetime) VALUES (TitleValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertMailingListMember` (IN `MailingListIdValue` INT(6), IN `MemberIdValue` INT(6))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO mailing_list_members (mailing_list_id, member_id, insert_datetime, update_datetime) VALUES (MailingListIdValue, MemberIdValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertMailingListToMail` (IN `MailIdValue` INT(6), IN `MailingListIdValue` INT(6))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO mail_recipients (mail_id, mailing_list_id, insert_datetime, update_datetime) VALUES (MailIdValue, MailingListIdValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertMailRecipient` (IN `MailIdValue` INT(6), IN `MailingListIdValue` INT(6))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO mail_recipients (mail_id, mailing_list_id, insert_datetime, update_datetime) VALUES (MailIdValue, MailingListIdValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertMainNewsImage` (IN `NewsIdValue` INT(6), IN `PathValue` VARCHAR(255))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO news_images (news_id, kinds, path, insert_datetime, update_datetime) VALUES (NewsIdValue, PathValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertMember` (IN `FamilyNameValue` VARCHAR(255), IN `FirstNameValue` VARCHAR(255), IN `FamilyNameFuriganaValue` VARCHAR(255), IN `FirstNameFuriganaValue` VARCHAR(255), IN `PasswordValue` VARCHAR(255), IN `EnckeyValue` VARCHAR(50), IN `BirthdayValue` DATETIME, IN `PostalCodeValue` VARCHAR(10), IN `AddressValue` VARCHAR(255), IN `TelnumberValue` VARCHAR(20), IN `MailAddressValue` VARCHAR(255), IN `MailMagazineFlagValue` INT(1))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO members (family_name, first_name, family_name_furigana, first_name_furigana, password, birthday, postal_code, address, telnumber, mail_address, mail_magazine_flag, insert_datetime, update_datetime) VALUES (FamilyNameValue, FirstNameValue, FamilyNameFuriganaValue, FirstNameFuriganaValue, AES_ENCRYPT(PasswordValue, EnckeyValue), BirthdayValue, PostalCodeValue, AddressValue, TelnumberValue, MailAddressValue, MailMagazineFlagValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertMemberApplication` (IN `MailAddressValue` VARCHAR(255), IN `SerialValue` VARCHAR(255))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO member_applications (mail_address, serial, insert_datetime, update_datetime) VALUES (MailAddressValue, SerialValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertMemberFromAdmin` (IN `FamilyNameValue` VARCHAR(255), IN `FirstNameValue` VARCHAR(255), IN `FamilyNameFuriganaValue` VARCHAR(255), IN `FirstNameFuriganaValue` VARCHAR(255), IN `PasswordValue` VARCHAR(255), IN `EnckeyValue` VARCHAR(50), IN `BirthdayValue` DATETIME, IN `PostalCodeValue` VARCHAR(10), IN `AddressValue` VARCHAR(255), IN `TelnumberValue` VARCHAR(20), IN `MailAddressValue` VARCHAR(255), IN `MailMagazineFlagValue` INT(1))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO members (family_name, first_name, family_name_furigana, first_name_furigana, password, birthday, postal_code, address, telnumber, mail_address, mail_magazine_flag, insert_datetime, update_datetime) VALUES (FamilyNameValue, FirstNameValue, FamilyNameFuriganaValue, FirstNameFuriganaValue, AES_ENCRYPT(PasswordValue, EnckeyValue), BirthdayValue, PostalCodeValue, AddressValue, TelnumberValue, MailAddressValue, MailMagazineFlagValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertNews` (IN `TitleValue` VARCHAR(255), IN `BodyValue` LONGTEXT, IN `StatusValue` VARCHAR(50), IN `PublicationDatetimeValue` DATETIME)   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO news (title, body, status, insert_datetime, update_datetime, publication_datetime) VALUES (TitleValue, BodyValue, StatusValue, now(), now(), PublicationDatetimeValue);
	COMMIT;
	SELECT LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertNewsImage` (IN `NewsIdValue` INT(6), IN `KindsValue` INT(1), IN `PathValue` VARCHAR(255))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO news_images (news_id, kinds, path, insert_datetime, update_datetime) VALUES (NewsIdValue, KindsValue, PathValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertOrder` (IN `TargetIdValue` VARCHAR(16))   BEGIN
  SELECT @res := id FROM orders_temp WHERE trading_id = TargetIdValue;
  IF @res != '' THEN
    INSERT INTO orders (member_id, trading_id, item_id, quantity, delivery_family_name, delivery_first_name, delivery_family_name_furigana, delivery_first_name_furigana, delivery_postal_code, delivery_address, delivery_telnumber, delivery_slip_flag, pay_type, price, order_number, status, myhouse_flag, insert_datetime, update_datetime) SELECT member_id, trading_id, item_id, quantity, delivery_family_name, delivery_first_name, delivery_family_name_furigana, delivery_first_name_furigana, delivery_postal_code, delivery_address, delivery_telnumber, delivery_slip_flag, pay_type, price, order_number, status, myhouse_flag, insert_datetime, update_datetime FROM orders_temp WHERE orders_temp.trading_id = TargetIdValue;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertOrderPostAddress` (IN `MemberIdValue` INT(6), IN `DeliveryFamilyNameValue` VARCHAR(255), IN `DeliveryFirstNameValue` VARCHAR(255), IN `DeliveryFamilyNameFuriganaValue` VARCHAR(255), IN `DeliveryFirstNameFuriganaValue` VARCHAR(255), IN `DeliveryPostalCodeValue` VARCHAR(10), IN `DeliveryAddressValue` VARCHAR(255), IN `DeliveryTelnumberValue` VARCHAR(20), IN `DeliverySlipFlagValue` INT(1), IN `MyhouseFlagValue` INT(1))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO order_post_addresses (member_id, delivery_family_name, delivery_first_name, delivery_family_name_furigana, delivery_first_name_furigana, delivery_postal_code, delivery_address, delivery_telnumber, delivery_slip_flag, myhouse_flag, insert_datetime, update_datetime) VALUES (MemberIdValue, DeliveryFamilyNameValue, DeliveryFirstNameValue, DeliveryFamilyNameFuriganaValue, DeliveryFirstNameFuriganaValue, DeliveryPostalCodeValue, DeliveryAddressValue, DeliveryTelnumberValue, DeliverySlipFlagValue, MyhouseFlagValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertOrders` (IN `MemberIdValue` INT(6), IN `TradingIdValue` VARCHAR(24), IN `ItemIdValue` INT(6), IN `QuantityValue` INT(6), IN `DeliveryFamilyNameValue` VARCHAR(255), IN `DeliveryFirstNameValue` VARCHAR(255), IN `DeliveryFamilyNameFuriganaValue` VARCHAR(255), IN `DeliveryFirstNameFuriganaValue` VARCHAR(255), IN `DeliveryPostalCodeValue` VARCHAR(10), IN `DeliveryAddressValue` VARCHAR(255), IN `DeliveryTelnumberValue` VARCHAR(20), IN `DeliverySlipFlagValue` INT(1), IN `PayTypeValue` INT(2), IN `PriceValue` INT(6), IN `OrderNumberValue` VARCHAR(50), IN `StatusValue` INT(6))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO orders (member_id, trading_id, item_id, quantity, delivery_family_name, delivery_first_name, delivery_family_name_furigana, delivery_first_name_furigana, delivery_postal_code, delivery_address, delivery_telnumber, delivery_slip_flag, pay_type, price, order_number, status, insert_datetime, update_datetime)
    VALUES ( MemberIdValue, TradingIdValue, ItemIdValue, QuantityValue, DeliveryFamilyNameValue, DeliveryFirstNameValue, DeliveryFamilyNameFuriganaValue, DeliveryFirstNameFuriganaValue, DeliveryPostalCodeValue, DeliveryAddressValue, DeliveryTelnumberValue, DeliverySlipFlagValue, PayTypeValue, PriceValue, OrderNumberValue, StatusValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertOrderTemp` (IN `MemberIdValue` INT(6), IN `TradingIdValue` VARCHAR(16), IN `ItemIdValue` INT(6), IN `QuantityValue` INT(6), IN `DeliveryFamilyNameValue` VARCHAR(255), IN `DeliveryFirstNameValue` VARCHAR(255), IN `DeliveryFamilyNameFuriganaValue` VARCHAR(255), IN `DeliveryFirstNameFuriganaValue` VARCHAR(255), IN `DeliveryPostalCodeValue` VARCHAR(10), IN `DeliveryAddressValue` VARCHAR(255), IN `DeliveryTelnumberValue` VARCHAR(20), IN `DeliverySlipFlagValue` INT(1), IN `PayTypeValue` INT(2), IN `PriceValue` INT(6), IN `OrderNumberValue` VARCHAR(50), IN `MyhouseFlagValue` INT(1))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO orders_temp (member_id, trading_id, item_id, quantity, delivery_family_name, delivery_first_name, delivery_family_name_furigana, delivery_first_name_furigana, delivery_postal_code, delivery_address, delivery_telnumber, delivery_slip_flag, pay_type, price, order_number, status, myhouse_flag, insert_datetime, update_datetime) VALUES (MemberIdValue, TradingIdValue, ItemIdValue, QuantityValue, DeliveryFamilyNameValue, DeliveryFirstNameValue, DeliveryFamilyNameFuriganaValue, DeliveryFirstNameFuriganaValue, DeliveryPostalCodeValue, DeliveryAddressValue, DeliveryTelnumberValue, DeliverySlipFlagValue, PayTypeValue, PriceValue, OrderNumberValue, 1, MyhouseFlagValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertPostAddressFromDeleveryAddress` (IN `IdValue` INT(6), IN `MemberIdValue` INT(6))   BEGIN
    INSERT INTO order_post_addresses(
      member_id,
      delivery_family_name,
      delivery_first_name,
      delivery_family_name_furigana,
      delivery_first_name_furigana,
      delivery_postal_code,
      delivery_address,
      delivery_telnumber,
      insert_datetime,
      update_datetime
    )
    SELECT
      MemberIdValue,
      family_name,
      first_name,
      family_name_furigana,
      first_name_furigana,
      postal_code,
      address,
      telnumber,
    now(),
    now()
    FROM   addressees
    WHERE  addressees.id = IdValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_InsertReissue` (IN `SerialValue` VARCHAR(255), IN `AdminFlagValue` INT(1), IN `MailAddressValue` VARCHAR(255))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    INSERT INTO reissues (serial, admin_flag, mail_address, insert_datetime, update_datetime) VALUES (SerialValue, AdminFlagValue, MailAddressValue, now(), now());
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_LoginAdmin` (IN `MailAddressValue` VARCHAR(255), IN `PasswordValue` VARCHAR(255), IN `EnckeyValue` VARCHAR(50))   BEGIN
    SELECT id, name FROM admins WHERE mail_address = MailAddressValue AND password = AES_ENCRYPT(PasswordValue, EnckeyValue) LIMIT 1;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_LoginMember` (IN `MailAddressValue` VARCHAR(255), IN `PasswordValue` VARCHAR(255), IN `EnckeyValue` VARCHAR(50))   BEGIN
    SELECT id, family_name FROM members WHERE mail_address = MailAddressValue AND password = AES_ENCRYPT(PasswordValue, EnckeyValue) LIMIT 1;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_MemberMailAddressCheck` (IN `MailAddressValue` VARCHAR(255))   BEGIN
    SELECT * FROM `members` WHERE `mail_address` = MailAddressValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectAdditionalShippingFee` (IN `AddressValue` VARCHAR(50))  COMMENT '引数の住所と文字列一致判定を行い、一致した地域の追加送料情報を取得' BEGIN
  SELECT prefecture_name, area_name, additional_shipping_fee FROM additional_shipping_fees WHERE INSTR(AddressValue, match_text) > 0 LIMIT 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectAdditionalShippingFeeList` ()   BEGIN
  SELECT * FROM additional_shipping_fees;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectAddressee` (IN `IdValue` INT(6), IN `MemberIdValue` INT(6))   BEGIN
    SELECT * FROM addressees WHERE id = IdValue AND member_id = MemberIdValue ORDER BY id DESC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectAddresseeLisT` (IN `MemberIdValue` INT(6))   BEGIN
    SELECT * FROM addressees WHERE member_id = MemberIdValue ORDER BY id DESC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectAdmin` (IN `IdValue` INT(6))   BEGIN
    SELECT id, name, mail_address, department_id, authority_id, status FROM admins WHERE id = IdValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectAdminAuthorityList` ()   BEGIN
    SELECT id, name FROM admin_authorities ORDER BY id DESC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectAdminByMailaddress` (IN `MailaddressValue` VARCHAR(255))   BEGIN
  SELECT id FROM admins WHERE mail_address = MailaddressValue;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectAdminList` (IN `OrderValue` VARCHAR(50), IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
    IF LimitValue = -1 THEN
      IF OrderValue = 'admins.insert_datetime DESC' THEN
        SELECT admins.id, admins.name AS name, admins.mail_address, departments.name AS department_name, admin_authorities.name AS authority_name, admins.status, admins.insert_datetime, admins.update_datetime FROM admins INNER JOIN departments ON admins.department_id = departments.id INNER JOIN admin_authorities ON admins.authority_id = admin_authorities.id ORDER BY admins.insert_datetime DESC;
      ELSEIF OrderValue = 'admins.department_id ASC' THEN
        SELECT admins.id, admins.name AS name, admins.mail_address, departments.name AS department_name, admin_authorities.name AS authority_name, admins.status, admins.insert_datetime, admins.update_datetime FROM admins INNER JOIN departments ON admins.department_id = departments.id INNER JOIN admin_authorities ON admins.authority_id = admin_authorities.id ORDER BY admins.department_id ASC;
      ELSE
        SELECT admins.id, admins.name AS name, admins.mail_address, departments.name AS department_name, admin_authorities.name AS authority_name, admins.status, admins.insert_datetime, admins.update_datetime FROM admins INNER JOIN departments ON admins.department_id = departments.id INNER JOIN admin_authorities ON admins.authority_id = admin_authorities.id ORDER BY admins.insert_datetime DESC;
      END IF;
    ELSE
      IF OrderValue = 'admins.insert_datetime DESC' THEN
        SELECT admins.id, admins.name AS name, admins.mail_address, departments.name AS department_name, admin_authorities.name AS authority_name, admins.status, admins.insert_datetime, admins.update_datetime FROM admins INNER JOIN departments ON admins.department_id = departments.id INNER JOIN admin_authorities ON admins.authority_id = admin_authorities.id ORDER BY admins.id DESC LIMIT LimitValue OFFSET OffsetValue;
      ELSEIF OrderValue = 'admins.department_id ASC' THEN
        SELECT admins.id, admins.name AS name, admins.mail_address, departments.name AS department_name, admin_authorities.name AS authority_name, admins.status, admins.insert_datetime, admins.update_datetime FROM admins INNER JOIN departments ON admins.department_id = departments.id INNER JOIN admin_authorities ON admins.authority_id = admin_authorities.id ORDER BY admins.id DESC LIMIT LimitValue OFFSET OffsetValue;
      ELSE
        SELECT admins.id, admins.name AS name, admins.mail_address, departments.name AS department_name, admin_authorities.name AS authority_name, admins.status, admins.insert_datetime, admins.update_datetime FROM admins INNER JOIN departments ON admins.department_id = departments.id INNER JOIN admin_authorities ON admins.authority_id = admin_authorities.id ORDER BY admins.insert_datetime DESC LIMIT LimitValue OFFSET OffsetValue;
      END IF;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectAdminPassword` (IN `MailAddressValue` VARCHAR(255))   BEGIN
    SELECT password FROM admins WHERE mail_address = MailAddressValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectAllMyOrderList` (IN `MemberIdValue` INT(6), IN `OrderValue` VARCHAR(50), IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
    IF LimitValue = -1 THEN
      SELECT orders.*, items.name, items.price, items.standard, items.postage, taxes.tax_value FROM orders INNER JOIN items ON orders.item_id = items.id WHERE orders.member_id = MemberIdValue ORDER BY orders.OrderValue;
    ELSE
      SELECT orders.*, items.name, items.price, items.standard, items.postage, taxes.tax_value FROM orders INNER JOIN items ON orders.item_id = items.id WHERE orders.member_id = MemberIdValue ORDER BY orders.OrderValue LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectAllOrderList` (IN `nameValue` VARCHAR(255), IN `OrderValue` VARCHAR(50), IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
    IF LimitValue = -1 THEN
      IF OrderValue = 'orders.id DESC' THEN
        SELECT orders.id AS order_id, orders.insert_datetime, orders.order_number, orders.quantity, members.family_name, members.first_name, members.telnumber, orders.status, items.name, departments.name AS department_name FROM `orders` INNER JOIN items ON orders.item_id = items.id INNER JOIN members ON orders.member_id = members.id INNER JOIN departments ON items.department_id = departments.id WHERE members.family_name REGEXP nameValue OR members.first_name REGEXP nameValue OR members.family_name_furigana REGEXP nameValue OR members.first_name_furigana REGEXP nameValue ORDER BY orders.id DESC;
      ELSEIF OrderValue = 'members.family_name_furigana ASC' THEN
        SELECT orders.id AS order_id, orders.insert_datetime, orders.order_number, orders.quantity, members.family_name, members.first_name, members.telnumber, orders.status, items.name, departments.name AS department_name FROM `orders` INNER JOIN items ON orders.item_id = items.id INNER JOIN members ON orders.member_id = members.id INNER JOIN departments ON items.department_id = departments.id WHERE members.family_name REGEXP nameValue OR members.first_name REGEXP nameValue OR members.family_name_furigana REGEXP nameValue OR members.first_name_furigana REGEXP nameValue ORDER BY members.family_name_furigana ASC;
      ELSEIF OrderValue = 'orders.insert_datetime ASC' THEN
        SELECT orders.id AS order_id, orders.insert_datetime, orders.order_number, orders.quantity, members.family_name, members.first_name, members.telnumber, orders.status, items.name, departments.name AS department_name FROM `orders` INNER JOIN items ON orders.item_id = items.id INNER JOIN members ON orders.member_id = members.id INNER JOIN departments ON items.department_id = departments.id WHERE members.family_name REGEXP nameValue OR members.first_name REGEXP nameValue OR members.family_name_furigana REGEXP nameValue OR members.first_name_furigana REGEXP nameValue ORDER BY orders.insert_datetime ASC;
      ELSEIF OrderValue = 'orders.insert_datetime DESC' THEN
        SELECT orders.id AS order_id, orders.insert_datetime, orders.order_number, orders.quantity, members.family_name, members.first_name, members.telnumber, orders.status, items.name, departments.name AS department_name FROM `orders` INNER JOIN items ON orders.item_id = items.id INNER JOIN members ON orders.member_id = members.id INNER JOIN departments ON items.department_id = departments.id WHERE members.family_name REGEXP nameValue OR members.first_name REGEXP nameValue OR members.family_name_furigana REGEXP nameValue OR members.first_name_furigana REGEXP nameValue ORDER BY orders.insert_datetime DESC;
      END IF;
    ELSE
      IF OrderValue = 'orders.id DESC' THEN
        SELECT orders.id AS order_id, orders.insert_datetime, orders.order_number, orders.quantity, members.family_name, members.first_name, members.telnumber, orders.status, items.name, departments.name AS department_name FROM `orders` INNER JOIN items ON orders.item_id = items.id INNER JOIN members ON orders.member_id = members.id INNER JOIN departments ON items.department_id = departments.id WHERE members.family_name REGEXP nameValue OR members.first_name REGEXP nameValue OR members.family_name_furigana REGEXP nameValue OR members.first_name_furigana REGEXP nameValue ORDER BY orders.id DESC LIMIT LimitValue OFFSET OffsetValue;
      ELSEIF OrderValue = 'members.family_name_furigana ASC' THEN
        SELECT orders.id AS order_id, orders.insert_datetime, orders.order_number, orders.quantity, members.family_name, members.first_name, members.telnumber, orders.status, items.name, departments.name AS department_name FROM `orders` INNER JOIN items ON orders.item_id = items.id INNER JOIN members ON orders.member_id = members.id INNER JOIN departments ON items.department_id = departments.id WHERE members.family_name REGEXP nameValue OR members.first_name REGEXP nameValue OR members.family_name_furigana REGEXP nameValue OR members.first_name_furigana REGEXP nameValue ORDER BY members.family_name_furigana ASC LIMIT LimitValue OFFSET OffsetValue;
      ELSEIF OrderValue = 'orders.insert_datetime ASC' THEN
        SELECT orders.id AS order_id, orders.insert_datetime, orders.order_number, orders.quantity, members.family_name, members.first_name, members.telnumber, orders.status, items.name, departments.name AS department_name FROM `orders` INNER JOIN items ON orders.item_id = items.id INNER JOIN members ON orders.member_id = members.id INNER JOIN departments ON items.department_id = departments.id WHERE members.family_name REGEXP nameValue OR members.first_name REGEXP nameValue OR members.family_name_furigana REGEXP nameValue OR members.first_name_furigana REGEXP nameValue ORDER BY orders.insert_datetime ASC LIMIT LimitValue OFFSET OffsetValue;
      ELSEIF OrderValue = 'orders.insert_datetime DESC' THEN
        SELECT orders.id AS order_id, orders.insert_datetime, orders.order_number, orders.quantity, members.family_name, members.first_name, members.telnumber, orders.status, items.name, departments.name AS department_name FROM `orders` INNER JOIN items ON orders.item_id = items.id INNER JOIN members ON orders.member_id = members.id INNER JOIN departments ON items.department_id = departments.id WHERE members.family_name REGEXP nameValue OR members.first_name REGEXP nameValue OR members.family_name_furigana REGEXP nameValue OR members.first_name_furigana REGEXP nameValue ORDER BY orders.insert_datetime DESC LIMIT LimitValue OFFSET OffsetValue;
      END IF;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectCartList` (IN `MemberIdValue` INT(6))   BEGIN
    SELECT carts.id AS cart_id, carts.quantity, items.id AS item_id, items.name, items.price, items.standard, items.postage, taxes.tax_value, items.stock_quantity, item_images.path FROM carts INNER JOIN items ON carts.item_id = items.id LEFT OUTER JOIN item_images ON items.id = item_images.item_id AND item_images.kinds = 1 INNER JOIN taxes ON items.tax_id = taxes.id WHERE carts.member_id = MemberIdValue AND items.status = 'public' ORDER BY carts.insert_datetime DESC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectCartListOnlyItemId` (IN `MemberIdValue` INT(6))   BEGIN
    SELECT item_id FROM carts WHERE member_id = MemberIdValue ORDER BY insert_datetime DESC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectCartsCount` (IN `memberIdValue` INT(6))   BEGIN
    SELECT count(id) FROM `carts` WHERE `member_id` = memberIdValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectCategory` (IN `CategoryIdValue` INT(6))   BEGIN
    SELECT id, name FROM categories WHERE id = CategoryIdValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectCategoryList` (IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
    IF LimitValue = -1 THEN
      SELECT id, name FROM categories ORDER BY id ASC;
    ELSE
      SELECT id, name FROM categories ORDER BY id ASC LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectDeliveryAddress` (IN `MemberIdValue` INT(6), IN `IdValue` INT(6))   BEGIN
    SELECT * FROM addressees WHERE id = IdValue AND member_id = MemberIdValue ORDER BY id DESC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectDeliveryAddressList` (IN `MemberIdValue` INT(6), IN `NameValue` VARCHAR(50), IN `LimitValue` INT(3), IN `OffsetValue` INT(3))   BEGIN
    IF LimitValue = -1 THEN
      SELECT * FROM addressees WHERE member_id = MemberIdValue AND (family_name REGEXP NameValue OR first_name REGEXP NameValue OR family_name_furigana REGEXP NameValue OR first_name_furigana REGEXP NameValue) ORDER BY id DESC;
    ELSE
      SELECT * FROM addressees WHERE member_id = MemberIdValue AND (family_name REGEXP NameValue OR first_name REGEXP NameValue OR family_name_furigana REGEXP NameValue OR first_name_furigana REGEXP NameValue) ORDER BY id DESC LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectDeliveryInfo` (IN `IdValue` INT(6))   BEGIN
    SELECT delivery_family_name, delivery_first_name, delivery_family_name_furigana, delivery_first_name_furigana, delivery_postal_code, delivery_address, delivery_telnumber FROM orders WHERE id = IdValue ORDER BY id DESC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectDepartment` (IN `DepartmentIdValue` INT(6))   BEGIN
    SELECT id, name FROM departments WHERE id = DepartmentIdValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectDepartmentList` (IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
    IF LimitValue = -1 THEN
      SELECT id, name FROM departments ORDER BY id ASC;
    ELSE
      SELECT id, name FROM departments ORDER BY id ASC LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectFavoriteList` (IN `MemberIdValue` INT(6), IN `OrderValue` VARCHAR(50), IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
  IF OrderValue = 'id ASC' THEN
    IF LimitValue = -1 THEN
      SELECT favorites.id, items.id AS item_id, items.name, items.price, taxes.tax_value, items.stock_quantity, item_images.path FROM favorites INNER JOIN items ON favorites.item_id = items.id LEFT OUTER JOIN item_images ON favorites.item_id = item_images.item_id AND item_images.kinds = 1 INNER JOIN taxes ON items.tax_id = taxes.id WHERE member_id = MemberIdValue AND items.status = 'public' ORDER BY favorites.id ASC;
    ELSE
      SELECT favorites.id, items.id AS item_id, items.name, items.price, taxes.tax_value, items.stock_quantity, item_images.path FROM favorites INNER JOIN items ON favorites.item_id = items.id LEFT OUTER JOIN item_images ON favorites.item_id = item_images.item_id AND item_images.kinds = 1 INNER JOIN taxes ON items.tax_id = taxes.id WHERE member_id = MemberIdValue AND items.status = 'public' ORDER BY favorites.id ASC LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  ELSEIF OrderValue = 'price ASC' THEN
    IF LimitValue = -1 THEN
      SELECT favorites.id, items.id AS item_id, items.name, items.price, taxes.tax_value, items.stock_quantity, item_images.path FROM favorites INNER JOIN items ON favorites.item_id = items.id LEFT OUTER JOIN item_images ON favorites.item_id = item_images.item_id AND item_images.kinds = 1 INNER JOIN taxes ON items.tax_id = taxes.id WHERE member_id = MemberIdValue AND items.status = 'public' ORDER BY items.price ASC;
    ELSE
      SELECT favorites.id, items.id AS item_id, items.name, items.price, taxes.tax_value, items.stock_quantity, item_images.path FROM favorites INNER JOIN items ON favorites.item_id = items.id LEFT OUTER JOIN item_images ON favorites.item_id = item_images.item_id AND item_images.kinds = 1 INNER JOIN taxes ON items.tax_id = taxes.id WHERE member_id = MemberIdValue AND items.status = 'public' ORDER BY items.price ASC LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  ELSEIF OrderValue = 'price DESC' THEN
    IF LimitValue = -1 THEN
      SELECT favorites.id, items.id AS item_id, items.name, items.price, taxes.tax_value, items.stock_quantity, item_images.path FROM favorites INNER JOIN items ON favorites.item_id = items.id LEFT OUTER JOIN item_images ON favorites.item_id = item_images.item_id AND item_images.kinds = 1 INNER JOIN taxes ON items.tax_id = taxes.id WHERE member_id = MemberIdValue AND items.status = 'public' ORDER BY items.price DESC;
    ELSE
      SELECT favorites.id, items.id AS item_id, items.name, items.price, taxes.tax_value, items.stock_quantity, item_images.path FROM favorites INNER JOIN items ON favorites.item_id = items.id LEFT OUTER JOIN item_images ON favorites.item_id = item_images.item_id AND item_images.kinds = 1 INNER JOIN taxes ON items.tax_id = taxes.id WHERE member_id = MemberIdValue AND items.status = 'public' ORDER BY items.price DESC LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  ELSE
    IF LimitValue = -1 THEN
      SELECT favorites.id, items.id AS item_id, items.name, items.price, taxes.tax_value, items.stock_quantity, item_images.path FROM favorites INNER JOIN items ON favorites.item_id = items.id LEFT OUTER JOIN item_images ON favorites.item_id = item_images.item_id AND item_images.kinds = 1 INNER JOIN taxes ON items.tax_id = taxes.id WHERE member_id = MemberIdValue AND items.status = 'public' ORDER BY favorites.id DESC;
    ELSE
      SELECT favorites.id, items.id AS item_id, items.name, items.price, taxes.tax_value, items.stock_quantity, item_images.path FROM favorites INNER JOIN items ON favorites.item_id = items.id LEFT OUTER JOIN item_images ON favorites.item_id = item_images.item_id AND item_images.kinds = 1 INNER JOIN taxes ON items.tax_id = taxes.id WHERE member_id = MemberIdValue AND items.status = 'public' ORDER BY favorites.id DESC LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectFavoritesCount` (IN `MemberIdValue` INT(6))   BEGIN
    SELECT count(id) FROM `faborites` WHERE `member_id` = MemberIdValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectItem` (IN `ItemIdValue` INT(6), IN `DepartmentIdValue` VARCHAR(6))   BEGIN
    SELECT items.* FROM items WHERE id = ItemIdValue AND department_id REGEXP DepartmentIdValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectItemImage` (IN `ItemIdValue` INT(6), IN `KindsValue` INT(1))   BEGIN
  SELECT id, path, CASE
    WHEN KindsValue = '2' THEN SUBSTRING(path, 17, 1)
    ELSE 0
    END AS img_num
  FROM item_images WHERE item_id = ItemIdValue AND kinds = KindsValue
  ORDER BY CASE
    WHEN KindsValue = '2' THEN SUBSTRING(path, 17, 1)
    ELSE id
  END
  ;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectItemImagePath` (IN `IdValue` INT(6), IN `ItemIdValue` INT(6))   BEGIN
  SELECT path FROM item_images WHERE id = IdValue AND item_id = ItemIdValue;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectItemList` (IN `CategoryValue` VARCHAR(4), IN `DepartmentIdValue` VARCHAR(6), IN `OrderValue` VARCHAR(50), IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
    IF LimitValue = -1 THEN
      IF OrderValue = 'items.id DESC' THEN
        SELECT items.*, item_images.path, categories.name AS category_name, departments.name AS department_name FROM items LEFT OUTER JOIN item_images ON items.id = item_images.item_id AND item_images.kinds = 1 INNER JOIN categories ON items.category_id = categories.id INNER JOIN departments ON items.department_id = departments.id WHERE items.category_id REGEXP CategoryValue AND items.department_id REGEXP DepartmentIdValue ORDER BY items.id DESC;
      ELSEIF OrderValue = 'items.name ASC' THEN
        SELECT items.*, item_images.path, categories.name AS category_name, departments.name AS department_name FROM items LEFT OUTER JOIN item_images ON items.id = item_images.item_id AND item_images.kinds = 1 INNER JOIN categories ON items.category_id = categories.id INNER JOIN departments ON items.department_id = departments.id WHERE items.category_id REGEXP CategoryValue AND items.department_id REGEXP DepartmentIdValue ORDER BY items.name ASC;
      ELSEIF OrderValue = 'items.insert_datetime DESC' THEN
        SELECT items.*, item_images.path, categories.name AS category_name, departments.name AS department_name FROM items LEFT OUTER JOIN item_images ON items.id = item_images.item_id AND item_images.kinds = 1 INNER JOIN categories ON items.category_id = categories.id INNER JOIN departments ON items.department_id = departments.id WHERE items.category_id REGEXP CategoryValue AND items.department_id REGEXP DepartmentIdValue ORDER BY items.insert_datetime DESC;
      ELSEIF OrderValue = 'items.insert_datetime ASC' THEN
        SELECT items.*, item_images.path, categories.name AS category_name, departments.name AS department_name FROM items LEFT OUTER JOIN item_images ON items.id = item_images.item_id AND item_images.kinds = 1 INNER JOIN categories ON items.category_id = categories.id INNER JOIN departments ON items.department_id = departments.id WHERE items.category_id REGEXP CategoryValue AND items.department_id REGEXP DepartmentIdValue ORDER BY items.insert_datetime ASC;
      END IF;
    ELSE
      IF OrderValue = 'items.id DESC' THEN
        SELECT items.*, item_images.path, categories.name AS category_name, departments.name AS department_name FROM items LEFT OUTER JOIN item_images ON items.id = item_images.item_id AND item_images.kinds = 1 INNER JOIN categories ON items.category_id = categories.id INNER JOIN departments ON items.department_id = departments.id WHERE items.category_id REGEXP CategoryValue AND items.department_id REGEXP DepartmentIdValue ORDER BY items.id DESC LIMIT LimitValue OFFSET OffsetValue;
      ELSEIF OrderValue = 'items.name ASC' THEN
        SELECT items.*, item_images.path, categories.name AS category_name, departments.name AS department_name FROM items LEFT OUTER JOIN item_images ON items.id = item_images.item_id AND item_images.kinds = 1 INNER JOIN categories ON items.category_id = categories.id INNER JOIN departments ON items.department_id = departments.id WHERE items.category_id REGEXP CategoryValue AND items.department_id REGEXP DepartmentIdValue ORDER BY items.name ASC LIMIT LimitValue OFFSET OffsetValue;
      ELSEIF OrderValue = 'items.insert_datetime DESC' THEN
        SELECT items.*, item_images.path, categories.name AS category_name, departments.name AS department_name FROM items LEFT OUTER JOIN item_images ON items.id = item_images.item_id AND item_images.kinds = 1 INNER JOIN categories ON items.category_id = categories.id INNER JOIN departments ON items.department_id = departments.id WHERE items.category_id REGEXP CategoryValue AND items.department_id REGEXP DepartmentIdValue ORDER BY items.insert_datetime DESC LIMIT LimitValue OFFSET OffsetValue;
      ELSEIF OrderValue = 'items.insert_datetime ASC' THEN
        SELECT items.*, item_images.path, categories.name AS category_name, departments.name AS department_name FROM items LEFT OUTER JOIN item_images ON items.id = item_images.item_id AND item_images.kinds = 1 INNER JOIN categories ON items.category_id = categories.id INNER JOIN departments ON items.department_id = departments.id WHERE items.category_id REGEXP CategoryValue AND items.department_id REGEXP DepartmentIdValue ORDER BY items.insert_datetime ASC LIMIT LimitValue OFFSET OffsetValue;
      END IF;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectItemMainImage` (IN `ItemIdValue` INT(6))   BEGIN
    SELECT path FROM item_images WHERE item_id = ItemIdValue AND kinds = 1;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectItemsCount` ()   BEGIN
    SELECT count(id) FROM items;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectItemStockQuantity` (IN `IdValue` INT(6))  COMMENT '商品の在庫数を取得' BEGIN
  SELECT stock_quantity FROM items WHERE id = IdValue;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMail` (IN `IdValue` INT(6))   BEGIN
    SELECT id, destination_type, title, body, insert_datetime FROM mails WHERE id = IdValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMailingList` (IN `IdValue` INT(6))   BEGIN
    SELECT id, title FROM mailing_lists WHERE id = IdValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMailingListList` (IN `OrderValue` VARCHAR(50), IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
    IF LimitValue = -1 THEN
      SELECT * FROM mailing_lists ORDER BY id DESC;
    ELSE
      SELECT * FROM mailing_lists ORDER BY id DESC LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMailingListMemberList` (IN `MailingListIdValue` INT(6), IN `OrderValue` VARCHAR(50), IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
    IF LimitValue = -1 THEN
      SELECT mailing_list_members.id, members.family_name, members.first_name, members.mail_address FROM mailing_list_members INNER JOIN members ON mailing_list_members.member_id = members.id WHERE mailing_list_id = MailingListIdValue ORDER BY mailing_list_members.id DESC;
    ELSE
      SELECT mailing_list_members.id, members.family_name, members.first_name, members.mail_address FROM mailing_list_members INNER JOIN members ON mailing_list_members.member_id = members.id WHERE mailing_list_id = MailingListIdValue ORDER BY mailing_list_members.id DESC LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMailList` (IN `OrderValue` VARCHAR(50), IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
    IF LimitValue = -1 THEN
      SELECT
      M.id,
      M.destination_type,
      M.title,
      M.status,
      M.insert_datetime,
      ME.family_name,
      ME.first_name
      FROM mails AS M
      LEFT OUTER JOIN mail_recipients AS MR
      ON MR.id = (
          SELECT MR2.id FROM mail_recipients AS MR2
          WHERE M.id = MR2.mail_id
          ORDER BY MR2.id LIMIT 1)
      LEFT OUTER JOIN mailing_list_members AS MLM
      ON MLM.id = (
          SELECT MLM2.id FROM mailing_list_members AS MLM2
          WHERE MR.mailing_list_id = MLM2.mailing_list_id
          ORDER BY MLM2.id LIMIT 1)
      LEFT OUTER JOIN members AS ME ON MLM.member_id = ME.id ORDER BY M.id DESC;
    ELSE
      SELECT
      M.id,
      M.destination_type,
      M.title,
      M.status,
      M.insert_datetime,
      ME.family_name,
      ME.first_name
      FROM mails AS M
      LEFT OUTER JOIN mail_recipients AS MR
      ON MR.id = (
          SELECT MR2.id FROM mail_recipients AS MR2
          WHERE M.id = MR2.mail_id
          ORDER BY MR2.id LIMIT 1)
      LEFT OUTER JOIN mailing_list_members AS MLM
      ON MLM.id = (
          SELECT MLM2.id FROM mailing_list_members AS MLM2
          WHERE MR.mailing_list_id = MLM2.mailing_list_id
          ORDER BY MLM2.id LIMIT 1)
      LEFT OUTER JOIN members AS ME ON MLM.member_id = ME.id ORDER BY M.id DESC
      LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMailRecipientAllMember` ()   BEGIN
    SELECT family_name, mail_address FROM members ORDER BY id ASC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMailRecipientApplicant` ()   BEGIN
    SELECT family_name, mail_address FROM members WHERE mail_magazine_flag = 1 ORDER BY id ASC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMailRecipientList` (IN `IdValue` INT(6), IN `OrderValue` VARCHAR(50), IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
    IF LimitValue = -1 THEN
      SELECT mail_recipients.id, mailing_lists.title FROM mail_recipients INNER JOIN mailing_lists ON mail_recipients.mailing_list_id = mailing_lists.id WHERE mail_recipients.mail_id = IdValue ORDER BY OrderValue;
    ELSE
      SELECT mail_recipients.id, mailing_lists.title FROM mail_recipients INNER JOIN mailing_lists ON mail_recipients.mailing_list_id = mailing_lists.id WHERE mail_recipients.mail_id = IdValue ORDER BY OrderValue LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMailRecipientMailingList` (IN `MailIdValue` INT(6))   BEGIN
    SELECT members.family_name, members.mail_address FROM mail_recipients INNER JOIN mailing_list_members ON mail_recipients.mailing_list_id = mailing_list_members.mailing_list_id INNER JOIN members ON mailing_list_members.member_id = members.id WHERE mail_recipients.mail_id = MailIdValue ORDER BY members.id ASC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMailRecipients` (IN `MailIdValue` INT(6))   BEGIN
    SELECT mailing_lists.id, mailing_lists.title FROM mail_recipients INNER JOIN mailing_lists ON mail_recipients.mailing_list_id = mailing_lists.id WHERE mail_recipients.mail_id = MailIdValue ORDER BY mail_recipients.id ASC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMember` (IN `MemberIdValue` INT(6))   BEGIN
    SELECT id, family_name, first_name, family_name_furigana, first_name_furigana, birthday, postal_code, address, telnumber, mail_address, mail_magazine_flag FROM members WHERE id = MemberIdValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMemberApplication` (IN `SerialValue` VARCHAR(255))   BEGIN
    SELECT mail_address, insert_datetime FROM member_applications WHERE serial = SerialValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMemberId` (IN `TradingIdValue` VARCHAR(16))   BEGIN
    SELECT DISTINCT member_id FROM `orders` WHERE trading_id = TradingIdValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMemberList` (IN `OrderValue` VARCHAR(50), IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
    IF LimitValue = -1 THEN
      IF OrderValue = 'members.insert_datetime DESC' THEN
        SELECT id, family_name, first_name, family_name_furigana, first_name_furigana, address, telnumber, mail_address, insert_datetime,update_datetime FROM members ORDER BY members.insert_datetime DESC;
      ELSEIF OrderValue = 'members.insert_datetime ASC' THEN
        SELECT id, family_name, first_name, family_name_furigana, first_name_furigana, address, telnumber, mail_address, insert_datetime,update_datetime FROM members ORDER BY members.insert_datetime ASC;
      ELSEIF OrderValue = 'members.family_name_furigana ASC' THEN
        SELECT id, family_name, first_name, family_name_furigana, first_name_furigana, address, telnumber, mail_address, insert_datetime,update_datetime FROM members ORDER BY members.family_name ASC;
      ELSE
        SELECT id, family_name, first_name, family_name_furigana, first_name_furigana, address, telnumber, mail_address, insert_datetime,update_datetime FROM members ORDER BY members.id DESC;
      END IF;
    ELSE
      IF OrderValue = 'members.insert_datetime DESC' THEN
        SELECT id, family_name, first_name, family_name_furigana, first_name_furigana, address, telnumber, mail_address, insert_datetime,update_datetime FROM members ORDER BY members.insert_datetime DESC LIMIT LimitValue OFFSET OffsetValue;
      ELSEIF OrderValue = 'members.insert_datetime ASC' THEN
        SELECT id, family_name, first_name, family_name_furigana, first_name_furigana, address, telnumber, mail_address, insert_datetime,update_datetime FROM members ORDER BY members.insert_datetime ASC LIMIT LimitValue OFFSET OffsetValue;
      ELSEIF OrderValue = 'members.family_name_furigana ASC' THEN
        SELECT id, family_name, first_name, family_name_furigana, first_name_furigana, address, telnumber, mail_address, insert_datetime,update_datetime FROM members ORDER BY members.family_name ASC LIMIT LimitValue OFFSET OffsetValue;
      ELSE
        SELECT id, family_name, first_name, family_name_furigana, first_name_furigana, address, telnumber, mail_address, insert_datetime,update_datetime FROM members ORDER BY members.id DESC;
      END IF;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMemberMailaddress` (IN `MailaddressValue` VARCHAR(255))   BEGIN
    SELECT id FROM members WHERE mail_address = MailaddressValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMemberMailaddressForMemberId` (IN `IdValue` INT(3))   BEGIN
    SELECT mail_address FROM `members` WHERE `id` = IdValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMemberPassword` (IN `MailAddressValue` VARCHAR(255))   BEGIN
    SELECT password FROM members WHERE mail_address = MailAddressValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMembersCount` ()   BEGIN
    SELECT count(id) FROM members;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectMyOrderList` (IN `MemberIdValue` INT(6), IN `StatusValue` INT(6), IN `OrderValue` VARCHAR(50), IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
    IF LimitValue = -1 THEN
      SELECT orders.*, items.name, items.price, items.standard, items.postage, taxes.tax_value FROM orders INNER JOIN items ON orders.item_id = items.id WHERE orders.member_id = MemberIdValue AND orders.status = StatusValue ORDER BY orders.OrderValue;
    ELSE
      SELECT orders.*, items.name, items.price, items.standard, items.postage, taxes.tax_value FROM orders INNER JOIN items ON orders.item_id = items.id WHERE orders.member_id = MemberIdValue AND orders.status = StatusValue ORDER BY orders.OrderValue LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectNews` (IN `NewsIdValue` INT(6))   BEGIN
    SELECT news.id, news.title, news.body, news.status, news.publication_datetime, news_images.id AS image_id, news_images.path AS image_path FROM news INNER JOIN news_images ON news.id = news_images.news_id WHERE news.id = NewsIdValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectNewsImage` (IN `NewsIdValue` INT(6), IN `KindsValue` INT(1))   BEGIN
    SELECT id, path FROM news_images WHERE news_id = NewsIdValue AND kinds = KindsValue ORDER BY id ASC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectNewsList` (IN `OrderValue` VARCHAR(50), IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
    IF LimitValue = -1 THEN
      IF OrderValue = 'news.insert_datetime ASC' THEN
        SELECT id, title, status, insert_datetime FROM news ORDER BY insert_datetime ASC;
      ELSEIF OrderValue = 'news.insert_datetime DESC' THEN
        SELECT id, title, status, insert_datetime FROM news ORDER BY insert_datetime DESC;
      ELSE
        SELECT id, title, status, insert_datetime FROM news ORDER BY id DESC;
      END IF;
    ELSE
      IF OrderValue = 'news.insert_datetime ASC' THEN
        SELECT id, title, status, insert_datetime FROM news ORDER BY insert_datetime ASC LIMIT LimitValue OFFSET OffsetValue;
      ELSEIF OrderValue = 'news.insert_datetime DESC' THEN
        SELECT id, title, status, insert_datetime FROM news ORDER BY insert_datetime DESC LIMIT LimitValue OFFSET OffsetValue;
      ELSE
        SELECT id, title, status, insert_datetime FROM news ORDER BY id DESC LIMIT LimitValue OFFSET OffsetValue;
      END IF;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectNostocksCount` ()   BEGIN
    SELECT count(id) FROM items WHERE status = 'public' AND stock_quantity = 0;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectOrder` (IN `OrderIdValue` INT(6))   BEGIN
    SELECT orders.*, items.id AS item_id, items.name AS item_name, items.item_serial, items.standard, members.family_name, members.first_name, members.telnumber, members.mail_address, departments.name AS department_name FROM `orders` INNER JOIN items ON orders.item_id = items.id INNER JOIN members ON orders.member_id = members.id INNER JOIN departments ON items.department_id = departments.id WHERE orders.id = OrderIdValue ORDER BY orders.id DESC LIMIT 1;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectOrderHistoryList` (IN `MemberIdValue` INT(6))   BEGIN
    SELECT orders.id AS order_id, orders.insert_datetime, orders.order_number, orders.quantity, orders.price, items.id AS item_id, items.name, items.item_serial, items.standard, item_images.path FROM `orders` INNER JOIN items ON orders.item_id = items.id LEFT OUTER JOIN item_images ON items.id = item_images.item_id AND item_images.kinds = 1 WHERE orders.member_id = MemberIdValue ORDER BY orders.id DESC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectOrderInfo` (IN `TradingIdValue` VARCHAR(16))   BEGIN
    SELECT orders.*, items.id AS item_id, items.name AS item_name, items.standard, items.price, items.postage, taxes.tax_value, pay_types.name AS pay_type_name FROM `orders` INNER JOIN items ON orders.item_id = items.id INNER JOIN taxes ON items.tax_id = taxes.id INNER JOIN pay_types ON orders.pay_type = pay_types.id  WHERE orders.trading_id = TradingIdValue ORDER BY orders.id;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectOrderList` (IN `MemberIdValue` INT(6), IN `StatusValue` INT(6), IN `LimitValue` INT(3), IN `OffsetValue` INT(3))   BEGIN
    IF LimitValue = -1 THEN
      SELECT orders.delivery_family_name, orders.delivery_first_name, orders.delivery_postal_code, orders.delivery_address, orders.delivery_telnumber, orders.delivery_slip_flag, items.name, items.standard, items.price, taxes.tax_value FROM orders INNER JOIN items ON orders.item_id = items.id INNER JOIN taxes ON items.tax_id = taxes.id WHERE orders.member_id = MemberIdValue AND orders.status = StatusValue ORDER BY orders.id ASC;
    ELSE
      SELECT orders.delivery_family_name, orders.delivery_first_name, orders.delivery_postal_code, orders.delivery_address, orders.delivery_telnumber, orders.delivery_slip_flag, items.name, items.standard, items.price, taxes.tax_value FROM orders INNER JOIN items ON orders.item_id = items.id INNER JOIN taxes ON items.tax_id = taxes.id WHERE orders.member_id = MemberIdValue AND orders.status = StatusValue ORDER BY orders.id ASC LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectOrderListforTradingId` (IN `TradingIdValue` VARCHAR(16))   BEGIN
    SELECT id, delivery_family_name, delivery_first_name, delivery_family_name_furigana, delivery_first_name_furigana, delivery_postal_code, delivery_address, delivery_telnumber, delivery_slip_flag, myhouse_flag FROM orders WHERE trading_id = TradingIdValue GROUP BY delivery_family_name, delivery_first_name, delivery_address ORDER BY id ASC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectOrderPostAddressList` (IN `MemberIdValue` INT(6))   BEGIN
    SELECT id, delivery_family_name, delivery_first_name, delivery_family_name_furigana, delivery_first_name_furigana, delivery_postal_code, delivery_address, delivery_telnumber, delivery_slip_flag, myhouse_flag FROM order_post_addresses WHERE member_id = MemberIdValue ORDER BY id ASC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectOrdersCount` (IN `StatusValue` INT(6))   BEGIN
    SELECT count(id) FROM orders WHERE status = StatusValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectOrderStatusList` ()   BEGIN
    SELECT id, name FROM order_status ORDER BY id DESC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectPayType` ()   BEGIN
    SELECT id, name FROM pay_types ORDER BY id ASC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectPublicItem` (IN `ItemIdValue` INT(6))   BEGIN
    SELECT items.*, taxes.tax_value, item_images.path, item_images.kinds FROM items LEFT OUTER JOIN item_images ON items.id = item_images.item_id AND item_images.kinds = 1 INNER JOIN taxes ON items.tax_id = taxes.id WHERE items.status = 'public' AND items.id = ItemIdValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectPublicItemImage` (IN `ItemIdValue` INT(6), IN `KindsValue` INT(1))   BEGIN
  SELECT item_images.id, item_images.path FROM item_images INNER JOIN items ON item_images.item_id = items.id
    WHERE item_images.item_id = ItemIdValue AND item_images.kinds = KindsValue AND items.status = 'public'
    ORDER BY CASE
      WHEN KindsValue = '2' THEN SUBSTRING(item_images.path, 17, 1)
      ELSE item_images.id
    END
  ;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectPublicItemList` (IN `CategoryIdValue` VARCHAR(6), IN `KeywordValue` VARCHAR(255), IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
    IF LimitValue = -1 THEN
      SELECT items.*, item_images.path, categories.name AS category_name, taxes.tax_value AS tax FROM items LEFT OUTER JOIN item_images ON items.id = item_images.item_id AND item_images.kinds = 1 INNER JOIN categories ON items.category_id = categories.id INNER JOIN taxes ON items.tax_id = taxes.id WHERE items.category_id REGEXP CategoryIdValue AND items.name REGEXP KeywordValue AND items.status = 'public' ORDER BY items.id DESC;
    ELSE
      SELECT items.*, item_images.path, categories.name AS category_name, taxes.tax_value AS tax FROM items LEFT OUTER JOIN item_images ON items.id = item_images.item_id AND item_images.kinds = 1 INNER JOIN categories ON items.category_id = categories.id INNER JOIN taxes ON items.tax_id = taxes.id WHERE items.category_id REGEXP CategoryIdValue AND items.name REGEXP KeywordValue AND items.status = 'public' ORDER BY items.id DESC LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectPublicNews` (IN `NewsIdValue` INT(6))   BEGIN
    SELECT title, body, publication_datetime FROM news WHERE status = 'public' AND id = NewsIdValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectPublicNewsList` (IN `OrderValue` VARCHAR(50), IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
    IF LimitValue = -1 THEN
      IF OrderValue = 'news.insert_datetime DESC' THEN
        SELECT news.id, news.title, news.body, news.publication_datetime, news_images.path FROM news INNER JOIN news_images ON news.id = news_images.news_id WHERE news.status = 'public' AND news_images.kinds = 1 AND news.publication_datetime < now() ORDER BY news.insert_datetime DESC;
      END IF;
    ELSE
      IF OrderValue = 'news.insert_datetime DESC' THEN
        SELECT news.id, news.title, news.body, news.publication_datetime, news_images.path FROM news INNER JOIN news_images ON news.id = news_images.news_id WHERE news.status = 'public' AND news_images.kinds = 1 AND news.publication_datetime < now() ORDER BY news.insert_datetime DESC LIMIT LimitValue OFFSET OffsetValue;
      END IF;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectRecommendItemList` ()   BEGIN
  SELECT items.id, items.name, items.price, items.stock_quantity, item_images.path, taxes.tax_value AS tax FROM items LEFT JOIN item_images ON items.id = item_images.item_id AND item_images.kinds = 1 INNER JOIN taxes ON items.tax_id = taxes.id WHERE items.status = 'public' AND items.recommend_flag = 1 ORDER BY items.insert_datetime DESC LIMIT 7;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectRepresentativeMailRecipient` (IN `MailIdValue` INT(6))   BEGIN
    SELECT members.family_name, members.first_name FROM mail_recipients INNER JOIN mailing_list_members ON mail_recipients.mailing_list_id = mailing_list_members.mailing_list_id INNER JOIN members ON mailing_list_members.member_id = members.id WHERE mail_recipients.mail_id = MailIdValue ORDER BY mail_recipients.id DESC LIMIT 1;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectSearchAddressee` (IN `MemberIdValue` INT(6), IN `NameValue` VARCHAR(50), IN `OrderValue` VARCHAR(50), IN `LimitValue` INT(3), IN `OffsetValue` INT(3))   BEGIN
    IF LimitValue = -1 THEN
      SELECT * FROM addressees WHERE member_id = MemberIdValue AND (family_name REGEXP NameValue OR first_name REGEXP NameValue OR family_name_furigana REGEXP NameValue OR first_name_furigana REGEXP NameValue) ORDER BY id DESC;
    ELSE
      SELECT * FROM addressees WHERE member_id = MemberIdValue AND (family_name REGEXP NameValue OR first_name REGEXP NameValue OR family_name_furigana REGEXP NameValue OR first_name_furigana REGEXP NameValue) ORDER BY id DESC LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectSearchItem` (IN `KeywordValue` VARCHAR(50), IN `DepartmentIdValue` VARCHAR(6))   BEGIN
    SELECT items.*, item_images.path, categories.name AS category_name, departments.name AS department_name FROM items LEFT OUTER JOIN item_images ON items.id = item_images.item_id AND item_images.kinds = 1 INNER JOIN categories ON items.category_id = categories.id INNER JOIN departments ON items.department_id = departments.id WHERE items.name REGEXP KeywordValue AND items.department_id REGEXP DepartmentIdValue ORDER BY items.id DESC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectSearchMailingList` (IN `TitleValue` VARCHAR(255))   BEGIN
    SELECT * FROM mailing_lists WHERE title REGEXP TitleValue ORDER BY id DESC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectSearchMailList` (IN `TitleValue` VARCHAR(255))   BEGIN
    SELECT
    M.id,
    M.destination_type,
    M.title,
    M.status,
    M.insert_datetime,
    ME.family_name,
    ME.first_name
    FROM mails AS M
    LEFT OUTER JOIN mail_recipients AS MR
    ON MR.id = (
        SELECT MR2.id FROM mail_recipients AS MR2
        WHERE M.id = MR2.mail_id
        ORDER BY MR2.id LIMIT 1)
    LEFT OUTER JOIN mailing_list_members AS MLM
    ON MLM.id = (
        SELECT MLM2.id FROM mailing_list_members AS MLM2
        WHERE MR.mailing_list_id = MLM2.mailing_list_id
        ORDER BY MLM2.id LIMIT 1)
    LEFT OUTER JOIN members AS ME ON MLM.member_id = ME.id WHERE M.title REGEXP TitleValue ORDER BY M.id DESC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectSearchMember` (IN `KeywordNameValue` VARCHAR(255))   BEGIN
    SELECT id, family_name, first_name, family_name_furigana, first_name_furigana, address, telnumber, mail_address, insert_datetime,update_datetime FROM members WHERE family_name REGEXP KeywordNameValue OR first_name REGEXP KeywordNameValue OR family_name_furigana REGEXP KeywordNameValue OR first_name_furigana REGEXP KeywordNameValue ORDER BY id DESC;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectSearchOrder` (IN `KeywordValue` VARCHAR(50), IN `OrderValue` VARCHAR(50))   BEGIN
    SELECT members.family_name, members.first_name, orders.name, orders.quantity, orders.number, order_status.name, departments.name FROM orders INNER JOIN members ON orders.member_id = members.id INNER JOIN order_status ON orders.status = order_status.id INNER JOIN items ON orders.item_id = items.id INNER JOIN departmentes ON items.department_id = departments.id WHERE family_name = '%KeywordValue%' OR first_name = '%KeywordValue%' OR family_name_furigana = '%KeywordValue%' OR first_name_furigana = '%KeywordValue%' ORDER BY OrderValue;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_SelectTaxList` (IN `LimitValue` INT(3), IN `OffsetValue` INT(5))   BEGIN
    IF LimitValue = -1 THEN
      SELECT id, tax_value FROM taxes ORDER BY id ASC;
    ELSE
      SELECT id, tax_value FROM taxes ORDER BY id ASC LIMIT LimitValue OFFSET OffsetValue;
    END IF;
  END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateAddressee` (IN `IdValue` INT(6), IN `MemberIdValue` INT(6), IN `FamilyNameValue` VARCHAR(255), IN `FirstNameValue` VARCHAR(255), IN `FamilyNameFuriganaValue` VARCHAR(255), IN `FirstNameFuriganaValue` VARCHAR(255), IN `PostalCodeValue` VARCHAR(10), IN `AddressValue` VARCHAR(255), IN `TelnumberValue` VARCHAR(20))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF FamilyNameValue != '' THEN
      UPDATE addressees SET family_name = FamilyNameValue , update_datetime = now()  WHERE id = IdValue AND member_id = MemberIdValue;
    END IF;
    IF FirstNameValue != '' THEN
      UPDATE addressees SET first_name = FirstNameValue , update_datetime = now()  WHERE id = IdValue AND member_id = MemberIdValue;
    END IF;
    IF FamilyNameFuriganaValue != '' THEN
      UPDATE addressees SET family_name_furigana = FamilyNameFuriganaValue , update_datetime = now()  WHERE id = IdValue AND member_id = MemberIdValue;
    END IF;
    IF FirstNameFuriganaValue != '' THEN
      UPDATE addressees SET first_name_furigana = FirstNameFuriganaValue , update_datetime = now()  WHERE id = IdValue AND member_id = MemberIdValue;
    END IF;
    IF PostalCodeValue != '' THEN
      UPDATE addressees SET postal_code = PostlCodeValue , update_datetime = now()  WHERE id = IdValue AND member_id = MemberIdValue;
    END IF;
    IF AddressValue != '' THEN
      UPDATE addressees SET address = AddressValue , update_datetime = now()  WHERE id = IdValue AND member_id = MemberIdValue;
    END IF;
    IF TelnumberValue != '' THEN
      UPDATE addressees SET telnumber = TelnumberValue , update_datetime = now()  WHERE id = IdValue AND member_id = MemberIdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateAdmin` (IN `IdValue` INT(6), IN `NameValue` VARCHAR(255), IN `MailAddressValue` VARCHAR(255), IN `DepartmentIdValue` INT(6), IN `AuthorityIdValue` INT(2), IN `StatusValue` INT(1))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF NameValue != '' THEN
      UPDATE admins SET name = NameValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF MailAddressValue != '' THEN
      UPDATE admins SET mail_address = MailAddressValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF DepartmentIdValue IS NOT NULL THEN
      UPDATE admins SET department_id = DepartmentIdValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF AuthorityIdValue IS NOT NULL THEN
      UPDATE admins SET authority_id = AuthorityIdValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF StatusValue IS NOT NULL THEN
      UPDATE admins SET status = StatusValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateAdminPassword` (IN `MailaddressValue` VARCHAR(255), IN `PasswordValue` VARCHAR(255), IN `EnckeyValue` VARCHAR(50))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF PasswordValue != '' THEN
      UPDATE admins SET password = AES_ENCRYPT(PasswordValue, EnckeyValue) , update_datetime = now()  WHERE mail_address = MailaddressValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateAdminStatus` (IN `IdValue` INT(6), IN `StatusValue` INT(1))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF StatusValue IS NOT NULL THEN
      UPDATE admins SET status = StatusValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateCategory` (IN `CategoryIdValue` INT(6), IN `NameValue` VARCHAR(50))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF NameValue != '' THEN
      UPDATE categories SET name = NameValue, update_datetime = now() WHERE id = CategoryIdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateDelivereAddress` (IN `MemberIdValue` INT(6), IN `IdValue` INT(6), IN `FamilyNameValue` VARCHAR(255), IN `FirstNameValue` VARCHAR(255), IN `FamilyNameFuriganaValue` VARCHAR(255), IN `FirstNameFuriganaValue` VARCHAR(255), IN `PostalCodeValue` VARCHAR(10), IN `AddressValue` VARCHAR(255), IN `TelnumberValue` VARCHAR(20))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF FamilyNameValue != '' THEN
      UPDATE addressees SET family_name = FamilyNameValue , update_datetime = now()  WHERE id = IdValue AND member_id = MemberIdValue;
    END IF;
    IF FirstNameValue != '' THEN
      UPDATE addressees SET first_name = FirstNameValue , update_datetime = now()  WHERE id = IdValue AND member_id = MemberIdValue;
    END IF;
    IF FamilyNameFuriganaValue != '' THEN
      UPDATE addressees SET family_name_furigana = FamilyNameFuriganaValue , update_datetime = now()  WHERE id = IdValue AND member_id = MemberIdValue;
    END IF;
    IF FirstNameFuriganaValue != '' THEN
      UPDATE addressees SET first_name_furigana = FirstNameFuriganaValue , update_datetime = now()  WHERE id = IdValue AND member_id = MemberIdValue;
    END IF;
    IF PostalCodeValue != '' THEN
      UPDATE addressees SET postal_code = PostalCodeValue , update_datetime = now()  WHERE id = IdValue AND member_id = MemberIdValue;
    END IF;
    IF AddressValue != '' THEN
      UPDATE addressees SET address = AddressValue , update_datetime = now()  WHERE id = IdValue AND member_id = MemberIdValue;
    END IF;
    IF TelnumberValue != '' THEN
      UPDATE addressees SET telnumber = TelnumberValue , update_datetime = now()  WHERE id = IdValue AND member_id = MemberIdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateDepartment` (IN `DepartmentIdValue` INT(6), IN `NameValue` VARCHAR(50))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF NameValue != '' THEN
      UPDATE departments SET name = NameValue, update_datetime = now() WHERE id = DepartmentIdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateItem` (IN `IdValue` INT(3), IN `NameValue` VARCHAR(255), IN `CategoryIdValue` INT(6), IN `RecommendFlagValue` INT(1), IN `ItemSerialValue` VARCHAR(255), IN `StandardValue` VARCHAR(50), IN `DescriptionValue` LONGTEXT, IN `PriceValue` INT(6), IN `TaxIdValue` INT(6), IN `PostageValue` INT(6), IN `StockQuantityValue` INT(6), IN `DepartmentIdValue` INT(6), IN `StatusValue` VARCHAR(50))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF NameValue != '' THEN
      UPDATE items SET name = NameValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF CategoryIdValue  IS NOT NULL THEN
      UPDATE items SET category_id = CategoryIdValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF RecommendFlagValue IS NOT NULL THEN
      UPDATE items SET recommend_flag = RecommendFlagValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF ItemSerialValue != '' THEN
      UPDATE items SET item_serial = ItemSerialValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF StandardValue != '' THEN
      UPDATE items SET standard = StandardValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF DescriptionValue != '' THEN
      UPDATE items SET description = DescriptionValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF PriceValue IS NOT NULL THEN
      UPDATE items SET price = PriceValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF TaxIdValue IS NOT NULL THEN
      UPDATE items SET tax_id = TaxIdValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF PostageValue IS NOT NULL THEN
      UPDATE items SET postage = PostageValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF StockQuantityValue IS NOT NULL THEN
      UPDATE items SET stock_quantity = StockQuantityValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF DepartmentIdValue IS NOT NULL THEN
      UPDATE items SET department_id = DepartmentIdValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF StatusValue != "" THEN
      UPDATE items SET status = StatusValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateItemImage` (IN `IdValue` INT(6), IN `PathValue` VARCHAR(255))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF PathValue != '' THEN
      UPDATE item_images SET path = PathValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateItemStatus` (IN `IdValue` INT(3), IN `StatusValue` VARCHAR(50), IN `DepartmentIdValue` VARCHAR(6))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF StatusValue != '' THEN
      UPDATE items SET status = StatusValue , update_datetime = now()  WHERE id = IdValue AND department_id REGEXP DepartmentIdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateItemStockQuantity` (IN `IdValue` INT(6), IN `OrderedQuantityValue` INT(6))  COMMENT '注文時に注文個数分だけ商品の在庫数を減らす' BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    UPDATE items SET stock_quantity = stock_quantity - OrderedQuantityValue, update_datetime = now() WHERE id = IdValue;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateMail` (IN `IdValue` INT(6), IN `DestinationTypeValue` INT(2), IN `TitleValue` VARCHAR(255), IN `BodyValue` LONGTEXT, IN `StatusValue` VARCHAR(50))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF DestinationTypeValue IS NOT NULL THEN
      UPDATE mails SET destination_type = DestinationTypeValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF TitleValue != '' THEN
      UPDATE mails SET title = TitleValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF BodyValue != '' THEN
      UPDATE mails SET body = BodyValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF StatusValue != '' THEN
      UPDATE mails SET status = StatusValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateMailingList` (IN `IdValue` INT(6), IN `TitleValue` VARCHAR(255))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF TitleValue != '' THEN
      UPDATE mailing_lists SET title = TitleValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateMainNewsImage` (IN `PathValue` VARCHAR(255), IN `NewsIdValue` INT(6))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF PathValue != '' THEN
      UPDATE news_images SET path = PathValue , update_datetime = now()  WHERE news_id = NewsIdValue AND kinds = 1;
    ELSE
      SELECT @res := id FROM news_images WHERE news_id = NewsIdValue AND kinds = 1;
      IF @res != '' THEN
        DELETE FROM news_images WHERE news_id = NewsIdValue AND kinds = 1 LIMIT 1;
      END IF;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateMember` (IN `IdValue` INT(6), IN `FamilyNameValue` VARCHAR(255), IN `FirstNameValue` VARCHAR(255), IN `FamilyNameFuriganaValue` VARCHAR(255), IN `FirstNameFuriganaValue` VARCHAR(255), IN `BirthdayValue` DATETIME, IN `PostalCodeValue` VARCHAR(10), IN `AddressValue` VARCHAR(255), IN `TelnumberValue` VARCHAR(20), IN `MailAddressValue` VARCHAR(255), IN `MailMagazineFlagValue` INT(1))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF FamilyNameValue != '' THEN
      UPDATE members SET family_name = FamilyNameValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF FirstNameValue != '' THEN
      UPDATE members SET first_name = FirstNameValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF FamilyNameFuriganaValue != '' THEN
      UPDATE members SET family_name_furigana = FamilyNameFuriganaValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF FirstNameFuriganaValue != '' THEN
      UPDATE members SET first_name_furigana = FirstNameFuriganaValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF BirthdayValue IS NOT NULL THEN
      UPDATE members SET birthday = BirthdayValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF PostalCodeValue != '' THEN
      UPDATE members SET postal_code = PostalCodeValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF AddressValue != '' THEN
      UPDATE members SET address = AddressValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF TelnumberValue != '' THEN
      UPDATE members SET telnumber = TelnumberValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF MailAddressValue != '' THEN
      UPDATE members SET mail_address = MailAddressValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF MailMagazineFlagValue IS NOT NULL THEN
      UPDATE members SET mail_magazine_flag = MailMagazineFlagValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateMemberPassword` (IN `MailaddressValue` VARCHAR(255), IN `PasswordValue` VARCHAR(255), IN `EnckeyValue` VARCHAR(50))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF PasswordValue != '' THEN
      UPDATE members SET password = AES_ENCRYPT(PasswordValue, EnckeyValue) , update_datetime = now()  WHERE mail_address = MailaddressValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateNews` (IN `IdValue` INT(6), IN `TitleValue` VARCHAR(255), IN `BodyValue` LONGTEXT, IN `StatusValue` VARCHAR(50), IN `PublicationDatetimeValue` DATETIME)   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF TitleValue != '' THEN
      UPDATE news SET title = TitleValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF BodyValue != '' THEN
      UPDATE news SET body = BodyValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF StatusValue != '' THEN
      UPDATE news SET status = StatusValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF PublicationDatetimeValue IS NOT NULL THEN
      UPDATE news SET publication_datetime = PublicationDatetimeValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateNewsStatus` (IN `IdValue` INT(6), IN `StatusValue` VARCHAR(50))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF StatusValue != '' THEN
      UPDATE news SET status = StatusValue, update_datetime = now() WHERE id = IdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateOrderDeliveryAndPayInfo` (IN `IdValue` INT(6), IN `DeliveryTypeValue` INT(2), IN `PayTypeValue` INT(2), IN `DeliveryDateValue` DATE, IN `DeliveryTimeValue` VARCHAR(50))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF DeliveryTypeValue IS NOT NULL THEN
      UPDATE orders SET  delivery_type = DeliveryTypeValue, update_datetime = now() WHERE id = IdValue;
    END IF;
    IF PayTypeValue IS NOT NULL THEN
      UPDATE orders SET  pay_type = PayTypeValue, update_datetime = now() WHERE id = IdValue;
    END IF;
    IF DeliveryDateValue != '' THEN
      UPDATE orders SET  delivery_date= DeliveryDateValue, update_datetime = now() WHERE id = IdValue;
    END IF;
    IF DeliveryTimeValue != '' THEN
      UPDATE orders SET  delivery_time= DeliveryTimeValue, update_datetime = now() WHERE id = IdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateOrderPriceAndNumber` (IN `IdValue` INT(6), IN `PriceValue` INT(6), IN `NumberValue` VARCHAR(50), IN `StatusValue` INT(6))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF PriceValue IS NOT NULL THEN
      UPDATE items SET price = PriceValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF NumberValue != '' THEN
      UPDATE items SET number = NumberValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF StatusValue IS NOT NULL THEN
      UPDATE items SET status = StatusValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateOrderQuantity` (IN `IdValue` INT(6), IN `QuantityValue` INT(6))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF QuantityValue IS NOT NULL THEN
      UPDATE orders SET quantity = QuuantityValue, update_datetime = now() WHERE id = IdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateOrderStatus` (IN `IdValue` INT(6), IN `StatusValue` INT(6), IN `DeliveryCompanyValue` INT(6), IN `TrackingNumberValue` VARCHAR(50))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF StatusValue IS NOT NULL THEN
      UPDATE orders SET status = StatusValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF DeliveryCompanyValue IS NOT NULL THEN
      UPDATE orders SET delivery_company = DeliveryCompanyValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
    IF TrackingNumberValue != '' THEN
      UPDATE orders SET tracking_number = TrackingNumberValue , update_datetime = now()  WHERE id = IdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_UpdateTax` (IN `TaxIdValue` INT(6), IN `TaxValueValue` INT(3))   BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION, SQLWARNING
	BEGIN
		ROLLBACK;
		SELECT 'FALSE' AS result FROM DUAL;
	END;
  START TRANSACTION;
    IF TaxValueValue IS NOT NULL THEN
      UPDATE taxes SET tax_value = TaxValueValue, update_datetime = now() WHERE id = TaxIdValue;
    END IF;
	COMMIT;
	SELECT 'TRUE' AS result FROM DUAL;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- テーブルの構造 `additional_shipping_fees`
--

CREATE TABLE `additional_shipping_fees` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `prefecture_name` varchar(10) NOT NULL COMMENT '都道府県名',
  `area_name` varchar(50) NOT NULL COMMENT '地域名',
  `match_text` varchar(50) NOT NULL COMMENT '判定用一致文字列',
  `additional_shipping_fee` int(11) NOT NULL COMMENT '追加送料'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `additional_shipping_fees`
--

INSERT INTO `additional_shipping_fees` (`id`, `prefecture_name`, `area_name`, `match_text`, `additional_shipping_fee`) VALUES
(1, '長崎県', '対馬市', '長崎県対馬市', 2000),
(2, '長崎県', '五島市', '長崎県五島市', 1500),
(3, '長崎県', '壱岐市', '長崎県壱岐市', 2000),
(4, '沖縄県', '全域', '沖縄県', 2000);

-- --------------------------------------------------------

--
-- テーブルの構造 `addressees`
--

CREATE TABLE `addressees` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `member_id` int(6) NOT NULL COMMENT '会員ID',
  `family_name` varchar(255) DEFAULT NULL COMMENT '姓',
  `first_name` varchar(255) DEFAULT NULL COMMENT '名',
  `family_name_furigana` varchar(255) DEFAULT NULL COMMENT 'セイ',
  `first_name_furigana` varchar(255) DEFAULT NULL COMMENT 'メイ',
  `postal_code` varchar(10) DEFAULT NULL COMMENT '郵便番号',
  `address` varchar(255) DEFAULT NULL COMMENT '住所',
  `telnumber` varchar(20) DEFAULT NULL COMMENT '電話番号',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='お届け先';

--
-- テーブルのデータのダンプ `addressees`
--

INSERT INTO `addressees` (`id`, `member_id`, `family_name`, `first_name`, `family_name_furigana`, `first_name_furigana`, `postal_code`, `address`, `telnumber`, `insert_datetime`, `update_datetime`) VALUES
(54, 88, 'FamilyName', 'FirstName', 'FamilyNameKana', 'FirstNameKana', '8170000', '長崎県対馬市1-2-3', '0234567890', '2022-11-09 11:52:34', '2022-11-09 11:52:34');

-- --------------------------------------------------------

--
-- テーブルの構造 `admins`
--

CREATE TABLE `admins` (
  `id` int(4) NOT NULL COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT '名前',
  `mail_address` varchar(255) NOT NULL COMMENT 'メールアドレス',
  `password` blob NOT NULL COMMENT 'パスワード',
  `department_id` int(6) NOT NULL COMMENT '担当部署ID',
  `authority_id` int(2) NOT NULL COMMENT '権限ID',
  `status` int(1) NOT NULL DEFAULT 1 COMMENT '状態',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理者';

--
-- テーブルのデータのダンプ `admins`
--

INSERT INTO `admins` (`id`, `name`, `mail_address`, `password`, `department_id`, `authority_id`, `status`, `insert_datetime`, `update_datetime`) VALUES
(32, 'Test Administrator', 'admin@test.test', 0xc36bf867de71abf16c37fd9d37ea3dc7, 8, 1, 1, '2022-11-09 11:47:42', '2022-11-09 11:47:42');

-- --------------------------------------------------------

--
-- テーブルの構造 `admin_authorities`
--

CREATE TABLE `admin_authorities` (
  `id` int(4) NOT NULL COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT '名前',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理者権限種類';

--
-- テーブルのデータのダンプ `admin_authorities`
--

INSERT INTO `admin_authorities` (`id`, `name`, `insert_datetime`, `update_datetime`) VALUES
(1, '管理者', '2021-10-15 23:53:40', '2021-10-15 23:53:40');

-- --------------------------------------------------------

--
-- テーブルの構造 `carts`
--

CREATE TABLE `carts` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `member_id` int(6) DEFAULT NULL COMMENT '会員ID',
  `item_id` int(6) DEFAULT NULL COMMENT '商品ID',
  `quantity` int(6) NOT NULL COMMENT '個数',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='カート';

--
-- テーブルのデータのダンプ `carts`
--

INSERT INTO `carts` (`id`, `member_id`, `item_id`, `quantity`, `insert_datetime`, `update_datetime`) VALUES
(367, 88, 200, 1, '2022-11-09 11:57:12', '2022-11-09 11:57:12');

-- --------------------------------------------------------

--
-- テーブルの構造 `categories`
--

CREATE TABLE `categories` (
  `id` int(4) NOT NULL COMMENT 'ID',
  `name` varchar(50) NOT NULL COMMENT 'カテゴリ名',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='カテゴリ';

--
-- テーブルのデータのダンプ `categories`
--

INSERT INTO `categories` (`id`, `name`, `insert_datetime`, `update_datetime`) VALUES
(1, '果物', '2021-10-08 13:21:52', '2021-10-25 16:52:45'),
(2, 'お肉', '2021-10-08 13:21:52', '2021-10-08 13:21:52'),
(3, '野菜', '2021-10-08 13:26:31', '2021-10-08 13:26:31'),
(4, '加工品', '2021-10-08 13:26:31', '2021-10-08 13:26:31'),
(5, '生花', '2021-10-08 13:26:31', '2021-10-08 13:26:31'),
(6, 'その他', '2021-10-08 13:27:15', '2021-10-08 13:27:15'),
(13, 'テストカテゴリ', '2022-11-07 15:48:32', '2022-11-07 15:48:32'),
(14, 'Test Category', '2022-11-09 10:37:33', '2022-11-09 10:37:33');

-- --------------------------------------------------------

--
-- テーブルの構造 `delivery_type`
--

CREATE TABLE `delivery_type` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT '配送名',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='配送種類';

-- --------------------------------------------------------

--
-- テーブルの構造 `departments`
--

CREATE TABLE `departments` (
  `id` int(4) NOT NULL COMMENT 'ID',
  `name` varchar(50) NOT NULL COMMENT '担当部署名',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='担当部署';

--
-- テーブルのデータのダンプ `departments`
--

INSERT INTO `departments` (`id`, `name`, `insert_datetime`, `update_datetime`) VALUES
(1, '部署A', '2021-10-08 13:24:34', '2022-11-09 11:39:36'),
(2, '部署B', '2021-10-08 13:24:34', '2022-11-09 11:39:42'),
(3, '部署D', '2021-10-08 13:25:51', '2022-11-09 11:39:48'),
(7, '部署E', '2022-11-07 10:03:58', '2022-11-09 11:39:53'),
(8, 'Test Department', '2022-11-09 10:35:39', '2022-11-09 10:35:39');

-- --------------------------------------------------------

--
-- テーブルの構造 `favorites`
--

CREATE TABLE `favorites` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `member_id` int(6) DEFAULT NULL COMMENT '会員ID',
  `item_id` int(6) DEFAULT NULL COMMENT '商品ID',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='お気に入り';

--
-- テーブルのデータのダンプ `favorites`
--

INSERT INTO `favorites` (`id`, `member_id`, `item_id`, `insert_datetime`, `update_datetime`) VALUES
(109, 88, 200, '2022-11-09 11:52:48', '2022-11-09 11:52:48');

-- --------------------------------------------------------

--
-- テーブルの構造 `items`
--

CREATE TABLE `items` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT '商品名',
  `category_id` int(6) NOT NULL COMMENT 'カテゴリID',
  `recommend_flag` int(1) NOT NULL DEFAULT 0 COMMENT 'おすすめ商品に表示フラグ',
  `item_serial` varchar(255) NOT NULL COMMENT '商品番号',
  `standard` varchar(50) NOT NULL COMMENT '規格',
  `description` longtext DEFAULT NULL COMMENT '商品説明',
  `price` int(6) NOT NULL COMMENT '金額',
  `tax_id` int(6) NOT NULL COMMENT '税率ID',
  `postage` int(6) NOT NULL COMMENT '送料',
  `stock_quantity` int(6) NOT NULL COMMENT '在庫数',
  `department_id` int(6) NOT NULL COMMENT '担当部署ID',
  `status` varchar(50) NOT NULL DEFAULT 'draft' COMMENT '状態',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `items`
--

INSERT INTO `items` (`id`, `name`, `category_id`, `recommend_flag`, `item_serial`, `standard`, `description`, `price`, `tax_id`, `postage`, `stock_quantity`, `department_id`, `status`, `insert_datetime`, `update_datetime`) VALUES
(200, 'ItemName', 14, 1, 'ItemNumber', 'Standard', 'Item description is here.\r\nItem description is here.\r\nItem description is here.\r\nItem description is here.\r\nItem description is here.', 9999, 2, 500, 988, 8, 'public', '2022-11-09 10:37:17', '2022-11-09 11:56:36');

-- --------------------------------------------------------

--
-- テーブルの構造 `item_images`
--

CREATE TABLE `item_images` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `item_id` int(6) NOT NULL COMMENT '商品ID',
  `kinds` int(1) NOT NULL DEFAULT 2 COMMENT '種別',
  `path` varchar(255) NOT NULL COMMENT '画像パス',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品画像';

--
-- テーブルのデータのダンプ `item_images`
--

INSERT INTO `item_images` (`id`, `item_id`, `kinds`, `path`, `insert_datetime`, `update_datetime`) VALUES
(566, 200, 1, '/20221109114840.jpg', '2022-11-09 11:48:40', '2022-11-09 11:48:40'),
(567, 200, 2, '/20221109114840_1.jpg', '2022-11-09 11:48:40', '2022-11-09 11:48:40'),
(568, 200, 2, '/20221109114840_2.jpg', '2022-11-09 11:48:40', '2022-11-09 11:48:40'),
(569, 200, 2, '/20221109114840_3.jpg', '2022-11-09 11:48:40', '2022-11-09 11:48:40'),
(570, 200, 2, '/20221109114840_4.jpg', '2022-11-09 11:48:40', '2022-11-09 11:48:40');

-- --------------------------------------------------------

--
-- テーブルの構造 `mailing_lists`
--

CREATE TABLE `mailing_lists` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `title` varchar(255) NOT NULL COMMENT 'タイトル',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='メーリングリスト';

-- --------------------------------------------------------

--
-- テーブルの構造 `mailing_list_members`
--

CREATE TABLE `mailing_list_members` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `mailing_list_id` int(6) NOT NULL COMMENT 'メーリングリストID',
  `member_id` int(6) NOT NULL COMMENT '会員ID',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='メーリングリスト会員';

-- --------------------------------------------------------

--
-- テーブルの構造 `mails`
--

CREATE TABLE `mails` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `destination_type` int(2) NOT NULL DEFAULT 1 COMMENT '送信先種別',
  `title` varchar(255) DEFAULT NULL COMMENT 'タイトル',
  `body` longtext DEFAULT NULL COMMENT '本文',
  `status` varchar(50) NOT NULL DEFAULT 'draft' COMMENT '状態',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='メールマガジン';

-- --------------------------------------------------------

--
-- テーブルの構造 `mail_recipients`
--

CREATE TABLE `mail_recipients` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `mail_id` int(6) NOT NULL COMMENT 'メルマガID',
  `mailing_list_id` int(6) NOT NULL COMMENT 'メーリングリストID',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='メルマガ送信先メーリングリスト';

-- --------------------------------------------------------

--
-- テーブルの構造 `members`
--

CREATE TABLE `members` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `family_name` varchar(255) DEFAULT NULL COMMENT '姓',
  `first_name` varchar(255) DEFAULT NULL COMMENT '名',
  `family_name_furigana` varchar(255) DEFAULT NULL COMMENT 'セイ',
  `first_name_furigana` varchar(255) DEFAULT NULL COMMENT 'メイ',
  `birthday` date DEFAULT NULL COMMENT '生年月日',
  `postal_code` varchar(10) DEFAULT NULL COMMENT '郵便番号',
  `address` varchar(255) DEFAULT NULL COMMENT '住所',
  `telnumber` varchar(20) DEFAULT NULL COMMENT '電話番号',
  `mail_address` varchar(255) DEFAULT NULL COMMENT 'メールアドレス',
  `mail_magazine_flag` int(1) NOT NULL DEFAULT 0 COMMENT 'メルマガ配信希望フラグ',
  `password` blob DEFAULT NULL COMMENT 'パスワード',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会員';

--
-- テーブルのデータのダンプ `members`
--

INSERT INTO `members` (`id`, `family_name`, `first_name`, `family_name_furigana`, `first_name_furigana`, `birthday`, `postal_code`, `address`, `telnumber`, `mail_address`, `mail_magazine_flag`, `password`, `insert_datetime`, `update_datetime`) VALUES
(88, 'FamilyName', 'FirstName', 'FamilyNameKana', 'FirstNameKana', '1980-01-01', '8500001', '長崎県長崎市西山1-2-3', '0234567890', 'member@test.test', 0, 0xc36bf867de71abf16c37fd9d37ea3dc7, '2022-11-09 11:50:46', '2022-11-09 11:50:46');

-- --------------------------------------------------------

--
-- テーブルの構造 `member_applications`
--

CREATE TABLE `member_applications` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `mail_address` varchar(255) NOT NULL COMMENT 'メールアドレス',
  `serial` varchar(255) NOT NULL COMMENT 'シリアルナンバー',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会員新規登録申請';

-- --------------------------------------------------------

--
-- テーブルの構造 `news`
--

CREATE TABLE `news` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `title` varchar(255) DEFAULT NULL COMMENT 'タイトル',
  `body` longtext DEFAULT NULL COMMENT '本文',
  `status` varchar(50) NOT NULL DEFAULT 'draft' COMMENT '状態',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時',
  `publication_datetime` datetime NOT NULL COMMENT '公開日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='お知らせ';

-- --------------------------------------------------------

--
-- テーブルの構造 `news_images`
--

CREATE TABLE `news_images` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `news_id` int(6) NOT NULL COMMENT 'お知らせID',
  `kinds` int(1) NOT NULL DEFAULT 2 COMMENT '種別',
  `path` varchar(255) NOT NULL COMMENT '画像パス',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='お知らせ画像';

-- --------------------------------------------------------

--
-- テーブルの構造 `orders`
--

CREATE TABLE `orders` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `member_id` int(6) NOT NULL COMMENT '注文者会員ID',
  `trading_id` varchar(24) DEFAULT NULL COMMENT '支払い取引ID',
  `item_id` int(6) DEFAULT NULL COMMENT '商品ID',
  `quantity` int(6) DEFAULT NULL COMMENT '数量',
  `delivery_family_name` varchar(255) DEFAULT NULL COMMENT '届け先姓',
  `delivery_first_name` varchar(255) DEFAULT NULL COMMENT '届け先名',
  `delivery_family_name_furigana` varchar(255) DEFAULT NULL COMMENT '届け先セイ',
  `delivery_first_name_furigana` varchar(255) DEFAULT NULL COMMENT '届け先メイ',
  `delivery_postal_code` varchar(10) DEFAULT NULL COMMENT '届け先郵便番号',
  `delivery_address` varchar(255) DEFAULT NULL COMMENT '届け先住所',
  `delivery_telnumber` varchar(20) DEFAULT NULL COMMENT '届け先電話番号',
  `delivery_slip_flag` int(1) NOT NULL DEFAULT 0 COMMENT '納品書フラグ',
  `delivery_date` date DEFAULT NULL COMMENT 'お届け日',
  `delivery_time` varchar(50) DEFAULT NULL COMMENT 'お届け時間',
  `delivery_type` int(2) DEFAULT NULL COMMENT '配送方法',
  `pay_type` int(2) DEFAULT NULL COMMENT '支払い方法',
  `price` int(6) DEFAULT NULL COMMENT '決済金額',
  `order_number` varchar(50) DEFAULT NULL COMMENT '注文番号',
  `status` int(6) NOT NULL DEFAULT 1 COMMENT '注文状態',
  `delivery_company` int(6) NOT NULL DEFAULT 1 COMMENT '配送業者',
  `tracking_number` varchar(50) DEFAULT NULL COMMENT '追跡番号',
  `myhouse_flag` int(1) NOT NULL DEFAULT 0 COMMENT '自宅フラグ',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='注文';

--
-- テーブルのデータのダンプ `orders`
--

INSERT INTO `orders` (`id`, `member_id`, `trading_id`, `item_id`, `quantity`, `delivery_family_name`, `delivery_first_name`, `delivery_family_name_furigana`, `delivery_first_name_furigana`, `delivery_postal_code`, `delivery_address`, `delivery_telnumber`, `delivery_slip_flag`, `delivery_date`, `delivery_time`, `delivery_type`, `pay_type`, `price`, `order_number`, `status`, `delivery_company`, `tracking_number`, `myhouse_flag`, `insert_datetime`, `update_datetime`) VALUES
(673, 88, 'mix221109115148', 200, 10, 'FamilyName', 'FirstName', 'FamilyNameKana', 'FirstNameKana', '8500001', '長崎県長崎市西山1-2-3', '0234567890', 1, NULL, NULL, NULL, 2, 110480, 'mix221109115148', 2, 1, NULL, 0, '2022-11-09 11:51:48', '2022-11-09 11:51:48'),
(674, 88, 'bfx221109115606', 200, 1, 'FamilyName', 'FirstName', 'FamilyNameKana', 'FirstNameKana', '8500001', '長崎県長崎市西山1-2-3', '0234567890', 1, NULL, NULL, NULL, 2, 11498, 'bfx221109115606', 2, 1, NULL, 0, '2022-11-09 11:56:06', '2022-11-09 11:56:06'),
(675, 88, 'bgz221109115636', 200, 1, 'FamilyName', 'FirstName', 'FamilyNameKana', 'FirstNameKana', '8170000', '長崎県対馬市1-2-3', '0234567890', 1, NULL, NULL, NULL, 2, 13498, 'bgz221109115636', 2, 1, NULL, 0, '2022-11-09 11:56:36', '2022-11-09 11:56:36');

-- --------------------------------------------------------

--
-- テーブルの構造 `orders_temp`
--

CREATE TABLE `orders_temp` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `member_id` int(6) NOT NULL COMMENT '注文者会員ID',
  `trading_id` varchar(16) DEFAULT NULL COMMENT '支払い取引ID',
  `item_id` int(6) DEFAULT NULL COMMENT '商品ID',
  `quantity` int(6) DEFAULT NULL COMMENT '数量',
  `delivery_family_name` varchar(255) DEFAULT NULL COMMENT '届け先姓',
  `delivery_first_name` varchar(255) DEFAULT NULL COMMENT '届け先名',
  `delivery_family_name_furigana` varchar(255) DEFAULT NULL COMMENT '届け先セイ',
  `delivery_first_name_furigana` varchar(255) DEFAULT NULL COMMENT '届け先メイ',
  `delivery_postal_code` varchar(10) DEFAULT NULL COMMENT '届け先郵便番号',
  `delivery_address` varchar(255) DEFAULT NULL COMMENT '届け先住所',
  `delivery_telnumber` varchar(20) DEFAULT NULL COMMENT '届け先電話番号',
  `delivery_slip_flag` int(1) NOT NULL DEFAULT 0 COMMENT '納品書フラグ',
  `delivery_date` date DEFAULT NULL COMMENT 'お届け日',
  `delivery_time` varchar(50) DEFAULT NULL COMMENT 'お届け時間',
  `delivery_type` int(2) DEFAULT NULL COMMENT '配送方法',
  `pay_type` int(2) DEFAULT NULL COMMENT '支払い方法',
  `price` int(6) DEFAULT NULL COMMENT '決済金額',
  `order_number` varchar(50) DEFAULT NULL COMMENT '注文番号',
  `status` int(6) NOT NULL DEFAULT 1 COMMENT '注文状態',
  `myhouse_flag` int(1) NOT NULL DEFAULT 0 COMMENT '自宅フラグ',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='注文';

-- --------------------------------------------------------

--
-- テーブルの構造 `order_post_addresses`
--

CREATE TABLE `order_post_addresses` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `member_id` int(6) NOT NULL COMMENT '注文者会員ID',
  `delivery_family_name` varchar(255) DEFAULT NULL COMMENT '届け先姓',
  `delivery_first_name` varchar(255) DEFAULT NULL COMMENT '届け先名',
  `delivery_family_name_furigana` varchar(255) DEFAULT NULL COMMENT '届け先セイ',
  `delivery_first_name_furigana` varchar(255) DEFAULT NULL COMMENT '届け先メイ',
  `delivery_postal_code` varchar(10) DEFAULT NULL COMMENT '届け先郵便番号',
  `delivery_address` varchar(255) DEFAULT NULL COMMENT '届け先住所',
  `delivery_telnumber` varchar(20) DEFAULT NULL COMMENT '届け先電話番号',
  `delivery_slip_flag` int(1) NOT NULL DEFAULT 0 COMMENT '納品書フラグ',
  `myhouse_flag` int(1) NOT NULL DEFAULT 0 COMMENT '自宅フラグ',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='注文';

-- --------------------------------------------------------

--
-- テーブルの構造 `order_status`
--

CREATE TABLE `order_status` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT 'ステータス名',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='注文状態種類';

--
-- テーブルのデータのダンプ `order_status`
--

INSERT INTO `order_status` (`id`, `name`, `insert_datetime`, `update_datetime`) VALUES
(1, '入金待ち', '2022-06-10 04:13:26', '2022-06-10 04:13:26'),
(2, '支払済み', '2022-06-10 04:13:26', '2022-06-10 04:13:26'),
(3, 'キャンセル', '2022-06-10 06:03:15', '2022-06-10 06:03:15');

-- --------------------------------------------------------

--
-- テーブルの構造 `pay_types`
--

CREATE TABLE `pay_types` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT '支払い方法名',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='支払い種類';

--
-- テーブルのデータのダンプ `pay_types`
--

INSERT INTO `pay_types` (`id`, `name`, `insert_datetime`, `update_datetime`) VALUES
(1, 'コンビニ決済', '2021-11-10 15:30:43', '2021-11-10 15:30:43'),
(2, 'クレジットカード決済', '2021-11-10 15:30:43', '2021-11-10 15:30:43');

-- --------------------------------------------------------

--
-- テーブルの構造 `reissues`
--

CREATE TABLE `reissues` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `serial` varchar(255) NOT NULL COMMENT 'シリアルナンバー',
  `admin_flag` int(1) NOT NULL DEFAULT 0 COMMENT '管理者フラグ',
  `mail_address` varchar(255) NOT NULL COMMENT 'メールアドレス',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='パスワード再発行申請';

-- --------------------------------------------------------

--
-- テーブルの構造 `taxes`
--

CREATE TABLE `taxes` (
  `id` int(6) NOT NULL COMMENT 'ID',
  `tax_value` int(3) NOT NULL COMMENT '税率',
  `start_datetime` date NOT NULL COMMENT '施行開始日',
  `end_datetime` date DEFAULT NULL COMMENT '施行終了日',
  `insert_datetime` datetime NOT NULL COMMENT '挿入日時',
  `update_datetime` datetime NOT NULL COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='税率';

--
-- テーブルのデータのダンプ `taxes`
--

INSERT INTO `taxes` (`id`, `tax_value`, `start_datetime`, `end_datetime`, `insert_datetime`, `update_datetime`) VALUES
(1, 8, '2014-04-01', NULL, '2021-10-08 13:55:11', '2022-11-07 14:21:04'),
(2, 10, '2019-10-01', NULL, '2021-10-08 13:55:11', '2022-11-07 14:21:04');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `additional_shipping_fees`
--
ALTER TABLE `additional_shipping_fees`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `addressees`
--
ALTER TABLE `addressees`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `admin_authorities`
--
ALTER TABLE `admin_authorities`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `delivery_type`
--
ALTER TABLE `delivery_type`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `item_images`
--
ALTER TABLE `item_images`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `mailing_lists`
--
ALTER TABLE `mailing_lists`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `mailing_list_members`
--
ALTER TABLE `mailing_list_members`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `mails`
--
ALTER TABLE `mails`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `mail_recipients`
--
ALTER TABLE `mail_recipients`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `member_applications`
--
ALTER TABLE `member_applications`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `news_images`
--
ALTER TABLE `news_images`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `orders_temp`
--
ALTER TABLE `orders_temp`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `order_post_addresses`
--
ALTER TABLE `order_post_addresses`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `pay_types`
--
ALTER TABLE `pay_types`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `reissues`
--
ALTER TABLE `reissues`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `additional_shipping_fees`
--
ALTER TABLE `additional_shipping_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=5;

--
-- テーブルの AUTO_INCREMENT `addressees`
--
ALTER TABLE `addressees`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=55;

--
-- テーブルの AUTO_INCREMENT `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=33;

--
-- テーブルの AUTO_INCREMENT `admin_authorities`
--
ALTER TABLE `admin_authorities`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=2;

--
-- テーブルの AUTO_INCREMENT `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=368;

--
-- テーブルの AUTO_INCREMENT `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=15;

--
-- テーブルの AUTO_INCREMENT `delivery_type`
--
ALTER TABLE `delivery_type`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- テーブルの AUTO_INCREMENT `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=9;

--
-- テーブルの AUTO_INCREMENT `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=110;

--
-- テーブルの AUTO_INCREMENT `items`
--
ALTER TABLE `items`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=201;

--
-- テーブルの AUTO_INCREMENT `item_images`
--
ALTER TABLE `item_images`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=571;

--
-- テーブルの AUTO_INCREMENT `mailing_lists`
--
ALTER TABLE `mailing_lists`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=13;

--
-- テーブルの AUTO_INCREMENT `mailing_list_members`
--
ALTER TABLE `mailing_list_members`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=35;

--
-- テーブルの AUTO_INCREMENT `mails`
--
ALTER TABLE `mails`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=85;

--
-- テーブルの AUTO_INCREMENT `mail_recipients`
--
ALTER TABLE `mail_recipients`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=36;

--
-- テーブルの AUTO_INCREMENT `members`
--
ALTER TABLE `members`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=89;

--
-- テーブルの AUTO_INCREMENT `member_applications`
--
ALTER TABLE `member_applications`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=68;

--
-- テーブルの AUTO_INCREMENT `news`
--
ALTER TABLE `news`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=31;

--
-- テーブルの AUTO_INCREMENT `news_images`
--
ALTER TABLE `news_images`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=82;

--
-- テーブルの AUTO_INCREMENT `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=676;

--
-- テーブルの AUTO_INCREMENT `orders_temp`
--
ALTER TABLE `orders_temp`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=72;

--
-- テーブルの AUTO_INCREMENT `order_post_addresses`
--
ALTER TABLE `order_post_addresses`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=119;

--
-- テーブルの AUTO_INCREMENT `order_status`
--
ALTER TABLE `order_status`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=4;

--
-- テーブルの AUTO_INCREMENT `pay_types`
--
ALTER TABLE `pay_types`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=3;

--
-- テーブルの AUTO_INCREMENT `reissues`
--
ALTER TABLE `reissues`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=143;

--
-- テーブルの AUTO_INCREMENT `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
