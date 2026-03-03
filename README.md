# Personal Finance Management -- Prototype

## 1. Giới thiệu

Đây là ứng dụng **Quản lý tài chính cá nhân (Prototype)** cho phép người
dùng:

-   Quản lý thu nhập (Income)
-   Quản lý chi tiêu (Expense)
-   Quản lý mục tiêu tiết kiệm (Goal)
-   Nạp tiền vào mục tiêu
-   Tự động cập nhật số dư (Balance)

### Tech Stack

-   **Backend:** PHP thuần (REST API)
-   **Frontend:** VueJS
-   **Database:** MySQL

------------------------------------------------------------------------

## 2. Yêu cầu môi trường

-   PHP \>= 8.0
-   MySQL \>= 5.7
-   NodeJS \>= 16
-   XAMPP / Laragon / Apache server

------------------------------------------------------------------------

## 3. Cài đặt & Chạy dự án

### Bước 1: Clone project

``` bash
git clone https://github.com/DuckZiiii300305/finance-app.git
cd finance-app
```

------------------------------------------------------------------------

### Bước 2: Cấu hình Database

Tạo database:

``` sql
CREATE DATABASE finance_app;
```

Import file:

database/finance_app.sql

Cấu hình file kết nối database tại:

backend/config/database.php

``` php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "finance_app";
```

------------------------------------------------------------------------

### Bước 3: Chạy Backend (PHP)

#### Nếu dùng XAMPP

-   Copy thư mục `backend` vào `htdocs`
-   Truy cập:

http://localhost/backend

#### Hoặc dùng PHP built-in server

``` bash
cd backend
php -S localhost:8000
```

------------------------------------------------------------------------

### Bước 4: Chạy Frontend (VueJS)

``` bash
cd frontend
npm install
npm run dev
```

Truy cập:

http://localhost:8080

------------------------------------------------------------------------

## 4. API chính

### Income

-   POST /income/add
-   DELETE /income/delete/{id}

### Expense

-   POST /expense/add
-   DELETE /expense/delete/{id}

### Goal

-   POST /goal/add
-   DELETE /goal/delete/{id}
-   POST /goal/deposit

------------------------------------------------------------------------

## 5. Chức năng chính

✔ Thêm / Xóa thu nhập\
✔ Thêm / Xóa chi tiêu\
✔ Thêm mục tiêu có ngày hoàn thành\
✔ Nạp tiền vào Goal\
✔ Tự động cập nhật Balance khi:

-   Thêm income\
-   Thêm expense\
-   Xóa income\
-   Xóa expense\
-   Nạp tiền goal

------------------------------------------------------------------------

## 6. Tài khoản test

Không yêu cầu đăng nhập (Prototype đơn user).

------------------------------------------------------------------------

## 7. Ghi chú

-   Prototype tập trung vào logic backend và flow dữ liệu.\
-   Không tối ưu bảo mật cho môi trường production.
