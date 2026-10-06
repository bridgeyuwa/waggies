@extends('layouts.app')

@php
    $cta = [
        'eyebrow' => 'TIMELINE ASSURANCE',
        'heading' => 'Planning an international move from Nigeria?',
        'body' => 'Start early. We recommend reaching out at least 4–6 weeks before travel so permits and health documents can be prepared in time.',
        'primaryLabel' => 'Request an import quote',
        'primaryHref' => route('book', ['service' => 'relocation', 'direction' => 'import']),
        'secondaryLabel' => 'View the checklist',
        'secondaryHref' => route('relocation.checklist'),
    ];
@endphp

@section('content')
    <section class="border-b border-primary/10 bg-primary-dark text-white">
        <div class="page-container py-12 sm:py-16">
            <p class="text-eyebrow text-secondary">LOCAL-ONLY DESIGN EXPLORATION</p>
            <h1 class="mt-3 max-w-3xl font-serif text-4xl font-bold leading-tight text-white sm:text-5xl">Wide CTA panels</h1>
            <p class="mt-5 max-w-2xl text-base leading-relaxed text-white/75 sm:text-lg">Compare three panel layouts with the same relocation message and real next steps. The heading accent is intentionally omitted to check that optional copy stays omitted.</p>
            <p class="mt-5 inline-flex rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.12em] text-white/75">Prototype · /__test/cta-panels</p>
        </div>
    </section>

    <section class="bg-surface py-12 sm:py-16" aria-label="CTA panel prototype">
        <div class="page-container">
            <div class="mb-8 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-eyebrow">Same content, different hierarchy</p>
                    <h2 class="mt-2 font-serif text-2xl font-bold text-primary-dark sm:text-3xl">Choose a layout to inspect</h2>
                </div>
                <p class="text-sm text-primary-dark/60">Use the picker, keys 1–3, or the arrow keys.</p>
            </div>

            <div id="stage" aria-live="polite" aria-atomic="true"></div>

            <template id="variant-centered">
                @include('pages.cta-panels.variants.centered', ['cta' => $cta])
            </template>
            <template id="variant-split">
                @include('pages.cta-panels.variants.split', ['cta' => $cta])
            </template>
            <template id="variant-compact">
                @include('pages.cta-panels.variants.compact', ['cta' => $cta])
            </template>
        </div>
    </section>

    <style>
        .proto-picker {
          position: fixed;
          bottom: 24px;
          left: 50%;
          transform: translateX(-50%);
          z-index: 2147483647;
          display: flex;
          align-items: center;
          gap: 2px;
          padding: 4px;
          border-radius: 999px;
          background: rgba(10, 10, 10, 0.82);
          -webkit-backdrop-filter: blur(12px) saturate(1.4);
          backdrop-filter: blur(12px) saturate(1.4);
          box-shadow:
            0 0 0 1px rgba(255, 255, 255, 0.08) inset,
            0 8px 24px rgba(0, 0, 0, 0.24),
            0 2px 6px rgba(0, 0, 0, 0.12);
          font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
          font-size: 13px;
          line-height: 1;
          -webkit-font-smoothing: antialiased;
          user-select: none;
          -webkit-user-select: none;
        }

        .proto-picker-highlight {
          position: absolute;
          top: 4px;
          left: 0;
          height: 28px;
          border-radius: 999px;
          background: rgba(255, 255, 255, 0.12);
          will-change: transform;
        }

        .proto-picker[data-ready] .proto-picker-highlight {
          transition:
            transform 250ms cubic-bezier(0.23, 1, 0.32, 1),
            width 250ms cubic-bezier(0.23, 1, 0.32, 1);
        }

        @media (prefers-reduced-motion: reduce) {
          .proto-picker[data-ready] .proto-picker-highlight { transition: none; }
        }

        .proto-picker-item {
          position: relative;
          display: flex;
          align-items: center;
          height: 28px;
          padding: 0 12px;
          border: 0;
          border-radius: 999px;
          background: transparent;
          color: rgba(255, 255, 255, 0.55);
          font: inherit;
          cursor: pointer;
          transition: color 150ms ease-out;
        }

        .proto-picker-item:hover {
          color: rgba(255, 255, 255, 0.85);
        }

        .proto-picker-item:active {
          transform: scale(0.97);
        }

        .proto-picker-item:focus-visible {
          outline: 2px solid rgba(255, 255, 255, 0.4);
          outline-offset: 2px;
        }

        .proto-picker-item[data-active] {
          color: #fff;
        }
    </style>

    <nav class="proto-picker" aria-label="Prototype variants">
        <span class="proto-picker-highlight" aria-hidden="true"></span>
        <button class="proto-picker-item" type="button" data-active aria-current="true">Centered</button>
        <button class="proto-picker-item" type="button">Split</button>
        <button class="proto-picker-item" type="button">Compact</button>
    </nav>

    <script>
        (() => {
            const stage = document.getElementById('stage');
            const picker = document.querySelector('.proto-picker');
            const highlight = picker.querySelector('.proto-picker-highlight');
            const items = [...picker.querySelectorAll('.proto-picker-item')];
            const templates = [
                document.getElementById('variant-centered'),
                document.getElementById('variant-split'),
                document.getElementById('variant-compact'),
            ];
            let current = 0;

            function moveHighlight() {
                const item = items[current];
                highlight.style.width = `${item.offsetWidth}px`;
                highlight.style.transform = `translateX(${item.offsetLeft}px)`;
            }

            function mount(index) {
                stage.replaceChildren(templates[index].content.cloneNode(true));
            }

            function setActive(index) {
                if (index < 0 || index >= templates.length) {
                    return;
                }

                current = index;
                items.forEach((item, itemIndex) => {
                    item.toggleAttribute('data-active', itemIndex === index);

                    if (itemIndex === index) {
                        item.setAttribute('aria-current', 'true');
                    } else {
                        item.removeAttribute('aria-current');
                    }
                });

                moveHighlight();

                const url = new URL(window.location.href);
                url.searchParams.set('v', String(index + 1));
                window.history.replaceState(null, '', url);
                mount(index);
            }

            items.forEach((item, index) => {
                item.addEventListener('click', () => setActive(index));
            });

            window.addEventListener('resize', moveHighlight);

            document.addEventListener('keydown', (event) => {
                const target = event.target;

                if (target instanceof HTMLElement && (/^(INPUT|TEXTAREA|SELECT)$/.test(target.tagName) || target.isContentEditable)) {
                    return;
                }

                if (event.metaKey || event.ctrlKey || event.altKey) {
                    return;
                }

                const number = Number.parseInt(event.key, 10);

                if (number >= 1 && number <= templates.length) {
                    setActive(number - 1);
                } else if (event.key === 'ArrowRight') {
                    setActive((current + 1) % templates.length);
                } else if (event.key === 'ArrowLeft') {
                    setActive((current - 1 + templates.length) % templates.length);
                }
            });

            const initialVariant = Number.parseInt(new URLSearchParams(window.location.search).get('v'), 10) || 1;
            setActive(initialVariant - 1);
            requestAnimationFrame(() => requestAnimationFrame(() => picker.setAttribute('data-ready', '')));
        })();
    </script>
@endsection
