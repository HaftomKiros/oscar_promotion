<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register | Oscar Promotion</title>
<link rel="icon" type="image/png" href="<?php echo base_url('my-assets/image/logo/oscar_logo1.png'); ?>">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
const T={
  en:{heroTitle:"Find Your Next\nOpportunity",heroDesc:"Fill in your information and connect with employers fast. Once registered, we will contact you for more detail information.",formTitle:"Job Seeker Registration",formDesc:"All fields marked * are required.",personalDetails:"Personal Details",fullName:"Full Name",fullNamePh:"Enter your full name",dob:"Date of Birth (Ethiopian)",phone:"Phone Number",phonePh:"e.g. 0912345678",sex:"Sex",male:"Male",female:"Female",education:"Education & Experience",eduLevel:"Education Level",experience:"Years of Experience",qualification:"Job Looking For",qualificationPh:"e.g. Accountant, Nurse, Driver",location:"Location",locationLabel:"Location / Address",locationPh:"City, Sub-city or Woreda",submit:"Submit Registration",consent:"By submitting, you confirm your details are accurate.",day:"Day",month:"Month",year:"Year",meskerem:"Meskerem",tikemet:"Tikemet",hidar:"Hidar",tahsas:"Tahsas",tir:"Tir",yekatit:"Yekatit",megabit:"Megabit",miyazia:"Miyazia",ginbot:"Ginbot",sene:"Sene",hamle:"Hamle",nehase:"Nehase"},
  ti:{heroTitle:"ናይ ስራሕ ፈለይ\nምዝገባ",heroDesc:"ብቐሊሉ ምምዝጋብ።",formTitle:"ምዝገባ ፎርም",formDesc:"ኩሎም * ምልክት ዘለዎም ዘድልዩ እዮም።",personalDetails:"ነገራት ብዓልቲ",fullName:"ምሉእ ስም",fullNamePh:"ምሉእ ስምካ",dob:"ዕለት ልደት",phone:"ቁጽሪ ስልኪ",phonePh:"0912345678",sex:"ጾታ",male:"ወዲ",female:"ጓል",education:"ትምህርትን ተሞኩሮን",eduLevel:"ደረጃ ትምህርት",experience:"ዓመታት ስራሕ",qualification:"ስራሕ ዝደሊ",qualificationPh:"ምሳሌ: ኣካውንታንት",location:"ቦታ",locationLabel:"ቦታ / ኣድራሻ",locationPh:"ከተማ",submit:"ምዝገባ ምቕራብ",consent:"ዝሃብካዮ ሓቂ ምዃኑ ተቐበሎ።",day:"ቀን",month:"ወርሒ",year:"ዓመት",meskerem:"መስከረም",tikemet:"ጥቅምቲ",hidar:"ሕዳር",tahsas:"ታሕሳስ",tir:"ጥር",yekatit:"የካቲት",megabit:"መጋቢት",miyazia:"ሚያዚያ",ginbot:"ግንቦት",sene:"ሰነ",hamle:"ሓምለ",nehase:"ነሓሰ"}
};
function t(lang){
  if(!T[lang])return;
  document.querySelectorAll('[data-k]').forEach(el=>{var k=el.getAttribute('data-k');if(T[lang][k]!==undefined)el.textContent=T[lang][k]});
  document.querySelectorAll('[data-kp]').forEach(el=>{var k=el.getAttribute('data-kp');if(T[lang][k])el.placeholder=T[lang][k]});
  var h=document.getElementById('hero-title');if(h)h.innerHTML=T[lang].heroTitle.replace('\n','<br>');
  localStorage.setItem('lang',lang);
  document.getElementById('langBtn').setAttribute('data-lang',lang);
  document.getElementById('langBtn').innerHTML=lang==='en'?'<i class="bi bi-translate"></i> ትግርኛ':'<i class="bi bi-translate"></i> English';
  var ms=['meskerem','tikemet','hidar','tahsas','tir','yekatit','megabit','miyazia','ginbot','sene','hamle','nehase'];
  Array.from(document.getElementById('dob_month').options).forEach((o,i)=>{if(i>0&&ms[i-1]&&T[lang][ms[i-1]])o.textContent=T[lang][ms[i-1]]});
}
document.addEventListener('DOMContentLoaded',()=>{
  var l=localStorage.getItem('lang')||'en'; t(l);
  document.getElementById('langBtn').addEventListener('click',function(){t(this.getAttribute('data-lang')==='en'?'ti':'en')});
  jQuery('#education_level').select2({theme:'bootstrap-5',placeholder:'Select level...',allowClear:true});
});
</script><style>
:root{--p:#0d2137;--pm:#1a3a5c;--pl:#2d5282;--a:#f5a623;--al:#fbbf24;--bg:#eef2f7;--white:#fff;--text:#1e293b;--muted:#64748b;--border:#dde3ec;--green:#10b981;--red:#ef4444;}
*{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;font-family:'Plus Jakarta Sans',sans-serif;-webkit-font-smoothing:antialiased;}
body{background:var(--bg);color:var(--text);min-height:100vh;}

/* ── SPLASH ── */
#splash{position:fixed;inset:0;z-index:9999;display:none;flex-direction:column;align-items:center;justify-content:flex-start;padding:60px 20px 28px;text-align:center;background:linear-gradient(160deg,var(--p) 0%,var(--pm) 50%,var(--pl) 100%);overflow-y:auto;overflow-x:hidden;-webkit-overflow-scrolling:touch;}
#splash::before{content:'';position:absolute;top:-80px;right:-80px;width:260px;height:260px;border-radius:50%;border:55px solid rgba(255,255,255,0.04);pointer-events:none;z-index:0;}
#splash::after{content:'';position:absolute;bottom:-90px;left:5%;width:280px;height:280px;border-radius:50%;border:55px solid rgba(245,166,35,0.06);pointer-events:none;z-index:0;}
#splash.hide{animation:splashOut 0.55s cubic-bezier(0.4,0,0.2,1) forwards;}
@keyframes splashOut{0%{opacity:1;transform:translateY(0);}100%{opacity:0;transform:translateY(-40px);}}
.sp-inner{position:relative;z-index:1;width:100%;max-width:420px;margin:0 auto;}
.sp-logo{width:68px;height:68px;border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 6px;animation:logoPop 0.5s 0.05s cubic-bezier(0.34,1.56,0.64,1) both;overflow:hidden;}
@keyframes logoPop{from{transform:scale(0.4);opacity:0;}to{transform:scale(1);opacity:1;}}
.sp-brand-name{font-size:12px;font-weight:800;color:rgba(255,255,255,0.55);letter-spacing:0.5px;margin-bottom:14px;}
.sp-title{font-size:clamp(22px,5.5vw,28px);font-weight:900;color:#fff;line-height:1.15;margin-bottom:6px;}
.sp-title span{color:var(--al);}
.sp-desc{font-size:12.5px;color:rgba(255,255,255,0.6);line-height:1.6;margin-bottom:18px;}
.sp-steps{display:flex;flex-direction:column;gap:0;margin-bottom:16px;text-align:left;}
.sp-step{display:flex;align-items:flex-start;gap:11px;position:relative;}
.sp-step:not(.sp-step-last){padding-bottom:2px;}
.sp-step-ico{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;position:relative;z-index:1;}
.sp-ico-1{background:rgba(245,166,35,0.2);color:var(--al);}
.sp-ico-2{background:rgba(99,179,237,0.2);color:#90cdf4;}
.sp-ico-3{background:rgba(52,211,153,0.2);color:#34d399;}
.sp-ico-4{background:rgba(167,139,250,0.2);color:#c4b5fd;}
.sp-step-line{position:absolute;left:16px;top:34px;width:2px;height:calc(100% - 24px);background:rgba(255,255,255,0.1);z-index:0;}
.sp-step-body{flex:1;padding:5px 0 14px;}
.sp-step-title{font-size:12.5px;font-weight:800;color:#fff;margin-bottom:1px;}
.sp-step-sub{font-size:11px;color:rgba(255,255,255,0.48);line-height:1.45;}
.sp-milestones{display:flex;align-items:center;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:13px;padding:12px 14px;margin-bottom:14px;}
.sp-mile{display:flex;flex-direction:column;align-items:center;gap:7px;flex:1;}
.sp-mile-dot{width:9px;height:9px;border-radius:50%;background:rgba(255,255,255,0.2);border:2px solid rgba(255,255,255,0.25);}
.sp-mile-dot.active{background:var(--a);border-color:var(--al);box-shadow:0 0 0 3px rgba(245,166,35,0.2);}
.sp-mile-label{font-size:9.5px;font-weight:700;color:rgba(255,255,255,0.5);line-height:1.4;text-align:center;}
.sp-mile-line{flex:1;height:2px;background:rgba(255,255,255,0.1);margin-bottom:20px;}
.sp-trust{display:flex;flex-direction:column;gap:6px;margin-bottom:16px;}
.sp-trust-pill{display:flex;align-items:center;gap:8px;background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.22);border-radius:10px;padding:7px 13px;font-size:11.5px;font-weight:600;color:rgba(255,255,255,0.82);text-align:left;}
.sp-trust-pill i{color:#34d399;font-size:13px;flex-shrink:0;}
.sp-cta{width:100%;padding:15px;background:linear-gradient(135deg,var(--a),#d97a08);color:#fff;border:none;border-radius:14px;font-size:15px;font-weight:900;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:9px;font-family:inherit;box-shadow:0 8px 24px rgba(245,166,35,0.45);transition:transform 0.15s;}
.sp-cta:active{transform:scale(0.97);}
.sp-foot{margin-top:10px;margin-bottom:4px;font-size:10.5px;color:rgba(255,255,255,0.28);}
.sp-foot strong{color:rgba(255,255,255,0.5);}
@media(max-width:768px){#splash{display:flex;}}
@media(max-width:360px){
  #splash{padding-top:48px;}
  .sp-title{font-size:20px;}
  .sp-step-body{padding-bottom:10px;}
  .sp-desc{margin-bottom:14px;}
}

/* ── MAIN LAYOUT ── */
.page-shell{display:flex;min-height:100vh;}

/* ── LEFT PANEL ── */
.left-panel{width:400px;flex-shrink:0;background:linear-gradient(160deg,var(--p) 0%,var(--pm) 45%,var(--pl) 100%);position:sticky;top:0;height:100vh;overflow:hidden;display:flex;flex-direction:column;justify-content:space-between;padding:40px 34px;}
.left-panel::before{content:'';position:absolute;inset:0;pointer-events:none;background:radial-gradient(ellipse 60% 40% at 80% 10%,rgba(245,166,35,0.08) 0%,transparent 70%),radial-gradient(ellipse 50% 50% at 10% 90%,rgba(59,108,183,0.15) 0%,transparent 70%);}
.left-panel::after{content:'';position:absolute;bottom:-80px;right:-80px;width:320px;height:320px;border-radius:50%;border:60px solid rgba(255,255,255,0.03);pointer-events:none;}
.lp-top{position:relative;z-index:1;}
.brand{display:flex;align-items:center;gap:13px;margin-bottom:44px;}
.brand-icon{width:48px;height:48px;border-radius:13px;background:var(--white);display:flex;align-items:center;justify-content:center;box-shadow:0 6px 18px rgba(245,166,35,0.3);flex-shrink:0;overflow:hidden;padding:2px;}
.brand-text .name{font-size:18px;font-weight:800;color:#fff;line-height:1;}
.brand-text .tag{font-size:11px;color:rgba(255,255,255,0.5);margin-top:3px;font-weight:500;}
.lp-headline{font-size:clamp(24px,2.4vw,32px);font-weight:900;color:#fff;line-height:1.2;margin-bottom:12px;}
.lp-headline span{color:var(--al);}
.lp-desc{font-size:13.5px;color:rgba(255,255,255,0.65);line-height:1.75;margin-bottom:32px;}
.feature-list{display:flex;flex-direction:column;gap:12px;margin-bottom:36px;}
.feat{display:flex;align-items:flex-start;gap:13px;}
.feat-icon{width:36px;height:36px;border-radius:10px;flex-shrink:0;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;color:var(--al);font-size:16px;}
.feat-body .ftitle{font-size:13px;font-weight:700;color:#fff;margin-bottom:2px;}
.feat-body .fdesc{font-size:11.5px;color:rgba(255,255,255,0.5);line-height:1.5;}
.stats-row{display:flex;border-radius:13px;overflow:hidden;border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.05);}
.stat{flex:1;padding:14px 10px;text-align:center;border-right:1px solid rgba(255,255,255,0.08);}
.stat:last-child{border-right:none;}
.stat .n{font-size:21px;font-weight:900;color:var(--al);display:block;}
.stat .l{font-size:10px;color:rgba(255,255,255,0.45);margin-top:2px;}
.lp-bottom{position:relative;z-index:1;}
.lp-footer{font-size:11px;color:rgba(255,255,255,0.3);}

/* ── RIGHT PANEL ── */
.right-panel{flex:1;overflow-y:auto;display:flex;flex-direction:column;min-width:0;}
.rp-topbar{height:56px;padding:0 36px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--border);background:var(--white);position:sticky;top:0;z-index:10;box-shadow:0 1px 6px rgba(13,33,55,0.06);}
.rp-topbar-left{font-size:13px;color:var(--muted);font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.rp-topbar-left span{color:var(--pm);font-weight:700;}
.lang-btn{display:flex;align-items:center;gap:6px;background:var(--bg);border:1.5px solid var(--border);color:var(--pm);padding:7px 16px;border-radius:40px;font-size:13px;font-weight:700;cursor:pointer;transition:all 0.2s;white-space:nowrap;flex-shrink:0;}
.lang-btn:hover{background:var(--pm);color:#fff;border-color:var(--pm);}
.rp-content{flex:1;padding:32px 36px 0;}

/* ── FORM CARD ── */
.form-card{background:var(--white);border-radius:20px;box-shadow:0 12px 48px rgba(13,33,55,0.10),0 2px 8px rgba(13,33,55,0.04);border:1px solid rgba(221,227,236,0.8);overflow:hidden;width:100%;}
.fc-banner{background:linear-gradient(135deg,var(--p) 0%,var(--pm) 50%,var(--pl) 100%);padding:28px 36px;position:relative;overflow:hidden;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;}
.fc-banner::before{content:'';position:absolute;top:-60px;right:-60px;width:220px;height:220px;border-radius:50%;border:50px solid rgba(255,255,255,0.05);pointer-events:none;}
.fc-banner::after{content:'';position:absolute;bottom:-80px;left:30%;width:260px;height:260px;border-radius:50%;border:50px solid rgba(245,166,35,0.06);pointer-events:none;}
.fc-banner-left{position:relative;z-index:1;}
.fc-banner-left h2{color:#fff;font-size:22px;font-weight:900;margin-bottom:4px;letter-spacing:-0.3px;}
.fc-banner-left p{color:rgba(255,255,255,0.6);font-size:13px;}
.fc-banner-right{position:relative;z-index:1;display:flex;gap:8px;flex-shrink:0;}
.fc-badge{display:flex;align-items:center;gap:5px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.18);color:#fff;font-size:12px;font-weight:600;padding:6px 13px;border-radius:30px;}
.fc-badge i{color:var(--al);}
.fc-steps{display:flex;align-items:center;background:rgba(255,255,255,0.06);border-top:1px solid rgba(255,255,255,0.1);padding:0 36px;overflow-x:auto;}
.fc-step{display:flex;align-items:center;gap:8px;padding:13px 0;flex:1;min-width:80px;position:relative;}
.fc-step:not(:last-child)::after{content:'';position:absolute;right:0;top:50%;transform:translateY(-50%);width:1px;height:40%;background:rgba(255,255,255,0.12);}
.step-num{width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,0.12);border:1.5px solid rgba(255,255,255,0.25);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.6);font-size:11px;font-weight:800;flex-shrink:0;}
.fc-step.done .step-num{background:var(--a);border-color:var(--a);color:#fff;box-shadow:0 0 0 3px rgba(245,166,35,0.25);}
.step-label{font-size:11px;font-weight:600;color:rgba(255,255,255,0.45);white-space:nowrap;}
.fc-step.done .step-label{color:rgba(255,255,255,0.9);}

/* ── SECTIONS ── */
.form-section{padding:30px 36px;position:relative;}
.form-section:not(:last-of-type){border-bottom:2px solid var(--border);}
.form-section.s1{background:linear-gradient(135deg,#f8fafd,#f2f6fb);}
.form-section.s2{background:linear-gradient(135deg,#fdfaf5,#fef8ec);}
.form-section.s3{background:var(--white);}
.sec-head{display:flex;align-items:center;gap:12px;margin-bottom:22px;padding-bottom:16px;border-bottom:1px dashed var(--border);}
.sec-num{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--pm),var(--pl));color:#fff;font-size:13px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 10px rgba(26,58,92,0.25);}
.sec-info h3{font-size:14px;font-weight:800;color:var(--p);margin:0 0 2px;}
.sec-info p{font-size:12px;color:var(--muted);margin:0;}
.sec-pill{margin-left:auto;font-size:10px;font-weight:800;letter-spacing:1px;text-transform:uppercase;background:rgba(245,166,35,0.1);border:1px solid rgba(245,166,35,0.3);color:var(--a);padding:3px 10px;border-radius:20px;white-space:nowrap;}

/* ── FIELDS ── */
.fld{position:relative;margin-bottom:0;}
.fld>label{font-size:11px;font-weight:800;color:var(--muted);margin-bottom:6px;display:flex;align-items:center;gap:5px;text-transform:uppercase;letter-spacing:0.9px;}
.fld>label .req{color:var(--red);}
.fld>label i{font-size:13px;color:var(--pm);}
.inp{width:100%;padding:13px 14px 13px 42px;border:1.5px solid var(--border);border-radius:12px;font-size:14px;color:var(--text);background:#fff;transition:all 0.2s;font-family:inherit;box-shadow:0 1px 4px rgba(13,33,55,0.04);}
.inp:focus{border-color:var(--pm);box-shadow:0 0 0 3px rgba(26,58,92,0.09);outline:none;}
.inp::placeholder{color:#b8c5d3;font-size:13px;}
.ico{position:absolute;left:13px;bottom:13px;color:var(--muted);font-size:15px;pointer-events:none;transition:color 0.2s;}
.ico.top{bottom:auto;top:13px;}
.fld:focus-within .ico{color:var(--pm);}
textarea.inp{padding-top:13px;min-height:88px;resize:vertical;}
select.inp{padding-left:14px;}
/* DOB inline */
.dob-inline{display:flex;align-items:center;gap:0;border:1.5px solid var(--border);border-radius:12px;overflow:hidden;background:#fff;box-shadow:0 1px 4px rgba(13,33,55,0.04);}
.dob-inline:focus-within{border-color:var(--pm);box-shadow:0 0 0 3px rgba(26,58,92,0.09);}
.dob-sel{flex:1;border:none!important;border-radius:0!important;box-shadow:none!important;padding:13px 6px;font-size:13px;text-align:center;background:transparent;min-width:0;cursor:pointer;}
.dob-sel:focus{outline:none;background:rgba(26,58,92,0.04);}
.dob-sep{flex-shrink:0;color:var(--muted);font-size:16px;font-weight:700;padding:0 2px;user-select:none;}
/* inp-wrap for select icon */
.inp-wrap{position:relative;}
.inp-wrap .ico{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:15px;pointer-events:none;}
.inp-wrap select.inp{padding-left:40px;}

/* ── RADIO ── */
.rcards{display:flex;gap:10px;}
.rc{flex:1;}
.rc input{display:none;}
.rc label{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;padding:14px 8px;border:1.5px solid var(--border);border-radius:12px;cursor:pointer;font-size:13px;font-weight:700;color:var(--muted);transition:all 0.2s;background:#fafbfc;}
.rc label i{font-size:22px;}
.rc input:checked+label{background:var(--p);color:#fff;border-color:var(--p);box-shadow:0 6px 16px rgba(13,33,55,0.2);}
.rc input:checked+label i{color:var(--al);}

/* ── SUBMIT ── */
.form-submit{padding:28px 36px;background:linear-gradient(135deg,#f0f4f9,#e8eef6);border-top:2px solid var(--border);}
.submit-btn{width:100%;padding:17px;background:linear-gradient(135deg,var(--a),#d97a08);color:#fff;border:none;border-radius:13px;font-size:16px;font-weight:900;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:9px;transition:all 0.25s;box-shadow:0 8px 26px rgba(245,166,35,0.45);font-family:inherit;position:relative;overflow:hidden;}
.submit-btn::after{content:'';position:absolute;inset:0;background:linear-gradient(rgba(255,255,255,0.1),transparent);pointer-events:none;}
.submit-btn:hover{transform:translateY(-2px);box-shadow:0 14px 34px rgba(245,166,35,0.55);}
.submit-btn:active{transform:translateY(0);}
.consent{display:flex;align-items:center;justify-content:center;gap:6px;margin-top:12px;font-size:12px;color:var(--muted);}
.consent i{color:var(--green);font-size:14px;}
.error-box{background:#fef2f2;border:1.5px solid #fecaca;border-radius:10px;padding:13px 15px;margin:20px 36px 0;color:var(--red);font-size:13px;display:flex;gap:8px;align-items:flex-start;}

/* ── SELECT2 ── */
.select2-container--bootstrap-5 .select2-selection{height:48px;padding:12px 14px;border-radius:12px;border-width:1.5px;}

/* ── PARTNERS ── */
.partners-wrap{padding:32px 36px 44px;}
.partners-head{display:flex;align-items:center;gap:12px;margin-bottom:20px;}
.partners-head::before,.partners-head::after{content:'';flex:1;height:1px;background:var(--border);}
.partners-head span{font-size:10px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:var(--muted);white-space:nowrap;padding:0 6px;}
.collab-banner{border-radius:16px;overflow:hidden;background:linear-gradient(135deg,var(--p) 0%,var(--pm) 55%,var(--pl) 100%);box-shadow:0 10px 36px rgba(13,33,55,0.14);position:relative;}
.collab-banner::before{content:'';position:absolute;top:-50px;right:-50px;width:220px;height:220px;border-radius:50%;border:50px solid rgba(255,255,255,0.04);pointer-events:none;}
.collab-banner::after{content:'';position:absolute;bottom:-70px;left:20%;width:260px;height:260px;border-radius:50%;border:50px solid rgba(245,166,35,0.06);pointer-events:none;}
.collab-inner{padding:28px 32px;position:relative;z-index:1;}
.collab-label{display:inline-flex;align-items:center;gap:6px;background:rgba(245,166,35,0.15);border:1px solid rgba(245,166,35,0.35);color:var(--al);font-size:10px;font-weight:800;letter-spacing:1.2px;text-transform:uppercase;padding:4px 13px;border-radius:30px;margin-bottom:12px;}
.collab-heading{font-size:18px;font-weight:900;color:#fff;line-height:1.3;margin-bottom:10px;}
.collab-text{font-size:13.5px;color:rgba(255,255,255,0.65);line-height:1.8;margin-bottom:0;max-width:620px;}
.collab-text strong{color:#fff;font-weight:800;}
.collab-divider{height:1px;background:rgba(255,255,255,0.1);margin:20px 0;}
.collab-logos{display:flex;align-items:center;gap:12px;flex-wrap:wrap;}
.collab-logo{display:flex;align-items:center;gap:12px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.13);border-radius:13px;padding:13px 16px;flex:1;min-width:150px;transition:all 0.2s;user-select:none;}
.collab-logo:hover{background:rgba(255,255,255,0.13);transform:translateY(-1px);}
.clogo-icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:19px;flex-shrink:0;background:rgba(255,255,255,0.9);overflow:hidden;padding:2px;}
.mercycorps .clogo-icon{background:rgba(255,255,255,0.92);}
.oscarop .clogo-icon{background:rgba(255,255,255,0.92);}
.clogo-name{font-size:14px;font-weight:800;color:#fff;line-height:1.1;}
.clogo-role{font-size:11px;color:rgba(255,255,255,0.45);margin-top:2px;}
.collab-x{width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.4);font-size:13px;flex-shrink:0;}
.rp-footer{text-align:center;padding:0 36px 28px;font-size:12px;color:var(--muted);}

/* ── TRUST + RECAPTCHA ── */
.trust-row{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:18px;}
.trust-item{display:flex;align-items:center;gap:6px;font-size:12px;font-weight:600;color:var(--muted);background:#f0f9f4;border:1px solid #c6f0db;border-radius:20px;padding:5px 12px;}
.trust-item i{color:var(--green);font-size:13px;}
.recaptcha-wrap{margin-bottom:18px;display:flex;flex-direction:column;align-items:flex-start;gap:8px;}
.recaptcha-wrap .g-recaptcha{transform-origin:left top;}
.recaptcha-err{display:flex;align-items:center;gap:6px;font-size:12px;color:var(--red);font-weight:600;}
.recaptcha-err i{font-size:14px;}
/* scale recaptcha on very small screens */
@media(max-width:360px){
  .recaptcha-wrap .g-recaptcha{transform:scale(0.88);}
}

/* ── ACTION STRIP (call center + play store) ── */
.action-strip{display:flex;flex-direction:column;gap:12px;margin-top:20px;}
.action-btn{display:flex;align-items:center;gap:14px;padding:16px 20px;border-radius:14px;text-decoration:none;transition:all 0.2s;border:1.5px solid var(--border);background:var(--white);box-shadow:0 2px 8px rgba(13,33,55,0.06);}
.action-btn:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(13,33,55,0.12);}
.action-ico{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
.call-btn .action-ico{background:linear-gradient(135deg,#10b981,#059669);color:#fff;box-shadow:0 4px 12px rgba(16,185,129,0.35);}
.play-btn .action-ico{background:linear-gradient(135deg,#1a73e8,#0f5bc4);color:#fff;box-shadow:0 4px 12px rgba(26,115,232,0.35);}
.action-body{flex:1;min-width:0;}
.action-title{font-size:14px;font-weight:800;color:var(--p);line-height:1.2;}
.call-btn .action-title{color:#065f46;}
.play-btn .action-title{color:#1e3a8a;}
.action-sub{font-size:12px;color:var(--muted);margin-top:2px;}
.action-arr{font-size:16px;color:var(--muted);flex-shrink:0;transition:transform 0.2s;}
.action-btn:hover .action-arr{transform:translateX(3px);}

/* ── RESPONSIVE ── */
@media(max-width:1100px){
  .left-panel{width:340px;padding:32px 26px;}
  .rp-content{padding:24px 24px 0;}
  .form-section,.form-submit{padding-left:24px;padding-right:24px;}
  .fc-banner,.fc-steps{padding-left:24px;padding-right:24px;}
  .error-box{margin-left:24px;margin-right:24px;}
  .partners-wrap{padding:24px 24px 36px;}
  .rp-footer{padding:0 24px 24px;}
  .rp-topbar{padding:0 24px;}
  .collab-inner{padding:24px 24px;}
}
@media(max-width:900px){
  .left-panel{width:300px;padding:28px 22px;}
  .brand-text .name{font-size:16px;}
  .lp-headline{font-size:22px;}
}
@media(max-width:768px){
  .left-panel{display:none;}
  .page-shell{min-height:100vh;}
  .right-panel{width:100%;}
  .rp-topbar{padding:0 16px;height:52px;}
  .rp-topbar-left{font-size:12px;}
  .rp-content{padding:16px 12px 0;}
  .form-section,.form-submit{padding:20px 16px;}
  .fc-banner{padding:20px 16px;}
  .fc-banner-left h2{font-size:18px;}
  .fc-steps{padding:0 16px;}
  .fc-step{min-width:60px;}
  .step-label{font-size:10px;}
  .error-box{margin:14px 0 0;}
  .sec-head{flex-wrap:wrap;gap:8px;}
  .dob-inline{border-radius:10px;}
  .dob-sel{padding:11px 4px;font-size:12px;}
  .partners-wrap{padding:16px 12px 32px;}
  .collab-inner{padding:20px 16px;}
  .collab-logos{flex-direction:column;}
  .collab-logo{width:100%;min-width:unset;}
  .rp-footer{padding:0 12px 20px;}
  .fc-banner-right{display:none;}
}
@media(max-width:480px){
  .rp-content{padding:12px 10px 0;}
  .form-section,.form-submit{padding:16px 14px;}
  .fc-banner{padding:16px 14px;}
  .inp{padding:12px 12px 12px 38px;font-size:13px;}
  .submit-btn{padding:15px;font-size:15px;}
  .dob-sel{padding:10px 3px;font-size:11px;}
  .rc label{padding:12px 6px;}
  .partners-wrap{padding:14px 10px 28px;}
  .collab-inner{padding:16px 14px;}
}
</style>
</head>
<body>

<!-- ═══════════════════════════════════════
     ONBOARDING SPLASH — shows every load
═══════════════════════════════════════ -->
<div id="splash">
  <div class="sp-inner">

    <!-- Logo + brand -->
    <div class="sp-logo"><img src="<?php echo base_url('my-assets/image/logo/oscar_logo1.png'); ?>" alt="Oscar Promotion" style="width:100%;height:100%;object-fit:contain;border-radius:18px;"></div>
    <div class="sp-brand-name">Oscar Promotion</div>

    <!-- Headline -->
    <div class="sp-title">Your Career<br><span>Starts Here</span></div>
    <p class="sp-desc">Fill in your information and connect with employers fast. Once registered, we will contact you for more detail information.</p>

    <!-- Journey steps -->
    <div class="sp-steps">
      <div class="sp-step">
        <div class="sp-step-ico sp-ico-1"><i class="bi bi-pencil-square"></i></div>
        <div class="sp-step-line"></div>
        <div class="sp-step-body">
          <div class="sp-step-title">Register Once</div>
          <div class="sp-step-sub">Fill your profile once. We keep searching for you.</div>
        </div>
      </div>
      <div class="sp-step">
        <div class="sp-step-ico sp-ico-2"><i class="bi bi-briefcase-fill"></i></div>
        <div class="sp-step-line"></div>
        <div class="sp-step-body">
          <div class="sp-step-title">We Find the Job</div>
          <div class="sp-step-sub">Oscar matches your skills to verified employer vacancies.</div>
        </div>
      </div>
      <div class="sp-step">
        <div class="sp-step-ico sp-ico-3"><i class="bi bi-telephone-outbound-fill"></i></div>
        <div class="sp-step-line"></div>
        <div class="sp-step-body">
          <div class="sp-step-title">We Call You</div>
          <div class="sp-step-sub">When there's a match, Oscar Promotion contacts you directly.</div>
        </div>
      </div>
      <div class="sp-step sp-step-last">
        <div class="sp-step-ico sp-ico-4"><i class="bi bi-gift-fill"></i></div>
        <div class="sp-step-body">
          <div class="sp-step-title">Always Free</div>
          <div class="sp-step-sub">No fees. No commissions. Just opportunities.</div>
        </div>
      </div>
    </div>

    <!-- Journey milestone bar -->
    <div class="sp-milestones">
      <div class="sp-mile"><div class="sp-mile-dot active"></div><div class="sp-mile-label">Register<br>Your Profile</div></div>
      <div class="sp-mile-line"></div>
      <div class="sp-mile"><div class="sp-mile-dot active"></div><div class="sp-mile-label">We Match<br>Your Skills</div></div>
      <div class="sp-mile-line"></div>
      <div class="sp-mile"><div class="sp-mile-dot"></div><div class="sp-mile-label">Get<br>Hired</div></div>
    </div>

    <!-- Security trust pills -->
    <div class="sp-trust">
      <div class="sp-trust-pill"><i class="bi bi-shield-lock-fill"></i> Your data is secure &amp; encrypted</div>
      <div class="sp-trust-pill"><i class="bi bi-person-check-fill"></i> Real information required</div>
      <div class="sp-trust-pill"><i class="bi bi-eye-slash-fill"></i> Never shared without consent</div>
    </div>

    <!-- CTA -->
    <button class="sp-cta" id="splashBtn">
      <i class="bi bi-pencil-square"></i> Register Now <i class="bi bi-arrow-right"></i>
    </button>
    <div class="sp-foot">Developed &amp; operated by <strong>Oscar Promotion</strong></div>
  </div>
</div>

<!-- ═══════════════════════════════════════
     MAIN PAGE
═══════════════════════════════════════ -->
<div class="page-shell">

  <!-- LEFT PANEL (desktop) -->
  <aside class="left-panel">
    <div class="lp-top">
      <div class="brand">
        <div class="brand-icon"><img src="<?php echo base_url('my-assets/image/logo/oscar_logo1.png'); ?>" alt="Oscar Promotion" style="width:44px;height:44px;object-fit:contain;border-radius:10px;display:block;"></div>
        <div class="brand-text"><div class="name">Oscar Promotion</div><div class="tag">Recruitment &amp; Placement Agency</div></div>
      </div>
      <h1 class="lp-headline" id="hero-title">Your Career<br>Starts <span>Here</span></h1>
      <p class="lp-desc" data-k="heroDesc">Oscar Promotion finds the right job for you. Register your details, and when a matching employer posts a vacancy — we contact you directly. No searching. No waiting alone.</p>
      <div class="feature-list">
        <div class="feat"><div class="feat-icon"><i class="bi bi-pencil-square"></i></div><div class="feat-body"><div class="ftitle">Register Once</div><div class="fdesc">Fill your profile once. We keep searching for you.</div></div></div>
        <div class="feat"><div class="feat-icon"><i class="bi bi-briefcase-fill"></i></div><div class="feat-body"><div class="ftitle">We Find the Job</div><div class="fdesc">Oscar matches your skills to verified employer vacancies.</div></div></div>
        <div class="feat"><div class="feat-icon"><i class="bi bi-telephone-outbound-fill"></i></div><div class="feat-body"><div class="ftitle">We Call You</div><div class="fdesc">When there's a match, Oscar Promotion contacts you directly.</div></div></div>
        <div class="feat"><div class="feat-icon"><i class="bi bi-shield-lock-fill"></i></div><div class="feat-body"><div class="ftitle">Always Free</div><div class="fdesc">No fees. No commissions. Just opportunities.</div></div></div>
      </div>
      <div class="stats-row">
        <div class="stat"><span class="n" style="font-size:12px;letter-spacing:0;">Register</span><span class="l">Your Profile</span></div>
        <div class="stat"><span class="n" style="font-size:12px;letter-spacing:0;">We Match</span><span class="l">Your Skills</span></div>
        <div class="stat"><span class="n" style="font-size:12px;letter-spacing:0;">Get Hired</span><span class="l">We Call You</span></div>
      </div>
    </div>
    <div class="lp-bottom"><div class="lp-footer">&copy; <?php echo date('Y'); ?> Oscar Promotion &middot; All rights reserved</div></div>
  </aside>

  <!-- RIGHT PANEL -->
  <main class="right-panel">
    <div class="rp-topbar">
      <div class="rp-topbar-left">Welcome &mdash; <span>Start your journey today</span></div>
      <button class="lang-btn" id="langBtn" data-lang="en"><i class="bi bi-translate"></i> ትግርኛ</button>
    </div>
    <div class="rp-content">
      <div class="form-card">
        <div class="fc-banner">
          <div class="fc-banner-left">
            <h2 data-k="formTitle">Job Seeker Registration</h2>
            <p data-k="formDesc">All fields marked * are required.</p>
          </div>
          <div class="fc-banner-right">
            <div class="fc-badge"><i class="bi bi-shield-check"></i> Secure</div>
            <div class="fc-badge"><i class="bi bi-gift"></i> Free</div>
          </div>
        </div>
        <div class="fc-steps">
          <div class="fc-step done"><div class="step-num">1</div><div class="step-label">Personal Info</div></div>
          <div class="fc-step done"><div class="step-num">2</div><div class="step-label">Education</div></div>
          <div class="fc-step done"><div class="step-num">3</div><div class="step-label">Location</div></div>
          <div class="fc-step"><div class="step-num">4</div><div class="step-label">Submit</div></div>
        </div>

        <?php if(!empty($errors)): ?>
        <div class="error-box"><i class="bi bi-exclamation-circle-fill"></i><span><?php echo $errors; ?></span></div>
        <?php endif; ?>

        <form action="<?php echo base_url('JobSeeker/submit'); ?>" method="post">
          <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>

          <!-- SECTION 1 -->
          <div class="form-section s1">
            <div class="sec-head">
              <div class="sec-num">1</div>
              <div class="sec-info"><h3 data-k="personalDetails">Personal Details</h3><p>Basic information about you</p></div>
              <div class="sec-pill">Required</div>
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <div class="fld">
                  <label><i class="bi bi-person-fill"></i><span data-k="fullName">Full Name</span><span class="req">*</span></label>
                  <input type="text" class="inp" name="full_name" value="<?php echo set_value('full_name'); ?>" data-kp="fullNamePh" placeholder="Enter your full name" required>
                  <i class="bi bi-person ico"></i>
                </div>
              </div>
              <div class="col-md-6">
                <div class="fld">
                  <label><i class="bi bi-telephone-fill"></i><span data-k="phone">Phone Number</span><span class="req">*</span></label>
                  <input type="tel" class="inp" name="phone_number" value="<?php echo set_value('phone_number'); ?>" data-kp="phonePh" placeholder="e.g. 0912345678" required>
                  <i class="bi bi-telephone ico"></i>
                </div>
              </div>
              <div class="col-md-8">
                <div class="fld">
                  <label><i class="bi bi-calendar3"></i><span data-k="dob">Date of Birth (Ethiopian)</span><span class="req">*</span></label>
                  <div class="dob-inline">
                    <select class="inp dob-sel" id="dob_day" required>
                      <option value="" selected disabled>DD</option>
                      <?php for($d=1;$d<=30;$d++) printf('<option value="%d">%02d</option>',$d,$d); ?>
                    </select>
                    <span class="dob-sep">/</span>
                    <select class="inp dob-sel" id="dob_month" required>
                      <option value="" selected disabled>MM</option>
                      <option value="1">01 — Meskerem</option><option value="2">02 — Tikemet</option>
                      <option value="3">03 — Hidar</option><option value="4">04 — Tahsas</option>
                      <option value="5">05 — Tir</option><option value="6">06 — Yekatit</option>
                      <option value="7">07 — Megabit</option><option value="8">08 — Miyazia</option>
                      <option value="9">09 — Ginbot</option><option value="10">10 — Sene</option>
                      <option value="11">11 — Hamle</option><option value="12">12 — Nehase</option>
                    </select>
                    <span class="dob-sep">/</span>
                    <select class="inp dob-sel" id="dob_year" required>
                      <option value="" selected disabled>YYYY</option>
                      <?php for($y=2010;$y>=1960;$y--) echo "<option value='$y'>$y</option>"; ?>
                    </select>
                    <input type="hidden" name="dob_ethiopian" id="dob_ethiopian" value="<?php echo set_value('dob_ethiopian'); ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="fld">
                  <label><i class="bi bi-person-badge"></i><span data-k="sex">Sex</span><span class="req">*</span></label>
                  <div class="inp-wrap">
                    <i class="bi bi-gender-ambiguous ico"></i>
                    <select class="inp" name="sex" id="sex_select" required>
                      <option value="" selected disabled>Select sex</option>
                      <option value="Male" <?php echo set_select('sex','Male'); ?>>Male</option>
                      <option value="Female" <?php echo set_select('sex','Female'); ?>>Female</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- SECTION 2 -->
          <div class="form-section s2">
            <div class="sec-head">
              <div class="sec-num">2</div>
              <div class="sec-info"><h3 data-k="education">Education &amp; Experience</h3><p>Your qualifications and work history</p></div>
              <div class="sec-pill">Required</div>
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <div class="fld">
                  <label><i class="bi bi-mortarboard-fill"></i><span data-k="eduLevel">Education Level</span><span class="req">*</span></label>
                  <select class="inp" name="education_level" id="education_level" style="padding-left:12px;" required>
                    <option value="" selected disabled>Select education level...</option>
                    <?php if(!empty($educational_levels)): foreach($educational_levels as $lvl): ?>
                    <option value="<?php echo $lvl['id']; ?>" <?php echo set_select('education_level',(string)$lvl['id']); ?>><?php echo htmlspecialchars($lvl['level']); ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="fld">
                  <label><i class="bi bi-briefcase-fill"></i><span data-k="experience">Years of Experience</span><span class="req">*</span></label>
                  <input type="number" class="inp" name="experience" value="<?php echo set_value('experience'); ?>" min="0" max="50" placeholder="0" required>
                  <i class="bi bi-briefcase ico"></i>
                </div>
              </div>
              <div class="col-12">
                <div class="fld">
                  <label><i class="bi bi-search"></i><span data-k="qualification">Job You're Looking For</span><span class="req">*</span></label>
                  <input type="text" class="inp" name="qualification_skills" value="<?php echo set_value('qualification_skills'); ?>" data-kp="qualificationPh" placeholder="e.g. Accountant, Nurse, Driver" required>
                  <i class="bi bi-search ico"></i>
                </div>
              </div>
            </div>
          </div>

          <!-- SECTION 3 -->
          <div class="form-section s3">
            <div class="sec-head">
              <div class="sec-num">3</div>
              <div class="sec-info"><h3 data-k="location">Location</h3><p>Where are you currently based?</p></div>
              <div class="sec-pill">Required</div>
            </div>
            <div class="fld">
              <label><i class="bi bi-geo-alt-fill"></i><span data-k="locationLabel">Location / Address</span><span class="req">*</span></label>
              <textarea class="inp" name="location" data-kp="locationPh" placeholder="City, Sub-city or Woreda" required><?php echo set_value('location'); ?></textarea>
              <i class="bi bi-geo-alt ico top"></i>
            </div>
          </div>

          <!-- SUBMIT -->
          <div class="form-submit">
            <input type="hidden" name="age" id="age" value="0">

            <!-- Security trust row -->
            <div class="trust-row">
              <div class="trust-item"><i class="bi bi-shield-lock-fill"></i><span>Your data is secure &amp; encrypted</span></div>
              <div class="trust-item"><i class="bi bi-person-check-fill"></i><span>Real information required</span></div>
              <div class="trust-item"><i class="bi bi-eye-slash-fill"></i><span>Never shared without consent</span></div>
            </div>

            <!-- reCAPTCHA -->
            <div class="recaptcha-wrap">
              <div class="g-recaptcha"
                data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"
                data-theme="light"
                data-size="normal">
              </div>
              <?php if(isset($recaptcha_error)): ?>
              <div class="recaptcha-err"><i class="bi bi-exclamation-triangle-fill"></i> <?php echo $recaptcha_error; ?></div>
              <?php endif; ?>
            </div>

            <button type="submit" class="submit-btn">
              <i class="bi bi-send-check-fill"></i>
              <span data-k="submit">Submit Registration</span>
              <i class="bi bi-arrow-right-circle"></i>
            </button>
            <p class="consent"><i class="bi bi-shield-fill-check"></i><span data-k="consent">By submitting, you confirm your details are accurate.</span></p>
          </div>
        </form>
      </div><!-- /form-card -->

      <!-- PARTNERS -->
      <div class="partners-wrap">
        <div class="partners-head"><span>Our Partners</span></div>
        <div class="collab-banner">
          <div class="collab-inner">
            <div class="collab-label"><i class="bi bi-patch-check-fill"></i> Platform Partnership</div>
            <h3 class="collab-heading">Built for impact.<br>Backed by trust.</h3>
            <p class="collab-text">This platform is developed and operated by <strong>Oscar Promotion</strong> in collaboration with <strong>Mercy Corps</strong> — dedicated to creating inclusive employment opportunities and connecting job seekers with real employers across Ethiopia.</p>
            <div class="collab-divider"></div>
            <div class="collab-logos">
              <div class="collab-logo mercycorps">
                <div class="clogo-icon"><img src="<?php echo base_url('my-assets/image/logo/mercy_crops.png'); ?>" alt="Mercy Corps" style="width:38px;height:38px;object-fit:contain;border-radius:6px;"></div>
                <div><div class="clogo-name">Mercy Corps</div><div class="clogo-role">Implementation Partner</div></div>
              </div>
              <div class="collab-x"><i class="bi bi-link-45deg"></i></div>
              <div class="collab-logo oscarop">
                <div class="clogo-icon"><img src="<?php echo base_url('my-assets/image/logo/oscar_logo1.png'); ?>" alt="Oscar Promotion" style="width:38px;height:38px;object-fit:contain;border-radius:6px;"></div>
                <div><div class="clogo-name">Oscar Promotion</div><div class="clogo-role">Developed &amp; Operated By</div></div>
              </div>
            </div>
          </div>
        </div>

        <!-- CONTACT & APP ACTIONS -->
        <div class="action-strip">
          <a href="tel:6558" class="action-btn call-btn">
            <div class="action-ico"><i class="bi bi-telephone-fill"></i></div>
            <div class="action-body">
              <div class="action-title">Call Center</div>
              <div class="action-sub">6558 &mdash; Free to call</div>
            </div>
            <i class="bi bi-arrow-right action-arr"></i>
          </a>
          <a href="https://play.google.com/store/apps/details?id=com.oscar.jobs&pcampaignid=web_share" target="_blank" rel="noopener" class="action-btn play-btn">
            <div class="action-ico"><i class="bi bi-google-play"></i></div>
            <div class="action-body">
              <div class="action-title">Oscar Jobs App</div>
              <div class="action-sub">Download on Google Play</div>
            </div>
            <i class="bi bi-arrow-right action-arr"></i>
          </a>
        </div>
      </div>

      <div class="rp-footer">&copy; <?php echo date('Y'); ?> Oscar Promotion &mdash; Connecting talent with opportunity across Ethiopia</div>
    </div><!-- /rp-content -->
  </main>
</div><!-- /page-shell -->

<script>
// DOB
$('#dob_day,#dob_month,#dob_year').on('change',function(){
  var d=$('#dob_day').val(),m=$('#dob_month').val(),y=$('#dob_year').val();
  if(d&&m&&y){
    $('#dob_ethiopian').val(y+'-'+m+'-'+d);
    var age=(new Date().getFullYear()-7)-parseInt(y);
    if(parseInt(m)>new Date().getMonth()+1)age--;
    if(age>=0)$('#age').val(age);
  }
});

// Onboarding splash — mobile only, shows every page load
(function(){
  if(window.innerWidth > 768) return;
  var splash = document.getElementById('splash');
  var btn    = document.getElementById('splashBtn');
  if(!splash) return;

  // Add extra top padding for status bar on Android (approx 24-32px)
  var extraTop = Math.max(window.screen.height - window.innerHeight, 0);
  if(extraTop > 0 && extraTop < 80){
    var curr = parseInt(getComputedStyle(splash).paddingTop) || 60;
    splash.style.paddingTop = (curr + Math.min(extraTop, 30)) + 'px';
  }

  document.body.style.overflow = 'hidden';

  function dismiss(){
    splash.classList.add('hide');
    document.body.style.overflow = '';
    setTimeout(function(){ splash.style.display = 'none'; }, 580);
  }

  if(btn) btn.addEventListener('click', dismiss);
  setTimeout(dismiss, 3500);
})();
</script>
</body>
</html>
