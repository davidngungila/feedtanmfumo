<?php

namespace Database\Seeders;

use App\Models\SmsMessageTemplate;
use Illuminate\Database\Seeder;

class SmsMessageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'template_name' => 'Inconsistent Reminder',
                'behavior_type' => 'Inconsistent Saver',
                'message_content' => 'Hi {{name}}, hongera kuanza kujiwekea akiba! Ongeza juhudi.Tuma: CRDB 0133608488501, NMB 40310094758, Mix 7232845, Halotel 621527, Mix 837603',
                'language' => 'sw',
                'priority' => 1,
                'variables' => ['name', 'amount', 'organization_name'],
            ],
            [
                'template_name' => 'Sporadic Reminder',
                'behavior_type' => 'Sporadic Saver',
                'message_content' => 'Hi {{name}}, hongera kuanza kujiwekea akiba! Ongeza juhudi.Tuma: CRDB 0133608488501, NMB 40310094758, Mix 7232845, Halotel 621527, Mix 837603',
                'language' => 'sw',
                'priority' => 2,
                'variables' => ['name'],
            ],
            [
                'template_name' => 'Non-Saver Reminder',
                'behavior_type' => 'Non-Saver',
                'message_content' => 'Hey {{name}}! Ndoto yako inaweza timia. Jinyime bando leo,weka akiba Sh. 2000 tu: CRDB 0133608488501, NMB 40310094758, Tigo 7232845, Halotel 621527, Mix 837603',
                'language' => 'sw',
                'priority' => 3,
                'variables' => ['name', 'organization_name'],
            ],
        ];

        foreach ($templates as $template) {
            SmsMessageTemplate::updateOrCreate(
                [
                    'template_name' => $template['template_name'],
                    'behavior_type' => $template['behavior_type'],
                ],
                [
                    'message_content' => $template['message_content'],
                    'language' => $template['language'],
                    'priority' => $template['priority'],
                    'variables' => $template['variables'],
                    'last_modified' => now(),
                ]
            );
        }
    }
}
