CREATE TABLE `deal_goods` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `good`   int(11) NOT NULL,
 `amount`  varchar(250) NOT NULL,

  CONSTRAINT `goods` FOREIGN KEY (`good`) REFERENCES `goods` (`id`) ON  DELETE NO ACTION ON UPDATE NO ACTION, 
 
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `specificaion` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `good_id`   int(11) NOT NULL,
 `material`  int(11) NOT NULL,
 `units`     int(11) NOT NULL,
 `quantity`  double(5, 2) not null,
 `price_plan` double(15, 2) not null,
 
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `deals` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `deal_num`   varchar(25) NOT NULL unique,
 `deal_name`  varchar(250) NOT NULL,
 `deal_date`  varchar(250) NOT NULL,
 `deal_goods` int(11) NOT NULL,
 `deal_specificaion` int(11) NOT NULL,

 CONSTRAINT `deal_goods` FOREIGN KEY (`deal_goods`) REFERENCES `deal_goods` (`id`) ON  DELETE NO ACTION ON UPDATE NO ACTION, 

 CONSTRAINT `specificaion` FOREIGN KEY (`deal_specificaion`) REFERENCES `specificaion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 
 
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ============================-- REQUESTS --===========================================
CREATE TABLE `purchase_request` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `request_num`   varchar(25) NOT NULL unique,
 `deal_num`   varchar(25) NOT NULL unique,
 `request_date`  varchar(250) NOT NULL,
 `author` int(11) NOT NULL, 
 `status` int(11) NOT NULL DEFAULT '1',

 CONSTRAINT `author` FOREIGN KEY (`author`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,

 CONSTRAINT `request_status` FOREIGN KEY (`status`) REFERENCES `status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
 
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

