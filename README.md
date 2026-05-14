https://yemeksistemi-production.up.railway.app/
# 🍽️ KYK Yemek Portalı

KYK yurtlarındaki günlük yemek menülerini şehir ve tarih bazlı olarak listeleyen, yönetim paneli üzerinden veri girişine imkân tanıyan web tabanlı bir portal uygulamasıdır.

---

## 🚀 Temel Özellikler

- **Tarih ve Şehir Filtreleme:** Kullanıcılar seçtikleri tarihe ve şehre (İzmir, İstanbul, Ankara) göre yemek listelerini görüntüleyebilir.
- **Öğün Ayrımı:** Sabah ve akşam menüleri kalori bilgileriyle birlikte ayrı bölümlerde listelenir.
- **Yönetim Paneli:** Yetkili kullanıcıların sisteme yeni menü eklemesine veya mevcut menüleri güncellemesine olanak sağlar.
- **Güvenlik:** Yönetici bilgileri harici bir yapılandırma dosyasında saklanır ve GitHub gibi platformlarda gizli tutulur.
- **Responsive Tasarım:** Tailwind CSS kullanılarak mobil ve masaüstü cihazlarla tam uyumlu hale getirilmiştir.

---

## ⚙️ Gereksinimler

| Yöntem | Gereksinimler |
|---|---|
| Docker ile | Docker, Docker Compose |
| Yerel kurulum | PHP 8.0+, MySQL 8.0+ |

---

## 📁 Dosya Yapısı

```
kyk-yemek-portali/
├── docker-compose.yml
└── src/
    ├── index.php          → Kullanıcı arayüzü ve menü listeleme
    ├── admin.php          → Menü ekleme ve düzenleme paneli
    ├── login.php          → Yönetim paneli giriş ekranı
    ├── config/
    │   ├── db.php         → Veritabanı bağlantı ayarları (gizli tutulmalı)
    │   └── security.php   → Yönetici giriş bilgileri (gizli tutulmalı)
    └── includes/
        ├── header.php     → Sayfa başlığı bileşeni
        └── footer.php     → Sayfa altlığı bileşeni
```

> ⚠️ `config/db.php` ve `config/security.php` dosyaları `.gitignore`'a eklenmiştir. Bu dosyaları kendiniz oluşturmanız gerekmektedir.

---

## 🗄️ Veritabanı Kurulumu

Her iki kurulum yöntemi için de önce aşağıdaki SQL ile tabloyu oluşturmanız gerekmektedir.

**Veritabanı adı:** `kyk_yemek_db`

```sql
CREATE DATABASE IF NOT EXISTS kyk_yemek_db CHARACTER SET utf8 COLLATE utf8_turkish_ci;

USE kyk_yemek_db;

CREATE TABLE yemekler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tarih DATE NOT NULL,
    ogun_turu ENUM('kahvalti', 'aksam') NOT NULL,
    sehir VARCHAR(50) NOT NULL,
    yemek_adi VARCHAR(255) NOT NULL,
    kalori INT DEFAULT 0
);
```

---

## 🐳 Yöntem 1: Docker ile Kurulum (Önerilen)

Docker kullanımı en kolay ve sorunsuz yöntemdir. PHP, Apache ve MySQL otomatik olarak ayağa kalkar.

### Adımlar

**1. Depoyu klonlayın:**
```bash
git clone <repo-url>
cd kyk-yemek-portali
```

**2. Yapılandırma dosyalarını oluşturun:**

`src/config/db.php` dosyasını aşağıdaki içerikle oluşturun:
```php
<?php
$host = 'db';            // Docker servis adı — değiştirmeyin
$dbname = 'kyk_yemek_db';
$username = 'root';
$password = '123';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}
```

`src/config/security.php` dosyasını aşağıdaki içerikle oluşturun:
```php
<?php
return [
    'admin_user' => 'admin',
    'admin_pass' => 'sifreniz'
];
```

**3. Docker container'larını başlatın:**
```bash
docker compose up -d
```

**4. Veritabanı tablosunu oluşturun:**

Tarayıcıdan phpMyAdmin'e gidin: [http://localhost:8081](http://localhost:8081)
- Kullanıcı adı: `root`
- Şifre: `123`

Sol panelden `kyk_yemek_db`'yi seçin, SQL sekmesine tıklayın ve yukarıdaki `CREATE TABLE` sorgusunu çalıştırın.

**5. Uygulamayı açın:**

[http://localhost:8080](http://localhost:8080)

### Docker Komutları

```bash
# Başlatmak için
docker compose up -d

# Durdurmak için
docker compose down

# Logları görmek için
docker compose logs -f

# Container durumlarını görmek için
docker ps
```

### Docker Servis Portları

| Servis | Adres |
|---|---|
| Uygulama | http://localhost:8080 |
| phpMyAdmin | http://localhost:8081 |
| MySQL | localhost:3307 |

---

## 💻 Yöntem 2: Yerel Kurulum (Docker Olmadan)

PHP ve MySQL bilgisayarınızda kurulu olmalıdır.

### Adımlar

**1. Depoyu klonlayın:**
```bash
git clone <repo-url>
cd kyk-yemek-portali
```

**2. MySQL'in çalıştığını doğrulayın:**
```bash
# Mac (Homebrew):
brew services start mysql

# Windows: XAMPP/WAMP kontrol panelinden MySQL'i başlatın

# Linux:
sudo systemctl start mysql
```

**3. Veritabanını oluşturun:**
```bash
mysql -u root -p
```
```sql
CREATE DATABASE kyk_yemek_db CHARACTER SET utf8 COLLATE utf8_turkish_ci;
USE kyk_yemek_db;

CREATE TABLE yemekler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tarih DATE NOT NULL,
    ogun_turu ENUM('kahvalti', 'aksam') NOT NULL,
    sehir VARCHAR(50) NOT NULL,
    yemek_adi VARCHAR(255) NOT NULL,
    kalori INT DEFAULT 0
);
```

**4. Yapılandırma dosyalarını oluşturun:**

`src/config/db.php` dosyasını aşağıdaki içerikle oluşturun:
```php
<?php
$host = '127.0.0.1';
$dbname = 'kyk_yemek_db';
$username = 'root';
$password = 'mysql_sifreniz';  // MySQL şifrenizi girin

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}
```

`src/config/security.php` dosyasını aşağıdaki içerikle oluşturun:
```php
<?php
return [
    'admin_user' => 'admin',
    'admin_pass' => 'sifreniz'
];
```

**5. PHP geliştirme sunucusunu başlatın:**
```bash
cd src
php -S localhost:8000
```

**6. Uygulamayı açın:**

[http://localhost:8000](http://localhost:8000)

---

## 🔐 Güvenlik Notları

- `config/db.php` ve `config/security.php` dosyaları `.gitignore`'a eklenmiştir, GitHub'a yüklenmez.
- Bu dosyaları her ortamda (yerel, Docker, sunucu) ayrı ayrı oluşturmanız gerekmektedir.
- Üretim ortamında güçlü şifreler kullanın.

---

## 🛠️ Sık Karşılaşılan Sorunlar

**`$db null` / `prepare() on null` hatası**
- Docker kullanıyorsanız: `db.php`'de host `'db'` olmalı, `'127.0.0.1'` değil.
- Yerel kurulumda: `db.php`'de host `'127.0.0.1'` olmalı.
- MySQL servisinin çalıştığından emin olun.

**Docker'da sayfa açılmıyor**
- `docker ps` ile container'ların çalışıp çalışmadığını kontrol edin.
- `localhost:8080` kullanın, `localhost:8000` değil.

**`php -S` ile bağlantı hatası**
- `php -S` Docker'ı bypass eder, MySQL'e doğrudan bağlanamaz.
- Ya `localhost:8080` üzerinden Docker'ı kullanın, ya da yerel MySQL kurulumunuzla çalışın.

---

## 📝 Lisans

Bu proje eğitim amaçlı geliştirilmiştir.
