insert into config_shop (yes_taxes, num_news) VALUES (0, 10);

insert into transport (name) VALUES (0);

update transport set IdTransport =0;

/* Add default currencies */

INSERT INTO `currency` VALUES (1,'a:2:{s:5:\"es-ES\";s:4:\"Euro\";s:5:\"en-US\";s:0:\"\";}','€'),(2,'a:2:{s:5:\"es-ES\";s:5:\"Dolar\";s:5:\"en-US\";s:0:\"\";}','$');

INSERT INTO `currency_change` VALUES (1,1,2,1.325),(2,2,1,0.75);

