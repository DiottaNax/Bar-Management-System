-- *******************************
-- * Author: Federico Diotallevi *
-- * Date: 2024                  *
-- *******************************

-- Database Section
-- ________________ 

create database if not exists MyManagementSystem;

use MyManagementSystem;

-- DBSpace Section
-- _______________


-- Tables Section
-- _____________ 

create table if not exists ADDITIONAL_REQUESTS (
     variationId int not null,
     tableId int not null,
     orderedProdId int not null,
     menuProdId int not null,
     constraint ID_ADDITIONAL_REQUESTS_ID primary key (variationId, tableId, orderedProdId, menuProdId));

create table if not exists CUSTOMER_ORDERS (
     tableId int,
     orderNum int, -- it could auto_increment, but it is design to increment along with table (i.e. it could exist order n.1 for two or more diffent tables)
     timestamp datetime default current_timestamp,
     inPreparation boolean not null,
     delivered boolean not null,
     waiterId int,
     constraint ID_CUSTOMER_ORDERS_ID primary key (tableId, orderNum));

create table if not existS EMPLOYEES (
     employeeId int auto_increment,
     email varchar(312) not null,
     password varchar(512) not null,
     cf varchar(17) not null,
     name varchar(128) not null,
     surname varchar(128) not null,
     city varchar(256),
     zipCode char(6),
     streetName varchar(256),
     streetNumber char(6),
     birthday date,
     hiringDate date,
     isWaiter boolean default 0,
     isStorekeeper boolean default 0,
     isKitchenStaff boolean default 0,
     isAdmin boolean default 0,
     constraint ID_EMPLOYEES_ID primary key (employeeId));

create table if not exists INGREDIENTS (
     menuProdId int,
     ingredientId int,
     portionSize varchar(64) not null,
     constraint ID_INGREDIENTS_ID primary key (ingredientId, menuProdId));

create table if not exists MENU_PRODUCTS (
     prodId int auto_increment,
     name varchar(200) not null,
     imgFile varchar(256),
     category varchar(100) not null,
     description varchar(512) not null,
     subcategory varchar(100) not null,
     price float(1) not null,
     constraint ID_MENU_PRODUCTS_ID primary key (prodId));

create table if not exists ORDINATIONS (
     orderNum int,
     menuProdId int,
     tableId int,
     orderedProdId int,
     quantity numeric(1) not null,
     constraint ID_ORDINATIONS_ID primary key (orderNum, menuProdId, tableId, orderedProdId));

create table if not exists PAID_PRODUCTS (
     orderedProdId int,
     menuProdId int,
     tableId int,
     receiptId int,
     quantity numeric(1) not null,
     constraint ID_PAID_PRODUCTS_ID primary key (orderedProdId, menuProdId, tableId, receiptId));

create table if not exists PRODUCTS_IN_TABLE (
     orderedProdId int auto_increment,
     menuProdId int,
     tableId int,
     quantity numeric(1) not null,
     finalPrice float(1) not null,
     hasVariation boolean not null,
     numPaid numeric(1) not null,
     constraint ID_PRODUCTS_IN_TABLE_ID primary key (orderedProdId, menuProdId, tableId));

create table if not exists RECEIPTS (
     receiptId int auto_increment,
     dateAndTime datetime default current_timestamp,
     total float(1) not null,
     paymentMethod varchar(20) not null,
     givenMoney float(1),
     changeAmount float(1),
     tableId int,
     constraint ID_RECEIPTS_ID primary key (receiptId));

create table if not exists RESERVATIONS (
     cellNumber varchar(15) not null,
     dateAndTime datetime not null,
     clientName varchar(128) not null,
     seats numeric(1) not null,
     tableId int default null,
     constraint ID_RESERVATIONS_ID primary key (cellNumber, dateAndTime));

create table if not exists STOCK_ORDERS (
     orderId int auto_increment,
     creationTimestamp datetime default current_timestamp,
     sent boolean not null,
     estimatedCost float(1) not null,
     storekeeperId int,
     constraint ID_STOCK_ORDERS_ID primary key (orderId));

create table if not exists STOCKED_UP_PRODUCTS (
     prodId int auto_increment,
     name varchar(200) not null,
     imgFile varchar(256) not null,
     category varchar(100) not null,
     subcategory varchar(100) not null,
     availability numeric(1) not null,
     constraint ID_STOCKED_UP_PRODUCTS_ID primary key (prodId));

create table if not exists SUPPLIERS (
     companyName varchar(128) not null,
     email varchar(312) not null,
     constraint ID_SUPPLIERS_ID primary key (companyName));

create table if not exists SUPPLY_COSTS (
     prodId int,
     companyName varchar(128) not null,
     cost float(1) not null,
     constraint ID_SUPPLY_COSTS_ID primary key (companyName, prodId));

create table if not exists SUPPLY_ITEMS (
     prodId int,
     orderId int not null,
     supplierName varchar(128) not null,
     quantity int not null,
     constraint ID_SUPPLY_ITEMS_ID primary key (prodId, orderId, supplierName));

create table if not exists TABLES (
     tableId int auto_increment,
     creationTimestamp datetime not null,
     name varchar(128) not null,
     seats numeric(1) not null,
     constraint ID_TABLES_ID primary key (tableId));

create table if not exists VARIATIONS (
     variationId int auto_increment,
     additionalRequest varchar(512) not null,
     additionalPrice float(1),
     constraint ID_VARIATIONS_ID primary key (variationId));


-- Constraints Section
-- ___________________ 

alter table ADDITIONAL_REQUESTS add constraint REF_ADDIT_VARIA_FK
     foreign key (variationId)
     references VARIATIONS(variationId)
     on delete cascade;

alter table ADDITIONAL_REQUESTS add constraint REF_ADDIT_PRODU_FK
     foreign key (orderedProdId, menuProdId, tableId)
     references PRODUCTS_IN_TABLE(orderedProdId, menuProdId, tableId)
     on delete cascade;

alter table CUSTOMER_ORDERS add constraint REF_CUSTO_TABLE
     foreign key (tableId)
     references TABLES(tableId)
     on delete cascade;

alter table CUSTOMER_ORDERS add constraint REF_CUSTO_WAITE_FK
     foreign key (waiterId)
     references EMPLOYEES(employeeId)
     on delete set null;

alter table INGREDIENTS add constraint REF_INGRE_STOCK
     foreign key (ingredientId)
     references STOCKED_UP_PRODUCTS(prodId)
     on delete cascade;

alter table INGREDIENTS add constraint REF_INGRE_MENU__FK
     foreign key (menuProdId)
     references MENU_PRODUCTS(prodId)
     on delete cascade;

alter table ORDINATIONS add constraint REF_ORDIN_PRODU_FK
     foreign key (orderedProdId, menuProdId, tableId)
     references PRODUCTS_IN_TABLE(orderedProdId, menuProdId, tableId)
     on delete cascade;

alter table ORDINATIONS add constraint REF_ORDIN_CUSTO_FK
     foreign key (tableId, orderNum)
     references CUSTOMER_ORDERS(tableId, orderNum)
     on delete cascade;

alter table PAID_PRODUCTS add constraint REF_PAID__RECEI_FK
     foreign key (receiptId)
     references RECEIPTS(receiptId)
     on delete cascade;

alter table PAID_PRODUCTS add constraint REF_PAID__PRODU
     foreign key (orderedProdId, menuProdId, tableId)
     references PRODUCTS_IN_TABLE(orderedProdId, menuProdId, tableId)
     on delete cascade;

alter table PRODUCTS_IN_TABLE add constraint REF_PRODU_TABLE_FK
     foreign key (tableId)
     references TABLES(tableId)
     on delete cascade;

alter table PRODUCTS_IN_TABLE add constraint REF_PRODU_MENU__FK
     foreign key (menuProdId)
     references MENU_PRODUCTS(prodId)
     on delete cascade;

alter table RECEIPTS add constraint REF_RECEI_TABLE_FK
     foreign key (tableId)
     references TABLES(tableId)
     on delete set null;

alter table RESERVATIONS add constraint REF_RESER_TABLE_FK
     foreign key (tableId)
     references TABLES(tableId)
     on delete set null;

alter table STOCK_ORDERS add constraint REF_STOCK_STORE_FK
     foreign key (storekeeperId)
     references EMPLOYEES(employeeId)
     on delete set null;

alter table SUPPLY_COSTS add constraint REF_SUPPL_SUPPL_1
     foreign key (companyName)
     references SUPPLIERS(companyName)
     on delete cascade;

alter table SUPPLY_COSTS add constraint REF_SUPPL_STOCK_2_FK
     foreign key (prodId)
     references STOCKED_UP_PRODUCTS(prodId)
     on delete cascade;

alter table SUPPLY_ITEMS add constraint REF_SUPPL_SUPPL_FK
     foreign key (supplierName)
     references SUPPLIERS(companyName);

alter table SUPPLY_ITEMS add constraint REF_SUPPL_STOCK_1_FK
     foreign key (orderId)
     references STOCK_ORDERS(orderId)
     on delete cascade;

alter table SUPPLY_ITEMS add constraint REF_SUPPL_STOCK
     foreign key (prodId)
     references STOCKED_UP_PRODUCTS(prodId)
     on delete cascade;

-- Index Section
-- _____________ 

create unique index ID_ADDITIONAL_REQUESTS_IND
     on ADDITIONAL_REQUESTS (variationId, tableId, orderedProdId, menuProdId);

create index REF_ADDIT_VARIA_IND
     on ADDITIONAL_REQUESTS (variationId);

create index REF_ADDIT_PRODU_IND
     on ADDITIONAL_REQUESTS (orderedProdId, menuProdId, tableId);

create unique index ID_CUSTOMER_ORDERS_IND
     on CUSTOMER_ORDERS (tableId, orderNum);

create index REF_CUSTO_WAITE_IND
     on CUSTOMER_ORDERS (waiterId);

create unique index ID_EMPLOYEES_IND
     on EMPLOYEES (employeeId);

create unique index ID_INGREDIENTS_IND
     on INGREDIENTS (ingredientId, menuProdId);

create index REF_INGRE_MENU__IND
     on INGREDIENTS (menuProdId);

create unique index ID_MENU_PRODUCTS_IND
     on MENU_PRODUCTS (prodId);

create unique index ID_ORDINATIONS_IND
     on ORDINATIONS (orderNum, menuProdId, tableId, orderedProdId);

create index REF_ORDIN_PRODU_IND
     on ORDINATIONS (orderedProdId, menuProdId, tableId);

create index REF_ORDIN_CUSTO_IND
     on ORDINATIONS (tableId, orderNum);

create unique index ID_PAID_PRODUCTS_IND
     on PAID_PRODUCTS (orderedProdId, menuProdId, tableId, receiptId);

create index REF_PAID__RECEI_IND
     on PAID_PRODUCTS (receiptId);

create unique index ID_PRODUCTS_IN_TABLE_IND
     on PRODUCTS_IN_TABLE (orderedProdId, menuProdId, tableId);

create index REF_PRODU_TABLE_IND
     on PRODUCTS_IN_TABLE (tableId);

create index REF_PRODU_MENU__IND
     on PRODUCTS_IN_TABLE (menuProdId);

create unique index ID_RECEIPTS_IND
     on RECEIPTS (receiptId);

create index REF_RECEI_TABLE_IND
     on RECEIPTS (tableId);

create unique index ID_RESERVATIONS_IND
     on RESERVATIONS (cellNumber, dateAndTime);

create index REF_RESER_TABLE_IND
     on RESERVATIONS (tableId);

create unique index ID_STOCK_ORDERS_IND
     on STOCK_ORDERS (orderId);

create index REF_STOCK_STORE_IND
     on STOCK_ORDERS (storekeeperId);

create unique index ID_STOCKED_UP_PRODUCTS_IND
     on STOCKED_UP_PRODUCTS (prodId);

create unique index ID_SUPPLIERS_IND
     on SUPPLIERS (companyName);

create unique index ID_SUPPLY_COSTS_IND
     on SUPPLY_COSTS (companyName, prodId);

create index REF_SUPPL_STOCK_2_IND
     on SUPPLY_COSTS (prodId);

create unique index ID_SUPPLY_ITEMS_IND
     on SUPPLY_ITEMS (prodId, orderId, supplierName);

create index REF_SUPPL_SUPPL_IND
     on SUPPLY_ITEMS (supplierName);

create index REF_SUPPL_STOCK_1_IND
     on SUPPLY_ITEMS (orderId);

create unique index ID_TABLES_IND
     on TABLES (tableId);

create unique index ID_VARIATIONS_IND
     on VARIATIONS (variationId);

create unique index UNIQUE_VARIATION_REQUEST_IND
     on VARIATIONS (additionalRequest);
