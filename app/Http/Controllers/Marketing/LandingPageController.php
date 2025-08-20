<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $settings = Setting::first();
        
        return view('marketing.home', [
            'title' => 'Welcome to Fotospeed',
            'description' => 'Capture your memories with our custom photo books.',
            'page' => 'home',
            'contact' => [
                'email' => $settings->org_email,
                'phone' => $settings->org_phone,
            ],
            'faqs' => [
                [
                    'question' => 'What is Fotospeed?',
                    'answer' => 'Fotospeed is a service that allows you to create custom photo books to preserve your memories.',
                ],
                [
                    'question' => 'How do I order a photo book?',
                    'answer' => 'You can place an order online, in-person at one of our branches, or by contacting our customer service team.',
                ],
                [
                    'question' => 'What payment methods do you accept?',
                    'answer' => 'We accept various payment methods, including cash, and online payment.',
                ],
                [
                    'question' => 'What are your business hours?',
                    'answer' => 'Our business hours are Monday to Friday from 9 AM to 5 PM',
                ],
                [
                    'question' => 'What is your delivery policy?',
                    'answer' => 'We offer delivery services within Lagos and nationwide.',
                ],
            ],
            'team' => [
                [
                    'id' => 1,
                    'name' => 'Antony Issac',
                    'role' => 'Founder & CEO',
                    'image' => 'letest-team-img2.jpg',
                    'socials' => [
                        'facebook' => 'https://www.facebook.com/antony.issac',
                        'twitter' => 'https://twitter.com/antony_issac',
                        'instagram' => 'https://www.instagram.com/antony_issac',
                    ],
                ],
                [
                    'id' => 2,
                    'name' => 'Jane Doe',
                    'role' => 'Creative Director',
                    'image' => 'letest-team-img2.jpg',
                    'socials' => [
                        'facebook' => 'https://www.facebook.com/jane.doe',
                        'twitter' => 'https://twitter.com/jane_doe',
                        'instagram' => 'https://www.instagram.com/jane_doe',
                    ],
                ],
                [
                    'id' => 3,
                    'name' => 'John Smith',
                    'role' => 'Marketing Manager',
                    'image' => 'letest-team-img2.jpg',
                    'socials' => [
                        'facebook' => 'https://www.facebook.com/john.smith',
                        'twitter' => 'https://twitter.com/john_smith',
                        'instagram' => 'https://www.instagram.com/john_smith',
                    ],
                ],
            ],
            'testimonials' => [
                [
                    'name' => 'Alice Johnson',
                    'role' => 'Photographer',
                    'organization' => 'Organization Name',
                    'feedback' => 'Fotospeed helped me create a beautiful album for my wedding. Highly recommend!',
                    'image' => '01.png',
                ],
                [
                    'name' => 'Bob Brown',
                    'role' => 'CEO',
                    'organization' => 'Agency Name',
                    'feedback' => 'Great service and quality! My family loved the photo book I created.',
                    'image' => '01.png',
                ],
                [
                    'name' => 'Charlie Davis',
                    'role' => 'Customer',
                    'organization' => '',
                    'feedback' => 'Fast delivery and excellent customer support. Will order again!',
                    'image' => '01.png',
                ],
            ],
            'kpis' => [
                'total_orders' => 36200,
                'happy_customers' => 1200,
                'branches' => 6,
                'team_members' => 20,
                'experience' => 11,
                'projects_completed' => 300,
            ],
            'features' => [
                [
                    'title' => 'High Quality Prints',
                    'description' => 'We use the best printing technology to ensure your photos look stunning.',
                    'icon' => 'feature-img1.svg',
                    'link' => '',
                ],
                [
                    'title' => 'Nation-wide Delivery',
                    'description' => 'Get your photo books delivered nationwide quickly and safely.',
                    'icon' => 'service3.svg',
                    'link' => '',
                ],
                [
                    'title' => 'Best Online Support',
                    'description' => 'Available from 9am - 5pm Mon - Sat to assist you on call and on Whatsapp.',
                    'icon' => 'feature-img3.svg',
                    'link' => '',
                ],
            ],
        ]);
    }
}
