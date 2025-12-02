# 📘 HƯỚNG DẪN SỬ DỤNG - Tính năng Quản lý Booking & Khách hàng

## 📋 MỤC LỤC
1. [Tổng quan](#tổng-quan)
2. [Các tính năng đã hoàn thành](#các-tính-năng-đã-hoàn-thành)
3. [Hướng dẫn truy cập](#hướng-dẫn-truy-cập)
4. [Hướng dẫn test chi tiết](#hướng-dẫn-test-chi-tiết)
5. [Cấu trúc file đã tạo](#cấu-trúc-file-đã-tạo)
6. [API Endpoints](#api-endpoints)
7. [Troubleshooting](#troubleshooting)

---

## 📖 TỔNG QUAN

Module **"Quản lý Booking & Khách hàng"** đã được xây dựng hoàn chỉnh theo mô hình MVC, bao gồm 3 tính năng chính:
1. ✅ Quản lý Booking & Cập nhật trạng thái
2. ✅ Quản lý Khách hàng & Xem chi tiết
3. ✅ Quản lý Ghi chú đặc biệt

**Công nghệ sử dụng:**
- Backend: PHP (MVC Pattern)
- Frontend: HTML, CSS, Bootstrap 5, JavaScript (inline)
- Database: MySQL
- Rendering: Server-side (PHP)

---

## 🎯 CÁC TÍNH NĂNG ĐÃ HOÀN THÀNH

### 1. Quản lý Booking & Trạng thái
- ✅ Xem danh sách tất cả booking
- ✅ Cập nhật trạng thái booking (Đang xử lý, Đã xác nhận, Đã cọc, Hoàn thành, Đã hủy)
- ✅ Hiển thị thông tin: Mã booking, Tour, Email, SĐT, Số lượng, Ngày khởi hành, Trạng thái, Tổng tiền

### 2. Quản lý Khách hàng
- ✅ Xem danh sách khách hàng (group by email)
- ✅ Xem chi tiết khách hàng (thông tin, booking, ghi chú)
- ✅ Thống kê: Tổng số booking, Tổng chi tiêu, Booking đầu tiên/cuối

### 3. Ghi chú đặc biệt
- ✅ Xem danh sách ghi chú
- ✅ Thêm ghi chú mới (ăn chay, dị ứng, sức khỏe, yêu cầu đặc biệt...)
- ✅ Chỉnh sửa ghi chú
- ✅ Xóa ghi chú

---

## 🔗 HƯỚNG DẪN TRUY CẬP

### Bước 1: Kiểm tra database
Đảm bảo database `travel_db` đã được import và có dữ liệu mẫu trong các bảng:
- `bookings` - Phải có ít nhất 1-2 booking
- `tours` - Phải có tour để join với bookings
- `notes` - Có thể có hoặc không (dữ liệu mẫu)

### Bước 2: Truy cập trang admin
```
http://localhost/Codethue3/index.php?action=dashboard
```

Hoặc các trang cụ thể:
- **Booking List**: `http://localhost/Codethue3/index.php?action=booking-list`
- **Customer List**: `http://localhost/Codethue3/index.php?action=customer-list`
- **Special Notes**: `http://localhost/Codethue3/index.php?action=special-notes`

### Bước 3: Điều hướng qua Sidebar
Click vào các menu trong sidebar bên trái để chuyển giữa các trang.

---

## 🧪 HƯỚNG DẪN TEST CHI TIẾT

### TEST 1: Quản lý Booking & Trạng thái

#### Test 1.1: Xem danh sách Booking
1. **Truy cập**: `http://localhost/Codethue3/index.php?action=booking-list`
2. **Kiểm tra**:
   - ✅ Trang hiển thị với sidebar bên trái
   - ✅ Header hiển thị "Quản lý Booking & Trạng thái"
   - ✅ Card hiển thị "Tổng số booking: X" (X là số lượng thực tế)
   - ✅ Bảng hiển thị danh sách booking với các cột:
     - STT, Mã Booking, Tour, Email, Số điện thoại
     - Số lượng, Ngày khởi hành, Trạng thái (có màu badge)
     - Tổng tiền, Hành động
   - ✅ Nếu không có booking: Hiển thị "Chưa có booking nào"

#### Test 1.2: Cập nhật trạng thái Booking
1. **Tìm booking**: Tìm một booking trong danh sách
2. **Click nút "Cập nhật"** (icon bút chì) ở cột "Hành động"
3. **Kiểm tra Modal**:
   - ✅ Modal hiện ra với tiêu đề "Cập nhật trạng thái booking"
   - ✅ Dropdown "Trạng thái" có đầy đủ các option:
     - Đang xử lý
     - Đã xác nhận
     - Đã cọc
     - Hoàn thành
     - Đã hủy
   - ✅ Dropdown đã chọn trạng thái hiện tại của booking
4. **Thực hiện cập nhật**:
   - Chọn trạng thái mới (ví dụ: "Đã xác nhận")
   - Click nút "Cập nhật"
5. **Kiểm tra kết quả**:
   - ✅ Hiển thị alert "Cập nhật trạng thái thành công!"
   - ✅ Trang tự động reload
   - ✅ Trạng thái trong bảng đã thay đổi
   - ✅ Badge màu đã thay đổi theo trạng thái mới

#### Test 1.3: Kiểm tra màu sắc Badge trạng thái
- ✅ **Đang xử lý**: Badge màu vàng (warning)
- ✅ **Đã xác nhận**: Badge màu xanh lá (success)
- ✅ **Đã cọc**: Badge màu xanh dương (info)
- ✅ **Hoàn thành**: Badge màu xanh lá (success)
- ✅ **Đã hủy**: Badge màu đỏ (danger)

---

### TEST 2: Quản lý Khách hàng

#### Test 2.1: Xem danh sách Khách hàng
1. **Truy cập**: `http://localhost/Codethue3/index.php?action=customer-list`
2. **Kiểm tra**:
   - ✅ Trang hiển thị với sidebar
   - ✅ Header "Danh sách Khách hàng"
   - ✅ Card hiển thị "Tổng số khách hàng: X"
   - ✅ Bảng hiển thị các cột:
     - STT, Email, Số điện thoại
     - Tổng số booking (badge xanh)
     - Tổng chi tiêu (định dạng tiền VN)
     - Booking đầu tiên (dd/mm/yyyy)
     - Booking gần nhất (dd/mm/yyyy)
     - Hành động (nút "Chi tiết")
   - ✅ Khách hàng được group theo email (không trùng lặp)

#### Test 2.2: Xem chi tiết Khách hàng
1. **Chọn khách hàng**: Click nút "Chi tiết" (icon mắt) ở một khách hàng
2. **Kiểm tra Modal**:
   - ✅ Modal hiện ra với tiêu đề "Chi tiết Khách hàng"
   - ✅ Modal có 2 phần chính:

   **Phần 1: Thông tin khách hàng (Bên trái)**
   - ✅ Email
   - ✅ Số điện thoại
   - ✅ Tổng số booking (badge)
   - ✅ Tổng chi tiêu (định dạng tiền)
   - ✅ Booking đầu tiên (dd/mm/yyyy)
   - ✅ Booking gần nhất (dd/mm/yyyy)

   **Phần 2: Ghi chú đặc biệt (Bên phải)**
   - ✅ Hiển thị danh sách ghi chú (nếu có)
   - ✅ Hoặc "Chưa có ghi chú" (nếu không có)

   **Phần 3: Danh sách Booking (Bên dưới)**
   - ✅ Bảng hiển thị tất cả booking của khách hàng:
     - Mã Booking, Tour, Số lượng
     - Ngày khởi hành, Trạng thái (badge màu), Tổng tiền
   - ✅ Hoặc "Chưa có booking" (nếu không có)

3. **Đóng modal**: Click nút X hoặc click bên ngoài modal

#### Test 2.3: Kiểm tra định dạng dữ liệu
- ✅ **Tiền tệ**: Hiển thị định dạng VN (ví dụ: 11.000.000đ)
- ✅ **Ngày tháng**: Định dạng dd/mm/yyyy (ví dụ: 20/11/2025)
- ✅ **Badge**: Màu sắc phù hợp với trạng thái

---

### TEST 3: Quản lý Ghi chú đặc biệt

#### Test 3.1: Xem danh sách Ghi chú
1. **Truy cập**: `http://localhost/Codethue3/index.php?action=special-notes`
2. **Kiểm tra**:
   - ✅ Trang hiển thị với sidebar
   - ✅ Header "Ghi chú đặc biệt của Khách hàng"
   - ✅ Card hiển thị "Tổng số ghi chú: X" và nút "+ Thêm ghi chú"
   - ✅ Bảng hiển thị các cột:
     - STT, Khách hàng (SĐT), Email
     - Loại ghi chú (badge màu xanh info)
     - Nội dung
     - Hành động (nút Sửa và Xóa)

#### Test 3.2: Thêm ghi chú mới
1. **Click nút "+ Thêm ghi chú"**
2. **Kiểm tra Modal**:
   - ✅ Modal hiện ra với tiêu đề "Thêm ghi chú mới"
   - ✅ Form có 3 trường:
     - Email khách hàng (text input, required)
     - Loại ghi chú (dropdown, required) với các option:
       - Ăn chay
       - Dị ứng
       - Sức khỏe
       - Yêu cầu đặc biệt
       - Khác
     - Nội dung (textarea, required)
3. **Điền thông tin**:
   - Email: Nhập email của khách hàng có booking (ví dụ: `khachhang@gmail.com`)
   - Loại ghi chú: Chọn "Ăn chay"
   - Nội dung: Nhập "Khách ăn chay trường – tránh món thịt, hải sản"
4. **Click "Lưu"**
5. **Kiểm tra kết quả**:
   - ✅ Hiển thị alert "Thêm ghi chú thành công!"
   - ✅ Trang tự động reload
   - ✅ Ghi chú mới xuất hiện trong bảng
   - ✅ Loại ghi chú hiển thị đúng (badge "Ăn chay")

#### Test 3.3: Chỉnh sửa ghi chú
1. **Chọn ghi chú**: Click nút "Sửa" (icon bút chì) ở một ghi chú
2. **Kiểm tra Modal**:
   - ✅ Modal hiện ra với tiêu đề "Chỉnh sửa ghi chú"
   - ✅ Email bị disable (readonly) - không thể sửa
   - ✅ Loại ghi chú đã được chọn sẵn
   - ✅ Nội dung đã được điền sẵn
3. **Sửa thông tin**:
   - Thay đổi loại ghi chú
   - Sửa nội dung
4. **Click "Lưu"**
5. **Kiểm tra kết quả**:
   - ✅ Hiển thị alert "Cập nhật ghi chú thành công!"
   - ✅ Trang reload
   - ✅ Ghi chú đã được cập nhật trong bảng

#### Test 3.4: Xóa ghi chú
1. **Chọn ghi chú**: Click nút "Xóa" (icon thùng rác) ở một ghi chú
2. **Kiểm tra Confirm**:
   - ✅ Hiển thị hộp thoại xác nhận "Bạn có chắc chắn muốn xóa ghi chú này?"
3. **Xác nhận xóa**: Click "OK"
4. **Kiểm tra kết quả**:
   - ✅ Hiển thị alert "Xóa ghi chú thành công!"
   - ✅ Trang reload
   - ✅ Ghi chú đã biến mất khỏi bảng

#### Test 3.5: Kiểm tra validation
1. **Thêm ghi chú với email không tồn tại**:
   - Email: `khongtontai@gmail.com`
   - Loại: Chọn bất kỳ
   - Nội dung: Nhập nội dung
   - Click "Lưu"
   - ✅ Hiển thị alert lỗi "Không tìm thấy booking của khách hàng này..."

2. **Thêm ghi chú thiếu thông tin**:
   - Để trống email hoặc loại hoặc nội dung
   - Click "Lưu"
   - ✅ Browser hiển thị validation "Vui lòng điền đầy đủ thông tin"

---

## 📁 CẤU TRÚC FILE ĐÃ TẠO

### Models (3 files)
```
models/
├── BookingModel.php      - Xử lý dữ liệu bookings
├── CustomerModel.php     - Xử lý dữ liệu khách hàng
└── NoteModel.php         - Xử lý dữ liệu ghi chú
```

### Controllers (3 files)
```
controllers/
├── BookingController.php - Logic quản lý booking
├── CustomerController.php - Logic quản lý khách hàng
└── NoteController.php    - Logic quản lý ghi chú
```

### Views (3 files)
```
views/admin/
├── booking-list.php      - Giao diện danh sách booking
├── customer-list.php     - Giao diện danh sách khách hàng
└── special-notes.php     - Giao diện quản lý ghi chú
```

### API Files (3 files)
```
api/
├── booking-status.php    - API xử lý booking (GET/POST)
├── customer-list.php     - API xử lý customer (GET)
└── special-notes.php     - API xử lý notes (GET/POST)
```

---

## 🔌 API ENDPOINTS

### Booking API (`api/booking-status.php`)

**GET - Lấy danh sách booking:**
```
GET api/booking-status.php
Response: JSON array of bookings
```

**GET - Lấy danh sách trạng thái:**
```
GET api/booking-status.php?action=statuses
Response: JSON array ["Đang xử lý", "Đã xác nhận", ...]
```

**POST - Cập nhật trạng thái:**
```
POST api/booking-status.php
Content-Type: application/json
Body: {"id": "1", "status": "Đã xác nhận"}
Response: {"success": true}
```

### Customer API (`api/customer-list.php`)

**GET - Lấy danh sách khách hàng:**
```
GET api/customer-list.php
Response: JSON array of customers
```

**GET - Lấy chi tiết khách hàng:**
```
GET api/customer-list.php?action=detail&email=xxx@gmail.com
Response: {"customer": {...}, "bookings": [...], "notes": [...]}
```

### Notes API (`api/special-notes.php`)

**GET - Lấy danh sách ghi chú:**
```
GET api/special-notes.php
Response: JSON array of notes
```

**GET - Lấy loại ghi chú:**
```
GET api/special-notes.php?action=types
Response: {"an_chay": "Ăn chay", "di_ung": "Dị ứng", ...}
```

**POST - Thêm ghi chú:**
```
POST api/special-notes.php
Content-Type: application/json
Body: {"action": "add", "email": "xxx@gmail.com", "type": "an_chay", "content": "..."}
Response: {"success": true}
```

**POST - Cập nhật ghi chú:**
```
POST api/special-notes.php
Content-Type: application/json
Body: {"action": "update", "id": "1", "type": "an_chay", "content": "..."}
Response: {"success": true}
```

**POST - Xóa ghi chú:**
```
POST api/special-notes.php
Content-Type: application/json
Body: {"action": "delete", "id": "1"}
Response: {"success": true}
```

---

## 🔧 TROUBLESHOOTING

### Lỗi: Trang không hiển thị dữ liệu

**Nguyên nhân có thể:**
1. Database chưa được import
2. Không có dữ liệu trong bảng `bookings`
3. Lỗi kết nối database

**Cách khắc phục:**
1. Kiểm tra file `commons/env.php` - Đảm bảo thông tin database đúng:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USERNAME', 'root');
   define('DB_PASSWORD', '');
   define('DB_NAME', 'travel_db');
   ```
2. Import lại file SQL: `travel_db (2).sql`
3. Kiểm tra có dữ liệu trong bảng `bookings`:
   ```sql
   SELECT * FROM bookings;
   ```

### Lỗi: Modal không hiển thị

**Nguyên nhân:**
- Bootstrap JavaScript chưa được load

**Cách khắc phục:**
- Kiểm tra file view có dòng:
  ```html
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  ```

### Lỗi: Cập nhật trạng thái không hoạt động

**Nguyên nhân:**
- API endpoint không đúng hoặc lỗi JavaScript

**Cách khắc phục:**
1. Mở Developer Tools (F12)
2. Kiểm tra tab Console xem có lỗi JavaScript không
3. Kiểm tra tab Network xem request có được gửi đi không
4. Kiểm tra file `api/booking-status.php` có tồn tại không

### Lỗi: Thêm ghi chú báo "Không tìm thấy booking"

**Nguyên nhân:**
- Email không tồn tại trong bảng `bookings`

**Cách khắc phục:**
1. Kiểm tra email có trong database:
   ```sql
   SELECT DISTINCT email FROM bookings;
   ```
2. Sử dụng email chính xác từ danh sách trên

### Lỗi: Sidebar không responsive

**Nguyên nhả:**
- CSS chưa được load

**Cách khắc phục:**
- Kiểm tra file view có dòng:
  ```html
  <link rel="stylesheet" href="./assets/list.css">
  ```

---

## ✅ CHECKLIST TEST

Sử dụng checklist này để đảm bảo tất cả tính năng hoạt động:

### Quản lý Booking
- [ ] Trang booking-list hiển thị đúng
- [ ] Danh sách booking hiển thị đầy đủ thông tin
- [ ] Trạng thái có màu badge đúng
- [ ] Modal cập nhật trạng thái hiển thị
- [ ] Dropdown trạng thái có đầy đủ option
- [ ] Cập nhật trạng thái thành công
- [ ] Trang reload sau khi cập nhật

### Quản lý Khách hàng
- [ ] Trang customer-list hiển thị đúng
- [ ] Danh sách khách hàng không trùng lặp
- [ ] Thống kê hiển thị đúng
- [ ] Modal chi tiết hiển thị
- [ ] Thông tin khách hàng đầy đủ
- [ ] Danh sách booking của khách hàng hiển thị
- [ ] Ghi chú của khách hàng hiển thị

### Quản lý Ghi chú
- [ ] Trang special-notes hiển thị đúng
- [ ] Danh sách ghi chú hiển thị
- [ ] Modal thêm ghi chú hoạt động
- [ ] Dropdown loại ghi chú có đầy đủ option
- [ ] Thêm ghi chú thành công
- [ ] Sửa ghi chú thành công
- [ ] Xóa ghi chú thành công
- [ ] Validation email hoạt động đúng

---

## 📞 LIÊN HỆ HỖ TRỢ

Nếu gặp vấn đề, kiểm tra:
1. File log PHP (nếu có)
2. Console trình duyệt (F12)
3. Network tab để xem request/response
4. Database connection trong `commons/env.php`

---

**Chúc bạn test thành công! 🎉**

