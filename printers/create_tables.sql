-- إنشاء جدول أجهزة الكمبيوتر
CREATE TABLE IF NOT EXISTS computers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asset_tag VARCHAR(255) NOT NULL UNIQUE,
    type ENUM('Desktop', 'Laptop', 'All-in-One'),
    make VARCHAR(100),
    model VARCHAR(100),
    serial_number VARCHAR(255),
    cpu VARCHAR(100),
    ram_gb INT,
    storage_type ENUM('SSD', 'HDD'),
    storage_gb INT,
    os VARCHAR(100),
    location VARCHAR(255),
    status ENUM('قيد الاستخدام', 'تحت الصيانة', 'معطل', 'مخزن') DEFAULT 'قيد الاستخدام',
    assigned_to VARCHAR(255),
    purchase_date DATE,
    warranty_expiry DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- إنشاء جدول الطابعات
CREATE TABLE IF NOT EXISTS printers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asset_tag VARCHAR(255) NOT NULL UNIQUE,
    make VARCHAR(100),
    model VARCHAR(100),
    serial_number VARCHAR(255),
    type ENUM('Laser', 'Inkjet', 'Multifunction'),
    location VARCHAR(255),
    status ENUM('يعمل', 'تحتاج حبر/حبر نافذ', 'تحت الصيانة', 'معطل') DEFAULT 'يعمل',
    ip_address VARCHAR(50),
    purchase_date DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- إنشاء جدول الهواتف الأرضية
CREATE TABLE IF NOT EXISTS landlines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone_number VARCHAR(50) NOT NULL UNIQUE,
    department VARCHAR(255),
    status ENUM('يعمل', 'معطل') DEFAULT 'يعمل',
    location VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);