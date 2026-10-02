<x-mail::message>
# New booking request

**{{ $bookingRequest->name }}** submitted a new booking request.

- **Email:** {{ $bookingRequest->email }}
- **Phone:** {{ $bookingRequest->phone }}
- **Service:** {{ $bookingRequest->service_key }}
- **Pet:** {{ $bookingRequest->pet_name }} ({{ $bookingRequest->pet_type }})

Review the request in the Waggies admin dashboard.
</x-mail::message>
