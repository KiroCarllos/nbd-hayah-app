<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\CampaignUpdate;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user
        $admin = User::where('is_admin', true)->first();
        if (!$admin) {
            $this->command->error('No admin user found. Please run UserSeeder first.');
            return;
        }

        // Get campaigns
        $campaigns = Campaign::all();
        if ($campaigns->isEmpty()) {
            $this->command->error('No campaigns found. Please run CampaignSeeder first.');
            return;
        }

        // Sample updates for each campaign
        $updateTemplates = [
            [
                'title' => 'تحديث حول الحالة الصحية',
                'content' => 'نحن سعداء لإعلامكم أن الحالة الصحية للمريض تتحسن تدريجياً. تم إجراء الفحوصات اللازمة وأظهرت النتائج تحسناً ملحوظاً في الحالة العامة.',
                'type' => 'medical',
                'is_important' => true,
            ],
            [
                'title' => 'وصول تبرعات جديدة',
                'content' => 'بفضل كرمكم وتبرعاتكم السخية، تمكنا من جمع مبلغ إضافي يساعد في تغطية تكاليف العلاج. نشكركم جميعاً على دعمكم المستمر.',
                'type' => 'financial',
                'is_important' => false,
            ],
            [
                'title' => 'نقل المريض للمستشفى',
                'content' => 'تم نقل المريض إلى المستشفى التخصصي لتلقي العلاج المطلوب. الفريق الطبي يتابع الحالة عن كثب ونتوقع تحسناً في الأيام القادمة.',
                'type' => 'progress',
                'is_important' => true,
            ],
            [
                'title' => 'شكر وتقدير',
                'content' => 'نتقدم بجزيل الشكر والامتنان لجميع المتبرعين الكرام الذين ساهموا في دعم هذه الحملة. دعمكم يعني الكثير لنا وللمستفيدين.',
                'type' => 'general',
                'is_important' => false,
            ],
            [
                'title' => 'تحديث عاجل - حاجة ماسة',
                'content' => 'نحتاج بشكل عاجل لمزيد من التبرعات لتغطية تكاليف العملية الجراحية العاجلة. كل مساهمة مهما كانت صغيرة ستحدث فرقاً كبيراً.',
                'type' => 'urgent',
                'is_important' => true,
            ],
        ];

        foreach ($campaigns as $campaign) {
            // Add 2-4 random updates for each campaign
            $numUpdates = rand(2, 4);
            $selectedUpdates = collect($updateTemplates)->random($numUpdates);

            foreach ($selectedUpdates as $index => $updateData) {
                CampaignUpdate::create([
                    'campaign_id' => $campaign->id,
                    'title' => $updateData['title'],
                    'content' => $updateData['content'],
                    'type' => $updateData['type'],
                    'is_important' => $updateData['is_important'],
                    'created_by' => $admin->id,
                    'created_at' => now()->subDays(rand(1, 30))->subHours(rand(1, 23)),
                ]);
            }
        }

        $this->command->info('Campaign updates seeded successfully!');
    }
}
