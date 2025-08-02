# إعداد Pusher للإشعارات

## المتغيرات المطلوبة في ملف .env

أضف هذه المتغيرات إلى ملف `.env` الخاص بك:

```env
# Broadcasting
BROADCAST_DRIVER=pusher

# Pusher Configuration
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

# Frontend Variables
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

## خطوات الإعداد

1. إنشاء حساب في [Pusher](https://pusher.com/)
2. إنشاء تطبيق جديد
3. نسخ App ID, App Key, App Secret
4. إضافة هذه القيم إلى ملف `.env`
5. تشغيل الخادم

## كيفية الحصول على بيانات Pusher

1. اذهب إلى [dashboard.pusher.com](https://dashboard.pusher.com/)
2. أنشئ تطبيق جديد
3. اختر "Channels" كمنتج
4. انسخ البيانات من صفحة "Keys"

## اختبار الإشعارات

عند إنشاء كورس جديد من لوحة التحكم، سيتم إرسال إشعار فوري لجميع المستخدمين المتصلين. 