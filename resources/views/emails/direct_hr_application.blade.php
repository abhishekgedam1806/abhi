<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Job Application</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #F8FAFC; margin: 0; padding: 20px; color: #1E293B; }
        .email-container { max-width: 600px; margin: 0 auto; background: #FFFFFF; border-radius: 12px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .email-header { background: linear-gradient(135deg, #1E40AF, #2563EB); color: #FFFFFF; padding: 28px 24px; text-align: center; }
        .email-body { padding: 28px 24px; }
        .candidate-card { background: #F1F5F9; border-radius: 10px; padding: 18px 20px; margin: 20px 0; border: 1px solid #CBD5E1; }
        .candidate-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
        .highlight-badge { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; font-size: 12px; font-weight: bold; padding: 4px 10px; border-radius: 20px; display: inline-block; }
        .footer { background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 18px; text-align: center; font-size: 12px; color: #64748B; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h2 style="margin: 0; font-size: 22px; font-weight: 800;">New Job Application</h2>
            <p style="margin: 6px 0 0 0; font-size: 14px; opacity: 0.9;">via {{ $siteName }}</p>
        </div>

        <div class="email-body">
            <p style="font-size: 15px; margin-top: 0;">Dear Hiring Team,</p>
            
            <p style="font-size: 14.5px; line-height: 1.6; color: #334155;">
                A candidate has applied for your job opening <strong>"{{ $job->title }}"</strong> on <strong>{{ $siteName }}</strong>.
            </p>

            <div class="candidate-card">
                <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #0F172A; display: flex; align-items: center; justify-content: space-between;">
                    <span>{{ $user->getName() }}</span>
                    <span class="highlight-badge">Verified Applicant</span>
                </h3>
                
                <table style="width: 100%; font-size: 13.5px; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 4px 0; color: #64748B; width: 35%;"><strong>Email:</strong></td>
                        <td style="padding: 4px 0; color: #0F172A;"><a href="mailto:{{ $user->email }}" style="color: #2563EB;">{{ $user->email }}</a></td>
                    </tr>
                    @if(!empty($user->phone))
                    <tr>
                        <td style="padding: 4px 0; color: #64748B;"><strong>Phone:</strong></td>
                        <td style="padding: 4px 0; color: #0F172A;">{{ $user->phone }}</td>
                    </tr>
                    @endif
                    @if(!empty($user->getLocation()))
                    <tr>
                        <td style="padding: 4px 0; color: #64748B;"><strong>Location:</strong></td>
                        <td style="padding: 4px 0; color: #0F172A;">{{ $user->getLocation() }}</td>
                    </tr>
                    @endif
                    @if(!empty($user->getJobExperience('job_experience')))
                    <tr>
                        <td style="padding: 4px 0; color: #64748B;"><strong>Experience:</strong></td>
                        <td style="padding: 4px 0; color: #0F172A;">{{ $user->getJobExperience('job_experience') }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            @if(!empty($coverNote))
                <div style="background: #FFFBEB; border-left: 4px solid #F59E0B; padding: 12px 16px; border-radius: 4px; margin: 16px 0; font-size: 13.5px; color: #92400E;">
                    <strong>Candidate's Note:</strong><br>
                    {{ $coverNote }}
                </div>
            @endif

            <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 14px 16px; margin-top: 20px;">
                <p style="margin: 0; font-size: 13.5px; color: #1E40AF;">
                    📎 <strong>Candidate's Resume is attached to this email.</strong> You can also reply directly to this email to contact <strong>{{ $user->getName() }}</strong> (<a href="mailto:{{ $user->email }}">{{ $user->email }}</a>).
                </p>
            </div>

            <div style="text-align: center; margin-top: 26px;">
                <a href="{{ route('job.detail', $job->slug) }}" style="display: inline-block; background: #2563EB; color: #FFFFFF; text-decoration: none; font-size: 14px; font-weight: bold; padding: 11px 22px; border-radius: 8px;">
                    View Job Opening on {{ $siteName }}
                </a>
            </div>
        </div>

        <div class="footer">
            This application was submitted via <strong>{{ $siteName }}</strong>. You are receiving this because your opening was published for hiring.
        </div>
    </div>
</body>
</html>
