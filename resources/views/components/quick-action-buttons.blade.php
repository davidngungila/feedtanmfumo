@php
    $phone = \App\Models\Setting::get('phone') ?? '0717358865';
    $email = \App\Models\Setting::get('email') ?? 'feedtan15@gmail.com';
    $whatsapp = \App\Models\Setting::get('whatsapp_number') ?? $phone;
    
    // Format phone number for tel: link (remove spaces, dashes, etc.)
    $phoneFormatted = preg_replace('/[^0-9+]/', '', $phone);
    $whatsappFormatted = preg_replace('/[^0-9+]/', '', $whatsapp);
    
    // Format WhatsApp number for wa.me link (remove + if present, add country code if needed)
    $whatsappLink = $whatsappFormatted;
    if (!str_starts_with($whatsappLink, '+')) {
        // If no +, assume it's a local number and might need country code
        // For Tanzania, add +255 if it starts with 0
        if (str_starts_with($whatsappLink, '0')) {
            $whatsappLink = '+255' . substr($whatsappLink, 1);
        } else {
            $whatsappLink = '+255' . $whatsappLink;
        }
    }
    
    // Check if we're in member layout (white header) or admin layout (white background)
    $isMemberLayout = isset($isMemberLayout) ? $isMemberLayout : false;
    
    // Button classes based on layout
    if ($isMemberLayout) {
        // Member layout: white buttons on green header
        $whatsappClass = 'p-2 text-white hover:bg-white hover:bg-opacity-20 rounded-full transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#015425]';
        $callClass = 'p-2 text-white hover:bg-white hover:bg-opacity-20 rounded-full transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#015425]';
        $emailClass = 'p-2 text-white hover:bg-white hover:bg-opacity-20 rounded-full transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#015425]';
    } else {
        // Admin layout: colored buttons on white background
        $whatsappClass = 'p-2 text-green-600 hover:text-green-700 hover:bg-green-50 rounded-full transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2';
        $callClass = 'p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-full transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2';
        $emailClass = 'p-2 text-[#015425] hover:text-[#027a3a] hover:bg-green-50 rounded-full transition focus:outline-none focus:ring-2 focus:ring-[#015425] focus:ring-offset-2';
    }
@endphp

<div class="flex items-center space-x-2">
    <!-- WhatsApp Button -->
    @if($whatsapp)
    <a href="https://wa.me/{{ $whatsappLink }}" 
       target="_blank" 
       rel="noopener noreferrer"
       class="{{ $whatsappClass }}"
       title="Contact via WhatsApp">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>
    </a>
    @endif

    <!-- Call Button -->
    @if($phone)
    <a href="tel:{{ $phoneFormatted }}" 
       class="{{ $callClass }}"
       title="Call: {{ $phone }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
        </svg>
    </a>
    @endif

    <!-- Email Button -->
    @if($email)
    <a href="mailto:{{ $email }}" 
       class="{{ $emailClass }}"
       title="Email: {{ $email }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
        </svg>
    </a>
    @endif
</div>
