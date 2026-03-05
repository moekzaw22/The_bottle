DROP TABLE product;

CREATE TABLE `product` (
  `Product_id` int(11) NOT NULL AUTO_INCREMENT,
  `Product_name` varchar(200) NOT NULL,
  `Product_type` varchar(100) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` int(11) NOT NULL,
  PRIMARY KEY (`Product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

INSERT INTO product VALUES("1","Red label","Whiskey","98","23000");
INSERT INTO product VALUES("4","Black label","Whiskey","50","30000");
INSERT INTO product VALUES("6","GR Smooth","Whiskey","58","1300");



DROP TABLE supplier;

CREATE TABLE `supplier` (
  `Supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `Supplier_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `phonenumber` int(11) NOT NULL,
  PRIMARY KEY (`Supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




