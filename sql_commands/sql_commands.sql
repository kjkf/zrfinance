
-- ==================================== CREATE TABLE ==================================== --

CREATE TABLE `account` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` text NOT NULL,
 `account_num` text,
 `company` int(11) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `company` (`company`),
 CONSTRAINT `account_company` FOREIGN KEY (`company`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8

CREATE TABLE `roles` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `role` text NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` text NOT NULL,
 `email` text NOT NULL,
 `role` int(11) NOT NULL,
 `password` text NOT NULL,
 PRIMARY KEY (`id`),
 KEY `role` (`role`),
 CONSTRAINT `user_role` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `company` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` text NOT NULL,
 `bin` int(12) DEFAULT NULL,
 `address` text,
 `director` text,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--alter TABLE employee add COLUMN `salary_fact` double(10, 2) not null default 10
--update employee set salary_fact = salary where `fire_date` is null
CREATE TABLE `employee` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` text NOT NULL,
 `surname` text NOT NULL,
 `email` text,
 `position` int(11) DEFAULT NULL,
 `department` int(11) DEFAULT NULL,
 `company` int(11) DEFAULT NULL,
 `salary` double(10, 2) not null,
 `salary_fact` double(10, 2) not null,
 `telephone` varchar(12) DEFAULT NULL,
 `is_fired` int(2) DEFAULT 0,
 `fire_date` datetime DEFAULT NULL,
 PRIMARY KEY (`id`),

 KEY `position` (`position`),
 KEY `department` (`department`),
 KEY `company` (`company`),

 CONSTRAINT `user_position` FOREIGN KEY (`position`) REFERENCES `position` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 
 CONSTRAINT `user_department` FOREIGN KEY (`department`) REFERENCES `department` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
 CONSTRAINT `user_company` FOREIGN KEY (`company`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `position` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` text NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `status` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(250) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

CREATE TABLE `receipt` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `date_time` datetime NOT NULL,
 `company_account` int(11) NOT NULL,
 `item` int(11) NOT NULL DEFAULT '1',
 `official` tinyint(1) DEFAULT NULL,
 `agreement_forzr` int(11) DEFAULT NULL,
 `agreement_fromzr` int(11) DEFAULT NULL,
 `employee` int(11) DEFAULT NULL,
 `description` varchar(500) DEFAULT NULL,
 `document` varchar(255) NOT NULL,
 `author` int(11) NOT NULL,
 `sum` double(10,2) NOT NULL,
 `status` int(11) NOT NULL DEFAULT '1',
 `status_reason` varchar(255) DEFAULT NULL,
 `approved_by` int(11) DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `agreement` (`agreement_forzr`),
 KEY `employee` (`employee`),
 KEY `author` (`author`),
 KEY `company` (`company_account`),
 KEY `item` (`item`),
 KEY `status` (`status`),
 KEY `approved_by` (`approved_by`),
 KEY `agreement_fromzr` (`agreement_fromzr`),
 CONSTRAINT `receipt_related_agreement_fromzr` FOREIGN KEY (`agreement_fromzr`) REFERENCES `agreement_fromZR` (`id`),
 CONSTRAINT `receipt_related_agreemnt` FOREIGN KEY (`agreement_forzr`) REFERENCES `agreement_forZR` (`id`),
 CONSTRAINT `receipt_related_company` FOREIGN KEY (`company_account`) REFERENCES `account` (`id`),
 CONSTRAINT `receipt_related_employee` FOREIGN KEY (`employee`) REFERENCES `employee` (`id`),
 CONSTRAINT `receipt_related_item` FOREIGN KEY (`item`) REFERENCES `receipt_item` (`id`),
 CONSTRAINT `receipt_related_status` FOREIGN KEY (`status`) REFERENCES `status` (`id`),
 CONSTRAINT `receipt_related_user` FOREIGN KEY (`author`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

CREATE TABLE `receipt_change` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `record_id` int(11) NOT NULL,
 `date_time` datetime NOT NULL,
 `old_value` double(10,2) NOT NULL,
 `new_value` double(10,2) NOT NULL,
 `reason` VARCHAR(500) NULL,
 `decision` tinyint(1) NOT NULL DEFAULT '0',
 `decision_date` datetime NOT NULL,
 `decision_by` int(11) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `record_id` (`record_id`),
 KEY `decision_by` (`decision_by`),
 CONSTRAINT `changed_receipt_id` FOREIGN KEY (`record_id`) REFERENCES `receipt` (`id`),
 CONSTRAINT `related_user` FOREIGN KEY (`decision_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `expense` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `date_time` datetime NOT NULL,
 `company_account` int(11) NOT NULL,
 `item` int(11) NOT NULL DEFAULT '6',
 `official` tinyint(1) DEFAULT NULL,
 `agreement_forzr` int(11) DEFAULT NULL,
 `agreement_fromzr` int(11) DEFAULT NULL,
 `employee` int(11) DEFAULT NULL,
 `description` varchar(500) DEFAULT NULL,
 `document` varchar(255) NOT NULL,
 `author` int(11) NOT NULL,
 `sum` double(10,2) NOT NULL,
 `status` int(11) NOT NULL DEFAULT '1',
 `status_reason` varchar(255) DEFAULT NULL,
 `approved_by` int(11) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `employee` (`employee`),
 KEY `author` (`author`),
 KEY `company` (`company_account`),
 KEY `agreement_forzr` (`agreement_forzr`),
 KEY `agreement_fromzr` (`agreement_fromzr`),
 KEY `item` (`item`),
 KEY `status` (`status`),
 KEY `approved_by` (`approved_by`),
 CONSTRAINT `expense_related_agreement_forzr` FOREIGN KEY (`agreement_forzr`) REFERENCES `agreement_forZR` (`id`),
 CONSTRAINT `expense_related_agreement_fromzr` FOREIGN KEY (`agreement_fromzr`) REFERENCES `agreement_fromZR` (`id`),
 CONSTRAINT `expense_related_company` FOREIGN KEY (`company_account`) REFERENCES `account` (`id`),
 CONSTRAINT `expense_related_employee` FOREIGN KEY (`employee`) REFERENCES `employee` (`id`),
 CONSTRAINT `expense_related_item` FOREIGN KEY (`item`) REFERENCES `expense_item` (`id`),
 CONSTRAINT `expense_related_status` FOREIGN KEY (`status`) REFERENCES `status` (`id`),
 CONSTRAINT `expense_related_user` FOREIGN KEY (`author`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8

CREATE TABLE `expense_change` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `record_id` int(11) NOT NULL,
 `date_time` datetime NOT NULL,
 `old_value` double(10,2) NOT NULL,
 `new_value` double(10,2) NOT NULL,
 `reason` VARCHAR(500) NULL,
 `decision` tinyint(1) NOT NULL DEFAULT '0',
 `decision_date` datetime NOT NULL,
 `decision_by` int(11) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `record_id` (`record_id`),
 KEY `decision_by` (`decision_by`),
 CONSTRAINT `changed_expense_id` FOREIGN KEY (`record_id`) REFERENCES `expense` (`id`),
 CONSTRAINT `related_expense_user` FOREIGN KEY (`decision_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `agreement_fromZR` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `company` int(11) NOT NULL,
 `customer` int(11) NOT NULL,
 `short_name` varchar(255) DEFAULT NULL,
 `manager` int(11) NOT NULL,
 `agreement_num` varchar(250) NOT NULL,
 `agreement_date` date NOT NULL,
 `agreement_sum` double(10,2) DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `company` (`company`),
 KEY `manager` (`manager`),
 KEY `customer` (`customer`),
 CONSTRAINT `agreementfr_related_company` FOREIGN KEY (`company`) REFERENCES `company` (`id`),
 CONSTRAINT `agreementfr_related_contarctor` FOREIGN KEY (`customer`) REFERENCES `contractor` (`id`),
 CONSTRAINT `agreementfr_related_manager` FOREIGN KEY (`manager`) REFERENCES `employee` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE `goods_category` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(255) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `goods_type` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(255) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `goods` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(250) NOT NULL,
 `code` varchar(100) NOT NULL,
 `type` int(11) NOT NULL,
 `category` int(11) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `type` (`type`),
 KEY `category` (`category`),
 CONSTRAINT `goods_realted_category` FOREIGN KEY (`category`) REFERENCES `goods_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `goods_realted_type` FOREIGN KEY (`type`) REFERENCES `goods_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `agreement_fromZR_goods` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `agreement` int(11) NOT NULL,
 `good` int(11) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `agreement` (`agreement`),
 KEY `good` (`good`),
 CONSTRAINT `related_agreement` FOREIGN KEY (`agreement`) REFERENCES `agreement_fromZR` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `related_good` FOREIGN KEY (`good`) REFERENCES `goods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `expense_category` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `category` varchar(250) NOT NULL,
 
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- ======================================= INSERTS ======================================= --
INSERT INTO `roles`(`id`, `role`) VALUES
(1,'admin'),
(2,'guest'),
(3,'chief'),
(4,'accountant'),
(5,'hr');

INSERT INTO `status`(`id`, `name`) VALUES
(1, 'new'),
(2, 'edited'),
(3, 'revision'),
(4, 'approved'),
(5, 'deleted');

INSERT INTO `company`(`id`, `name`) VALUES
(1,'Златарь'),
(2,'Производственная компания Златарь'),
(3,'Торговый дом Златарь'),
(4,'Златарь Мотаж'),
(5,'Ares Group');

INSERT INTO `account`(`name`, `company`) VALUES
-- 1 - ТОО Златарь
('АО Банк ЦентрКредит', 1),
('АО Народный Банк Казахстана', 1),
('Касса', 1),
-- 2 - ПК Златарь
('АО Банк Центр Кредит', 2),
('Касса', 2),
-- 3 - ТД Златарь
('АО Банк ЦентрКредит', 3),
('АО Народный Банк Казахстана(Halyk)', 3),
('Касса', 3),
-- 4 - Златарь Монтаж
('АО Банк ЦентрКредит', 4),
('Касса', 4),
-- 5 - АРЕС ГРУПП
('АО Народный Банк Казахстана', 5),
('Касса', 5);

INSERT INTO `position`(`id`, `name`) VALUES
(1,'Hr Manager'),
(2,'Бригадир сварочного участка'),
(3,'Бухгалтер'),
(4,'Главный бухгалтер'),
(5,'Главный инженер'),
(6,'Директор'),
(7,'Директор по развитию направления - СНВФ'),
(8,'Инженер'),
(9,'Инженер по БиОТ'),
(10,'Инженер-конструктор'),
(11,'Кладовщик'),
(12,'Менеджер'),
(13,'Менеджер Битрикс'),
(14,'Менеджер по интернет продвижению'),
(15,'Начальник производства'),
(16,'Оператор'),
(17,'Операционный директор'),
(18,'Сварщик'),
(19,'Слесарь'),
(20,'Учредитель'),
(21,'Электрик'),
(22,'Системнвй администратор'),
(23,'Снабженец'),
(24,'Водитель'),
(25,'Начальник участка'),
(26,'Монтажник');
(27,'Помощник зав.складом');
(28,'Маляр');


--INSERT INTO `employee`(`name`, `surname`, `email`, `position`) VALUES
--('Владимир','Акимов','akimov.08@list.ru',16),
--('Лариса','Артемьева','hr@zlatar12.com',1),
--('Александр','Бондарь','a.bondar@zlatar12.com',10),
--('Александр','Буховец','alexbuh@mail.ru',19),
--('Ядвига','Волынская','y.volynskaya@zlatar12.com',12),
--('Павел','Губин','pavel.g@office.hoster.kz',13),
--('Алексей','Друзенко','druzen91@gmail.com',2),
--('Ильяс','Закиров','i.zakirov@zlatar12.com',10),
--('Аскар','Идрисов','askar_i88@mail.ru',18),
--('Ержан','Калмуратов','aler-forever@mail.ru',18),
--('Жанасыл','Канатбеков','Zh.Kanatbekov@zlatar12.com',8),
--('Станислав','Королев','korolevstas@mail.ru',18),
--('Александр','Корчебанов','a.korchebanov@zlatar12.com',17),
--('Куаныш','Сарсенбаев','zlatarsklad@gmail.com',11),
--('Асемгуль','Курмангалиева','asem@mail.ru',9),
--('Юлия','Мавлянова','julia@zlatar12.com',6),
--('Бота','Мадимова','msbota@gmail.com',null),
--('Раиса','Мамадалиева','glavbuch@zlatar12.com',4),
--('Николай','Матвиенко','matvienko@mail.ru',18),
--('Гульмира','Моллахунова','GMollakhunova@zlatar12.com',null),
--('Мадина','Мухтарова','m.mukhtarova@zlatar12.com',12),
--('Медет','Нариманов','medet199624@gmail.com',16),
--('Александр','Обидин','bokhanalex1302@gmail.com',19),
--('Виктор','Панченко','panchenko.v71@mail.ru',15),
--('Мурат','Примбетов','murat/primbetov@mail.ru',18),
--('Виталий','Рассказов','v.rasskazov@zlatar12.com',7),
--('Олег','Родченко','ro2872@list.ru',18),
--('Валерий','Романенко','zakup@zlatar12.com',null),
--('Аида','Саимова','a.saimova@zlatar12.com',12),
--('Ализар','Сайфатов','',16),
--('Евгений','Свинолупов','y.svinolupov@zlatar12.com',14),
--('Наталья','Струнина','eva06.86@mail.ru',3),
--('Ербол','Тайбагаров','e.taibagarov@zlatar12.com',6),
--('Константин','Цай','k.tsay@zlatar12.com',null),
--('Тахир','Чаплыев','t.chaplyev@zlatar12.com',12),
--('Ахмет','Чило-Оглы','akhmet.chiloogly@mail.ru',19),
--('Дмитрий','Шаломенцев','mitya3010760@gmail.com',21),
--('Эдуард','Шмальц','odin-dwa-tri@mail.ru',20),
--('Дмитрий','Щукин','d.chshukin@zlatar12.com',null),
--('Валерия','Ялонжа','valeriya.k@office.hoster.kz',null);

--INSERT INTO `employee`(`name`, `surname`, `company`, `position`) VALUES
--('Мадимова', 'Жанара',5,6),
--('Курмангалиева', 'Асемгуль',3,9),
--('Карсаков', 'Александр',3,12),
--('Жанзак', 'Жасулан',3,8),
--('Утегенов', 'Ерман',3,8),
--('Мацура', 'Евгений',4,24),
--('Уалиханов', 'Еркин',4,25),
--('Соколов', 'Сергей',4,26),
--('Цаценко', 'Оксана',4,3),
--('Тураев', 'Талгат',2,28),
--('Савин', 'Евгений',2,19),
--('Попов', 'Валерий',2,19),
--('Узаков  Талгат', 'Талгат',2,28),
--('Байтугулов', 'Ануар',2,27),
--('Сердюк', 'Валерий',2,18),
--('Дуйсенбаев', 'Хамит',2,28),
--('Барвинок', 'Сергей',2,19),
--('Бекжанов', 'Бекзат',2,16),
--('Уткин', 'Данил',2,19),
--('Шуртыгин', 'Николай',2,19),
--('Жуматаев', 'Дулатбек',2,28),
--('Касымов', 'Арман',2,28),
--('Есжанов', 'Максат',2,16);

--UPDATE `employee` SET `company` = '2' WHERE `employee`.`id` = 1;
--UPDATE `employee` SET `company` = '3' WHERE `employee`.`id` = 2;
--UPDATE `employee` SET `company` = '3' WHERE `employee`.`id` = 3;
--UPDATE `employee` SET `company` = '2' WHERE `employee`.`id` = 4;
--UPDATE `employee` SET `company` = '3' WHERE `employee`.`id` = 5;
--UPDATE `employee` SET `company` = '' WHERE `employee`.`id` = 6;
--UPDATE `employee` SET `company` = '1' WHERE `employee`.`id` = 7;
--UPDATE `employee` SET `company` = '3' WHERE `employee`.`id` = 8;
--UPDATE `employee` SET `company` = '2' WHERE `employee`.`id` = 9;
--UPDATE `employee` SET `company` = '2' WHERE `employee`.`id` = 10;
--UPDATE `employee` SET `company` = '3' WHERE `employee`.`id` = 11;
--UPDATE `employee` SET `company` = '' WHERE `employee`.`id` = 12;
--UPDATE `employee` SET `company` = '3' WHERE `employee`.`id` = 13;
--UPDATE `employee` SET `company` = '2' WHERE `employee`.`id` = 14;
--UPDATE `employee` SET `company` = '2' WHERE `employee`.`id` = 15;
--UPDATE `employee` SET `company` = '3' WHERE `employee`.`id` = 16;
--UPDATE `employee` SET `company` = '5' WHERE `employee`.`id` = 17;
--UPDATE `employee` SET `company` = '2' WHERE `employee`.`id` = 18;
--UPDATE `employee` SET `company` = '5' WHERE `employee`.`id` = 19;
--UPDATE `employee` SET `company` = '3' WHERE `employee`.`id` = 20;
--UPDATE `employee` SET `company` = '' WHERE `employee`.`id` = 21;
--UPDATE `employee` SET `company` = '2' WHERE `employee`.`id` = 22;
--UPDATE `employee` SET `company` = '2' WHERE `employee`.`id` = 23;
--UPDATE `employee` SET `company` = '2' WHERE `employee`.`id` = 24;
--UPDATE `employee` SET `company` = '1' WHERE `employee`.`id` = 25;
--UPDATE `employee` SET `company` = '4' WHERE `employee`.`id` = 26;
--UPDATE `employee` SET `company` = '2' WHERE `employee`.`id` = 27;
--UPDATE `employee` SET `company` = '2' WHERE `employee`.`id` = 28;
--UPDATE `employee` SET `company` = '3' WHERE `employee`.`id` = 29;
--UPDATE `employee` SET `company` = '2' WHERE `employee`.`id` = 30;
--UPDATE `employee` SET `company` = '3' WHERE `employee`.`id` = 31;
--UPDATE `employee` SET `company` = '3' WHERE `employee`.`id` = 32;
--UPDATE `employee` SET `company` = '2' WHERE `employee`.`id` = 33;
--UPDATE `employee` SET `company` = '3' WHERE `employee`.`id` = 34;
--UPDATE `employee` SET `company` = '3' WHERE `employee`.`id` = 35;
--UPDATE `employee` SET `company` = '2' WHERE `employee`.`id` = 36;
--UPDATE `employee` SET `company` = '2' WHERE `employee`.`id` = 37;
--UPDATE `employee` SET `company` = '3' WHERE `employee`.`id` = 38;
--UPDATE `employee` SET `company` = '3' WHERE `employee`.`id` = 39;
--UPDATE `employee` SET `company` = '' WHERE `employee`.`id` = 40;


alter table `expense_item` add column `need_contractor` int(6) DEFAULT null; -- в значении указывается айдишник категории, к которой относится контрагент
alter table `expense_item` add column `descr` varchar(250) DEFAULT '';
INSERT INTO `expense_item`(`name`, `descr`, `need_agreement`, `need_employee`, `need_contractor`) VALUES
('Покупка товаров/услуг для реализации Договора', '', 1, null, null),
('Оплата товаров/услуг поставленных для ZR', '', 1, null, null),
('Выдача аванса сотруднику', '', null, 1, null),
('Выдача денежных средств в подотчет сотруднику', '', null, 1, null),
('Материалы металл', '', null, null, 1),
('Материалы фурнитура', 'крепеж, сварочная проволка, обтирочная ткань и т.д.', null, null, 2),
('Материалы краска', 'полимерная краска, краска в балончиках и другая краска', null, null, 3),
('Материалы прочие', '', null, null,  4),
('Канцтовары', 'картриджи, бумага, ручки и т.д.', null, null, 5),
('Мыломойка', '', null, null, 6),
('Продукты', 'чай, сахар, кофе, молоко и т.д.', null, null, 7),
('Интернет', '', null, null, 8),
('Транспортные услуги', 'доставка, такси, авиабилеты', null, null, 9),
('Налоги', '', null, null, null),
('Банк', '', null, null, null),
('Оплата ГСМ', '', null, 1, null),
('Прочие', '', null, null, null);

INSERT INTO `receipt_item`(`name`, `need_agreement`, `need_goods`) VALUES
('Оплата товаров/услуг ZR(т.е. ZR - исполнитель)', 1, null),
('Возврат денежных средств по договору', 1, null),
('Прочие', null, null),
('Остаток на начало периода внедрения', null, null),
('Оплата товаров (оптово-розничная продажа, аренда)', null, 1);


INSERT INTO `agreement_forZR`(`company`, `executer`, `agreement_num`, `agreement_date`, `agreement_sum`)
VALUES (1,'ТОО Чистые воды','202009-0034','2020-09-15',45000);

INSERT INTO `receipt`
(`date_time`, `company_account`, `item`, `official`, `agreement_forzr`, `agreement_fromzr`, `employee`, `description`, `document`, `author`, `sum`, `status`, `status_date`, `status_reason`, `approved_by`) VALUES
('2022-04-10',1,4,1,null,null,null,'остаток на начало периода внедрения','no',2,'20000000',1,'2022-04-10',null,null);

INSERT INTO `goods_type`(`id`, `name`) VALUES
(1, 'готовый товар'),
(2, 'изготовленный товар'),
(3, 'товар в аренду');

INSERT INTO `goods_category`(`id`, `name`) VALUES
(1, 'фасадный подъемник'),
(2, 'светильник'),
(3, 'крючок'),
(4, 'держатель туалетной бумаги'),
(5, 'комплект настенных полок');

INSERT INTO `goods`(`name`, `code`, `type`, `category`) VALUES
('фасадный подъемник', '', 3, 1),
-- . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . --
('светильник садово-парковый, мозайка, SV-SE4MOZ', 'SV-SE4MOZ', 1, 2),
('светильник садово-парковый, мозайка, SV-SE6MOZ', 'SV-SE6MOZ', 1, 2),
('светильник садово-парковый, мозайка, SV-SE8MOZ', 'SV-SE8MOZ', 1, 2),
('светильник садово-парковый, мозайка, SV-SL4MOZ', 'SV-SL4MOZ', 1, 2),
('светильник садово-парковый, мозайка, SV-SL6MOZ', 'SV-SL6MOZ', 1, 2),

('светильник садово-парковый, колотый лёд, SV-SE4LED', 'SV-SE4LED', 1, 2),
('светильник садово-парковый, колотый лёд, SV-SE6LED', 'SV-SE6LED', 1, 2),
('светильник садово-парковый, колотый лёд, SV-SE8LED', 'SV-SE8LED', 1, 2),
('светильник садово-парковый, колотый лёд, SV-SL4LED', 'SV-SL4LED', 1, 2),
('светильник садово-парковый, колотый лёд, SV-SL6LED', 'SV-SL6LED', 1, 2),
('светильник садово-парковый, колотый лёд, SV-SL8LED', 'SV-SL8LED', 1, 2),

('светильник садово-парковый, орнамент, SV-SL4ORN', 'SV-SL4ORN', 1, 2),
('светильник садово-парковый, орнамент, SV-SL6ORN', 'SV-SL6ORN', 1, 2),
('светильник садово-парковый, орнамент, SV-SL8ORN', 'SV-SL8ORN', 1, 2),
('светильник садово-парковый, орнамент, SV-SE4ORN', 'SV-SE4ORN', 1, 2),
('светильник садово-парковый, орнамент, SV-SE6ORN', 'SV-SE6ORN', 1, 2),

('светильник садово-парковый, дубаи, SV-SL4DUB', 'SV-SL4DUB', 1, 2),
('светильник садово-парковый, дубаи, SV-SL6DUB', 'SV-SL6DUB', 1, 2),
('светильник садово-парковый, дубаи, SV-SL8DUB', 'SV-SL8DUB', 1, 2),
('светильник садово-парковый, дубаи, SV-SE4DUB', 'SV-SE4DUB', 1, 2),
('светильник садово-парковый, дубаи, SV-SE6DUB', 'SV-SE6DUB', 1, 2),
('светильник садово-парковый, дубаи, SV-SE8DUB', 'SV-SE8DUB', 1, 2),
-- . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . --
('крючок настенный, 3шт, KN-SE3STL', 'KN-SE3STL', 1, 3),
('крючок настенный, 3шт, KN-BE3STL', 'KN-BE3STL', 1, 3),
('крючок настенный, 3шт, KN-CH3STL', 'KN-CH3STL', 1, 3),
('крючок настенный, 3шт, KN-SL3STL', 'KN-SL3STL', 1, 3),
('крючок настенный, 6шт, KN-SL6STL', 'KN-SL6STL', 1, 3),
('крючок настенный, 6шт, KN-SE6STL', 'KN-SE6STL', 1, 3),
('крючок настенный, 6шт, KN-BE6STL', 'KN-BE6STL', 1, 3),
('крючок настенный, 6шт, KN-CH6STL', 'KN-CH6STL', 1, 3),
-- . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . --
('держатель туалетной бумаги, DTB-SLZERO', 'DTB-SLZERO', 1, 4),
('держатель туалетной бумаги, DTB-CHZERO', 'DTB-CHZERO', 1, 4),
('держатель туалетной бумаги, DTB-SEZERO', 'DTB-SEZERO', 1, 4),
('держатель туалетной бумаги, DTB-BEZERO', 'DTB-BEZERO', 1, 4),
-- . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . --
('комплект настенных полок, zero, KNP-BE3ZERO', 'KNP-BE3ZERO', 1, 5),
('комплект настенных полок, zero, KNP-SL3ZERO-40', 'KNP-SL3ZERO-40', 1, 5),
('комплект настенных полок, zero, KNP-SE3ZERO-40', 'KNP-SE3ZERO-40', 1, 5),
('комплект настенных полок, zero, KNP-GR3ZERO-40', 'KNP-GR3ZERO-40', 1, 5),
('комплект настенных полок, zero, KNP-CH3ZERO-40', 'KNP-CH3ZERO-40', 1, 5),
('комплект настенных полок, zero, KNP-BE3ZERO-40', 'KNP-BE3ZERO-40', 1, 5),

('комплект настенных полок, wave, KNP-SE3WAVE-40', 'KNP-SE3WAVE-40', 1, 5),
('комплект настенных полок, wave, KNP-SL3WAVE-40', 'KNP-SL3WAVE-40', 1, 5),
('комплект настенных полок, wave, KNP-BE3WAVE-40', 'KNP-BE3WAVE-40', 1, 5),
('комплект настенных полок, wave, KNP-CH3WAVE-40', 'KNP-CH3WAVE-40', 1, 5),
('комплект настенных полок, wave, KNP-GR3WAVE-40', 'KNP-GR3WAVE-40', 1, 5);
