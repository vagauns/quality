use vendasRelatorio;

/* STATEMENTS HERE */

CREATE TABLE `produtos` (
  `id` int not null auto_increment comment 'pk_produtos_id',
  `sku` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  constraint pk_produtos_id primary key (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

insert into produtos
    (sku, name, price, created_at, updated_at)
  values
    ('0001', 'Camisa Azul', 100, '2018-01-01 00:00:00', '2018-01-01 00:00:00'),
    ('0003', 'Shorts Jeans', 100, '2018-01-01 00:00:00', '2018-01-01 00:00:00'),
    ('0004', 'Blusa Moleton', 100, '2018-01-01 00:00:00', '2018-01-01 00:00:00'),
    ('0006', 'Camisa Polo', 100, '2018-01-01 00:00:00', '2018-01-01 00:00:00'),
    ('0007', 'Meia Branca', 100, '2018-01-01 00:00:00', '2018-01-01 00:00:00'),
    ('0008', 'Camisa Regata Preta', 100, '2018-01-01 00:00:00', '2018-01-01 00:00:00'),
    ('00010', 'Camisa Regata Branca', 100, '2018-01-01 00:00:00', '2018-01-01 00:00:00');







CREATE TABLE `vendas` (
  `id` int not null auto_increment comment 'pk_vendas_id',
  `produto_id` int(11) not null,
  `quantity` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  constraint pk_vendas_id primary key (id),
  constraint fk_produtos_id_vendas foreign key (produto_id) references produtos(id)
);

