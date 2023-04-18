-- ================================ CREATE TABLES ================================

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
 `is_approved` int(2) DEFAULT 0,

 PRIMARY KEY (`id`),

 KEY `author_key` (`author`),

 CONSTRAINT `author` FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION 
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `salary_month` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `employee_id` int(11) NOT NULL, 
 `date_time` datetime NOT NULL,
 `salary_fzp` int(11) NOT NULL, 

 `working_hours_per_month` int(11) NOT NULL, -- количество рабочих часов
 `worked_hours_per_month` int(11) NOT NULL,  -- количество отработанных часов

 `increase_payments` double(10,2) DEFAULT null,  -- бонусы и прибавки
 `increase_explanation` text DEFAULT null,       -- пояснения к бонусам и прибавкам
 `decrease_payments` double(10,2) DEFAULT null,  -- штрафы и удержания
 `decrease_explanation` text DEFAULT null,       -- пояснения к штрафам и удержаниям

 `advances` double(10,2) DEFAULT null,           -- авансы
PRIMARY KEY (`id`),
 KEY `salary_fzp_key` (`salary_fzp`),
 KEY `employee_id` (`employee_id`),

 CONSTRAINT `employee` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION ,
 CONSTRAINT `salary_fzp` FOREIGN KEY (`salary_fzp`) REFERENCES `salary_fzp` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION 
 
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



-- ================================ ALTER TABLES ===========================
--ALTER TABLE employee ADD COLUMN `department` int(11) DEFAULT NULL;
--ALTER TABLE employee ADD COLUMN `direction` int(11) DEFAULT NULL;
--ALTER TABLE employee ADD COLUMN `salary` double(10, 2) not null DEFAULT 0;

--ALTER TABLE employee ADD KEY `department` (`department`);
--ALTER TABLE employee ADD KEY `direction` (`direction`);

--ALTER TABLE employee ADD CONSTRAINT `user_position` FOREIGN KEY (`position`) REFERENCES `position` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
--ALTER TABLE employee ADD CONSTRAINT `user_department` FOREIGN KEY (`department`) REFERENCES `department` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
--ALTER TABLE employee ADD CONSTRAINT `user_direction` FOREIGN KEY (`direction`) REFERENCES `direction` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
--ALTER TABLE employee ADD CONSTRAINT `user_company` FOREIGN KEY (`company`) REFERENCES `company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ================================ INSERTS ================================
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
('Бекжанов', 'Бекзат', 220000, 3, 2, 16, 2),
('Мацак', 'Андрей', 250000, 4, 2, 18, 2),
('Аюпов', 'Артем', 200000, 4, 2, 18, 2),
('Барвинок', 'Сергей', 135000, 5, 2, 19, 2),
('Уткин', 'Данил', 180000, 5, 2, 28, 2),
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
--update employee set salary = 220000, department=3,    direction=2, position=16, company=2 where id=Бекжанов Бекзат;
update employee set salary = 300000, department=4,    direction=2, position=29, company=2 where id=7;
update employee set salary = 220000, department=4,    direction=2, position=18, company=2 where id=27;
update employee set salary = 250000, department=4,    direction=2, position=18, company=2 where id=25;
update employee set salary = 200000, department=4,    direction=2, position=18, company=2 where id=19;
--update employee set salary = 250000, department=4,    direction=2, position=18, company=2 where id=Мацак Андрей;
--update employee set salary = 200000, department=4,    direction=2, position=18 company=4 where id=Аюпов Артем;
update employee set salary = 170000, department=5,    direction=2, position=19, company=2 where id=36;
update employee set salary = 220000, department=5,    direction=2, position=19, company=2 where id=37;
--update employee set salary = 135000, department=5,    direction=2, position=19, company=2 where id=Барвинок Сергей;
--update employee set salary = 180000, department=5,    direction=2, position=28, company=2 where id=Уткин Данил;
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

update employee set salary = 500000,  department=9,   direction=1, position=null, company=3 where id=17;
update employee set salary = 2500000, department=9,   direction=1, position=37, company=3 where id=38;
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

update employee set salary = 300000, department=null, direction=мон, position=26, company=4 where id=48;
update employee set salary = 300000, department=null, direction=1, position=3, company=4 where id=49;
