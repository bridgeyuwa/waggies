<?php

namespace Tests\Feature;

use App\Models\JobOpening;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AboutPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_child_pages_render_with_page_metadata(): void
    {
        $pages = [
            ['uri' => '/about/testimonials', 'title' => 'Client Testimonials'],
            ['uri' => '/about/gallery', 'title' => 'Photo Gallery'],
            ['uri' => '/about/careers', 'title' => 'Careers at Waggies'],
            ['uri' => '/about/partnerships', 'title' => 'Partnerships'],
        ];

        foreach ($pages as $page) {
            $this->get($page['uri'])
                ->assertOk()
                ->assertSee('<title>'.$page['title'].'</title>', false)
                ->assertSee('application/ld+json', false);
        }
    }

    public function test_careers_lists_only_current_open_roles_and_uses_the_contact_handoff(): void
    {
        JobOpening::factory()->create([
            'title' => 'Current Care Role',
            'department' => 'Veterinary Care',
            'location' => 'Life Camp, Abuja',
            'employment_type' => 'Full-time',
            'summary' => 'Support the veterinary team with compassionate patient care.',
            'description' => 'Support the veterinary team with compassionate, organised day-to-day patient care.',
            'requirements' => 'Experience working with animals and a calm, organised approach.',
            'status' => JobOpening::STATUS_OPEN,
            'published_at' => now(),
        ]);
        JobOpening::factory()->create([
            'title' => 'Draft Care Role',
            'status' => JobOpening::STATUS_DRAFT,
        ]);
        JobOpening::factory()->create([
            'title' => 'Future Care Role',
            'status' => JobOpening::STATUS_OPEN,
            'published_at' => now()->addDay(),
        ]);

        $this->get('/about/careers')
            ->assertOk()
            ->assertSee('Current Care Role')
            ->assertSee('Veterinary Care')
            ->assertSee('Full-time')
            ->assertSee('Life Camp, Abuja')
            ->assertSee('Support the veterinary team with compassionate patient care.')
            ->assertSee('Support the veterinary team with compassionate, organised day-to-day patient care.')
            ->assertSee('Experience working with animals and a calm, organised approach.')
            ->assertSee('View role details')
            ->assertSee('About the role')
            ->assertSee('Requirements')
            ->assertDontSee('Draft Care Role')
            ->assertDontSee('Future Care Role')
            ->assertSee('intent=careers', false)
            ->assertSee('source=job-opening', false)
            ->assertDontSee('job=', false);
    }

    public function test_careers_empty_state_keeps_the_careers_contact_path(): void
    {
        $this->get('/about/careers')
            ->assertOk()
            ->assertSee('No current openings')
            ->assertSee('Contact Waggies')
            ->assertSee('intent=careers', false)
            ->assertSee('source=careers-page', false);
    }
}
