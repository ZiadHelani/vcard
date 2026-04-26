<?php

namespace Database\Seeders;

use App\Models\PrivacyContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrivacyContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $privacyContent = [
            [
                'privacy_policy' => [
                    'en' => "
Welcome to Knot. We are committed to protecting your privacy and handling your personal information responsibly.

This Privacy Policy explains what information we collect when you use our website, mobile application, or services, and how we use, maintain, and protect that information.

Information We Collect:
We may collect personal information that you provide directly such as your name, email address, phone number, and other profile details when you register or use our services. We may also collect information automatically such as device information, IP address, and usage data.

Use of Information:
The collected information is used to operate and improve our services, personalize user experience, communicate with users, and ensure the security of our platform.

Optional Contact Access:
Our application may offer an optional feature allowing you to upload your contact list to connect with people you know. This feature requires your explicit consent and can be revoked at any time.

Third-Party Links:
Our platform may contain links to third-party websites. We are not responsible for their privacy practices or content.

Security:
We implement security measures designed to protect your personal data from unauthorized access, loss, or misuse.

By using our services, you agree to the terms of this Privacy Policy.
",
                    'ar' => "
مرحباً بك في Knot. نحن ملتزمون بحماية خصوصية المستخدمين والتعامل مع بياناتهم الشخصية بمسؤولية.

توضح سياسة الخصوصية هذه نوع المعلومات التي نقوم بجمعها عند استخدامك لموقعنا أو تطبيقنا أو خدماتنا، وكيف نقوم باستخدام هذه المعلومات وحمايتها.

المعلومات التي نجمعها:
قد نقوم بجمع معلومات شخصية تقدمها لنا بشكل مباشر مثل الاسم والبريد الإلكتروني ورقم الهاتف ومعلومات الملف الشخصي عند التسجيل أو استخدام الخدمة. كما قد نجمع معلومات بشكل تلقائي مثل معلومات الجهاز وعنوان IP وبيانات الاستخدام.

استخدام المعلومات:
نستخدم هذه المعلومات لتشغيل خدماتنا وتحسينها، وتخصيص تجربة المستخدم، والتواصل مع المستخدمين، وضمان أمان المنصة.

مشاركة جهات الاتصال:
قد يوفر التطبيق ميزة اختيارية تسمح برفع قائمة جهات الاتصال الخاصة بك لمساعدتك في التواصل مع معارفك. يتم ذلك فقط بعد الحصول على موافقتك ويمكنك إلغاء هذه الميزة في أي وقت.

روابط الطرف الثالث:
قد يحتوي الموقع على روابط لمواقع أخرى. نحن غير مسؤولين عن سياسات الخصوصية أو المحتوى الخاص بتلك المواقع.

الأمان:
نطبق إجراءات أمنية مناسبة لحماية بياناتك الشخصية من الوصول غير المصرح به أو الفقدان أو سوء الاستخدام.

باستخدامك للخدمة فإنك توافق على سياسة الخصوصية هذه.
"
                ],

                'terms_of_use' => [
                    'en' => "
By accessing or using the Knot website or application, you agree to comply with these Terms of Use.

User Responsibilities:
You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.

Acceptable Use:
You agree not to use the platform for illegal activities, unauthorized access attempts, or any activity that violates applicable laws.

Intellectual Property:
All content, trademarks, logos, and materials available on the platform are the property of Knot or its licensors and may not be reproduced or distributed without permission.

User Content:
Users may upload or share content on the platform. By submitting content, you grant Knot a non-exclusive, worldwide, royalty-free license to use, display, and distribute that content for the purpose of operating the service.

Third-Party Services:
The platform may contain links to third-party services. Knot is not responsible for the content or practices of those services.

Limitation of Liability:
Knot shall not be liable for any damages arising from the use of the platform.

Changes to Terms:
Knot reserves the right to update these terms at any time. Continued use of the platform indicates acceptance of the updated terms.
",
                    'ar' => "
باستخدامك لموقع أو تطبيق Knot فإنك توافق على الالتزام بشروط الاستخدام التالية.

مسؤولية المستخدم:
أنت مسؤول عن الحفاظ على سرية بيانات حسابك وكلمة المرور، وجميع الأنشطة التي تتم من خلال حسابك.

الاستخدام المقبول:
يجب عدم استخدام المنصة لأي أنشطة غير قانونية أو لمحاولة الوصول غير المصرح به إلى الأنظمة أو حسابات المستخدمين الآخرين.

الملكية الفكرية:
جميع المحتويات والشعارات والعلامات التجارية الموجودة على المنصة هي ملك لشركة Knot أو الجهات المرخصة لها، ولا يجوز استخدامها أو إعادة نشرها دون إذن مسبق.

محتوى المستخدم:
يمكن للمستخدمين نشر أو مشاركة محتوى على المنصة. وبنشر هذا المحتوى فإنك تمنح Knot ترخيصاً عالمياً غير حصري لاستخدام هذا المحتوى لغرض تشغيل الخدمة.

خدمات الطرف الثالث:
قد تحتوي المنصة على روابط لمواقع أو خدمات خارجية، ولا تتحمل Knot مسؤولية محتواها أو سياسات الخصوصية الخاصة بها.

تحديد المسؤولية:
لا تتحمل Knot أي مسؤولية عن الأضرار الناتجة عن استخدام المنصة.

تعديل الشروط:
تحتفظ Knot بالحق في تعديل هذه الشروط في أي وقت، ويعد استمرار استخدامك للمنصة موافقة على التعديلات.
"
                ],
            ]
        ];

        foreach ($privacyContent as $p) {
            PrivacyContent::query()->create($p);
        }

    }
}
