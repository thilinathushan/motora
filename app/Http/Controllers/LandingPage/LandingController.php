<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $reviews = [
            [
                'description' => "This platform helped me uncover critical details about a vehicle I was planning to purchase. It saved me from making a costly mistake. Highly recommended!",
                'name' => 'Saman Perera',
                'designation' => 'Manager, Lanka Motors Pvt Ltd',
            ],
            [
                'description' => "As a service center owner, this system has streamlined how we access service records. It’s accurate, fast, and extremely helpful for our customers.",
                'name' => 'Kavindi Fernando',
                'designation' => 'Owner, AutoCare Solutions',
            ],
            [
                'description' => "I used this system to verify the history of my fleet vehicles. It has improved our maintenance scheduling and reduced unexpected breakdowns.",
                'name' => 'Anura Jayasinghe',
                'designation' => 'Operations Manager, Ceylon Transport Services',
            ],
            [
                'description' => "This platform is a game-changer! Its predictive maintenance insights have saved me time and money. I wouldn’t trust any other service.",
                'name' => 'Tharushi Wickramasinghe',
                'designation' => 'Vehicle Owner',
            ],
            [
                'description' => "This system has revolutionized the way we manage our fleet. The predictive maintenance feature has saved us both time and money by identifying issues before they become serious.",
                'name' => 'Chamara Perera',
                'designation' => 'Operations Manager, Ceylon Fleet Solutions',
            ],
            [
                'description' => "As a vehicle owner, having access to detailed service records and maintenance predictions has been invaluable. It's a must-have tool for anyone who values their vehicle.",
                'name' => 'Anusha Jayawardena',
                'designation' => 'Vehicle Owner',
            ],

        ];
        return view('pages.landing_page.welcome', compact('reviews'));
    }
}
