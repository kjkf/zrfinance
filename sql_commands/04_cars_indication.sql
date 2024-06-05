insert into roles (role) values ('driver');

CREATE TABLE `cars` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user` int(11) NOT NULL unique,
 `car_name` varchar(250) NOT NULL unique,
 `consumption` smallint NOT NULL,
 
 PRIMARY KEY (`id`),
 KEY `car_user` (`user`),
 CONSTRAINT `car_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `cars_indication` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `car` int(11) NOT NULL,
 `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `indication` INT NOT NULL,
 `pic` varchar(50) NOT NULL,
 `date_key` varchar(50) not null,
 
 PRIMARY KEY (`id`),
 KEY `car` (`car`),
 CONSTRAINT `car` FOREIGN KEY (`car`) REFERENCES `cars` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `cars_indication_end` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `car` int(11) NOT NULL,
 `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `indication` INT NOT NULL,
 `pic` varchar(50) NOT NULL,
 `date_key` varchar(50) not null,
 
 PRIMARY KEY (`id`),
 KEY `car` (`car`),
 CONSTRAINT `car_end` FOREIGN KEY (`car`) REFERENCES `cars` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `coupons` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `base` int(11) NOT NULL,
 `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `quantity` smallints ,
 `money` decimal ,
 
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `coupons_base` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `base` int(11) NOT NULL, -- идентификатор пользователя или 0 - если пила/кара
  
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `coupons_receipt` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `quantity` int not null,
  
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

insert into coupons_base('base') values 
(0), (134)