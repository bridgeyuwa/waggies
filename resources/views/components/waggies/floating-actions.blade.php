<div
    x-data="waggiesChat"
    class="fixed bottom-20 right-4 z-layer-sticky flex flex-col items-end gap-3 sm:bottom-6 sm:right-6 lg:bottom-6"
>
    <div
        x-cloak
        x-show="chatOpen"
        x-transition
        x-ref="chatPanel"
        id="waggies-chat-dialog"
        class="waggies-chat-panel fixed z-layer-floating-panel flex min-w-0 flex-col overflow-hidden rounded-2xl border border-primary/10 bg-white shadow-xl"
        role="dialog"
        aria-modal="true"
        :aria-hidden="(!chatOpen).toString()"
        aria-labelledby="waggies-chat-title"
        aria-describedby="waggies-chat-description"
        tabindex="-1"
        @keydown="handleChatKeydown($event)"
    >
        <div class="flex shrink-0 items-center justify-between bg-primary px-5 py-4 text-white">
            <div class="flex min-w-0 items-center gap-3">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/20">
                    <x-waggies.icon name="assistant" size="18" class="text-white" />
                </div>
                <div class="min-w-0">
                    <h3 id="waggies-chat-title" class="text-sm font-semibold text-white">Waggies AI Assistant</h3>
                    <p id="waggies-chat-status" class="text-xs text-white/70">Usually replies in a few seconds</p>
                </div>
            </div>
            <div class="flex shrink-0 items-center gap-1">
                <button
                    x-cloak
                    x-show="messages.length > 1"
                    type="button"
                    class="min-h-11 rounded-lg px-2 text-xs font-semibold text-white/80 transition-colors hover:bg-white/10 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white"
                    @click="resetChat()"
                >
                    New chat
                </button>
                <button
                    type="button"
                    aria-label="Close chat"
                    class="flex min-h-11 min-w-11 items-center justify-center rounded-lg transition-colors hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white"
                    @click="closeChat()"
                >
                    <x-waggies.icon name="close" size="18" class="text-white" />
                </button>
            </div>
        </div>

        <p id="waggies-chat-description" class="sr-only">
            Ask about Waggies services, bookings, opening hours, prices, and general pet-care information. Do not share sensitive personal information. For emergencies or diagnosis, contact a veterinarian directly.
        </p>
        <p class="sr-only" role="status" aria-live="polite" aria-atomic="true" x-text="announcement"></p>

        <div
            x-ref="messages"
            class="relative flex min-h-0 min-w-0 flex-1 flex-col space-y-4 overflow-y-auto overscroll-contain px-4 py-4"
            role="log"
            aria-live="off"
            aria-relevant="additions"
            aria-label="Chat messages"
            @scroll="handleMessagesScroll()"
        >
            <template x-for="(message, index) in messages" :key="index">
                <div class="flex min-w-0" :class="message.role === 'user' ? 'justify-end' : 'justify-start'">
                    <div class="min-w-0 max-w-[80%]">
                        <p
                            x-show="message.text"
                            class="break-words rounded-2xl px-4 py-2.5 text-sm leading-relaxed"
                            :class="message.role === 'user' ? 'rounded-br-md bg-primary text-white' : 'rounded-bl-md bg-surface-purple text-primary-dark'"
                            x-text="message.text"
                        ></p>

                        <div x-show="message.error" class="mt-2 flex flex-wrap gap-2">
                            <button
                                type="button"
                                class="rounded-full border border-primary/15 px-3 py-1.5 text-xs font-semibold text-primary transition-colors hover:bg-surface-purple focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40"
                                @click="retry(message.retryText)"
                            >
                                Try again
                            </button>
                            <a
                                href="{{ $businessProfile->whatsapp_url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="rounded-full border border-whatsapp/30 px-3 py-1.5 text-xs font-semibold text-whatsapp transition-colors hover:bg-whatsapp/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-whatsapp/40"
                            >
                                Continue on WhatsApp
                            </a>
                        </div>

                        <div x-show="hasSources(message)" class="mt-2 flex flex-wrap items-center gap-1.5 text-xs text-primary-dark/60">
                            <span>Sources:</span>
                            <template x-for="(source, sourceIndex) in message.sources" :key="sourceIndex">
                                <a
                                    x-show="sourceUrl(source)"
                                    :href="sourceUrl(source)"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="font-semibold text-primary underline decoration-primary/30 underline-offset-2 transition-colors hover:text-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40"
                                    x-text="sourceLabel(source)"
                                ></a>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

            <div x-show="messages.length === 1 && !sending" class="flex shrink-0 flex-col gap-2">
                <p class="text-xs font-semibold text-primary-dark/60">Try asking:</p>
                <div class="flex flex-wrap gap-2">
                    <template x-for="suggestion in suggestions" :key="suggestion">
                        <button
                            type="button"
                            class="rounded-full border border-primary/15 px-3 py-2 text-left text-xs font-semibold text-primary transition-colors hover:bg-surface-purple focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40"
                            @click="sendSuggestion(suggestion)"
                            x-text="suggestion"
                        ></button>
                    </template>
                </div>
            </div>

            <p
                x-show="sending"
                class="shrink-0 text-xs text-primary-dark/60"
                role="status"
                aria-live="polite"
                aria-atomic="true"
            >
                Waggies is checking its approved information…
            </p>
        </div>

        <button
            x-cloak
            x-show="newReplyAvailable"
            x-transition
            type="button"
            class="absolute bottom-20 left-1/2 z-10 -translate-x-1/2 rounded-full bg-primary px-3 py-2 text-xs font-semibold text-white shadow-lg transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40"
            @click="scrollToLatest(true)"
        >
            New reply · Jump to latest
        </button>

        <div class="shrink-0 border-t border-primary/5">
            <p class="px-4 pt-2 text-[11px] leading-4 text-primary-dark/50">
                Please don’t share sensitive information. For emergencies, contact a veterinarian directly.
            </p>
            <form
                class="flex items-center gap-2 px-4 py-3 pb-[max(0.75rem,env(safe-area-inset-bottom))]"
                @submit.prevent="send()"
            >
                <label class="sr-only" for="chat-input">Chat message</label>
                <input
                    id="chat-input"
                    x-ref="chatInput"
                    x-model="input"
                    type="text"
                    maxlength="1200"
                    autocomplete="off"
                    aria-describedby="waggies-chat-input-hint"
                    :aria-busy="sending"
                    placeholder="Type a message..."
                    class="min-w-0 flex-1 rounded-full border border-primary/10 bg-surface px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
                >
                <span id="waggies-chat-input-hint" class="sr-only">Press Enter to send your message.</span>
                <button
                    type="submit"
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-primary text-white transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 disabled:cursor-not-allowed disabled:opacity-40"
                    aria-label="Send message"
                    :disabled="!input.trim() || sending"
                >
                    <x-waggies.icon name="send" size="18" class="text-white" />
                </button>
            </form>
        </div>
    </div>

    <div x-show="!chatOpen && showTop" x-transition class="waggies-fab--back-to-top fixed z-layer-floating group">
        <button
            type="button"
            aria-label="Back to top"
            class="flex h-11 w-11 items-center justify-center rounded-full bg-primary text-white shadow-lg transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-white"
            @click="window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' })"
        >
            <x-waggies.icon name="arrow-up" size="20" class="text-white" />
        </button>
        <div aria-hidden="true" class="pointer-events-none absolute bottom-full right-0 mb-2 opacity-0 transition-opacity duration-200 group-hover:opacity-100 group-focus-within:opacity-100">
            <div class="whitespace-nowrap rounded-full bg-primary-dark px-3 py-1.5 text-xs font-semibold text-white shadow-md">Back to top</div>
        </div>
    </div>

    <div x-show="!chatOpen" x-transition class="waggies-fab--whatsapp fixed z-layer-floating group">
        <a
            href="{{ $businessProfile->whatsapp_url }}"
            target="_blank"
            rel="noopener noreferrer"
            class="block flex h-14 w-14 items-center justify-center rounded-full bg-whatsapp text-whatsapp-foreground shadow-2xl transition duration-200 hover:scale-110 hover:ring-4 hover:ring-white focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-white"
            aria-label="Chat on WhatsApp"
        >
            <x-waggies.brand-icon name="whatsapp" size="24" class="text-whatsapp-foreground" />
        </a>
        <div aria-hidden="true" class="pointer-events-none absolute bottom-full right-0 mb-2 opacity-0 transition-opacity duration-200 group-hover:opacity-100 group-focus-within:opacity-100">
            <div class="whitespace-nowrap rounded-full bg-primary-dark px-3 py-1.5 text-xs font-semibold text-white shadow-md">Chat on WhatsApp</div>
        </div>
    </div>

    <div x-show="!chatOpen" x-transition class="waggies-fab--assistant fixed z-layer-floating group">
        <button
            type="button"
            class="flex h-14 w-14 items-center justify-center rounded-full bg-primary text-white shadow-2xl transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-white"
            aria-label="Open Waggies AI Assistant"
            aria-controls="waggies-chat-dialog"
            :aria-expanded="chatOpen"
            @click="openChat()"
        >
            <x-waggies.icon name="assistant" size="24" class="text-white" />
        </button>
        <div aria-hidden="true" class="pointer-events-none absolute bottom-full right-0 mb-2 opacity-0 transition-opacity duration-200 group-hover:opacity-100 group-focus-within:opacity-100">
            <div class="whitespace-nowrap rounded-full bg-primary-dark px-3 py-1.5 text-xs font-semibold text-white shadow-md">Open Waggies AI Assistant</div>
        </div>
    </div>
</div>
