<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user first
        $admin = User::create([
            'name' => 'مدير الموقع',
            'email' => 'admin@nabdhayah.com',
            'mobile' => '0501234567',
            'password' => bcrypt('password'),
            'profile_image' => null,
            'wallet_balance' => 0,
            'is_admin' => true,
        ]);

        // Create sample campaigns
        $campaigns = [
            [
                'title' => 'مساعدة الأسر المحتاجة',
                'description' => 'حملة لمساعدة الأسر المحتاجة في توفير الطعام والمأوى الآمن. نهدف إلى الوصول لأكبر عدد من الأسر وتوفير احتياجاتهم الأساسية.',
                'target_amount' => 50000.00,
                'current_amount' => 15000.00,
                'images' => ['https://via.placeholder.com/400x300/28a745/ffffff?text=مساعدة+الأسر'],
                'is_priority' => true,
                'is_active' => true,
                'end_date' => now()->addMonths(3),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'علاج الأطفال المرضى',
                'description' => 'حملة خيرية لعلاج الأطفال المرضى الذين لا يستطيع أهلهم تحمل تكاليف العلاج. كل مساهمة تعني إنقاذ حياة طفل.',
                'target_amount' => 100000.00,
                'current_amount' => 35000.00,
                'images' => ['https://via.placeholder.com/400x300/dc3545/ffffff?text=علاج+الأطفال'],
                'is_priority' => true,
                'is_active' => true,
                'end_date' => now()->addMonths(6),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'بناء مدرسة في القرية',
                'description' => 'مشروع لبناء مدرسة في إحدى القرى النائية لتوفير التعليم للأطفال. التعليم حق لكل طفل.',
                'target_amount' => 200000.00,
                'current_amount' => 75000.00,
                'images' => ['https://via.placeholder.com/400x300/007bff/ffffff?text=بناء+مدرسة'],
                'is_priority' => false,
                'is_active' => true,
                'end_date' => now()->addYear(),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'توفير المياه النظيفة',
                'description' => 'حملة لحفر آبار وتوفير المياه النظيفة للمناطق التي تعاني من نقص في المياه.',
                'target_amount' => 80000.00,
                'current_amount' => 20000.00,
                'images' => ['https://via.placeholder.com/400x300/17a2b8/ffffff?text=المياه+النظيفة'],
                'is_priority' => true,
                'is_active' => true,
                'end_date' => now()->addMonths(4),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'كفالة الأيتام',
                'description' => 'برنامج كفالة الأيتام لتوفير الرعاية والتعليم والمأوى للأطفال الأيتام.',
                'target_amount' => 150000.00,
                'current_amount' => 90000.00,
                'images' => ['https://via.placeholder.com/400x300/ffc107/ffffff?text=كفالة+الأيتام'],
                'is_priority' => false,
                'is_active' => true,
                'end_date' => null,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($campaigns as $campaignData) {
            Campaign::create($campaignData);
        }
    }
}
