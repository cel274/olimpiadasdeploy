-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2025 at 05:12 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agvi`
--

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idViaje` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `fechaReserva` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `nombreRol` varchar(255) NOT NULL,
  `orden` int(11) NOT NULL,
  `idRol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`nombreRol`, `orden`, `idRol`) VALUES
('Usuario', 1, 1),
('Administrador', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `nombre` varchar(25) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `rol` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`nombre`, `clave`, `rol`, `id`) VALUES
('t4rtadericotaa', '$2y$10$eeeMj3QrAuiR5wBZ8L.i7Oa3yW37w1WqM/ybdojmsKCwedAdsTKLO', 1, 12),
('asd123', '$2y$10$P.jXDKbMIhEUL4oOsQqFOuGLg8osXKSJjIO8awv963o8.AM2Avzbi', 1, 13),
('manteca700', '$2y$10$1pxUSNk9CApUktneOOt8Mel5k5fyi3jbB0M42Kfjml2rMnSo1vMty', 1, 14),
('hola', '$2y$10$mgVBwVvB3mXPxWefGuE4NeCHvXKuRAtCp9EUbAkQrHw7bdiJl.t3K', 1, 15),
('sumo1987', '$2y$10$zG54.ApmSZXXKz7muxNXTuAn4pBd7hLFDTuHSqOxJ0ewA47IErqGa', 1, 16),
('juanito', '$2y$10$MqgDM2tbZYyGdWHRyu3la.dtCTg5czmTB/JeIv7BcLu5ExDlThmcm', 1, 17),
('zapato', '$2y$10$yan7JbVFYEI8nqLCTRsO8e48D77szgSfwGtPNYuLSCTI7Fodmjmgi', 1, 18),
('asd1', '$2y$10$yfBNTnNQOJe6PRqDdB6Qper9O2BbX7/GpjwwplRbuLZETpcTVpJ8C', 1, 19),
('admin', '$2y$10$O1GsAv679bqP/74X09kLQe2xwWA/Lvaq8PIiTuPRuH5MSAaeqltB.', 2, 21);

-- --------------------------------------------------------

--
-- Table structure for table `viajes`
--

CREATE TABLE `viajes` (
  `id` int(11) NOT NULL,
  `destino` varchar(100) DEFAULT NULL,
  `pais` varchar(5) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `viajes`
--

INSERT INTO `viajes` (`id`, `destino`, `pais`, `precio`) VALUES
(1, 'San Carlos de Bariloche', 'AR', 350000.00),
(2, 'Mendoza', 'AR', 250000.00),
(3, 'Santiago de Chile', 'CL', 400000.00),
(10, 'Buenos Aires', 'AR', 100000.00),
(13, 'Punta del Este', 'UY', 150000.00),
(14, 'Río de Janeiro', 'BR', 250000.00),
(15, 'Lima', 'PE', 200000.00),
(16, 'Ciudad de México', 'MX', 350000.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUsuario` (`idUsuario`),
  ADD KEY `idViaje` (`idViaje`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`idRol`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `usuarios_ibfk_1` (`rol`);

--
-- Indexes for table `viajes`
--
ALTER TABLE `viajes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `idRol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `viajes`
--
ALTER TABLE `viajes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`idViaje`) REFERENCES `viajes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `roles` (`idRol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
