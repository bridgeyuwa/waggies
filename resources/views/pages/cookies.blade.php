<x-layouts.minimal title="Cookies Policy" last-updated="1 January 2026" nav-section="">

    <p>This Cookies Policy explains how Waggies uses cookies and similar tracking technologies on our website.</p>

    <h2>1. What Are Cookies?</h2>
    <p>Cookies are small text files placed on your device when you visit a website. They allow the website to recognise your device and remember certain information about your visit.</p>

    <h2>2. How We Use Cookies</h2>
    <p>We use cookies for the following purposes:</p>
    <ul>
        <li><strong>Essential cookies</strong> — required for the website to function correctly, such as session management and CSRF protection.</li>
        <li><strong>Analytics cookies</strong> — anonymised data that helps us understand how visitors use our website so we can improve it.</li>
        <li><strong>Preference cookies</strong> — remember choices you make (such as dismissing the cookie banner) to improve your experience on return visits.</li>
    </ul>

    <h2>3. Third-Party Cookies</h2>
    <p>We may use third-party services (such as Google Analytics) that set their own cookies. These parties are responsible for their own cookie policies, which we encourage you to review.</p>

    <h2>4. Managing Cookies</h2>
    <p>Most browsers allow you to control cookies through their settings. You can also use our cookie banner to manage your preferences. Please note that disabling essential cookies may affect the functionality of our website.</p>

    <h2>5. Changes to This Policy</h2>
    <p>We may update this Cookies Policy from time to time. Please check back periodically to stay informed.</p>

    <h2>6. Contact</h2>
    <p>If you have any questions about how we use cookies, please <a href="{{ route('contact') }}">contact us</a>.</p>


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::webPage()
    ->name('Cookies Policy — Waggies')
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.minimal>
