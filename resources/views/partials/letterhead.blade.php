{{-- Kop Surat BPMP Provinsi Gorontalo --}}
<div class="letterhead">
    <div class="letterhead-content">
        <div class="logo">
            @php
                $logoUrl = (request()->is('*/pdf') || request()->has('download') || request()->header('Accept') === 'application/pdf' || str_contains(request()->url(), 'pdf')) 
                    ? public_path('logo-bpmp-small.png') 
                    : asset('logo-bpmp-small.png');
            @endphp
            <img src="{{ $logoUrl }}" alt="Logo BPMP" class="logo-image">
        </div>
        
        <div class="header-text">
            <p class="ministry-name">KEMENTERIAN PENDIDIKAN DASAR DAN</p>
            <p class="ministry-name">MENENGAH</p>
            
            <p class="institution-name">BALAI PENJAMINAN MUTU PENDIDIKAN</p>
            <p class="province-name">PROVINSI GORONTALO</p>
            
            <div class="contact-info">
                <p>Jalan dr. Zainal Umar Sidiki Tilongkabila, Bone Bolango Gorontalo</p>
                <p>Telepon 085242700045 Po.Box : 1024 Kode Pos 96554</p>
                <p>Laman: www.bpmpgorontalo.kemdikbud.go.id</p>
                <p>Email bpmpgorontalo@kemdikbud.go.id</p>
            </div>
        </div>
    </div>
</div>

<style>
.letterhead {
    width: 100%;
    border-bottom: 2px solid #000;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.letterhead-content {
    display: flex;
    align-items: center;
    width: 100%;
}

.logo {
    flex-shrink: 0;
    margin-right: 20px;
}

.logo-shield {
    width: 80px;
    height: 80px;
    border: 2px solid #000;
    position: relative;
    clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
    background: #fff;
}

.logo-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    font-size: 8px;
    font-weight: bold;
}

        .garuda-emblem {
            width: 50px;
            height: 40px;
            margin: 0 auto 2px;
            position: relative;
        }
        
        .garuda-wings {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 45px;
            height: 25px;
            border: 2px solid #000;
            border-radius: 50% 50% 0 0;
            background: transparent;
        }
        
        .garuda-wings::before {
            content: '';
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            height: 15px;
            border: 1px solid #000;
            border-radius: 50% 50% 0 0;
        }
        
        .garuda-wings::after {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            height: 10px;
            border: 1px solid #000;
            border-radius: 50% 50% 0 0;
        }
        
        .garuda-body {
            position: absolute;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            width: 8px;
            height: 12px;
            border: 2px solid #000;
            background: transparent;
        }
        
        .garuda-flame {
            position: absolute;
            top: 8px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 8px;
            border: 1px solid #000;
            background: transparent;
            border-radius: 50% 50% 0 0;
        }
        
        .garuda-tail {
            position: absolute;
            top: 25px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 8px;
            border: 1px solid #000;
            background: transparent;
            border-radius: 0 0 50% 50%;
        }
        
        .garuda-tail::before {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            right: 2px;
            height: 4px;
            border: 1px solid #000;
            border-radius: 0 0 50% 50%;
        }
        
        .garuda-book {
            position: absolute;
            top: 32px;
            left: 50%;
            transform: translateX(-50%);
            width: 12px;
            height: 4px;
            border: 1px solid #000;
            background: transparent;
            border-radius: 0 0 2px 2px;
        }
        
        .garuda-book::before {
            content: '';
            position: absolute;
            top: 1px;
            left: 1px;
            right: 1px;
            height: 2px;
            border: 1px solid #000;
            border-radius: 0 0 1px 1px;
        }

.tut-wuri-text {
    font-size: 6px;
    font-weight: bold;
    margin-top: 2px;
}

.header-text {
    flex: 1;
    text-align: center;
}

.ministry-name {
    font-family: 'Times New Roman', serif;
    font-size: 12px;
    margin: 0;
    line-height: 1.2;
}

.institution-name {
    font-family: 'Times New Roman', serif;
    font-size: 14px;
    font-weight: bold;
    margin: 3px 0;
    line-height: 1.2;
}

.province-name {
    font-family: 'Times New Roman', serif;
    font-size: 16px;
    font-weight: bold;
    margin: 0;
    line-height: 1.2;
}

.contact-info {
    font-family: 'Times New Roman', serif;
    text-align: center;
    font-size: 10px;
    margin-top: 8px;
    line-height: 1.3;
}

.contact-info p {
    margin: 2px 0;
}

.logo-image {
    width: 80px;
    height: 80px;
    object-fit: contain;
}

.logo-placeholder {
    width: 80px;
    height: 80px;
    border: 2px solid #000;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: bold;
    text-align: center;
    background-color: #f0f0f0;
}
</style>
