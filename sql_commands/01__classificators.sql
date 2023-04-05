CREATE TABLE `contractor_type` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `contr_type` varchar(200) NOT NULL,
 
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

insert into contractor_type(`contr_type`) values
  ('Поставщик'),
  ('Заказчик');

CREATE TABLE `company_type` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `type` varchar(200) NOT NULL,
 
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

insert into company_type(`type`) values
  ('Юридическое лицо'),
  ('Физическое лицо');

CREATE TABLE `cl_contractor` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(200) NOT NULL,
 `full_name` varchar(500) NOT NULL,
 `contractor_type` int(11) NOT NULL,
 `company_type` int(11) NOT NULL,
 `country_reg`  int(11) NOT NULL,
 `INN` varchar(50) NOT NULL,
 `KPP` varchar(50) NOT NULL,
 `OGRP` varchar(50) NOT NULL,
-- адрес организации
 `region` varchar(200), -- регион
 `locality` varchar(200), -- населенный пункт
 `city` varchar(200), -- город
 `street` varchar(200), -- улица
 `house` varchar(20),  -- дом
 `housing` varchar(20),  -- корпус
 `flat` varchar(20),  -- квартира

-- сведения о руководителе
 `head_first_name` varchar(200),
 `head_middle_name` varchar(200),
 `head_last_name` varchar(200),

 PRIMARY KEY (`id`),
 KEY `contractor_type` (`contractor_type`),
 CONSTRAINT `contractor_type` FOREIGN KEY (`contractor_type`) REFERENCES `contractor_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 
 CONSTRAINT `company_type` FOREIGN KEY (`company_type`) REFERENCES `company_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

INSERT INTO `cl_contractor` ( `name`, `full_name`, `contractor_type`, `company_type`, `country_reg`, `INN`, `KPP`, `OGRP`, `region`, `locality`, `city`, `street`, `house`, `housing`, `flat`, `head_first_name`, `head_middle_name`, `head_last_name`) VALUES ('Супер Крепеж', 'ОсОО \"Супер Крепеж\"', '12', '12', '', '834023543247', '834023543212', '834034523543', '', '', 'Алматы', 'Дубнинская', '120', '', '', 'Сидоров', 'Петр', 'Иванович');

INSERT INTO `cl_contractor` (`name`, `full_name`, `contractor_type`, `company_type`, `country_reg`, `INN`, `KPP`, `OGRP`, `region`, `locality`, `city`, `street`, `house`, `housing`, `flat`, `head_first_name`, `head_middle_name`, `head_last_name`) VALUES (NULL, 'Мануфактура', 'ОсОО \"Мануфактура\"', '12', '12', '', '853402323247', '840235274312', '897634523543', '', '', 'Алматы', 'Советская', '653', '', '22', 'Смирнова', 'Наталья', 'Петровна');

