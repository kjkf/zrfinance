CREATE TABLE `expense_type` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `expense_type` varchar(250) NOT NULL,
 
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- alter table `contractor` add column `expense_type` int(11) NOT NULL;
-- alter table `contractor` add CONSTRAINT `expense_type_id` FOREIGN KEY (`expense_type_key`) REFERENCES `expense_type` (`id`);

insert into expense_type(`expense_type`) values
  ('Товары и услуги для обеспечения заказов'),
  ('Свои переводы'),
  ('Переезд - прочее'),
  ('Регулярный расход - юристы'),
  ('Регулярный расход - офисные расходы'),
  ('Аренда, КазСтрой'),
  ('Кредит'),
  ('Етаса - ЖР, работа на стороне'),
  ('Регулярный расход - Цех'),
  ('Етаса - ЖР, монтаж'),
  ('Регулярный расход - Реклама'),
  ('Регулярный расход - ИТ'),
  ('Товары в цех'),
  ('Прочее'),
  ('Аренда, Искер'),
  ('Регулярный расход - телефон'),
  ('Налоги и соц. отчисления');

  -- Date;Number;Вид операции;Сумма;Получатель;Автор;Комментарий
  create table `funds_operations` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `date` TIMESTAMP NOT NULL,
    `number` varchar(200) not null,
    `operation_type` varchar(200) not null default 'Оплата поставщику', 
    `sum` double(15,2) DEFAULT null,
    `contractor` int(11) NOT NULL,
    `expense_type` int(11) NOT NULL,
    `author` varchar(250) not null,
    `comments` varchar(300) not null,

    CONSTRAINT `expense_type` FOREIGN KEY (`expense_type`) REFERENCES `expense_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 
    CONSTRAINT `contractor` FOREIGN KEY (`contractor`) REFERENCES `contractor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 

    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

create table `funds_operations_supporting` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `date` TIMESTAMP NOT NULL,
    `number` varchar(200) not null,
    `operation_type` varchar(200) not null default 'Оплата поставщику', 
    `sum` double(15,2) DEFAULT null,
    `contractor` int(11) NOT NULL,
    `expense_type` int(11) NOT NULL,
    `author` varchar(250) not null,
    `comments` varchar(300) not null,

    CONSTRAINT `expense_type` FOREIGN KEY (`expense_type`) REFERENCES `expense_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 
    CONSTRAINT `contractor` FOREIGN KEY (`contractor`) REFERENCES `contractor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 

    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
