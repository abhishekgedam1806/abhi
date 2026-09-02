<?php

use Illuminate\Database\Seeder;
use App\WhatsAppTemplate;
use App\WhatsAppSetting;

class WhatsAppTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Ensure Global Settings exist
        WhatsAppSetting::getSettings();

        // 2. Seed Pre-Approved Templates
        $templates = [
            [
                'template_key' => 'job_match',
                'title' => 'New Job Match Alert',
                'category' => 'UTILITY',
                'provider_template_name' => 'job_match_alert_v1',
                'language' => 'en',
                'header_text' => '🎯 New Job Match Alert',
                'body_text' => "Hi {{name}},\n\nWe found an exciting new job matching your profile:\n\n💼 *{{job_title}}*\n🏢 *{{company}}*\n📍 *{{location}}*\n💰 *{{salary}}*\n\nClick below to view the job details and apply instantly:\n{{action_url}}",
                'footer_text' => 'SolocomDigi Job Portal • Verified Opportunities',
                'variables_schema' => ['name', 'job_title', 'company', 'location', 'salary', 'action_url'],
                'is_active' => true,
            ],
            [
                'template_key' => 'application_confirmation',
                'title' => 'Application Submitted Confirmation',
                'category' => 'UTILITY',
                'provider_template_name' => 'application_confirmation_v1',
                'language' => 'en',
                'header_text' => '✅ Application Submitted',
                'body_text' => "Hi {{name}},\n\nYour application for *{{job_title}}* at *{{company}}* has been successfully submitted.\n\nYou can track the progress of your application anytime here:\n{{action_url}}",
                'footer_text' => 'SolocomDigi Job Portal',
                'variables_schema' => ['name', 'job_title', 'company', 'action_url'],
                'is_active' => true,
            ],
            [
                'template_key' => 'job_applied',
                'title' => 'New Candidate Application Alert',
                'category' => 'UTILITY',
                'provider_template_name' => 'employer_job_applied_v1',
                'language' => 'en',
                'header_text' => '📬 New Applicant Alert',
                'body_text' => "Hello {{company_name}},\n\nA new candidate *{{candidate_name}}* has applied for your job opening:\n\n💼 *{{job_title}}*\n\nReview their resume and profile directly in your employer portal:\n{{action_url}}",
                'footer_text' => 'SolocomDigi Employer Desk',
                'variables_schema' => ['company_name', 'candidate_name', 'job_title', 'action_url'],
                'is_active' => true,
            ],
            [
                'template_key' => 'job_published',
                'title' => 'Job Posting Status Update',
                'category' => 'UTILITY',
                'provider_template_name' => 'job_status_update_v1',
                'language' => 'en',
                'header_text' => '🚀 Job Status Update',
                'body_text' => "Hello {{company_name}},\n\nYour job opening *{{job_title}}* is now *{{status}}* on SolocomDigi.\n\nManage your job opening and view applicants here:\n{{action_url}}",
                'footer_text' => 'SolocomDigi Employer Desk',
                'variables_schema' => ['company_name', 'job_title', 'status', 'action_url'],
                'is_active' => true,
            ],
            [
                'template_key' => 'candidate_match',
                'title' => 'Matching Candidate Found Alert',
                'category' => 'UTILITY',
                'provider_template_name' => 'candidate_match_alert_v1',
                'language' => 'en',
                'header_text' => '🎯 Candidate Match Alert',
                'body_text' => "Hello {{company_name}},\n\nOur smart matching system found candidate(s) matching your requirement for *{{job_title}}*.\n\nOpen your secure dashboard to review candidate qualifications:\n{{action_url}}",
                'footer_text' => 'SolocomDigi AI Match Engine',
                'variables_schema' => ['company_name', 'job_title', 'action_url'],
                'is_active' => true,
            ],
            [
                'template_key' => 'application_status',
                'title' => 'Application Status Update',
                'category' => 'UTILITY',
                'provider_template_name' => 'app_status_update_v1',
                'language' => 'en',
                'header_text' => '🔔 Application Status Update',
                'body_text' => "Hi {{name}},\n\nYour application for *{{job_title}}* at *{{company}}* has been updated to: *{{status}}*.\n\nView details and next steps here:\n{{action_url}}",
                'footer_text' => 'SolocomDigi Career Portal',
                'variables_schema' => ['name', 'job_title', 'company', 'status', 'action_url'],
                'is_active' => true,
            ],
            [
                'template_key' => 'new_message',
                'title' => 'New Portal Message Alert',
                'category' => 'UTILITY',
                'provider_template_name' => 'new_message_alert_v1',
                'language' => 'en',
                'header_text' => '💬 New Message Alert',
                'body_text' => "Hi {{name}},\n\nYou have received a new message regarding *{{title}}*:\n\n_{{message}}_\n\nLogin to your secure inbox to reply:\n{{action_url}}",
                'footer_text' => 'SolocomDigi Messaging',
                'variables_schema' => ['name', 'title', 'message', 'action_url'],
                'is_active' => true,
            ],
            [
                'template_key' => 'otp_verification',
                'title' => 'WhatsApp Verification & Security Code',
                'category' => 'AUTHENTICATION',
                'provider_template_name' => 'whatsapp_otp_verify_v1',
                'language' => 'en',
                'header_text' => '🔐 Security Verification Code',
                'body_text' => "Hi {{name}},\n\nYour SolocomDigi verification code is: *{{code}}*\n\nThis code expires in 10 minutes. Do not share this OTP with anyone.",
                'footer_text' => 'SolocomDigi Security',
                'variables_schema' => ['name', 'code', 'action_url'],
                'is_active' => true,
            ],
            [
                'template_key' => 'payment_confirmation',
                'title' => 'Payment & Package Confirmation',
                'category' => 'UTILITY',
                'provider_template_name' => 'payment_receipt_v1',
                'language' => 'en',
                'header_text' => '💳 Payment Receipt',
                'body_text' => "Hi {{name}},\n\nYour payment for package *{{package_name}}* (₹{{amount}}) has been received successfully.\n\nTransaction ID: *{{transaction_id}}*\n\nView invoice here:\n{{action_url}}",
                'footer_text' => 'SolocomDigi Billing Desk',
                'variables_schema' => ['name', 'package_name', 'amount', 'transaction_id', 'action_url'],
                'is_active' => true,
            ],
            [
                'template_key' => 'contact_approved',
                'title' => 'Contact Request Approved',
                'category' => 'UTILITY',
                'provider_template_name' => 'contact_request_approved_v1',
                'language' => 'en',
                'header_text' => '🤝 Contact Request Approved',
                'body_text' => "Hello {{company_name}},\n\nA candidate has approved your contact request for *{{job_title}}*.\n\nOpen your employer dashboard to view their approved contact details:\n{{action_url}}",
                'footer_text' => 'SolocomDigi Permission System',
                'variables_schema' => ['company_name', 'job_title', 'action_url'],
                'is_active' => true,
            ],
        ];

        foreach ($templates as $tmpl) {
            WhatsAppTemplate::updateOrCreate(
                ['template_key' => $tmpl['template_key']],
                $tmpl
            );
        }
    }
}
