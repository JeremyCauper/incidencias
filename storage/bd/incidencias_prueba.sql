/*
 Navicat Premium Data Transfer

 Source Server         : Mi MySql
 Source Server Type    : MySQL
 Source Server Version : 100425
 Source Host           : localhost:3306
 Source Schema         : incidencias_prueba

 Target Server Type    : MySQL
 Target Server Version : 100425
 File Encoding         : 65001

 Date: 05/03/2025 18:06:02
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for areas
-- ----------------------------
DROP TABLE IF EXISTS `areas`;
CREATE TABLE `areas`  (
  `id_area` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `estatus` tinyint(1) NULL DEFAULT 1,
  PRIMARY KEY (`id_area`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of areas
-- ----------------------------
INSERT INTO `areas` VALUES (1, 'Soporte', 1);
INSERT INTO `areas` VALUES (2, 'Facturacion', 1);
INSERT INTO `areas` VALUES (3, 'Supervisor', 1);
INSERT INTO `areas` VALUES (4, 'Reportes', 1);

-- ----------------------------
-- Table structure for cargo_contacto
-- ----------------------------
DROP TABLE IF EXISTS `cargo_contacto`;
CREATE TABLE `cargo_contacto`  (
  `id_cargo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_cargo`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cargo_contacto
-- ----------------------------
INSERT INTO `cargo_contacto` VALUES (1, 'Jefe de Playa', 1, NULL, NULL);
INSERT INTO `cargo_contacto` VALUES (2, 'Islero', 1, NULL, NULL);
INSERT INTO `cargo_contacto` VALUES (3, 'Jefe de Planta', 1, NULL, NULL);
INSERT INTO `cargo_contacto` VALUES (4, 'Administrador(a)', 1, NULL, NULL);
INSERT INTO `cargo_contacto` VALUES (5, 'Supervisor', 1, NULL, NULL);
INSERT INTO `cargo_contacto` VALUES (6, 'Contadora', 1, NULL, NULL);
INSERT INTO `cargo_contacto` VALUES (7, 'Asistente Contable', 1, NULL, NULL);
INSERT INTO `cargo_contacto` VALUES (8, 'Encargado', 1, NULL, NULL);
INSERT INTO `cargo_contacto` VALUES (9, 'Cajero', 1, NULL, NULL);
INSERT INTO `cargo_contacto` VALUES (10, 'Jefe de Sistemas', 1, NULL, NULL);
INSERT INTO `cargo_contacto` VALUES (11, 'Asistente de Sistemas', 1, NULL, NULL);
INSERT INTO `cargo_contacto` VALUES (12, 'Gerente General', 1, NULL, NULL);
INSERT INTO `cargo_contacto` VALUES (13, 'Gerente Comercial', 1, NULL, NULL);

-- ----------------------------
-- Table structure for contactos_empresas
-- ----------------------------
DROP TABLE IF EXISTS `contactos_empresas`;
CREATE TABLE `contactos_empresas`  (
  `id_contact` int(11) NOT NULL AUTO_INCREMENT,
  `nro_doc` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nombres` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `telefono` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `cargo` int(11) NOT NULL,
  `correo` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_contact`) USING BTREE,
  INDEX `cargo`(`cargo`) USING BTREE,
  CONSTRAINT `contactos_empresas_ibfk_1` FOREIGN KEY (`cargo`) REFERENCES `cargo_contacto` (`id_cargo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of contactos_empresas
-- ----------------------------
INSERT INTO `contactos_empresas` VALUES (2, '97542187', 'Admin', '948756321', 1, 'jcauper@gmail.com', 1, '2024-08-24 00:14:49', '2024-07-27 08:06:09');
INSERT INTO `contactos_empresas` VALUES (3, '00098358', 'Admin2', '987654321', 3, NULL, 1, '2024-12-11 14:35:15', '2024-12-11 09:22:33');
INSERT INTO `contactos_empresas` VALUES (4, '00098359', 'Admin3', '954872163', 2, NULL, 1, '2024-12-11 15:25:59', '2024-12-11 14:37:22');
INSERT INTO `contactos_empresas` VALUES (5, '97542187', 'Admin', '948756321', 1, 'jcauper@gmail.com', 1, NULL, '2024-12-30 15:50:09');
INSERT INTO `contactos_empresas` VALUES (6, '00098359', 'Admin3', '954872163', 2, NULL, 1, NULL, '2025-01-12 22:11:21');
INSERT INTO `contactos_empresas` VALUES (7, '08670150', 'PATRICIA BETSABET GUEVARA PEREZ', '965438217', 1, NULL, 1, NULL, '2025-01-31 17:17:44');
INSERT INTO `contactos_empresas` VALUES (8, '00098359', 'ERLINDA RAMIREZ OLIVEIRA', '912197130', 3, NULL, 1, NULL, '2025-02-02 18:39:06');
INSERT INTO `contactos_empresas` VALUES (9, '08670150', 'PATRICIA BETSABET GUEVARA PEREZ', '965438217', 1, NULL, 1, NULL, '2025-02-17 14:36:22');

-- ----------------------------
-- Table structure for empresas
-- ----------------------------
DROP TABLE IF EXISTS `empresas`;
CREATE TABLE `empresas`  (
  `id_empresa` int(11) NOT NULL AUTO_INCREMENT,
  `ruc_empresa` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `razon_social` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `contrato` int(11) NULL DEFAULT NULL,
  `id_nube` int(11) NULL DEFAULT NULL,
  `id_grupo` int(11) NULL DEFAULT NULL,
  `direccion` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `distrito` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `facturacion` int(11) NULL DEFAULT NULL,
  `prico` int(11) NULL DEFAULT NULL,
  `encargado` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cargo` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefono` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `correo` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `estado` int(11) NULL DEFAULT NULL,
  `eds` int(11) NULL DEFAULT NULL,
  `visitas` int(11) NULL DEFAULT NULL,
  `mantenimientos` int(11) NULL DEFAULT NULL,
  `dias_visita` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_empresa`) USING BTREE,
  INDEX `ruc_empresa`(`ruc_empresa`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 314 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of empresas
-- ----------------------------
INSERT INTO `empresas` VALUES (1, '20345774042', 'SERVICENTRO AGUKI S.A.', 0, 115, 3, 'AV. ELMER FAUCETT 5482', 'CALLAO', 1, 1, 'Freddy Taira', '', '946501508', 'ftaira@aguki.com', 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (2, '20517103633', 'AJ GROUP INVERGAS', 0, 31, 4, 'AV. SANTIAGO DE CHUCO NRO. 501 COO. UNIVERSAL (ESTACION DE SERVICIO)', 'SANTA ANITA', 1, 0, 'CAROLINA ALIAGA', 'GERENTE GENERAL', '998878575', '', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (3, '20603850913', 'A.J.C. NEGOCIOS E.I.R.L.', 0, 100, 5, 'CAL.ALVARADO NRO. 701 (KM. 11 DE LA AV. TUPAC AMARU)', 'COMAS', 1, 0, 'Juan Salazar', '', '933737615', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (4, '20603906790', 'TRANSPORTES VALL E.I.R.L.', 0, 99, 5, 'CAL.ALVARADO NRO. 701 (KM. 11 DE LA AV. TUPAC AMARU)', 'COMAS', 1, 0, 'Juan Salazar', '', '933737615', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (5, '20513567139', 'ALTA VIDDA GAS S.A.C.', 0, 14, 7, 'CAL.FELIPE SANTIAGO SALAVERRY NRO. 341 (ALT CDRA 18 DE AV CIRCUNVALACION)', 'SAN LUIS', 1, 1, 'KARLA VITTOR', 'GERENTE COMERCIAL', '995867602', 'kvittor@altaviddagas.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (6, '20518664019', 'AMEL PHARMA E.I.R.L.', 0, 148, 8, 'JR. PARURO NRO. 926 INT. 381', 'LIMA', 1, 0, '', '', '', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (7, '20604857105', 'CORPORACION AXELL S.A.C.', 0, 130, 9, 'JR. HUANTA NRO. 944 INT. B URB. BARRIOS ALTOS', 'LIMA', 1, 0, '', '', '', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (8, '20600251873', 'BALIAN S.A.C.', 0, 108, 10, 'AV. LAS PRADERAS MZA. U LOTE. 29 URB. PRADERA DE STA ANITA II E', 'EL AGUSTINO', 1, 1, 'Angel Morocco', '', '992210914', 'amorocco@balian.com.pe', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (9, '20546454224', 'CORPORACION BOTIFARMA E.I.R.L', 0, 9, 11, 'JR. PARURO NRO. 926 INT. 2076', 'LIMA', 1, 0, 'Wilmer Reyna', 'Administrador', '997364151', 'wilmer_reyna25@hotmail.com', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (10, '20156278751', 'J.R. TELECOM S.R.LTDA.', 0, 84, 12, 'Jr. Conray Grande Nro. 4909 - Urb. Parque El Naranjal', 'LOS OLIVOS', 1, 0, 'beatriz Crisostomo', 'Contadora', '965395755', 'bcrisostomo@cableperu.net', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (11, '20501688593', 'CABLE VIDEO PERU S.A.C.', 0, 78, 12, 'Jr. Conray Grande Nro. 4901 - Urb. Parque Naranjal - Cdra.13 Av. Naranjal', 'LOS OLIVOS', 1, 1, 'Maricela Cordova', 'Contadora', '966370237', 'mcordova@cableperu.pe', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (12, '20548480214', 'CATAFARMA S.A.C.', 0, 141, 14, 'AV. JOSE SANTOS CHOCANO NRO. 104 P.J. VEINTIDOS DE OCTUBRE (AL COSTADO DEL HOSP. SAN JOSE)', 'CALLAO', 1, 0, 'Edwin Carlos Diaz', 'Administrador', '918106426', 'boticainkaperu@hotmail.com', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (13, '20499102071', 'ALIMENTOS SELECTOS CAZTELLANI S.A.', 0, 6, 15, 'AV. ARAVICUS NRO. 228 URB. TAHUANTISUYO (KM.5 AV.TUPAC AMARU C/CARLOS IZAS)', 'INDEPENDENCIA', 1, 0, 'Roberto Aguilar', 'Gerente General', '996394345', 'cafecaztellani@gmail.com', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (14, '20605002448', 'SERVICENTROS CELESTE 3 SAC', 0, 144, 16, 'AV. QUILCA MZA. E LOTE. 29 URB. AEROPUERTO PROV. CONST. DEL CALLAO', 'CALLAO', 1, 0, ' Lizette Silva', ' Administracion', '987264002 ', 'servicentrocelestesa@gmail.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (15, '20168217723', 'COMERCIALIZADORA INDUSTRIAL LA MOLINA S.A.C.', 0, 92, 17, 'AV. LA MOLINA NRO. 448 URB. EL ARTESANO (BAJAR OVALO STA ANIT)', 'ATE', 1, 0, 'Carlos Briceño', '', '910736075', 'carlos.briceno@distribuidoraessa.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (16, '20491287544', 'CLINICA CORAZON DE JESUS SAC.', 0, 97, 18, 'AV. MARISCAL BENAVIDES NRO. 565', 'SAN VICENTE DE CAÑETE', 1, 0, 'Isabut', 'Administradora', '925213430', 'clinicacorazondejesus@gmail.com', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (17, '20562897128', 'CORPORACION FARMACEUTICA SALVADOR S.A.C - COFARSA S.A.C.', 0, 74, 19, 'AV. GNRAL MIGUEL IGLESIAS NRO. 947 INT. A-B ZONA D (FRENTE AL HOSPITAL MARIA AUX)', 'SAN JUAN DE MIRAFLORES', 1, 0, '', '', '', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (18, '20548279039', 'CORGAS S.A.C.', 1, 82, 20, 'LAS TORRES NRO. 497 URB. LOS SAUCES', 'ATE', 1, 1, 'GUSTAVO CHAVEZ', '', '937504719', 'gchavez36@gmail.com', 1, 1, 2, 0, 10);
INSERT INTO `empresas` VALUES (19, '10199887608', 'QUISPE YUPANQUI SILVIO', 0, 63, 21, 'MZ D27 LOTE 13 AA.HH BOCANEGRA ZONA 3 AV. QUILCA FRENTE TOTUS DE AV. QUILCA', 'SAN MARTIN DE PORRES', 1, 0, 'Henry Quispe', 'Administrador', '983213615', 'henryqc2016@hotmail.com', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (20, '20524016070', 'DELTA COMBUSTIBLES E.I.R.L.', 0, 61, 22, 'AV. ALFREDO MENDIOLA NRO. 700 URB. INGENIERIA (ALT. CRUCE AV. HABICH E INGENIERIA A 2 C)', 'SAN MARTIN DE PORRES', 1, 1, 'DARWIN ATACHAGUA', '', '957292531', 'darwin.atachagua@deltaate.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (21, '20555690534', 'DELTA ATE E.I.R.L', 0, 64, 22, 'AV. NICOLAS AYLLON NRO. 3620 A.H. SANTA ILUMINATA (CARRETERA CENTRAL CRUCE AV ATE)', 'ATE', 1, 1, 'DARWIN ATACHAGUA', '', '957292531', 'darwin.atachagua@deltaate.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (22, '20511172633', 'ESTACION DE SERVICIOS GRIFO DENVER S.R.L.', 1, 0, 24, 'Av. Canta Callao Numero 59', 'SMP', 0, 0, 'JULIO HUERTA', '', '957992031', '', 1, 1, 2, 0, 10);
INSERT INTO `empresas` VALUES (23, '20530743919', 'DIESEL MAX SRL', 0, 50, 25, 'AV. CRUZ BLANCA NRO. 996 (CHIRI GRIFO)', 'HUALMAY', 1, 1, 'WALTER GARCIA', '', '983489932', 'dieselmax21@yahoo.es', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (24, '20603821247', 'CORPORACION DISFARMED SAC', 0, 142, 26, 'PJ. OCHO MZA. D LOTE. 11 A.H. ALTO EL ROSAL', 'SAN JUAN DE LURIGANCHO', 1, 0, 'Miguel Cañahuaray', 'Administrador', '992514229', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (25, '20603635711', 'A & B DROFAR INVERSIONES S.A.C.', 0, 146, 27, 'JR. CUSCO NRO. 811 INT. 207 URB. BARRIOS ALTOS (ESPALDA DE RENIEC)', 'LIMA', 1, 0, '', '', '', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (26, '20538108295', 'DUOGAS S.A.', 0, 0, 28, 'Ca. Las Garzas Nro. 328', 'SAN ISIDRO', 0, 0, 'EDWIN ARANGO', '', '952675018', 'erango@duogas.com.pe', 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (27, '20517855252', 'INVERSIONES DUVAL SAC', 1, 58, 29, 'AV. ALFREDO MENDIOLA NRO. 6200 INT. 101 URB. MOLITALIA (CRUCE CON AVENIDA MEXICO)', 'LOS OLIVOS', 1, 0, ' Jorge Vargas', ' Administracion', ' 945628525', ' jorgev524@yahoo.com', 1, 1, 2, 0, 5);
INSERT INTO `empresas` VALUES (28, '20492314154', 'INVERSIONES EBENEZER S.R.L', 0, 65, 30, 'AV. 4 MZA. B4 LOTE. 03 P.J. NESTOR GAMBETTA BAJA ESTE (COSTADO DEL MERCADO ROJO GAMBETTA)', 'CALLAO', 1, 0, '', '', '', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (29, '20521431955', 'ECO TRADING S.A.C', 1, 36, 31, 'REPUBLICA ARGENTINA NRO. 798 URB. ZONA INDUSTRIAL (ALT. DE C.C. LA CACHINA)', 'LIMA', 1, 0, ' Jose Borda', ' Administrador', '971150078', ' ecogas.ar@hotmail.com', 1, 1, 2, 1, 5);
INSERT INTO `empresas` VALUES (30, '20478967111', 'ESTACION DE SERVICIO LURIN', 0, 119, 32, 'MZ. C. LT 2 URB. LOS HUERTOS DE VILENA', 'LURIN', 1, 0, 'RAUL SOLIER REYNOSO', '', '998322921', 'eesslurinsac@gmail.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (31, '20343883936', 'ESTACION DE SERVICIO NIAGARA S.R.L', 0, 35, 33, 'JR. ELVIRA GARCIA Y GARCIA NRO. 2790 (ALT.COLONIAL Y UNIVERSITARIA)', 'LIMA', 1, 0, ' Ricardo Yep', ' Gerente', ' 994219434', ' ricardoyep@hotmail.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (32, '10043210675', 'JAPA BORONDA MARIO RODOLFO', 0, 37, 34, 'CAR.MARGINAL NRO. SN C.P. MENOR HUANCABAMBA', 'OXAPAMPA', 1, 0, 'RULO', '', '941165440', '', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (33, '20512220836', 'FARMA SAN AGUSTIN S.R.L.', 0, 94, 35, 'CAL.JOSE TORRES PAZ NRO. 110 URB. CIUDAD DE DIOS ZONA A (FRENTE A LA POSTA CIUDAD DE DIOS)', 'SAN JUAN DE MIRAFLORES', 1, 0, 'Augusto Castillo', 'Gerente General', '981578374', 'castilloaugusto18@gmail.com', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (34, '20448556663', 'FARMACIA SAN FRANCISCO S.A.C.', 0, 137, 36, 'JR. HUANCANE 721 - PUNO - SAN ROMAN - JULIACA', 'JULIACA', 1, 0, 'Margarita Sucari', 'Gerente General', '962656246', 'msucari41@hotmail.com', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (35, '20600534883', 'CORPORACION GANAJUR S.A.C.', 0, 72, 37, 'JR. ANCASH 14142 BARRIOS ALTOS', 'LIMA', 1, 0, 'Juan Carlos Ortiz', '', '945363393', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (36, '20601790484', 'INVERSIONES LUMIPHARMA E.I.R.L.', 0, 71, 37, 'JR. ANTONIO MIROQUESADA NRO. 806 INT. 403 (ESQ.JR PARURO909 EDF COM MRQS 4PISO 403A)', 'LIMA', 1, 0, '', '', '', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (37, '20425788192', 'CENTRO GAS DIEGO EIRL', 0, 60, 39, 'AV. LA MOLINA NRO. 401 URB. VULCANO (LETRERO MGAS - FRENTE GRIFO MOBIL)', 'ATE', 1, 0, 'GENOVEVA ESPIRITU', '', '989003565', 'iquitos7@hotmail.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (38, '20502716761', 'GAS ESCORPIO S.R.L.', 0, 102, 39, 'AV. LA MOLINA NRO. 401 URB. VULCANO (ALT. OVALO SANTA ANITA) LIMA - LIMA - ATE', 'ATE', 1, 0, 'GENOVEVA ESPIRITU', '', '989003565', 'iquitos7@hotmail.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (39, '20492920666', 'GASBEL EQUIPOS & ASESORIA S.A.C.', 1, 93, 41, 'JR. HUARAZ NRO. 1134 URB. BREÑA', 'BREÑA', 1, 0, 'MILAGROS ALMANDROS', '', '996890808', 'malmandros@jevaro.com.pe', 1, 1, 2, 0, 9);
INSERT INTO `empresas` VALUES (40, '20520786873', 'GASOCENTRO LIMA SUR S.A.C.', 1, 22, 42, 'AV. JUAN DE ALIAGA NRO. 278 INT. 503 (EX JOSE COSSIO)', 'MAGDALENA DEL MAR', 1, 0, ' Alex Naters', ' Gerente', ' 987937575', ' naters.alex@gmail.com', 1, 1, 2, 0, 9);
INSERT INTO `empresas` VALUES (41, '20546818102', 'GRUPO AVTEC CONTENIDOS SAC', 0, 27, 43, 'AV. DEL PINAR NRO. 110 DPTO. 204 URB. CHACARILLA DEL ESTANQUE', 'SANTIAGO DE SURCO', 1, 0, 'FERNANDO BAZAN', 'GERENTE OPERACIONES', '989054789', 'fernando.bazan@gasored.pe', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (42, '20600658311', 'ESTACION TRAPICHE S.A.C.', 0, 5, 43, 'AV. DEL PINAR NRO. 110 DPTO. 204 URB. CHACARILLA DEL ESTANQUE', 'SANTIAGO DE SURCO', 1, 0, 'FERNANDO BAZAN', 'GERENTE OPERACIONES', '989054789', 'fernando.bazan@gasored.pe', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (43, '20100111838', 'GRIFOS ESPINOZA S A', 0, 104, 45, 'AV. JAVIER PRADO ESTE NRO. 6519 URB. PABLO CANEPA (ENTRE CRUCE AV. INGENIEROS, GESA MARKETS)', 'LA MOLINA', 1, 1, ' Francisco ponte', ' Gerente Com.', ' 998115473', 'fponte@grupogesa.com ', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (44, '20138645607', 'GRIFO SANTO DOMINGO DE GUZMAN SRLTDA', 0, 140, 46, 'AV. RAMIRO PRIALE LOTE. 23A ASC. DIGNIDAD NACIONAL (PARCELA L)', 'SAN JUAN DE LURIGANCHO', 1, 1, 'SUSAN', 'ADMINISTRADORA', '976363362', 'adm.santodomingo.srl@gmail.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (45, '', '', 0, 30, 47, 'AV. HEROES DEL ALTO CENEPA NRO. 697', 'COMAS', 1, 1, 'Felix Huaman', 'Gerente General', '993494331', 'grifotrapiche@yahoo.es', 1, 1, 2, 0, NULL);
INSERT INTO `empresas` VALUES (46, '20514304921', 'GRUPO INTIFARMA S.A.C.', 0, 96, 48, 'AV. INSURGENTES NRO. 711 INT. 4 SAN MIGUEL (MINISTERIO MARINA)', 'LA PERLA', 1, 0, '', '', '', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (47, '20602712363', 'CONSORCIO GAS DEL SUR', 0, 20, 49, 'CAL. PANAMERICANA SUR KM. 33.5 URB. PREDIO LAS SALINAS', 'LURIN', 1, 0, ' Luis Berrios Tosi', 'Luis Berrios', '', 'contabilidad3@copepdelperu.com', 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (48, '20538807037', 'HEVALFAR SRL', 0, 91, 51, 'MZA. D-1 LOTE 26 2DO. PISO INT. 202 URB. LAS PRADERAS DE STA. ANITA', 'EL AGUSTINO', 1, 0, '', '', '', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (49, '20521111888', 'INVERSIONES Y REPRESENTACIONES EGAR S.A.C.', 0, 89, 52, 'AV. ALFREDO MENDIOLA NRO. 3973 INT. 201 URB. MICAELA BASTIDAS - Lima - Lima - Los Olivos', 'LOS OLIVOS', 1, 0, 'ORLANDO QUISPE', 'DUEÑO', '920659824', '', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (50, '20493089570', 'ESTACION DE SERVICIOS H & A S.A.C.', 0, 48, 53, 'AV. UNIVERSITARIA NRO. S/N (CDRA 51 ESQUINA CON LA CALLE A)', 'LOS OLIVOS', 1, 1, 'FELICIANO AZAÑERO', 'GERENTE GENERAL', '963760146', 'estacion.hasac@gmail.com>', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (51, '20556597376', 'ESTACION DE SERVICIOS ANDAHUASI S.A.C.', 0, 45, 53, 'AV. UNIVERSITARIA MZA. A LOTE. 06 (CDRA. 51 FRENTE PARQUE NARANJAL)', 'LOS OLIVOS', 1, 0, ' Maribel Bernabé', ' Administración', '963760146 ', 'estacion.hasac@gmail.com ', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (52, '20551615856', 'INVERSIONES JIARA S.A.C.', 0, 138, 55, 'AV. ESTEBAN CAMPODONICO NRO. 262 URB. SANTA CATALINA', 'LA VICTORIA', 1, 1, 'CARMEN ARAMAYO', 'GERENTE GENERAL', '959373867', 'alkohler_eirl@hotmail.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (53, '20371975561', 'REPRESENTACIONES JEMMS S.A.C.', 0, 136, 56, 'AV. ALFREDO MENDIOLA NRO. 1085 URB. PALAO 2DA ETAPA', 'SAN MARTIN DE PORRES', 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (54, '20492197417', 'JE OPERADORES SAC', 0, 86, 56, 'Av. Nestor Gambeta Km. 7.10 Mz.B-6 Lt.4 Coop. Vivienda de Trab. ENAPU  - Callao - Callao - Callao ', 'CALLAO', 1, 1, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (55, '20512853529', 'ATS AMERICA SAC', 0, 87, 56, 'AV. LIMA SUR NRO. 895 CHOSICA LIMA - LIMA - LURIGANCHO', 'LURIGANCHO', 1, 1, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (56, '20535614548', 'OPERADORES DE ESTACIONES SAC', 0, 85, 56, 'AV. CIRCUNVALACION NRO. 1386 (ALT MERCADO DE FRUTAS) LIMA - LIMA - LA VICTORIA', 'LA VICTORIA', 1, 1, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (57, '20551112781', 'GRUVENI SRL', 0, 150, 56, 'JR. LOS ANTROPOLOGOS MZA. D LOTE. 4 COO. LA UNION (MODULO DE PODER JUDICIAL DE PROCERES)', 'SAN JUAN DE LURIGANCHO', 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (58, '20551297978', 'GRANDINO S.A.C.', 0, 135, 56, 'CAL.LOS CEREZOS NRO. 291 URB. DE LA LOTIZ. CHILLON', 'PUENTE PIEDRA', 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (59, '20566149151', 'GASNOR S.A.C.', 0, 76, 56, 'AV. ENCALADA 232 - SANTIAGO DE SURCO', 'SANTIAGO DE SURCO', 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (60, '20566149401', 'ESTACIONES DEL NORTE SAC', 0, 70, 56, 'CAR.PANAN NORTE KM. 1168 P.J. BARRIO LETICIA', 'MANCORA', 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (61, '20600868862', 'PETRO CALLAO SAC', 0, 88, 56, 'AV. ARGENTINA NRO. 498 URB. CHACARITAS - Callao - Callao - Callao', 'CALLAO', 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (62, '20600908627', '524 CONSULTING S.A.C.', 0, 134, 56, 'AV. LA ENCALADA NRO. 232 URB. CENTRO COMERCIAL MONTERRICO', 'SANTIAGO DE SURCO', 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (63, '20602003427', 'PETRO NAZCA S.A.C.', 0, 101, 56, 'AV. PANAMERICANA NRO. 891 URB. VISTA ALEGRE (GRIFO PECSA)', 'NAZCA', 1, 1, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (64, '20604631379', 'MOVI PETROL S.A.C.', 0, 131, 56, 'LA ENCALADA NRO. 232', 'SANTIAGO DE SURCO', 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (65, '20404000447', 'SERVICENTRO ESPINOZA NORTE S.A', 0, 149, 68, 'CAR.PAN. NORTE NRO. K191 (LA PALMA PTO. SUPE)', 'SUPE PUERTO', 1, 0, 'Manuel Menacho', 'Administrador', '992018928', 'manuluismd1999@gmail.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (66, '20605427147', 'INVERSIONES KATIMILA E.I.R.L.', 0, 147, 68, 'AV. CENTENARIO KM. 4.100 KM. REF (AV. CENTENARIO KM. 4.100)', 'CALLERIA', 1, 0, 'Manuel Menacho', 'Administrador', '992018928', 'manuluismd1999@gmail.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (67, '20283756115', 'SERVICENTRO UCAYALI S.A.C', 0, 17, 70, 'AV. CENTENARIO NRO.4100', 'YARINACOCHA', 1, 1, 'Fiorella Elias', 'Contadora', '970428573', 'f.elias@grespinoza.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (68, '20404883918', 'GRIFOS ESPINOZA DE TINGO MARIA S.A.', 0, 24, 70, 'AV ENRIQUE PIMENTEL NRO 116', 'RUPA-RUPA', 1, 1, 'Fiorella Elias', 'Contadora', '970428573', 'f.elias@grespinoza.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (69, '20508196475', 'PETROCENTRO YULIA S.A.C.', 0, 49, 70, 'AV. DE LA MARINA NRO. 2789 URB. MARANGA 1RA ET. (CRUCE CON AV.ESCARDO)', 'SAN MIGUEL', 1, 0, 'Fiorella Elias', 'Contadora', '970428573', 'f.elias@grespinoza.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (70, '20605129154', 'GRIFOS GES S.A.C.', 0, 133, 73, 'AV. ISABEL LA CATOLICA NRO. S/N URB. MATUTE (ESQUINA CON JR ANDAHUAYLAS)', 'LA VICTORIA', 1, 0, 'Anyeli Macalupu', 'Contadora', '939375251', 'amacalupu@grifosges.pe', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (71, '20605450475', 'SERVICENTRO JAQUELINE DE PUCALLPA S.A.C.', 0, 145, 73, 'AV. CARRETERA FEDERICO BASADRE KM. 9.00 (LATERAL DERECHO)', 'YARINACOCHA', 1, 0, 'Anyeli Macalupu', 'Contadora', '939375251', 'amacalupu@grifosges.pe', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (72, '20203530073', 'SERVICENTRO SAN HILARION S.A.', 0, 69, 75, 'AV. FLORES DE PRIMAVERA NRO. 1988 URB. SAN HILARION (MZ B - LT.03 / CRUCE CON AV.CTO.GRANDE)', 'SAN JUAN DE LURIGANCHO', 1, 0, 'Tomas Zavala', 'Gerente General', '998196242', 'huancayo18@hotmail.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (73, '20506467854', 'CORPORACION JULCAN S.A.', 0, 13, 75, 'AV. PROCERES DE LA INDEPENDEN NRO. 2556 URB. LOS ANGELES (ALTURA DEL PARADERO 20)', 'SAN JUAN DE LURIGANCHO', 1, 1, 'TOMAS ZAVALA', '', '998196242', 'huancayo18@hotmail.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (74, '20347869849', 'SERVIC. Y AFINES LAS AMERICAS EIRL', 1, 40, 77, 'AV. DE LAS AMERICAS NRO. 1259 URB. BALCONCILLO (GRIFO PETROPERU)', 'LA VICTORIA', 1, 1, ' Luisa Manco', ' Administracion', ' 987817538', ' servicentroyafineslasamericas@hotmail.com', 1, 1, 2, 1, 8);
INSERT INTO `empresas` VALUES (75, '20459020137', 'MARKET LAS BELENES S.A.C', 0, 77, 1, 'JR. EL POLO 493 - URB EL DERBY DE MONTERRICO', 'SANTIAGO DE SURCO', 1, 0, 'Paty Medina', 'Gerente General', '966833946', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (76, '20101312519', 'LIMABANDA S.A.C.', 0, 59, 79, 'AV. MARISCAL ORBEGOSO NRO. 120 URB. EL PINO', 'SAN LUIS', 1, 1, 'LUIS NUÑEZ', '', '989005187', 'lnudarco@limabandasac.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (77, '20124367850', 'INVERSIONES TRANSP. Y SERV. CINCO S.A.C.', 0, 23, 79, 'AV. JAVIER PRADO ESTE NRO. 1059 URB. SANTA CATALINA (FRENTE AL COLG.SAN AGUSTIN)', 'LA VICTORIA', 1, 0, 'LUIS NUÑEZ', 'Gerente General', '989005187', 'lnudarco@limabandasac.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (78, '20492727661', 'LIVORNO OIL TRADING S.A.C.', 0, 103, 81, 'JR. ABTAO NRO. 784 (ESQ. HIPOLITO UNANUE)', 'LA VICTORIA', 1, 0, 'MAGNOLIA', '', '946594870', 'administracion.control@merrillperu.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (79, '20137926742', 'SERVICENTRO LOS ROSALES S.A.', 0, 124, 82, 'AV. AYACUCHO NRO 140', 'SANTIAGO DE SURCO', 1, 0, 'Maricell Guillen', 'Administradora', '998450561', 'mguillen@servicentrolosrosales.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (80, '20110623420', 'INVERSIONES LUMARCO SA', 1, 122, 1, 'CAR.CENTRAL KM. 11.2 A.H. LA ESTRELLA (GRIFO PECSA COSTADO PLAZA REAL STA CLARA)', 'ATE', 1, 1, 'Luciano Marching', 'Gerente General', '967778888', '', 1, 1, 2, 2, 10);
INSERT INTO `empresas` VALUES (81, '20517735605', 'LUXOR PHARMACEUTICAL SAC', 0, 90, 84, 'AV. CESAR VALLEJO 895.', 'VILLA EL SALVADOR', 1, 0, '', '', '', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (82, '20524359601', 'MABA FARMA S.A.C.', 0, 11, 85, 'MZA. C LOTE. 4 JAZMIN DE OQUENDO (COSTADO MERCADO LA ALBORADA)', 'CALLAO', 1, 0, 'Miguel Balvin', 'Administrador', '975547119', 'mabafarmasac-2013@hotmail.com', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (83, '20161800920', 'LUBRIGAS S.R.LTDA.', 0, 80, 86, 'Av. Nicolas Ayllon Nro. 3562 Fnd. Mayorazgo (Frente a Planta Qui lomica Suiza)', 'ATE', 1, 1, 'Alejandro Mandujano', '', '980090788', 'amandujano@grupomandujano.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (84, '20516035758', 'GASNORTE S.A.C', 0, 79, 86, 'Av. Gerardo Unger Nro. 3301 - Urbanización: Habilit.Indust.Pan.Norte', 'INDEPENDENCIA', 1, 0, 'Alejandro Mandujano', '', '980090788', 'amandujano@grupomandujano.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (85, '20524249848', 'CENTROGAS VISTA ALEGRE S.A.C.', 0, 83, 86, 'Av. Nicolas Ayllon Nro. 4706 Fnd. Vista Alegre', 'ATE', 1, 0, 'Alejandro Mandujano', '', '980090788', 'amandujano@grupomandujano.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (86, '20547011954', 'CENTROGAS IQUITOS S.A.C.', 0, 67, 86, 'AV. IQUITOS NRO. 983 (CRUCE CON AV CATOLICA)', 'LA VICTORIA', 1, 0, 'Alejandro Mandujano', '', '980090788', 'amandujano@grupomandujano.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (87, '20557618920', 'CENTRAL PARIACHI S.A.C.', 0, 57, 86, 'AV. NICOLAS AYLLON NRO. S/N SEMI RUSTICO PARIACHI PARCELA 10906', 'ATE', 1, 0, 'Alejandro Mandujano', '', '980090788', 'amandujano@grupomandujano.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (88, '20517053351', 'MASGAS PERU S.A.C.', 1, 0, 92, 'Av. Tupac Amaru Nro. 3685 - Urb. Carabayllo ', 'CARABAYLLO', 0, 0, 'Victor Naranjo', 'Gerente General', '994271052', 'administracionperu@masgasperu.com', 1, 1, 2, 0, 5);
INSERT INTO `empresas` VALUES (89, '20371826727', 'ESTACION DE SERVICIOS GRIFO MASTER SRL', 1, 0, 93, 'Av. Alfredo Mendiola Mza. E Lote. 16 - Asoc. Rio Santa', 'LOS OLIVOS', 0, 0, '', '', '', '', 1, 1, 2, 2, 10);
INSERT INTO `empresas` VALUES (90, '20603673485', 'MATIAS & ALEXA E.I.R.L.', 0, 75, 94, 'JR. PARURO NRO. 926 INT. 345B GALERIA CENTRO COMERCIAL CAPON CENTER', 'LIMA', 1, 0, 'Jerson', 'Administrador', '983465591', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (91, '20304887762', 'MIDAS GAS S.A', 0, 44, 95, 'AV. NICOLAS ARRIOLA NRO. 3191 .', 'SAN LUIS', 1, 1, 'Walter Meza', 'Administrador', '987518298', 'walter.meza@midasgas.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (92, '10090647879', 'SALCEDO GUEVARA NESTOR', 0, 127, 96, 'CAR. CENTRAL NRO 16.5 URB. HUAYCAN', 'LIMA', 1, 1, ' Alexandra', ' Administradora', ' 920660239', ' Inandisa01@gmail.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (93, '20566091306', 'NG FARMA S.A.C.', 0, 132, 97, 'AV. SANTA ROSA 1044 APV. LOS CHASQUIS', 'SAN MARTIN DE PORRES', 1, 0, '', '', '', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (94, '20177941591', 'ORGANIZACION FUTURO SAC', 1, 121, 98, 'AV. JAVIER PRADO ESTE 6651', 'LA MOLINA', 1, 1, 'Gunther Paucar', 'Gerente General', '977810907', 'gpaucar@orfusac.com', 1, 1, 2, 0, 6);
INSERT INTO `empresas` VALUES (95, '20517231631', 'PANAMERICAN GAS TRADING S.A.C.', 0, 0, 99, 'Av. Republica De Panama Nro. 4120 ', 'SURQUILLO', 0, 0, '', '', '', '', 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (96, '20452799368', 'ESTACION FINLANDIA E.I.R.L.', 0, 109, 100, 'AV. SIETE MZA. 9 LOTE. 02-A (ESQUINA DE AV. SIETE Y FINLANDIA)', 'LA TINGUIÑA', 1, 1, 'Miriam Ocaña', 'Administradora', '956406088', '', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (97, '20494793521', 'ESTACION EL OVALO E.I.R.L.', 1, 114, 100, 'AV. F. LEON DE VIVEIRO', 'ICA', 1, 1, 'Nola Cordova', 'Administradora', '924770964', '', 1, 1, 2, 2, 10);
INSERT INTO `empresas` VALUES (98, '20601709148', 'PETRO LUMARA S.A.C.', 1, 106, 102, 'Ca. Montegrande Nro. 109 Int. 301 - Urb. Chacarilla Del Estanque', 'SANTIAGO DE SURCO', 1, 0, ' Fernando Camacho', ' Administrador', ' 975802575', '  fcamacho@cocsaperusa.com', 1, 1, 4, 0, 4);
INSERT INTO `empresas` VALUES (99, '20511193045', 'ESTACION DE SERVICIOS MONTE EVEREST SAC', 0, 125, 103, 'AV. AVIACION NRO. 4285 (ALT.CDRA 42 AV.AVIACION)', 'SURQUILLO', 1, 1, 'x', '', '', '', 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (100, '20514636843', 'ESTACIONES DE SERVICIOS PETRO WORLD SAC', 0, 126, 103, 'AV. VENEZUELA ESQUINA CON AV. RIVA AGUERO', 'SAN MIGUEL', 1, 1, 'x', '', '', '', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (101, '20505133430', 'PETROCARGO S.A.C', 1, 68, 105, 'AV. ELMER FAUCCETT NRO. 6000', 'CALLAO', 1, 0, 'WILBER LEON', 'CONTADOR', '994271076', 'wilbertleon@petrocorpsa.com', 1, 1, 2, 0, 10);
INSERT INTO `empresas` VALUES (102, '10009635128', 'TEODORA DOMINGUEZ', 0, 33, 106, 'AV. NICOLAS DE AYLLON N 441 CHACLACAYO - LIMA - LIMA', 'CHACLACAYO', 1, 0, 'Teodora', 'Gerente General', '960919061', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (103, '20127765279', 'COESTI S.A.', 1, 66, 107, 'Av. Circunvalación del Club de Golf Los Incas N° 134 Urb. Club de Golf Los Incas - Lima - Lima - Santiago de Surco', 'SANTIAGO DE SURCO', 1, 1, 'Claudio Aramburu', 'Jefe de Proyectos', '947640511', 'CAramburuL@primax.com.pe', 1, 1, 2, 2, 1);
INSERT INTO `empresas` VALUES (104, '20330033313', 'PERUANA DE ESTACIONES SERVICIOS SAC', 0, 117, 107, 'Av. Circunvalación del Club de Golf Los Incas N° 134 Urb. Club de Golf Los Incas - Lima - Lima - Santiago de Surco', 'SANTIAGO DE SURCO', 1, 1, 'Claudio Aramburu', '', '', '', 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (105, '20603822359', 'DROGUERIA DISTRIBUIDORA PRIMED S.A.C.', 0, 105, 109, 'Av. Gral. Miguel Iglesias Mz.g Lt.30 - AA.HH. Javier Heraud', 'SAN JUAN DE MIRAFLORES', 1, 0, '', '', '', '', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (106, '20603012268', 'INVERSIONES RAMSAN E.I.R.L.', 0, 16, 110, 'Jr. Pedro Garenzon Nº 500, Urb. Miguel Grau', 'ANCON', 1, 0, 'Flor Roxana Sanchez', '', '', 'consor.norteno17@gmail.com / anconero.201802@gmail.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (107, '20503840121', 'REPSOL COMERCIAL SAC', 1, 0, 111, 'Av. Victor Andres Belaunde Nro. 147 ', 'SAN ISIDRO', 0, 0, 'Juan Carlos Evangelista', 'Jefe de Tecnologia', '996412738', 'JEVANGELISTAR@repsol.com', 1, 1, 2, 0, 10);
INSERT INTO `empresas` VALUES (108, '20325753821', 'RED INTERNACIONAL DE COMBUSTIBLE Y SERVICIO AUTOMOTRIZ S.R.L.', 0, 51, 112, 'AV. NICOLAS ARRIOLA NRO. 1003 URB. LA POLVORA', 'LA VICTORIA', 1, 0, ' Catherine Solano', 'Administracion ', ' 992298539', '  jefeope@ricsa.com.pe', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (109, '20486255171', 'CORPORACION RIO BRANCO S A', 1, 143, 113, 'CAR.PANAMERICANA NORTE KM. 92.5 C.P. CHANCAYLLO (BARRIO SAN JUAN PASANDO EL PUENTE)', 'CHANCAY', 1, 1, 'Ralpy Hinostroza', 'Administrador', '964004784', 'riobrancoralpy@hotmail.com', 1, 1, 2, 0, 10);
INSERT INTO `empresas` VALUES (110, '10091479791', 'CARLOS ALFREDO IBAÑEZ MANCHEGO', 0, 118, 114, 'AV. DE LOS HEROES 1187-1189', 'SAN JUAN DE MIRAFLORES', 1, 0, 'Sandra', '', '948639772', '', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (111, '20493091396', 'GASOCENTRO PUENTE NUEVO S.A.C.', 0, 112, 114, 'MZA. G LOTE. 1 ASOCIACION DE VIVIENDA ANCIETA', 'EL AGUSTINO', 1, 0, 'Gina Mispireta', 'Gerente General', '989265054', '', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (112, '20502825624', 'ESTACION DE SERVICIOS SAN JUANITO S.A.C.', 0, 116, 114, 'AV. HEROES NRO. 1109 (ALT.HOSPITAL MARIA AUXILIADORA)', 'SAN JUAN DE MIRAFLORES', 1, 0, 'Nelly', 'Administradora', '989265061', '', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (113, '20511053031', 'ESTACION DE SERVICIO GIO SAC', 0, 123, 114, 'AV. PACHACUTEC NRO. 3859 P.J. CESAR VALLEJO', 'VILLA MARIA DEL TRIUNFO', 1, 0, 'Jose Ibañez', 'Gerente General', '989119054', '', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (114, '20402786729', 'INVERSIONES SANTA ROSA E.I.R.L', 0, 12, 118, 'JR. MOQUEGUA NRO. 398 INT. 7 P.J. FLORIDA BAJA', 'CHIMBOTE', 1, 1, 'Felipe Chu', 'Gerente General', '981444073', 'grifosantarosa1998@hotmail.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (115, '20373831124', 'ESTACION DE SERVICIOS SCHOII S.R.L.', 0, 29, 119, 'AV. MARIANO CORNEJO NRO .1508(POR LA PLAZA DE LA BANDERA)', 'LIMA', 1, 0, '', '', '', '', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (116, '20518960688', 'PITS GNV SAC', 0, 34, 119, 'AV. NICOLAS DE PIEROLA NRO. 800 (MZ.H1 LT.16, ESQUINA CON AV. VILLA MARIA)', 'VILLA MARIA DEL TRIUNFO', 1, 0, 'SUSY LAO', '', '994077048', 'pitsgnvsac@yahoo.es', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (117, '20602772935', 'DISTRIBUCIONES SELMAC S.A.C.', 0, 98, 121, 'JR. PARURO NRO. 926 INT. 212 URB. BARRIOS ALTOS', 'LIMA', 1, 0, 'Sabi', 'Administrador', '955106629', 'contabilidadselmacsac@gmail.com', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (118, '20210975862', 'OPERACIONES Y SERVICIOS GENERALES S A', 0, 38, 122, 'AV. CAMINOS DEL INCA MZA. N LOTE. 19 URB. SAN JUAN BAUTISTA DE V. (URB.SAN JUAN BAUTISTA DE VILLA)', 'CHORRILLOS', 1, 1, 'Eliana Rafael ', ' Administracion', ' 946433001', 'asistenteosg@operadoresmr.com.pe ', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (119, '20334129595', 'GRIFO SERVITOR S.A', 0, 0, 123, 'Av. Alfredo Mendiola - Urb. Industrial La Milla ', 'SMP', 0, 0, 'Christian Higa', 'Gerente General', '998228493', '', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (120, '20517117421', 'SHICHI - FUKU CORPORATION S.A.C.', 0, 56, 124, 'AV. CANADA NRO. 298 URB. SANTA CATALINA', 'LA VICTORIA', 1, 0, ' Jose Nakada', ' Administracion', '', ' estacioncanadagnv@hotmail.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (121, '20517700640', 'SIROCO HOLDINGS S.A.C', 0, 0, 125, 'Av.Elmer Faucett #735 Callao', 'Cercado Callao', 0, 0, 'RICARDO HIDALGO', '', '994219434', 'ricardo.hidalgo@hesperservices.com', 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (122, '20377674686', 'SERVICENTRO SMILE S.A.', 1, 110, 126, 'CAL.LOS ORFEBREROS NRO. 129 URB. IND EL ARTESANO', 'ATE', 1, 1, 'ELVIS MARLON', 'ADMINISTRADOR', '999086928', 'e.napravnick@servicentrosmile.com', 1, 1, 4, 2, 3);
INSERT INTO `empresas` VALUES (123, '20534525070', 'COMERCIALIZADORA DE COMBUSTIBLES TRIVEÑO S.A.C.', 0, 113, 127, 'AV. MATIAS MANZANILLA-2DO PIS NRO. 625 INT. 04 (FRENTE AL HOSPITAL DEL SEGURO SOCIAL)', 'ICA', 1, 0, 'ROMULO TRIVEÑO', '', '956725267', 'trivenog@comtrisac.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (124, '20298736820', 'INVERSIONES UCHIYAMA SRL', 0, 2, 128, 'AV. LA MAR NRO. 2382', 'SAN MIGUEL', 1, 0, 'Lizbeth Castro', 'Contadora', '975363363', 'lizbeth@estacionlamar.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (125, '20514326496', 'CORPORACION UNO S.A.', 0, 107, 129, 'AV. VICTOR ANDRÉS BELAUNDE NRO. 214 INT. 303 (ESQUINA CON CALLE LOS PINOS)', 'SAN ISIDRO', 1, 1, 'LEANDRA BENDITA', '', '985054954', 'leandra.bendita@senergyc.com', 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (126, '20565949731', 'CONSORCIO VITAFARMA S.A.C.', 0, 129, 130, 'JR. PARURO NRO. 775 INT. 307 URB. BARRIOS ALTOS', 'LIMA', 1, 0, '', '', '', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (150, '20020020000', 'DEMO GS', 0, 15, 1, 'Condevilla', 'SAN MARTIN DE PORRES', 1, 0, '', NULL, '', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (151, '12345678901', 'DEMO', 0, 19, 1, 'AV. LT PLAZA NORTE', 'SAN MARTIN DE PORRES', 1, 0, '', NULL, '', 'demo@demo.com', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (152, '20101127614', 'MANUEL IGREDA Y JULIO RIOS S.R.L', 0, 47, 91, 'CAL.MONTERREY NRO. 341 INT. 502', 'SANTIAGO DE SURCO', 1, 0, '', NULL, '', '', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (153, '20519069262', 'RICARDO CALDERON INGENIEROS SAC', 0, 52, 138, 'AV. AUGUSTO B LEGUIA NRO. 307 COO. POLICIAL (ACONT DE AV. PERU -ANTES DE ZARUMILLA)', 'SAN MARTIN DE PORRES', 1, 0, '', NULL, '9999999', 'ventas@rcingenieros.com', 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (154, '20348303636', 'ESTACION DE SERVICIOS HERCO S.A.C.', 1, 55, 49, 'CAR.PANAMERICANA SUR NUEVA KM. 33.5 MZA. C LOTE. 14 SECTOR LAS SALINAS (GRIFO HERCO) ', 'LURIN', 1, 1, '', NULL, '', 'herco@ventas.com', 1, 1, 2, 0, 8);
INSERT INTO `empresas` VALUES (155, '20601351944', 'NEGOCIACIONES VALERIA & CHRIS S.A.C', 0, 73, 1, 'AV. LIMA SUR NRO. S/N CHOSICA (LT A2-1 A1-9) ', 'SAN JUAN DE LURIGANCHO', 1, 0, '', NULL, '', 'ventas@valeria.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (156, '20392479687', 'INVERSIONES CORONACION S.R.L.', 0, 95, 1, 'MZ E LT 10 SECTOR CENTRAL HUERTOS DE MANCHAY.', 'PACHACAMAC', 1, 0, 'Samuel.', NULL, '942416053 - 6624797', ' ', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (157, '20515789961', 'GAMA INVERSIONES GENERALES S.A.C.', 0, 111, 1, 'AV. QUILCA CUADRA 11 S/N MZA. E LOTE. 29 URB. AEROPUERTO PROV. CONST. DEL CALLAO', 'CALLAO', 1, 0, ' ', NULL, '  ', ' ', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (158, '20602359981', 'PUNTO GAS S.A.C.', 0, 139, 1, 'AV. MARISCAL OSCAR T. BENAVIDES NRO. 1657 URB. LA TRINIDAD (ALTURA CDRA. 16 EX COLONIAL) ', 'LIMA', 1, 0, '', NULL, ' ', ' ', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (159, '20604271089', 'ESTACION DE SERVICIOS VICTORIA L & K HNAS. S.A.C.', 0, 151, 34, 'JR. LAS ACACIAS NRO. SN (1 CDRA DEL GRIFO HUANCABAMBA)', 'HUANCABAMBA', 1, 0, ' ', NULL, ' ', '', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (160, '20522168182', 'FARMACIAS INTEGRALES DE LA SOLIDARIDAD S.A.C.', 0, 152, 1, 'AV. ANGAMOS ESTE NRO. 716 (HOSPITAL DE SOLIDARIDAD DE SURQUILLO)', 'SURQUILLO', 1, 0, ' ', NULL, ' ', ' ', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (161, '20600765044', 'EUCEL S.R.L.', 0, 153, 1, 'PJ. SAN MARTIN MZA. L LOTE. 3 CANTO CHICO', 'SAN JUAN DE LURIGANCHO', 1, 0, ' ', NULL, ' ', ' ', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (162, '20605395482', 'DROGUERIA LMG FARMA PERU S.A.C.', 0, 154, 1, 'UPIS SAN JOSE MZA. G-1 LOTE. 4 INT. 2 ', 'LURIN', 1, 0, ' ', NULL, ' (01) 4045448 / 961 297 889', ' ', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (163, '20514721280', 'GRUPO INTI S.A.C', 0, 155, 1, 'Jr. Dante 893 - 899', 'SURQUILLO', 1, 0, ' ', NULL, '447-3684', 'grupointisac@gmail.com', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (164, '20338926830', 'GRIFO VALERIA VICTORIA S.A.C.', 0, 156, 1, 'AV. RIVA AGUERO NRO. 411', 'EL AGUSTINO', 1, 0, ' ', NULL, ' ', ' ', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (165, '20484056227', 'OLEOCENTRO Y SERVICIOS SAN PEDRO EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA', 0, 157, 1, 'MZA. 30 LOTE. 01 A.H. SAN PEDRO', 'PIURA', 1, 0, ' ', NULL, ' ', ' ', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (166, '20546828671', 'DRUGSTORE SOL FARMA CORP. E.I.R.L.', 0, 158, 1, 'CAL.CORACEROS NRO. 158', 'PUEBLO LIBRE', 1, 0, ' ', NULL, '4270669 / 979462433', ' ', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (167, '20605344896', 'DROGUERIA DISTRIBUIDORA E IMPORTADORA VILLALEON E.I.R.L', 0, 159, 9, 'JR. HUANTA NRO. 944 INT. B URB.  BARRIOS ALTOS', 'LIMA', 1, 0, ' ', NULL, ' ', ' ', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (168, '10214719717', 'HILARIO CHAUCA GILMAR MARCIANO', 0, 160, 1, 'AV. AVIACION S/N ', 'SANTIAGO DE SURCO', 1, 0, ' ', NULL, ' ', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (169, '20605224505', 'BOTICA NUEVO PERU E.I.R.L.', 0, 161, 1, 'AV. LOS PINOS NRO. 1414 URB. EL PINAR', 'COMAS', 1, 0, ' ', NULL, ' ', ' ', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (170, '20604193711', 'INVERSIONES P & M FARMA E.I.R.L.', 0, 162, 1, 'JR. MERCURIO NRO. 172 URB. SAN CARLOS II ETP', 'SAN JUAN DE LURIGANCHO', 1, 0, '', NULL, ' ', ' ', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (171, '10702311191', 'ALIAGA PEREZ LEONARDO CARLOS', 0, 163, 1, 'S/N', 'LIMA', 1, 0, '', NULL, ' 932 475 889', 'boticasdelahorro2019@gmail.com', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (172, '20605809686', 'GRIFOS ESSA PUCALLPA S.A.C.', 0, 164, 1, 'AV. FEDERICO BASADRE NRO. 298', 'PADRE ABAD', 1, 0, ' ', NULL, ' ', '', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (173, '20546740154', 'PRECIO S.A.C.', 0, 165, 1, 'AV. LA ENCALADA NRO. 232 URB. CENTRO COMERCIAL MONTERRICO (A 2 CUADRAS DE VIVANDA)', 'SANTIAGO DE SURCO', 1, 0, 'CHRISTIAN GONZALES', NULL, '5342336', 'atencionalcliente@estacion715.com', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (174, '20605764712', 'DROGUERIA JJC S.A.C.', 0, 166, 1, 'CAL.LAS ANTILLAS NRO. 150 URB. ISLA VERDE', 'PUEBLO LIBRE', 1, 0, '', NULL, ' ', ' ', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (175, '20603587481', 'CONSORCIO EFE S.A.C.', 0, 167, 1, 'AV. JOSE SANTOS CHOCANO NRO. 128 P.J. VEINTIDOS DE OCTUBRE ', 'CARMEN DE LA LEGUA', 1, 0, ' ', NULL, ' ', ' ', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (176, '20557398628', 'DISTRIBUIDORA V & G FARMA S.R.L.', 0, 168, 1, 'CAL.LOS PETALOS NRO. 189 URB. LA ACHIRANA', 'SANTA ANITA', 1, 0, '', NULL, ' 987047349 / 986745685', 'distribuidoravgfarma@gmail.com', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (177, '20553772436', 'GASOCENTRO ICA S.A.', 0, 0, 131, 'Av. Mexico # 295', 'LA VICTORIA', 0, 0, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (179, '20100079179', 'ESTACION DE SERV BOLIVAR S A', 0, 0, 131, 'SAN ROQUE - SANDIEGO DE SURCO ', 'LIMA', 0, 0, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (180, '20605955704', 'PETRO LAS LOMAS S.A.C.', 0, 169, 1, 'CAL.LOS PROCERES NRO. S/N PBLO. LAS LOMAS PIURA - PIURA - LAS LOMAS', 'LAS LOMAS', 1, 0, 'CHRISTIAN GONZALES', NULL, '5342336', 'atencionalcliente@estacion715.com', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (181, '20605720847', 'GRIFOS ESSA DE TINGO MARIA S.A.C', 0, 170, 1, 'CAR.TINGO MARIA A HUANUCO NRO. 2.5 CAS. AFILADOR HUANUCO', 'RUPA-RUPA', 1, 0, ' ', NULL, ' ', ' ', 1, 1, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (182, '20565966589', 'CORPORACION VICTORIA PERUANA S.A.C', 0, 171, 1, 'Calle Los Detectives Mz. F2 Lt. L1 Urb. Honor y Lealtad ', 'SANTIAGO DE SURCO', 1, 0, '', NULL, '(056) 283040', 'drogueria-america@outlook.com', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (183, '10463388089', 'CAHUAPAZA APAZA FREDY EDWIN', 0, 172, 1, 'Jr. Bolivar 153', 'JULIACA', 1, 0, '', NULL, '', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (184, '10411640855', 'AGUIRRE ZURITA CARLOS JAVIER', 0, 173, 1, 'JR LEONCIO PRADO 1008', 'MAGDALENA DEL MAR', 1, 0, '', NULL, '', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (185, '10458612256', 'CORZO LAYME MARIA LORENA', 0, 174, 1, 'Mz.188 Lt.10 AA.HH. Huascar', 'SAN JUAN DE LURIGANCHO', 1, 0, '', NULL, '', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (186, '10060507291', 'CORZO OCANA EFRAIN', 0, 175, 1, '', 'LIMA', 1, 0, '', NULL, '', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (187, '20549666362', 'CONSORCIO MEDICORP & SALUD S.A.C.', 0, 176, 1, 'AV. LAS FLORES DE PRIMAVERA NRO. 1045 URB. LAS FLORES', 'SAN JUAN DE LURIGANCHO', 1, 0, '', NULL, '950279527', 'medicorp_i_salud@hotmail.com', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (189, '20606082917', 'INVERSIONES FARMACEUTICA DIAZ S.A.C', 0, 178, 1, '	JR. LAS CALEZAS NRO. 131 (ALTURA DE PLAZA VEA RIMAC)', 'RIMAC', 1, 0, '', NULL, '01-7151990', ' ', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (192, '20601136059', 'FARMA SOLUTIONS E.I.R.L.', 0, 179, 1, 'Calle Loma Los Crisantemos 117', 'SANTIAGO DE SURCO', 1, 0, '', NULL, '960207974', '', 1, 0, NULL, NULL, NULL);
INSERT INTO `empresas` VALUES (200, '20335757697', 'WO SOCIEDAD ANONIMA', 0, NULL, 132, 'AV. GUILLERMO PRESCOTT NRO. 202 URB.  RISSO', 'SAN ISIDRO', NULL, NULL, NULL, NULL, '4419938', NULL, 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (201, '20402173476', 'CARRION INVERSIONES S.A.', 0, NULL, 1, 'JR. ANTONIO LOBATO NRO. 651', 'EL TAMBO', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (202, '20602629750', 'RAINFOREST DC S.A.C.', 0, NULL, 45, 'AV. JAVIER PRADO ESTE NRO. 6519 URB.  PABLO CANEPA  (CRUCE ENTRE INGENIEROS Y JAVIER PRADO OE)', 'LA MOLINA', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (203, '20451706323', 'ESTACION DE SERVICIOS VAMA SAC', 0, NULL, 114, 'AV. DE LOS HEROES NRO. 1187 URB.  SAN JUANITO  (AL FRENTE HOSP. MARIA AUXILIADORA)', 'SAN JUAN DE MIRAFLORES', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (208, '20118180306', 'ESTACION CORMAR S.A.', 1, NULL, 148, 'AV. NICOLAS AYLLON NRO. 3456 URB.  VILLA SANTA ANITA', 'ATE', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 2, 0, 8);
INSERT INTO `empresas` VALUES (209, '20487514749', 'GNV DEL NORTE S.A.C', 0, NULL, 133, 'AV. FELIPE SALAVERRY NRO. 930 URB.  PATAZCA  (CERCA A GRIFO)', 'CHICLAYO', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 2, 0, 10);
INSERT INTO `empresas` VALUES (211, '20457948060', 'XIN XING S.A.', 0, NULL, 1, 'JR. MIRO QUESADA NRO. 1308 LIMA LIMA LIMA', 'LIMA', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (212, '20600465237', 'EXPERIENCIA PERUANA SOCIEDAD ANONIMA CERRADA', 0, NULL, 139, 'Jr. Huaraz Nº 1484  Lima Lima Breña', 'Cercado de Lima', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (213, '20517453618', 'GLMAR SOCIEDAD ANONIMA CERRADA', 0, NULL, 139, 'Av El Triunfo # 210 VMT', 'Villa María del Triunfo', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (214, '20517767396', 'ESCOH SOCIEDAD ANONIMA CERRADA - ESCOH SAC', 0, NULL, 139, 'Av. Fernando Leon de Vivero s/n ICA - ICA  ', 'ICA', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (215, '20101285449', 'TRANSPORTES Y SERVICIOS SANTA CRUZ S A', 0, NULL, 141, 'AV. NARANJAL 299 NRO. C INT. 15 URB. NARANJAL-INDUSTRIAL LIMA - LIMA - INDEPENDENCIA', 'COMAS', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (216, '20515657119', 'ADMINISTRADORA DE SERVICIOS Y ASOCIADOS S.A.C.', 0, NULL, 142, 'AV. JAIME BAUZATE Y MEZA NRO. 1050 LIMA LIMA LA VICTORIA', 'LA VICTORIA', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (217, '20604303029', 'ADMINISTRACION DE GRIFOS L&L ONE S.A.C.', 0, NULL, 144, 'JR. MONTE ROSA NRO. 256 INT. 902 URB. CHACARILLA DEL ESTANQUE LIMA LIMA SANTIAGO DE SURCO', 'SANTIAGO DE SURCO', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (218, '20604302863', 'ADMINISTRACION DE GRIFOS LEP S.A.C.', 0, NULL, 145, 'JR. MONTE ROSA NRO. 256 INT. 902 URB. CHACARILLA DEL ESTANQUE LIMA LIMA SANTIAGO DE SURCO', 'SANTIAGO DE SURCO', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (219, '10704012964', 'POLO GOMEZ BRYAN MARTIN', 0, NULL, 1, 'PRUEBA', 'PRUEBA', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, NULL);
INSERT INTO `empresas` VALUES (220, '20385649194', 'SERVICENTRO TITI S.A.C.', 0, NULL, 146, 'AV. PABLO PATRON NRO. 120 URB. SAN PABLO LIMA LIMA LA VICTORIA', 'LA VICTORIA', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (221, '20601697531', 'CORPORACION HA SOCIEDAD ANONIMA CERRADA ', 0, NULL, 53, 'AV. ALFREDO MENDIOLA NRO 6810 – SMP ', 'SMP', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (222, '20553368902', 'COMBUSTIBLES LIMPIOS PERUANOS SOCIEDAD ANONIMA CERRADA COLPE S.A.C.', 0, NULL, 149, 'CAL. REAL NRO. 588 CERCADO DE EL TAMBO JUNIN HUANCAYO EL TAMBO', 'EL TAMBO', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (223, '11111111111', 'RC DEMO', 1, 0, 1, 'RCI', 'San Martin', 1, 1, 'RC DEMO', '', '55555555', '', 1, 1, 4, 0, 2);
INSERT INTO `empresas` VALUES (224, '20549745076', 'GASOCENTRO SANTA ANA S.A.C', 1, NULL, 37, 'AV. LOS PROCERES MZA. D-2 LOTE. 41 URB. SANTA ANA LIMA LIMA LOS OLIVOS', 'LOS OLIVOS', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 2, 0, 9);
INSERT INTO `empresas` VALUES (225, '20537901277', 'CORPORACION PYX S.A.C. - CORP PYX S.A.C.', 0, NULL, 150, 'AV. ANGELICA GAMARRA NRO. 1361 INT. 00 LIMA LIMA LOS OLIVOS', 'LOS OLIVOS', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (226, '20547799845', 'CONSORCIO GRIFOS DEL PERU SOCIEDAD ANONIMA CERRADA', 0, NULL, 151, 'AV. EL DERBY NRO. 254 DPTO. 704 LIMA LIMA SANTIAGO DE SURCO', 'SANTIAGO DE SURCO', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (227, '20565643496', 'GLOBAL FUEL SOCIEDAD ANONIMA', 1, NULL, 152, 'AV. REPUBLICA DE PANAMA NRO. 3591 INT. 401 URB. LIMATAMBO LIMA LIMA SAN ISIDRO', 'SAN ISIDRO', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 2, 2, 10);
INSERT INTO `empresas` VALUES (228, '2051199502', 'TERPEL PERU S.A.C.', 0, NULL, 153, 'AV. JORGE BASADRE GROHMANN NRO. 347 INT. 1001 (EDIFICIO PATIO CENTRIC)', 'SAN ISIDRO', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (229, '20566238927', 'PERU BUS INTERNACIONAL S.A.', 0, NULL, 154, 'AV. CANTA CALLAO MZA. D LOTE. 11 URB. HUERTOS DEL NARANJAL', 'SAN MARTIN DE PORRES', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 2, 0, 0);
INSERT INTO `empresas` VALUES (261, '20492841014', 'GANAGAS S.A.C.', 0, NULL, 155, 'Av.Los Proceres 655 SANTIAGO DE SURCO LIMA', 'SANTIAGO DE SURCO', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (262, '20507248676', 'VIJOGAS S.A.C.', 0, NULL, 156, 'AV. SANTA ROSA NRO. 610 URB. LOS SAUCES LIMA LIMA ATE', 'ATE', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (263, '20538289656', 'CONSORCIO MICE - JOCEGAS', 0, NULL, 1, 'AV. MARIA REICHE NRO. S/N URB. PACHACAMAC LIMA LIMA VILLA EL SALVADOR', 'VILLA EL SALVADOR', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (264, '20493143612', 'ESTACION DE SERVICIOS MASUR S.A.C.', 0, NULL, 158, 'AV. REPUBLICA DE PANAMA N° 4361', 'Surquillo', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (265, '20510954999', 'GASOCENTRO EL SOL S.A.C.', 0, NULL, 158, 'AV.EL SOL EQ.AV.GUARDIA C NRO. S/N LIMA LIMA CHORRILLOS', 'CHORRILLOS', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (266, '20510957581', 'SERVICENTRO SHALOM SAC', 0, NULL, 159, 'AV. EL DERBY NRO. 254 INT. 704 URB. EL DERBY DE MONTERRICO LIMA LIMA SANTIAGO DE SURCO', 'SANTIAGO DE SURCO', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (289, '20519251656', 'INVERSIONES GASSURCO S.A.C', 0, NULL, 158, 'AV. EL DERBY NRO. 254 INT. 704 URB. EL DERBY DE MONTERRICO LIMA LIMA SANTIAGO DE SURCO', 'SANTIAGO DE SURCO', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (292, '20605899715', 'GO ORUE SAC', 0, NULL, 99, 'Av. El Derby # 254 int 704 Lima - Lima - SANTIAGO DE SURCO', 'SURCO', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (293, '20517710955', 'SERVICIOS MULTIPLES SANTA CECILIA S.A.C.  SERMUSCE S.A.C.', 0, NULL, 158, 'AV. EL DERBY NRO. 254 INT. 704 URB. EL DERBY DE MONTERRICO LIMA LIMA SANTIAGO DE SURCO', 'SANTIAGO DE SURCO', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (295, '20606092998', 'CORPORACION JUDY S.A.C.', 1, NULL, 0, 'Nro. . Ex Fundo Naranjal Parcela', 'SMP', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 2, 2, 10);
INSERT INTO `empresas` VALUES (302, '20593472244', 'ALLIN GROUP - JAVIER PRADO S.A.', 1, NULL, 1, 'JIRON PINOS 308', 'AL MOLINA', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 2, 0, 10);
INSERT INTO `empresas` VALUES (303, '20492898717', 'ECOMOVIL SOCIEDAD ANONIMA CERRADA', 1, NULL, 0, 'AV. PROLONGACION PRIMAVERA NRO. 120 INT. A316 LIMA LIMA SANTIAGO DE SURCO', 'SANTIAGO DE SURCO', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 0, 0);
INSERT INTO `empresas` VALUES (304, '00000000', 'ECOMOVIL ', 1, NULL, 160, 'SMP', 'SMP', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 2, 1, 5);
INSERT INTO `empresas` VALUES (313, '00000000000', 'CORPORACION JUDY SAC', 1, NULL, 161, 'EX FUNDO NARANJAL PARCELA 59', 'SAN MARTIN DE PORRES', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 2, 2, 10);

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for grupos
-- ----------------------------
DROP TABLE IF EXISTS `grupos`;
CREATE TABLE `grupos`  (
  `id_grupo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL DEFAULT NULL,
  `descripcion` mediumtext CHARACTER SET utf8 COLLATE utf8_spanish_ci NULL,
  `fecha` date NULL DEFAULT NULL,
  `estado` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id_grupo`) USING BTREE,
  UNIQUE INDEX `V_REP`(`nombre`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 162 CHARACTER SET = utf8 COLLATE = utf8_spanish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of grupos
-- ----------------------------
INSERT INTO `grupos` VALUES (1, 'SIN GRUPO', 'EMPRESA SOLAS', '2019-10-30', 0);
INSERT INTO `grupos` VALUES (2, 'BOTICAS', 'TODAS LAS BOTICAS', '2019-10-30', 0);
INSERT INTO `grupos` VALUES (3, 'AGUKI', '', '2020-03-02', 1);
INSERT INTO `grupos` VALUES (4, 'AJ GROUP', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (5, 'AJC', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (7, 'ALTA VIDDA', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (8, 'AMEL', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (9, 'AXELL', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (10, 'BALIAN', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (11, 'BOTIFARMA', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (12, 'CABLE PERU', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (14, 'CATAFARMA', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (15, 'CAZTELLANI', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (16, 'CELESTE', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (17, 'CILAMSAC', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (18, 'CLINICA CORAZON DE JESUS', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (19, 'COFARSA', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (20, 'CORGAS', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (21, 'CRUZ VERDE', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (22, 'DELTA', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (24, 'DENVER', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (25, 'DIESEL MAX', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (26, 'DISFARMED', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (27, 'DROFAR', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (28, 'DUOGAS', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (29, 'DUVAL', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (30, 'EBENEZER', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (31, 'ECO TRADING', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (32, 'EDS LURIN', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (33, 'EDS NIAGARA', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (34, 'ESTACION VICTORIA', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (35, 'FARMA SAN AGUSTIN', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (36, 'FARMACIA SAN FRANCISCO', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (37, 'GANAJUR', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (39, 'GAS DIEGO', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (41, 'GASBEL', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (42, 'GASOCENTRO LIMA SUR', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (43, 'GASORED', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (45, 'GESA', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (46, 'GRIFO SANTO DOMINGO', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (47, 'GRIFO TRAPICHE', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (48, 'GRUPO INTIFARMA', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (49, 'HERCO', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (51, 'HEVALFAR', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (52, 'HUARAL GAS', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (53, 'HYA', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (55, 'INVERSIONES JIARA', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (56, 'JEMMS', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (68, 'JU-EDGAR', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (70, 'JU-ELSA', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (73, 'JU-JACQUELINE', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (75, 'JULCAN', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (77, 'LAS AMERICAS', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (78, 'LAS BELENES', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (79, 'LIMABANDA', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (81, 'LIVORNO', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (82, 'LOS ROSALES', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (83, 'LUMARCO', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (84, 'LUXOR', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (85, 'MABAFARMA', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (86, 'MANDUJANO', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (91, 'MANUEL IGREDA', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (92, 'MASGAS', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (93, 'MASTER', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (94, 'MATIAS Y ALEXA', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (95, 'MIDAS', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (96, 'NESTOR SALCEDO', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (97, 'NG FARMA', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (98, 'ORFUSAC', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (99, 'ORUE', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (100, 'OVALO', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (102, 'PETRO LUMARA', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (103, 'PETROAMERICA', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (105, 'PETROCARGO', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (106, 'PIHUICHO', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (107, 'PRIMAX', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (109, 'PRIMED', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (110, 'RAMSAN', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (111, 'REPSOL', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (112, 'RICSA', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (113, 'RIO BRANCO', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (114, 'SAN JUANITO', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (118, 'SANTA ROSA', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (119, 'SCHOII', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (121, 'SELMAC', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (122, 'SERVIGRIFOS', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (123, 'SERVITOR', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (124, 'SHICHI FUKU', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (125, 'SIROCCO', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (126, 'SMILE', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (127, 'TRIVEÑO', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (128, 'UCHIYAMA', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (129, 'UNOGAS', NULL, '2020-03-02', 1);
INSERT INTO `grupos` VALUES (130, 'VITAFARMA', NULL, '2020-03-02', 0);
INSERT INTO `grupos` VALUES (131, 'BOLIVAR S.A.', '', '2020-03-06', 1);
INSERT INTO `grupos` VALUES (132, 'WO SA', '', '2020-10-05', 1);
INSERT INTO `grupos` VALUES (133, 'GASCOP', '', '2021-02-10', 1);
INSERT INTO `grupos` VALUES (136, ' XIN XONG', '', '2021-03-13', 1);
INSERT INTO `grupos` VALUES (138, 'OFICINA', '', '2021-03-16', 1);
INSERT INTO `grupos` VALUES (139, 'COPETROL', '', '2021-04-13', 1);
INSERT INTO `grupos` VALUES (141, 'SANTA CRUZ', '', '2021-04-29', 1);
INSERT INTO `grupos` VALUES (142, 'ASSA', '', '2021-05-27', 1);
INSERT INTO `grupos` VALUES (143, 'INTRASERV', 'ORMEÑO', '2021-07-16', 1);
INSERT INTO `grupos` VALUES (144, 'ADMINISTRACION DE GRIFOS LLONE S.A.C', '', '2021-10-08', 1);
INSERT INTO `grupos` VALUES (145, 'EESS PICORP', 'Administración de grifos lep sac', '2021-11-17', 1);
INSERT INTO `grupos` VALUES (146, 'TITI', '', '2022-02-25', 1);
INSERT INTO `grupos` VALUES (147, 'TRAILER GAS SAC', '', '2022-04-11', 1);
INSERT INTO `grupos` VALUES (148, 'ESTACION CORMAR S.A.', '', '2022-05-13', 1);
INSERT INTO `grupos` VALUES (149, 'COLPE S.A.C', '', '2022-07-18', 1);
INSERT INTO `grupos` VALUES (150, 'CORPORACION PYX', '', '2022-08-01', 1);
INSERT INTO `grupos` VALUES (151, 'CONSORCIOGRIFOS DEL PERU S.A.C', '', '2022-08-10', 1);
INSERT INTO `grupos` VALUES (152, 'AVA SAC', '', '2022-08-23', 1);
INSERT INTO `grupos` VALUES (153, 'Terpel Gazel', '', '2022-10-05', 1);
INSERT INTO `grupos` VALUES (154, 'PERU BUS', '', '2022-11-14', 1);
INSERT INTO `grupos` VALUES (155, 'GANAGAS SAC', '', '2022-11-16', 1);
INSERT INTO `grupos` VALUES (156, 'VijoGas', '', '2022-11-25', 1);
INSERT INTO `grupos` VALUES (157, 'CONSORCIO MICE - JOSEGAS', '', '2022-12-03', 1);
INSERT INTO `grupos` VALUES (158, 'GO', '', '2022-12-13', 1);
INSERT INTO `grupos` VALUES (159, 'SHALOM', '', '2022-12-20', 1);
INSERT INTO `grupos` VALUES (160, 'ECOMOVIL', 'ECOMOVIL', '2024-05-24', 1);
INSERT INTO `grupos` VALUES (161, 'CORPORACION JUDY SAC', '', '2024-12-28', 1);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_reset_tokens_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp(0) NULL DEFAULT NULL,
  `expires_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token`) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type`, `tokenable_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for tb_area
-- ----------------------------
DROP TABLE IF EXISTS `tb_area`;
CREATE TABLE `tb_area`  (
  `id_area` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_area`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_area
-- ----------------------------
INSERT INTO `tb_area` VALUES (1, 'Soporte', 1, '2024-07-27 19:46:46', '2024-07-27 19:46:46');
INSERT INTO `tb_area` VALUES (2, 'Facturacion', 1, '2024-07-27 19:46:50', '2024-07-27 19:46:50');
INSERT INTO `tb_area` VALUES (3, 'Supervisor', 1, '2024-07-27 19:46:52', '2024-07-27 19:46:52');
INSERT INTO `tb_area` VALUES (4, 'Reportes', 1, '2024-07-27 19:46:55', '2024-07-27 19:46:55');

-- ----------------------------
-- Table structure for tb_contac_ordens
-- ----------------------------
DROP TABLE IF EXISTS `tb_contac_ordens`;
CREATE TABLE `tb_contac_ordens`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nro_doc` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nombre_cliente` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `firma_digital` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_contac_ordens
-- ----------------------------
INSERT INTO `tb_contac_ordens` VALUES (3, '61505130', 'JEREMY PATRICK CAUPER SILVANO', 'fdc_61505130.png', 1, '2025-02-12 11:20:17', '2024-08-26 15:18:31');
INSERT INTO `tb_contac_ordens` VALUES (4, '70401296', 'BRYAN MARTIN POLO GOMEZ', 'fdc_.png', 1, '2025-03-02 23:15:46', '2024-08-28 11:23:42');
INSERT INTO `tb_contac_ordens` VALUES (7, '72192063', 'DANIEL EDUARDO MITTA FLORES', 'fdc_72192063.png', 1, NULL, '2025-01-12 22:48:47');
INSERT INTO `tb_contac_ordens` VALUES (8, '10392834', 'MARLON RAUL RAMOS SAJAMI', 'fdc_10392834.png', 1, '2024-12-18 12:54:46', '2024-12-18 12:54:46');

-- ----------------------------
-- Table structure for tb_empresas
-- ----------------------------
DROP TABLE IF EXISTS `tb_empresas`;
CREATE TABLE `tb_empresas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ruc` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `razon_social` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `contrato` tinyint(1) NOT NULL,
  `id_nube` int(11) NULL DEFAULT NULL,
  `id_grupo` int(11) NOT NULL,
  `direccion` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ubigeo` varbinary(6) NOT NULL,
  `facturacion` int(11) NOT NULL DEFAULT 0,
  `prico` int(11) NOT NULL,
  `encargado` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cargo` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefono` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `correo` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `eds` int(11) NULL DEFAULT NULL,
  `visitas` int(11) NOT NULL DEFAULT 0,
  `mantenimientos` int(11) NOT NULL DEFAULT 0,
  `dias_visita` int(11) NOT NULL DEFAULT 0,
  `codigo_aviso` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `ruc`(`ruc`) USING BTREE,
  UNIQUE INDEX `razon_social`(`razon_social`) USING BTREE,
  INDEX `FK_ID_GRUPO`(`id_grupo`) USING BTREE,
  CONSTRAINT `tb_empresas_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `tb_grupos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 203 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_empresas
-- ----------------------------
INSERT INTO `tb_empresas` VALUES (1, '20345774042', 'SERVICENTRO AGUKI S.A.', 0, 115, 3, 'AV. ELMER FAUCETT 5482', 0x313530313430, 1, 1, 'Freddy Taira', NULL, '946501508', 'ftaira@aguki.com', 1, 0, 0, 0, 0, 1, '2025-03-03 00:15:56', '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (2, '20517103633', 'AJ GROUP INVERGAS', 0, 31, 4, 'AV. SANTIAGO DE CHUCO NRO. 501 COO. UNIVERSAL (ESTACION DE SERVICIO)', 0x313530313430, 1, 0, 'CAROLINA ALIAGA', 'GERENTE GENERAL', '998878575', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (3, '20603850913', 'A.J.C. NEGOCIOS E.I.R.L.', 0, 100, 5, 'CAL.ALVARADO NRO. 701 (KM. 11 DE LA AV. TUPAC AMARU)', 0x313530313430, 1, 0, 'Juan Salazar', '', '933737615', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (4, '20603906790', 'TRANSPORTES VALL E.I.R.L.', 0, 99, 5, 'CAL.ALVARADO NRO. 701 (KM. 11 DE LA AV. TUPAC AMARU)', 0x313530313430, 1, 0, 'Juan Salazar', '', '933737615', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (5, '20513567139', 'ALTA VIDDA GAS S.A.C.', 0, 14, 6, 'CAL.FELIPE SANTIAGO SALAVERRY NRO. 341 (ALT CDRA 18 DE AV CIRCUNVALACION)', 0x313530313430, 1, 1, 'KARLA VITTOR', 'GERENTE COMERCIAL', '995867602', 'kvittor@altaviddagas.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (6, '20518664019', 'AMEL PHARMA E.I.R.L.', 0, 148, 7, 'JR. PARURO NRO. 926 INT. 381', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (7, '20604857105', 'CORPORACION AXELL S.A.C.', 0, 130, 8, 'JR. HUANTA NRO. 944 INT. B URB. BARRIOS ALTOS', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (8, '20600251873', 'BALIAN S.A.C.', 0, 108, 9, 'AV. LAS PRADERAS MZA. U LOTE. 29 URB. PRADERA DE STA ANITA II E', 0x313530313430, 1, 1, 'Angel Morocco', '', '992210914', 'amorocco@balian.com.pe', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (9, '20546454224', 'CORPORACION BOTIFARMA E.I.R.L', 0, 9, 10, 'JR. PARURO NRO. 926 INT. 2076', 0x313530313430, 1, 0, 'Wilmer Reyna', 'Administrador', '997364151', 'wilmer_reyna25@hotmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (10, '20156278751', 'J.R. TELECOM S.R.LTDA.', 0, 84, 11, 'Jr. Conray Grande Nro. 4909 - Urb. Parque El Naranjal', 0x313530313430, 1, 0, 'beatriz Crisostomo', 'Contadora', '965395755', 'bcrisostomo@cableperu.net', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (11, '20501688593', 'CABLE VIDEO PERU S.A.C.', 0, 78, 11, 'Jr. Conray Grande Nro. 4901 - Urb. Parque Naranjal - Cdra.13 Av. Naranjal', 0x313530313430, 1, 1, 'Maricela Cordova', 'Contadora', '966370237', 'mcordova@cableperu.pe', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (12, '20548480214', 'CATAFARMA S.A.C.', 0, 141, 12, 'AV. JOSE SANTOS CHOCANO NRO. 104 P.J. VEINTIDOS DE OCTUBRE (AL COSTADO DEL HOSP. SAN JOSE)', 0x313530313430, 1, 0, 'Edwin Carlos Diaz', 'Administrador', '918106426', 'boticainkaperu@hotmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (13, '20499102071', 'ALIMENTOS SELECTOS CAZTELLANI S.A.', 0, 6, 13, 'AV. ARAVICUS NRO. 228 URB. TAHUANTISUYO (KM.5 AV.TUPAC AMARU C/CARLOS IZAS)', 0x313530313430, 1, 0, 'Roberto Aguilar', 'Gerente General', '996394345', 'cafecaztellani@gmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (14, '20605002448', 'SERVICENTROS CELESTE 3 SAC', 0, 144, 14, 'AV. QUILCA MZA. E LOTE. 29 URB. AEROPUERTO PROV. CONST. DEL CALLAO', 0x313530313430, 1, 0, ' Lizette Silva', ' Administracion', '987264002 ', 'servicentrocelestesa@gmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (15, '20168217723', 'COMERCIALIZADORA INDUSTRIAL LA MOLINA S.A.C.', 0, 92, 15, 'AV. LA MOLINA NRO. 448 URB. EL ARTESANO (BAJAR OVALO STA ANIT)', 0x313530313430, 1, 0, 'Carlos Briceño', '', '910736075', 'carlos.briceno@distribuidoraessa.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (16, '20491287544', 'CLINICA CORAZON DE JESUS SAC.', 0, 97, 16, 'AV. MARISCAL BENAVIDES NRO. 565', 0x313530313430, 1, 0, 'Isabut', 'Administradora', '925213430', 'clinicacorazondejesus@gmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (17, '20562897128', 'CORPORACION FARMACEUTICA SALVADOR S.A.C - COFARSA S.A.C.', 0, 74, 17, 'AV. GNRAL MIGUEL IGLESIAS NRO. 947 INT. A-B ZONA D (FRENTE AL HOSPITAL MARIA AUX)', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (18, '20548279039', 'CORGAS S.A.C.', 1, 82, 18, 'LAS TORRES NRO. 497 URB. LOS SAUCES', 0x313530313430, 1, 1, 'GUSTAVO CHAVEZ', '', '937504719', 'gchavez36@gmail.com', 1, 2, 0, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (19, '10199887608', 'QUISPE YUPANQUI SILVIO', 0, 63, 19, 'MZ D27 LOTE 13 AA.HH BOCANEGRA ZONA 3 AV. QUILCA FRENTE TOTUS DE AV. QUILCA', 0x313530313430, 1, 0, 'Henry Quispe', 'Administrador', '983213615', 'henryqc2016@hotmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (20, '20524016070', 'DELTA COMBUSTIBLES E.I.R.L.', 0, 61, 20, 'AV. ALFREDO MENDIOLA NRO. 700 URB. INGENIERIA (ALT. CRUCE AV. HABICH E INGENIERIA A 2 C)', 0x313530313430, 1, 1, 'DARWIN ATACHAGUA', '', '957292531', 'darwin.atachagua@deltaate.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (21, '20555690534', 'DELTA ATE E.I.R.L', 0, 64, 20, 'AV. NICOLAS AYLLON NRO. 3620 A.H. SANTA ILUMINATA (CARRETERA CENTRAL CRUCE AV ATE)', 0x313530313430, 1, 1, 'DARWIN ATACHAGUA', '', '957292531', 'darwin.atachagua@deltaate.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (22, '20511172633', 'ESTACION DE SERVICIOS GRIFO DENVER S.R.L.', 1, 0, 21, 'Av. Canta Callao Numero 59', 0x313530313430, 0, 0, 'JULIO HUERTA', '', '957992031', '', 1, 2, 0, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (23, '20530743919', 'DIESEL MAX SRL', 0, 50, 22, 'AV. CRUZ BLANCA NRO. 996 (CHIRI GRIFO)', 0x313530313430, 1, 1, 'WALTER GARCIA', '', '983489932', 'dieselmax21@yahoo.es', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (24, '20603821247', 'CORPORACION DISFARMED SAC', 0, 142, 23, 'PJ. OCHO MZA. D LOTE. 11 A.H. ALTO EL ROSAL', 0x313530313430, 1, 0, 'Miguel Cañahuaray', 'Administrador', '992514229', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (25, '20603635711', 'A & B DROFAR INVERSIONES S.A.C.', 0, 146, 24, 'JR. CUSCO NRO. 811 INT. 207 URB. BARRIOS ALTOS (ESPALDA DE RENIEC)', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (26, '20538108295', 'DUOGAS S.A.', 0, 0, 25, 'Ca. Las Garzas Nro. 328', 0x313530313430, 0, 0, 'EDWIN ARANGO', '', '952675018', 'erango@duogas.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (27, '20517855252', 'INVERSIONES DUVAL SAC', 1, 58, 26, 'AV. ALFREDO MENDIOLA NRO. 6200 INT. 101 URB. MOLITALIA (CRUCE CON AVENIDA MEXICO)', 0x313530313430, 1, 0, ' Jorge Vargas', ' Administracion', ' 945628525', ' jorgev524@yahoo.com', 1, 2, 0, 5, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (28, '20492314154', 'INVERSIONES EBENEZER S.R.L', 0, 65, 27, 'AV. 4 MZA. B4 LOTE. 03 P.J. NESTOR GAMBETTA BAJA ESTE (COSTADO DEL MERCADO ROJO GAMBETTA)', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (29, '20521431955', 'ECO TRADING S.A.C', 1, 36, 28, 'REPUBLICA ARGENTINA NRO. 798 URB. ZONA INDUSTRIAL (ALT. DE C.C. LA CACHINA)', 0x313530313430, 1, 0, ' Jose Borda', ' Administrador', '971150078', ' ecogas.ar@hotmail.com', 1, 2, 1, 5, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (30, '20478967111', 'ESTACION DE SERVICIO LURIN', 0, 119, 29, 'MZ. C. LT 2 URB. LOS HUERTOS DE VILENA', 0x313530313430, 1, 0, 'RAUL SOLIER REYNOSO', '', '998322921', 'eesslurinsac@gmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (31, '20343883936', 'ESTACION DE SERVICIO NIAGARA S.R.L', 0, 35, 30, 'JR. ELVIRA GARCIA Y GARCIA NRO. 2790 (ALT.COLONIAL Y UNIVERSITARIA)', 0x313530313430, 1, 0, ' Ricardo Yep', ' Gerente', ' 994219434', ' ricardoyep@hotmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (32, '10043210675', 'JAPA BORONDA MARIO RODOLFO', 0, 37, 31, 'CAR.MARGINAL NRO. SN C.P. MENOR HUANCABAMBA', 0x313530313430, 1, 0, 'RULO', '', '941165440', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (33, '20512220836', 'FARMA SAN AGUSTIN S.R.L.', 0, 94, 32, 'CAL.JOSE TORRES PAZ NRO. 110 URB. CIUDAD DE DIOS ZONA A (FRENTE A LA POSTA CIUDAD DE DIOS)', 0x313530313430, 1, 0, 'Augusto Castillo', 'Gerente General', '981578374', 'castilloaugusto18@gmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (34, '20448556663', 'FARMACIA SAN FRANCISCO S.A.C.', 0, 137, 33, 'JR. HUANCANE 721 - PUNO - SAN ROMAN - JULIACA', 0x313530313430, 1, 0, 'Margarita Sucari', 'Gerente General', '962656246', 'msucari41@hotmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (35, '20600534883', 'CORPORACION GANAJUR S.A.C.', 0, 72, 34, 'JR. ANCASH 14142 BARRIOS ALTOS', 0x313530313430, 1, 0, 'Juan Carlos Ortiz', '', '945363393', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (36, '20601790484', 'INVERSIONES LUMIPHARMA E.I.R.L.', 0, 71, 34, 'JR. ANTONIO MIROQUESADA NRO. 806 INT. 403 (ESQ.JR PARURO909 EDF COM MRQS 4PISO 403A)', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (37, '20425788192', 'CENTRO GAS DIEGO EIRL', 0, 60, 35, 'AV. LA MOLINA NRO. 401 URB. VULCANO (LETRERO MGAS - FRENTE GRIFO MOBIL)', 0x313530313430, 1, 0, 'GENOVEVA ESPIRITU', '', '989003565', 'iquitos7@hotmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (38, '20502716761', 'GAS ESCORPIO S.R.L.', 0, 102, 35, 'AV. LA MOLINA NRO. 401 URB. VULCANO (ALT. OVALO SANTA ANITA) LIMA - LIMA - ATE', 0x313530313430, 1, 0, 'GENOVEVA ESPIRITU', '', '989003565', 'iquitos7@hotmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (39, '20492920666', 'GASBEL EQUIPOS & ASESORIA S.A.C.', 1, 93, 36, 'JR. HUARAZ NRO. 1134 URB. BREÑA', 0x313530313430, 1, 0, 'MILAGROS ALMANDROS', '', '996890808', 'malmandros@jevaro.com.pe', 1, 2, 0, 9, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (40, '20520786873', 'GASOCENTRO LIMA SUR S.A.C.', 1, 22, 37, 'AV. JUAN DE ALIAGA NRO. 278 INT. 503 (EX JOSE COSSIO)', 0x313530313430, 1, 0, ' Alex Naters', ' Gerente', ' 987937575', ' naters.alex@gmail.com', 1, 2, 0, 9, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (41, '20546818102', 'GRUPO AVTEC CONTENIDOS SAC', 0, 27, 38, 'AV. DEL PINAR NRO. 110 DPTO. 204 URB. CHACARILLA DEL ESTANQUE', 0x313530313430, 1, 0, 'FERNANDO BAZAN', 'GERENTE OPERACIONES', '989054789', 'fernando.bazan@gasored.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (42, '20600658311', 'ESTACION TRAPICHE S.A.C.', 0, 5, 38, 'AV. DEL PINAR NRO. 110 DPTO. 204 URB. CHACARILLA DEL ESTANQUE', 0x313530313430, 1, 0, 'FERNANDO BAZAN', 'GERENTE OPERACIONES', '989054789', 'fernando.bazan@gasored.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (43, '20100111838', 'GRIFOS ESPINOZA S A', 0, 104, 39, 'AV. JAVIER PRADO ESTE NRO. 6519 URB. PABLO CANEPA (ENTRE CRUCE AV. INGENIEROS, GESA MARKETS)', 0x313530313430, 1, 1, ' Francisco ponte', ' Gerente Com.', ' 998115473', 'fponte@grupogesa.com ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (44, '20138645607', 'GRIFO SANTO DOMINGO DE GUZMAN SRLTDA', 0, 140, 40, 'AV. RAMIRO PRIALE LOTE. 23A ASC. DIGNIDAD NACIONAL (PARCELA L)', 0x313530313430, 1, 1, 'SUSAN', 'ADMINISTRADORA', '976363362', 'adm.santodomingo.srl@gmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (46, '20514304921', 'GRUPO INTIFARMA S.A.C.', 0, 96, 42, 'AV. INSURGENTES NRO. 711 INT. 4 SAN MIGUEL (MINISTERIO MARINA)', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (47, '20602712363', 'CONSORCIO GAS DEL SUR', 0, 20, 43, 'CAL. PANAMERICANA SUR KM. 33.5 URB. PREDIO LAS SALINAS', 0x313530313430, 1, 0, ' Luis Berrios Tosi', 'Luis Berrios', '', 'contabilidad3@copepdelperu.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (48, '20538807037', 'HEVALFAR SRL', 0, 91, 44, 'MZA. D-1 LOTE 26 2DO. PISO INT. 202 URB. LAS PRADERAS DE STA. ANITA', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (49, '20521111888', 'INVERSIONES Y REPRESENTACIONES EGAR S.A.C.', 0, 89, 45, 'AV. ALFREDO MENDIOLA NRO. 3973 INT. 201 URB. MICAELA BASTIDAS - Lima - Lima - Los Olivos', 0x313530313430, 1, 0, 'ORLANDO QUISPE', 'DUEÑO', '920659824', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (50, '20493089570', 'ESTACION DE SERVICIOS H & A S.A.C.', 0, 48, 46, 'AV. UNIVERSITARIA NRO. S/N (CDRA 51 ESQUINA CON LA CALLE A)', 0x313530313430, 1, 1, 'FELICIANO AZAÑERO', 'GERENTE GENERAL', '963760146', 'estacion.hasac@gmail.com>', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (51, '20556597376', 'ESTACION DE SERVICIOS ANDAHUASI S.A.C.', 0, 45, 46, 'AV. UNIVERSITARIA MZA. A LOTE. 06 (CDRA. 51 FRENTE PARQUE NARANJAL)', 0x313530313430, 1, 0, ' Maribel Bernabé', ' Administración', '963760146 ', 'estacion.hasac@gmail.com ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (52, '20551615856', 'INVERSIONES JIARA S.A.C.', 0, 138, 47, 'AV. ESTEBAN CAMPODONICO NRO. 262 URB. SANTA CATALINA', 0x313530313430, 1, 1, 'CARMEN ARAMAYO', 'GERENTE GENERAL', '959373867', 'alkohler_eirl@hotmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (53, '20371975561', 'REPRESENTACIONES JEMMS S.A.C.', 0, 136, 48, 'AV. ALFREDO MENDIOLA NRO. 1085 URB. PALAO 2DA ETAPA', 0x313530313430, 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (54, '20492197417', 'JE OPERADORES SAC', 0, 86, 48, 'Av. Nestor Gambeta Km. 7.10 Mz.B-6 Lt.4 Coop. Vivienda de Trab. ENAPU  - Callao - Callao - Callao ', 0x313530313430, 1, 1, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (55, '20512853529', 'ATS AMERICA SAC', 0, 87, 48, 'AV. LIMA SUR NRO. 895 CHOSICA LIMA - LIMA - LURIGANCHO', 0x313530313430, 1, 1, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (56, '20535614548', 'OPERADORES DE ESTACIONES SAC', 0, 85, 48, 'AV. CIRCUNVALACION NRO. 1386 (ALT MERCADO DE FRUTAS) LIMA - LIMA - LA VICTORIA', 0x313530313430, 1, 1, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (57, '20551112781', 'GRUVENI SRL', 0, 150, 48, 'JR. LOS ANTROPOLOGOS MZA. D LOTE. 4 COO. LA UNION (MODULO DE PODER JUDICIAL DE PROCERES)', 0x313530313430, 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (58, '20551297978', 'GRANDINO S.A.C.', 0, 135, 48, 'CAL.LOS CEREZOS NRO. 291 URB. DE LA LOTIZ. CHILLON', 0x313530313430, 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (59, '20566149151', 'GASNOR S.A.C.', 0, 76, 48, 'AV. ENCALADA 232 - SANTIAGO DE SURCO', 0x313530313430, 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (60, '20566149401', 'ESTACIONES DEL NORTE SAC', 0, 70, 48, 'CAR.PANAN NORTE KM. 1168 P.J. BARRIO LETICIA', 0x313530313430, 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (61, '20600868862', 'PETRO CALLAO SAC', 0, 88, 48, 'AV. ARGENTINA NRO. 498 URB. CHACARITAS - Callao - Callao - Callao', 0x313530313430, 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (62, '20600908627', '524 CONSULTING S.A.C.', 0, 134, 48, 'AV. LA ENCALADA NRO. 232 URB. CENTRO COMERCIAL MONTERRICO', 0x313530313430, 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (63, '20602003427', 'PETRO NAZCA S.A.C.', 0, 101, 48, 'AV. PANAMERICANA NRO. 891 URB. VISTA ALEGRE (GRIFO PECSA)', 0x313530313430, 1, 1, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (64, '20604631379', 'MOVI PETROL S.A.C.', 0, 131, 48, 'LA ENCALADA NRO. 232', 0x313530313430, 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (65, '20404000447', 'SERVICENTRO ESPINOZA NORTE S.A', 0, 149, 49, 'CAR.PAN. NORTE NRO. K191 (LA PALMA PTO. SUPE)', 0x313530313430, 1, 0, 'Manuel Menacho', 'Administrador', '992018928', 'manuluismd1999@gmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (66, '20605427147', 'INVERSIONES KATIMILA E.I.R.L.', 0, 147, 49, 'AV. CENTENARIO KM. 4.100 KM. REF (AV. CENTENARIO KM. 4.100)', 0x313530313430, 1, 0, 'Manuel Menacho', 'Administrador', '992018928', 'manuluismd1999@gmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (67, '20283756115', 'SERVICENTRO UCAYALI S.A.C', 0, 17, 50, 'AV. CENTENARIO NRO.4100', 0x313530313430, 1, 1, 'Fiorella Elias', 'Contadora', '970428573', 'f.elias@grespinoza.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (68, '20404883918', 'GRIFOS ESPINOZA DE TINGO MARIA S.A.', 0, 24, 50, 'AV ENRIQUE PIMENTEL NRO 116', 0x313530313430, 1, 1, 'Fiorella Elias', 'Contadora', '970428573', 'f.elias@grespinoza.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (69, '20508196475', 'PETROCENTRO YULIA S.A.C.', 0, 49, 50, 'AV. DE LA MARINA NRO. 2789 URB. MARANGA 1RA ET. (CRUCE CON AV.ESCARDO)', 0x313530313430, 1, 0, 'Fiorella Elias', 'Contadora', '970428573', 'f.elias@grespinoza.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (70, '20605129154', 'GRIFOS GES S.A.C.', 0, 133, 51, 'AV. ISABEL LA CATOLICA NRO. S/N URB. MATUTE (ESQUINA CON JR ANDAHUAYLAS)', 0x313530313430, 1, 0, 'Anyeli Macalupu', 'Contadora', '939375251', 'amacalupu@grifosges.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (71, '20605450475', 'SERVICENTRO JAQUELINE DE PUCALLPA S.A.C.', 0, 145, 51, 'AV. CARRETERA FEDERICO BASADRE KM. 9.00 (LATERAL DERECHO)', 0x313530313430, 1, 0, 'Anyeli Macalupu', 'Contadora', '939375251', 'amacalupu@grifosges.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (72, '20203530073', 'SERVICENTRO SAN HILARION S.A.', 0, 69, 52, 'AV. FLORES DE PRIMAVERA NRO. 1988 URB. SAN HILARION (MZ B - LT.03 / CRUCE CON AV.CTO.GRANDE)', 0x313530313430, 1, 0, 'Tomas Zavala', 'Gerente General', '998196242', 'huancayo18@hotmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (73, '20506467854', 'CORPORACION JULCAN S.A.', 0, 13, 52, 'AV. PROCERES DE LA INDEPENDEN NRO. 2556 URB. LOS ANGELES (ALTURA DEL PARADERO 20)', 0x313530313430, 1, 1, 'TOMAS ZAVALA', '', '998196242', 'huancayo18@hotmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (74, '20347869849', 'SERVIC. Y AFINES LAS AMERICAS EIRL', 1, 40, 53, 'AV. DE LAS AMERICAS NRO. 1259 URB. BALCONCILLO (GRIFO PETROPERU)', 0x313530313430, 1, 1, ' Luisa Manco', ' Administracion', ' 987817538', ' servicentroyafineslasamericas@hotmail.com', 1, 2, 1, 8, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (75, '20459020137', 'MARKET LAS BELENES S.A.C', 0, 77, 1, 'JR. EL POLO 493 - URB EL DERBY DE MONTERRICO', 0x313530313430, 1, 0, 'Paty Medina', 'Gerente General', '966833946', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (76, '20101312519', 'LIMABANDA S.A.C.', 0, 59, 55, 'AV. MARISCAL ORBEGOSO NRO. 120 URB. EL PINO', 0x313530313430, 1, 1, 'LUIS NUÑEZ', '', '989005187', 'lnudarco@limabandasac.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (77, '20124367850', 'INVERSIONES TRANSP. Y SERV. CINCO S.A.C.', 0, 23, 55, 'AV. JAVIER PRADO ESTE NRO. 1059 URB. SANTA CATALINA (FRENTE AL COLG.SAN AGUSTIN)', 0x313530313430, 1, 0, 'LUIS NUÑEZ', 'Gerente General', '989005187', 'lnudarco@limabandasac.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (78, '20492727661', 'LIVORNO OIL TRADING S.A.C.', 0, 103, 56, 'JR. ABTAO NRO. 784 (ESQ. HIPOLITO UNANUE)', 0x313530313430, 1, 0, 'MAGNOLIA', '', '946594870', 'administracion.control@merrillperu.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (79, '20137926742', 'SERVICENTRO LOS ROSALES S.A.', 0, 124, 57, 'AV. AYACUCHO NRO 140', 0x313530313430, 1, 0, 'Maricell Guillen', 'Administradora', '998450561', 'mguillen@servicentrolosrosales.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (80, '20110623420', 'INVERSIONES LUMARCO SA', 1, 122, 1, 'CAR.CENTRAL KM. 11.2 A.H. LA ESTRELLA (GRIFO PECSA COSTADO PLAZA REAL STA CLARA)', 0x313530313430, 1, 1, 'Luciano Marching', 'Gerente General', '967778888', '', 1, 2, 2, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (81, '20517735605', 'LUXOR PHARMACEUTICAL SAC', 0, 90, 59, 'AV. CESAR VALLEJO 895.', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (82, '20524359601', 'MABA FARMA S.A.C.', 0, 11, 60, 'MZA. C LOTE. 4 JAZMIN DE OQUENDO (COSTADO MERCADO LA ALBORADA)', 0x313530313430, 1, 0, 'Miguel Balvin', 'Administrador', '975547119', 'mabafarmasac-2013@hotmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (83, '20161800920', 'LUBRIGAS S.R.LTDA.', 0, 80, 61, 'Av. Nicolas Ayllon Nro. 3562 Fnd. Mayorazgo (Frente a Planta Qui lomica Suiza)', 0x313530313430, 1, 1, 'Alejandro Mandujano', '', '980090788', 'amandujano@grupomandujano.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (84, '20516035758', 'GASNORTE S.A.C', 0, 79, 61, 'Av. Gerardo Unger Nro. 3301 - Urbanización: Habilit.Indust.Pan.Norte', 0x313530313430, 1, 0, 'Alejandro Mandujano', '', '980090788', 'amandujano@grupomandujano.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (85, '20524249848', 'CENTROGAS VISTA ALEGRE S.A.C.', 0, 83, 61, 'Av. Nicolas Ayllon Nro. 4706 Fnd. Vista Alegre', 0x313530313430, 1, 0, 'Alejandro Mandujano', '', '980090788', 'amandujano@grupomandujano.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (86, '20547011954', 'CENTROGAS IQUITOS S.A.C.', 0, 67, 61, 'AV. IQUITOS NRO. 983 (CRUCE CON AV CATOLICA)', 0x313530313430, 1, 0, 'Alejandro Mandujano', '', '980090788', 'amandujano@grupomandujano.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (87, '20557618920', 'CENTRAL PARIACHI S.A.C.', 0, 57, 61, 'AV. NICOLAS AYLLON NRO. S/N SEMI RUSTICO PARIACHI PARCELA 10906', 0x313530313430, 1, 0, 'Alejandro Mandujano', '', '980090788', 'amandujano@grupomandujano.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (88, '20517053351', 'MASGAS PERU S.A.C.', 1, 0, 63, 'Av. Tupac Amaru Nro. 3685 - Urb. Carabayllo ', 0x313530313430, 0, 0, 'Victor Naranjo', 'Gerente General', '994271052', 'administracionperu@masgasperu.com', 1, 2, 0, 5, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (89, '20371826727', 'ESTACION DE SERVICIOS GRIFO MASTER SRL', 1, 0, 64, 'Av. Alfredo Mendiola Mza. E Lote. 16 - Asoc. Rio Santa', 0x313530313430, 0, 0, '', '', '', '', 1, 2, 2, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (90, '20603673485', 'MATIAS & ALEXA E.I.R.L.', 0, 75, 65, 'JR. PARURO NRO. 926 INT. 345B GALERIA CENTRO COMERCIAL CAPON CENTER', 0x313530313430, 1, 0, 'Jerson', 'Administrador', '983465591', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (91, '20304887762', 'MIDAS GAS S.A', 0, 44, 66, 'AV. NICOLAS ARRIOLA NRO. 3191 .', 0x313530313430, 1, 1, 'Walter Meza', 'Administrador', '987518298', 'walter.meza@midasgas.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (92, '10090647879', 'SALCEDO GUEVARA NESTOR', 0, 127, 67, 'CAR. CENTRAL NRO 16.5 URB. HUAYCAN', 0x313530313430, 1, 1, ' Alexandra', ' Administradora', ' 920660239', ' Inandisa01@gmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (93, '20566091306', 'NG FARMA S.A.C.', 0, 132, 68, 'AV. SANTA ROSA 1044 APV. LOS CHASQUIS', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (94, '20177941591', 'ORGANIZACION FUTURO SAC', 1, 121, 69, 'AV. JAVIER PRADO ESTE 6651', 0x313530313430, 1, 1, 'Gunther Paucar', 'Gerente General', '977810907', 'gpaucar@orfusac.com', 1, 2, 0, 6, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (95, '20517231631', 'PANAMERICAN GAS TRADING S.A.C.', 0, 0, 70, 'Av. Republica De Panama Nro. 4120 ', 0x313530313430, 0, 0, '', '', '', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (96, '20452799368', 'ESTACION FINLANDIA E.I.R.L.', 0, 109, 71, 'AV. SIETE MZA. 9 LOTE. 02-A (ESQUINA DE AV. SIETE Y FINLANDIA)', 0x313530313430, 1, 1, 'Miriam Ocaña', 'Administradora', '956406088', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (97, '20494793521', 'ESTACION EL OVALO E.I.R.L.', 1, 114, 71, 'AV. F. LEON DE VIVEIRO', 0x313530313430, 1, 1, 'Nola Cordova', 'Administradora', '924770964', '', 1, 2, 2, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (98, '20601709148', 'PETRO LUMARA S.A.C.', 1, 106, 72, 'Ca. Montegrande Nro. 109 Int. 301 - Urb. Chacarilla Del Estanque', 0x313530313430, 1, 0, ' Fernando Camacho', ' Administrador', ' 975802575', '  fcamacho@cocsaperusa.com', 1, 4, 0, 4, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (99, '20511193045', 'ESTACION DE SERVICIOS MONTE EVEREST SAC', 0, 125, 73, 'AV. AVIACION NRO. 4285 (ALT.CDRA 42 AV.AVIACION)', 0x313530313430, 1, 1, 'x', '', '', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (100, '20514636843', 'ESTACIONES DE SERVICIOS PETRO WORLD SAC', 0, 126, 73, 'AV. VENEZUELA ESQUINA CON AV. RIVA AGUERO', 0x313530313430, 1, 1, 'x', '', '', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (101, '20505133430', 'PETROCARGO S.A.C', 1, 68, 74, 'AV. ELMER FAUCCETT NRO. 6000', 0x313530313430, 1, 0, 'WILBER LEON', 'CONTADOR', '994271076', 'wilbertleon@petrocorpsa.com', 1, 2, 0, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (102, '10009635128', 'TEODORA DOMINGUEZ', 0, 33, 75, 'AV. NICOLAS DE AYLLON N 441 CHACLACAYO - LIMA - LIMA', 0x313530313430, 1, 0, 'Teodora', 'Gerente General', '960919061', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (103, '20127765279', 'COESTI S.A.', 1, 66, 76, 'Av. Circunvalación del Club de Golf Los Incas N° 134 Urb. Club de Golf Los Incas - Lima - Lima - Santiago de Surco', 0x313530313430, 1, 1, 'Claudio Aramburu', NULL, '947640511', 'CAramburuL@primax.com.pe', 1, 2, 2, 1, 1, 1, '2024-12-30 17:05:54', '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (104, '20330033313', 'PERUANA DE ESTACIONES SERVICIOS SAC', 0, 117, 76, 'Av. Circunvalación del Club de Golf Los Incas N° 134 Urb. Club de Golf Los Incas - Lima - Lima - Santiago de Surco', 0x313530313430, 1, 1, 'Claudio Aramburu', '', '', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (105, '20603822359', 'DROGUERIA DISTRIBUIDORA PRIMED S.A.C.', 0, 105, 77, 'Av. Gral. Miguel Iglesias Mz.g Lt.30 - AA.HH. Javier Heraud', 0x313530313430, 1, 0, '', '', '', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (106, '20603012268', 'INVERSIONES RAMSAN E.I.R.L.', 0, 16, 78, 'Jr. Pedro Garenzon Nº 500, Urb. Miguel Grau', 0x313530313430, 1, 0, 'Flor Roxana Sanchez', '', '', 'consor.norteno17@gmail.com / anconero.201802@gmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (107, '20503840121', 'REPSOL COMERCIAL SAC', 1, 0, 79, 'Av. Victor Andres Belaunde Nro. 147 ', 0x313530313430, 0, 0, 'Juan Carlos Evangelista', 'Jefe de Tecnologia', '996412738', 'JEVANGELISTAR@repsol.com', 1, 2, 0, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (108, '20325753821', 'RED INTERNACIONAL DE COMBUSTIBLE Y SERVICIO AUTOMOTRIZ S.R.L.', 0, 51, 80, 'AV. NICOLAS ARRIOLA NRO. 1003 URB. LA POLVORA', 0x313530313430, 1, 0, ' Catherine Solano', 'Administracion ', ' 992298539', '  jefeope@ricsa.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (109, '20486255171', 'CORPORACION RIO BRANCO S A', 1, 143, 81, 'CAR.PANAMERICANA NORTE KM. 92.5 C.P. CHANCAYLLO (BARRIO SAN JUAN PASANDO EL PUENTE)', 0x313530313430, 1, 1, 'Ralpy Hinostroza', 'Administrador', '964004784', 'riobrancoralpy@hotmail.com', 1, 2, 0, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (110, '10091479791', 'CARLOS ALFREDO IBAÑEZ MANCHEGO', 0, 118, 82, 'AV. DE LOS HEROES 1187-1189', 0x313530313430, 1, 0, 'Sandra', '', '948639772', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (111, '20493091396', 'GASOCENTRO PUENTE NUEVO S.A.C.', 0, 112, 82, 'MZA. G LOTE. 1 ASOCIACION DE VIVIENDA ANCIETA', 0x313530313430, 1, 0, 'Gina Mispireta', 'Gerente General', '989265054', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (112, '20502825624', 'ESTACION DE SERVICIOS SAN JUANITO S.A.C.', 0, 116, 82, 'AV. HEROES NRO. 1109 (ALT.HOSPITAL MARIA AUXILIADORA)', 0x313530313430, 1, 0, 'Nelly', 'Administradora', '989265061', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (113, '20511053031', 'ESTACION DE SERVICIO GIO SAC', 0, 123, 82, 'AV. PACHACUTEC NRO. 3859 P.J. CESAR VALLEJO', 0x313530313430, 1, 0, 'Jose Ibañez', 'Gerente General', '989119054', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (114, '20402786729', 'INVERSIONES SANTA ROSA E.I.R.L', 0, 12, 83, 'JR. MOQUEGUA NRO. 398 INT. 7 P.J. FLORIDA BAJA', 0x313530313430, 1, 1, 'Felipe Chu', 'Gerente General', '981444073', 'grifosantarosa1998@hotmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (115, '20373831124', 'ESTACION DE SERVICIOS SCHOII S.R.L.', 0, 29, 84, 'AV. MARIANO CORNEJO NRO .1508(POR LA PLAZA DE LA BANDERA)', 0x313530313430, 1, 0, '', '', '', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (116, '20518960688', 'PITS GNV SAC', 0, 34, 84, 'AV. NICOLAS DE PIEROLA NRO. 800 (MZ.H1 LT.16, ESQUINA CON AV. VILLA MARIA)', 0x313530313430, 1, 0, 'SUSY LAO', '', '994077048', 'pitsgnvsac@yahoo.es', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (117, '20602772935', 'DISTRIBUCIONES SELMAC S.A.C.', 0, 98, 85, 'JR. PARURO NRO. 926 INT. 212 URB. BARRIOS ALTOS', 0x313530313430, 1, 0, 'Sabi', 'Administrador', '955106629', 'contabilidadselmacsac@gmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (118, '20210975862', 'OPERACIONES Y SERVICIOS GENERALES S A', 0, 38, 86, 'AV. CAMINOS DEL INCA MZA. N LOTE. 19 URB. SAN JUAN BAUTISTA DE V. (URB.SAN JUAN BAUTISTA DE VILLA)', 0x313530313430, 1, 1, 'Eliana Rafael ', ' Administracion', ' 946433001', 'asistenteosg@operadoresmr.com.pe ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (119, '20334129595', 'GRIFO SERVITOR S.A', 0, 0, 87, 'Av. Alfredo Mendiola - Urb. Industrial La Milla ', 0x313530313430, 0, 0, 'Christian Higa', 'Gerente General', '998228493', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (120, '20517117421', 'SHICHI - FUKU CORPORATION S.A.C.', 0, 56, 88, 'AV. CANADA NRO. 298 URB. SANTA CATALINA', 0x313530313430, 1, 0, ' Jose Nakada', ' Administracion', '', ' estacioncanadagnv@hotmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (121, '20517700640', 'SIROCO HOLDINGS S.A.C', 0, 0, 89, 'Av.Elmer Faucett #735 Callao', 0x313530313430, 0, 0, 'RICARDO HIDALGO', '', '994219434', 'ricardo.hidalgo@hesperservices.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (122, '20377674686', 'SERVICENTRO SMILE S.A.', 1, 110, 90, 'CAL.LOS ORFEBREROS NRO. 129 URB. IND EL ARTESANO', 0x313530313430, 1, 1, 'ELVIS MARLON', 'ADMINISTRADOR', '999086928', 'e.napravnick@servicentrosmile.com', 1, 4, 2, 3, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (123, '20534525070', 'COMERCIALIZADORA DE COMBUSTIBLES TRIVEÑO S.A.C.', 0, 113, 91, 'AV. MATIAS MANZANILLA-2DO PIS NRO. 625 INT. 04 (FRENTE AL HOSPITAL DEL SEGURO SOCIAL)', 0x313530313430, 1, 0, 'ROMULO TRIVEÑO', '', '956725267', 'trivenog@comtrisac.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (124, '20298736820', 'INVERSIONES UCHIYAMA SRL', 0, 2, 92, 'AV. LA MAR NRO. 2382', 0x313530313430, 1, 0, 'Lizbeth Castro', 'Contadora', '975363363', 'lizbeth@estacionlamar.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (125, '20514326496', 'CORPORACION UNO S.A.', 0, 107, 93, 'AV. VICTOR ANDRÉS BELAUNDE NRO. 214 INT. 303 (ESQUINA CON CALLE LOS PINOS)', 0x313530313430, 1, 1, 'LEANDRA BENDITA', '', '985054954', 'leandra.bendita@senergyc.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (126, '20565949731', 'CONSORCIO VITAFARMA S.A.C.', 0, 129, 94, 'JR. PARURO NRO. 775 INT. 307 URB. BARRIOS ALTOS', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (127, '20020020000', 'DEMO GS', 0, 15, 1, 'Condevilla', 0x313530313430, 1, 0, '', NULL, '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (128, '12345678901', 'DEMO', 0, 19, 1, 'AV. LT PLAZA NORTE', 0x313530313430, 1, 0, '', NULL, '', 'demo@demo.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (129, '20101127614', 'MANUEL IGREDA Y JULIO RIOS S.R.L', 0, 47, 62, 'CAL.MONTERREY NRO. 341 INT. 502', 0x313530313430, 1, 0, '', NULL, '', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (130, '20519069262', 'RICARDO CALDERON INGENIEROS SAC', 0, 52, 99, 'AV. AUGUSTO B LEGUIA NRO. 307 COO. POLICIAL (ACONT DE AV. PERU -ANTES DE ZARUMILLA)', 0x313530313430, 1, 0, '', NULL, '9999999', 'ventas@rcingenieros.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (131, '20348303636', 'ESTACION DE SERVICIOS HERCO S.A.C.', 1, 55, 43, 'CAR.PANAMERICANA SUR NUEVA KM. 33.5 MZA. C LOTE. 14 SECTOR LAS SALINAS (GRIFO HERCO) ', 0x313530313430, 1, 1, '', NULL, '', 'herco@ventas.com', 1, 2, 0, 8, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (132, '20601351944', 'NEGOCIACIONES VALERIA & CHRIS S.A.C', 0, 73, 1, 'AV. LIMA SUR NRO. S/N CHOSICA (LT A2-1 A1-9) ', 0x313530313430, 1, 0, '', NULL, '', 'ventas@valeria.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (133, '20392479687', 'INVERSIONES CORONACION S.R.L.', 0, 95, 1, 'MZ E LT 10 SECTOR CENTRAL HUERTOS DE MANCHAY.', 0x313530313430, 1, 0, 'Samuel.', NULL, '942416053 - 6624797', ' ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (134, '20515789961', 'GAMA INVERSIONES GENERALES S.A.C.', 0, 111, 1, 'AV. QUILCA CUADRA 11 S/N MZA. E LOTE. 29 URB. AEROPUERTO PROV. CONST. DEL CALLAO', 0x313530313430, 1, 0, ' ', NULL, '  ', ' ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (135, '20602359981', 'PUNTO GAS S.A.C.', 0, 139, 1, 'AV. MARISCAL OSCAR T. BENAVIDES NRO. 1657 URB. LA TRINIDAD (ALTURA CDRA. 16 EX COLONIAL) ', 0x313530313430, 1, 0, '', NULL, ' ', ' ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (136, '20604271089', 'ESTACION DE SERVICIOS VICTORIA L & K HNAS. S.A.C.', 0, 151, 31, 'JR. LAS ACACIAS NRO. SN (1 CDRA DEL GRIFO HUANCABAMBA)', 0x313530313430, 1, 0, ' ', NULL, ' ', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (137, '20522168182', 'FARMACIAS INTEGRALES DE LA SOLIDARIDAD S.A.C.', 0, 152, 1, 'AV. ANGAMOS ESTE NRO. 716 (HOSPITAL DE SOLIDARIDAD DE SURQUILLO)', 0x313530313430, 1, 0, ' ', NULL, ' ', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (138, '20600765044', 'EUCEL S.R.L.', 0, 153, 1, 'PJ. SAN MARTIN MZA. L LOTE. 3 CANTO CHICO', 0x313530313430, 1, 0, ' ', NULL, ' ', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (139, '20605395482', 'DROGUERIA LMG FARMA PERU S.A.C.', 0, 154, 1, 'UPIS SAN JOSE MZA. G-1 LOTE. 4 INT. 2 ', 0x313530313430, 1, 0, ' ', NULL, ' (01) 4045448 / 961 ', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (140, '20514721280', 'GRUPO INTI S.A.C', 0, 155, 1, 'Jr. Dante 893 - 899', 0x313530313430, 1, 0, ' ', NULL, '447-3684', 'grupointisac@gmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (141, '20338926830', 'GRIFO VALERIA VICTORIA S.A.C.', 0, 156, 1, 'AV. RIVA AGUERO NRO. 411', 0x313530313430, 1, 0, ' ', NULL, ' ', ' ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (142, '20484056227', 'OLEOCENTRO Y SERVICIOS SAN PEDRO EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA', 0, 157, 1, 'MZA. 30 LOTE. 01 A.H. SAN PEDRO', 0x313530313430, 1, 0, ' ', NULL, ' ', ' ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (143, '20546828671', 'DRUGSTORE SOL FARMA CORP. E.I.R.L.', 0, 158, 1, 'CAL.CORACEROS NRO. 158', 0x313530313430, 1, 0, ' ', NULL, '4270669 / 979462433', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (144, '20605344896', 'DROGUERIA DISTRIBUIDORA E IMPORTADORA VILLALEON E.I.R.L', 0, 159, 8, 'JR. HUANTA NRO. 944 INT. B URB.  BARRIOS ALTOS', 0x313530313430, 1, 0, ' ', NULL, ' ', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (145, '10214719717', 'HILARIO CHAUCA GILMAR MARCIANO', 0, 160, 1, 'AV. AVIACION S/N ', 0x313530313430, 1, 0, ' ', NULL, ' ', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (146, '20605224505', 'BOTICA NUEVO PERU E.I.R.L.', 0, 161, 1, 'AV. LOS PINOS NRO. 1414 URB. EL PINAR', 0x313530313430, 1, 0, ' ', NULL, ' ', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (147, '20604193711', 'INVERSIONES P & M FARMA E.I.R.L.', 0, 162, 1, 'JR. MERCURIO NRO. 172 URB. SAN CARLOS II ETP', 0x313530313430, 1, 0, '', NULL, ' ', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (148, '10702311191', 'ALIAGA PEREZ LEONARDO CARLOS', 0, 163, 1, 'S/N', 0x313530313430, 1, 0, '', NULL, ' 932 475 889', 'boticasdelahorro2019@gmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (149, '20605809686', 'GRIFOS ESSA PUCALLPA S.A.C.', 0, 164, 1, 'AV. FEDERICO BASADRE NRO. 298', 0x313530313430, 1, 0, ' ', NULL, ' ', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (150, '20546740154', 'PRECIO S.A.C.', 0, 165, 1, 'AV. LA ENCALADA NRO. 232 URB. CENTRO COMERCIAL MONTERRICO (A 2 CUADRAS DE VIVANDA)', 0x313530313430, 1, 0, 'CHRISTIAN GONZALES', NULL, '5342336', 'atencionalcliente@estacion715.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (151, '20605764712', 'DROGUERIA JJC S.A.C.', 0, 166, 1, 'CAL.LAS ANTILLAS NRO. 150 URB. ISLA VERDE', 0x313530313430, 1, 0, '', NULL, ' ', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (152, '20603587481', 'CONSORCIO EFE S.A.C.', 0, 167, 1, 'AV. JOSE SANTOS CHOCANO NRO. 128 P.J. VEINTIDOS DE OCTUBRE ', 0x313530313430, 1, 0, ' ', NULL, ' ', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (153, '20557398628', 'DISTRIBUIDORA V & G FARMA S.R.L.', 0, 168, 1, 'CAL.LOS PETALOS NRO. 189 URB. LA ACHIRANA', 0x313530313430, 1, 0, '', NULL, ' 987047349 / 9867456', 'distribuidoravgfarma@gmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (154, '20553772436', 'GASOCENTRO ICA S.A.', 0, 0, 95, 'Av. Mexico # 295', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (155, '20100079179', 'ESTACION DE SERV BOLIVAR S A', 0, 0, 95, 'SAN ROQUE - SANDIEGO DE SURCO ', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (156, '20605955704', 'PETRO LAS LOMAS S.A.C.', 0, 169, 1, 'CAL.LOS PROCERES NRO. S/N PBLO. LAS LOMAS PIURA - PIURA - LAS LOMAS', 0x313530313430, 1, 0, 'CHRISTIAN GONZALES', NULL, '5342336', 'atencionalcliente@estacion715.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (157, '20605720847', 'GRIFOS ESSA DE TINGO MARIA S.A.C', 0, 170, 1, 'CAR.TINGO MARIA A HUANUCO NRO. 2.5 CAS. AFILADOR HUANUCO', 0x313530313430, 1, 0, ' ', NULL, ' ', ' ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (158, '20565966589', 'CORPORACION VICTORIA PERUANA S.A.C', 0, 171, 1, 'Calle Los Detectives Mz. F2 Lt. L1 Urb. Honor y Lealtad ', 0x313530313430, 1, 0, '', NULL, '(056) 283040', 'drogueria-america@outlook.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (159, '10463388089', 'CAHUAPAZA APAZA FREDY EDWIN', 0, 172, 1, 'Jr. Bolivar 153', 0x313530313430, 1, 0, '', NULL, '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (160, '10411640855', 'AGUIRRE ZURITA CARLOS JAVIER', 0, 173, 1, 'JR LEONCIO PRADO 1008', 0x313530313430, 1, 0, '', NULL, '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (161, '10458612256', 'CORZO LAYME MARIA LORENA', 0, 174, 1, 'Mz.188 Lt.10 AA.HH. Huascar', 0x313530313430, 1, 0, '', NULL, '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (162, '10060507291', 'CORZO OCANA EFRAIN', 0, 175, 1, '', 0x313530313430, 1, 0, '', NULL, '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (163, '20549666362', 'CONSORCIO MEDICORP & SALUD S.A.C.', 0, 176, 1, 'AV. LAS FLORES DE PRIMAVERA NRO. 1045 URB. LAS FLORES', 0x313530313430, 1, 0, '', NULL, '950279527', 'medicorp_i_salud@hotmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (164, '20606082917', 'INVERSIONES FARMACEUTICA DIAZ S.A.C', 0, 178, 1, '	JR. LAS CALEZAS NRO. 131 (ALTURA DE PLAZA VEA RIMAC)', 0x313530313430, 1, 0, '', NULL, '01-7151990', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (165, '20601136059', 'FARMA SOLUTIONS E.I.R.L.', 0, 179, 1, 'Calle Loma Los Crisantemos 117', 0x313530313430, 1, 0, '', NULL, '960207974', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (166, '20335757697', 'WO SOCIEDAD ANONIMA', 0, NULL, 96, 'AV. GUILLERMO PRESCOTT NRO. 202 URB.  RISSO', 0x313530313430, 0, 0, NULL, NULL, '4419938', NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (167, '20402173476', 'CARRION INVERSIONES S.A.', 0, NULL, 1, 'JR. ANTONIO LOBATO NRO. 651', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (168, '20602629750', 'RAINFOREST DC S.A.C.', 0, NULL, 39, 'AV. JAVIER PRADO ESTE NRO. 6519 URB.  PABLO CANEPA  (CRUCE ENTRE INGENIEROS Y JAVIER PRADO OE)', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (169, '20451706323', 'ESTACION DE SERVICIOS VAMA SAC', 0, NULL, 82, 'AV. DE LOS HEROES NRO. 1187 URB.  SAN JUANITO  (AL FRENTE HOSP. MARIA AUXILIADORA)', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (170, '20118180306', 'ESTACION CORMAR S.A.', 1, NULL, 108, 'AV. NICOLAS AYLLON NRO. 3456 URB.  VILLA SANTA ANITA', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 2, 0, 8, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (171, '20487514749', 'GNV DEL NORTE S.A.C', 0, NULL, 97, 'AV. FELIPE SALAVERRY NRO. 930 URB.  PATAZCA  (CERCA A GRIFO)', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 2, 0, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (172, '20457948060', 'XIN XING S.A.', 0, NULL, 1, 'JR. MIRO QUESADA NRO. 1308 LIMA LIMA LIMA', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (173, '20600465237', 'EXPERIENCIA PERUANA SOCIEDAD ANONIMA CERRADA', 0, NULL, 100, 'Jr. Huaraz Nº 1484  Lima Lima Breña', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (174, '20517453618', 'GLMAR SOCIEDAD ANONIMA CERRADA', 0, NULL, 100, 'Av El Triunfo # 210 VMT', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (175, '20517767396', 'ESCOH SOCIEDAD ANONIMA CERRADA - ESCOH SAC', 0, NULL, 100, 'Av. Fernando Leon de Vivero s/n ICA - ICA  ', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (176, '20101285449', 'TRANSPORTES Y SERVICIOS SANTA CRUZ S A', 0, NULL, 101, 'AV. NARANJAL 299 NRO. C INT. 15 URB. NARANJAL-INDUSTRIAL LIMA - LIMA - INDEPENDENCIA', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (177, '20515657119', 'ADMINISTRADORA DE SERVICIOS Y ASOCIADOS S.A.C.', 0, NULL, 102, 'AV. JAIME BAUZATE Y MEZA NRO. 1050 LIMA LIMA LA VICTORIA', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (178, '20604303029', 'ADMINISTRACION DE GRIFOS L&L ONE S.A.C.', 0, NULL, 104, 'JR. MONTE ROSA NRO. 256 INT. 902 URB. CHACARILLA DEL ESTANQUE LIMA LIMA SANTIAGO DE SURCO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (179, '20604302863', 'ADMINISTRACION DE GRIFOS LEP S.A.C.', 0, NULL, 105, 'JR. MONTE ROSA NRO. 256 INT. 902 URB. CHACARILLA DEL ESTANQUE LIMA LIMA SANTIAGO DE SURCO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (180, '10704012964', 'POLO GOMEZ BRYAN MARTIN', 0, NULL, 1, 'PRUEBA', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (181, '20385649194', 'SERVICENTRO TITI S.A.C.', 0, NULL, 106, 'AV. PABLO PATRON NRO. 120 URB. SAN PABLO LIMA LIMA LA VICTORIA', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (182, '20601697531', 'CORPORACION HA SOCIEDAD ANONIMA CERRADA ', 0, NULL, 46, 'AV. ALFREDO MENDIOLA NRO 6810 – SMP ', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (183, '20553368902', 'COMBUSTIBLES LIMPIOS PERUANOS SOCIEDAD ANONIMA CERRADA COLPE S.A.C.', 0, NULL, 109, 'CAL. REAL NRO. 588 CERCADO DE EL TAMBO JUNIN HUANCAYO EL TAMBO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (184, '11111111111', 'RC DEMO', 1, 0, 1, 'RCI', 0x313530313430, 1, 1, 'RC DEMO', '', '55555555', '', 1, 4, 0, 2, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (185, '20549745076', 'GASOCENTRO SANTA ANA S.A.C', 1, NULL, 34, 'AV. LOS PROCERES MZA. D-2 LOTE. 41 URB. SANTA ANA LIMA LIMA LOS OLIVOS', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 2, 0, 9, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (186, '20537901277', 'CORPORACION PYX S.A.C. - CORP PYX S.A.C.', 0, NULL, 110, 'AV. ANGELICA GAMARRA NRO. 1361 INT. 00 LIMA LIMA LOS OLIVOS', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (187, '20547799845', 'CONSORCIO GRIFOS DEL PERU SOCIEDAD ANONIMA CERRADA', 0, NULL, 111, 'AV. EL DERBY NRO. 254 DPTO. 704 LIMA LIMA SANTIAGO DE SURCO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (188, '20565643496', 'GLOBAL FUEL SOCIEDAD ANONIMA', 1, NULL, 112, 'AV. REPUBLICA DE PANAMA NRO. 3591 INT. 401 URB. LIMATAMBO LIMA LIMA SAN ISIDRO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 2, 2, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (189, '2051199502', 'TERPEL PERU S.A.C.', 0, NULL, 113, 'AV. JORGE BASADRE GROHMANN NRO. 347 INT. 1001 (EDIFICIO PATIO CENTRIC)', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (190, '20566238927', 'PERU BUS INTERNACIONAL S.A.', 0, NULL, 114, 'AV. CANTA CALLAO MZA. D LOTE. 11 URB. HUERTOS DEL NARANJAL', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 2, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (191, '20492841014', 'GANAGAS S.A.C.', 0, NULL, 115, 'Av.Los Proceres 655 SANTIAGO DE SURCO LIMA', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (192, '20507248676', 'VIJOGAS S.A.C.', 0, NULL, 116, 'AV. SANTA ROSA NRO. 610 URB. LOS SAUCES LIMA LIMA ATE', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (193, '20538289656', 'CONSORCIO MICE - JOCEGAS', 0, NULL, 1, 'AV. MARIA REICHE NRO. S/N URB. PACHACAMAC LIMA LIMA VILLA EL SALVADOR', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (194, '20493143612', 'ESTACION DE SERVICIOS MASUR S.A.C.', 0, NULL, 118, 'AV. REPUBLICA DE PANAMA N° 4361', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (195, '20510954999', 'GASOCENTRO EL SOL S.A.C.', 0, NULL, 118, 'AV.EL SOL EQ.AV.GUARDIA C NRO. S/N LIMA LIMA CHORRILLOS', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (196, '20510957581', 'SERVICENTRO SHALOM SAC', 0, NULL, 119, 'AV. EL DERBY NRO. 254 INT. 704 URB. EL DERBY DE MONTERRICO LIMA LIMA SANTIAGO DE SURCO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (197, '20519251656', 'INVERSIONES GASSURCO S.A.C', 0, NULL, 118, 'AV. EL DERBY NRO. 254 INT. 704 URB. EL DERBY DE MONTERRICO LIMA LIMA SANTIAGO DE SURCO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (198, '20605899715', 'GO ORUE SAC', 0, NULL, 70, 'Av. El Derby # 254 int 704 Lima - Lima - SANTIAGO DE SURCO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (199, '20517710955', 'SERVICIOS MULTIPLES SANTA CECILIA S.A.C.  SERMUSCE S.A.C.', 0, NULL, 118, 'AV. EL DERBY NRO. 254 INT. 704 URB. EL DERBY DE MONTERRICO LIMA LIMA SANTIAGO DE SURCO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (200, '20606092998', 'CORPORACION JUDY S.A.C.', 1, NULL, 121, 'Nro. . Ex Fundo Naranjal Parcela', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 2, 2, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (201, '20593472244', 'ALLIN GROUP - JAVIER PRADO S.A.', 1, NULL, 1, 'JIRON PINOS 308', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 2, 0, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (202, '20492898717', 'ECOMOVIL SOCIEDAD ANONIMA CERRADA', 1, NULL, 120, 'AV. PROLONGACION PRIMAVERA NRO. 120 INT. A316 LIMA LIMA SANTIAGO DE SURCO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');

-- ----------------------------
-- Table structure for tb_grupos
-- ----------------------------
DROP TABLE IF EXISTS `tb_grupos`;
CREATE TABLE `tb_grupos`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `Nombre`(`nombre`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 122 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_grupos
-- ----------------------------
INSERT INTO `tb_grupos` VALUES (1, 'SIN GRUPO', 1, '2024-12-06 16:34:03', '2024-11-20 18:05:26');
INSERT INTO `tb_grupos` VALUES (2, 'BOTICAS', 1, '2024-12-06 16:34:12', '2024-11-20 18:05:26');
INSERT INTO `tb_grupos` VALUES (3, 'AGUKI', 1, NULL, '2024-11-20 18:05:27');
INSERT INTO `tb_grupos` VALUES (4, 'AJ GROUP', 1, NULL, '2024-11-20 18:05:27');
INSERT INTO `tb_grupos` VALUES (5, 'AJC', 1, NULL, '2024-11-20 18:05:27');
INSERT INTO `tb_grupos` VALUES (6, 'ALTA VIDDA', 1, NULL, '2024-11-20 18:05:27');
INSERT INTO `tb_grupos` VALUES (7, 'AMEL', 1, NULL, '2024-11-20 18:05:28');
INSERT INTO `tb_grupos` VALUES (8, 'AXELL', 1, NULL, '2024-11-20 18:05:28');
INSERT INTO `tb_grupos` VALUES (9, 'BALIAN', 1, '2025-01-31 15:39:36', '2024-11-20 18:05:28');
INSERT INTO `tb_grupos` VALUES (10, 'BOTIFARMA', 1, NULL, '2024-11-20 18:05:28');
INSERT INTO `tb_grupos` VALUES (11, 'CABLE PERU', 1, NULL, '2024-11-20 18:05:29');
INSERT INTO `tb_grupos` VALUES (12, 'CATAFARMA', 1, NULL, '2024-11-20 18:05:29');
INSERT INTO `tb_grupos` VALUES (13, 'CAZTELLANI', 1, NULL, '2024-11-20 18:05:29');
INSERT INTO `tb_grupos` VALUES (14, 'CELESTE', 1, NULL, '2024-11-20 18:05:29');
INSERT INTO `tb_grupos` VALUES (15, 'CILAMSAC', 1, NULL, '2024-11-20 18:05:30');
INSERT INTO `tb_grupos` VALUES (16, 'CLINICA CORAZON DE JESUS', 1, NULL, '2024-11-20 18:05:30');
INSERT INTO `tb_grupos` VALUES (17, 'COFARSA', 1, NULL, '2024-11-20 18:05:30');
INSERT INTO `tb_grupos` VALUES (18, 'CORGAS', 1, NULL, '2024-11-20 18:05:30');
INSERT INTO `tb_grupos` VALUES (19, 'CRUZ VERDE', 1, NULL, '2024-11-20 18:05:31');
INSERT INTO `tb_grupos` VALUES (20, 'DELTA', 1, NULL, '2024-11-20 18:05:31');
INSERT INTO `tb_grupos` VALUES (21, 'DENVER', 1, NULL, '2024-11-20 18:05:31');
INSERT INTO `tb_grupos` VALUES (22, 'DIESEL MAX', 1, NULL, '2024-11-20 18:05:31');
INSERT INTO `tb_grupos` VALUES (23, 'DISFARMED', 1, NULL, '2024-11-20 18:05:32');
INSERT INTO `tb_grupos` VALUES (24, 'DROFAR', 1, NULL, '2024-11-20 18:05:32');
INSERT INTO `tb_grupos` VALUES (25, 'DUOGAS', 1, NULL, '2024-11-20 18:05:32');
INSERT INTO `tb_grupos` VALUES (26, 'DUVAL', 1, NULL, '2024-11-20 18:05:32');
INSERT INTO `tb_grupos` VALUES (27, 'EBENEZER', 1, NULL, '2024-11-20 18:05:33');
INSERT INTO `tb_grupos` VALUES (28, 'ECO TRADING', 1, NULL, '2024-11-20 18:05:33');
INSERT INTO `tb_grupos` VALUES (29, 'EDS LURIN', 1, NULL, '2024-11-20 18:05:33');
INSERT INTO `tb_grupos` VALUES (30, 'EDS NIAGARA', 1, NULL, '2024-11-20 18:05:33');
INSERT INTO `tb_grupos` VALUES (31, 'ESTACION VICTORIA', 1, NULL, '2024-11-20 18:05:34');
INSERT INTO `tb_grupos` VALUES (32, 'FARMA SAN AGUSTIN', 1, NULL, '2024-11-20 18:05:34');
INSERT INTO `tb_grupos` VALUES (33, 'FARMACIA SAN FRANCISCO', 1, NULL, '2024-11-20 18:05:34');
INSERT INTO `tb_grupos` VALUES (34, 'GANAJUR', 1, NULL, '2024-11-20 18:05:34');
INSERT INTO `tb_grupos` VALUES (35, 'GAS DIEGO', 1, NULL, '2024-11-20 18:05:35');
INSERT INTO `tb_grupos` VALUES (36, 'GASBEL', 1, NULL, '2024-11-20 18:05:35');
INSERT INTO `tb_grupos` VALUES (37, 'GASOCENTRO LIMA SUR', 1, NULL, '2024-11-20 18:05:35');
INSERT INTO `tb_grupos` VALUES (38, 'GASORED', 1, NULL, '2024-11-20 18:05:35');
INSERT INTO `tb_grupos` VALUES (39, 'GESA', 1, NULL, '2024-11-20 18:05:36');
INSERT INTO `tb_grupos` VALUES (40, 'GRIFO SANTO DOMINGO', 1, NULL, '2024-11-20 18:05:36');
INSERT INTO `tb_grupos` VALUES (41, 'GRIFO TRAPICHE', 1, NULL, '2024-11-20 18:05:36');
INSERT INTO `tb_grupos` VALUES (42, 'GRUPO INTIFARMA', 1, NULL, '2024-11-20 18:05:36');
INSERT INTO `tb_grupos` VALUES (43, 'HERCO', 1, NULL, '2024-11-20 18:05:37');
INSERT INTO `tb_grupos` VALUES (44, 'HEVALFAR', 1, NULL, '2024-11-20 18:05:37');
INSERT INTO `tb_grupos` VALUES (45, 'HUARAL GAS', 1, NULL, '2024-11-20 18:05:37');
INSERT INTO `tb_grupos` VALUES (46, 'HYA', 1, NULL, '2024-11-20 18:05:37');
INSERT INTO `tb_grupos` VALUES (47, 'INVERSIONES JIARA', 1, NULL, '2024-11-20 18:05:38');
INSERT INTO `tb_grupos` VALUES (48, 'JEMMS', 1, NULL, '2024-11-20 18:05:38');
INSERT INTO `tb_grupos` VALUES (49, 'JU-EDGAR', 1, NULL, '2024-11-20 18:05:38');
INSERT INTO `tb_grupos` VALUES (50, 'JU-ELSA', 1, NULL, '2024-11-20 18:05:38');
INSERT INTO `tb_grupos` VALUES (51, 'JU-JACQUELINE', 1, NULL, '2024-11-20 18:05:39');
INSERT INTO `tb_grupos` VALUES (52, 'JULCAN', 1, NULL, '2024-11-20 18:05:39');
INSERT INTO `tb_grupos` VALUES (53, 'LAS AMERICAS', 1, NULL, '2024-11-20 18:05:39');
INSERT INTO `tb_grupos` VALUES (54, 'LAS BELENES', 1, NULL, '2024-11-20 18:05:39');
INSERT INTO `tb_grupos` VALUES (55, 'LIMABANDA', 1, NULL, '2024-11-20 18:05:40');
INSERT INTO `tb_grupos` VALUES (56, 'LIVORNO', 1, NULL, '2024-11-20 18:05:40');
INSERT INTO `tb_grupos` VALUES (57, 'LOS ROSALES', 1, NULL, '2024-11-20 18:05:40');
INSERT INTO `tb_grupos` VALUES (58, 'LUMARCO', 1, NULL, '2024-11-20 18:05:41');
INSERT INTO `tb_grupos` VALUES (59, 'LUXOR', 1, NULL, '2024-11-20 18:05:41');
INSERT INTO `tb_grupos` VALUES (60, 'MABAFARMA', 1, NULL, '2024-11-20 18:05:41');
INSERT INTO `tb_grupos` VALUES (61, 'MANDUJANO', 1, NULL, '2024-11-20 18:05:41');
INSERT INTO `tb_grupos` VALUES (62, 'MANUEL IGREDA', 1, NULL, '2024-11-20 18:05:42');
INSERT INTO `tb_grupos` VALUES (63, 'MASGAS', 1, NULL, '2024-11-20 18:05:42');
INSERT INTO `tb_grupos` VALUES (64, 'MASTER', 1, NULL, '2024-11-20 18:05:42');
INSERT INTO `tb_grupos` VALUES (65, 'MATIAS Y ALEXA', 1, NULL, '2024-11-20 18:05:42');
INSERT INTO `tb_grupos` VALUES (66, 'MIDAS', 1, NULL, '2024-11-20 18:05:43');
INSERT INTO `tb_grupos` VALUES (67, 'NESTOR SALCEDO', 1, NULL, '2024-11-20 18:05:43');
INSERT INTO `tb_grupos` VALUES (68, 'NG FARMA', 1, NULL, '2024-11-20 18:05:43');
INSERT INTO `tb_grupos` VALUES (69, 'ORFUSAC', 1, NULL, '2024-11-20 18:05:43');
INSERT INTO `tb_grupos` VALUES (70, 'ORUE', 1, NULL, '2024-11-20 18:05:44');
INSERT INTO `tb_grupos` VALUES (71, 'OVALO', 1, NULL, '2024-11-20 18:05:44');
INSERT INTO `tb_grupos` VALUES (72, 'PETRO LUMARA', 1, NULL, '2024-11-20 18:05:44');
INSERT INTO `tb_grupos` VALUES (73, 'PETROAMERICA', 1, NULL, '2024-11-20 18:05:44');
INSERT INTO `tb_grupos` VALUES (74, 'PETROCARGO', 1, NULL, '2024-11-20 18:05:45');
INSERT INTO `tb_grupos` VALUES (75, 'PIHUICHO', 1, NULL, '2024-11-20 18:05:45');
INSERT INTO `tb_grupos` VALUES (76, 'PRIMAX', 1, NULL, '2024-11-20 18:05:45');
INSERT INTO `tb_grupos` VALUES (77, 'PRIMED', 1, NULL, '2024-11-20 18:05:45');
INSERT INTO `tb_grupos` VALUES (78, 'RAMSAN', 1, NULL, '2024-11-20 18:05:46');
INSERT INTO `tb_grupos` VALUES (79, 'REPSOL', 1, NULL, '2024-11-20 18:05:46');
INSERT INTO `tb_grupos` VALUES (80, 'RICSA', 1, NULL, '2024-11-20 18:05:46');
INSERT INTO `tb_grupos` VALUES (81, 'RIO BRANCO', 1, NULL, '2024-11-20 18:05:46');
INSERT INTO `tb_grupos` VALUES (82, 'SAN JUANITO', 1, NULL, '2024-11-20 18:05:47');
INSERT INTO `tb_grupos` VALUES (83, 'SANTA ROSA', 1, NULL, '2024-11-20 18:05:47');
INSERT INTO `tb_grupos` VALUES (84, 'SCHOII', 1, NULL, '2024-11-20 18:05:47');
INSERT INTO `tb_grupos` VALUES (85, 'SELMAC', 1, NULL, '2024-11-20 18:05:47');
INSERT INTO `tb_grupos` VALUES (86, 'SERVIGRIFOS', 1, NULL, '2024-11-20 18:05:48');
INSERT INTO `tb_grupos` VALUES (87, 'SERVITOR', 1, NULL, '2024-11-20 18:05:48');
INSERT INTO `tb_grupos` VALUES (88, 'SHICHI FUKU', 1, NULL, '2024-11-20 18:05:48');
INSERT INTO `tb_grupos` VALUES (89, 'SIROCCO', 1, NULL, '2024-11-20 18:05:49');
INSERT INTO `tb_grupos` VALUES (90, 'SMILE', 1, NULL, '2024-11-20 18:05:49');
INSERT INTO `tb_grupos` VALUES (91, 'TRIVEÑO', 1, NULL, '2024-11-20 18:05:49');
INSERT INTO `tb_grupos` VALUES (92, 'UCHIYAMA', 1, NULL, '2024-11-20 18:05:49');
INSERT INTO `tb_grupos` VALUES (93, 'UNOGAS', 1, NULL, '2024-11-20 18:05:50');
INSERT INTO `tb_grupos` VALUES (94, 'VITAFARMA', 1, NULL, '2024-11-20 18:05:50');
INSERT INTO `tb_grupos` VALUES (95, 'BOLIVAR S.A.', 1, NULL, '2024-11-20 18:05:50');
INSERT INTO `tb_grupos` VALUES (96, 'WO SA', 1, NULL, '2024-11-20 18:05:50');
INSERT INTO `tb_grupos` VALUES (97, 'GASCOP', 1, NULL, '2024-11-20 18:05:51');
INSERT INTO `tb_grupos` VALUES (98, ' XIN XONG', 1, NULL, '2024-11-20 18:05:51');
INSERT INTO `tb_grupos` VALUES (99, 'OFICINA', 1, NULL, '2024-11-20 18:05:51');
INSERT INTO `tb_grupos` VALUES (100, 'COPETROL', 1, NULL, '2024-11-20 18:05:51');
INSERT INTO `tb_grupos` VALUES (101, 'SANTA CRUZ', 1, NULL, '2024-11-20 18:05:52');
INSERT INTO `tb_grupos` VALUES (102, 'ASSA', 1, NULL, '2024-11-20 18:05:52');
INSERT INTO `tb_grupos` VALUES (103, 'INTRASERV', 1, NULL, '2024-11-20 18:05:52');
INSERT INTO `tb_grupos` VALUES (104, 'ADMINISTRACION DE GRIFOS LLONE S.A.C', 1, NULL, '2024-11-20 18:05:53');
INSERT INTO `tb_grupos` VALUES (105, 'EESS PICORP', 1, NULL, '2024-11-20 18:05:53');
INSERT INTO `tb_grupos` VALUES (106, 'TITI', 1, NULL, '2024-11-20 18:05:53');
INSERT INTO `tb_grupos` VALUES (107, 'TRAILER GAS SAC', 1, NULL, '2024-11-20 18:05:53');
INSERT INTO `tb_grupos` VALUES (108, 'ESTACION CORMAR S.A.', 1, NULL, '2024-11-20 18:05:54');
INSERT INTO `tb_grupos` VALUES (109, 'COLPE S.A.C', 1, NULL, '2024-11-20 18:05:54');
INSERT INTO `tb_grupos` VALUES (110, 'CORPORACION PYX', 1, NULL, '2024-11-20 18:05:54');
INSERT INTO `tb_grupos` VALUES (111, 'CONSORCIOGRIFOS DEL PERU S.A.C', 1, NULL, '2024-11-20 18:05:54');
INSERT INTO `tb_grupos` VALUES (112, 'AVA SAC', 1, NULL, '2024-11-20 18:05:55');
INSERT INTO `tb_grupos` VALUES (113, 'Terpel Gazel', 1, NULL, '2024-11-20 18:05:55');
INSERT INTO `tb_grupos` VALUES (114, 'PERU BUS', 1, NULL, '2024-11-20 18:05:55');
INSERT INTO `tb_grupos` VALUES (115, 'GANAGAS SAC', 1, NULL, '2024-11-20 18:05:55');
INSERT INTO `tb_grupos` VALUES (116, 'VijoGas', 1, NULL, '2024-11-20 18:05:56');
INSERT INTO `tb_grupos` VALUES (117, 'CONSORCIO MICE - JOSEGAS', 1, NULL, '2024-11-20 18:05:56');
INSERT INTO `tb_grupos` VALUES (118, 'GO', 1, NULL, '2024-11-20 18:05:56');
INSERT INTO `tb_grupos` VALUES (119, 'SHALOM', 1, NULL, '2024-11-20 18:05:56');
INSERT INTO `tb_grupos` VALUES (120, 'ECOMOVIL', 1, NULL, '2024-12-30 11:48:01');
INSERT INTO `tb_grupos` VALUES (121, 'CORPORACION JUDY SAC', 1, NULL, '2024-12-30 11:48:04');

-- ----------------------------
-- Table structure for tb_inc_asignadas
-- ----------------------------
DROP TABLE IF EXISTS `tb_inc_asignadas`;
CREATE TABLE `tb_inc_asignadas`  (
  `id_asignadas` int(11) NOT NULL AUTO_INCREMENT,
  `cod_incidencia` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `creador` int(11) NOT NULL,
  `fecha` date NULL DEFAULT NULL,
  `hora` time(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_asignadas`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_inc_asignadas
-- ----------------------------
INSERT INTO `tb_inc_asignadas` VALUES (1, 'INC-00000001', 3, 1, NULL, NULL, NULL, '2024-12-30 15:45:57');
INSERT INTO `tb_inc_asignadas` VALUES (2, 'INC-00000003', 3, 1, '2024-12-30', '15:54:01', '2024-12-30 15:54:01', '2024-12-30 15:54:01');
INSERT INTO `tb_inc_asignadas` VALUES (3, 'INC-00000004', 4, 1, '2024-12-30', '16:14:16', '2024-12-30 16:14:16', '2024-12-30 16:14:16');
INSERT INTO `tb_inc_asignadas` VALUES (4, 'INC-00000005', 5, 1, NULL, NULL, NULL, '2025-01-06 17:57:28');
INSERT INTO `tb_inc_asignadas` VALUES (5, 'INC-00000006', 5, 1, NULL, NULL, NULL, '2025-01-12 17:53:04');
INSERT INTO `tb_inc_asignadas` VALUES (6, 'INC-00000008', 1, 1, '2025-01-12', '22:19:55', '2025-01-12 22:19:55', '2025-01-12 22:19:55');
INSERT INTO `tb_inc_asignadas` VALUES (7, 'INC-00000002', 3, 1, '2025-01-17', '16:37:56', '2025-01-17 16:37:56', '2025-01-17 16:37:56');
INSERT INTO `tb_inc_asignadas` VALUES (8, 'INC-00000007', 3, 1, '2025-01-17', '17:00:50', '2025-01-17 17:00:50', '2025-01-17 17:00:50');
INSERT INTO `tb_inc_asignadas` VALUES (9, 'INC-00000009', 5, 1, '2025-01-31', '17:20:00', '2025-01-31 17:20:00', '2025-01-31 17:20:00');
INSERT INTO `tb_inc_asignadas` VALUES (10, 'INC-00000012', 3, 1, NULL, NULL, NULL, '2025-02-02 17:16:04');
INSERT INTO `tb_inc_asignadas` VALUES (11, 'INC-00000013', 3, 1, NULL, NULL, NULL, '2025-02-02 17:39:02');
INSERT INTO `tb_inc_asignadas` VALUES (12, 'INC-00000011', 4, 1, '2025-02-17', '14:01:16', NULL, '2025-02-17 14:01:16');
INSERT INTO `tb_inc_asignadas` VALUES (14, 'INC-00000013', 4, 1, '2025-02-17', '14:50:05', NULL, '2025-02-17 14:50:05');
INSERT INTO `tb_inc_asignadas` VALUES (15, 'INC-00000014', 5, 1, '2025-02-17', '14:50:59', NULL, '2025-02-17 14:50:59');
INSERT INTO `tb_inc_asignadas` VALUES (16, 'INC-00000016', 3, 1, '2025-02-17', '15:02:54', NULL, '2025-02-17 15:02:54');
INSERT INTO `tb_inc_asignadas` VALUES (17, 'INC-00000017', 5, 1, '2025-02-26', '12:02:43', NULL, '2025-02-26 12:02:43');
INSERT INTO `tb_inc_asignadas` VALUES (18, 'INC-00000015', 3, 1, '2025-02-26', '14:39:21', NULL, '2025-02-26 14:39:21');
INSERT INTO `tb_inc_asignadas` VALUES (22, 'INC-00000018', 5, 1, '2025-03-01', '14:49:16', NULL, '2025-03-01 14:49:16');
INSERT INTO `tb_inc_asignadas` VALUES (23, 'INC-00000019', 3, 1, '2025-03-03', '10:00:59', NULL, '2025-03-03 10:00:59');
INSERT INTO `tb_inc_asignadas` VALUES (24, 'INC-00000020', 3, 1, '2025-03-05', '12:25:42', NULL, '2025-03-05 12:25:42');
INSERT INTO `tb_inc_asignadas` VALUES (25, 'INC-00000027', 3, 1, '2025-03-05', '16:50:45', NULL, '2025-03-05 16:50:45');

-- ----------------------------
-- Table structure for tb_inc_seguimiento
-- ----------------------------
DROP TABLE IF EXISTS `tb_inc_seguimiento`;
CREATE TABLE `tb_inc_seguimiento`  (
  `id_seguimiento` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `cod_incidencia` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `fecha` date NULL DEFAULT NULL,
  `hora` time(0) NULL DEFAULT NULL,
  `estado` tinyint(1) NULL DEFAULT 0 COMMENT '0: Iniciado / 1: Finalizado',
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_seguimiento`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 37 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_inc_seguimiento
-- ----------------------------
INSERT INTO `tb_inc_seguimiento` VALUES (1, 1, 'INC-00000003', '2024-12-30', '16:17:42', 0, NULL, '2024-12-30 16:17:42');
INSERT INTO `tb_inc_seguimiento` VALUES (2, 1, 'INC-00000004', '2025-01-06', '17:57:52', 0, NULL, '2025-01-06 17:57:52');
INSERT INTO `tb_inc_seguimiento` VALUES (3, 1, 'INC-00000003', '2025-01-12', '16:15:15', 1, NULL, '2025-01-12 16:15:16');
INSERT INTO `tb_inc_seguimiento` VALUES (4, 1, 'INC-00000005', '2025-01-12', '17:53:12', 0, NULL, '2025-01-12 17:53:12');
INSERT INTO `tb_inc_seguimiento` VALUES (5, 1, 'INC-00000004', '2025-01-12', '21:47:09', 1, NULL, '2025-01-12 21:47:10');
INSERT INTO `tb_inc_seguimiento` VALUES (6, 1, 'INC-00000005', '2025-01-12', '21:49:40', 1, NULL, '2025-01-12 21:49:41');
INSERT INTO `tb_inc_seguimiento` VALUES (7, 1, 'INC-00000005', '2025-01-12', '22:05:35', 1, NULL, '2025-01-12 22:05:35');
INSERT INTO `tb_inc_seguimiento` VALUES (8, 1, 'INC-00000006', '2025-01-12', '22:05:59', 0, NULL, '2025-01-12 22:05:59');
INSERT INTO `tb_inc_seguimiento` VALUES (9, 1, 'INC-00000006', '2025-01-12', '22:08:33', 1, NULL, '2025-01-12 22:08:34');
INSERT INTO `tb_inc_seguimiento` VALUES (10, 1, 'INC-00000008', '2025-01-12', '22:20:49', 0, NULL, '2025-01-12 22:20:49');
INSERT INTO `tb_inc_seguimiento` VALUES (11, 1, 'INC-00000008', '2025-01-12', '22:48:46', 1, NULL, '2025-01-12 22:48:47');
INSERT INTO `tb_inc_seguimiento` VALUES (12, 1, 'INC-00000001', '2025-01-17', '16:39:34', 0, NULL, '2025-01-17 16:39:34');
INSERT INTO `tb_inc_seguimiento` VALUES (13, 1, 'INC-00000002', '2025-01-17', '16:40:22', 0, NULL, '2025-01-17 16:40:22');
INSERT INTO `tb_inc_seguimiento` VALUES (14, 1, 'INC-00000001', '2025-01-17', '16:41:46', 1, NULL, '2025-01-17 16:41:47');
INSERT INTO `tb_inc_seguimiento` VALUES (15, 1, 'INC-00000007', '2025-01-17', '17:01:48', 0, NULL, '2025-01-17 17:01:48');
INSERT INTO `tb_inc_seguimiento` VALUES (16, 1, 'INC-00000007', '2025-01-31', '17:21:20', 1, NULL, '2025-01-31 17:21:20');
INSERT INTO `tb_inc_seguimiento` VALUES (17, 1, 'INC-00000009', '2025-02-01', '21:18:39', 0, NULL, '2025-02-01 21:18:39');
INSERT INTO `tb_inc_seguimiento` VALUES (18, 1, 'INC-00000009', '2025-02-12', '11:22:55', 1, NULL, '2025-02-12 11:22:58');
INSERT INTO `tb_inc_seguimiento` VALUES (19, 1, 'INC-00000011', '2025-02-17', '14:48:39', 0, NULL, '2025-02-17 14:48:39');
INSERT INTO `tb_inc_seguimiento` VALUES (20, 1, 'INC-00000016', '2025-02-17', '15:03:32', 0, NULL, '2025-02-17 15:03:32');
INSERT INTO `tb_inc_seguimiento` VALUES (21, 1, 'INC-00000011', '2025-02-17', '17:54:48', 1, NULL, '2025-02-17 17:54:51');
INSERT INTO `tb_inc_seguimiento` VALUES (22, 3, 'INC-00000012', '2025-02-17', '18:07:30', 0, NULL, '2025-02-17 18:07:30');
INSERT INTO `tb_inc_seguimiento` VALUES (23, 3, 'INC-00000012', '2025-02-17', '18:08:17', 1, NULL, '2025-02-17 18:08:20');
INSERT INTO `tb_inc_seguimiento` VALUES (24, 3, 'INC-00000013', '2025-02-26', '11:45:44', 0, NULL, '2025-02-26 11:45:44');
INSERT INTO `tb_inc_seguimiento` VALUES (25, 3, 'INC-00000013', '2025-02-26', '11:53:20', 1, NULL, '2025-02-26 11:53:23');
INSERT INTO `tb_inc_seguimiento` VALUES (26, 5, 'INC-00000016', '2025-02-26', '12:05:51', 1, NULL, '2025-02-26 12:05:53');
INSERT INTO `tb_inc_seguimiento` VALUES (27, 5, 'INC-00000014', '2025-02-26', '12:06:49', 0, NULL, '2025-02-26 12:06:49');
INSERT INTO `tb_inc_seguimiento` VALUES (28, 5, 'INC-00000014', '2025-02-26', '12:07:05', 1, NULL, '2025-02-26 12:07:07');
INSERT INTO `tb_inc_seguimiento` VALUES (29, 5, 'INC-00000017', '2025-02-26', '12:20:26', 0, NULL, '2025-02-26 12:20:26');
INSERT INTO `tb_inc_seguimiento` VALUES (30, 1, 'INC-00000018', '2025-03-01', '15:00:07', 0, NULL, '2025-03-01 15:00:07');
INSERT INTO `tb_inc_seguimiento` VALUES (31, 1, 'INC-00000017', '2025-03-03', '09:35:14', 1, NULL, '2025-03-03 09:35:17');
INSERT INTO `tb_inc_seguimiento` VALUES (32, 3, 'INC-00000015', '2025-03-03', '11:57:23', 0, NULL, '2025-03-03 11:57:23');
INSERT INTO `tb_inc_seguimiento` VALUES (33, 3, 'INC-00000015', '2025-03-03', '12:14:01', 1, NULL, '2025-03-03 12:14:04');
INSERT INTO `tb_inc_seguimiento` VALUES (34, 3, 'INC-00000019', '2025-03-05', '12:32:36', 0, NULL, '2025-03-05 12:32:36');
INSERT INTO `tb_inc_seguimiento` VALUES (35, 3, 'INC-00000019', '2025-03-05', '12:34:48', 1, NULL, '2025-03-05 12:34:51');
INSERT INTO `tb_inc_seguimiento` VALUES (36, 1, 'INC-00000027', '2025-03-05', '16:51:00', 0, NULL, '2025-03-05 16:51:00');

-- ----------------------------
-- Table structure for tb_incidencias
-- ----------------------------
DROP TABLE IF EXISTS `tb_incidencias`;
CREATE TABLE `tb_incidencias`  (
  `id_incidencia` int(11) NOT NULL AUTO_INCREMENT,
  `cod_incidencia` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ruc_empresa` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_tipo_estacion` int(11) NOT NULL,
  `prioridad` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_tipo_soporte` int(11) NOT NULL,
  `id_tipo_incidencia` int(11) NOT NULL,
  `id_problema` int(11) NOT NULL,
  `id_subproblema` int(11) NOT NULL,
  `id_contacto` int(11) NULL DEFAULT NULL,
  `observacion` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `fecha_informe` date NOT NULL,
  `hora_informe` time(0) NOT NULL,
  `estado_informe` int(1) NULL DEFAULT 0,
  `id_usuario` int(11) NOT NULL,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_incidencia`) USING BTREE,
  UNIQUE INDEX `cod_incidencia`(`cod_incidencia`) USING BTREE COMMENT 'El codigo debe ser unico',
  INDEX `FK_Tipo_Estacion`(`id_tipo_estacion`) USING BTREE,
  INDEX `FK_Tipo_Soporte`(`id_tipo_soporte`) USING BTREE,
  INDEX `FK_Problema`(`id_problema`) USING BTREE,
  INDEX `FK_Sub_Problema`(`id_subproblema`) USING BTREE,
  INDEX `FK_Tipo_Incidendia`(`id_tipo_incidencia`) USING BTREE,
  INDEX `id_contacto`(`id_contacto`) USING BTREE,
  INDEX `FK_Sucursal`(`id_sucursal`) USING BTREE,
  INDEX `ruc_empresa`(`ruc_empresa`) USING BTREE,
  CONSTRAINT `FK_Sucursal` FOREIGN KEY (`id_sucursal`) REFERENCES `tb_sucursales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_incidencias_ibfk_1` FOREIGN KEY (`id_contacto`) REFERENCES `contactos_empresas` (`id_contact`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_incidencias_ibfk_2` FOREIGN KEY (`ruc_empresa`) REFERENCES `empresas` (`ruc_empresa`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_incidencias_ibfk_3` FOREIGN KEY (`id_problema`) REFERENCES `tb_problema` (`id_problema`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_incidencias_ibfk_4` FOREIGN KEY (`id_subproblema`) REFERENCES `tb_subproblema` (`id_subproblema`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_incidencias
-- ----------------------------
INSERT INTO `tb_incidencias` VALUES (1, 'INC-00000001', '20127765279', 17, 1, 'Alta', 1, 2, 1, 1, NULL, 'urgente', '2024-12-30', '15:43:37', 3, 1, 1, NULL, '2024-12-30 15:45:57');
INSERT INTO `tb_incidencias` VALUES (2, 'INC-00000002', '20127765279', 132, 1, 'Alta', 1, 2, 3, 35, 5, 'mega urgente', '2024-12-30', '15:47:24', 2, 1, 0, NULL, '2024-12-30 15:50:09');
INSERT INTO `tb_incidencias` VALUES (3, 'INC-00000003', '20345774042', 18, 1, 'Media', 1, 2, 9, 88, NULL, 'super urgente', '2024-12-30', '15:52:17', 3, 1, 1, NULL, '2024-12-30 15:52:59');
INSERT INTO `tb_incidencias` VALUES (4, 'INC-00000004', '20603850913', 209, 1, 'Alta', 3, 2, 1, 1, NULL, 'urgentisimo', '2024-12-30', '16:13:31', 3, 1, 1, NULL, '2024-12-30 16:13:57');
INSERT INTO `tb_incidencias` VALUES (5, 'INC-00000005', '20127765279', 18, 1, 'Alta', 1, 1, 7, 76, NULL, NULL, '2025-01-06', '17:51:34', 3, 1, 1, NULL, '2025-01-06 17:57:28');
INSERT INTO `tb_incidencias` VALUES (6, 'INC-00000006', '20127765279', 21, 1, 'Alta', 1, 2, 2, 19, NULL, 'urgente', '2025-01-12', '17:51:57', 3, 1, 1, NULL, '2025-01-12 17:53:04');
INSERT INTO `tb_incidencias` VALUES (7, 'INC-00000007', '20513567139', 95, 2, 'Alta', 1, 2, 3, 37, NULL, 'urgente', '2025-01-12', '22:10:21', 3, 1, 1, NULL, '2025-01-12 22:10:41');
INSERT INTO `tb_incidencias` VALUES (8, 'INC-00000008', '20127765279', 19, 2, 'Media', 2, 2, 2, 21, 6, 'urgente', '2025-01-12', '22:10:45', 3, 1, 1, NULL, '2025-01-12 22:11:21');
INSERT INTO `tb_incidencias` VALUES (9, 'INC-00000009', '20517103633', 163, 1, 'Alta', 1, 2, 4, 48, NULL, 'urgente', '2025-01-17', '16:59:14', 3, 1, 1, NULL, '2025-01-17 16:59:57');
INSERT INTO `tb_incidencias` VALUES (10, 'INC-00000010', '20517103633', 163, 1, 'Alta', 1, 2, 1, 1, 7, 'urgente', '2025-01-31', '17:14:03', 0, 1, 1, NULL, '2025-01-31 17:17:44');
INSERT INTO `tb_incidencias` VALUES (11, 'INC-00000011', '20127765279', 20, 1, 'Alta', 1, 2, 1, 2, NULL, NULL, '2025-02-02', '16:44:33', 3, 1, 1, NULL, '2025-02-02 16:45:06');
INSERT INTO `tb_incidencias` VALUES (12, 'INC-00000012', '20345774042', 132, 1, 'Alta', 1, 2, 9, 88, NULL, NULL, '2025-02-02', '17:14:07', 3, 1, 1, NULL, '2025-02-02 17:16:04');
INSERT INTO `tb_incidencias` VALUES (13, 'INC-00000013', '20345774042', 132, 1, 'Alta', 1, 2, 2, 20, 8, NULL, '2025-02-02', '18:38:24', 3, 1, 1, '2025-02-02 18:39:06', '2025-02-02 17:39:02');
INSERT INTO `tb_incidencias` VALUES (14, 'INC-00000014', '20517103633', 163, 1, 'Alta', 3, 2, 2, 20, 9, 'urgente', '2025-02-17', '14:35:21', 3, 1, 1, NULL, '2025-02-17 14:36:22');
INSERT INTO `tb_incidencias` VALUES (15, 'INC-00000015', '20127765279', 23, 1, 'Alta', 2, 2, 2, 19, NULL, 'urgente', '2025-02-17', '14:50:14', 3, 1, 1, NULL, '2025-02-17 14:50:43');
INSERT INTO `tb_incidencias` VALUES (16, 'INC-00000016', '20517103633', 163, 1, 'Alta', 3, 2, 2, 20, NULL, 'urgente', '2025-02-17', '14:59:57', 3, 1, 1, NULL, '2025-02-17 15:01:58');
INSERT INTO `tb_incidencias` VALUES (17, 'INC-00000017', '20127765279', 20, 1, 'Alta', 1, 2, 1, 3, NULL, 'pruebaaa', '2025-02-26', '12:01:45', 4, 1, 1, NULL, '2025-02-26 12:02:43');
INSERT INTO `tb_incidencias` VALUES (18, 'INC-00000018', '20127765279', 18, 1, 'Alta', 1, 2, 1, 2, NULL, 'urgente ahora', '2025-03-01', '14:04:21', 2, 1, 1, '2025-03-01 14:04:24', '2025-03-01 13:23:19');
INSERT INTO `tb_incidencias` VALUES (19, 'INC-00000019', '20127765279', 20, 1, 'Alta', 2, 2, 1, 1, NULL, 'urgentee', '2025-03-03', '10:00:21', 3, 1, 1, NULL, '2025-03-03 10:00:59');
INSERT INTO `tb_incidencias` VALUES (20, 'INC-00000020', '20517103633', 163, 1, 'Alta', 1, 2, 1, 1, NULL, 'urgente', '2025-03-05', '12:24:38', 1, 1, 1, NULL, '2025-03-05 12:25:04');
INSERT INTO `tb_incidencias` VALUES (21, 'INC-00000021', '20603906790', 210, 1, 'Alta', 1, 2, 1, 1, NULL, NULL, '2025-03-05', '15:17:47', 0, 1, 1, NULL, '2025-03-05 15:18:14');
INSERT INTO `tb_incidencias` VALUES (22, 'INC-00000022', '20530743919', 174, 1, 'Alta', 1, 2, 1, 1, NULL, NULL, '2025-03-05', '15:18:19', 0, 1, 1, NULL, '2025-03-05 15:18:59');
INSERT INTO `tb_incidencias` VALUES (23, 'INC-00000023', '20517103633', 163, 1, 'Alta', 2, 2, 2, 20, NULL, NULL, '2025-03-05', '15:51:15', 0, 1, 1, NULL, '2025-03-05 15:51:27');
INSERT INTO `tb_incidencias` VALUES (24, 'INC-00000024', '20513567139', 94, 1, 'Alta', 1, 2, 1, 3, NULL, NULL, '2025-03-05', '15:51:39', 0, 1, 1, NULL, '2025-03-05 15:51:54');
INSERT INTO `tb_incidencias` VALUES (25, 'INC-00000025', '20517103633', 163, 1, 'Alta', 1, 2, 1, 1, NULL, 'urgente', '2025-03-05', '15:56:50', 0, 1, 1, NULL, '2025-03-05 15:57:07');
INSERT INTO `tb_incidencias` VALUES (26, 'INC-00000026', '20127765279', 17, 1, 'Alta', 1, 2, 2, 20, NULL, NULL, '2025-03-05', '15:57:53', 0, 1, 1, NULL, '2025-03-05 15:58:10');
INSERT INTO `tb_incidencias` VALUES (27, 'INC-00000027', '20127765279', 19, 1, 'Alta', 1, 2, 2, 23, NULL, NULL, '2025-03-05', '16:08:57', 2, 1, 1, NULL, '2025-03-05 16:09:27');

-- ----------------------------
-- Table structure for tb_materiales
-- ----------------------------
DROP TABLE IF EXISTS `tb_materiales`;
CREATE TABLE `tb_materiales`  (
  `id_materiales` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `producto` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `cantidad` int(11) NULL DEFAULT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_materiales`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_materiales
-- ----------------------------
INSERT INTO `tb_materiales` VALUES (1, NULL, 'Jack Tool', NULL, 1, '2024-08-12 14:47:29', '2024-08-12 14:47:29');
INSERT INTO `tb_materiales` VALUES (2, NULL, 'Protector de Manguera Data (05 Mtrs)', NULL, 1, '2024-08-12 14:47:33', '2024-08-12 14:47:33');
INSERT INTO `tb_materiales` VALUES (3, NULL, 'Cable Telefonico (07 Mtrs)', NULL, 1, '2024-08-12 14:47:38', '2024-08-12 14:47:38');
INSERT INTO `tb_materiales` VALUES (4, NULL, 'RJ 45', NULL, 1, '2024-08-12 14:47:43', '2024-08-12 14:47:43');
INSERT INTO `tb_materiales` VALUES (5, NULL, 'RJ 12', NULL, 1, '2024-08-12 14:47:51', '2024-08-12 14:47:51');
INSERT INTO `tb_materiales` VALUES (6, NULL, 'RJ 11', NULL, 1, '2024-08-12 14:47:53', '2024-08-12 14:47:53');
INSERT INTO `tb_materiales` VALUES (7, NULL, 'RJ 9', NULL, 1, '2024-08-12 14:47:56', '2024-08-12 14:47:56');
INSERT INTO `tb_materiales` VALUES (8, NULL, 'Patch cord de Red (1M)', NULL, 1, '2024-08-12 14:48:00', '2024-08-12 14:48:00');
INSERT INTO `tb_materiales` VALUES (9, NULL, 'Patch cord de Red (2M)', NULL, 1, '2024-08-12 14:48:04', '2024-08-12 14:48:04');
INSERT INTO `tb_materiales` VALUES (10, NULL, 'Patch cord de Red (3M)', NULL, 1, '2024-08-12 14:48:06', '2024-08-12 14:48:06');
INSERT INTO `tb_materiales` VALUES (11, NULL, 'Fuente de 12v.', NULL, 1, '2024-08-12 14:48:09', '2024-08-12 14:48:09');
INSERT INTO `tb_materiales` VALUES (12, NULL, 'Fuente de 5v.', NULL, 1, '2024-08-12 14:48:11', '2024-08-12 14:48:11');
INSERT INTO `tb_materiales` VALUES (13, NULL, 'Paq. de Precintos', NULL, 1, '2024-08-12 14:48:14', '2024-08-12 14:48:14');
INSERT INTO `tb_materiales` VALUES (14, NULL, 'Cinta aislante', NULL, 1, '2024-08-12 14:48:18', '2024-08-12 14:48:18');
INSERT INTO `tb_materiales` VALUES (15, NULL, 'USB Serial', NULL, 1, '2024-08-12 14:48:20', '2024-08-12 14:48:20');
INSERT INTO `tb_materiales` VALUES (16, NULL, 'Precinto', NULL, 1, '2024-08-12 14:48:23', '2024-08-12 14:48:23');
INSERT INTO `tb_materiales` VALUES (17, NULL, 'Cinta aislante', NULL, 1, '2024-08-12 14:48:26', '2024-08-12 14:48:26');
INSERT INTO `tb_materiales` VALUES (18, NULL, 'Cable de red Cat5e mts', NULL, 1, '2024-08-12 14:48:28', '2024-08-12 14:48:28');
INSERT INTO `tb_materiales` VALUES (19, NULL, 'Cable vulcanizado Nro14 x  mts', NULL, 1, '2024-08-12 14:48:31', '2024-08-12 14:48:31');
INSERT INTO `tb_materiales` VALUES (20, NULL, 'Fuente impresora Tysso(24v)', NULL, 1, '2024-08-12 14:48:34', '2024-08-12 14:48:34');
INSERT INTO `tb_materiales` VALUES (21, NULL, 'Toma electrica externa', NULL, 1, '2024-08-12 14:48:36', '2024-08-12 14:48:36');
INSERT INTO `tb_materiales` VALUES (22, NULL, 'Cable vulcanizado nro 16', NULL, 1, '2024-08-12 14:48:39', '2024-08-12 14:48:39');
INSERT INTO `tb_materiales` VALUES (23, NULL, 'Bornera', NULL, 1, '2024-08-12 14:48:41', '2024-08-12 14:48:41');
INSERT INTO `tb_materiales` VALUES (24, NULL, 'Compuesto Sellante', NULL, 1, '2024-08-12 14:48:44', '2024-08-12 14:48:44');

-- ----------------------------
-- Table structure for tb_materiales_usados
-- ----------------------------
DROP TABLE IF EXISTS `tb_materiales_usados`;
CREATE TABLE `tb_materiales_usados`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_ordens` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_material` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cod_ordens`(`cod_ordens`) USING BTREE,
  INDEX `id_material`(`id_material`) USING BTREE,
  CONSTRAINT `tb_materiales_usados_ibfk_2` FOREIGN KEY (`id_material`) REFERENCES `tb_materiales` (`id_materiales`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_materiales_usados
-- ----------------------------
INSERT INTO `tb_materiales_usados` VALUES (1, 'ST24-00000001', 2, 1, NULL, '2025-01-12 16:15:16');
INSERT INTO `tb_materiales_usados` VALUES (2, 'ST24-00000001', 4, 3, NULL, '2025-01-12 16:15:16');
INSERT INTO `tb_materiales_usados` VALUES (3, 'ST24-00000002', 3, 2, NULL, '2025-01-12 21:47:10');
INSERT INTO `tb_materiales_usados` VALUES (4, 'ST24-00000004', 3, 2, NULL, '2025-01-12 22:08:34');
INSERT INTO `tb_materiales_usados` VALUES (5, 'ST24-00000006', 4, 1, NULL, '2025-01-12 22:48:47');
INSERT INTO `tb_materiales_usados` VALUES (6, 'ST24-00000007', 2, 1, NULL, '2025-01-17 16:41:47');
INSERT INTO `tb_materiales_usados` VALUES (7, 'ST24-00000008', 1, 1, NULL, '2025-01-31 17:21:20');
INSERT INTO `tb_materiales_usados` VALUES (8, 'ST24-00000008', 4, 2, NULL, '2025-01-31 17:21:20');
INSERT INTO `tb_materiales_usados` VALUES (9, 'ST24-00000009', 18, 1, NULL, '2025-02-12 11:22:56');
INSERT INTO `tb_materiales_usados` VALUES (10, 'ST24-00000013', 2, 2, NULL, '2025-02-17 17:54:51');
INSERT INTO `tb_materiales_usados` VALUES (11, 'ST24-00000020', 2, 1, NULL, '2025-02-17 18:08:20');
INSERT INTO `tb_materiales_usados` VALUES (12, 'ST25-00000021', 3, 1, NULL, '2025-02-26 11:53:23');
INSERT INTO `tb_materiales_usados` VALUES (13, 'ST25-00000023', 2, 1, NULL, '2025-03-03 09:35:17');
INSERT INTO `tb_materiales_usados` VALUES (14, 'ST25-00000023', 4, 2, NULL, '2025-03-03 09:35:17');
INSERT INTO `tb_materiales_usados` VALUES (15, 'ST25-00000024', 3, 1, NULL, '2025-03-03 12:14:04');
INSERT INTO `tb_materiales_usados` VALUES (16, 'ST25-00000024', 6, 2, NULL, '2025-03-03 12:14:04');
INSERT INTO `tb_materiales_usados` VALUES (17, 'ST25-00000025', 1, 1, NULL, '2025-03-05 12:34:51');

-- ----------------------------
-- Table structure for tb_menu
-- ----------------------------
DROP TABLE IF EXISTS `tb_menu`;
CREATE TABLE `tb_menu`  (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ruta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `submenu` tinyint(1) NULL DEFAULT 0,
  `eliminado` tinyint(1) NULL DEFAULT 0,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id_menu`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_menu
-- ----------------------------
INSERT INTO `tb_menu` VALUES (1, 'Incidencias', 'fas fa-house', '/incidencias/registradas', 0, 0, 1, '2025-02-01 18:09:27', '2025-01-31 21:00:34');
INSERT INTO `tb_menu` VALUES (2, 'Incidencias Resueltas', 'fas fa-list-check', '/incidencias/resueltas', 0, 0, 1, '2025-02-01 15:51:23', '2025-01-31 21:00:34');
INSERT INTO `tb_menu` VALUES (3, 'Visitas', 'fas fa-person-biking', 'ControlVisitas', 1, 0, 1, '2025-02-01 18:09:16', '2025-02-01 16:30:49');
INSERT INTO `tb_menu` VALUES (4, 'Empresas', 'far fa-building', 'ControlEmpresas', 1, 0, 1, NULL, '2025-02-01 18:12:14');
INSERT INTO `tb_menu` VALUES (5, 'Control de Usuarios', 'fas fa-user-group', 'ControlUsarios', 1, 0, 1, NULL, '2025-02-01 18:14:02');
INSERT INTO `tb_menu` VALUES (6, 'Mantenimientos', 'fas fa-gears', 'ControlMantenimientos', 1, 0, 1, NULL, '2025-02-01 18:15:04');
INSERT INTO `tb_menu` VALUES (7, 'Sistema', 'fas fa-laptop-code', 'Sistema', 1, 0, 1, NULL, '2025-02-10 15:14:55');
INSERT INTO `tb_menu` VALUES (8, 'Buzon Tecnico', 'fas fa-address-book', 'Buzon', 1, 0, 1, NULL, '2025-02-10 16:25:23');

-- ----------------------------
-- Table structure for tb_orden_correlativo
-- ----------------------------
DROP TABLE IF EXISTS `tb_orden_correlativo`;
CREATE TABLE `tb_orden_correlativo`  (
  `id_cor` int(11) NOT NULL AUTO_INCREMENT,
  `num_orden` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_cor`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_orden_correlativo
-- ----------------------------
INSERT INTO `tb_orden_correlativo` VALUES (2, 'ST24-00000001', NULL, '2025-01-12 16:15:16');
INSERT INTO `tb_orden_correlativo` VALUES (3, 'ST24-00000002', NULL, '2025-01-12 21:47:10');
INSERT INTO `tb_orden_correlativo` VALUES (4, 'ST24-00000003', NULL, '2025-01-12 21:49:41');
INSERT INTO `tb_orden_correlativo` VALUES (6, 'ST24-00000004', NULL, '2025-01-12 22:08:34');
INSERT INTO `tb_orden_correlativo` VALUES (7, 'ST24-00000006', NULL, '2025-01-12 22:48:47');
INSERT INTO `tb_orden_correlativo` VALUES (8, 'ST24-00000007', NULL, '2025-01-17 16:41:47');
INSERT INTO `tb_orden_correlativo` VALUES (9, 'ST24-00000008', NULL, '2025-01-31 17:21:20');
INSERT INTO `tb_orden_correlativo` VALUES (13, 'ST24-00000009', NULL, '2025-02-12 11:22:58');
INSERT INTO `tb_orden_correlativo` VALUES (20, 'ST24-00000013', NULL, '2025-02-17 17:54:51');
INSERT INTO `tb_orden_correlativo` VALUES (21, 'ST24-00000020', NULL, '2025-02-17 18:08:20');
INSERT INTO `tb_orden_correlativo` VALUES (22, 'ST24-00000021', NULL, '2025-02-17 18:08:20');
INSERT INTO `tb_orden_correlativo` VALUES (23, 'ST24-00000022', NULL, '2025-02-26 14:02:19');
INSERT INTO `tb_orden_correlativo` VALUES (24, 'ST25-00000023', NULL, NULL);
INSERT INTO `tb_orden_correlativo` VALUES (25, 'ST25-00000024', NULL, '2025-03-03 12:14:04');
INSERT INTO `tb_orden_correlativo` VALUES (26, 'ST25-00000025', NULL, '2025-03-05 12:34:51');

-- ----------------------------
-- Table structure for tb_orden_servicio
-- ----------------------------
DROP TABLE IF EXISTS `tb_orden_servicio`;
CREATE TABLE `tb_orden_servicio`  (
  `id_ordens` int(11) NOT NULL AUTO_INCREMENT,
  `cod_ordens` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cod_incidencia` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `observaciones` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `recomendaciones` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_contacto` int(11) NULL DEFAULT NULL,
  `fecha_f` date NOT NULL,
  `hora_f` time(0) NOT NULL,
  `codigo_aviso` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_ordens`) USING BTREE,
  INDEX `cod_ordens`(`cod_ordens`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_orden_servicio
-- ----------------------------
INSERT INTO `tb_orden_servicio` VALUES (1, 'ST24-00000001', 'INC-00000003', 'disco quemado', 'cambiar', 3, '2025-01-12', '16:15:15', 'gdfgdfgdfs', 1, '2025-01-12 21:25:16', '2025-01-12 16:15:16');
INSERT INTO `tb_orden_servicio` VALUES (2, 'ST24-00000002', 'INC-00000004', 'jacktool roto', 'cambiarlo', NULL, '2025-01-12', '21:47:09', '3', 1, NULL, '2025-01-12 21:47:10');
INSERT INTO `tb_orden_servicio` VALUES (3, 'ST24-00000003', 'INC-00000005', 'TERMINAL TOUCH sucio', 'Limpiar', NULL, '2025-01-12', '22:05:35', NULL, 1, NULL, '2025-01-12 22:05:35');
INSERT INTO `tb_orden_servicio` VALUES (5, 'ST24-00000004', 'INC-00000006', 'TERMINAL TOUCH sucio', 'Limpiar', 9, '2025-01-12', '22:08:33', 'sdfsdfasdfasd', 1, '2025-01-12 22:09:14', '2025-01-12 22:08:34');
INSERT INTO `tb_orden_servicio` VALUES (6, 'ST24-00000006', 'INC-00000008', 'impresora sin tinta', 'llenar la tinta', 7, '2025-01-12', '22:48:46', 'dsfsdf', 1, NULL, '2025-01-12 22:48:47');
INSERT INTO `tb_orden_servicio` VALUES (7, 'ST24-00000007', 'INC-00000001', 'prueba', 'prue2', NULL, '2025-01-17', '16:41:46', 'qwerty', 1, '2025-01-17 16:42:45', '2025-01-17 16:41:47');
INSERT INTO `tb_orden_servicio` VALUES (8, 'ST24-00000008', 'INC-00000007', 'touch', 'cambiar', NULL, '2025-01-31', '17:21:20', '3', 1, NULL, '2025-01-31 17:21:20');
INSERT INTO `tb_orden_servicio` VALUES (9, 'ST24-00000009', 'INC-00000009', 'no comunicaba', 'debe comunicar', 4, '2025-02-12', '11:22:55', '3', 1, NULL, '2025-02-12 11:22:58');
INSERT INTO `tb_orden_servicio` VALUES (16, 'ST24-00000013', 'INC-00000011', 'no lee', 'tiene que leer', 4, '2025-02-17', '17:54:48', 'qwerty', 1, '2025-02-26 11:44:11', '2025-02-17 17:54:51');
INSERT INTO `tb_orden_servicio` VALUES (17, 'ST24-00000020', 'INC-00000012', 'observacion', 'recomendacion', 4, '2025-02-17', '18:08:17', '3', 1, NULL, '2025-02-17 18:08:20');
INSERT INTO `tb_orden_servicio` VALUES (18, 'ST25-00000021', 'INC-00000013', 'no imprime', 'tiene que imprimir', 4, '2025-02-26', '11:53:20', '3', 1, NULL, '2025-02-26 11:53:23');
INSERT INTO `tb_orden_servicio` VALUES (19, 'ST25-00000021', 'INC-00000016', 'ok', 'ok', 4, '2025-02-26', '12:05:51', '3', 1, NULL, '2025-02-26 12:05:53');
INSERT INTO `tb_orden_servicio` VALUES (20, 'ST25-00000022', 'INC-00000014', 'ee', 'ee', NULL, '2025-02-26', '12:07:05', '3', 1, NULL, '2025-02-26 12:07:07');
INSERT INTO `tb_orden_servicio` VALUES (21, 'ST25-00000023', 'INC-00000017', 'no lee', 'tiene que leer', NULL, '2025-03-03', '09:35:14', NULL, 1, NULL, '2025-03-03 09:35:17');
INSERT INTO `tb_orden_servicio` VALUES (22, 'ST25-00000024', 'INC-00000015', 'no imprime', 'que imprima', NULL, '2025-03-03', '12:14:01', 'qwerty', 1, '2025-03-03 12:20:36', '2025-03-03 12:14:04');
INSERT INTO `tb_orden_servicio` VALUES (23, 'ST25-00000025', 'INC-00000019', 'no lee', 'debe leer', NULL, '2025-03-05', '12:34:48', 'qwerty', 1, '2025-03-05 12:35:25', '2025-03-05 12:34:51');

-- ----------------------------
-- Table structure for tb_orden_visita
-- ----------------------------
DROP TABLE IF EXISTS `tb_orden_visita`;
CREATE TABLE `tb_orden_visita`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_orden_visita` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_visita` int(11) NULL DEFAULT NULL,
  `fecha_visita` date NULL DEFAULT NULL,
  `hora_inicio` time(0) NULL DEFAULT NULL,
  `hora_fin` time(0) NULL DEFAULT NULL,
  `eliminado` tinyint(1) NULL DEFAULT 0,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_orden_visita
-- ----------------------------
INSERT INTO `tb_orden_visita` VALUES (1, 'VT25-00000001', 9, '2025-02-24', '09:13:26', '09:13:26', 0, '2025-02-24 09:13:26');
INSERT INTO `tb_orden_visita` VALUES (2, 'VT25-00000002', 10, '2025-02-24', '12:18:24', '12:18:24', 0, '2025-02-24 12:18:24');
INSERT INTO `tb_orden_visita` VALUES (5, 'VT25-00000003', 11, '2025-02-24', '15:40:41', '15:40:41', 0, '2025-02-24 15:40:41');
INSERT INTO `tb_orden_visita` VALUES (6, 'VT25-00000004', 14, '2025-02-26', '15:28:18', '15:28:18', 0, '2025-02-26 15:28:18');
INSERT INTO `tb_orden_visita` VALUES (7, 'VT25-00000005', 16, '2025-03-03', '12:30:30', '12:30:30', 0, '2025-03-03 12:30:30');
INSERT INTO `tb_orden_visita` VALUES (8, 'VT25-00000006', 17, '2025-03-05', '12:49:18', '12:49:18', 0, '2025-03-05 12:49:18');

-- ----------------------------
-- Table structure for tb_orden_visita_correlativo
-- ----------------------------
DROP TABLE IF EXISTS `tb_orden_visita_correlativo`;
CREATE TABLE `tb_orden_visita_correlativo`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_orden_visita` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_orden_visita_correlativo
-- ----------------------------
INSERT INTO `tb_orden_visita_correlativo` VALUES (1, 'VT25-00000001', '2025-02-24 09:13:26');
INSERT INTO `tb_orden_visita_correlativo` VALUES (2, 'VT25-00000002', '2025-02-24 12:18:24');
INSERT INTO `tb_orden_visita_correlativo` VALUES (5, 'VT25-00000003', '2025-02-24 15:40:41');
INSERT INTO `tb_orden_visita_correlativo` VALUES (6, 'VT25-00000004', '2025-02-26 15:28:18');
INSERT INTO `tb_orden_visita_correlativo` VALUES (7, 'VT25-00000005', '2025-03-03 12:30:30');
INSERT INTO `tb_orden_visita_correlativo` VALUES (8, 'VT25-00000006', '2025-03-05 12:49:18');

-- ----------------------------
-- Table structure for tb_orden_visita_filas
-- ----------------------------
DROP TABLE IF EXISTS `tb_orden_visita_filas`;
CREATE TABLE `tb_orden_visita_filas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_orden_visita` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `posicion` int(11) NULL DEFAULT NULL,
  `checked` int(11) NULL DEFAULT NULL,
  `descripcion` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 113 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_orden_visita_filas
-- ----------------------------
INSERT INTO `tb_orden_visita_filas` VALUES (1, 'VT25-00000001', 1, 0, NULL, '2025-02-24 09:13:23');
INSERT INTO `tb_orden_visita_filas` VALUES (2, 'VT25-00000001', 2, 0, NULL, '2025-02-24 09:13:23');
INSERT INTO `tb_orden_visita_filas` VALUES (3, 'VT25-00000001', 3, 0, NULL, '2025-02-24 09:13:23');
INSERT INTO `tb_orden_visita_filas` VALUES (4, 'VT25-00000001', 4, 0, NULL, '2025-02-24 09:13:23');
INSERT INTO `tb_orden_visita_filas` VALUES (5, 'VT25-00000001', 5, 0, NULL, '2025-02-24 09:13:23');
INSERT INTO `tb_orden_visita_filas` VALUES (6, 'VT25-00000001', 6, 0, NULL, '2025-02-24 09:13:23');
INSERT INTO `tb_orden_visita_filas` VALUES (7, 'VT25-00000001', 7, 0, NULL, '2025-02-24 09:13:23');
INSERT INTO `tb_orden_visita_filas` VALUES (8, 'VT25-00000001', 8, 0, NULL, '2025-02-24 09:13:23');
INSERT INTO `tb_orden_visita_filas` VALUES (9, 'VT25-00000001', 9, 0, NULL, '2025-02-24 09:13:23');
INSERT INTO `tb_orden_visita_filas` VALUES (10, 'VT25-00000001', 10, 0, NULL, '2025-02-24 09:13:23');
INSERT INTO `tb_orden_visita_filas` VALUES (11, 'VT25-00000001', 11, 1, '16617', '2025-02-24 09:13:23');
INSERT INTO `tb_orden_visita_filas` VALUES (12, 'VT25-00000001', 12, 0, NULL, '2025-02-24 09:13:23');
INSERT INTO `tb_orden_visita_filas` VALUES (13, 'VT25-00000001', 13, 0, NULL, '2025-02-24 09:13:23');
INSERT INTO `tb_orden_visita_filas` VALUES (14, 'VT25-00000001', 14, 0, NULL, '2025-02-24 09:13:23');
INSERT INTO `tb_orden_visita_filas` VALUES (15, 'VT25-00000002', 1, 0, NULL, '2025-02-24 12:18:22');
INSERT INTO `tb_orden_visita_filas` VALUES (16, 'VT25-00000002', 2, 0, NULL, '2025-02-24 12:18:22');
INSERT INTO `tb_orden_visita_filas` VALUES (17, 'VT25-00000002', 3, 0, NULL, '2025-02-24 12:18:22');
INSERT INTO `tb_orden_visita_filas` VALUES (18, 'VT25-00000002', 4, 0, NULL, '2025-02-24 12:18:22');
INSERT INTO `tb_orden_visita_filas` VALUES (19, 'VT25-00000002', 5, 0, NULL, '2025-02-24 12:18:22');
INSERT INTO `tb_orden_visita_filas` VALUES (20, 'VT25-00000002', 6, 0, NULL, '2025-02-24 12:18:22');
INSERT INTO `tb_orden_visita_filas` VALUES (21, 'VT25-00000002', 7, 0, NULL, '2025-02-24 12:18:22');
INSERT INTO `tb_orden_visita_filas` VALUES (22, 'VT25-00000002', 8, 0, NULL, '2025-02-24 12:18:22');
INSERT INTO `tb_orden_visita_filas` VALUES (23, 'VT25-00000002', 9, 0, NULL, '2025-02-24 12:18:22');
INSERT INTO `tb_orden_visita_filas` VALUES (24, 'VT25-00000002', 10, 0, NULL, '2025-02-24 12:18:22');
INSERT INTO `tb_orden_visita_filas` VALUES (25, 'VT25-00000002', 11, 1, '16617', '2025-02-24 12:18:22');
INSERT INTO `tb_orden_visita_filas` VALUES (26, 'VT25-00000002', 12, 0, NULL, '2025-02-24 12:18:22');
INSERT INTO `tb_orden_visita_filas` VALUES (27, 'VT25-00000002', 13, 0, NULL, '2025-02-24 12:18:22');
INSERT INTO `tb_orden_visita_filas` VALUES (28, 'VT25-00000002', 14, 0, NULL, '2025-02-24 12:18:22');
INSERT INTO `tb_orden_visita_filas` VALUES (57, 'VT25-00000003', 1, 0, NULL, '2025-02-24 15:40:39');
INSERT INTO `tb_orden_visita_filas` VALUES (58, 'VT25-00000003', 2, 0, NULL, '2025-02-24 15:40:39');
INSERT INTO `tb_orden_visita_filas` VALUES (59, 'VT25-00000003', 3, 0, NULL, '2025-02-24 15:40:39');
INSERT INTO `tb_orden_visita_filas` VALUES (60, 'VT25-00000003', 4, 0, NULL, '2025-02-24 15:40:39');
INSERT INTO `tb_orden_visita_filas` VALUES (61, 'VT25-00000003', 5, 0, NULL, '2025-02-24 15:40:39');
INSERT INTO `tb_orden_visita_filas` VALUES (62, 'VT25-00000003', 6, 0, NULL, '2025-02-24 15:40:39');
INSERT INTO `tb_orden_visita_filas` VALUES (63, 'VT25-00000003', 7, 1, '16676', '2025-02-24 15:40:39');
INSERT INTO `tb_orden_visita_filas` VALUES (64, 'VT25-00000003', 8, 0, NULL, '2025-02-24 15:40:39');
INSERT INTO `tb_orden_visita_filas` VALUES (65, 'VT25-00000003', 9, 0, NULL, '2025-02-24 15:40:39');
INSERT INTO `tb_orden_visita_filas` VALUES (66, 'VT25-00000003', 10, 0, NULL, '2025-02-24 15:40:39');
INSERT INTO `tb_orden_visita_filas` VALUES (67, 'VT25-00000003', 11, 0, NULL, '2025-02-24 15:40:39');
INSERT INTO `tb_orden_visita_filas` VALUES (68, 'VT25-00000003', 12, 0, NULL, '2025-02-24 15:40:39');
INSERT INTO `tb_orden_visita_filas` VALUES (69, 'VT25-00000003', 13, 0, NULL, '2025-02-24 15:40:39');
INSERT INTO `tb_orden_visita_filas` VALUES (70, 'VT25-00000003', 14, 0, NULL, '2025-02-24 15:40:39');
INSERT INTO `tb_orden_visita_filas` VALUES (71, 'VT25-00000004', 1, 0, NULL, '2025-02-26 15:28:16');
INSERT INTO `tb_orden_visita_filas` VALUES (72, 'VT25-00000004', 2, 0, NULL, '2025-02-26 15:28:16');
INSERT INTO `tb_orden_visita_filas` VALUES (73, 'VT25-00000004', 3, 0, NULL, '2025-02-26 15:28:16');
INSERT INTO `tb_orden_visita_filas` VALUES (74, 'VT25-00000004', 4, 0, NULL, '2025-02-26 15:28:16');
INSERT INTO `tb_orden_visita_filas` VALUES (75, 'VT25-00000004', 5, 0, NULL, '2025-02-26 15:28:16');
INSERT INTO `tb_orden_visita_filas` VALUES (76, 'VT25-00000004', 6, 0, NULL, '2025-02-26 15:28:16');
INSERT INTO `tb_orden_visita_filas` VALUES (77, 'VT25-00000004', 7, 1, '16676', '2025-02-26 15:28:16');
INSERT INTO `tb_orden_visita_filas` VALUES (78, 'VT25-00000004', 8, 0, NULL, '2025-02-26 15:28:16');
INSERT INTO `tb_orden_visita_filas` VALUES (79, 'VT25-00000004', 9, 0, NULL, '2025-02-26 15:28:16');
INSERT INTO `tb_orden_visita_filas` VALUES (80, 'VT25-00000004', 10, 0, NULL, '2025-02-26 15:28:16');
INSERT INTO `tb_orden_visita_filas` VALUES (81, 'VT25-00000004', 11, 0, NULL, '2025-02-26 15:28:16');
INSERT INTO `tb_orden_visita_filas` VALUES (82, 'VT25-00000004', 12, 0, NULL, '2025-02-26 15:28:16');
INSERT INTO `tb_orden_visita_filas` VALUES (83, 'VT25-00000004', 13, 0, NULL, '2025-02-26 15:28:16');
INSERT INTO `tb_orden_visita_filas` VALUES (84, 'VT25-00000004', 14, 0, NULL, '2025-02-26 15:28:16');
INSERT INTO `tb_orden_visita_filas` VALUES (85, 'VT25-00000005', 1, 0, NULL, '2025-03-03 12:30:28');
INSERT INTO `tb_orden_visita_filas` VALUES (86, 'VT25-00000005', 2, 0, NULL, '2025-03-03 12:30:28');
INSERT INTO `tb_orden_visita_filas` VALUES (87, 'VT25-00000005', 3, 0, NULL, '2025-03-03 12:30:28');
INSERT INTO `tb_orden_visita_filas` VALUES (88, 'VT25-00000005', 4, 0, NULL, '2025-03-03 12:30:28');
INSERT INTO `tb_orden_visita_filas` VALUES (89, 'VT25-00000005', 5, 0, NULL, '2025-03-03 12:30:28');
INSERT INTO `tb_orden_visita_filas` VALUES (90, 'VT25-00000005', 6, 0, NULL, '2025-03-03 12:30:28');
INSERT INTO `tb_orden_visita_filas` VALUES (91, 'VT25-00000005', 7, 1, '16676', '2025-03-03 12:30:28');
INSERT INTO `tb_orden_visita_filas` VALUES (92, 'VT25-00000005', 8, 0, NULL, '2025-03-03 12:30:28');
INSERT INTO `tb_orden_visita_filas` VALUES (93, 'VT25-00000005', 9, 0, NULL, '2025-03-03 12:30:28');
INSERT INTO `tb_orden_visita_filas` VALUES (94, 'VT25-00000005', 10, 0, NULL, '2025-03-03 12:30:28');
INSERT INTO `tb_orden_visita_filas` VALUES (95, 'VT25-00000005', 11, 0, NULL, '2025-03-03 12:30:28');
INSERT INTO `tb_orden_visita_filas` VALUES (96, 'VT25-00000005', 12, 0, NULL, '2025-03-03 12:30:28');
INSERT INTO `tb_orden_visita_filas` VALUES (97, 'VT25-00000005', 13, 0, NULL, '2025-03-03 12:30:28');
INSERT INTO `tb_orden_visita_filas` VALUES (98, 'VT25-00000005', 14, 0, NULL, '2025-03-03 12:30:28');
INSERT INTO `tb_orden_visita_filas` VALUES (99, 'VT25-00000006', 1, 0, NULL, '2025-03-05 12:49:15');
INSERT INTO `tb_orden_visita_filas` VALUES (100, 'VT25-00000006', 2, 0, NULL, '2025-03-05 12:49:15');
INSERT INTO `tb_orden_visita_filas` VALUES (101, 'VT25-00000006', 3, 0, NULL, '2025-03-05 12:49:15');
INSERT INTO `tb_orden_visita_filas` VALUES (102, 'VT25-00000006', 4, 0, NULL, '2025-03-05 12:49:15');
INSERT INTO `tb_orden_visita_filas` VALUES (103, 'VT25-00000006', 5, 0, NULL, '2025-03-05 12:49:15');
INSERT INTO `tb_orden_visita_filas` VALUES (104, 'VT25-00000006', 6, 0, NULL, '2025-03-05 12:49:15');
INSERT INTO `tb_orden_visita_filas` VALUES (105, 'VT25-00000006', 7, 1, '16776', '2025-03-05 12:49:15');
INSERT INTO `tb_orden_visita_filas` VALUES (106, 'VT25-00000006', 8, 0, NULL, '2025-03-05 12:49:15');
INSERT INTO `tb_orden_visita_filas` VALUES (107, 'VT25-00000006', 9, 0, NULL, '2025-03-05 12:49:15');
INSERT INTO `tb_orden_visita_filas` VALUES (108, 'VT25-00000006', 10, 0, NULL, '2025-03-05 12:49:15');
INSERT INTO `tb_orden_visita_filas` VALUES (109, 'VT25-00000006', 11, 1, 'windown', '2025-03-05 12:49:15');
INSERT INTO `tb_orden_visita_filas` VALUES (110, 'VT25-00000006', 12, 0, NULL, '2025-03-05 12:49:15');
INSERT INTO `tb_orden_visita_filas` VALUES (111, 'VT25-00000006', 13, 0, NULL, '2025-03-05 12:49:15');
INSERT INTO `tb_orden_visita_filas` VALUES (112, 'VT25-00000006', 14, 0, NULL, '2025-03-05 12:49:15');

-- ----------------------------
-- Table structure for tb_orden_visita_islas
-- ----------------------------
DROP TABLE IF EXISTS `tb_orden_visita_islas`;
CREATE TABLE `tb_orden_visita_islas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_orden_visita` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `isla` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pos` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `impresoras` int(11) NULL DEFAULT NULL,
  `des_impresoras` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lectores` int(11) NULL DEFAULT NULL,
  `des_lector` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jack` int(11) NULL DEFAULT NULL,
  `des_jack` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `voltaje` int(11) NULL DEFAULT NULL,
  `des_voltaje` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `caucho` int(11) NULL DEFAULT NULL,
  `des_caucho` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `mueblepos` int(11) NULL DEFAULT NULL,
  `des_mueblepos` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `mr350` int(11) NULL DEFAULT NULL,
  `des_mr350` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `switch` int(11) NULL DEFAULT NULL,
  `des_switch` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_orden_visita_islas
-- ----------------------------
INSERT INTO `tb_orden_visita_islas` VALUES (1, 'VT25-00000001', NULL, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-02-24 09:13:23');
INSERT INTO `tb_orden_visita_islas` VALUES (2, 'VT25-00000002', NULL, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-02-24 12:18:22');
INSERT INTO `tb_orden_visita_islas` VALUES (3, 'VT25-00000003', '1', '2', 1, '3032324543', 0, NULL, 1, '2', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-02-24 15:11:11');
INSERT INTO `tb_orden_visita_islas` VALUES (4, 'VT25-00000003', '2', '3', 1, '32454345324', 0, NULL, 1, '45679', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-02-24 15:11:11');
INSERT INTO `tb_orden_visita_islas` VALUES (9, 'VT25-00000004', '1', '2', 1, '324324', 0, NULL, 1, '2', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-02-26 15:28:16');
INSERT INTO `tb_orden_visita_islas` VALUES (10, 'VT25-00000005', '1', '2', 1, '324543', 0, NULL, 1, '2', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-03-03 12:30:28');
INSERT INTO `tb_orden_visita_islas` VALUES (11, 'VT25-00000006', '1', '2', 1, '12434323', 0, NULL, 1, '2', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-03-05 12:49:15');
INSERT INTO `tb_orden_visita_islas` VALUES (12, 'VT25-00000006', '2', '3', 1, '4234234', 0, NULL, 1, '3', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-03-05 12:49:15');

-- ----------------------------
-- Table structure for tb_problema
-- ----------------------------
DROP TABLE IF EXISTS `tb_problema`;
CREATE TABLE `tb_problema`  (
  `id_problema` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `descripcion` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tipo_incidencia` int(11) NULL DEFAULT NULL COMMENT 'TIPO 1 : REMOTO , TIPO 2 : PRESENCIAL',
  `eliminado` tinyint(1) NULL DEFAULT 0,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_problema`) USING BTREE,
  UNIQUE INDEX `codigo`(`codigo`) USING BTREE,
  INDEX `FK_Tipo_Incidencia`(`tipo_incidencia`) USING BTREE,
  CONSTRAINT `FK_Tipo_Incidencia` FOREIGN KEY (`tipo_incidencia`) REFERENCES `tb_tipo_incidencia` (`id_tipo_incidencia`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_problema
-- ----------------------------
INSERT INTO `tb_problema` VALUES (1, 'L-PRE', 'PROBLEMA DE LECTURA', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (2, 'I-PRE', 'PROBLEMA DE IMPRESORAS', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (3, 'T-PRE', 'PROBLEMA DE TERMINAL TOUCH / MR350', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (4, 'C-PRE', 'PROBLEMA DE COMUNICACION', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (5, 'L-REM', 'PROBLEMA DE LECTURA', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (6, 'I-REM', 'PROBLEMA DE IMPRESORA', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (7, 'T-REM', 'PROBLEMA DE TERMINAL TOUCH', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (8, 'C-REM', 'PROBLEMA DE COMUNICACIÓN', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (9, 'ACT-PRE', 'ACTUALIZACION DE SISTEMA', 2, 0, 1, '2025-01-31 15:51:12', '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (10, 'PSIS-REM', 'PROBLEMAS DE SISTEMA', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (11, 'MANT', 'MANTENIMIENTO GENERAL', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (12, 'PRU-PRE', 'PRUEBA', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (13, 'SPA-PRE', 'SOPORTES ADICIONALES', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (14, 'SPA-REM', 'SOPORTES ADICIONALES', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (15, 'PSCE-REM', 'PROBLEMAS EN SISTEMA SAUCE', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (17, 'CSV-PRE', 'CAMBIO DE SERVIDOR', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (18, 'CSV-REM', 'CAMBIO DE SERVIDOR', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (19, 'CDD-PRE', 'CAMBIO DE DISCO DURO', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (20, 'CDD-REM', 'CAMBIO DE DISCO DURO', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (21, 'PSIS-PRE', 'PROBLEMAS DE SISTEMA', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (22, 'PRE-PNM', 'PUESTA EN MARCHA/ INICIO DE VENTAS', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (23, 'R-PRE', 'REUBICACIÓN DE EQUIPOS', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (24, 'REM-PNM', 'SEGUIMIENTO PUESTA EN MARCHA/ INICIO DE VENTAS', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (25, 'S-PRE', 'LEVANTAMIENTO DE INFORMACION', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (26, 'ACT-PRUEBA', 'Problema prueba', 1, 0, 1, NULL, '2025-01-31 13:23:47');

-- ----------------------------
-- Table structure for tb_submenu
-- ----------------------------
DROP TABLE IF EXISTS `tb_submenu`;
CREATE TABLE `tb_submenu`  (
  `id_submenu` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu` int(11) NOT NULL,
  `descripcion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `categoria` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `ruta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `eliminado` tinyint(1) NULL DEFAULT 0,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id_submenu`) USING BTREE,
  INDEX `Fk_Menu`(`id_menu`) USING BTREE,
  CONSTRAINT `Fk_Menu` FOREIGN KEY (`id_menu`) REFERENCES `tb_menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_submenu
-- ----------------------------
INSERT INTO `tb_submenu` VALUES (1, 3, 'Visitas', NULL, '/visitas/sucursales', 0, 1, '2025-02-01 20:09:44', '2025-01-31 21:06:39');
INSERT INTO `tb_submenu` VALUES (2, 3, 'Terminadas', NULL, '/visitas/terminadas', 0, 1, '2025-02-01 20:06:11', '2025-01-31 21:06:39');
INSERT INTO `tb_submenu` VALUES (3, 4, 'Empresas', NULL, '/empresas/empresas', 0, 1, NULL, '2025-02-01 20:12:21');
INSERT INTO `tb_submenu` VALUES (4, 4, 'Grupos Empresas', NULL, '/empresas/grupos', 0, 1, '2025-02-10 10:20:20', '2025-02-01 20:13:05');
INSERT INTO `tb_submenu` VALUES (5, 4, 'Sucursales Empresas', NULL, '/empresas/sucursales', 0, 1, NULL, '2025-02-01 20:13:33');
INSERT INTO `tb_submenu` VALUES (6, 5, 'Usuarios', NULL, '/control-de-usuario/usuarios', 0, 1, NULL, '2025-02-01 20:15:25');
INSERT INTO `tb_submenu` VALUES (7, 6, 'Problemas', 'Incidentes', '/mantenimiento/problemas/problemas', 0, 1, NULL, '2025-02-01 20:16:46');
INSERT INTO `tb_submenu` VALUES (8, 6, 'Sub Problemas', 'Incidentes', '/mantenimiento/problemas/subproblemas', 0, 1, NULL, '2025-02-01 20:17:36');
INSERT INTO `tb_submenu` VALUES (9, 7, 'Menu', 'Config. Menu', '/mantenimiento/menu/menu', 0, 1, '2025-02-10 15:15:29', '2025-02-01 20:18:53');
INSERT INTO `tb_submenu` VALUES (10, 7, 'Sub Menu', 'Config. Menu', '/mantenimiento/menu/submenu', 0, 1, '2025-02-10 15:15:44', '2025-02-01 20:19:18');
INSERT INTO `tb_submenu` VALUES (11, 8, 'Soporte Asignadas', NULL, '/buzon-personal/asignadas', 0, 1, NULL, '2025-02-25 08:51:24');
INSERT INTO `tb_submenu` VALUES (12, 8, 'Soporte Resueltas', NULL, '/buzon-personal/resueltas', 0, 1, NULL, '2025-02-25 08:52:00');

-- ----------------------------
-- Table structure for tb_subproblema
-- ----------------------------
DROP TABLE IF EXISTS `tb_subproblema`;
CREATE TABLE `tb_subproblema`  (
  `id_subproblema` int(11) NOT NULL AUTO_INCREMENT,
  `id_problema` int(11) NULL DEFAULT NULL,
  `codigo_sub` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `descripcion` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `eliminado` tinyint(1) NULL DEFAULT 0,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_subproblema`) USING BTREE,
  UNIQUE INDEX `codigo_sub`(`codigo_sub`) USING BTREE,
  INDEX `id_problema`(`id_problema`) USING BTREE,
  CONSTRAINT `id_problema` FOREIGN KEY (`id_problema`) REFERENCES `tb_problema` (`id_problema`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 255 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_subproblema
-- ----------------------------
INSERT INTO `tb_subproblema` VALUES (1, 1, 'L-PRE_01', 'VALIDACION DE JACKTOOL', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (2, 1, 'L-PRE_02', 'RESTAURACION PROBLEMA JACKTOOL', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (3, 1, 'L-PRE_03', 'CAMBIO DE JACKTOOL', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (4, 1, 'L-PRE_04', 'VALIDACION DE MANGUERA TELEFONICA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (5, 1, 'L-PRE_05', 'RESTAURACION PROBLEMA MANGUERA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (6, 1, 'L-PRE_06', 'CAMBIO DE MANGUERA TELEFONICA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (7, 1, 'L-PRE_07', 'VALIDACION DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (8, 1, 'L-PRE_08', 'RESTAURACION DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (9, 1, 'L-PRE_09', 'CAMBIO DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (10, 1, 'L-PRE_10', 'VALIDACION DE DISPOSITIVO LECTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (11, 1, 'L-PRE_11', 'RESTAURACION PROBLEMA DISPOSITIVO LECTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (12, 1, 'L-PRE_12', 'CAMBIO DE DISPOTIVO LECTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (13, 1, 'L-PRE_13', 'INSTALACION DE DISPOSITIVO LECTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (14, 1, 'L-PRE_14', 'LECTOR DE CHIP DAÑADO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (15, 1, 'L-PRE_15', 'VALIDACION RJ45', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (16, 1, 'L-PRE_16', 'CAMBIO DE RJ45', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (17, 1, 'L-PRE_17', 'VALIDACION FUENTE DE DISPOSITIVO LECTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (18, 1, 'L-PRE_18', 'CAMBIO FUENTE DE DISPOSITIVO LECTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (19, 2, 'I-PRE_01', 'VALIDACION DE IMPRESORA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (20, 2, 'I-PRE_02', 'RESTAURACION PROBLEMA IMPRESORA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (21, 2, 'I-PRE_03', 'INSTALACION DEIMPRESORA BACKUP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (22, 2, 'I-PRE_04', 'RECOJO DE IMPRESORA BACKUP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (23, 2, 'I-PRE_05', 'RECOJO DE IMPRESORA EDS PARA REPARACION', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (24, 2, 'I-PRE_06', 'SALIDA DE IMPRESORA EDS REPARADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (25, 2, 'I-PRE_07', 'INSTALACION IMPRESORA NUEVA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (26, 2, 'I-PRE_08', 'VALIDACION DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (27, 2, 'I-PRE_09', 'RESTAURACION DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (28, 2, 'I-PRE_10', 'CAMBIO DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (29, 2, 'I-PRE_11', 'CONFIGURACION DE IMPRESORA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (30, 10, 'PSIS-REM_45', 'ANULACION DE DOCUMENTO BOLETA A CALIBRACION', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (31, 2, 'I-PRE_13', 'ATASCO DE PAPEL', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (32, 2, 'I-PRE_14', 'ATASCO DE CUCHILLA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (33, 2, 'I-PRE_15', 'FUENTE DESCONECTADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (34, 3, 'T-PRE_01', 'VALIDACION DE DISPOSITIVO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (35, 3, 'T-PRE_02', 'RESTAURACION PROBLEMA ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (36, 3, 'T-PRE_03', 'INSTALACION DE TERMINAL', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (37, 3, 'T-PRE_04', 'CONFIGURACION DE TERMINAL ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (38, 3, 'T-PRE_05', 'CONFIGURACION DE APLICATIVO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (39, 3, 'T-PRE_06', 'DESCALIBRACION DE PANTALLA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (40, 3, 'T-PRE_07', 'PANTALLA CONGELADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (41, 3, 'T-PRE_08', 'FUENTE DESCONECTADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (42, 3, 'T-PRE_09', 'CAMBIO DE FUENTE 12V ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (43, 3, 'T-PRE_10', 'VALIDACION DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (44, 3, 'T-PRE_11', 'RESTAURACION DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (45, 3, 'T-PRE_12', 'CAMBIO DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (46, 4, 'C-PRE_01', 'VALIDACION DEPERDIDA DE COMUNICACIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (47, 4, 'C-PRE_02', 'RESTAURACION PERDIDA DE COMUNICACIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (48, 4, 'C-PRE_03', 'INSTALACION DEINTERFAZ DE BACKUP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (49, 4, 'C-PRE_04', 'RECOJO DE INTERFAZ BACKUP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (50, 4, 'C-PRE_05', 'RECOJO DE INTERFAZ EDS PARA REPARACION', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (51, 4, 'C-PRE_06', 'SALIDA DE INTERFAZ EDS REPARADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (52, 4, 'C-PRE_07', 'INSTALACION INTERFAZ NUEVA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (53, 4, 'C-PRE_08', 'VALIDACION DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (54, 4, 'C-PRE_09', 'RESTAURACION DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (55, 4, 'C-PRE_10', 'CAMBIO DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (56, 4, 'C-PRE_11', 'CONFIGURACION DE INTERFAZ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (57, 4, 'C-PRE_12', 'FUENTE DESCONECTADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (58, 4, 'C-PRE_13', 'INDEPENDIZACION DE SURTIDORES CON INTERFAZ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (59, 5, 'L-REM_01', 'LENTITUD EN LECTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (60, 5, 'L-REM_02', 'REINICIO DE SERVICIOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (61, 5, 'L-REM_03', 'REINYECCION DE SISTEMA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (62, 5, 'L-REM_04', 'MANTENIMIENTO DE SISTEMA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (63, 5, 'L-REM_05', 'DISPOSITIVO BLOQUEADO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (64, 5, 'L-REM_06', 'CONFIGURACION DISPOSITIVO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (65, 5, 'L-REM_07', 'CARA DESPACHO BLOQUEDA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (66, 5, 'L-REM_08', 'INDICE FUERA DE LA MATRIZ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (68, 6, 'I-REM_01', 'LENTITUD EN IMPRESIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (69, 6, 'I-REM_02', 'DOCUMENTOS EN COLA DE IMPRESIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (70, 6, 'I-REM_03', 'DOCUMENTO PENDIENTE POR ENVIAR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (71, 6, 'I-REM_04', 'CONFLICTO DE IP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (72, 6, 'I-REM_05', 'CONFIGURACION DE IMPRESORA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (73, 6, 'I-REM_06', 'DERIVACION DE IMPRESIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (74, 7, 'T-REM_01', 'VALIDACION DE DISPOSITIVO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (75, 7, 'T-REM_02', 'CONFIGURACION DE APLICATIVO ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (76, 7, 'T-REM_03', 'DESCALIBRACION DE PANTALLA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (77, 7, 'T-REM_04', 'PANTALLA CONGELADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (78, 7, 'T-REM_05', 'APP GST NO FIDELIZA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (79, 7, 'T-REM_06', 'SERVICIO AUTORIZADOR NO ENCONTRADO ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (80, 7, 'T-REM_07', 'PERDIDA DE COMUNICACIÓN ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (81, 8, 'C-REM_01', 'VALIDACION DEPERDIDA DE COMUNICACIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (82, 8, 'C-REM_02', 'RESTAURACION PERDIDA DE COMUNICACIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (83, 8, 'C-REM_03', 'CONFIGURACION DE INTERFAZ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (84, 8, 'C-REM_04', 'CONFLICTO DE PUERTOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (85, 8, 'C-REM_05', 'PUERTO FANTASMAS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (86, 8, 'C-REM_06', 'CONFIGURACION PUERTO INTERFAZ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (87, 8, 'C-REM_07', 'CAMBIO DE PUERTO COMUNICACIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (88, 9, 'ACT-PRE_01', 'ACTUALIZACION DE NUEVA VERSION', 0, 1, '2025-01-31 16:38:14', '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (89, 9, 'ACT-PRE_02', 'ACTUALIZACION PRUEBAS PILOTO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (90, 9, 'ACT-PRE_03', 'ACTUALIZACION O.S', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (91, 9, 'ACT-PRE_04', 'ACTUALIZACION DE CAMBIO DE FACTURADOR ELECTRONICO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (92, 9, 'ACT-PRE_05', 'ACTUALIZACION CAMBIO DE SERIES / PREFIJOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (93, 10, 'PSIS-REM_01', 'REINICIO DE SERVICIO COMPLETO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (94, 10, 'PSIS-REM_02', 'CIERRE/ APERTURA DE TURNO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (95, 10, 'PSIS-REM_03', 'CAMBIO DE PRECIO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (96, 10, 'PSIS-REM_04', 'INGRESAR NUEVO EMPLEADO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (97, 10, 'PSIS-REM_05', 'INGRESAR NUEVO CLIENTE CRÉDITO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (98, 10, 'PSIS-REM_06', 'INGRESAR NUEVO CLIENTE CON DESCUENTO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (99, 10, 'PSIS-REM_07', 'INGRESAR A GENERADOR DE REPORTES', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (100, 10, 'PSIS-REM_08', 'INGRESAR A REPORTES GASOLUTIONS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (101, 10, 'PSIS-REM_09', 'DEJAR BASE DE DATOS COMO HISTORICA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (102, 10, 'PSIS-REM_10', 'USUARIO BLOQUEADO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (103, 10, 'PSIS-REM_11', 'RETRANSMISION DE VENTAS CLUB PGN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (104, 10, 'PSIS-REM_12', 'ANULACIÓN DE DOCUMENTOS BOLETA A VALE', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (105, 10, 'PSIS-REM_13', 'ANULACIÓN DE DOCUMENTOS BOLETA A FACTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (106, 10, 'PSIS-REM_14', 'ACTUALIZACION DE DATOS DE CLIENTE SUNAT', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (107, 10, 'PSIS-REM_15', 'PROBLEMAS CON BASE DE DATOS GASOLUTIONS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (108, 10, 'PSIS-REM_16', 'PROBLEMAS CON SISTEMA OPERATIVO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (109, 10, 'PSIS-REM_17', 'CAMBIO DE DISCO DURO CON SISTEMA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (110, 10, 'PSIS-REM_18', 'COPIA POR NUMERO DE DOCUMENTO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (111, 10, 'PSIS-REM_19', 'COPIA POR NUMERO DE DOCUMENTO CLIENTE CREDITO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (112, 11, 'MANT_01', 'MANTENMIENTO GENERAL DE EESS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (113, 2, 'I-PRE_16', 'SOBRE ESCRITURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (114, 7, 'T-REM_08', 'APLICATIVO CERRADO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (115, 10, 'PSIS-REM_20', 'INGRESO AL DATA ADMIN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (116, 10, 'PSIS-REM_21', 'INGRESO AL CONFIGURADOR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (117, 10, 'PSIS-REM_22', 'REGISTRO DE PLACAS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (118, 10, 'PSIS-REM_23', 'EMISION DE BOLETA CON NUMERO DE DNI', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (119, 10, 'PSIS-REM_24', 'VENTA PERDIDA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (120, 10, 'PSIS-REM_25', 'ACTUALIZACION DE DATA CLIENTE RUC', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (121, 10, 'PSIS-REM_26', 'ANULACIÓN DE DOCUMENTO DE FACTURA A FACTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (122, 10, 'PSIS-REM_27', 'ANULACIÓN DE DOCUMENTO POR VENTA MILLONARIA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (123, 3, 'T-PRE_13', 'ACTUALIZACION DE APLICATIVO GST', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (124, 3, 'T-PRE_14', 'SALIDA DE EQUIPO/ TERMINAL EDS REPARADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (125, 17, 'CSV-PRE_01', 'CAMBIO DE SERVIDOR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (126, 13, 'SPA-PRE_01', 'INSTALACION DE SWICH', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (127, 13, 'SPA-PRE_02', 'ENTREGA DE LECTORES', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (128, 13, 'SPA-PRE_03', 'INSTALACION DE ANTIVIRUS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (129, 13, 'SPA-PRE_04', 'APOYO ÁREA DE PROYECTOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (130, 5, 'L-REM_09', 'VALIDACION DE VENTAS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (131, 1, 'L-PRE_19', 'PRUEBAS DE VENTA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (132, 14, 'SPA-REM_01', 'APERTURA DE USER EN SERVER', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (133, 14, 'SPA-REM_02', 'VALIDACIÓN EN SISTEMAS CARGA EN GNC', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (134, 13, 'SPA-PRE_05', 'VALIDACIÓN DE CARGA EN GNC', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (135, 15, 'PSCE-REM_01', 'SISTEMA NO ACTIVA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (136, 15, 'PSCE-REM_02', 'SISTEMA BLOQUEADO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (137, 13, 'SPA-PRE_06', 'VALIDACIÓN DE UPS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (138, 13, 'SPA-PRE_07', 'REVISIÓN TÉCNICA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (139, 10, 'PSIS-REM_28', 'EXCEPSIÓN DEL CDC ( CLUB PGN)', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (140, 10, 'PSIS-REM_29', 'APLIAR TIEMPO DE ANULACIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (141, 14, 'SPA-REM_03', 'CARAS BLOQUEADAS POR VENTAS PUNTOS BONUS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (142, 3, 'T-PRE_15', 'INSTALACION DE EQUIPO BACKUP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (143, 13, 'SPA-PRE_08', ' IMPLEMENTACION', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (144, 10, 'PSIS-REM_30', 'ANULACIÓN DE DOCUMENTO VALE A BOLETA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (145, 10, 'PSIS-REM_31', 'CONVERSION DE VENTA EFECTIVO A TARJETA CORPORATIVA ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (146, 13, 'SPA-PRE_09', 'PRUEBAS DE VENTAS CON TARJETA CORPORATIVA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (147, 14, 'SPA-REM_04', 'APERTURA DE REPORTES', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (148, 14, 'SPA-REM_05', 'LA OPERACION SE COMPLETO CORRECTRAMENTE', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (149, 10, 'PSIS-REM_32', 'ANULACIÓN DE DOCUMENTO SIN EMITIR OTRO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (150, 10, 'PSIS-REM_33', 'ANULACIÓN DE DOCUMENTO POR UNA NOTA DE CRÉDITO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (151, 10, 'PSIS-REM_34', 'SACAR COPIA DE TURNOS / AUDITORIA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (152, 10, 'PSIS-REM_35', 'REIMPRESIÓN COPIA DE DOCUMENTO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (153, 13, 'SPA-PRE_11', 'INSTALACION DE BANDA MAGNETICA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (155, 13, 'SPA-PRE_12', 'VALIDACIÓN DE PANATALLA SERVER', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (156, 14, 'SPA-REM_06', 'APERTURA DEL MAI STATION', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (158, 13, 'SPA-PRE_13', 'RECOJO DE EQUIPOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (159, 13, 'SPA-PRE_14', 'INSTALACION DE EQUIPOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (160, 13, 'SPA-PRE_15', 'PRUEBAS SISTEMAS SAUCE', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (161, 10, 'PSIS-REM_36', 'ANULACION DE DOCUMENTO VALA A FACTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (164, 6, 'I-REM_07', 'IMPRESIONES BASURAS/ DATOS INCORRECTO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (165, 14, 'SPA-REM_07', 'INVENTARIO PRIMAX', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (166, 13, 'SPA-PRE_16', 'DEVOLUCIÓN DE EQUIPO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (167, 13, 'SPA-PRE_17', 'CAMBIO DE IP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (168, 10, 'PSIS-REM_37', 'DESACTIVAR PLACA DE CLIENTE CREDITO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (169, 5, 'L-REM_10', 'CHIP BLOQUEADO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (170, 10, 'PSIS-REM_38', 'VENTA CON INCONSISTENCIA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (171, 13, 'SPA-PRE_18', 'CORTO DE FLUIDO ELECTRICO / VALIDACION DE EQUIPOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (172, 14, 'SPA-REM_08', 'DIFERENCIA EN PARTE DIARO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (173, 19, 'CDD-PRE_01', 'CAMBIO DE DISCO DURO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (175, 6, 'I-REM_08', 'NO IMPRIMEN DOCUMENTOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (176, 10, 'PSIS-REM_39', 'VALIDACIONES DE SISTEMA OPERATIVO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (177, 10, 'PSIS-REM_40', 'MANTENIMIENTO DE SISTEMAS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (184, 4, 'C-PRE_14', 'CONFIGURACION DE PUERTOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (185, 18, 'CSV-REM_01', 'CAMBIO DE SERVIDOR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (186, 14, 'SPA-REM_09', 'PROBLEMAS - ERROR DEL SURTIDOR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (187, 14, 'SPA-REM_10', 'APERTURA DEL GENERADOR DE ARCHIVOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (189, 13, 'SPA-PRE_19', 'INVENTARIO/ LEVANTAMIENTO DE INFO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (190, 14, 'SPA-REM_11', 'PRUEBAS SISTEMAS SAUCE', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (191, 10, 'PSIS-REM_41', 'PROBLEMAS CON TARJETA CORPORATIVA / BONUS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (192, 10, 'PSIS-REM_42', 'ANULACION DE DOCUMENTO FACTURA A VALE', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (193, 10, 'PSIS-REM_43', 'VENTA EN CURSO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (194, 20, 'CDD-REM_01', 'CAMBIO DE DISCO DURO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (196, 6, 'I-REM_09', 'IMPRESION CON LINEAS EN BLANCO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (197, 14, 'SPA-REM_12', 'REVISION DE UPS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (198, 14, 'SPA-REM_13', 'PROBLEMAS CON FLUIDO ELÉCTRICO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (199, 21, 'PSIS-PRE_01', 'PROBLEMAS VARIOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (202, 13, 'SPA-PRE_20', 'VALIDACIONES SISTEMAS SAUCE', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (203, 13, 'SPA-PRE_21', 'LEVANTAMIENTO DE INFORMACION', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (204, 15, 'PSCE-REM_03', 'CARA BLOQUEADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (205, 14, 'SPA-REM_14', 'RESTABLECIMIENTO DE SISTEMAS POR PARADA DE EMERGENCIA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (208, 13, 'SPA-PRE_22', 'VALIDACIÓN DE EQUIPOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (209, 10, 'PSIS-REM_44', 'PROBLEMAS EN CONSULTA SUNAT', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (210, 12, 'PRU-PRE_01', 'PRIMAX GO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (211, 13, 'SPA-PRE_23', 'CAMBIO DE SERVIDOR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (212, 15, 'PSCE-REM_04', 'EXCEPCION SALTOS DE LECTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (213, 2, 'I-PRE_17', 'NO IMPRIMEN DOCUMENTOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (214, 14, 'SPA-REM_15', 'INCONVENIENTE CON GNV POR MANTENIMIENTE DE TABLERO ELÉCTRICO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (216, 13, 'SPA-PRE_24', 'ENTREGA DE MANGUERAS TELEFÓNICAS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (217, 23, 'R-PRE_01', 'IMPRESORAS TYSSO/ EPSON', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (218, 23, 'R-PRE_02', 'GABINETE', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (219, 23, 'R-PRE_03', 'EQUIPO DE LECTURA ISR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (220, 23, 'R-PRE_04', 'EQUIPO TERMINAL TOUCH', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (221, 10, 'PSIS-REM_46', 'DESACTIVAR DESCUENTO DE CLIENTE / CREDITO ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (223, 6, 'I-REM_10', 'IMPRESIONES ENTRE CORTADAS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (224, 8, 'C-REM_08', 'CONFIGURACION DE IP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (225, 6, 'I-REM_11', 'SOBRE ESCRITURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (226, 13, 'SPA-PRE_25', 'SOPORTES VARIOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (227, 10, 'PSIS-REM_47', 'CONFIGURACION DE HORARIOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (228, 14, 'SPA-REM_16', 'REVISIÓN DE IP SERVER/ IP PUBLICA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (229, 14, 'SPA-REM_17', 'CONSULTAS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (231, 13, 'SPA-PRE_26', 'ACTIVACIÓN DE LICENCIA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (232, 14, 'SPA-REM_18', 'AGREGAR PRODUCTOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (236, 2, 'I-PRE_18', 'IMPRESIÓN BORROSA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (237, 14, 'SPA-REM_20', 'VALIDACIÓN DE CHIP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (238, 10, 'PSIS-REM_48', 'ANULACIÒN DE FACTURA A BOLETA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (239, 14, 'SPA-REM_21', 'SEGUIMEINTO PRUEBAS DE VENTA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (240, 12, 'PRU-PRE_02', 'PRUEBAS DE VENTA POR DONACIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (241, 10, 'PSIS-REM_49', 'EMITIR DOCUMENTO POR VENTA MILLONARIA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (242, 13, 'SPA-PRE_27', 'VALIDACIÓN - ERROR DEL SURTIDOR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (244, 14, 'SPA-REM_22', 'CAPACITACIÓN / ESTACIÓN DE SERVICIO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (245, 14, 'SPA-REM_23', 'GENERACIÓN DE CLAVE DINÁMICA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (246, 14, 'SPA-REM_24', 'SEGUIMIENTO DE ACTIVAVIÓN CREDITO ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (247, 14, 'SPA-REM_25', 'CONFIGURACIONES DATA ADMIN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (248, 14, 'SPA-REM_26', 'VALIDACIONES CON EQUIPO MR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (249, 13, 'SPA-PRE_28', 'PROBLEMA CON EQUIPO MR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (250, 2, 'I-PRE_19', 'IMPRESORA DE BAJA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (251, 2, 'I-PRE_20', 'CAMBIO DE IMPRESORA POR REPOSICIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (252, 10, 'PSIS-REM_50', 'EXCEPCIÓN EL RECIBO NO TIENE UN DOCUMENTO ASOCIADO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (253, 25, 'S-PRE_01', 'LEVANTAMIENTO DE INFORMACION', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (254, 1, 'ACT-PRE_PRUEBA', 'Problema de lectura prueba', 0, 1, NULL, '2025-01-31 16:39:31');

-- ----------------------------
-- Table structure for tb_sucursales
-- ----------------------------
DROP TABLE IF EXISTS `tb_sucursales`;
CREATE TABLE `tb_sucursales`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ruc` varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cofide` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `direccion` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ubigeo` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(14) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `correo` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `v_visitas` tinyint(1) NOT NULL DEFAULT 0,
  `v_mantenimientos` tinyint(1) NOT NULL DEFAULT 0,
  `url_mapa` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ruc`(`ruc`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 355 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_sucursales
-- ----------------------------
INSERT INTO `tb_sucursales` VALUES (1, '20100111838', 'GESA CENTRAL', NULL, 'AV. VICTOR R HAYA DE LA TORRE 1949', '010101', ' ', '', 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (2, '20100111838', 'GESA AREQUIPA', NULL, 'AV. AREQUIPA Nro. 900', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (3, '20100111838', 'GESA TABLADA', NULL, 'AV. PACHACUTEC 5295', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (4, '20100111838', 'GESA ULTRAGRIFOS', NULL, 'CAR.PANAMERICANA SUR NRO. 18.5', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (5, '20100111838', 'GESA VILLA', NULL, 'AV. MICAELA BASTIDAS # 1848', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (6, '20100111838', 'GESA HUACHO', NULL, 'CAR.PANAMERICANA NORTE NRO. 148', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (7, '20100111838', 'GESA BARRANCA', NULL, 'ANTIGUA CAR.PANAMERICANA NORTE NRO. 196 URB LAS PALMERAS', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (8, '20100111838', 'GESA ICA', NULL, 'CAR.PANAMERICANA SUR KM. 299 (ALT. DEL KM 299 PANAMERICANA SUR)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (9, '20100111838', 'GESA ASIA', NULL, 'AV. PANAMERICANA SUR KM. 97.5 URB. ASIA (BOULEVARD ASIA)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (10, '20100111838', 'GESA CHIMBOTE', NULL, 'PANAMERICANA NORTE KM 429.5', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (11, '20100111838', 'GESA PISCO', NULL, 'AV. FERMIN TANGUI # 220', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (12, '20100111838', 'GESA PUENTE PIEDRA', NULL, 'PANAMERICANA NORTE KM 25 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (13, '20100111838', 'GESA LURIN', NULL, 'AV. LIMA NRO. 2205 (ALT. POSTES 22 Y 23)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (14, '20101312519', 'LIMABANDA S.A.C.', NULL, 'AV. MARISCAL ORBEGOSO NRO. 120 URB. EL PINO', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (15, '20101312519', 'LIMABANDA S.A.C.', NULL, 'AV. CARLOS IZAGUIRRE 220 INDEPENDENCIA', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (16, '20101312519', 'LIMABANDA S.A.C.', NULL, 'AV VICTOR RAUL HAYA DE LA TORRE 250 URB SAN EDUARDO ', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (17, '20127765279', 'E/S JAVIER PRADO', NULL, ' AV. JAVIER PRADO NRO. 1059 LIMA - LIMA - LA VICTORIA', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (18, '20127765279', 'E/S TAVIRSA', '', 'Av. Prol. Huaylas 600, Lima', '010101', '999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (19, '20127765279', 'E/S HIPODROMO', NULL, 'AV. JAVIER PRADO ESTE 4400', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (20, '20127765279', 'E/S ZARATE', NULL, ' AV. MALECON CHECA 1. ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (21, '20127765279', 'E/S RISSO', NULL, 'AV. ARENALES N°  2100', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (22, '20127765279', 'E/S LA MARINA', NULL, 'AV. LA MARINA 2185', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (23, '20127765279', 'E/S BAHIA', NULL, 'AV. AVIACION 1500 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (24, '20127765279', 'E/S CASTAÑOS', '', 'AV. JAVIER PRADO OESTE 1895', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (25, '20127765279', 'E/S BENAVIDES', NULL, 'AV. REPUBLICA DE PANAMA Y BENAVIDES S/N MIRAFLORES', '010101', ' ', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (26, '20127765279', 'E/S ESCOSA', NULL, 'AV. GUARDIA CIVIL 333 LA CAMPIÑA', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (27, '20127765279', 'E/S HUIRACOCHA', NULL, 'AV. GREGORIO ECOBEDO 410 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (28, '20127765279', 'E/S MONTERRICO', NULL, 'AV. LA MOLINA 580 URB. RESIDENCIAL MONTERRICO', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (29, '20127765279', 'E/S ARRIOLA', '', 'AV. NICOLAS ARRIOLA 710 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (30, '20127765279', 'E/S FELVERANA', NULL, 'AV. NICOLAS DE AYLLON 2162', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (31, '20127765279', 'E/S 28 DE JULIO', NULL, 'AV. 28 DE JULIO 220', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (32, '20127765279', 'E/S CANADA', NULL, 'AV. CANADA CDRA. 11 ESQ. VICTOR ALZAMORA ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (33, '20127765279', 'E/S BRASIL', '', 'AV. EL EJERCITO #110', '010101', ' ', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (34, '20127765279', 'E/S SUDAMERICANO', NULL, 'AV. COLONIAL N° 817', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (35, '20127765279', 'E/S FRUTALES', '', 'AV. LOS FRUTALES 994 URB. CAMINO REAL', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (36, '20127765279', 'E/S QUILCA', NULL, 'URB SAN ALONSO STA IRENE AV QUILCA / CALLE 10', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (37, '20127765279', 'E/S BREÑA', '', 'AV. TINGO MARIA 1711', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (38, '20127765279', 'E/S MAGDALENA', NULL, 'AV. DEL EJERCITO 101', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (39, '20127765279', 'E/S CASTILLA LIMA', '', 'AV. NICOLAS ARRIOLA 295', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (40, '20127765279', 'E/S LA PERLA', '', 'AV LA PAZ N° 2326', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (41, '20127765279', 'E/S TINGO MARIA', NULL, 'AV. TINGO MARIA 1194', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (42, '20127765279', 'E/S IGARZA', NULL, 'AV. TOMAS VALLE 1981', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (43, '20127765279', 'E/S AREQUIPA I', '', 'AV. AREQUIPA 3325', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (44, '20127765279', 'E/S CARMELO', '', 'AV. LA MARINA 3112 URB. MARANGA', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (45, '20127765279', 'E/S ORRANTIA', NULL, 'AV. DEL EJERCITO 965', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (46, '20127765279', 'E/S SUCRE', NULL, 'AV. SUCRE 1070 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (47, '20127765279', 'E/S AULY ', NULL, 'AV. EL SOL KM 1 NRO. 33 URB. SAN CARLOS', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (48, '20127765279', 'E/S SAN LUIS', NULL, 'AV. NICOLAS AYLLON 1340', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (49, '20127765279', 'E/S SAN ISIDRO', NULL, 'AV. REPUBLICA DE PANAMA 3690-3696', '010101', ' 221-1340', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (50, '20127765279', 'E/S PIURA II', NULL, 'MZ A SUBLOTE A-1 SECTOR PARCELA J-ZONA INDSUTRIAL', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (51, '20127765279', 'E/S PERSHING', NULL, 'AV. FAUSTINO SANCHEZ 471', '010101', '463-0707', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (52, '20127765279', 'E/S SAN JUAN DE LURIGANCHO', NULL, 'AV. LURIGANCHO S/N MZA. B LOTE 10 (LOTE A-B-C-D-E Y F-1)', '010101', '7172549', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (53, '20127765279', 'E/S FAUCETT', NULL, 'AV ELMER FAUCETT 384', '010101', '4512408', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (54, '20127765279', 'E/S CHICLAYO', NULL, 'FND. LA ESPERANZA AV. EVITAMIENTO S/N', '010101', '612068', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (55, '20127765279', 'E/S ROSARIO', NULL, 'AV. TOMAS MARSANO 1008 ', '010101', '4452786', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (56, '20127765279', 'E/S ÑAÑA', NULL, 'AV NICOLAS AYLLON #1500', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (57, '20127765279', 'E/S FERRARI', NULL, 'AV. TOMAS MARSANO NRO 5010', '010101', '274-3090', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (58, '20127765279', 'E/S MONTREAL', NULL, 'AV. CAMINOS DEL INCA 2017', '010101', '275-0382', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (59, '20127765279', 'E/S GRAU', NULL, 'AV. GRAU NRO 1308 - ESQU JT HUANUCO 1101', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (60, '20127765279', 'E/S ARICA', '', 'AV. ARICA NRO. 499 URB. GARDEN CITY', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (61, '20127765279', 'E/S SALAVERRY', '', 'JR. SALAVERRY 480 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (62, '20127765279', 'E/S ATE', '', 'AV. NICOLAS AYLLON MZ. A Lt. 1- CARRET. CTRAL KM. 16', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (63, '20127765279', 'E/S AREQUIPA II', '', 'AV. AREQUIPA NRO. 1890', '010101', ' ', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (64, '20127765279', 'E/S ANGAMOS', '', 'AV. ANGAMOS ESTE NRO 1401', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (65, '20127765279', 'E/S COLOMA', '', 'AV. DOMINGO COLOMA NRO 152', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (66, '20127765279', 'E/S PECSA COLONIAL III', '', 'AV, OSCAR R BENAVIDES NRO S/N (ESQUINA JR. PRESBITERO)', '010101', ' ', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (67, '20127765279', 'E/S PROCERES', '', 'AV. PROCERES DE INDEPENDENCIA NRO 701 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (68, '20127765279', 'E/S GARDENIAS', '', 'JR.BATALLA DE SAN JUAN 112 URB SANTA TERESA (ESQ. CON AV. MORRO SOLAR) ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (69, '20127765279', 'E/S COLONIAL II', '', 'AV MCAL OSCAR R. BENAVIDES NRO 300', '010101', '4237562', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (70, '20127765279', 'E/S MEXICO', '', 'AV NICOLAS AYLLON MZA 306 LOTE 1-2 URB SAN PABLO (LOTE 1 - 2 - 3)', '010101', '6602466', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (71, '20127765279', 'E/S PASEO LA REPUBLICA', '', 'AV. PASEO DE LA REPUBLICA NRO 5545 URB SAN ANTONIO', '010101', '2431334', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (72, '20127765279', 'E/S TOMAS VALLE', '', 'AV. TOMAS VALLE ESQ. AV BETA NRO SN(FUNDO GARAGAY BAJO)', '010101', '5338042', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (73, '20127765279', 'E/S WIESSE', '', 'MZA B LOTE 06 URB CANTO GRANDE ESQ. PROCERES DE LA INDEPENDENCIA', '010101', '3889296', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (74, '20127765279', 'E/S CALERA', '', 'AV. AVIACION NRO 4524 (ESQUINA CON AV. VILLARAN URB LOS SAUCES )', '010101', '2608688', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (75, '20127765279', 'E/S TOMAS MARZANO', '', 'AV. TOMAS MARSANO NRO. 4070 URB. 18 DE NOVIEMBRE', '010101', '448-2770', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (76, '20127765279', 'E/S CIRCUNVALACION', '', 'AV. CIRCUNVALACION 1411', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (77, '20127765279', 'E/S LA PAZ', '', 'AV. LA PAZ NRO 1200 URB. MIRAMAR', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (78, '20127765279', 'E/S BOLIVAR', '', 'AV BOLIVAR NRO 1020', '010101', '4602870', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (79, '20156278751', 'OFICINA BARRANCA', NULL, 'JR GALVEZ 587 BARRANCA', '010101', '0', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (80, '20156278751', 'OFICINA CHANCAY', NULL, 'CALLE BOLIVAR 222 CHANCAY', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (81, '20156278751', 'OFICINA PATIVILCA', NULL, 'CALLE BOLIVAR 222 CHANCAY', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (82, '20156278751', 'OFICINA HUARAL', NULL, 'CALLE BOLIVAR 222 CHANCAY', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (83, '20156278751', 'OFICINA HUACHO', NULL, 'CALLE BOLIVAR 222 CHANCAY', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (84, '20168217723', 'CILIAMSAC ATE', NULL, 'AV. LA MOLINA NRO. 448 URB. EL ARTESANO (BAJAR OVALO STA ANIT)', '010101', '0', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (85, '20168217723', 'CILAMSAC SJM', NULL, ' Panamericana Sur KM 17.5 San Juan De Miraflores', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (86, '20210975862', 'OPERACIONES Y SERVICIOS SJM', NULL, 'CARRETERA PANAMERICANA SUR KM. 14 (ENTRE PTE ALIPO Y PTE HUMAMARCA)', '010101', '3231654789', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (87, '20210975862', 'OPERACIONES Y SERVICIOS CHORRILLOS', NULL, 'AV. CAMINOS DEL INCA MZA. N LOTE. 19 URB. SAN JUAN BAUTISTA DE V. (URB.SAN JUAN BAUTISTA DE VILLA)', '010101', '3231654789', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (88, '20325753821', 'RICSA ARRIOLA', NULL, 'AV. NICOLAS ARRIOLA NRO. 1003 URB. LA POLVORA', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (89, '20325753821', 'RICSA CHACLACAYO', NULL, 'Km 22.7 URB LAS CASUARINAS DE CHACLACAYO - CHACLACAYO - LIMA', '010101', '3804764', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (90, '20492727661', 'EDS LIVORNO BAUZATE', NULL, 'AV. BAUZATE Y MEZA 568.ESQ RENOVACION', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (91, '20492727661', 'EDS LIVORNO ABTAO', NULL, 'JR. ABTAO NRO. 784 (ESQ. HIPOLITO UNANUE)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (92, '20512220836', 'FARMA SAN AGUSTIN SJM', NULL, 'CAL.JOSE TORRES PAZ NRO. 110 URB. CIUDAD DE DIOS ZONA A (FRENTE A LA POSTA CIUDAD DE DIOS)', '010101', '981578374', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (93, '20512220836', 'FARMA SAN AGUSTIN VMT', NULL, 'AV. LIMA 852 P.J. JOSE GALVEZ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (94, '20513567139', 'ALTA VIDDA SAN LUIS', NULL, 'CAL.FELIPE SANTIAGO SALAVERRY NRO. 341 (ALT CDRA 18 DE AV CIRCUNVALACION)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (95, '20513567139', 'ALTA VIDDA LOS OLIVOS', NULL, 'CALLE LOS HORNOS N# 149 URB. INDUSTRIAL NARANJAL', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (96, '20513567139', 'ALTA VIDDA SJL', NULL, 'AV. SANTA ROSA N# 942 URB. CANTO GRANDE 2DA ETAPA', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (97, '20514304921', 'INTIFARMA MIRAFLORES', NULL, 'AV. ANGAMOS ESTE NRO. 252', '010101', '995 555 655', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (98, '20514304921', 'INTIFARMA LA PERLA', NULL, 'AV. LOS INSURGENTES NRO. 709 INT. 3 URB. CAP LUIS GERMAN ASTETE (ALT MINISTERIO DE MARINA)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (99, '20514304921', 'INTIFARMA LOS OLIVOS', NULL, '	AV. NARANJAL NRO. 1486', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (100, '20514326496', 'UNO GAS  ICA ', NULL, 'Calle Fernando Leon Arechua Nro.281 -  URB. San Miguel ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (101, '20514326496', 'UNO GAS CHINCHA BAJA', NULL, 'Urb Predio Cerco La Viña Azabache - Lote 54 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (102, '20520786873', 'GASOCENTRO LIMA SUR  - Lurin', NULL, 'Av Lima 2100', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (103, '20520786873', 'GASOCENTRO LIMA SUR - Huaral', '', 'CAR. CHANCAY-HUARAL KM.7', '010101', '', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (104, '20605450475', 'EDS KM.9', NULL, 'AV. CARRETERA FEDERICO BASADRE KM. 9.00 (LATERAL DERECHO)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (105, '20605450475', 'EDS PAMPAYURAC', NULL, '-', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (106, '20605129154', 'EDS GES', NULL, 'AV. ISABEL LA CATOLICA NRO. S/N URB. MATUTE (ESQUINA CON JR ANDAHUAYLAS)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (107, '20605427147', 'EDS KM. 4', NULL, 'AV. CENTENARIO KM. 4.100 KM. REF (AV. CENTENARIO KM. 4.100)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (108, '20404000447', 'EDS MONTECARLO', NULL, 'AV. SANTA ROSA MZA. A LOTE. 11 URB. COOPIP (LOTE 11 Y 12 ALT. AV. ALCIDES VIGO)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (109, '20508196475', 'PETROCENTRO YULIA S.A.C', NULL, 'AV. DE LA MARINA NRO. 2789 URB. MARANGA 1RA ET. (CRUCE CON AV.ESCARDO)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (110, '20508196475', 'PETROCENTRO YULIA S.A.C', NULL, 'AV. JAVIER PRADO OESTE NRO. 900 (ALT. DE SUPERMERCADO STA ISABEL)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (111, '20283756115', 'EDS AGUAYTIA', NULL, 'AV. Centenario Nro. 4.100 Coronel Portillo', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (112, '20404883918', 'GETIMSA PIMENTEL', NULL, 'AV. ENRIQUE PIMENTEL NRO. 116', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (113, '20404883918', 'GETIMSA RAYMONDI', NULL, 'AV. RAYMONDI NRO 1001.', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (114, '20404883918', 'GETIMSA AFILADOR', NULL, 'CAS. AFILADOR CAR. TINGO MARIA A HUANUCO 2.5', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (115, '10009635128', 'POLLERIA PIUHICHO', NULL, 'AV. NICOLAS DE AYLLON N 441 CHACLACAYO - LIMA - LIMA', '010101', '960948488', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (116, '10043210675', 'ESTACION DE SERVICIOS VICTORIA', NULL, 'CAR. MARGINAL S-D CPME', '010101', '987654321', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (117, '10090647879', 'SALCEDO GUEVARA NESTOR', NULL, 'CAR. CENTRAL NRO 16.5 URB. HUAYCAN', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (118, '10091479791', 'EDS EX SAN JUANITO', '', 'AV. DE LOS HEROES 1187-1189', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (119, '10199887608', 'QUISPE YUPANQUI SILVIO', NULL, 'MZ D27 LOTE 13 AA.HH BOCANEGRA ZONA 3 AV. QUILCA FRENTE TOTUS DE  AV. QUILCA', '010101', '13621677', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (120, '20101127614', 'MANUEL IGREDA Y JULIO RIOS S.R.L.', NULL, 'CAL.MONTERREY NRO. 341 INT. 502 LIMA - LIMA - SANTIAGO DE SURCO', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (121, '20110623420', 'E/S INVERSIONES LUMARCO', NULL, 'CARRETERA CENTRAL KM 11.2 SANTA CLARA', '010101', '356-3047', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (122, '20124367850', 'INVERSIONES TRANSP. Y SERV. CINCO S.A.C.', NULL, 'AV. JAVIER PRADO ESTE NRO. 1059 URB. SANTA CATALINA (FRENTE AL COLG. SAN AGUSTIN)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (123, '20137926742', 'SERVICENTRO LOS ROSALES SA', NULL, 'AV. AYACUCHO NRO 140', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (124, '20138645607', 'GRIFO SANTO DOMINGO DE GUZMAN SRLTDA', NULL, 'AV. RAMIRO PRIALE LOTE. 23A ASC. DIGNIDAD NACIONAL (PARCELA L)', '010101', '3710079', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (125, '20161800920', 'LUBRIGAS S.R.LTDA.', NULL, 'Av. Nicolas Ayllon Nro. 3562 Fnd. Mayorazgo (Frente a Planta Qui lomica Suiza)', '010101', '0', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (126, '20177941591', 'EDS ORFUSAC', '9139', 'AV. JAVIER PRADO ESTE 6651 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (127, '20127765279', 'E/S TRAPICHE', 'E159309', 'AV. HEROES DEL ALTO CENEPA NRO. 697', '010101', '5373121 / 7150', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (128, '20203530073', 'SERVICENTRO SAN HILARION S.A.', NULL, 'AV. FLORES DE PRIMAVERA NRO. 1988 URB. SAN HILARION (MZ B - LT.03 / CRUCE CON AV.CTO.GRANDE)', '010101', '13893224', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (129, '20298736820', 'INVERSIONES UCHIYAMA SRL', NULL, 'CAL.CAHUIDE NRO. 160 P.J. J C MARIATEGUI SECT SAN GABRIEL B', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (130, '20348303636', 'Herco San Luis', '9050', 'AV. NICOLAS ARRIOLA NRO. 3191 ', '010101', '', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (131, '20343883936', 'ESTACION DE SERVICIO NIAGARA SRL.', NULL, 'JR. ELVIRA GARCIA Y GARCIA NRO. 2790 (ALT.COLONIAL Y UNIVERSITARIA)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (132, '20345774042', 'SERVICENTRO AGUKI S.A.', NULL, 'AV. ELMER FAUCETT 5482', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (133, '20347869849', 'LAS AMERICAS EIRL', 'E159286', 'AV. DE LAS AMERICAS NRO. 1259 URB. BALCONCILLO (GRIFO PETROPERU)', '010101', '987817538', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (134, '20348303636', 'HERCO GAMBETA ', 'E159247', 'AV. NESTOR GAMBETA NRO. S/N EX FUNDO MARQUEZ (SUBLOTE 1 1-A CARRET.VENTANILLA KM 14.5) PROV. CONST. ', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (135, '20371975561', 'REPRESENTACIONES JEMMS S.A.C.', NULL, 'AV. ALFREDO MENDIOLA NRO. 1085 URB. PALAO 2DA ETAPA', '010101', '5342336', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (136, '20373831124', 'PITS II GNV', '', 'AV. MARIANO CORNEJO NRO. 1508 (POR LA PLAZA DE LA BANDERA)', '010101', '994077048', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (137, '20377674686', 'SERVICENTRO SMILE S.A.', NULL, 'AV. SEPARADORA INDUSTRIAL/ORFEBREROS 129', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (138, '20402786729', 'INVERSIONES SANTA ROSA E.I.R.L', NULL, 'JR. MOQUEGUA NRO. 398 INT. 7 P.J. FLORIDA BAJA', '010101', '994893599', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (139, '20425788192', 'CENTRO GAS DIEGO EIRL', NULL, ' AV. LA MOLINA NRO. 401 URB. VULCANO (LETRERO MGAS - FRENTE GRIFO MOBIL)', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (140, '20448556663', 'FARMACIA SAN FRANCISCO S.A.C.', NULL, 'JR. HUANCANE 721 ', '010101', '051-336596', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (141, '20452799368', 'ESTACION FINLANDIA E.I.R.L.', NULL, 'AV. SIETE MZA. 9 LOTE. 02-A (ESQUINA DE AV. SIETE Y FINLANDIA)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (142, '20459020137', 'MARKET LAS BELENES S.A.C', NULL, 'JR. EL POLO 493 URB EL DERBY DE MONTERRICO', '010101', '966833946', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (143, '20478967111', 'ESTACION DE SERVICIO LURIN', NULL, 'MZ. C. LT 2 URB. LOS HUERTOS DE VILENA', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (144, '20486255171', 'E/S CORPORACION RIO BRANCO', NULL, 'CAR.PANAMERICANA NORTE KM. 92.5 C.P. CHANCAYLLO (BARRIO SAN JUAN PASANDO EL PUENTE)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (145, '20491287544', 'CLINICA CORAZON DE JESUS SAC', NULL, '	AV. MARISCAL BENAVIDES NRO. 565', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (146, '20492197417', 'JE OPERADORES SAC', '', 'AV. NESTOR GAMBETA KM. 7.10 MZA. B-6 LOTE. 4 COO. VIVIENDA DE TRAB. ENAPU - CALLAO - CALLAO', '010101', '5342336', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (147, '20492314154', 'INVERSIONES EBENEZER S.R.L', NULL, 'AV. 4 MZA. B4 LOTE. 03 P.J. NESTOR GAMBETTA BAJA ESTE (COSTADO DEL MERCADO ROJO GAMBETTA)', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (148, '20492920666', 'GASBEL', NULL, 'Av Micaela Bastidas cruce con 200 millas', '010101', '0', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (149, '20493089570', 'ESTACION DE SERVICIOS H & A', NULL, ' AV. UNIVERSITARIA NRO. S/N (CDRA 51 ESQUINA CON LA CALLE A), LOS OLIVOS, LIMA.', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (150, '20493091396', 'GASOCENTRO PUENTE NUEVO S.A.C.', NULL, 'AV. JOSE CARLOS MARIATEGUI N° 2397', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (151, '20494793521', 'ESTACION EL OVALO E.I.R.L.', '', 'AV. F. LEON DE VIVEIRO - Carr. Panamericana Sur 311', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (152, '20499102071', 'ALIMENTOS SELECTOS CAZTELLANI S.A.', NULL, 'AV. ARAVICUS NRO. 228 URB. TAHUANTISUYO (KM.5 AV.TUPAC AMARU C/CARLOS IZAS)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (153, '20501688593', 'CABLE VIDEO PERU S.A.C.', NULL, 'Jr. CONRAY GRANDE Nro. 4901 - Urb. PARQUE NARANJAL - Cdra.13 Av. NARANJAL', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (154, '20502716761', 'GAS ESCORPIO S.R.L.', NULL, 'AV. LA MOLINA NRO. 401 URB. VULCANO (ALT. OVALO SANTA ANITA) LIMA - LIMA - ATE', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (155, '20502825624', 'ESTACION DE SERVICIOS SAN JUANITO S.A.C.', NULL, 'AV. HEROES NRO. 1109 (ALT.HOSPITAL MARIA AUXILIADORA)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (156, '20505133430', 'PETROCARGO S.A.C', NULL, 'AV.GERARDO UNGER MZ. D LT. 26 INDEPENDENCIA ', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (157, '20506467854', 'CORPORACION JULCAN S.A.', NULL, 'AV. PROCERES DE LA INDEPENDEN NRO. 2556 URB. LOS ANGELES', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (158, '20511053031', 'ESTACION DE SERVICIO  GIO SAC', NULL, 'AV. PACHACUTEC NRO. 3859 P.J. CESAR VALLEJO ', '010101', '7190235', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (159, '20511193045', 'EDS EVEREST', NULL, 'AV. AVIACION NRO. 4285 (ALT.CDRA 42 AV.AVIACION)', '010101', '2718701', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (160, '20512853529', 'ATS AMERICA SAC', NULL, 'AV. LIMA SUR NRO. 895 CHOSICA LIMA - LIMA â€“ LURIGANCHO', '010101', '5342336', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (161, '20514636843', 'ESTACIONES DE SERVICIOS PETRO WORLD', NULL, 'AV. VENEZUELA ESQUINA CON AV. RIVA AGUERO ', '010101', '464-1114', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (162, '20516035758', 'GASOCENTRO NORTE SOCIEDAD ANONIMA CERRADA', NULL, 'AV. GERARDO UNGER NRO. 3301 URB. HABILIT.INDUST.PAN.NORTE (Fte.aq Comisaria Indep./ex GrifoMosquito)', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (163, '20517103633', 'AJ GROUP INVERGAS SAC', NULL, 'AV. SANTIAGO DE CHUCO NRO. 501 COO. UNIVERSAL (ESTACION DE SERVICIO)', '010101', '993059907', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (164, '20517117421', 'SHICHI-FUKU CORPORATION', NULL, 'AV. CANADA NRO. 298 URB. SANTA CATALINA', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (165, '20517735605', 'LUXOR PHARMACEUTICAL', NULL, 'AV. CESAR VALLEJO 895. ', '010101', '  ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (166, '20517855252', 'INVERSIONES DUVAL', NULL, ' AV. ALFREDO MENDIOLA NRO. 6200 INT. 101 URB. MOLITALIA (CRUCE CON AVENIDA MEXICO)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (167, '20518664019', 'AMEL PHARMA', NULL, 'JR. PARURO NRO. 926 INT. 381 (GALERIA CAPON CENTER) ', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (168, '20518960688', 'PITS II GNVsss', '', ' AV. NICOLAS DE PIEROLA NRO. 800 (MZ.H1 LT.16, ESQUINA CON AV. VILLA MARIA)', '010101', '994077048', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (169, '20521111888', 'EDS EGAR S.A.C.', NULL, 'AV. ALFREDO MENDIOLA NRO. 3973 INT. 201 URB. MICAELA BASTIDAS - LIMA - LIMA - LOS OLIVOS', '010101', '01 2502164', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (170, '20521431955', 'ECO TRADING S.A.C', NULL, 'AV. REPUBLICA ARGENTINA NRO. 798 URB. ZONA INDUSTRIAL (ALT. DE C.C. LA CACHINA) ', '010101', '971150078', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (171, '20524016070', 'DELTA  San Martin', '', 'AV. ALFREDO MENDIOLA NRO. 700 URB. INGENIERIA (ALT. CRUCE AV. HABICH E INGENIERIA A 2 C)', '010101', '965461201', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (172, '20524249848', 'CENTROGAS VISTA ALEGRE', NULL, 'Av. Nicolas Ayllon Nro. 4706 Fnd. Vista Alegre', '010101', '0', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (173, '20524359601', 'MABA FARMA', NULL, 'MZA. C LOTE. 4 JAZMIN DE OQUENDO (COSTADO MERCADO LA ALBORADA)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (174, '20530743919', 'DIESEL MAX', NULL, ' CAR.MAZO-VEGUETA NRO. SN C.P. LA PERLITA (GRIFO DIESEL MAX) ', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (175, '20534525070', 'EDS TRIVEÑO', NULL, 'AV. MATIAS MANZANILLA-2DO PIS NRO. 625 INT. 04 (FRENTE AL HOSPITAL DEL SEGURO SOCIAL)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (176, '20535614548', 'OPERADORES DE ESTACIONES', NULL, 'CIRCUNVALACION NRO. 1386 (ALT MERCADO DE FRUTAS) LIMA - LIMA - LA VICTORIA', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (177, '20538807037', 'HEVALFAR SRL', NULL, 'JR. PARURO NRO. 926 INT. 2072 GALERIA CAPON CENTER', '010101', '0', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (178, '20546454224', 'BOTIFARMA', NULL, 'JR. PARURO NRO. 926 INT. 2076', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (179, '20546818102', 'GRUPO AVTEC', NULL, 'CALLE MONTE GRANDE N° 109 OFICINA 307,  URB. CHACARILLA DEL ESTANQUE', '010101', '989054789', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (180, '20547011954', 'CENTROGAS IQUITOS', NULL, 'AV. IQUITOS NRO. 983 (CRUCE CON AV CATOLICA)', '010101', '949800092', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (181, '20548279039', 'CORGAS', NULL, 'LAS TORRES NRO. 497 URB. LOS SAUCES', '010101', '937504719', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (182, '20548480214', 'CATAFARMA ', NULL, 'AV. JOSE SANTOS CHOCANO NRO. 104 P.J. VEINTIDOS DE OCTUBRE (AL COSTADO DEL HOSP. SAN JOSE)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (183, '20551112781', 'GRUVENI', NULL, '	JR. LOS ANTROPOLOGOS MZA. D LOTE. 4 COO. LA UNION (MODULO DE PODER JUDICIAL DE PROCERES)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (184, '20551297978', 'GRANDINO', NULL, 'CAL.LOS CEREZOS NRO. 291 URB. DE LA LOTIZ. CHILLON', '010101', '5342336', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (185, '20551615856', 'INVERSIONES JIARA', NULL, 'AV. ESTEBAN CAMPODONICO NRO. 262 URB. SANTA CATALINA', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (186, '20555690534', 'DELTA ATE E', NULL, 'AV. NICOLAS AYLLON NRO. 3620 A.H. SANTA ILUMINATA (CARRETERA CENTRAL CRUCE AV ATE', '010101', '965451201', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (187, '20556597376', 'ESTACION DE SERVICIOS ANDAHUASI', NULL, 'AV. HUAURA - SAYAN LOTE. 100 (SECT. SANJUAN DE CAÃƒâ€˜AS.ESQUINA AV. RIO)', '010101', '974628921', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (188, '20557618920', 'CENTRAL PARIACHI', NULL, 'AV. NICOLAS AYLLON NRO. S/N SEMI RUSTICO PARIACHI PARCELA 10906 ', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (189, '20562897128', 'COFARSA', NULL, 'AV. GNRAL MIGUEL IGLESIAS NRO. 947 INT. A-B ZONA D (FRENTE AL HOSPITAL MARIA AUX)', '010101', '987946819', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (190, '20565949731', 'CONSORCIO VITAFARMA', NULL, 'JR. PARURO NRO. 775 INT. 307 URB. BARRIOS ALTOS', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (191, '20566091306', 'NG FARMA', NULL, 'AV. SANTA ROSA NRO. 1046 APV. LOS CHASQUIS (2DO.PISO.)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (192, '20566149151', 'GASNOR', NULL, 'AV. ENCALADA 232 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (193, '20566149401', 'ESTACIONES DEL NORTE', NULL, 'CAR.PANAN NORTE KM. 1168 P.J. BARRIO LETICIA', '010101', '13600374', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (194, '20600251873', 'BALIAN', NULL, 'AV. LAS PRADERAS MZA. U LOTE. 29 URB. PRADERA DE STA ANITA II E', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (195, '20600534883', 'CORPORACION GANAJUR', NULL, 'JR. ANCASH 14142 BARRIOS ALTOS', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (196, '20600658311', 'ESTACION TRAPICHE ', NULL, 'CAL.MONTE GRANDE NRO. 109 DPTO. 307 URB. CHACARILLA DEL ESTANQUE LIMA - LIMA - SANTIAGO DE SURCO', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (197, '20600868862', 'PETRO CALLAO', NULL, 'AV. ARGENTINA NRO. 498 URB. CHACARITAS PROV. CONST. DEL CALLAO - PROV. CONST. DEL CALLAO â€“ CALLAO', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (198, '20600908627', '524 CONSULTING', NULL, 'AV. LA ENCALADA NRO. 232 URB. CENTRO COMERCIAL MONTERRICO', '010101', '5342336', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (199, '20601709148', 'EESS PETRO LUMARA I', '', 'AV.LAS LOMAS Mz.Q LT1A URB ZARATE SJL.', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (200, '20601790484', 'INVERSIONES LUMIPHARMA', NULL, 'JR. ANTONIO MIROQUESADA NRO. 806 INT. 403 (ESQ.JR PARURO909 EDF COM MRQS 4PISO 403A)', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (201, '20602003427', 'PETRO NAZCA', NULL, 'AV. PANAMERICANA NRO. 891 URB. VISTA ALEGRE (GRIFO PECSA)', '010101', '5342336', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (202, '20602712363', 'CONSORCIO GAS DEL SUR', '', 'CAL. PANAMERICANA SUR KM. 33.5 URB. PREDIO LAS SALINAS', '010101', '', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (203, '20602772935', 'DISTRIBUCIONES SELMAC', NULL, 'JR. PARURO NRO. 926 INT. 212 URB. BARRIOS ALTOS ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (204, '20603012268', 'INVERSIONES RAMSAN', NULL, 'JR. PEDRO GARENZON NRO. 500 URB. MIGUEL GRAU (FRENTE AL CLUB DE TIRO-ANCON)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (205, '20603635711', 'A & B DROFAR INVERSIONES', NULL, 'JR. CUSCO NRO. 811 INT. 207 URB. BARRIOS ALTOS (ESPALDA DE RENIEC)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (206, '20603673485', 'MATIAS & ALEXA', NULL, 'JR. PARURO NRO. 926 INT. 345B GALERIA CENTRO COMERCIAL CAPON CENTER', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (207, '20603821247', 'CORPORACION DISFARMED', NULL, '-', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (208, '20603822359', 'DROGUERIA DISTRIBUIDORA PRIMED', NULL, 'Av. Gral. Miguel Iglesias Mz.g Lt.30 - AA.HH. Javier Heraud ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (209, '20603850913', 'A.J.C. NEGOCIOS', NULL, 'CAL.ALVARADO NRO. 701 (KM. 11 DE LA AV. TUPAC AMARU)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (210, '20603906790', 'TRANSPORTES VALL E', NULL, 'CAL.ALVARADO NRO. 701 (KM. 11 DE LA AV. TUPAC AMARU)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (211, '20604631379', 'MOVI PETROL ', NULL, 'LA ENCALADA NRO. 232 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (212, '20604857105', 'CORPORACION AXELL', NULL, 'JR. HUANTA NRO. 944 INT. B URB. BARRIOS ALTOS', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (213, '20605002448', 'SERVICENTROS CELESTE 3', NULL, 'AV. QUILCA MZA. E LOTE. 29 URB.  AEROPUERTO  PROV. CONST. DEL CALLAO', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (214, '20334129595', 'GRIFO SERVITOR', NULL, '-', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (215, '20371826727', 'GRIFO MASTER', '', 'AV. ALFREDO MENDIOLA ESQ. CALLE 12', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (216, '20511172633', 'GRIFO DENVER', NULL, '-', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (217, '20517053351', 'MASGAS', NULL, '-', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (218, '20517231631', 'PANAMERICAN GAS TRADING', NULL, '-', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (219, '20517700640', 'SIROCO MANCO CAPAC', '9125', 'Av.Manco Capac 301', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (220, '20538108295', 'DUOGAS', NULL, '-', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (221, '20020020000', 'DEMO GS', NULL, 'Condevilla', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (222, '12345678901', 'DEMO', NULL, 'AV. LT PLAZA NORTE', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (223, '20101127614', 'GRIFOSA LA MARINA', '', ' Av. La Marina 1305 - San Miguel  - Lima. / Dom. Fiscal : CAL.MONTERREY NRO. 373 INT. 701 URB. CHACARILLA DEL ESTANQUE LIMA - LIMA - SANTIAGO DE SURCO', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (224, '20519069262', 'OFICINA', '', 'AV. AUGUSTO B LEGUIA NRO. 307 COO. POLICIAL (ACONT DE AV. PERU -ANTES DE ZARUMILLA)', '010101', '9999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (225, '20348303636', 'ESTACION DE SERVICIOS HERCO S.A.C.', '', 'CAR.PANAMERICANA SUR NUEVA KM. 33.5 MZA. C LOTE. 14 SECTOR LAS SALINAS (GRIFO HERCO) ', '010101', '', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (226, '20601351944', 'NEGOCIACIONES VALERIA & CHRIS S.A.C', NULL, 'AV. LIMA SUR NRO. S/N CHOSICA (LT A2-1 A1-9) ', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (227, '20392479687', 'INVERSIONES CORONACION S.R.L.', NULL, 'MZ E LT 10 SECTOR CENTRAL HUERTOS DE MANCHAY.', '010101', '942416053 - 66', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (228, '20515789961', 'GAMA INVERSIONES GENERALES S.A.C.', NULL, 'AV. QUILCA CUADRA 11 S/N MZA. E LOTE. 29 URB. AEROPUERTO PROV. CONST. DEL CALLAO', '010101', '  ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (229, '20602359981', 'PUNTO GAS S.A.C.', NULL, 'AV. MARISCAL OSCAR T. BENAVIDES NRO. 1657 URB. LA TRINIDAD (ALTURA CDRA. 16 EX COLONIAL) ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (230, '20604271089', 'ESTACION DE SERVICIOS VICTORIA L & K HNAS. S.A.C.', NULL, 'JR. LAS ACACIAS NRO. SN (1 CDRA DEL GRIFO HUANCABAMBA)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (231, '20522168182', 'FARMACIAS INTEGRALES DE LA SOLIDARIDAD S.A.C.', NULL, 'AV. ANGAMOS ESTE NRO. 716 (HOSPITAL DE SOLIDARIDAD DE SURQUILLO)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (232, '20600765044', 'EUCEL S.R.L.', NULL, 'PJ. SAN MARTIN MZA. L LOTE. 3 CANTO CHICO', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (233, '20605395482', 'DROGUERIA LMG FARMA PERU S.A.C.', NULL, 'UPIS SAN JOSE MZA. G-1 LOTE. 4 INT. 2 ', '010101', ' (01) 4045448 ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (234, '20514721280', 'GRUPO INTI S.A.C', NULL, 'Jr. Dante 893 - 899', '010101', '447-3684', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (235, '20338926830', 'GRIFO VALERIA VICTORIA S.A.C.', NULL, 'AV. RIVA AGUERO NRO. 411', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (236, '20484056227', 'OLEOCENTRO Y SERVICIOS SAN PEDRO EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA', NULL, 'MZA. 30 LOTE. 01 A.H. SAN PEDRO', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (237, '20546828671', 'DRUGSTORE SOL FARMA CORP. E.I.R.L.', NULL, 'CAL.CORACEROS NRO. 158', '010101', '4270669 / 9794', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (238, '20605344896', 'DROGUERIA DISTRIBUIDORA E IMPORTADORA VILLALEON E.I.R.L', NULL, 'JR. HUANTA NRO. 944 INT. B URB.  BARRIOS ALTOS', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (239, '10214719717', 'HILARIO CHAUCA GILMAR MARCIANO', NULL, 'AV. AVIACION S/N ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (240, '20605224505', 'BOTICA NUEVO PERU E.I.R.L.', NULL, 'AV. LOS PINOS NRO. 1414 URB. EL PINAR', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (241, '20604193711', 'INVERSIONES P & M FARMA E.I.R.L.', NULL, 'JR. MERCURIO NRO. 172 URB. SAN CARLOS II ETP', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (242, '10702311191', 'ALIAGA PEREZ LEONARDO CARLOS', NULL, 'S/N', '010101', ' 932 475 889', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (243, '20605809686', 'GRIFOS ESSA PUCALLPA S.A.C.', NULL, 'AV. FEDERICO BASADRE NRO. 298', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (244, '20100111838', 'GESA SOL DE ORO', NULL, 'AV. ALFREDO MENDIOLA 3550', '010101', ' ', '', 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (245, '20503840121', 'EESS ARRIOLA', NULL, 'Av.Nicolas Arriola 2140', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (246, '20503840121', 'EESS IQUITOS', NULL, 'AV.IQUITOS 1100', '010101', '', '', 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (247, '20503840121', 'EESS REVOREDO', NULL, 'Av. Elmer Faucett # 2900.', '010101', '', '', 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (248, '20503840121', 'EESS TRIESTAR', NULL, 'Av. Proceres de la Independencia 1487 Urb. Los Jardines ', '010101', '', '', 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (249, '20503840121', 'EESS ANGAMOS', NULL, 'Av.Angamos Este  1715', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (250, '20503840121', 'EESS CANTOLAO', '', 'Av. Elmer Faucett 6000', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (251, '20503840121', 'EESS DUEÑAS\r\n', NULL, 'Av. Nicolas Dueñas # 606', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (252, '20503840121', 'EESS CHACARILLA', NULL, 'AV. PRIMAVERA  N° 1212', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (253, '20503840121', 'EESS LAS TIENDAS\r\n', NULL, 'Av. Andres Aramburu 904-908', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (254, '20503840121', 'EESS GRACCO\r\n', NULL, 'AV. LOS INGENIEROS MZA. E. LOTE. 14-2 (con Av. Separadora Industrial 2503)', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (255, '20546740154', 'PRECIO S.A.C.', NULL, 'AV. LA ENCALADA NRO. 232 URB. CENTRO COMERCIAL MONTERRICO (A 2 CUADRAS DE VIVANDA)', '010101', '5342336', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (256, '20605764712', 'DROGUERIA JJC S.A.C.', NULL, 'CAL.LAS ANTILLAS NRO. 150 URB. ISLA VERDE', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (257, '20603587481', 'CONSORCIO EFE S.A.C.', NULL, 'AV. JOSE SANTOS CHOCANO NRO. 128 P.J. VEINTIDOS DE OCTUBRE ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (258, '20557398628', 'DISTRIBUIDORA V & G FARMA S.R.L.', NULL, 'CAL.LOS PETALOS NRO. 189 URB. LA ACHIRANA', '010101', ' 987047349 / 9', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (259, '20553772436', 'GASOCENTRO ICA S.A.', NULL, '-', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (260, '20100079179', 'ESTACION DE SERV BOLIVAR S A', NULL, '-', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (261, '20605955704', 'PETRO LAS LOMAS S.A.C.', NULL, 'CAL.LOS PROCERES NRO. S/N PBLO. LAS LOMAS PIURA - PIURA - LAS LOMAS', '010101', '5342336', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (262, '20605720847', 'GRIFOS ESSA DE TINGO MARIA S.A.C', NULL, 'CAR.TINGO MARIA A HUANUCO NRO. 2.5 CAS. AFILADOR HUANUCO', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (263, '20565966589', 'CORPORACION VICTORIA PERUANA S.A.C', NULL, 'Calle Los Detectives Mz. F2 Lt. L1 Urb. Honor y Lealtad ', '010101', '(056) 283040', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (264, '10463388089', 'CAHUAPAZA APAZA FREDY EDWIN', NULL, 'Jr. Bolivar 153', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (265, '10411640855', 'AGUIRRE ZURITA CARLOS JAVIER', NULL, 'JR LEONCIO PRADO 1008', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (266, '10458612256', 'CORZO LAYME MARIA LORENA', NULL, 'Mz.188 Lt.10 AA.HH. Huascar', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (267, '10060507291', 'CORZO OCANA EFRAIN', NULL, '', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (268, '20549666362', 'CONSORCIO MEDICORP & SALUD S.A.C.', NULL, 'AV. LAS FLORES DE PRIMAVERA NRO. 1045 URB. LAS FLORES', '010101', '950279527', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (269, '20606082917', 'INVERSIONES FARMACEUTICA DIAZ S.A.C', NULL, '	JR. LAS CALEZAS NRO. 131 (ALTURA DE PLAZA VEA RIMAC)', '010101', '01-7151990', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (270, '20601136059', 'FARMA SOLUTIONS E.I.R.L.', NULL, 'Calle Loma Los Crisantemos 117', '010101', '960207974', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (271, '20100111838', 'GESA CAÑETE', NULL, 'Antigua Panamericana Sur m. 143.5', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (272, '20100111838', 'GESA LA MOLINA', NULL, 'Av. La Molina Este 1596', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (273, '20127765279', 'E/S CHORRILLOS', '', 'Av.  Defensores del Morro 1391', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (274, '20335757697', 'WO SOCIEDAD ANONIMA', NULL, 'AV. GUILLERMO PRESCOTT NRO. 202 URB.  RISSO', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (275, '20402173476', 'EESS SAN LUIS', NULL, 'Av. Nicolas Arriola Nº 2400', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (276, '20127765279', 'E/S SURCO', '9002', 'Av. Circunvalación 377 Urb. San Ignacio de Loyola Monterrico', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (277, '20127765279', 'E/S LA VICTORIA', '9081', 'Av. Isabel La Catolica 077', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (278, '20127765279', 'E/S CASTILLA PIURA I', '9127', 'Av. Luis Moreno S/N Miraflores I Etapa Castilla', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (279, '20602629750', 'E/S BELA', '', 'AV. PETIT THOUARS N° 3929 ESQUINA  CON CALLE CHACARILLA ', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (280, '20451706323', 'ESTACION DE SERVICIOS VAMA SAC', NULL, 'AV. DE LOS HEROES NRO. 1187 URB.  SAN JUANITO  (AL FRENTE HOSP. MARIA AUXILIADORA)', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (281, '20451706323', 'ESTACION DE SERVICIOS VAMA SAC', NULL, '-', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (282, '20127765279', 'E/S SAN BORJA', '9080', 'AV.AGUSTIN DE LA ROSA TORO N° 1312-1358 URB.JACARANDA II SECTOR 5 SAN BORJA LIMA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (283, '20118180306', 'ESTACION CORMAR S.A.', '', 'AV. NICOLAS AYLLON NRO. 3456 URB.  VILLA SANTA ANITA', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (284, '20118180306', 'E/S CORMAR', '9031', 'Carretera Central km 2.5', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (285, '20600658311', 'GASORED', 'E159311', 'Urb. Tungasuca II Mz. A Lt. 65 y 66 CARABAYLLO - LIMA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (286, '20517700640', 'SIROCO FAUCETT', '9149', 'Av.Elmer Faucett 735 Callao', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (287, '20601709148', 'EESS PETRO LUMARA II', '9045', 'AV. CHINCHASUYO Nº 710 SJL - LIMA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (288, '20487514749', 'E/S CHICLAYO', '', 'AV. FELIPE SALAVERRY NRO. 930 URB.  PATAZCA  (CERCA A GRIFO)', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (289, '20100111838', 'BELA', NULL, '-', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (290, '20457948060', 'XIN XING S.A.', NULL, 'JR. MIRO QUESADA NRO. 1308 LIMA LIMA LIMA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (291, '20457948060', 'EESS XIN XONG ARGENTINA', '9041', 'Av. Argentina 898', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (292, '20127765279', 'OFIC. JAVIER PRADO', '', 'JAVIER PRADO S/N', '010101', NULL, NULL, 0, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (293, '20100111838', 'GESA SANTA MARIA HUACHO', '', 'Panam. Norte Km 147.5', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (294, '20600465237', 'ES BREÑA', '9044', 'Jr. Huaraz Nº 1484  Lima Lima Breña', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (296, '20517453618', 'ES VMT', '9082', 'Av El Triunfo # 210 VMT', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (297, '20517767396', 'EDS / ESCOH ICA', 'E119215', 'Av. Fernando Leon de Vivero s/n ICA - ICA  ', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (298, '20101285449', 'EDS SANTA CRUZ', '', 'AV. NARANJAL 299 NRO. C INT. 15 URB. NARANJAL-INDUSTRIAL LIMA - LIMA - INDEPENDENCIA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (299, '20515657119', 'EESS ASSA LA VICTORIA', '', 'AV. JAIME BAUZATE Y MEZA NRO. 1050 LIMA LIMA LA VICTORIA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (300, '20100111838', 'GESA TOMAS VALLE', '20100111838', 'Av. Tomas Valle Parcela F Sección C ', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (301, '20348303636', 'HERCO LURIN', 'E159317', 'Panamericana Sur Km. 33.5. Urb. Predio Las Salinas - Lima', '010101', NULL, NULL, 0, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (302, '20604303029', 'ADMINISTRACION DE GRIFOS L&L ONE S.A.C.', NULL, 'JR. MONTE ROSA NRO. 256 INT. 902 URB. CHACARILLA DEL ESTANQUE LIMA LIMA SANTIAGO DE SURCO', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (303, '20604303029', 'GRIFOS LLONE', 'E159336', 'Av. Universitaria Mz. B  Lt. Ref. Programa de vivienda santa Rosa de Carabayllo', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (304, '20604302863', 'ADMINISTRACION DE GRIFOS LEP S.A.C.', NULL, 'JR. MONTE ROSA NRO. 256 INT. 902 URB. CHACARILLA DEL ESTANQUE LIMA LIMA SANTIAGO DE SURCO', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (305, '20604302863', 'EDS Picorp José Granda', '9016', 'Av. José Granda 3210 ', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (306, '10704012964', 'POLO GOMEZ BRYAN MARTIN', '', 'PRUEBA', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (307, '20127765279', 'E/S CUSCO', 'E159347', 'AV. LA CULTURA Nª 1506 SAN SEBASTIAN ', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (308, '20385649194', 'SERVICENTRO TITI S.A.C.', NULL, 'AV. PABLO PATRON NRO. 120 URB. SAN PABLO LIMA LIMA LA VICTORIA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (309, '20605899715', 'Go Orue', '9029', 'Av. El Derby # 254 int 704 Lima - Lima - SANTIAGO DE SURCO', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (311, '20348303636', 'HERCO GNC', '', 'AV. NESTOR GAMBETA NRO. S/N EX FUNDO MARQUEZ (SUBLOTE 1 1-A CARRET.VENTANILLA KM 14.5) PROV. CONST.', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (312, '20601697531', 'EESS HA', '9193', 'AV. ALFREDO MENDIOLA NRO 6810 - SMP', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (313, '20127765279', 'E/S NAZCA', 'E159350', 'CARRETERA PANAMERICANA SUR KM. 446 SECTOR CURVE S/N NAZCA ICA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (314, '20553368902', 'EESS COLPE S.A.C.', 'E129318', 'CAL. REAL NRO. 588 CERCADO DE EL TAMBO JUNIN HUANCAYO EL TAMBO', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (315, '11111111111', 'Sucursal DEMO RC', '', 'demo', '010101', NULL, NULL, 0, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (316, '20549745076', 'GASOCENTRO SANTA ANA S.A.C', 'E159293', 'AV. LOS PROCERES MZA. D-2 LOTE. 41 URB. SANTA ANA LIMA LIMA LOS OLIVOS', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (317, '20537901277', 'E/S PYX Gamarra', NULL, 'Av. Angélica Gamarra 1436', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (318, '20547799845', 'E/S Gas Surco', '9089', 'Av. Guardia civil Mz B Lote 1-2-3 con Calle Doña Mercedes', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (319, '20547799845', 'E/S GO MANCO CAPAC', '9089', 'AV. MANCO CAPAC NRO. 693 LIMA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (320, '20565643496', 'EESS AVA SAN LUIS', '', 'Av. Nicolás Ayllón 1711', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (321, '10704012964', 'PRUEBA', '', 'DADASD', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (322, '2051199502', 'Gazel Juanes', '', 'Carretera panamerica sur, km 307', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (323, '20566238927', 'E/S CANTA CALLAO', 'E159351', ' AV. CANTA CALLAO MZA. D LOTE. 11 URB. HUERTOS DEL NARANJAL (A MEDIA CDRA. DEL CIRCUITO DE MANEJO)', '010101', NULL, NULL, 0, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (324, '20492841014', 'GANAGAS S.A.C.', NULL, 'Av.Los Proceres 655 SANTIAGO DE SURCO LIMA', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (325, '20507248676', 'VIJOGAS S.A.C.', NULL, 'AV. SANTA ROSA NRO. 610 URB. LOS SAUCES LIMA LIMA ATE', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (326, '20507248676', 'E/S Vijogas Ate', '', 'AV. SANTA ROSA NRO. 610 URB. LOS SAUCES', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (327, '20538289656', 'EESS CONSORCIO MICE', 'E159218', 'AV.MARIA REICHE N° S/N URB PACHACAMAC (ESQ. CON AV. SEPARADORA INDUSTRIAL) VILLA EL SALVADOR - LIMA', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (328, '20493143612', 'EESS MASUR', NULL, 'AV. DERBY NRO. 254 INT. 704 LIMA LIMA SANTIAGO DE SURCO', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (329, '20510954999', 'EESS EL SOL', NULL, 'AV.EL SOL EQ.AV.GUARDIA C NRO. S/N LIMA LIMA CHORRILLOS', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (330, '20510957581', 'SERVICENTRO SHALOM SAC', NULL, 'AV. EL DERBY NRO. 254 INT. 704 URB. EL DERBY DE MONTERRICO LIMA LIMA SANTIAGO DE SURCO', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (331, '20510957581', 'EDS Shalom', '9056', 'Av. Naciones Unidas 1222 Cercado de Lima ', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (332, '20127765279', 'E/S GAMARRA II', '', 'Av. La Paz 1480  Esq. Con Agustin Gamarra', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (333, '20519251656', 'INVERSIONES GASSURCO S.A.C', NULL, 'AV. EL DERBY NRO. 254 INT. 704 URB. EL DERBY DE MONTERRICO LIMA LIMA SANTIAGO DE SURCO', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (334, '20519251656', 'GASSURCO', '', 'AV. GUARDIA CIVIL MZ B LOTE 1-2-3 CON CALLE DOÑA MERCEDES', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (335, '20503840121', 'EESS CHUBUT', 'E159310', 'AV. VENEZUELA 4647', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (336, '20503840121', 'EESS COLONIAL A', '', 'Av. Óscar R. Benavides 930, Lima 15081', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (337, '20517710955', 'EESS GO PRIALE / SANTA CESILIA', '', 'AV. EL DERBY NRO. 254 INT. 704 URB. EL DERBY DE MONTERRICO LIMA LIMA SANTIAGO DE SURCO', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (338, '20606092998', 'EESS JUDY', '', 'EXFUNDO NARANJAL PARCELA 59 ', '010101', NULL, NULL, 0, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (339, '20127765279', 'E/S PUENTE PIEDRA', '', 'Av. San Juan de Dios, Puente Piedra', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (340, '20593472244', 'ALLIN GROUP - JAVIER PRADO S.A.', '', 'JIRON PINOS 308', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (341, '20100111838', 'GESA BRASIL', '', 'Magdalena', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (342, '20565643496', 'AVA PETIT THOUARS', '', 'AV Arequipa', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (343, '20565643496', 'AVA BOLIVAR', '', 'Parque de las aguas', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (344, '20127765279', 'ALTOMOCHE', '00000', 'PAN. NORTE SUB LOTE 1B GRUPO A SECTOR V TRUJILLO LA LIBERTAD', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (345, '20492898717', 'ECOMOVIL SOCIEDAD ANONIMA CERRADA', NULL, 'AV. PROLONGACION PRIMAVERA NRO. 120 INT. A316 LIMA LIMA SANTIAGO DE SURCO', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (346, '20492898717', 'ECOMOVIL', '', 'SMP', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (347, '20492898717', 'ECOMOVIL', '', 'SMP', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (350, '20168217723', 'EESS MOLINA', '', 'Molina', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (351, '20168217723', 'EESS LURIN', '', 'MolinaLURIN', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (352, '20565643496', 'AVA PRIMAVERA', '', '-', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (353, '20565643496', 'Ava Finlandia ', '', 'Av. Finlandia MZ A- D urb San idelfonso', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (354, '20606092998', 'CORPORACION JUDY SAC', '', 'EX FUNDO NARANJAL PARCELA 59', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');

-- ----------------------------
-- Table structure for tb_tipo_estacion
-- ----------------------------
DROP TABLE IF EXISTS `tb_tipo_estacion`;
CREATE TABLE `tb_tipo_estacion`  (
  `id_tipo_estacion` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_tipo_estacion`) USING BTREE,
  UNIQUE INDEX `unique_descripcion`(`descripcion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_tipo_estacion
-- ----------------------------
INSERT INTO `tb_tipo_estacion` VALUES (1, 'GNV', 1, '2024-07-27 22:25:59', '2024-07-27 22:26:02');
INSERT INTO `tb_tipo_estacion` VALUES (2, 'GLP Y LIQUIDOS', 1, '2024-07-27 22:27:06', '2024-07-27 22:27:08');
INSERT INTO `tb_tipo_estacion` VALUES (3, 'GNC', 1, '2024-07-27 22:27:37', '2024-07-27 22:27:40');
INSERT INTO `tb_tipo_estacion` VALUES (4, 'GNL', 1, '2024-07-27 22:29:13', '2024-07-27 22:29:16');
INSERT INTO `tb_tipo_estacion` VALUES (5, 'OFICINA', 1, '2024-07-27 23:44:10', '2024-07-27 23:44:14');

-- ----------------------------
-- Table structure for tb_tipo_incidencia
-- ----------------------------
DROP TABLE IF EXISTS `tb_tipo_incidencia`;
CREATE TABLE `tb_tipo_incidencia`  (
  `id_tipo_incidencia` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_tipo_incidencia`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_tipo_incidencia
-- ----------------------------
INSERT INTO `tb_tipo_incidencia` VALUES (1, 'REMOTO', 1, '2024-07-27 21:38:54', '2024-07-27 21:38:57');
INSERT INTO `tb_tipo_incidencia` VALUES (2, 'PRESENCIAL', 1, '2024-07-27 21:39:42', '2024-07-27 21:39:45');

-- ----------------------------
-- Table structure for tb_tipo_soporte
-- ----------------------------
DROP TABLE IF EXISTS `tb_tipo_soporte`;
CREATE TABLE `tb_tipo_soporte`  (
  `id_tipo_soporte` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_tipo_soporte`) USING BTREE,
  UNIQUE INDEX `unique_descripcion`(`descripcion`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_tipo_soporte
-- ----------------------------
INSERT INTO `tb_tipo_soporte` VALUES (1, 'Soporte Tecnico', 1, '2024-07-27 22:30:16', '2024-07-27 22:30:16');
INSERT INTO `tb_tipo_soporte` VALUES (2, 'Visita Tecnica', 1, '2024-07-27 22:30:21', '2024-07-27 22:30:21');
INSERT INTO `tb_tipo_soporte` VALUES (3, 'Soporte Nocturno', 1, '2024-07-27 22:30:23', '2024-07-27 22:30:23');
INSERT INTO `tb_tipo_soporte` VALUES (4, 'Mantenimiento ', 1, '2024-07-27 22:30:26', '2024-07-27 22:30:26');
INSERT INTO `tb_tipo_soporte` VALUES (5, 'Cambio Servidor', 1, '2024-07-27 22:30:30', '2024-07-27 22:30:30');
INSERT INTO `tb_tipo_soporte` VALUES (6, 'Actualizacion de Sistema', 1, '2024-07-27 22:34:19', '2024-07-27 22:34:19');
INSERT INTO `tb_tipo_soporte` VALUES (7, 'Mantenimiento Impresora', 1, '2024-07-27 22:34:22', '2024-07-27 22:34:22');

-- ----------------------------
-- Table structure for tb_vis_asignadas
-- ----------------------------
DROP TABLE IF EXISTS `tb_vis_asignadas`;
CREATE TABLE `tb_vis_asignadas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_visitas` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `creador` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time(0) NOT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_visitas`(`id_visitas`) USING BTREE,
  CONSTRAINT `tb_vis_asignadas_ibfk_1` FOREIGN KEY (`id_visitas`) REFERENCES `tb_visitas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_vis_asignadas
-- ----------------------------
INSERT INTO `tb_vis_asignadas` VALUES (13, 8, 3, 1, '2025-02-18', '18:38:22', '2025-02-18 18:38:22');
INSERT INTO `tb_vis_asignadas` VALUES (14, 9, 4, 1, '2025-02-18', '19:49:26', '2025-02-18 19:49:26');
INSERT INTO `tb_vis_asignadas` VALUES (15, 10, 4, 1, '2025-02-18', '20:21:18', '2025-02-18 20:21:18');
INSERT INTO `tb_vis_asignadas` VALUES (16, 9, 3, 1, '2025-02-18', '23:13:28', '2025-02-18 23:13:28');
INSERT INTO `tb_vis_asignadas` VALUES (17, 11, 5, 1, '2025-02-24', '12:47:17', '2025-02-24 12:47:17');
INSERT INTO `tb_vis_asignadas` VALUES (18, 12, 3, 1, '2025-02-26', '12:00:19', '2025-02-26 12:00:19');
INSERT INTO `tb_vis_asignadas` VALUES (19, 13, 5, 1, '2025-02-26', '12:03:45', '2025-02-26 12:03:45');
INSERT INTO `tb_vis_asignadas` VALUES (20, 14, 5, 1, '2025-02-26', '15:10:17', '2025-02-26 15:10:17');
INSERT INTO `tb_vis_asignadas` VALUES (21, 15, 5, 1, '2025-02-26', '16:28:05', '2025-02-26 16:28:05');
INSERT INTO `tb_vis_asignadas` VALUES (22, 16, 3, 1, '2025-03-03', '01:50:30', '2025-03-03 01:50:30');
INSERT INTO `tb_vis_asignadas` VALUES (23, 17, 3, 1, '2025-03-05', '12:30:10', '2025-03-05 12:30:10');
INSERT INTO `tb_vis_asignadas` VALUES (24, 18, 4, 1, '2025-03-05', '12:30:45', '2025-03-05 12:30:45');
INSERT INTO `tb_vis_asignadas` VALUES (25, 19, 3, 1, '2025-03-05', '13:09:39', '2025-03-05 13:09:39');
INSERT INTO `tb_vis_asignadas` VALUES (26, 20, 3, 1, '2025-03-05', '13:11:10', '2025-03-05 13:11:10');

-- ----------------------------
-- Table structure for tb_vis_seguimiento
-- ----------------------------
DROP TABLE IF EXISTS `tb_vis_seguimiento`;
CREATE TABLE `tb_vis_seguimiento`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_visitas` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time(0) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Iniciado / 1: Finalizado',
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_visitas`(`id_visitas`) USING BTREE,
  CONSTRAINT `tb_vis_seguimiento_ibfk_1` FOREIGN KEY (`id_visitas`) REFERENCES `tb_visitas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_vis_seguimiento
-- ----------------------------
INSERT INTO `tb_vis_seguimiento` VALUES (4, 9, 1, '2025-02-18', '20:40:46', 0, '2025-02-18 20:40:46');
INSERT INTO `tb_vis_seguimiento` VALUES (5, 9, 1, '2025-02-23', '23:47:55', 1, '2025-02-23 23:47:55');
INSERT INTO `tb_vis_seguimiento` VALUES (7, 10, 1, '2025-02-24', '12:17:21', 0, '2025-02-24 12:17:21');
INSERT INTO `tb_vis_seguimiento` VALUES (8, 10, 1, '2025-02-24', '12:18:24', 1, '2025-02-24 12:18:24');
INSERT INTO `tb_vis_seguimiento` VALUES (12, 11, 1, '2025-02-24', '12:17:21', 0, '2025-02-24 12:17:21');
INSERT INTO `tb_vis_seguimiento` VALUES (13, 11, 1, '2025-02-24', '15:40:41', 1, '2025-02-24 15:40:41');
INSERT INTO `tb_vis_seguimiento` VALUES (14, 14, 5, '2025-02-26', '15:24:42', 0, '2025-02-26 15:24:42');
INSERT INTO `tb_vis_seguimiento` VALUES (15, 14, 5, '2025-02-26', '15:28:18', 1, '2025-02-26 15:28:18');
INSERT INTO `tb_vis_seguimiento` VALUES (16, 15, 5, '2025-02-26', '16:33:43', 0, '2025-02-26 16:33:43');
INSERT INTO `tb_vis_seguimiento` VALUES (17, 16, 3, '2025-03-03', '12:21:02', 0, '2025-03-03 12:21:02');
INSERT INTO `tb_vis_seguimiento` VALUES (18, 16, 3, '2025-03-03', '12:30:30', 1, '2025-03-03 12:30:30');
INSERT INTO `tb_vis_seguimiento` VALUES (19, 17, 3, '2025-03-05', '12:40:48', 0, '2025-03-05 12:40:48');
INSERT INTO `tb_vis_seguimiento` VALUES (20, 17, 3, '2025-03-05', '12:49:18', 1, '2025-03-05 12:49:18');
INSERT INTO `tb_vis_seguimiento` VALUES (21, 20, 3, '2025-03-05', '13:13:07', 0, '2025-03-05 13:13:07');

-- ----------------------------
-- Table structure for tb_visitas
-- ----------------------------
DROP TABLE IF EXISTS `tb_visitas`;
CREATE TABLE `tb_visitas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sucursal` int(11) NOT NULL,
  `id_creador` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time(0) NOT NULL,
  `estado` int(1) NULL DEFAULT 0,
  `contingencia` tinyint(1) NULL DEFAULT 0,
  `eliminado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_sucursal`(`id_sucursal`) USING BTREE,
  INDEX `id_creador`(`id_creador`) USING BTREE,
  CONSTRAINT `tb_visitas_ibfk_1` FOREIGN KEY (`id_sucursal`) REFERENCES `tb_sucursales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_visitas_ibfk_2` FOREIGN KEY (`id_creador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_visitas
-- ----------------------------
INSERT INTO `tb_visitas` VALUES (8, 121, 1, '2025-02-20', '18:38:22', 0, 0, 1, '2025-02-18 19:45:16');
INSERT INTO `tb_visitas` VALUES (9, 121, 1, '2025-02-20', '19:49:26', 2, 0, 0, '2025-02-18 19:49:26');
INSERT INTO `tb_visitas` VALUES (10, 284, 1, '2025-02-21', '20:21:18', 2, 0, 0, '2025-02-18 20:21:18');
INSERT INTO `tb_visitas` VALUES (11, 121, 1, '2025-02-27', '12:47:17', 2, 0, 0, '2025-02-24 12:47:17');
INSERT INTO `tb_visitas` VALUES (12, 17, 1, '2025-02-26', '12:00:19', 0, 0, 1, '2025-02-26 12:00:19');
INSERT INTO `tb_visitas` VALUES (13, 20, 1, '2025-02-26', '12:03:45', 0, 0, 1, '2025-02-26 12:03:45');
INSERT INTO `tb_visitas` VALUES (14, 284, 1, '2025-02-27', '15:10:17', 2, 0, 0, '2025-02-26 15:10:17');
INSERT INTO `tb_visitas` VALUES (15, 23, 1, '2025-02-26', '16:28:05', 1, 0, 1, '2025-02-26 16:28:05');
INSERT INTO `tb_visitas` VALUES (16, 121, 1, '2025-03-05', '01:50:30', 2, 0, 0, '2025-03-03 01:50:30');
INSERT INTO `tb_visitas` VALUES (17, 121, 1, '2025-03-05', '12:30:10', 2, 0, 0, '2025-03-05 12:30:10');
INSERT INTO `tb_visitas` VALUES (18, 284, 1, '2025-03-05', '12:30:45', 0, 0, 0, '2025-03-05 12:30:45');
INSERT INTO `tb_visitas` VALUES (19, 20, 1, '2025-03-05', '13:09:39', 0, 0, 0, '2025-03-05 13:09:39');
INSERT INTO `tb_visitas` VALUES (20, 121, 1, '2025-03-05', '13:11:10', 1, 0, 0, '2025-03-05 13:11:10');

-- ----------------------------
-- Table structure for tipo_usuario
-- ----------------------------
DROP TABLE IF EXISTS `tipo_usuario`;
CREATE TABLE `tipo_usuario`  (
  `id_tipo_acceso` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `estatus` tinyint(1) NULL DEFAULT 1,
  PRIMARY KEY (`id_tipo_acceso`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tipo_usuario
-- ----------------------------
INSERT INTO `tipo_usuario` VALUES (1, 'Gerencia', 'success', 1);
INSERT INTO `tipo_usuario` VALUES (2, 'Administrativo', 'info', 1);
INSERT INTO `tipo_usuario` VALUES (3, 'Tecnico', 'primary', 1);
INSERT INTO `tipo_usuario` VALUES (4, 'Personalido', 'warning', 1);

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios`  (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `ndoc_usuario` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nombres` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `apellidos` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email_personal` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email_corporativo` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email_verified_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `fecha_nacimiento` date NOT NULL,
  `tel_personal` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tel_corporativo` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `usuario` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contrasena` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pass_view` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto_perfil` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `firma_digital` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `tipo_acceso` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `menu_usuario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `eliminado` tinyint(1) NULL DEFAULT 0,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_usuario`) USING BTREE,
  UNIQUE INDEX `U_ndoc_usuario`(`ndoc_usuario`) USING BTREE,
  UNIQUE INDEX `U_usuario`(`usuario`) USING BTREE,
  UNIQUE INDEX `U_contrasena`(`contrasena`) USING BTREE,
  UNIQUE INDEX `U_email_personal`(`email_personal`) USING BTREE,
  UNIQUE INDEX `U_email_corporativo`(`email_corporativo`) USING BTREE,
  UNIQUE INDEX `U_tel_personal`(`tel_personal`) USING BTREE,
  UNIQUE INDEX `U_tel_corporativo`(`tel_corporativo`) USING BTREE,
  INDEX `Fk_Tipo_Usuario`(`tipo_acceso`) USING BTREE,
  INDEX `Fk_Id_Area`(`id_area`) USING BTREE,
  CONSTRAINT `Fk_Id_Area` FOREIGN KEY (`id_area`) REFERENCES `tb_area` (`id_area`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Fk_Tipo_Usuario` FOREIGN KEY (`tipo_acceso`) REFERENCES `tipo_usuario` (`id_tipo_acceso`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of usuarios
-- ----------------------------
INSERT INTO `usuarios` VALUES (1, '61505130', 'JEREMY PATRICK', 'CAUPER SILVANO', 'jcauper@gmail.com', 'jcauper@email.com', '2025-02-25 08:52:42', '2003-07-14', '974562354', '954213548', 'jcauper', '$2y$12$Cqb.U5Z70oVD05Zdrq6a4uqS6CQIdWYHZf.cSvbk3QmYbfAsuCfG.', '123456', 'user_auth.jpg', 'fd_jcauper.png', 4, 1, 'eyIxIjpbXSwiMiI6W10sIjMiOlsiMSIsIjIiXSwiNCI6WyIzIiwiNCIsIjUiXSwiNSI6WyI2Il0sIjYiOlsiNyIsIjgiXSwiNyI6WyI5IiwiMTAiXSwiOCI6WyIxMSIsIjEyIl19', NULL, 0, 1, '2025-02-25 08:52:42', '2024-07-09 23:00:19');
INSERT INTO `usuarios` VALUES (3, '12345678', 'Pedro', 'Suarez', 'psuarez@gmail.com', 'psuarez@email.com', '2025-02-25 08:54:29', '2003-01-14', '935423118', '952332137', 'psuarez', '$2y$12$3CmRGy97YD3R0M5j19rrRO.G6AbM6n26v8y3CPEJI8ca2.bsRSiLC', '123789', 'fp_psuarez.png', 'fd_psuarez.png', 3, 3, 'eyI4IjpbIjExIiwiMTIiXX0=', NULL, 0, 1, '2025-02-10 16:31:18', '2024-07-13 02:41:10');
INSERT INTO `usuarios` VALUES (4, '74716278', 'JOSTHEIN JOSEPH', 'MAYORCA BELLEZA', 'jmayorca@gmail.com', 'jmayorca@email.com', '2025-02-25 08:54:29', '1997-06-11', '978456123', '985267341', 'jmayorca', '$2y$12$CAclmFJJoM2plUl48iJsgeRbm8WrDbu8jynetkGuWVVBxGTONEm9C', '147852', 'user_auth.jpg', 'fd_jmayorca.png', 3, 1, 'eyI4IjpbIjExIiwiMTIiXX0=', NULL, 0, 1, '2025-02-10 15:28:24', '2024-07-15 22:18:33');
INSERT INTO `usuarios` VALUES (5, '70401296', 'BRYAN MARTIN', 'POLO GOMEZ', 'talvan@gmail.com', 'talvan@email.com', '2025-02-25 08:54:29', '2001-07-02', '987564123', '948741236', 'talvan', '$2y$12$6oyxU4QP06ERy7uIw4t6yeJuW1s6bmft/lUWc9SMosYlyZrHPbwN.', '987654', 'user_auth.jpg', 'fd_talvan.png', 3, 1, 'eyI4IjpbIjExIiwiMTIiXX0=', NULL, 0, 1, '2025-02-10 15:28:09', '2024-07-22 02:16:27');

-- ----------------------------
-- Procedure structure for GetCodeInc
-- ----------------------------
DROP PROCEDURE IF EXISTS `GetCodeInc`;
delimiter ;;
CREATE PROCEDURE `GetCodeInc`()
BEGIN
    DECLARE last_cod VARCHAR(20);
    DECLARE nuevo_num INT;
    DECLARE nuevo_cod VARCHAR(20);

    -- Obtener el último código generado
		SELECT cod_incidencia INTO last_cod
		FROM tb_incidencias
		ORDER BY id_incidencia DESC
		LIMIT 1;

    -- Si no hay ningún código, inicializar con "INC-00000001"
    IF last_cod IS NULL THEN
        SET nuevo_cod = 'INC-00000001';
    ELSE
        -- Extraer el número del último código
        SET nuevo_num = CAST(SUBSTRING(last_cod, 5) AS UNSIGNED) + 1;
        -- Generar el nuevo código
        SET nuevo_cod = CONCAT('INC-', LPAD(nuevo_num, 8, '0'));
    END IF;

    -- Seleccionar y devolver el nuevo código generado
    SELECT nuevo_cod AS cod_incidencia;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for GetCodeOrds
-- ----------------------------
DROP PROCEDURE IF EXISTS `GetCodeOrds`;
delimiter ;;
CREATE PROCEDURE `GetCodeOrds`(IN _serie CHAR(2))
BEGIN
    DECLARE last_cod INT;
    DECLARE nuevo_num INT;
    DECLARE nuevo_cod VARCHAR(20);

    -- Obtener el último código generado
    SET last_cod = (SELECT id_cor FROM tb_orden_correlativo ORDER BY id_cor DESC LIMIT 1);

    -- Si no hay ningún código, inicializar con "ST-00000001"
    IF last_cod IS NULL THEN
				ALTER TABLE tb_orden_correlativo AUTO_INCREMENT = 1;
        SET nuevo_cod = CONCAT('ST', _serie, '-00000001');
    ELSE
        -- Extraer el número del último código, asegurarse de que contiene solo números
        SET nuevo_num = last_cod;
        
        -- Generar el nuevo código
        SET nuevo_cod = CONCAT('ST', _serie, '-', LPAD(nuevo_num, 8, '0'));
    END IF;

    -- Seleccionar y devolver el nuevo código generado
    SELECT nuevo_cod AS num_orden;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for GetCodeOrdVis
-- ----------------------------
DROP PROCEDURE IF EXISTS `GetCodeOrdVis`;
delimiter ;;
CREATE PROCEDURE `GetCodeOrdVis`(IN _serie CHAR(2))
BEGIN
    DECLARE last_cod VARCHAR(20);
    DECLARE last_num INT;
    DECLARE nuevo_num INT;
    DECLARE nuevo_cod VARCHAR(20);

    -- Obtener el último código generado
    SELECT cod_orden_visita INTO last_cod 
    FROM tb_orden_visita_correlativo 
    ORDER BY cod_orden_visita DESC 
    LIMIT 1;

    -- Si no hay ningún código, inicializar con "VT-00000001"
    IF last_cod IS NULL THEN
        ALTER TABLE tb_orden_visita_correlativo AUTO_INCREMENT = 1;
        SET nuevo_cod = CONCAT('VT', _serie, '-00000001');
    ELSE
        -- Extraer solo la parte numérica del código (asumiendo que el formato es 'VT25-XXXXXXXX')
        SET last_num = CAST(SUBSTRING_INDEX(last_cod, '-', -1) AS UNSIGNED);
        
        -- Incrementar en 1
        SET nuevo_num = last_num + 1;
        
        -- Generar el nuevo código con formato adecuado
        SET nuevo_cod = CONCAT('VT', _serie, '-', LPAD(nuevo_num, 8, '0'));
    END IF;

    -- Devolver el nuevo código generado
    SELECT nuevo_cod AS cod_orden;
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
