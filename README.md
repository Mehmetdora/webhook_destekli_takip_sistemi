# Webhook Destekli Sipariş Takip Sistemi
Bu proje, Laravel 11 kullanılarak geliştirilmiş basit bir sipariş takip sistemidir.
Sipariş oluşturma, listeleme ve sipariş durumu güncelleme işlemleri yapılabilmektedir.
Sipariş durumu güncellendiğinde, işlemler servis katmanı üzerinde yapılarak sisteme webhook bildirimi gönderilir, böylece controller dosyalarında sadece ilgili servisler çağrılarak kullanılır. <br>
Webhook isteği başarısız olsa bile sipariş durumu sistem içinde başarıyla güncellenmeye devam eder ve hata loglanır.

## Kullanılan Teknolojiler
Laravel: 11<br>
PHP: 8.x<br>
Veritabanı: MySQL<br>
HTTP Client: Laravel HTTP Client (Http::post)<br>
Loglama: Laravel Log sistemi<br>

## Proje Yapısı (Özet)
app/Http/Controllers → Request karşılayan controller’lar<br>
app/Services → Asıl işlemlerin bulunduğu servis katmanı<br>
database/migrations → Veritabanı tabloları<br>
routes/api.php → API endpoint tanımları<br>

## Kurulum Adımları
### 1️⃣ Projeyi klonlayın<br>
git clone https://github.com/Mehmetdora/webhook_destekli_takip_sistemi.git<br>
cd webhook_destekli_takip_sistemi<hr>
### 2️⃣ Bağımlılıkları yükleyin
composer install<hr>
### 3️⃣ .env dosyasını oluşturun
cp .env.example .env<hr>
### 4️⃣ Uygulama anahtarını oluşturun
php artisan key:generate<hr>
### Veritabanı Ayarları
MySQL üzerinde bir veritabanı oluşturun,<br><br>
.env dosyasında aşağıdaki alanları düzenleyin:<br>
DB_CONNECTION=mysql<br>
DB_HOST=127.0.0.1<br>
DB_PORT=3306<br>
DB_DATABASE=webhook_destekli_takip_sistemi <br>
DB_USERNAME=root<br>
DB_PASSWORD=<br><br>
Not: DB_DATABASE değeri istenilen başka bir isimle değiştirilebilir.<hr>
### Webhook Ayarları
Webhook davranışını test edebilmek için .env dosyasında aşağıdaki değişkenler tanımlanmıştır:<br>
WEBHOOK_URL2="https://webhook.site/"<br>
WEBHOOK_URL="https://httpbin.org/status/503"<br><br>
WEBHOOK_URL2 → Başarılı webhook testleri için kullanılabilir<br>
WEBHOOK_URL → Bilinçli olarak 503 hatası dönen bir URL (hata ve loglama testleri için)<br><hr>

### Migration’ları Çalıştırma
php artisan migrate<br><br>
Bu işlemle birlikte orders tablosu oluşturulur.<br>
### Uygulamayı Çalıştırma
php artisan serve<br><br>
Varsayılan adres:
http://127.0.0.1:8000<hr>
### API Endpoint’leri
#### Sipariş Oluşturma:<br>
POST /api/orders<br>
{<br>
    "order_no": "333",<br>
    "customer_name": "User1",<br>
    "total_price": 999.99,<br>
    "status": "cancelled"<br>
}<br><br>
#### Sipariş Listeleme:<br>
GET /api/orders<br><br>
#### Sipariş Durumu Güncelleme
POST /api/orders/{id}/status<br>
{<br>
  "status": "paid"<br>
}<br><br>
Geçerli status değerleri:<br>
pending, paid, shipped, cancelled<hr>
#### Webhook Çalışma Mantığı
Sipariş durumu güncellendiğinde öncelikle OrderUpdateRequest.php ile gelen verilerin doğrulanması yapılır. <br> Sonrasında veriler düzgünse önce veritabanı üzerinde güncelleme yapılır, hata durumunda geriye bilgilendirme dönülür. Eğer hata alınmazsa en son ilgili webhook servisi çağrılarak ilgili url'e Http::post() kullanılarak bilgiler gönderilir. <br><br>
Webhook isteği başarısız olursa:<br>
Sipariş durumu geri alınmaz<br>
Hata loglanır<br>
Kullanıcıya başarılı response döner<hr>
### Loglama
Webhook başarısızlıkları storage/logs/laravel.log dosyasına aşağıdaki bilgilerle kaydedilir:<br>
Sipariş Kaydı Id'si,<br>
Sipariş numarası,<br>
Gönderilmeye çalışılan url,<br>
Hata mesajı,<br>
Status,<br>
Response Header,Body,Payload<br><br>
Bu sayede sistem içi işlemler etkilenmeden hatalar takip edilebilir.<hr>
### Webhook Testi
Webhook testleri için:<br>
https://webhook.site adresi kullanılabilir<br>
Veya hata senaryosu için https://httpbin.org/status/503 kullanılabilir<hr>
### Notlar
Controller’lar yalnızca request/response yönetir.
Asıl veri tabanı işlemleri ve kontroller servis katmanında yer alır. <br>
