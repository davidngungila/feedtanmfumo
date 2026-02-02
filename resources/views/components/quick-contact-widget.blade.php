@php
    $phone = \App\Models\Setting::get('phone') ?? '0717358865';
    $email = \App\Models\Setting::get('email') ?? 'feedtan15@gmail.com';
    $whatsapp = \App\Models\Setting::get('whatsapp_number') ?? $phone;
    $organizationName = \App\Models\Setting::get('organization_name') ?? 'FeedTan Community Microfinance Group';
    
    // Format phone number for tel: link (remove spaces, dashes, etc.)
    $phoneFormatted = preg_replace('/[^0-9+]/', '', $phone);
    $whatsappFormatted = preg_replace('/[^0-9+]/', '', $whatsapp);
    
    // Format WhatsApp number for wa.me link
    $whatsappLink = $whatsappFormatted;
    if (!str_starts_with($whatsappLink, '+')) {
        // For Tanzania, add +255 if it starts with 0
        if (str_starts_with($whatsappLink, '0')) {
            $whatsappLink = '+255' . substr($whatsappLink, 1);
        } else {
            $whatsappLink = '+255' . $whatsappLink;
        }
    }
    
    // WhatsApp message text
    $whatsappMessage = urlencode("Hi {$organizationName}, I would like to inquire about your services");
@endphp

<style>
    .quick-contact-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 12px;
        align-items: flex-end;
    }
    
    .contact-icon-btn {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .contact-icon-btn:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    }
    
    .contact-icon-btn.whatsapp {
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
    }
    
    .contact-icon-btn.whatsapp:hover {
        background: linear-gradient(135deg, #128C7E 0%, #25D366 100%);
    }
    
    .contact-icon-btn.email {
        background: linear-gradient(135deg, #015425 0%, #027a3a 100%);
    }
    
    .contact-icon-btn.email:hover {
        background: linear-gradient(135deg, #027a3a 0%, #015425 100%);
    }
    
    .contact-icon-btn.call {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    }
    
    .contact-icon-btn.call:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
    }
    
    .contact-icon-btn svg {
        width: 24px;
        height: 24px;
    }
    
    /* Tooltip */
    .contact-icon-btn::after {
        content: attr(title);
        position: absolute;
        right: 70px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        font-weight: 500;
    }
    
    .contact-icon-btn:hover::after {
        opacity: 1;
    }
    
    .contact-icon-btn::before {
        content: '';
        position: absolute;
        right: 64px;
        top: 50%;
        transform: translateY(-50%);
        border: 6px solid transparent;
        border-left-color: rgba(0, 0, 0, 0.8);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    
    .contact-icon-btn:hover::before {
        opacity: 1;
    }
    
    /* Mobile responsive */
    @media (max-width: 640px) {
        .quick-contact-widget {
            bottom: 15px;
            right: 15px;
            gap: 10px;
        }
        
        .contact-icon-btn {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }
        
        .contact-icon-btn svg {
            width: 20px;
            height: 20px;
        }
        
        .contact-icon-btn::after {
            display: none;
        }
        
        .contact-icon-btn::before {
            display: none;
        }
    }
</style>

<div class="quick-contact-widget" id="quickContactWidget">
    @if($whatsapp)
    <a href="https://wa.me/{{ $whatsappLink }}?text={{ $whatsappMessage }}" 
       target="_blank" 
       rel="noopener noreferrer"
       class="contact-icon-btn whatsapp" 
       title="WhatsApp">
        <svg fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>
    </a>
    @endif

    @if($email)
    <a href="mailto:{{ $email }}" 
       class="contact-icon-btn email" 
       title="Email Us">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
        </svg>
    </a>
    @endif

    @if($phone)
    <a href="tel:{{ $phoneFormatted }}" 
       class="contact-icon-btn call" 
       title="Call Us">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
        </svg>
    </a>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const widget = document.getElementById('quickContactWidget');
        if (widget) {
            // Add animation on load
            const buttons = widget.querySelectorAll('.contact-icon-btn');
            buttons.forEach((btn, index) => {
                btn.style.opacity = '0';
                btn.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    btn.style.transition = 'all 0.4s ease';
                    btn.style.opacity = '1';
                    btn.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }
    });
</script>

