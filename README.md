<div dir="rtl">

### کلیت موارد پیاده سازی شده:

1. امکان خواندن کانفیگ ها از دیتابیس
2. امکان تخصیص یک کانفیگ به یک کاربر


### مواردی که اضافه شده

1. افزودن پارامتر به فایل کانفیگ gateway  
   1. اضافه کردن پارامتر source  
       1. می تواند database باشد که کافنیگ ها را از دیتابیس بخواند  
       2. می تواند config باشد که مثل روش قبلی، کانفیگ ها را از کانفیگ خود پکیج بخواند  
   2. اضافه کردن پارامتر gateway_configs_table_name (مشخص می کند که نام جدول کانفیگ درگاه ها چه باشد)
   3. اضافه کردن پارامتر users_table_name (نام جدول کاربران که ستون gateway_config_id  پس از اجرای مایگرشن به آن افزوده می شود)  

2. مایگریشن:
    1.  ساخت جدول gateway_configs
    2.  افزودن ستون gateway_config_id به جدول users (نام جدول در فایل کانفیگ  قابل تغییر است)

3. مدل 
    1. ایجاد مدل GatewayConfig برای کار با جدول مربوطه

4. Trait
    1. ایجاد تریت HasGatewayConfig که باید به مدل کاربر اضافه شود و ارتباط بین جدول کاربران و GatewayConfig را بوجود می آورد.  
5. تغییرات در هسته ی کد
    1. تغییرات در GatewayResolver 

### نحوه ی استفاده

1. با قرار دادن مقدار database در پارامتر source فایل کانفیگ، می توانید کانفیگ درگاه خود را از دیتابیس بخوانید. و اگر می خواهید مانند قدیم، کانفیگ ها از فایل کانفیگ خود پکیج خوانده شود، مقدار آن را برابر config قرار دهید.

2. فایل های پروژه را پابلیش کنید. (این مورد با توجه به اینکه پکیج قدیمی است، در نسخه های جدید لاراول قابل اجرا نیست)

3. پارامتر های زیر را در مسیر config/gateway.php با توجه به نیاز های خود تغییر دهید
- source (config | database)
- gateway_configs_table_name (نام جدولی که کانفیگ های درگاه پرداخت در آن ذخیره می شوند) 
- users_table_name (نام جدول کاربران در برنامه ی شما)

4. تریت HasGatewayConfig را به مدل کاربر خود اضافه کنید. این تریت ارتباط بین جدول کاربر و کانفیگ درگاه های پرداخت را برقرار می کند. 

5. کانفیگ دلخواه خود را در دیتابیس با توجه به ستون های زیر ایجاد کنید:
- port (نام درگاه. با حروف کوچک نوشته شود و از بین درگاه های پرداخت پیاده سازی شده در فایل src/Enum.php باشد)
- name (نام دلخواه کانفیگ)
- config (داده های کانفیگ خود را به صورت آرایه ای در اینجا ذخیره کنید. نیازی به انکود و دیکود داده های آرایه ای نیست. ستون config در مدل GatewayConfig به صورت آرایه کست شده است.)

6. حال می توانید با یکی از دو روش زیر، کانفیگ دلخواه خود را از دیتابیس دریافت و به درگاه پرداخت متصل شوید.

    1. اتصال از طریق نام کانفیگ با تابع makeWithConfigName
        1. با پاس دادن نام درگاه و سپس نام کانفیگ ثبت شده در دیتابیس، می توانید کانفیگ را دریافت کرده و تراکنش جدیدی در بانک مورد نظر بسازید.

    2. اتصال از طریق کانفیگ کاربر با تابع makeWithUser

       1. مطمئن شوید که مرحله ی 4 را انجام داده اید. سپس یک کانفیگ را به کاربر مورد نظر اختصاص دهید. این کار با ست کردن آیدی مورد نظر کانفیگ در ستون gateway_config_id رکورد کاربر انجام می شود.
       2. تابع makeWithUser را فراخوانی کنید. پارامتر اول نام درگاه مورد نظر و پارامتر دوم، مدل کاربر مورد نظر است. 
       3. نکته: در نظر داشته باشید که باید ریلیشن gatewayConfig را ایگرلود کرده باشید. 

نکاتی که هنگام پیاده سازی به ذهنم رسید و شایان ذکر است:
1. می توانستم در تابع makeWithUser، به جای مدل کاربر، آیدی کاربر را گرفته و خودم عملیات ایگرلود و گرفتن از پایگاه داده را انجام دهم. ولی از آنجایی که سیستم ممکن است چند سیستم احراز هویت داشته باشد، استفاده از config('auth.providers.users.model') ممکن بود با توجه به ساختار پروژه، کار درستی نباشد. بخاطر همین ترجیح دادم که خود برنامه نویس مدل را ایگرلود شده به تابع پاس بدهد.

با تشکر  
مصطفی بینش



```
متاسفانه این پکیج دیگر پشتیبانی نمی شود
```

پکیج اتصال به تمامی IPG ها و  بانک های ایرانی.

این پکیج با ورژن های
(  ۴ و ۵ و ۶  )
 لاراول سازگار می باشد


پشتیبانی تنها از درگاهای زیر می باشد:
 1. MELLAT
 2. SADAD (MELLI)
 3. SAMAN
 4. PARSIAN
 5. PASARGAD
 6. ZARINPAL
 7. PAYPAL 
 8. ASAN PARDAKHT 
 9. PAY.IR ( برای فراخوانی از 'payir' استفاده نمایید)
 10. Irankish (**جدید** -  برای فراخوانی از 'irankish' استفاده نمایید)
----------


**نصب**:

دستورات زیر را جهت نصب دنبال کنید :

**مرحله ۱)**

</div>


```php

composer require larabook/gateway

```   

<div dir="rtl">
 
**مرحله ۲)**

    تغییرات زیر را در فایل  config/app.php اعمال نمایید:

**توجه برای نسخه های لاراول ۶ به بعد  این مرحله نیاز به انجام نمی باشد** 

</div>

```php

'providers' => [
  ...
  Larabookir\Gateway\GatewayServiceProvider::class, // <-- add this line at the end of provider array
],


'aliases' => [
  ...
  'Gateway' => Larabookir\Gateway\Gateway::class, // <-- add this line at the end of aliases array
]

```



<div dir="rtl">

**مرحله ۳) - انتقال فایل های مورد نیاز**

برای لاراول ۵ :
</div>

```php

php artisan vendor:publish --provider=Larabookir\Gateway\GatewayServiceProviderLaravel5

```

<div dir="rtl">
برای لاراول ۶ به بعد :
</div>

```php

php artisan vendor:publish 

// then choose : GatewayServiceProviderLaravel6

```

<div dir="rtl"> 

**مرحله ۴) - ایجاد جداول**

</div>

```php

php artisan migrate

```


<div dir="rtl"> 
 
**مرحله ۵)**

عملیات نصب پایان یافته است حال فایل gateway.php را در مسیر app/  باز نموده و  تنظیمات مربوط به درگاه بانکی مورد نظر خود را در آن وارد نمایید .

حال میتوایند برای اتصال به api  بانک  از یکی از روش های زیر به انتخاب خودتان استفاده نمایید . (Facade , Service container):
</div>
 
 1. Gateway::make(new Mellat())
 2. Gateway::make('mellat')
 3. Gateway::mellat()
 4. app('gateway')->make(new Mellat());
 5. app('gateway')->mellat();
 
<div dir="rtl">

 مثال :‌اتصال به بانک ملت (درخواست توکن و انتقال کاربر به درگاه بانک)
توجه :‌ مقدار متد price   به ریال وارد شده است و معادل یکصد تومان می باشد

یک روت از نوع GET با آدرس /bank/request ایجاد نمایید و کد های زیر را در آن قرار دهید .

</div>


```php

try {

   $gateway = \Gateway::make('mellat');

   $gateway->setCallback(url('/bank/response')); // You can also change the callback
   $gateway->price(1000)
           // setShipmentPrice(10) // optional - just for paypal
           // setProductName("My Product") // optional - just for paypal
           ->ready();

   $refId =  $gateway->refId(); // شماره ارجاع بانک
   $transID = $gateway->transactionId(); // شماره تراکنش

   // در اینجا
   //  شماره تراکنش  بانک را با توجه به نوع ساختار دیتابیس تان 
   //  در جداول مورد نیاز و بسته به نیاز سیستم تان
   // ذخیره کنید .

   return $gateway->redirect();

} catch (\Exception $e) {

   echo $e->getMessage();
}

```

<div dir="rtl">

 و سپس روت با مسیر /bank/response  و از نوع post  ایجاد نمایید و کد های زیر را در آن قرار دهید :

</div>


```php

try { 

   $gateway = \Gateway::verify();
   $trackingCode = $gateway->trackingCode();
   $refId = $gateway->refId();
   $cardNumber = $gateway->cardNumber();

   // تراکنش با موفقیت سمت بانک تایید گردید
   // در این مرحله عملیات خرید کاربر را تکمیل میکنیم

} catch (\Larabookir\Gateway\Exceptions\RetryException $e) {

    // تراکنش قبلا سمت بانک تاییده شده است و
    // کاربر احتمالا صفحه را مجددا رفرش کرده است
    // لذا تنها فاکتور خرید قبل را مجدد به کاربر نمایش میدهیم

    echo $e->getMessage() . "<br>";

} catch (\Exception $e) {

    // نمایش خطای بانک
    echo $e->getMessage();
}

```

<div dir="rtl">
 
در صورت تمایل جهت همکاری در توسعه   :

 1. توسعه مستندات پکیج.
 2. گزارش باگ و خطا.
 3. همکاری در نوشتن ماژول دیگر بانک ها برای این پکیج .


درصورت بروز هر گونه 
 [باگ](https://github.com/larabook/gateway/issues) یا [خطا](https://github.com/larabook/gateway/issues)  .
  ما را آگاه سازید .
  
این پکیج از پکیج دیگری بنام  poolport  مشتق شده است اما برخی از عملیات آن متناسب با فریموورک لارول تغییر کرده است
</div>
