<?php

namespace Database\Seeders;

use App\Models\CmsTestimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (CmsTestimonial::count() === 0) {
            CmsTestimonial::create([
                'author_name'  => 'Joan F.',
                'author_title' => 'Verified Buyer',
                'content'      => 'This is a great shopping website! I\'ve ordered twice in the past and will order again in the future. Great price, great items, great customer service!',
                'avatar_image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80',
                'rating'       => 5,
                'company_name' => 'Fashion Weekly',
                'is_active'    => true,
                'sort_order'   => 1,
            ]);

            CmsTestimonial::create([
                'author_name'  => 'Mike P.',
                'author_title' => 'Regular Customer',
                'content'      => 'Always has the best prices and fastest shipping. I highly recommend this company and will continue to use them for future orders.',
                'avatar_image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
                'rating'       => 5,
                'company_name' => 'Apex Studios',
                'is_active'    => true,
                'sort_order'   => 2,
            ]);

            CmsTestimonial::create([
                'author_name'  => 'Terry Fisk',
                'author_title' => 'Business Owner',
                'content'      => 'This is a great store website. They always have what I need for corporate gifts and seasonal promotions.',
                'avatar_image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80',
                'rating'       => 5,
                'company_name' => 'Aspire Properties',
                'company_link' => 'https://example.com',
                'is_active'    => true,
                'sort_order'   => 3,
            ]);

            CmsTestimonial::create([
                'author_name'  => 'Matt Jones',
                'author_title' => 'Product Reviewer',
                'content'      => 'An outstanding e-commerce platform offering top quality items with flawless customer support.',
                'avatar_image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
                'rating'       => 5,
                'company_name' => 'Tech Pulse',
                'is_active'    => true,
                'sort_order'   => 4,
            ]);
        }
    }
}
