{{--
    Hub hero — full-bleed image hero for content section landing & category pages.

    Props:
      type       — blog | guide | kb | faq | checklist (preset copy & imagery)
      eyebrow, title, subtitle, imageSrc, eyebrowIcon — override presets
      minHeight, variant — passed through to hero.image
--}}
@props([
    'type' => 'blog',
    'eyebrow' => null,
    'title' => null,
    'subtitle' => null,
    'imageSrc' => null,
    'eyebrowIcon' => null,
    'minHeight' => '480px',
    'variant' => 'center',
])

@php
    $presets = [
        'blog' => [
            'eyebrow' => 'Waggies Blog',
            'icon' => 'article',
            'title' => 'Pet Care Tips &amp; News',
            'subtitle' => 'Expert advice, heartwarming stories, and the latest from the Waggies team in Abuja.',
            'image' => config('waggies.hero_images.blog'),
        ],
        'guide' => [
            'eyebrow' => 'Pet Care Guides',
            'icon' => 'menu_book',
            'title' => 'In-Depth Pet Care Guides',
            'subtitle' => 'Comprehensive, expert-written guides to help you make the best decisions for your pet.',
            'image' => config('waggies.hero_images.guide'),
        ],
        'kb' => [
            'eyebrow' => 'Knowledge Base',
            'icon' => 'help',
            'title' => 'How Can We Help?',
            'subtitle' => 'Quick answers to common questions about our services, policies, and pet care.',
            'image' => config('waggies.hero_images.kb'),
        ],
        'faq' => [
            'eyebrow' => 'Help Centre',
            'icon' => 'help',
            'title' => 'Frequently Asked Questions',
            'subtitle' => 'Quick answers to the questions we hear most often. Can\'t find what you need? Contact us directly.',
            'image' => config('waggies.hero_images.faq'),
        ],
        'checklist' => [
            'eyebrow' => 'Relocation Checklist',
            'icon' => 'checklist',
            'title' => 'Your Pet Relocation Checklist',
            'subtitle' => 'Stay on top of every requirement. Start early — some steps can take weeks to complete.',
            'image' => config('waggies.hero_images.checklist'),
        ],
    ];

    $preset = $presets[$type] ?? $presets['blog'];
@endphp

<x-hero.image
    :image-src="$imageSrc ?? $preset['image']"
    :eyebrow="$eyebrow ?? $preset['eyebrow']"
    :eyebrow-icon="$eyebrowIcon ?? $preset['icon']"
    :title="$title ?? $preset['title']"
    :subtitle="$subtitle ?? $preset['subtitle']"
    :min-height="$minHeight"
    :variant="$variant"
    {{ $attributes }}
/>
