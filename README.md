https://yemeksistemi-production.up.railway.app/
# KYK Yemek Portalı

Bu proje, KYK yurtlarındaki günlük yemek menülerini şehir ve tarih bazlı olarak listeleyen, aynı zamanda bir yönetim paneli üzerinden veri girişine imkan tanıyan web tabanlı bir portal uygulamasıdır.

---

## 🚀 Temel Özellikler

- **Tarih ve Şehir Filtreleme:** Kullanıcılar seçtikleri tarihe ve şehre (İzmir, İstanbul, Ankara) göre yemek listelerini görüntüleyebilir.
- **Öğün Ayrımı:** Sabah ve akşam menüleri kalori bilgileriyle birlikte ayrı bölümlerde listelenir.
- **Yönetim Paneli:** Yetkili kullanıcıların sisteme yeni menü eklemesine veya mevcut menüleri güncellemesine olanak sağlar.
- **Güvenlik:** Yönetici bilgileri harici bir yapılandırma dosyasında saklanır ve GitHub gibi platformlarda gizli tutulur.
- **Responsive Tasarım:** Tailwind CSS kullanılarak mobil ve masaüstü cihazlarla tam uyumlu hale getirilmiştir.

---

## ⚙️ Kurulum ve Çalıştırma

### 📌 Gereksinimler

- PHP 8.0 veya üzeri
- MySQL / MariaDB
- Docker (Opsiyonel)

---

### 💻 Yerel Kurulum (Localhost)

1. Depoyu bilgisayarınıza indirin:
   ```bash
   git clone <repo-url>
   ```

2. MySQL üzerinde `kyk_yemek_db` adında bir veritabanı oluşturun.

3. Aşağıdaki SQL komutunu çalıştırarak gerekli tabloyu oluşturun:

   ```sql
   CREATE TABLE yemekler (
       id INT AUTO_INCREMENT PRIMARY KEY,
       tarih DATE NOT NULL,
       ogun_turu ENUM('kahvalti', 'aksam') NOT NULL,
       sehir VARCHAR(50) NOT NULL,
       yemek_adi VARCHAR(255) NOT NULL,
       kalori INT DEFAULT 0
   );
   ```

4. `config/db.php` dosyasını açarak veritabanı kullanıcı adınızı ve şifrenizi girin.

5. Ana dizinde `config/security.php` adında bir dosya oluşturun ve şu içeriği ekleyin:

   ```php
   <?php
   return [
       'admin_user' => 'admin_kullanici_adi',
       'admin_pass' => 'admin_sifresi'
   ];
   ```

6. Terminal üzerinden projeyi başlatın:

   ```bash
   php -S localhost:8000
   ```

7. Tarayıcınızdan şu adrese gidin:
   ```
   http://localhost:8000
   ```

---

## 📁 Dosya Yapısı

```
index.php      → Kullanıcı arayüzü ve menü listeleme ekranı
admin.php      → Menü ekleme ve düzenleme işlemlerinin yapıldığı panel
login.php      → Yönetim paneline giriş ekranı
config/        → Veritabanı ve güvenlik yapılandırma dosyaları
includes/      → Header ve footer gibi tekrar eden bileşenler
```

---

## 📜 Lisans

Bu proje eğitim amaçlı geliştirilmiştir. Tüm hakları saklıdır.
