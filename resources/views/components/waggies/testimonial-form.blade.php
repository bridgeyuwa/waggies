<div x-data="testimonialForm(@js(route('testimonials.store')))" class="relative">
    <div class="overflow-hidden rounded-3xl border border-surface-purple/60 bg-white shadow-[0_20px_60px_-20px_rgba(107,44,145,0.35)]">
        <div class="bg-primary px-6 py-5 sm:px-10">
            <div class="flex items-center justify-between gap-4" aria-label="Testimonial submission progress">
                <template x-for="(label, index) in ['Experience', 'About You', 'Consent & Submit']" :key="label">
                    <div class="flex flex-1 items-center gap-3 last:flex-none">
                        <div class="flex flex-col items-center gap-1.5">
                            <div class="relative grid size-9 place-items-center rounded-full text-sm font-bold transition-colors duration-300" :class="stepIndex === index ? 'bg-white text-primary' : stepIndex > index ? 'bg-white/25 text-white' : 'bg-white/10 text-white/70'">
                                <template x-if="stepIndex > index"><x-waggies.icon name="check" size="16" aria-hidden="true" /></template>
                                <template x-if="stepIndex <= index"><span x-text="index + 1"></span></template>
                                <span x-show="stepIndex === index" class="absolute -inset-1 rounded-full border-2 border-white/80" aria-hidden="true"></span>
                            </div>
                            <span class="text-[10px] font-semibold uppercase tracking-wide sm:text-xs" :class="stepIndex === index ? 'text-white' : stepIndex > index ? 'text-white/80' : 'text-white/60'" x-text="label"></span>
                        </div>
                        <div x-show="index < 2" class="hidden h-0.5 flex-1 overflow-hidden rounded-full bg-white/20 sm:block">
                            <div class="h-full bg-white transition-[width] duration-300" :class="stepIndex > index ? 'w-full' : 'w-0'"></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="relative min-h-112 px-6 py-8 sm:px-10 sm:py-10">
            <div x-show="submitted" x-cloak tabindex="-1" class="flex flex-col items-center justify-center py-10 text-center" role="status" aria-live="polite">
                <svg width="64" height="64" viewBox="0 0 64 64" fill="none" aria-hidden="true"><circle cx="32" cy="32" r="28" stroke="var(--color-primary)" stroke-width="3" fill="var(--color-surface-purple)"/><path d="M20 33 L28 41 L44 24" stroke="var(--color-primary)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <h3 class="mt-4 font-serif text-2xl font-bold text-primary-dark">Thank you!</h3>
                <p class="mt-1.5 max-w-sm text-primary-dark/60">Your testimonial has been submitted. We&apos;ll review it and share it with the Waggies community soon.</p>
            </div>

            <form x-show="!submitted" x-cloak @submit.prevent="submit($event)" :aria-busy="submitting" class="space-y-5" aria-describedby="testimonial-form-help">
                @csrf
                <div class="hidden" aria-hidden="true">
                    <label for="testimonial-website">Leave this field empty</label>
                    <input id="testimonial-website" name="website" type="text" tabindex="-1" autocomplete="off">
                </div>
                <p id="testimonial-form-help" class="sr-only">Required fields are marked. The form has three steps.</p>
                <input type="hidden" name="rating" :value="data.rating">

                <div x-show="step === 0" x-transition class="space-y-5">
                    <div>
                        <h3 class="font-serif text-xl font-bold text-primary-dark">Your Experience</h3>
                        <p class="mt-0.5 text-sm text-primary-dark/60">Tell us how Waggies helped you and your pet.</p>
                    </div>

                    <div class="space-y-1.5">
                        <p id="testimonial-rating-label" class="font-semibold text-primary-dark">Overall rating <span class="text-primary" aria-hidden="true">*</span></p>
                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-1" role="radiogroup" aria-labelledby="testimonial-rating-label" aria-required="true" :aria-describedby="errors.rating ? 'testimonial-rating-error' : null">
                                <template x-for="n in 5" :key="n">
                                    <button type="button" @click="data.rating = n; clear('rating')" class="-m-1 rounded-full p-1 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/60" :aria-label="n + (n > 1 ? ' stars' : ' star')" :aria-checked="data.rating === n" role="radio">
                                        <span :class="data.rating >= n ? 'text-gold' : 'text-primary-dark/20 hover:text-gold'"><x-waggies.icon name="star" size="30" variant="filled" /></span>
                                    </button>
                                </template>
                            </div>
                            <span x-show="data.rating > 0" class="ml-1 text-sm font-semibold text-primary-dark/70" x-text="data.rating + ' of 5'"></span>
                        </div>
                        <p id="testimonial-rating-error" x-show="errors.rating" x-text="errors.rating" class="text-xs font-medium text-error" role="alert"></p>
                    </div>

                    <div class="space-y-1.5">
                        <label for="testimonial-service" class="font-semibold text-primary-dark">Service used <span class="text-primary" aria-hidden="true">*</span></label>
                        <select id="testimonial-service" name="service" x-model="data.service" @change="clear('service')" required :aria-invalid="errors.service ? 'true' : null" :aria-describedby="errors.service ? 'testimonial-service-error' : null" class="contact-input rounded-md!">
                            <option value="">Choose a service</option>
                            <template x-for="service in services" :key="service"><option :value="service" x-text="service"></option></template>
                        </select>
                        <p id="testimonial-service-error" x-show="errors.service" x-text="errors.service" class="text-xs font-medium text-error" role="alert"></p>
                    </div>

                    <div class="space-y-1.5">
                        <label for="testimonial-story" class="font-semibold text-primary-dark">Your story <span class="text-primary" aria-hidden="true">*</span></label>
                        <textarea id="testimonial-story" name="story" x-model="data.story" @input="clear('story')" required maxlength="2000" rows="5" placeholder="Share what happened, how the team treated your pet, and what you loved most..." :aria-invalid="errors.story ? 'true' : null" :aria-describedby="errors.story ? 'testimonial-story-help testimonial-story-error' : 'testimonial-story-help'" class="contact-input resize-none rounded-md!"></textarea>
                        <div id="testimonial-story-help" class="flex items-center justify-between text-[11px] text-primary-dark/60"><span>Minimum 50 characters.</span><span x-text="data.story.length + '/2000'"></span></div>
                        <p id="testimonial-story-error" x-show="errors.story" x-text="errors.story" class="text-xs font-medium text-error" role="alert"></p>
                    </div>
                </div>

                <div x-show="step === 1" x-transition class="space-y-5">
                    <div>
                        <h3 class="font-serif text-xl font-bold text-primary-dark">About You</h3>
                        <p class="mt-0.5 text-sm text-primary-dark/60">So we can attribute your testimonial properly.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="space-y-1.5">
                            <label for="authorName" class="font-semibold text-primary-dark">Your name <span class="text-primary" aria-hidden="true">*</span></label>
                            <input id="authorName" name="author_name" x-model="data.authorName" @input="clear('authorName')" required maxlength="60" placeholder="e.g. Adaeze O." :aria-invalid="errors.authorName ? 'true' : null" :aria-describedby="errors.authorName ? 'author-name-error' : null" class="contact-input rounded-md!">
                            <p id="author-name-error" x-show="errors.authorName" x-text="errors.authorName" class="text-xs font-medium text-error" role="alert"></p>
                        </div>
                        <div class="space-y-1.5">
                            <label for="authorLocation" class="font-semibold text-primary-dark">Your area <span class="text-primary" aria-hidden="true">*</span></label>
                            <input id="authorLocation" name="author_location" x-model="data.authorLocation" @input="clear('authorLocation')" required maxlength="80" placeholder="e.g. Maitama, Abuja" :aria-invalid="errors.authorLocation ? 'true' : null" :aria-describedby="errors.authorLocation ? 'author-location-error' : null" class="contact-input rounded-md!">
                            <p id="author-location-error" x-show="errors.authorLocation" x-text="errors.authorLocation" class="text-xs font-medium text-error" role="alert"></p>
                        </div>
                    </div>
                </div>

                <div x-show="step === 2" x-transition class="space-y-5">
                    <div>
                        <h3 class="font-serif text-xl font-bold text-primary-dark">Consent &amp; Submit</h3>
                        <p class="mt-0.5 text-sm text-primary-dark/60">Review your consent before submitting.</p>
                    </div>

                    <div x-show="serverError" x-text="serverError" class="rounded-xl border border-error/40 bg-error-light/50 p-4 text-sm text-error" role="alert" aria-live="assertive"></div>

                    <div class="space-y-1.5">
                        <label for="testimonial-consent" class="flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition" :class="errors.consent ? 'border-error bg-error-light/40' : 'border-surface-purple bg-surface-purple/40 hover:bg-surface-purple/70'">
                            <input id="testimonial-consent" name="consent" type="checkbox" x-model="data.consent" @change="clear('consent')" required :aria-invalid="errors.consent ? 'true' : null" :aria-describedby="errors.consent ? 'testimonial-consent-error' : null" class="mt-1 size-4 accent-primary">
                            <span class="text-sm leading-relaxed text-primary-dark/80">I agree to Waggies using my testimonial on their website and marketing materials.</span>
                        </label>
                        <p id="testimonial-consent-error" x-show="errors.consent" x-text="errors.consent" class="text-xs font-medium text-error" role="alert"></p>
                    </div>

                    <div class="mt-2 rounded-xl border border-surface-purple bg-white p-4">
                        <p class="mb-2 text-[11px] font-semibold uppercase tracking-wide text-primary-dark/50">Your submission</p>
                        <div class="flex flex-wrap items-center gap-3 text-sm"><div class="flex items-center gap-0.5"><template x-for="n in 5" :key="n"><span :class="data.rating >= n ? 'text-gold' : 'text-primary-dark/20'"><x-waggies.icon name="star" size="16" variant="filled" /></span></template></div><span class="text-primary-dark/60">·</span><span class="font-semibold text-primary-dark" x-text="data.service || ' - '"></span><span class="text-primary-dark/60">·</span><span class="text-primary-dark/70" x-text="(data.authorName || 'Your name') + (data.authorLocation ? ' · ' + data.authorLocation : '')"></span></div>
                        <p x-show="data.story" x-text="data.story" class="mt-1 line-clamp-2 text-sm text-primary-dark/70"></p>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3 pt-4">
                    <button type="button" @click="back" :disabled="stepIndex === 0 || submitting" class="inline-flex min-h-11 items-center gap-1 rounded-full px-4 py-3 text-primary-dark hover:bg-surface-purple/60 hover:text-primary disabled:cursor-not-allowed disabled:opacity-40"><x-waggies.icon name="arrow-back" size="16" aria-hidden="true" />Back</button>
                    <x-waggies.button x-show="stepIndex < 2" type="button" @click="next">Continue <x-waggies.icon name="arrow-forward" size="16" aria-hidden="true" /></x-waggies.button>
                    <x-waggies.button x-show="stepIndex === 2" type="submit" x-bind:disabled="submitting" x-bind:aria-busy="submitting"><span x-show="!submitting" class="inline-flex items-center gap-2"><x-waggies.icon name="send" size="16" aria-hidden="true" />Submit Testimonial</span><span x-show="submitting" aria-hidden="true">Submitting...</span></x-waggies.button>
                </div>
            </form>
        </div>
    </div>
</div>
