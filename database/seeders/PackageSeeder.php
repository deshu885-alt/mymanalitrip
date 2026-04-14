<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name'         => 'Budget Manali Volvo Package',
                'type'         => 'budget',
                'duration'     => '3 Nights / 4 Days',
                'nights'       => 3, 'days' => 4,
                'price'        => 6999,
                'price_label'  => 'per person',
                'is_bestseller'=> true, 'is_featured' => true,
                'sort_order'   => 1,
                'highlights'   => ['Volvo Bus Included', 'Solang Valley', 'Local Sightseeing', 'Meals Included'],
                'inclusions'   => ['Delhi ⇄ Manali Volvo tickets', '3 Nights hotel stay', 'Breakfast + Dinner', 'Local sightseeing', 'Solang Valley visit'],
                'exclusions'   => ['Personal expenses', 'Adventure activities', 'GST extra'],
                'places_covered'=> ['Solang Valley', 'Hadimba Temple', 'Mall Road', 'Old Manali'],
                'itinerary'    => json_encode([
                    ['day' => 1, 'title' => 'Overnight Volvo Delhi → Manali', 'description' => 'Board the luxury Volvo bus from Kashmere Gate, Delhi. Overnight journey through the hills.'],
                    ['day' => 2, 'title' => 'Arrival + Local Sightseeing',   'description' => 'Arrive Manali morning. Check-in hotel. Visit Hadimba Temple, Vashisht, Mall Road.'],
                    ['day' => 3, 'title' => 'Solang Valley Adventure Day',   'description' => 'Full day at Solang Valley. Snow activities, ATV rides, paragliding (optional, at extra cost).'],
                    ['day' => 4, 'title' => 'Checkout + Return to Delhi',    'description' => 'After breakfast, checkout. Board return Volvo to Delhi. Arrive next morning.'],
                ]),
                'seasonal_pricing' => [
                    ['months' => [12, 1], 'price' => 8999, 'note' => 'Peak winter / snowfall season'],
                    ['months' => [2, 3],  'price' => 7999, 'note' => 'Late winter'],
                    ['months' => [4, 5, 6, 7, 8, 9, 10, 11], 'price' => 6999, 'note' => 'Regular season'],
                ],
                'excerpt' => 'Best-value Manali package from Delhi via Volvo. 3 nights hotel, meals, Solang Valley & local sightseeing — all at ₹6,999.',
            ],
            [
                'name'         => 'Manali Honeymoon Special',
                'type'         => 'honeymoon',
                'duration'     => '4 Nights / 5 Days',
                'nights'       => 4, 'days' => 5,
                'price'        => 11999,
                'price_label'  => 'per couple',
                'is_featured'  => true, 'sort_order' => 2,
                'highlights'   => ['Candlelight Dinner', 'Flower Bed Decoration', 'Private Cab', '3★ Hotel'],
                'inclusions'   => ['Candlelight dinner', 'Flower bed decoration', 'Private cab sightseeing', '3★ hotel stay', 'Breakfast + Dinner'],
                'places_covered'=> ['Hadimba Temple', 'Solang Valley', 'Atal Tunnel', 'Old Manali', 'Mall Road'],
                'excerpt' => 'Romantic Manali honeymoon package for couples. Candlelight dinner, flower decoration, private cab & 3★ hotel at ₹11,999.',
            ],
            [
                'name'         => 'Shimla Manali Combo Package',
                'type'         => 'combo',
                'duration'     => '5 Nights / 6 Days',
                'nights'       => 5, 'days' => 6,
                'price'        => 12999,
                'price_label'  => 'per person',
                'is_featured'  => true, 'sort_order' => 3,
                'highlights'   => ['Kufri Snow Point', 'River Rafting', 'Solang Valley', 'Mall Road'],
                'inclusions'   => ['Delhi ⇄ Shimla ⇄ Manali ⇄ Delhi cab', '5 nights hotel', 'Breakfast + Dinner', 'All sightseeing'],
                'places_covered'=> ['Shimla', 'Kufri', 'Kullu', 'Manali', 'Solang Valley'],
                'excerpt' => 'Combine Shimla and Manali in one epic trip. Kufri snow, Kullu rafting, Solang Valley at ₹12,999.',
            ],
            [
                'name'         => 'Luxury Manali Tour Package',
                'type'         => 'luxury',
                'duration'     => '4 Nights / 5 Days',
                'nights'       => 4, 'days' => 5,
                'price'        => 18999,
                'price_label'  => 'per person',
                'is_featured'  => true, 'sort_order' => 4,
                'highlights'   => ['4★ Resort Stay', 'Private Cab from Delhi', 'Riverside Room', 'Bonfire Night'],
                'inclusions'   => ['4★ Resort stay', 'Private cab from Delhi', 'River side hotel room', 'Bonfire + music night', 'All meals'],
                'excerpt' => 'Premium Manali experience. 4★ riverside resort, private cab, bonfire nights — for those who travel in style.',
            ],
            [
                'name'         => 'Manali Family Package',
                'type'         => 'family',
                'duration'     => '4 Nights / 5 Days',
                'nights'       => 4, 'days' => 5,
                'price'        => 10499,
                'price_child'  => 6299,
                'price_label'  => 'per person',
                'is_featured'  => true, 'sort_order' => 5,
                'highlights'   => ['Family Rooms', 'Kid-Friendly', 'Comfortable Cab', 'Naggar Castle'],
                'places_covered'=> ['Hadimba Temple', 'Van Vihar', 'Solang Valley', 'Kullu', 'Naggar Castle'],
                'excerpt' => 'Perfect family Manali trip. Spacious rooms, kid-friendly sightseeing & comfortable travel at ₹10,499.',
            ],
            [
                'name'         => 'Manali Adventure Package',
                'type'         => 'adventure',
                'duration'     => '4 Nights / 5 Days',
                'nights'       => 4, 'days' => 5,
                'price'        => 12499,
                'price_label'  => 'per person',
                'is_featured'  => true, 'sort_order' => 6,
                'highlights'   => ['Paragliding', 'River Rafting', 'ATV Ride', 'Zipline'],
                'activities'   => ['Paragliding', 'River rafting', 'ATV ride', 'Zipline', 'Snow scooter'],
                'excerpt' => 'Adrenaline-packed Manali trip. Paragliding, river rafting, ATV rides, zipline — for thrill seekers.',
            ],
            [
                'name'         => 'Manali Snowfall Winter Package',
                'type'         => 'winter',
                'duration'     => '3 Nights / 4 Days',
                'nights'       => 3, 'days' => 4,
                'price'        => 8999,
                'price_label'  => 'per person',
                'is_featured'  => true, 'sort_order' => 7,
                'highlights'   => ['Snow Activities', 'Skiing', 'Bonfire Nights', 'Dec–Feb Special'],
                'excerpt' => 'Winter Manali trip during snowfall season. Snow activities, skiing, bonfire nights at ₹8,999. Best Dec–Feb.',
            ],
            [
                'name'         => 'Kasol + Manali Backpacking Trip',
                'type'         => 'backpacking',
                'duration'     => '5 Nights / 6 Days',
                'nights'       => 5, 'days' => 6,
                'price'        => 9499,
                'price_label'  => 'per person',
                'is_featured'  => false, 'sort_order' => 8,
                'highlights'   => ['Kasol Cafes', 'Manikaran Sahib', 'Old Manali Nightlife'],
                'places_covered'=> ['Kasol', 'Manikaran Sahib', 'Kheerganga', 'Manali', 'Solang Valley'],
                'excerpt' => 'Youth backpacking trip covering Kasol cafes, Manikaran, Old Manali nightlife & Solang Valley.',
            ],
            [
                'name'         => 'Manali Group Tour Package',
                'type'         => 'group',
                'duration'     => '3 Nights / 4 Days',
                'nights'       => 3, 'days' => 4,
                'price'        => 6499,
                'price_label'  => 'per person',
                'is_featured'  => false, 'sort_order' => 9,
                'highlights'   => ['Group Discounts', 'DJ Night', 'Bonfire Party'],
                'excerpt' => 'College group & friends Manali tour. DJ night, bonfire, group discounts at ₹6,499. Minimum 10 people.',
            ],
            [
                'name'         => 'Manali Extended 7-Day Trip',
                'type'         => 'extended',
                'duration'     => '6 Nights / 7 Days',
                'nights'       => 6, 'days' => 7,
                'price'        => 15999,
                'price_label'  => 'per person',
                'is_featured'  => false, 'sort_order' => 10,
                'highlights'   => ['Atal Tunnel', 'Sissu', 'Kasol', 'Naggar Castle'],
                'places_covered'=> ['Manali', 'Atal Tunnel', 'Sissu', 'Kasol', 'Kullu', 'Naggar Castle'],
                'excerpt' => 'Complete Himachal experience — Manali, Atal Tunnel, Sissu, Kasol, Kullu, Naggar Castle in 7 days.',
            ],
        ];

        foreach ($packages as $data) {
            $data['slug'] = Str::slug($data['name']);
            $data['is_active'] = true;
            $data['is_bestseller'] ??= false;
            $data['is_featured']   ??= false;
            $data['rating']        = 4.8;
            $data['reviews_count'] = rand(120, 800);
            $data['starting_city'] = 'Delhi';
            $data['departure_type']= 'daily';

            // Model casts handle JSON encoding automatically
            Package::updateOrCreate(['slug' => $data['slug']], $data);
        }

        $this->command->info('✅ 10 packages seeded successfully!');
    }
}