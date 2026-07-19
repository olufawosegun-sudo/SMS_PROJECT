<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Offer</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    
                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #1B6B3E 0%, #0F4D2A 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                                🎓 Congratulations!
                            </h1>
                            <p style="margin: 10px 0 0 0; color: #D4A843; font-size: 16px; font-weight: 600;">
                                ADMISSION OFFER
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #333333;">
                                Dear <strong>{{ $application->guardian_name }}</strong>,
                            </p>

                            <p style="margin: 0 0 20px 0; font-size: 15px; color: #555555; line-height: 1.6;">
                                We are delighted to inform you that <strong style="color: #1B6B3E;">{{ $application->first_name }} {{ $application->last_name }}</strong> 
                                has been offered admission into <strong>{{ $application->appliedClass->name }}</strong> 
                                at <strong>{{ $application->school->name }}</strong>.
                            </p>

                            {{-- Student Info Box --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8faf9; border-left: 4px solid #1B6B3E; margin: 25px 0; border-radius: 5px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 10px 0; font-size: 13px; color: #666666;">
                                            <strong style="color: #1B6B3E;">Application Number:</strong> {{ $application->application_no }}
                                        </p>
                                        <p style="margin: 0 0 10px 0; font-size: 13px; color: #666666;">
                                            <strong style="color: #1B6B3E;">Student Name:</strong> {{ $application->first_name }} {{ $application->last_name }} {{ $application->other_name }}
                                        </p>
                                        <p style="margin: 0 0 10px 0; font-size: 13px; color: #666666;">
                                            <strong style="color: #1B6B3E;">Class Offered:</strong> {{ $application->appliedClass->name }}
                                        </p>
                                        <p style="margin: 0; font-size: 13px; color: #666666;">
                                            <strong style="color: #1B6B3E;">Offer Date:</strong> {{ $offer->offer_date->format('F d, Y') }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 20px 0; font-size: 15px; color: #555555; line-height: 1.6;">
                                Please find attached the official <strong>Admission Offer Letter</strong> with complete details 
                                regarding the admission requirements, deadlines, and next steps.
                            </p>

                            {{-- Important Notice --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff9e6; border: 2px solid #D4A843; margin: 25px 0; border-radius: 5px;">
                                <tr>
                                    <td style="padding: 20px; text-align: center;">
                                        <p style="margin: 0 0 10px 0; font-size: 14px; color: #D4A843; font-weight: bold;">
                                            ⏰ IMPORTANT: DEADLINE FOR ACCEPTANCE
                                        </p>
                                        <p style="margin: 0; font-size: 16px; color: #333333; font-weight: bold;">
                                            {{ $offer->offer_date->addDays(14)->format('F d, Y') }}
                                        </p>
                                        <p style="margin: 10px 0 0 0; font-size: 13px; color: #666666;">
                                            Accept this offer within 14 days to secure your child's place
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Action Button --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/accept-offer/' . $offer->id) }}" 
                                           style="display: inline-block; padding: 15px 40px; background-color: #1B6B3E; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;">
                                            Accept Offer Now
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 20px 0 0 0; font-size: 14px; color: #555555; line-height: 1.6;">
                                If you have any questions or need assistance, please don't hesitate to contact our admissions office.
                            </p>

                            <p style="margin: 20px 0 0 0; font-size: 15px; color: #333333;">
                                Best regards,<br>
                                <strong>{{ $application->school->name }}</strong><br>
                                <span style="font-size: 13px; color: #666666;">Admissions Office</span>
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: #f8f8f8; padding: 20px 30px; text-align: center; border-top: 1px solid #e0e0e0;">
                            <p style="margin: 0 0 10px 0; font-size: 12px; color: #999999;">
                                {{ $application->school->address }}<br>
                                {{ $application->school->city }}, {{ $application->school->state }}, {{ $application->school->country }}
                            </p>
                            <p style="margin: 0 0 10px 0; font-size: 12px; color: #999999;">
                                Email: {{ $application->school->email }} | Phone: {{ $application->school->phone }}
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #bbbbbb;">
                                This is an automated email. Please do not reply directly to this message.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
