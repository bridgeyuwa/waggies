@props(['headings', 'mobile' => false])

@if(count($headings) > 0)
    @if($mobile)
        <details x-data="waggiesArticleToc" class="mb-8 rounded-2xl border border-primary/10 bg-white px-4 py-3 lg:hidden">
            <summary class="cursor-pointer list-none py-2 font-semibold text-primary-dark marker:hidden [&::-webkit-details-marker]:hidden">On this page</summary>
            <nav aria-label="Table of contents" class="pb-2 pt-2">
                <ul class="space-y-1">
                    @foreach($headings as $heading)
                        <li>
                            <a href="#{{ $heading['id'] }}" @click="setActive('{{ $heading['id'] }}')" class="block border-l-2 py-1 pl-3 text-sm leading-snug transition-[border-color,color] duration-180 {{ $heading['level'] === 3 ? 'pl-5 text-xs' : '' }} border-transparent text-text-muted hover:border-primary/30 hover:text-primary-dark" data-toc-link="{{ $heading['id'] }}">{{ $heading['text'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </details>
    @else
        <aside x-data="waggiesArticleToc" class="hidden lg:block" aria-label="Article navigation">
            <div class="sticky top-28">
                <h3 class="text-label mb-4 text-primary">On this page</h3>
                <nav aria-label="Table of contents">
                    <ul class="space-y-2">
                        @foreach($headings as $heading)
                            <li>
                                <a href="#{{ $heading['id'] }}" @click="setActive('{{ $heading['id'] }}')" class="block border-l-2 py-1 pl-3 text-sm leading-snug transition-[border-color,color] duration-180 {{ $heading['level'] === 3 ? 'pl-5 text-xs' : '' }} {{ $loop->first ? 'border-primary font-semibold text-primary' : 'border-transparent text-text-muted hover:border-primary/30 hover:text-primary-dark' }}" data-toc-link="{{ $heading['id'] }}" @if($loop->first) aria-current="location" @endif>{{ $heading['text'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>
        </aside>
    @endif
@endif
