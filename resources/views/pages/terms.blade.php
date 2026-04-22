<x-layouts.minimal title="Terms of Service" last-updated="1 January 2026" nav-section="">

    <p>These Terms of Service govern your use of Waggies' services and website. By using our services, you agree to these terms in full. Please read them carefully.</p>

    <h2>1. Services</h2>
    <p>Waggies provides pet boarding, grooming, veterinary care, training, transport, and relocation services. All services are subject to availability and must be booked in advance unless otherwise stated.</p>

    <h2>2. Bookings & Payments</h2>
    <p>All bookings are confirmed upon receipt of the required deposit. Full payment is due at check-in or as otherwise agreed. We reserve the right to cancel unconfirmed bookings.</p>

    <h2>3. Cancellations</h2>
    <p>Cancellations made more than 48 hours before the scheduled service date will receive a full refund of any deposit. Cancellations within 48 hours may forfeit the deposit at our discretion.</p>

    <h2>4. Pet Health Requirements</h2>
    <p>All pets using our boarding and grooming services must be up to date with core vaccinations. We reserve the right to refuse or terminate a booking if a pet presents a health risk to other animals or staff.</p>

    <h2>5. Owner Responsibilities</h2>
    <p>You are responsible for providing accurate information about your pet's health, temperament, and any special requirements. Failure to disclose relevant information may result in cancellation without refund.</p>

    <h2>6. Liability</h2>
    <p>Waggies takes every precaution to ensure the safety and wellbeing of all pets in our care. However, we cannot accept liability for illness or injury arising from pre-existing undisclosed conditions. We carry appropriate insurance for the services we provide.</p>

    <h2>7. Use of Our Website</h2>
    <p>You may use our website for lawful purposes only. You must not attempt to gain unauthorised access to any part of our website or its systems.</p>

    <h2>8. Changes to These Terms</h2>
    <p>We may update these Terms of Service from time to time. Continued use of our services following any update constitutes acceptance of the revised terms.</p>

    <h2>9. Contact</h2>
    <p>If you have any questions about these Terms of Service, please <a href="{{ route('contact') }}">contact us</a>.</p>


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::webPage()
    ->name('Terms of Service — Waggies')
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.minimal>
