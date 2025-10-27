<?php

namespace Modules\Updater\Services;

use App\Models\Navbar;
use App\Models\NavbarItem;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Setting;

class DataService
{
   public function seedDataUpdater()
   {
      $aboutUsArray = [
         'hero' => [
            'flags' => [
               'title' => true,
            ]
         ],
         'success_statistics' => [
            'flags' => [
               'title' => true,
               'description' => true,
            ]
         ],
         'team' => [
            'flags' => [
               'title' => true,
               'description' => true,
            ]
         ],
         'call_to_action' => [
            'flags' => [
               'title' => true,
               'sub_title' => true,
               'description' => true,
            ]
         ],
      ];

      $ourTeamArray = [
         'top_instructors' => [
            'flags' => [
               'title' => true,
               'description' => true,
            ]
         ],
         'partners' => [
            'flags' => [
               'title' => true,
            ]
         ]
      ];

      $aboutUs = Page::where('type', 'inner_page')
         ->where('slug', 'about-us')
         ->with(['sections:id,page_id,slug'])
         ->first();

      $ourTeam = Page::where('type', 'inner_page')
         ->where('slug', 'our-team')
         ->with(['sections:id,page_id,slug'])
         ->first();

      foreach ($aboutUs->sections as $section) {
         if (isset($aboutUsArray[$section->slug]['flags'])) {
            $sectionModel = PageSection::find($section->id);
            $sectionModel->flags = $aboutUsArray[$section->slug]['flags'];
            $sectionModel->save();
         }
      }

      foreach ($ourTeam->sections as $section) {
         if (isset($ourTeamArray[$section->slug]['flags'])) {
            $sectionModel = PageSection::find($section->id);
            $sectionModel->flags = $ourTeamArray[$section->slug]['flags'];
            $sectionModel->save();
         }
      }

      // Create navbar item (existing functionality)
      $navbar = Navbar::where('slug', 'navbar_1')->first();

      if ($navbar) {
         NavbarItem::firstOrCreate(
            ['slug' => 'blogs'],
            [
               'navbar_id' => $navbar->id,
               'type' => 'url',
               'title' => 'Blogs',
               'value' => '/blogs/all',
            ]
         );

         NavbarItem::firstOrCreate(
            ['slug' => 'cart'],
            [
               'navbar_id' => $navbar->id,
               'type' => 'action',
               'title' => 'Cart',
            ]
         );

         NavbarItem::firstOrCreate(
            ['slug' => 'language'],
            [
               'navbar_id' => $navbar->id,
               'type' => 'action',
               'title' => 'Language',
            ]
         );
      }

      // Payment gateway settings
      $settings = [
         [
            'type' => 'payment',
            'sub_type' => 'sslcommerz',
            'title' => 'SSLCommerz Settings',
            'fields' => [
               'active' => false,
               'test_mode' => true,
               'currency' => 'BDT',
               'store_id' => '',
               'store_password' => '',
            ],
         ],
         [
            'type' => 'payment',
            'sub_type' => 'razorpay',
            'title' => 'Razorpay Settings',
            'fields' => [
               'active' => false,
               'test_mode' => true,
               'currency' => 'INR',
               'api_key' => '',
               'api_secret' => '',
            ],
         ],
      ];

      foreach ($settings as $setting) {
         Setting::firstOrCreate(
            ['sub_type' => $setting['sub_type']], // Search by sub_type
            $setting                              // Find or insert
         );
      }

      $system = Setting::where('type', 'system')->first();

      if ($system && !array_key_exists('selling_currency', $system->fields)) {
         $system->fields = array_merge($system->fields, ['selling_currency' => 'USD']);
         $system->save();
      }

      if ($system && !array_key_exists('global_style', $system->fields)) {
         $system->fields = array_merge($system->fields, ['global_style' => '']);
         $system->save();
      }

      if ($system && !array_key_exists('direction', $system->fields)) {
         $system->fields = array_merge($system->fields, ['direction' => 'none']);
         $system->save();
      }

      // Add blog section into home-4 and home-5
      $home4 = Page::where('slug', 'home-4')->first();
      if ($home4) {
         PageSection::firstOrCreate(
            ['page_id' => $home4->id, 'slug' => 'blogs'],
            [
               'page_id' => $home4->id,
               'name' => 'Blogs',
               'slug' => 'blogs',
               'title' => 'Best Rated Posts',
               'description' => 'These are the most popular courses among listen courses learners worldwide',
               'flags' => [
                  'title' => true,
                  'description' => true,
               ],
               'properties' => [
                  'contents' => [1, 2, 3, 4, 5, 6] // Example blog post IDs
               ],
            ]
         );
      }

      $home5 = Page::where('slug', 'home-5')->first();
      if ($home5) {
         PageSection::firstOrCreate(
            ['page_id' => $home5->id, 'slug' => 'blogs'],
            [
               'page_id' => $home5->id,
               'name' => 'Blogs',
               'slug' => 'blogs',
               'title' => 'Blogs',
               'sub_title' => 'Best Rated Posts',
               'description' => 'These are the most popular courses among listen courses learners worldwide',
               'flags' => [
                  'title' => true,
                  'sub_title' => true,
                  'description' => true,
               ],
               'properties' => [
                  'contents' => [1, 2, 3, 4, 5, 6] // Example blog post IDs
               ],
            ]
         );
      }
   }
}
