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
 `indication` smallint NOT NULL,
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
 `indication` smallint NOT NULL,
 `pic` varchar(50) NOT NULL,
 `date_key` varchar(50) not null,
 
 PRIMARY KEY (`id`),
 KEY `car` (`car`),
 CONSTRAINT `car_end` FOREIGN KEY (`car`) REFERENCES `cars` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;