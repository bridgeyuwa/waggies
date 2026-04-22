<x-layouts.minimal title="Privacy Policy" last-updated="1 January 2026" nav-section="">

    <p>At Waggies, we are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, and protect the information you share with us when you use our services or visit our website.</p>

    <h2>1. Information We Collect</h2>
    <p>We may collect the following types of information:</p>
    <ul>
        <li><strong>Contact information</strong> — your name, email address, and phone number when you contact us or make a booking.</li>
        <li><strong>Pet information</strong> — details about your pet(s) including species, breed, age, and health records where relevant to the service.</li>
        <li><strong>Usage data</strong> — anonymised data about how you use our website, collected via analytics tools.</li>
    </ul>

    <h2>2. How We Use Your Information</h2>
    <p>We use the information we collect to:</p>
    <ul>
        <li>Provide and manage the pet care services you have requested.</li>
        <li>Communicate with you about your bookings, enquiries, and updates.</li>
        <li>Send you newsletters and marketing communications where you have opted in.</li>
        <li>Improve our website and services.</li>
    </ul>

    <h2>3. Data Sharing</h2>
    <p>We do not sell or rent your personal data to third parties. We may share data with trusted service providers (such as payment processors or email platforms) solely to operate our services, and only under strict confidentiality obligations.</p>

    <h2>4. Data Retention</h2>
    <p>We retain your personal data for as long as necessary to provide our services and comply with legal obligations. You may request deletion of your data at any time by contacting us.</p>

    <h2>5. Your Rights</h2>
    <p>You have the right to access, correct, or request deletion of your personal data. To exercise these rights, please contact us at the address below.</p>

    <h2>6. Cookies</h2>
    <p>We use cookies to improve your experience on our website. For more information, see our <a href="{{ route('cookies') }}">Cookies Policy</a>.</p>

    <h2>7. Contact</h2>
    <p>If you have any questions about this Privacy Policy, please <a href="{{ route('contact') }}">contact us</a>.</p>


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::webPage()
    ->name('Privacy Policy — Waggies')
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.minimal>
