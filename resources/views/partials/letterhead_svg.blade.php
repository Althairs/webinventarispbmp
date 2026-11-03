{{-- Kop Surat BPMP Provinsi Gorontalo dengan Logo SVG --}}
<div class="letterhead">
    <div class="letterhead-content">
        <div class="logo">
            <div class="logo-shield">
                <div class="logo-content">
                    <svg class="garuda-svg" viewBox="0 0 100 100" width="60" height="60">
                        <!-- Garuda Wings -->
                        <path d="M20 30 Q50 10 80 30 Q50 20 20 30" stroke="#000" stroke-width="2" fill="none"/>
                        <path d="M25 35 Q50 15 75 35 Q50 25 25 35" stroke="#000" stroke-width="1" fill="none"/>
                        <path d="M30 40 Q50 20 70 40 Q50 30 30 40" stroke="#000" stroke-width="1" fill="none"/>
                        
                        <!-- Garuda Body -->
                        <rect x="48" y="25" width="4" height="15" stroke="#000" stroke-width="2" fill="none"/>
                        
                        <!-- Flame/Torch -->
                        <path d="M48 20 Q50 15 52 20 Q50 18 48 20" stroke="#000" stroke-width="1" fill="none"/>
                        
                        <!-- Garuda Tail -->
                        <path d="M35 50 Q50 40 65 50 Q50 45 35 50" stroke="#000" stroke-width="1" fill="none"/>
                        <path d="M40 55 Q50 45 60 55 Q50 50 40 55" stroke="#000" stroke-width="1" fill="none"/>
                        
                        <!-- Book -->
                        <rect x="40" y="60" width="20" height="6" stroke="#000" stroke-width="1" fill="none"/>
                        <line x1="45" y1="62" x2="55" y2="62" stroke="#000" stroke-width="0.5"/>
                        <line x1="45" y1="64" x2="55" y2="64" stroke="#000" stroke-width="0.5"/>
                    </svg>
                    <div class="tut-wuri-text">TUT WURI HANDAYANI</div>
                </div>
            </div>
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

.garuda-svg {
    margin-bottom: 2px;
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
    font-size: 14px;
    margin: 0;
    line-height: 1.2;
}

.institution-name {
    font-size: 16px;
    font-weight: bold;
    margin: 5px 0;
    line-height: 1.2;
}

.province-name {
    font-size: 18px;
    font-weight: bold;
    margin: 0;
    line-height: 1.2;
}

.contact-info {
    text-align: left;
    font-size: 11px;
    margin-top: 10px;
    line-height: 1.3;
}

.contact-info p {
    margin: 2px 0;
}
</style>
