/* Add default currencies */

INSERT INTO `currency` VALUES (1,'a:2:{s:5:\"es-ES\";s:4:\"Euro\";s:5:\"en-US\";s:0:\"\";}','€'),(2,'a:2:{s:5:\"es-ES\";s:5:\"Dolar\";s:5:\"en-US\";s:0:\"\";}','$');

INSERT INTO `currency_change` VALUES (1,1,2,1.325),(2,2,1,0.75);

/* Set transports and initial countries */

INSERT INTO `zone_shop` VALUES (1,'a:2:{s:5:\"es-ES\";s:7:\"España\";s:5:\"en-US\";s:5:\"Spain\";}','ES',0,0),(2,'a:2:{s:5:\"es-ES\";s:14:\"Estados Unidos\";s:5:\"en-US\";s:13:\"United States\";}','US',0,0),(3,'a:2:{s:5:\"es-ES\";s:15:\"Resto del mundo\";s:5:\"en-US\";s:18:\"All over the world\";}','WORLD',0,1);

INSERT INTO `transport` (IdTransport, name, country) VALUES (1,'SEUR',1),(2,'UPS',2),(3,'GPS',3);

INSERT INTO `transport` (name, country) VALUES ('', NULL);

update transport set IdTransport=0 where name='';

INSERT INTO `country_shop` VALUES (1,'a:2:{s:5:\"es-ES\";s:7:\"España\";s:5:\"en-US\";s:5:\"Spain\";}', 'españa', 'spain','ES',NULL,1),(2,'a:2:{s:5:\"es-ES\";s:14:\"Estados Unidos\";s:5:\"en-US\";s:13:\"United States\";}', 'estados-unidos', 'united-states', 'US',NULL,2),(3,'a:2:{s:5:\"es-ES\";s:15:\"Resto del mundo\";s:5:\"en-US\";s:18:\"All over the world\";}', 'resto-del-mundo', 'all-over-the-world','WORLD',NULL,3);

/* Set payment form */

INSERT INTO `payment_form` VALUES (1,'a:2:{s:5:\"es-ES\";s:15:\"Contrareembolso\";s:5:\"en-US\";s:10:\"Ondelivery\";}','ondelivery.php',5);

/* Set config_shop */

insert into `config_shop` (yes_taxes, num_news, idcurrency) VALUES (0, 10, 1);