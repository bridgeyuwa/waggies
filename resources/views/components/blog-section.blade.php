{{--
    Blog Section Component
    Usage (minimal – uses default posts):
        <x-blog-section />

    Usage (custom data):
        <x-blog-section
            label="From the Blog"
            heading="Pet Care Tips & News"
            subheading="Expert advice, heartwarming stories, and the latest from Waggies HQ."
            view-all-url="/blog"
            :posts="$posts"
        />

    $posts is an array of post arrays, each with:
        image, category, categoryStyle ('primary'|'secondary'),
        readTime, title, excerpt,
        authorInitial, authorName, authorDate, url
    The first post in the array is automatically treated as "featured".
--}}

@props([
    'label'      => 'From the Blog',
    'heading'    => 'Pet Care Tips & News',
    'subheading' => 'Expert advice, heartwarming stories, and the latest from Waggies HQ.',
    'viewAllUrl' => '#',
    'posts'      => [
        [
            'image'         => 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=600&h=400&fit=crop&q=80',
            'category'      => 'Boarding',
            'categoryStyle' => 'primary',
            'readTime'      => '5 min read',
            'title'         => "10 Things to Pack for Your Dog's First Boarding Stay",
            'excerpt'       => "First time leaving your pet with us? We've put together the definitive packing list so your dog feels right at home from day one.",
            'authorInitial' => 'A',
            'authorName'    => 'Amaka Eze',
            'authorDate'    => 'Apr 2026',
            'url'           => '#',
        ],
        [
            'image'         => 'https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?w=600&h=400&fit=crop&q=80',
            'category'      => 'Grooming',
            'categoryStyle' => 'primary',
            'readTime'      => '3 min read',
            'title'         => 'How Often Should You Groom Your Cat?',
            'excerpt'       => "Cats are self-groomers, but that doesn't mean a professional spa session is off the table.",
            'authorInitial' => 'T',
            'authorName'    => 'Temi Adeyemi',
            'authorDate'    => 'Mar 2026',
            'url'           => '#',
        ],
        [
            'image'         => 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=600&h=400&fit=crop&q=80',
            'category'      => 'Nutrition',
            'categoryStyle' => 'secondary',
            'readTime'      => '4 min read',
            'title'         => 'The Best Foods for Senior Dogs in Nigeria',
            'excerpt'       => 'As dogs age, their dietary needs change. Our vet nutritionist breaks down what to feed your older companion.',
            'authorInitial' => 'K',
            'authorName'    => 'Dr. Kemi Bello',
            'authorDate'    => 'Mar 2026',
            'url'           => '#',
        ],
    ],
])

<section id="blog-section" class="py-20 bg-surface-purple">
    <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">

        {{-- Section header --}}
        <div class="flex items-end justify-between mb-10">
            <div>
                <span class="text-primary font-bold tracking-widest uppercase text-xs mb-2 block">
                    {{ $label }}
                </span>
                <h2 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark leading-tight">
                    {{ $heading }}
                </h2>
                <p class="text-primary-dark/50 mt-2 text-sm max-w-md">
                    {{ $subheading }}
                </p>
            </div>

            {{-- Desktop "View all" --}}
            <a
                href="{{ $viewAllUrl }}"
                class="hidden sm:inline-flex items-center gap-1.5 text-primary font-semibold text-sm uppercase tracking-wide hover:text-primary-light transition-colors shrink-0 ml-6"
            >
                View all posts
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>
        </div>

        {{-- Cards grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($posts as $index => $post)
                <x-card.blog
                    :post="$post"
                    :featured="$index === 0"
                />
            @endforeach
        </div>

        {{-- Mobile "View all" --}}
        <div class="mt-8 text-center sm:hidden">
            <a
                href="{{ $viewAllUrl }}"
                class="inline-flex items-center gap-2 border border-primary text-primary px-7 py-3 rounded-full font-semibold text-sm hover:bg-primary hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2"
            >
                View all posts
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>
        </div>

    </div>
</section>
