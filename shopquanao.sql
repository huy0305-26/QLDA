-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2025 at 05:22 PM
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
-- Database: `shopquanao`
--

-- --------------------------------------------------------

--
-- Table structure for table `baocaodoanhso`
--

CREATE TABLE `baocaodoanhso` (
  `MaBC` int(11) NOT NULL,
  `NgayBaoCao` date DEFAULT NULL,
  `MaSP` int(11) DEFAULT NULL,
  `SoLuongBan` int(11) DEFAULT NULL,
  `DoanhThu` decimal(10,2) DEFAULT NULL,
  `LoiNhuan` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `baocaodoanhso`
--

INSERT INTO `baocaodoanhso` (`MaBC`, `NgayBaoCao`, `MaSP`, `SoLuongBan`, `DoanhThu`, `LoiNhuan`) VALUES
(1, '2025-10-12', 1, 20, 2400000.00, 800000.00),
(2, '2025-10-12', 3, 10, 2700000.00, 900000.00);

-- --------------------------------------------------------

--
-- Table structure for table `chitiethoadon`
--

CREATE TABLE `chitiethoadon` (
  `MaCTHD` int(11) NOT NULL,
  `MaHD` int(11) DEFAULT NULL,
  `MaSP_BienThe` int(11) DEFAULT NULL,
  `SoLuong` int(11) DEFAULT NULL,
  `DonGia` decimal(10,2) DEFAULT NULL,
  `ThanhTien` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chitiethoadon`
--

INSERT INTO `chitiethoadon` (`MaCTHD`, `MaHD`, `MaSP_BienThe`, `SoLuong`, `DonGia`, `ThanhTien`) VALUES
(1, 1, 1, 1, 120000.00, 120000.00),
(2, 2, 4, 1, 270000.00, 270000.00),
(3, 3, 4, 2, 270000.00, 540000.00),
(4, 3, 3, 1, 2300000.00, 2300000.00),
(5, 4, 1, 2, 120000.00, 240000.00),
(6, 4, 2, 2, 120000.00, 240000.00),
(7, 5, 7, 1, 250000.00, 250000.00);

-- --------------------------------------------------------

--
-- Table structure for table `chitietphieunhap`
--

CREATE TABLE `chitietphieunhap` (
  `MaCTPN` int(11) NOT NULL,
  `MaPN` int(11) DEFAULT NULL,
  `MaSP_BienThe` int(11) DEFAULT NULL,
  `SoLuong` int(11) DEFAULT NULL,
  `DonGia` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chitietphieunhap`
--

INSERT INTO `chitietphieunhap` (`MaCTPN`, `MaPN`, `MaSP_BienThe`, `SoLuong`, `DonGia`) VALUES
(1, 1, 1, 100, 80000.00),
(2, 1, 2, 80, 80000.00),
(3, 2, 3, 50, 150000.00),
(4, 2, 4, 40, 180000.00);

-- --------------------------------------------------------

--
-- Table structure for table `chitiet_phieuxuatnhap`
--

CREATE TABLE `chitiet_phieuxuatnhap` (
  `MaCTPXN` int(11) NOT NULL,
  `MaPhieu` int(11) NOT NULL,
  `MaSP_BienThe` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `DonGia` decimal(10,2) NOT NULL,
  `ThanhTien` decimal(10,2) GENERATED ALWAYS AS (`SoLuong` * `DonGia`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danhmuc`
--

CREATE TABLE `danhmuc` (
  `MaDM` int(11) NOT NULL,
  `TenDM` varchar(100) DEFAULT NULL,
  `MaDM_Cha` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `danhmuc`
--

INSERT INTO `danhmuc` (`MaDM`, `TenDM`, `MaDM_Cha`) VALUES
(1, 'Áo thun', 6),
(2, 'Áo sơ mi', 6),
(3, 'Quần jean', 5),
(5, 'Quần', NULL),
(6, 'Áo', NULL),
(11, 'Quần test', 5),
(13, 'Phụ Kiện', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hoadon`
--

CREATE TABLE `hoadon` (
  `MaHD` int(11) NOT NULL,
  `NgayLap` date DEFAULT NULL,
  `TongTien` decimal(10,2) DEFAULT NULL,
  `HinhThucThanhToan` varchar(50) DEFAULT NULL,
  `MaKH` int(11) DEFAULT NULL,
  `TenKH` varchar(100) DEFAULT NULL,
  `SoDienThoai` varchar(15) DEFAULT NULL,
  `DiaChi` text DEFAULT NULL,
  `MaNV` int(11) DEFAULT NULL,
  `GhiChu` text DEFAULT NULL,
  `TrangThai` varchar(50) DEFAULT 'Đang xử lý'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hoadon`
--

INSERT INTO `hoadon` (`MaHD`, `NgayLap`, `TongTien`, `HinhThucThanhToan`, `MaKH`, `TenKH`, `SoDienThoai`, `DiaChi`, `MaNV`, `GhiChu`, `TrangThai`) VALUES
(1, '2025-10-10', 120000.00, 'Tiền mặt', 1, 'Nguyen Van A', '0901234567', 'Q1, TP.HCM', 2, 'Khách mua trực tiếp', 'Đang xử lý'),
(2, '2025-10-12', 270000.00, 'Chuyển khoản', 2, 'Tran Thi B', '0907654321', 'Q5, Hà Nội', 2, 'Mua online', 'Đang xử lý'),
(3, '2025-10-24', 2840000.00, 'Tiền mặt', NULL, 'Trần Gia Huy', '0393091124', 'Quận Bình Thạnh, Hồ Chí Minh', NULL, 'Ghi chú...', 'Đã xác nhận'),
(4, '2025-10-24', 480000.00, 'Tiền mặt', 4, 'Trần Gia Huy', '0393091124', 'Bình Thạnh, Hồ Chí Minh', NULL, 'Ghi Chú nè....', 'Đang xử lý'),
(5, '2025-10-24', 250000.00, 'Tiền mặt', 4, 'Trần Gia Huyy', '0393091124', 'Bình Thạnh', NULL, 'Ghi Chú...', 'Đang xử lý');

-- --------------------------------------------------------

--
-- Table structure for table `khachhang`
--

CREATE TABLE `khachhang` (
  `MaKH` int(11) NOT NULL,
  `HoTen` varchar(100) DEFAULT NULL,
  `SoDienThoai` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `HangThanhVien` enum('Thuong','Bac','Vang','KimCuong') DEFAULT 'Thuong',
  `DiemTichLuy` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khachhang`
--

INSERT INTO `khachhang` (`MaKH`, `HoTen`, `SoDienThoai`, `Email`, `DiaChi`, `HangThanhVien`, `DiemTichLuy`) VALUES
(1, 'Nguyen Van A', '0901234567', 'a@gmail.com', 'Q1, TP.HCM', 'Vang', 1200),
(2, 'Tran Thi BB', '0907654321', 'b@gmail.com', 'Q5, Hà Nội', 'Bac', 800),
(3, 'Le Van C', '0912345678', 'c@gmail.com', 'Bien Hoa, Dong Nai', 'Thuong', 200),
(4, 'Trần Gia Huyy', '0393091124', '', 'Bình Thạnh', 'Bac', 730);

-- --------------------------------------------------------

--
-- Table structure for table `khuyenmai`
--

CREATE TABLE `khuyenmai` (
  `MaKM` int(11) NOT NULL,
  `TenKM` varchar(100) DEFAULT NULL,
  `LoaiKM` enum('PhanTram','SoTien') DEFAULT NULL,
  `GiaTri` decimal(10,2) DEFAULT NULL,
  `NgayBatDau` date DEFAULT NULL,
  `NgayKetThuc` date DEFAULT NULL,
  `DieuKienApDung` text DEFAULT NULL,
  `TrangThai` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `khuyenmai`
--

INSERT INTO `khuyenmai` (`MaKM`, `TenKM`, `LoaiKM`, `GiaTri`, `NgayBatDau`, `NgayKetThuc`, `DieuKienApDung`, `TrangThai`) VALUES
(1, 'Giảm 10% toàn bộ áo thun', 'PhanTram', 10.00, '2025-10-01', '2025-10-20', 'Áp dụng cho sản phẩm thuộc danh mục Áo thun', 1),
(2, 'Giảm 50k cho đơn hàng > 500k', 'SoTien', 50000.00, '2025-10-05', '2025-10-31', 'Đơn hàng từ 500k trở lên', 1);

-- --------------------------------------------------------

--
-- Table structure for table `khuyenmai_bienthe`
--

CREATE TABLE `khuyenmai_bienthe` (
  `MaKM` int(11) NOT NULL,
  `MaSP_BienThe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhacungcap`
--

CREATE TABLE `nhacungcap` (
  `MaNCC` int(11) NOT NULL,
  `TenNCC` varchar(100) DEFAULT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `SoDienThoai` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `GhiChu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhacungcap`
--

INSERT INTO `nhacungcap` (`MaNCC`, `TenNCC`, `DiaChi`, `SoDienThoai`, `Email`, `GhiChu`) VALUES
(1, 'Công ty Dệt May Việt Tiến', 'Q3, TP.HCM', '02838485555', 'contact@viettien.vn', 'Cung cấp quần áo nam'),
(2, 'Công ty May 10', 'Hà Nội', '02436789999', 'info@may10.vn', 'Cung cấp áo sơ mi và áo thun');

-- --------------------------------------------------------

--
-- Table structure for table `nhanvien`
--

CREATE TABLE `nhanvien` (
  `MaNV` int(11) NOT NULL,
  `HoTen` varchar(100) DEFAULT NULL,
  `TenDangNhap` varchar(50) DEFAULT NULL,
  `MatKhau` varchar(255) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `SoDienThoai` varchar(15) DEFAULT NULL,
  `MaVaiTro` int(11) DEFAULT NULL,
  `TrangThai` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhanvien`
--

INSERT INTO `nhanvien` (`MaNV`, `HoTen`, `TenDangNhap`, `MatKhau`, `Email`, `SoDienThoai`, `MaVaiTro`, `TrangThai`) VALUES
(1, 'Admin', 'admin', '123456', 'admin@shop.vn', '0901111222', 1, '1'),
(2, 'Nguyen Thi Mai', 'mai_nv', '123456', 'mai@shop.vn', '0903333444', 3, '1'),
(3, 'Le Quoc Hung', 'hung_kho', '123456', 'hung@shop.vn', '0905555666', 2, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `phieunhap`
--

CREATE TABLE `phieunhap` (
  `MaPN` int(11) NOT NULL,
  `NgayNhap` date DEFAULT NULL,
  `TongTien` decimal(10,2) DEFAULT NULL,
  `MaNCC` int(11) DEFAULT NULL,
  `MaNV` int(11) DEFAULT NULL,
  `GhiChu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phieunhap`
--

INSERT INTO `phieunhap` (`MaPN`, `NgayNhap`, `TongTien`, `MaNCC`, `MaNV`, `GhiChu`) VALUES
(1, '2025-09-25', 5000000.00, 1, 3, 'Nhập áo thun, quần jean đợt 1'),
(2, '2025-09-30', 4000000.00, 2, 3, 'Nhập áo sơ mi đợt 2');

-- --------------------------------------------------------

--
-- Table structure for table `phieuxuatnhap`
--

CREATE TABLE `phieuxuatnhap` (
  `MaPhieu` int(11) NOT NULL,
  `NgayLap` date DEFAULT NULL,
  `LoaiPhieu` enum('Nhap','Xuat') DEFAULT NULL,
  `MaSP` int(11) DEFAULT NULL,
  `SoLuong` int(11) DEFAULT NULL,
  `DonGia` decimal(10,2) DEFAULT NULL,
  `ThanhTien` decimal(10,2) DEFAULT NULL,
  `MaNV` int(11) DEFAULT NULL,
  `GhiChu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phieuxuatnhap`
--

INSERT INTO `phieuxuatnhap` (`MaPhieu`, `NgayLap`, `LoaiPhieu`, `MaSP`, `SoLuong`, `DonGia`, `ThanhTien`, `MaNV`, `GhiChu`) VALUES
(1, '2025-10-01', 'Nhap', 1, 100, 80000.00, 8000000.00, 3, 'Nhập hàng đợt 1'),
(2, '2025-10-11', 'Xuat', 1, 20, 120000.00, 2400000.00, 2, 'Xuất bán cho khách');

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `MaSP` int(11) NOT NULL,
  `TenSP` varchar(100) DEFAULT NULL,
  `MaDM` int(11) DEFAULT NULL,
  `MaTH` int(11) DEFAULT NULL,
  `XuatXu` varchar(100) DEFAULT NULL,
  `MoTa` text DEFAULT NULL,
  `HinhAnh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`MaSP`, `TenSP`, `MaDM`, `MaTH`, `XuatXu`, `MoTa`, `HinhAnh`) VALUES
(1, 'Áo thun cổ tròn nam', 1, 3, 'Việt Nam', 'Áo thun cotton 100%, co giãn tốt', '1761310019_do-mixi--1736908520669519357993.png'),
(2, 'Áo sơ mi trắng tay dài', 2, 1, 'Việt Nam', 'Chất liệu cotton lạnh, kiểu dáng công sở', '1761283724_sanpham3.webp'),
(3, 'Quần jean xanh nam', 3, 4, 'Trung Quốc', 'Jean slimfit, vải dày, bền màu', '1761283412_sanpham1.jpg'),
(5, 'TEST 123', 1, 1, 'Việt Nam', 'Mô tả....', '1761310012_1727345194543.png'),
(6, 'Quần test', 2, 5, 'Việt Nam', 'Mô tả....', '1761310006_4KG2VgKFDJWqdtg4UMRqk5CnkJVoCpe5QMd20Pf7.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `sanpham_bienthe`
--

CREATE TABLE `sanpham_bienthe` (
  `MaSP_BienThe` int(11) NOT NULL,
  `MaSP` int(11) DEFAULT NULL,
  `KichThuoc` varchar(10) DEFAULT NULL,
  `MauSac` varchar(50) DEFAULT NULL,
  `GiaNhap` decimal(10,2) DEFAULT NULL,
  `GiaBan` decimal(10,2) DEFAULT NULL,
  `GiaGoc` decimal(10,2) DEFAULT NULL,
  `TonKho` int(11) DEFAULT NULL,
  `NgayCapNhat` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sanpham_bienthe`
--

INSERT INTO `sanpham_bienthe` (`MaSP_BienThe`, `MaSP`, `KichThuoc`, `MauSac`, `GiaNhap`, `GiaBan`, `GiaGoc`, `TonKho`, `NgayCapNhat`) VALUES
(1, 1, 'M', 'Trắng', 80000.00, 120000.00, 150000.00, 48, '2025-10-01'),
(2, 1, 'L', 'Đen', 80000.00, 120000.00, 150000.00, 38, '2025-10-01'),
(3, 2, 'M', 'Trắng', 150000.00, 2300000.00, 2875000.00, 24, '2025-10-01'),
(4, 3, '32', 'Xanh đậm', 180000.00, 270000.00, 337500.00, 18, '2025-10-01'),
(7, 5, 'XL', ' Đen', 100000.00, 250000.00, 416667.00, 19, NULL),
(8, 6, 'XL', ' Đen', 150000.00, 300000.00, 600000.00, 10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `saoluu`
--

CREATE TABLE `saoluu` (
  `MaSaoLuu` int(11) NOT NULL,
  `TenFile` varchar(255) DEFAULT NULL,
  `NgaySaoLuu` date DEFAULT NULL,
  `DuongDan` varchar(255) DEFAULT NULL,
  `TrangThai` enum('ThanhCong','Loi') DEFAULT NULL,
  `MaNV` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saoluu`
--

INSERT INTO `saoluu` (`MaSaoLuu`, `TenFile`, `NgaySaoLuu`, `DuongDan`, `TrangThai`, `MaNV`) VALUES
(1, 'backup_2025_10_10.sql', '2025-10-10', 'C:\\backup\\backup_2025_10_10.sql', 'ThanhCong', 1),
(2, 'backup_2025_10_12.sql', '2025-10-12', 'C:\\backup\\backup_2025_10_12.sql', 'ThanhCong', 1);

-- --------------------------------------------------------

--
-- Table structure for table `thuonghieu`
--

CREATE TABLE `thuonghieu` (
  `MaTH` int(11) NOT NULL,
  `TenTH` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thuonghieu`
--

INSERT INTO `thuonghieu` (`MaTH`, `TenTH`) VALUES
(1, 'Việt Tiến'),
(2, 'Canifa'),
(3, 'Routine'),
(4, 'Yody'),
(5, 'Adidas');

-- --------------------------------------------------------

--
-- Table structure for table `vaitro`
--

CREATE TABLE `vaitro` (
  `MaVaiTro` int(11) NOT NULL,
  `TenVaiTro` varchar(50) DEFAULT NULL,
  `QuyenHan` enum('ToanQuyen','NhapXuat','XemBanHang') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaitro`
--

INSERT INTO `vaitro` (`MaVaiTro`, `TenVaiTro`, `QuyenHan`) VALUES
(1, 'Quản trị viên', 'ToanQuyen'),
(2, 'Quản lý kho', 'NhapXuat'),
(3, 'Nhân viên bán hàng', 'XemBanHang');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_baocaodoanhso`
-- (See below for the actual view)
--
CREATE TABLE `vw_baocaodoanhso` (
`MaSP_BienThe` int(11)
,`SoLuongBan` decimal(32,0)
,`DoanhThu` decimal(32,2)
,`LoiNhuan` decimal(43,2)
);

-- --------------------------------------------------------

--
-- Structure for view `vw_baocaodoanhso`
--
DROP TABLE IF EXISTS `vw_baocaodoanhso`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_baocaodoanhso`  AS SELECT `cthd`.`MaSP_BienThe` AS `MaSP_BienThe`, sum(`cthd`.`SoLuong`) AS `SoLuongBan`, sum(`cthd`.`ThanhTien`) AS `DoanhThu`, sum(`cthd`.`ThanhTien`) - sum(`cthd`.`SoLuong` * `spbt`.`GiaNhap`) AS `LoiNhuan` FROM (`chitiethoadon` `cthd` join `sanpham_bienthe` `spbt` on(`spbt`.`MaSP_BienThe` = `cthd`.`MaSP_BienThe`)) GROUP BY `cthd`.`MaSP_BienThe` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `baocaodoanhso`
--
ALTER TABLE `baocaodoanhso`
  ADD PRIMARY KEY (`MaBC`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Indexes for table `chitiethoadon`
--
ALTER TABLE `chitiethoadon`
  ADD PRIMARY KEY (`MaCTHD`),
  ADD KEY `MaHD` (`MaHD`),
  ADD KEY `MaSP_BienThe` (`MaSP_BienThe`);

--
-- Indexes for table `chitietphieunhap`
--
ALTER TABLE `chitietphieunhap`
  ADD PRIMARY KEY (`MaCTPN`),
  ADD KEY `MaPN` (`MaPN`),
  ADD KEY `MaSP_BienThe` (`MaSP_BienThe`);

--
-- Indexes for table `chitiet_phieuxuatnhap`
--
ALTER TABLE `chitiet_phieuxuatnhap`
  ADD PRIMARY KEY (`MaCTPXN`),
  ADD KEY `fk_ctpxn_phieu` (`MaPhieu`),
  ADD KEY `fk_ctpxn_bienthe` (`MaSP_BienThe`);

--
-- Indexes for table `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`MaDM`),
  ADD KEY `fk_danhmuc_cha` (`MaDM_Cha`);

--
-- Indexes for table `hoadon`
--
ALTER TABLE `hoadon`
  ADD PRIMARY KEY (`MaHD`),
  ADD KEY `MaKH` (`MaKH`),
  ADD KEY `MaNV` (`MaNV`);

--
-- Indexes for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`MaKH`);

--
-- Indexes for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  ADD PRIMARY KEY (`MaKM`);

--
-- Indexes for table `khuyenmai_bienthe`
--
ALTER TABLE `khuyenmai_bienthe`
  ADD PRIMARY KEY (`MaKM`,`MaSP_BienThe`),
  ADD KEY `fk_km_bienthe_bt` (`MaSP_BienThe`);

--
-- Indexes for table `nhacungcap`
--
ALTER TABLE `nhacungcap`
  ADD PRIMARY KEY (`MaNCC`);

--
-- Indexes for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`MaNV`),
  ADD KEY `fk_nhanvien_vaitro` (`MaVaiTro`);

--
-- Indexes for table `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD PRIMARY KEY (`MaPN`),
  ADD KEY `MaNCC` (`MaNCC`),
  ADD KEY `MaNV` (`MaNV`);

--
-- Indexes for table `phieuxuatnhap`
--
ALTER TABLE `phieuxuatnhap`
  ADD PRIMARY KEY (`MaPhieu`),
  ADD KEY `fk_pxuatnhap_nhanvien` (`MaNV`);

--
-- Indexes for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSP`),
  ADD KEY `MaDM` (`MaDM`),
  ADD KEY `MaTH` (`MaTH`);

--
-- Indexes for table `sanpham_bienthe`
--
ALTER TABLE `sanpham_bienthe`
  ADD PRIMARY KEY (`MaSP_BienThe`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Indexes for table `saoluu`
--
ALTER TABLE `saoluu`
  ADD PRIMARY KEY (`MaSaoLuu`),
  ADD KEY `fk_saoluu_nhanvien` (`MaNV`);

--
-- Indexes for table `thuonghieu`
--
ALTER TABLE `thuonghieu`
  ADD PRIMARY KEY (`MaTH`);

--
-- Indexes for table `vaitro`
--
ALTER TABLE `vaitro`
  ADD PRIMARY KEY (`MaVaiTro`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `baocaodoanhso`
--
ALTER TABLE `baocaodoanhso`
  MODIFY `MaBC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chitiethoadon`
--
ALTER TABLE `chitiethoadon`
  MODIFY `MaCTHD` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `chitietphieunhap`
--
ALTER TABLE `chitietphieunhap`
  MODIFY `MaCTPN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `chitiet_phieuxuatnhap`
--
ALTER TABLE `chitiet_phieuxuatnhap`
  MODIFY `MaCTPXN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `MaDM` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `hoadon`
--
ALTER TABLE `hoadon`
  MODIFY `MaHD` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `MaKH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `khuyenmai`
--
ALTER TABLE `khuyenmai`
  MODIFY `MaKM` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `nhacungcap`
--
ALTER TABLE `nhacungcap`
  MODIFY `MaNCC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `MaNV` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `phieunhap`
--
ALTER TABLE `phieunhap`
  MODIFY `MaPN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `phieuxuatnhap`
--
ALTER TABLE `phieuxuatnhap`
  MODIFY `MaPhieu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `MaSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sanpham_bienthe`
--
ALTER TABLE `sanpham_bienthe`
  MODIFY `MaSP_BienThe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `saoluu`
--
ALTER TABLE `saoluu`
  MODIFY `MaSaoLuu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `thuonghieu`
--
ALTER TABLE `thuonghieu`
  MODIFY `MaTH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vaitro`
--
ALTER TABLE `vaitro`
  MODIFY `MaVaiTro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `baocaodoanhso`
--
ALTER TABLE `baocaodoanhso`
  ADD CONSTRAINT `baocaodoanhso_ibfk_1` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `chitiethoadon`
--
ALTER TABLE `chitiethoadon`
  ADD CONSTRAINT `chitiethoadon_ibfk_1` FOREIGN KEY (`MaHD`) REFERENCES `hoadon` (`MaHD`),
  ADD CONSTRAINT `chitiethoadon_ibfk_2` FOREIGN KEY (`MaSP_BienThe`) REFERENCES `sanpham_bienthe` (`MaSP_BienThe`),
  ADD CONSTRAINT `fk_cthd_bienthe` FOREIGN KEY (`MaSP_BienThe`) REFERENCES `sanpham_bienthe` (`MaSP_BienThe`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cthd_hd` FOREIGN KEY (`MaHD`) REFERENCES `hoadon` (`MaHD`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chitietphieunhap`
--
ALTER TABLE `chitietphieunhap`
  ADD CONSTRAINT `chitietphieunhap_ibfk_1` FOREIGN KEY (`MaPN`) REFERENCES `phieunhap` (`MaPN`),
  ADD CONSTRAINT `chitietphieunhap_ibfk_2` FOREIGN KEY (`MaSP_BienThe`) REFERENCES `sanpham_bienthe` (`MaSP_BienThe`);

--
-- Constraints for table `chitiet_phieuxuatnhap`
--
ALTER TABLE `chitiet_phieuxuatnhap`
  ADD CONSTRAINT `fk_ctpxn_bienthe` FOREIGN KEY (`MaSP_BienThe`) REFERENCES `sanpham_bienthe` (`MaSP_BienThe`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ctpxn_phieu` FOREIGN KEY (`MaPhieu`) REFERENCES `phieuxuatnhap` (`MaPhieu`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD CONSTRAINT `fk_danhmuc_cha` FOREIGN KEY (`MaDM_Cha`) REFERENCES `danhmuc` (`MaDM`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `hoadon`
--
ALTER TABLE `hoadon`
  ADD CONSTRAINT `hoadon_ibfk_1` FOREIGN KEY (`MaKH`) REFERENCES `khachhang` (`MaKH`),
  ADD CONSTRAINT `hoadon_ibfk_2` FOREIGN KEY (`MaNV`) REFERENCES `nhanvien` (`MaNV`);

--
-- Constraints for table `khuyenmai_bienthe`
--
ALTER TABLE `khuyenmai_bienthe`
  ADD CONSTRAINT `fk_km_bienthe_bt` FOREIGN KEY (`MaSP_BienThe`) REFERENCES `sanpham_bienthe` (`MaSP_BienThe`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_km_bienthe_km` FOREIGN KEY (`MaKM`) REFERENCES `khuyenmai` (`MaKM`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `fk_nhanvien_vaitro` FOREIGN KEY (`MaVaiTro`) REFERENCES `vaitro` (`MaVaiTro`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD CONSTRAINT `fk_phieunhap_nhanvien` FOREIGN KEY (`MaNV`) REFERENCES `nhanvien` (`MaNV`) ON UPDATE CASCADE,
  ADD CONSTRAINT `phieunhap_ibfk_1` FOREIGN KEY (`MaNCC`) REFERENCES `nhacungcap` (`MaNCC`),
  ADD CONSTRAINT `phieunhap_ibfk_2` FOREIGN KEY (`MaNV`) REFERENCES `nhanvien` (`MaNV`);

--
-- Constraints for table `phieuxuatnhap`
--
ALTER TABLE `phieuxuatnhap`
  ADD CONSTRAINT `fk_pxuatnhap_nhanvien` FOREIGN KEY (`MaNV`) REFERENCES `nhanvien` (`MaNV`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `fk_sp_dm` FOREIGN KEY (`MaDM`) REFERENCES `danhmuc` (`MaDM`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sp_th` FOREIGN KEY (`MaTH`) REFERENCES `thuonghieu` (`MaTH`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`MaDM`) REFERENCES `danhmuc` (`MaDM`),
  ADD CONSTRAINT `sanpham_ibfk_2` FOREIGN KEY (`MaTH`) REFERENCES `thuonghieu` (`MaTH`);

--
-- Constraints for table `sanpham_bienthe`
--
ALTER TABLE `sanpham_bienthe`
  ADD CONSTRAINT `sanpham_bienthe_ibfk_1` FOREIGN KEY (`MaSP`) REFERENCES `sanpham` (`MaSP`);

--
-- Constraints for table `saoluu`
--
ALTER TABLE `saoluu`
  ADD CONSTRAINT `fk_saoluu_nhanvien` FOREIGN KEY (`MaNV`) REFERENCES `nhanvien` (`MaNV`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
