--
-- Database: `mymanagementsystem`
--

--
-- Dump dei dati per la tabella `stocked_up_products`
--

INSERT INTO `stocked_up_products` (`prodId`, `name`, `imgFile`, `category`, `availability`, `subcategory`) VALUES
(1, 'Bondstreet', 'bondstreet.jpeg', 'Gin', 30, 'London Dry'),
(2, 'Campari', 'default.jpeg', 'Bitter', 40, 'Alchoholic'),
(3, 'Carlo Alberto Red', 'carlo-alberto-red.jpg', 'Vermouth', 20, 'Fortified wine'),
(4, 'Soda', 'default.jpeg', 'Soda', 10, 'Mixers'),
(5, 'Schweppes Tonic', 'schweppes-tonic.jpg', 'Tonic water', 20, 'Mixers'),
(6, 'Lacrima C.O.', 'default.jpeg', 'Red Wine', 10, 'Lacrima di Morro'),
(7, 'Croissant', 'croissant.jpeg', 'Breakfast', 100, 'Sweet'),
(8, 'Mini Pizza', 'mini-pizza.jpeg', 'Breakfast', 100, 'Savoury'),
(9, 'Le Vaglie', 'le-vaglie.jpeg', 'White Wine', 10, 'Verdicchio');

--
-- Dump dei dati per la tabella `menu_products`
--

INSERT INTO `menu_products` (`prodId`, `name`, `imgFile`, `category`, `description`, `subcategory`, `price`) VALUES
(1, 'Americano', 'americano.jpg', 'Cocktail', 'A drink characterized by a favolous bitter flavour, perfect for enjoying on any occasion.', 'After-dinner', 6.5),
(2, 'Negroni', 'negroni.jpeg', 'Cocktail', 'A classic Italian cocktail, the Negroni is a balanced blend of gin, Campari, and sweet vermouth, known for its bold, bittersweet flavor.', 'After-dinner', 7),
(3, 'Espresso', 'caffe.jpeg', 'Breakfast', 'A strong, rich Italian coffee served in a small cup.', 'Coffee', 1),
(4, 'Craft Beer', 'beer.jpg', 'Beer', 'A locally brewed craft beer with a unique flavor.', 'Blond', 5),
(5, 'Mini Pizza', 'mini-pizza.jpeg', 'Breakfast', 'A small, savory pizza topped with tomato sauce and cheese.', 'Savory', 1.5),
(6, 'Croissant', 'croissant.jpeg', 'Breakfast', 'A flaky, buttery pastry, perfect for a morning treat.', 'Sweet', 1.1),
(7, 'Green Tea', 'greentea.jpeg', 'Drink', 'A soothing cup of green tea, rich in antioxidants.', 'Tea', 2.5),
(8, 'Americano Coffee', 'default.jpeg', 'Breakfast', 'A diluted espresso for a lighter, longer coffee experience.', 'Coffee', 2.5),
(13, 'Martini', 'martini.jpeg', 'Cocktail', 'A classic Martini: smooth gin, perfectly chilled, with a hint of dry vermouth, garnished with a twist of lemon or olive. Timeless elegance in a glass.', 'After-dinner', 8.5),
(14, 'Le Vaglie', 'le-vaglie.jpeg', 'White Wine', 'Le Vaglie by Stefano Antonucci is a premium dry white wine, offering crisp acidity with vibrant notes of citrus and white flowers, crafted with elegance and finesse.', 'Bottle', 24),
(15, 'Aperol Spritz', 'aperol-spritz.jpg', 'Cocktail', 'A refreshing Italian cocktail with Aperol, prosecco, and a splash of soda, garnished with an orange slice. Perfectly bittersweet.', 'Pre-dinner', 5.5);


--
-- Dump dei dati per la tabella `tables`
--

INSERT INTO `tables` (`tableId`, `creationTimestamp`, `name`, `seats`) VALUES
(1, '2024-09-05 22:11:44', 'Prova', 1),
(2, '2024-09-13 22:37:57', 'Outdoor left 2', 5),
(3, '2024-09-09 10:00:00', 'Sunflower', 4),
(4, '2024-09-09 12:00:00', 'Lavender', 6),
(5, '2024-09-09 14:00:00', 'Daisy', 4),
(6, '2024-09-09 16:00:00', 'Bluebell', 2),
(7, '2024-09-09 18:00:00', 'Marigold', 8),
(8, '2024-09-10 10:00:00', 'Rose', 6),
(9, '2024-09-10 12:00:00', 'Tulip', 4),
(10, '2024-09-10 14:00:00', 'Orchid', 6),
(11, '2024-09-10 16:00:00', 'Lilac', 2),
(12, '2024-09-10 18:00:00', 'Jasmine', 8),
(13, '2024-09-11 10:00:00', 'Iris', 6),
(14, '2024-09-11 12:00:00', 'Lily', 4),
(15, '2024-09-11 14:00:00', 'Poppy', 6),
(16, '2024-09-11 16:00:00', 'Violet', 2),
(17, '2024-09-11 18:00:00', 'Daffodil', 8),
(18, '2024-09-12 10:00:00', 'Peony', 6),
(19, '2024-09-12 12:00:00', 'Magnolia', 4),
(20, '2024-09-12 14:00:00', 'Begonia', 6),
(21, '2024-09-12 16:00:00', 'Zinnia', 2),
(22, '2024-09-12 18:00:00', 'Geranium', 8),
(23, '2024-09-13 10:00:00', 'Azalea', 6),
(24, '2024-09-13 12:00:00', 'Hyacinth', 4),
(25, '2024-09-13 14:00:00', 'Camellia', 6),
(26, '2024-09-13 16:00:00', 'Heather', 2),
(27, '2024-09-13 18:00:00', 'Petunia', 8),
(28, '2024-09-14 10:00:00', 'Freesia', 6),
(29, '2024-09-14 12:00:00', 'Chrysanthemum', 4),
(30, '2024-09-14 14:00:00', 'Amaryllis', 6),
(31, '2024-09-14 16:00:00', 'Dahlia', 2),
(32, '2024-09-14 18:00:00', 'Gardenia', 8);

--
-- Dump dei dati per la tabella `receipts`
--

INSERT INTO `receipts` (`receiptId`, `dateAndTime`, `total`, `paymentMethod`, `givenMoney`, `changeAmount`) VALUES
(1, '2024-09-05 22:20:13', 9, 'electronic', NULL, NULL),
(2, '2024-09-13 22:42:45', 34.1, 'electronic', NULL, NULL),
(3, '2024-09-09 10:45:00', 45.5, 'cash', 50, 4.5),
(4, '2024-09-09 11:45:00', 60, 'electronic', NULL, NULL),
(5, '2024-09-09 12:45:00', 34.75, 'cash', 40, 5.25),
(6, '2024-09-09 13:45:00', 58, 'electronic', NULL, NULL),
(7, '2024-09-09 14:45:00', 90.25, 'cash', 100, 9.75),
(8, '2024-09-09 15:45:00', 50, 'electronic', NULL, NULL),
(9, '2024-09-09 16:45:00', 38.5, 'cash', 40, 1.5),
(10, '2024-09-09 17:45:00', 65, 'electronic', NULL, NULL),
(11, '2024-09-09 18:45:00', 48, 'cash', 50, 2),
(12, '2024-09-09 19:45:00', 90, 'electronic', NULL, NULL),
(13, '2024-09-10 10:45:00', 55.5, 'cash', 60, 4.5),
(14, '2024-09-10 11:45:00', 75, 'electronic', NULL, NULL),
(15, '2024-09-10 12:45:00', 29.75, 'cash', 30, 0.25),
(16, '2024-09-10 13:45:00', 95, 'electronic', NULL, NULL),
(17, '2024-09-10 14:45:00', 42.25, 'cash', 50, 7.75),
(18, '2024-09-10 15:45:00', 63, 'electronic', NULL, NULL),
(19, '2024-09-10 16:45:00', 82.5, 'cash', 100, 17.5),
(20, '2024-09-10 17:45:00', 55, 'electronic', NULL, NULL),
(21, '2024-09-10 18:45:00', 72, 'cash', 80, 8),
(22, '2024-09-10 19:45:00', 89, 'electronic', NULL, NULL),
(23, '2024-09-11 10:45:00', 33.5, 'cash', 40, 6.5),
(24, '2024-09-11 11:45:00', 107, 'electronic', NULL, NULL),
(25, '2024-09-11 12:45:00', 62.75, 'cash', 70, 7.25),
(26, '2024-09-11 13:45:00', 44, 'electronic', NULL, NULL),
(27, '2024-09-11 14:45:00', 79.25, 'cash', 90, 10.75),
(28, '2024-09-11 15:45:00', 85, 'electronic', NULL, NULL),
(29, '2024-09-11 16:45:00', 66.5, 'cash', 70, 3.5),
(30, '2024-09-11 17:45:00', 95, 'electronic', NULL, NULL),
(31, '2024-09-11 18:45:00', 38, 'cash', 40, 2),
(32, '2024-09-11 19:45:00', 55, 'electronic', NULL, NULL),
(33, '2024-09-12 10:45:00', 43.5, 'cash', 50, 6.5),
(34, '2024-09-12 11:45:00', 88, 'electronic', NULL, NULL),
(35, '2024-09-12 12:45:00', 71.75, 'cash', 80, 8.25),
(36, '2024-09-12 13:45:00', 49, 'electronic', NULL, NULL),
(37, '2024-09-12 14:45:00', 91.25, 'cash', 100, 8.75),
(38, '2024-09-12 15:45:00', 64, 'electronic', NULL, NULL),
(39, '2024-09-12 16:45:00', 55.5, 'cash', 60, 4.5),
(40, '2024-09-12 17:45:00', 75, 'electronic', NULL, NULL),
(41, '2024-09-12 18:45:00', 62, 'cash', 70, 8),
(42, '2024-09-12 19:45:00', 94, 'electronic', NULL, NULL),
(43, '2024-09-13 10:45:00', 59.5, 'cash', 60, 0.5),
(44, '2024-09-13 11:45:00', 88, 'electronic', NULL, NULL),
(45, '2024-09-13 12:45:00', 79.75, 'cash', 80, 0.25),
(46, '2024-09-13 13:45:00', 95, 'electronic', NULL, NULL),
(47, '2024-09-13 14:45:00', 42.25, 'cash', 50, 7.75),
(48, '2024-09-13 15:45:00', 73, 'electronic', NULL, NULL),
(49, '2024-09-13 16:45:00', 82.5, 'cash', 100, 17.5),
(50, '2024-09-13 17:45:00', 59, 'electronic', NULL, NULL),
(51, '2024-09-13 18:45:00', 92, 'cash', 100, 8),
(52, '2024-09-13 19:45:00', 83, 'electronic', NULL, NULL),
(53, '2024-09-14 10:45:00', 47.5, 'cash', 50, 2.5),
(54, '2024-09-14 11:45:00', 92, 'electronic', NULL, NULL),
(55, '2024-09-14 12:45:00', 64.75, 'cash', 70, 5.25),
(56, '2024-09-14 13:45:00', 95, 'electronic', NULL, NULL),
(57, '2024-09-14 14:45:00', 42.25, 'cash', 50, 7.75),
(58, '2024-09-14 15:45:00', 66, 'electronic', NULL, NULL),
(59, '2024-09-14 16:45:00', 95.5, 'cash', 100, 4.5),
(60, '2024-09-14 17:45:00', 77, 'electronic', NULL, NULL),
(61, '2024-09-14 18:45:00', 69, 'cash', 70, 1),
(62, '2024-09-14 19:45:00', 59, 'electronic', NULL, NULL);

--
-- Dump dei dati per la tabella `variations`
--

INSERT INTO `variations` (`variationId`, `additionalRequest`, `additionalPrice`) VALUES
(1, 'Angostura drops', 0.1),
(2, 'Martini glass', NULL),
(3, 'On the rocks', NULL),
(4, 'Macchiato', 0.2),
(5, 'Double Vodka', 2.5),
(6, 'No ice', 0);

--
-- Dump dei dati per la tabella `additional_requests`
--

INSERT INTO `additional_requests` (`menuProdId`, `tableId`, `orderedProdId`, `variationId`) VALUES
(1, 2, 3, 1),
(4, 1, 1, 5),
(4, 2, 4, 3);

--
-- Dump dei dati per la tabella `customer_orders`
--

INSERT INTO `customer_orders` (`orderId`, `timestamp`, `inPreparation`, `delivered`, `tableId`, `waiterId`) VALUES
(1, '2024-09-05 22:12:15', 0, 0, 1, 2),
(2, '2024-09-13 22:40:43', 0, 0, 2, 1),
(63, '2024-09-09 10:30:00', 1, 0, 1, 2),
(64, '2024-09-09 11:30:00', 0, 1, 1, 2),
(65, '2024-09-09 12:30:00', 1, 0, 2, 2),
(66, '2024-09-09 13:30:00', 1, 1, 2, 2),
(67, '2024-09-09 14:30:00', 1, 0, 3, 2),
(68, '2024-09-09 15:30:00', 1, 1, 3, 2),
(69, '2024-09-09 16:30:00', 1, 0, 4, 2),
(70, '2024-09-09 17:30:00', 1, 1, 4, 2),
(71, '2024-09-09 18:30:00', 1, 0, 5, 2),
(72, '2024-09-09 19:30:00', 1, 1, 5, 2),
(73, '2024-09-10 10:30:00', 1, 0, 6, 2),
(74, '2024-09-10 11:30:00', 1, 1, 6, 2),
(75, '2024-09-10 12:30:00', 1, 0, 7, 2),
(76, '2024-09-10 13:30:00', 1, 1, 7, 2),
(77, '2024-09-10 14:30:00', 1, 0, 8, 2),
(78, '2024-09-10 15:30:00', 1, 1, 8, 2),
(79, '2024-09-10 16:30:00', 1, 0, 9, 2),
(80, '2024-09-10 17:30:00', 1, 1, 9, 2),
(81, '2024-09-10 18:30:00', 1, 0, 10, 2),
(82, '2024-09-10 19:30:00', 1, 1, 10, 2),
(83, '2024-09-11 10:30:00', 1, 0, 11, 2),
(84, '2024-09-11 11:30:00', 1, 1, 11, 2),
(85, '2024-09-11 12:30:00', 1, 0, 12, 2),
(86, '2024-09-11 13:30:00', 1, 1, 12, 2),
(87, '2024-09-11 14:30:00', 1, 0, 13, 2),
(88, '2024-09-11 15:30:00', 1, 1, 13, 2),
(89, '2024-09-11 16:30:00', 1, 0, 14, 2),
(90, '2024-09-11 17:30:00', 1, 1, 14, 2),
(91, '2024-09-11 18:30:00', 1, 0, 15, 2),
(92, '2024-09-11 19:30:00', 1, 1, 15, 2),
(93, '2024-09-12 10:30:00', 1, 0, 16, 2),
(94, '2024-09-12 11:30:00', 1, 1, 16, 2),
(95, '2024-09-12 12:30:00', 1, 0, 17, 2),
(96, '2024-09-12 13:30:00', 1, 1, 17, 2),
(97, '2024-09-12 14:30:00', 1, 0, 18, 2),
(98, '2024-09-12 15:30:00', 1, 1, 18, 2),
(99, '2024-09-12 16:30:00', 1, 0, 19, 2),
(100, '2024-09-12 17:30:00', 1, 1, 19, 2),
(101, '2024-09-12 18:30:00', 1, 0, 20, 2),
(102, '2024-09-12 19:30:00', 1, 1, 20, 2),
(103, '2024-09-13 10:30:00', 1, 0, 21, 2),
(104, '2024-09-13 11:30:00', 1, 1, 21, 2),
(105, '2024-09-13 12:30:00', 1, 0, 22, 2),
(106, '2024-09-13 13:30:00', 1, 1, 22, 2),
(107, '2024-09-13 14:30:00', 1, 0, 23, 2),
(108, '2024-09-13 15:30:00', 1, 1, 23, 2),
(109, '2024-09-13 16:30:00', 1, 0, 24, 2),
(110, '2024-09-13 17:30:00', 1, 1, 24, 2),
(111, '2024-09-13 18:30:00', 1, 0, 25, 2),
(112, '2024-09-13 19:30:00', 1, 1, 25, 2),
(113, '2024-09-14 10:30:00', 1, 0, 26, 2),
(114, '2024-09-14 11:30:00', 1, 1, 26, 2),
(115, '2024-09-14 12:30:00', 1, 0, 27, 2),
(116, '2024-09-14 13:30:00', 1, 1, 27, 2),
(117, '2024-09-14 14:30:00', 1, 0, 28, 2),
(118, '2024-09-14 15:30:00', 1, 1, 28, 2),
(119, '2024-09-14 16:30:00', 1, 0, 29, 2),
(120, '2024-09-14 17:30:00', 1, 1, 29, 2),
(121, '2024-09-14 18:30:00', 1, 0, 30, 2),
(122, '2024-09-14 19:30:00', 1, 1, 30, 2);

--
-- Dump dei dati per la tabella `employees`
--

INSERT INTO `employees` (`employeeId`, `email`, `password`, `cf`, `name`, `surname`, `city`, `zipCode`, `streetName`, `streetNumber`, `birthday`, `hiringDate`, `isAdmin`, `isWaiter`, `isKitchenStaff`, `isStorekeeper`) VALUES
(1, 'proprietario@volume.it', 'admin', 'DTLFRC02R01D488R', 'Diotta', 'Nax', 'Fano', '61032', 'Via IV Novembre', '99A', '2002-10-01', '2024-08-01', 1, 0, 0, 0),
(2, 'cameriere@volume.it', 'cameriere', 'MRAJVD02M03D488V', 'Astro', 'Baleno', 'Fano', '61032', 'Via de\' Gasparoli', '5', '2002-08-03', '2024-08-23', 0, 1, 0, 1),
(3, 'cucina@volume.it', 'cucina', 'SNTGLI02D10C573U', 'Gio', 'Santi', 'Sarsina', '47027', 'Non me la ricordo', 'mai', '2002-04-10', '2023-08-20', 0, 0, 1, 1);

--
-- Dump dei dati per la tabella `ingredients`
--

INSERT INTO `ingredients` (`menuProdId`, `ingredientId`, `portionSize`) VALUES
(2, 1, '30ml'),
(1, 2, '30ml'),
(2, 2, '30ml'),
(1, 3, '30ml'),
(2, 3, '30ml'),
(13, 3, '15ml'),
(1, 4, '30ml');

--
-- Dump dei dati per la tabella `ordinations`
--

INSERT INTO `ordinations` (`menuProdId`, `tableId`, `orderedProdId`, `orderId`, `quantity`) VALUES
(4, 1, 1, 1, 1),
(5, 1, 2, 1, 1),
(1, 2, 3, 2, 1),
(4, 2, 4, 2, 4),
(5, 2, 5, 2, 5);

--
-- Dump dei dati per la tabella `paid_products`
--

INSERT INTO `paid_products` (`menuProdId`, `tableId`, `orderedProdId`, `receiptId`, `quantity`) VALUES
(1, 2, 3, 2, 1),
(4, 1, 1, 1, 1),
(4, 2, 4, 2, 4),
(5, 1, 2, 1, 1),
(5, 2, 5, 2, 5);

--
-- Dump dei dati per la tabella `products_in_table`
--

INSERT INTO `products_in_table` (`orderedProdId`, `menuProdId`, `tableId`, `quantity`, `finalPrice`, `hasVariation`, `numPaid`) VALUES
(1, 4, 1, 1, 7.5, 1, 1),
(2, 5, 1, 1, 1.5, 0, 1),
(3, 1, 2, 1, 6.6, 1, 1),
(4, 4, 2, 4, 5, 1, 4),
(5, 5, 2, 5, 1.5, 0, 5);

--
-- Dump dei dati per la tabella `stock_orders`
--

INSERT INTO `stock_orders` (`orderId`, `creationTimestamp`, `sent`, `estimatedCost`, `storekeeperId`) VALUES
(3, '2024-09-05 22:31:52', 0, 274, 2),
(4, '2024-09-13 23:31:29', 0, 301.3, 1),
(5, '2024-09-09 07:32:31', 1, 1371.35, 3);

--
-- Dump dei dati per la tabella `suppliers`
--

INSERT INTO `suppliers` (`companyName`, `email`) VALUES
('Astro Food', 'astrofood@suppliers.com'),
('Barzetti', 'barzetti@suppliers.com'),
('Blue Bay', 'bluebay@suppliers.com'),
('Caribbean Company', 'caribbeancomp@suppliers.com'),
('Char Friends', 'charfriends@suppliers.com'),
('Nax Wines', 'naxwines@suppliers.com');

--
-- Dump dei dati per la tabella `supply_costs`
--

INSERT INTO `supply_costs` (`prodId`, `companyName`, `cost`) VALUES
(7, 'Astro Food', 0.65),
(8, 'Astro Food', 0.45),
(1, 'Barzetti', 18.1),
(2, 'Barzetti', 12.4),
(4, 'Barzetti', 0.7),
(5, 'Barzetti', 1),
(9, 'Barzetti', 10),
(4, 'Blue Bay', 0.7),
(5, 'Blue Bay', 0.9),
(1, 'Caribbean Company', 16.7),
(2, 'Caribbean Company', 15.3),
(3, 'Caribbean Company', 12.2),
(1, 'Char Friends', 14.3),
(2, 'Char Friends', 13.1),
(4, 'Char Friends', 0.69),
(3, 'Nax Wines', 9.9),
(9, 'Nax Wines', 8.9);

--
-- Dump dei dati per la tabella `supply_items`
--

INSERT INTO `supply_items` (`orderId`, `supplierName`, `prodId`, `quantity`) VALUES
(3, 'Char Friends', 1, 10),
(4, 'Char Friends', 1, 10),
(3, 'Char Friends', 2, 10),
(4, 'Char Friends', 4, 20),
(4, 'Blue Bay', 5, 50),
(4, 'Astro Food', 7, 50),
(4, 'Astro Food', 8, 50),
(4, 'Nax Wines', 9, 5);


