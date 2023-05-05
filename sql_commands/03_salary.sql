-- ================================ CREATE TABLES ================================

CREATE TABLE `emp_contract_type` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `contract_type` varchar(250) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `department` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(250) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `direction` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(250) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `salary_fzp` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `author` int(11) NOT NULL, 
 `is_approved` int(2) DEFAULT 0, -- 0 - не утрвержден, 1 - утвержден, 2 - отправлен на доработку, 4 - на согласовании
 `rejection_reason` varchar(500), default NULL ,
 `mrp` double(10, 2) DEFAULT 0, 
 `min_zp` double(10, 2) DEFAULT 0, 

 PRIMARY KEY (`id`),

 KEY `author_key` (`author`),

 CONSTRAINT `author` FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION 
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `salary_month` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `employee_id` int(11) NOT NULL, 
 `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `salary_fzp` int(11) NOT NULL, 
 `employee_salary` double(10,2) DEFAULT null,  -- зарплата, так как может меняться, нужно сохранять для истории
 `employee_salary_fact` double(10,2) DEFAULT null,  -- зарплата, так как может меняться, нужно сохранять для истории

 `working_hours_per_month` int(11) NOT NULL, -- количество рабочих часов
 `worked_hours_per_month` int(11) NOT NULL,  -- количество отработанных часов

 `tax_OSMS` double(10,2) DEFAULT 0,  -- налог ОСМС
 `tax_IPN` double(10,2) DEFAULT 0,  -- налог ИПН
 `tax_OPV` double(10,2) DEFAULT 0,  -- налог ОВП
 
 `advances` double(10,2) DEFAULT null,           -- авансы
 PRIMARY KEY (`id`),
 KEY `salary_fzp_key` (`salary_fzp`),
 KEY `employee_id` (`employee_id`),

 CONSTRAINT `employee` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ,
 CONSTRAINT `salary_fzp` FOREIGN KEY (`salary_fzp`) REFERENCES `salary_fzp` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION 
 
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--CREATE TABLE `working_time_balance_by_year` (
-- `id` int(11) NOT NULL AUTO_INCREMENT,
 

-- PRIMARY KEY (`id`),

-- CONSTRAINT `author` FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION 
--) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE `working_time_balance` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `year` int(20) not null, -- год
 `month` int(11) not null, -- месяц
 `calendar_days` int(11) not null, -- календарные дни
 `working_calendar_days` int(11) not null, -- календарные дни без праздников
 `working_5_days` int(11) not null, -- рабочие дни - пятидневка
 `working_6_days` int(11) not null, -- рабочие дни - шестидневка

 `w40_5d_hours` int(11) not null, -- рабочие часы - пятидневка 40часовая рабочая неделя
 `w40_6d_hours` int(11) not null, -- рабочие часы - шестидневка 40часовая рабочая неделя
 `w36_5d_hours` int(11) not null, -- рабочие часы - пятидневка 36часовая рабочая неделя
 `w36_6d_hours` int(11) not null, -- рабочие часы - шестидневка 36часовая рабочая неделя
 

 PRIMARY KEY (`id`)

) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `bonus_fines_types` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(250) NOT NULL,
 `type` varchar(250) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `bonus_fines` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `employee_id` int(11) NOT NULL,
 `salary_fzp` int(11) NOT NULL,  
 `bonus`  double(10,2) NOT NULL default 0,
 `fines`  double(10,2) NOT NULL default 0con,
 `type_id` int(11) NOT NULL,

 PRIMARY KEY (`id`),
 KEY `salary_fzp_key` (`salary_fzp`),
 KEY `employee_id` (`employee_id`),
 KEY `type_id` (`type_id`),

 CONSTRAINT `bonus_fines_type` FOREIGN KEY (`type_id`) REFERENCES `bonus_fines_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ,
 CONSTRAINT `bonus_fines_employee` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ,
 CONSTRAINT `bonus_fines_salary_fzp` FOREIGN KEY (`salary_fzp`) REFERENCES `salary_fzp` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `salary_settings` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `mrp` double(10, 2) not null default 0,
 `min_zp` double(10, 2) not null default 0,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ================================ ALTER TABLES ===========================
--ALTER TABLE employee ADD COLUMN `contract_type` int(11) DEFAULT NULL;
--ALTER TABLE employee ADD COLUMN `is_tax` int(11) DEFAULT 1;
--ALTER TABLE employee ADD COLUMN `department` int(11) DEFAULT NULL;
--ALTER TABLE employee ADD COLUMN `direction` int(11) DEFAULT NULL;
--ALTER TABLE employee ADD COLUMN `salary` double(10, 2) not null DEFAULT 0;
--ALTER TABLE employee ADD COLUMN `salary_fact` double(10, 2) not null DEFAULT 0;

--ALTER TABLE employee ADD KEY `contract_type` (`contract_type`);
--ALTER TABLE employee ADD KEY `department` (`department`);
--ALTER TABLE employee ADD KEY `direction` (`direction`);

--ALTER TABLE employee ADD CONSTRAINT `user_contract_type` FOREIGN KEY (`contract_type`) REFERENCES `emp_contract_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
--ALTER TABLE employee ADD CONSTRAINT `user_position` FOREIGN KEY (`position`) REFERENCES `position` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
--ALTER TABLE employee ADD CONSTRAINT `user_department` FOREIGN KEY (`department`) REFERENCES `department` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
--ALTER TABLE employee ADD CONSTRAINT `user_direction` FOREIGN KEY (`direction`) REFERENCES `direction` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
--ALTER TABLE employee ADD CONSTRAINT `user_company` FOREIGN KEY (`company`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ================================ INSERTS ================================
insert into salary_settings(mrp) calues (3450);

insert into emp_contract_type(contract_type) values 
('Трудовой договор'),
('ГПХ');

insert into `working_time_balance`(`year`, `month`,`calendar_days`, `working_calendar_days`, `working_5_days`, `working_6_days`, `w40_5d_hours`, `w40_6d_hours`, `w36_5d_hours`, `w36_6d_hours`) VALUES
(2023, 1, 31, 29, 20, 23, 160, 155, 144, 138),
(2023, 2, 28, 28, 20, 24, 160, 160, 144, 144),
(2023, 3, 31, 27, 19, 23, 152, 153, 136.8, 138),
(2023, 4, 30, 30, 20, 25, 160, 165, 144, 150),
(2023, 5, 31, 28, 20, 24, 160, 160, 144, 144),
(2023, 6, 30, 30, 21, 25, 168, 167, 151.2, 150),
(2023, 7, 31, 30, 20, 25, 160, 165, 144, 150),
(2023, 8, 31, 30, 22, 26, 176, 174, 158.4, 156),
(2023, 9, 30, 30, 21, 26, 168, 172, 151.2, 156),
(2023, 10, 31, 30, 21, 25, 168, 167, 151.2, 150),
(2023, 11, 30, 30, 22, 26, 176, 174, 158.4, 156),
(2023, 12, 31, 30, 20, 25, 160, 167, 144, 150);

insert into `working_time_balance`(`year`, `month`,`calendar_days`, `working_calendar_days`, `working_5_days`, `working_6_days`, `w40_5d_hours`, `w40_6d_hours`, `w36_5d_hours`, `w36_6d_hours`) VALUES
(2024, 1,  31, 29, 21, 25, 168, 167, 151.2, 150),
(2024, 2,  29, 29, 21, 25, 168, 167, 151.2, 150),
(2024, 3,  31, 27, 17, 22, 136, 146, 122.4, 132),
(2024, 4,  30, 30, 22, 26, 176, 174, 158.4, 156),
(2024, 5,  31, 28, 20, 24, 160, 160, 144, 144),
(2024, 6,  30, 30, 20, 25, 160, 165, 144, 150),
(2024, 7,  31, 30, 22, 26, 176, 176, 158.4, 156),
(2024, 8,  31, 30, 21, 26, 168, 172, 151.2, 156),
(2024, 9,  30, 30, 21, 25, 168, 167, 151.2, 150),
(2024, 10, 31, 30, 22, 26, 176, 174, 158.4, 156),
(2024, 11, 30, 30, 21, 26, 168, 172, 151.2, 156),
(2024, 12, 31, 30, 21, 25, 168, 167, 151.2, 150);

insert into `working_time_balance`(`year`, `month`,`calendar_days`, `working_calendar_days`, `working_5_days`, `working_6_days`, `w40_5d_hours`, `w40_6d_hours`, `w36_5d_hours`, `w36_6d_hours`) VALUES
(2022, 1,  31, 29, 18, 23, 144, 153, 129.6, 138),
(2022, 2,  28, 28, 20, 24, 160, 160, 144, 144),
(2022, 3,  31, 27, 19, 23, 152, 153, 136.8, 138),
(2022, 4,  30, 30, 21, 26, 168, 172, 151.2, 156),
(2022, 5,  31, 28, 19, 23, 152, 155, 136.8, 138),
(2022, 6,  30, 30, 22, 26, 176, 174, 158.4, 156),
(2022, 7,  31, 30, 20, 24, 160, 160, 144, 144),
(2022, 8,  31, 30, 22, 26, 176, 174, 158.4, 156),
(2022, 9,  30, 30, 22, 26, 176, 174, 158.4, 156),
(2022, 10, 31, 30, 20, 25, 160, 165, 144, 150),
(2022, 11, 30, 30, 22, 26, 176, 174, 158.4, 156),
(2022, 12, 31, 30, 21, 26, 168, 172, 151.2, 156);


insert into `bonus_fines_types`(`id`, `name`, `type`) VALUES
(1,'Бонусы и проценты', 'bonus'),
(2,'Премия', 'bonus'),
(3,'KPI процентный', 'bonus'),
(4,'KPI результативный', 'bonus'),
(5,'Штрафы', 'fines'),
(6,'Прочие удержания', 'fines'),
(7,'Удержания по ТМЦ(Форма)', 'fines'),
(8,'Удержания по ТМЦ(Обувь)', 'fines'),
(9,'Удержания по ТМЦ(Инструмент)', 'fines');


insert into `department`(`id`, `name`) VALUES
(1,'Бухгалтерия'),
(2,'Цех производство'),
(3,'Операторы'),
(4,'Сварщики'),
(5,'Слесаря'),
(6,'Полимерщики'),
(7,'Склад'),
(8,'Штамповщики'),
(9,'Администрация'),
(10,'Отдел продаж'),
(11,'Инженерный отдел');

insert into `direction`(`id`, `name`) VALUES
(1,'Администрация'),
(2,'Цех'),
(3,'Монтаж');

insert into `position`(`id`, `name`) VALUES
(29,'зам производства'),
(30,'пом.маляра'),
(31,'полимерщик'),
(32,'заведующий складом'),
(33,'штамповщик'),
(34,'старший менеджер'),
(35,'РОП АП'),
(36,'дизайнер'),
(37,'учредитель');

insert into employee (surname, name, salary, department, direction, position, company) VALUES
('Мацак', 'Андрей', 250000, 4, 2, 18, 2),
('Аюпов', 'Артем', 200000, 4, 2, 18, 2),
('Эфрон', 'Максим', 170000, 5, 2, 19, 2),
('Фефелов', 'Роман', 130000, 5, 2, 19, 2),
('Квак', 'Александр', 110000, 5, 2, 30, 2),
('Уримбек', 'Максат', 70000, 5, 2, 19, 2),
('Жумакан', 'Елнур', 70000, 5, 2, 19, 2),
('Муратов', 'Даулет', 70000, 5, 2, 19, 2),
('Абдрахим', 'Жигер', 70000, 5, 2, 19, 2),
('Амангельди', 'Акжол', 70000, 5, 2, 19, 2),
('Жарылгасов', 'Кайрат', 140000, 5, 2, 19, 2),
('Сарсенбаев', 'Каныш', 180000, 6, 2, 31, 2),

('Канетов', 'Т.', 250000, 10, null, 12, 3),
('Амантай', 'д.', 250000, 10, null, 12, 3),
('Шаяхметов', 'Б.', 250000, 10, null, 12, 3),

('Ануарбеков', 'Самат', 450000, 11, 1, 8, 3),
('Балгаева', 'Назира', 250000, 11, 1, 8, 3),
('Давлечин', 'Дмитрий', 350000, 11, 1, 36, 3);
-- ================================================== UPDATES ===========================================================================
update employee set salary = 75000,  department=null, direction=1, position=9, company=2 where id=15;
update employee set salary = 250000, department=null, direction=1, position=23, company=2 where id=28;
update employee set salary = 170000, department=null, direction=1, position=24, company=4 where id=46;
update employee set salary = 500000, department=1,    direction=1, position=4, company=2 where id=18;
update employee set salary = 400000, department=2,    direction=2, position=15, company=2 where id=10;
update employee set salary = 260000, department=3,    direction=2, position=16, company=2 where id=1;
update employee set salary = 220000, department=3,    direction=2, position=16, company=2 where id=58;
update employee set salary = 300000, department=4,    direction=2, position=29, company=2 where id=7;
update employee set salary = 220000, department=4,    direction=2, position=18, company=2 where id=27;
update employee set salary = 250000, department=4,    direction=2, position=18, company=2 where id=25;
update employee set salary = 200000, department=4,    direction=2, position=18, company=2 where id=19;
--update employee set salary = 250000, department=4,    direction=2, position=18, company=2 where id=Мацак Андрей;
--update employee set salary = 200000, department=4,    direction=2, position=18 company=4 where id=Аюпов Артем;
update employee set salary = 170000, department=5,    direction=2, position=19, company=2 where id=36;
update employee set salary = 220000, department=5,    direction=2, position=19, company=2 where id=37;
update employee set salary = 135000, department=5,    direction=2, position=19, company=2 where id=57;
update employee set salary = 180000, department=5,    direction=2, position=28, company=2 where id=59;
--update employee set salary = 170000, department=5,    direction=2, position=19, company=2 where id=Эфрон Максим;
--update employee set salary = 130000, department=5,    direction=2, position=19, company=2 where id=Фефелов Роман;
--update employee set salary = 110000, department=5,    direction=2, position=30, company=2 where id=Квак Александр;
--update employee set salary = 70000,  department=5,    direction=2, position=19, company=2 where id=Уримбек Максат;
--update employee set salary = 70000,  department=5,    direction=2, position=19, company=2 where id=Жумакан Елнур;
--update employee set salary = 70000,  department=5,    direction=2, position=19, company=2 where id=Муратов Даулет;
--update employee set salary = 70000,  department=5,    direction=2, position=19, company=2 where id=Абдрахим Жигер;
--update employee set salary = 70000,  department=5,    direction=2, position=19, company=2 where id=Амангельди Акжол;
--update employee set salary = 140000, department=5,    direction=2, position=19, company=2 where id=Жарылгасов Кайрат;
--update employee set salary = 180000, department=6,    direction=2, position=31, company=2 where id=Сарсенбаев Каныш;
update employee set salary = 220000, department=7,    direction=2, position=32, company=4 where id=14;
update employee set salary = 170000, department=8,    direction=2, position=33, company=2 where id=23;

update employee set salary = 500000,  department=9,   direction=1, position=6, company=3 where id=17;
update employee set salary = 2500000, department=9,   direction=1, position=null, company=3 where id=38;
update employee set salary = 350000,  department=9,   direction=1, position=3, company=3 where id=32;
update employee set salary = 270000,  department=9,   direction=1, position=1, company=3 where id=2;
  
update employee set salary = 200000, department=10,   direction=1, position=34, company=3 where id=5;
update employee set salary = 450000, department=10,   direction=1, position=35, company=3 where id=31;
--update employee set salary = 250000, department=10,   direction=null, position=12, company=3 where id=Канетов Т.;
--update employee set salary = 250000, department=10,   direction=null, position=12, company=3 where id=Амантай Д.;
--update employee set salary = 250000, department=10,   direction=null, position=12, company=3 where id=Шаяхметов Б.;
  
--update employee set salary = 450000, department=11,   direction=1, position=8, company=3 where id=Ануарбеков Самат;
--update employee set salary = 250000, department=11,   direction=1, position=8, company=3 where id=Балгаева Назира;
--update employee set salary = 350000, department=11,   direction=1, position=36, company=3 where id=Давлечин Дмитрий;

update employee set salary = 300000, department=null, direction=3, position=26, company=4 where id=48;
update employee set salary = 300000, department=null, direction=1, position=3, company=4 where id=49;
