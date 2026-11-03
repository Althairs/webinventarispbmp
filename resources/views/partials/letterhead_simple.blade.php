{{-- Kop Surat BPMP Provinsi Gorontalo - Versi Sederhana --}}
<div class="letterhead">
    <div class="letterhead-content">
        <div class="logo">
            <div class="logo-shield">
                <div class="logo-content">
                    <div class="garuda-simple">
                        <div class="garuda-wings-simple"></div>
                        <div class="garuda-body-simple"></div>
                        <div class="garuda-book-simple"></div>
                    </div>
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

.garuda-simple {
    width: 35px;
    height: 30px;
    margin: 0 auto 2px;
    position: relative;
}

.garuda-wings-simple {
    width: 100%;
    height: 15px;
    border: 2px solid #000;
    border-radius: 50% 50% 0 0;
    background: transparent;
    position: relative;
}

.garuda-wings-simple::before {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    right: 2px;
    height: 8px;
    border: 1px solid #000;
    border-radius: 50% 50% 0 0;
}

.garuda-body-simple {
    width: 8px;
    height: 8px;
    border: 2px solid #000;
    background: transparent;
    margin: 2px auto;
    border-radius: 50%;
}

.garuda-book-simple {
    width: 12px;
    height: 4px;
    border: 1px solid #000;
    background: transparent;
    margin: 0 auto;
    border-radius: 0 0 2px 2px;
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
