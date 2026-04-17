<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registration Successful &mdash; Oscar Promotion</title>
<link rel="icon" type="image/png" href="<?php echo base_url('my-assets/image/logo/oscar_logo1.png'); ?>">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{
    --primary:#0f2a4a;
    --primary-mid:#1a3a5c;
    --accent:#f5a623;
    --accent-light:#fbbf24;
    --bg:#f0f4f8;
    --white:#ffffff;
    --text:#1e293b;
    --text-muted:#64748b;
    --border:#e2e8f0;
    --success:#10b981;
}
*{box-sizing:border-box;margin:0;padding:0;}
body{
    font-family:'Plus Jakarta Sans',sans-serif;
    background:var(--bg);color:var(--text);
    min-height:100vh;display:flex;flex-direction:column;
    align-items:center;justify-content:center;padding:24px;
    -webkit-font-smoothing:antialiased;
}

/* Confetti dots in background */
body::before{
    content:'';position:fixed;inset:0;z-index:0;pointer-events:none;
    background-image:
        radial-gradient(circle,rgba(245,166,35,0.12) 1px,transparent 1px),
        radial-gradient(circle,rgba(26,58,92,0.08) 1px,transparent 1px);
    background-size:40px 40px,60px 60px;
    background-position:0 0,20px 20px;
}

.card{
    position:relative;z-index:1;
    background:var(--white);border-radius:22px;
    padding:48px 40px 40px;max-width:500px;width:100%;
    text-align:center;
    box-shadow:0 24px 64px rgba(15,42,74,0.14);
}

/* Success ring animation */
.success-ring{
    width:86px;height:86px;border-radius:50%;
    background:linear-gradient(135deg,var(--primary),var(--primary-mid));
    display:flex;align-items:center;justify-content:center;
    margin:0 auto 28px;
    box-shadow:0 0 0 12px rgba(16,185,129,0.1),0 8px 28px rgba(15,42,74,0.25);
    animation:popIn 0.5s cubic-bezier(0.34,1.56,0.64,1) both;
}
@keyframes popIn{from{transform:scale(0.5);opacity:0}to{transform:scale(1);opacity:1}}
.success-ring svg{width:38px;height:38px;}
.check-path{stroke-dasharray:60;stroke-dashoffset:60;animation:drawCheck 0.45s 0.4s ease forwards;}
@keyframes drawCheck{to{stroke-dashoffset:0}}

h1{font-size:26px;font-weight:800;color:var(--primary);margin-bottom:8px;}
.subtitle{font-size:14px;color:var(--text-muted);line-height:1.65;margin-bottom:28px;}
.subtitle strong{color:var(--text);}

/* ID Box */
.id-card{
    background:linear-gradient(135deg,var(--primary) 0%,var(--primary-mid) 100%);
    border-radius:16px;padding:24px;margin-bottom:24px;
    position:relative;overflow:hidden;
}
.id-card::after{
    content:'';position:absolute;top:-20px;right:-20px;
    width:100px;height:100px;border-radius:50%;
    background:rgba(255,255,255,0.05);
}
.id-label{
    font-size:10px;font-weight:800;letter-spacing:2px;
    text-transform:uppercase;color:rgba(255,255,255,0.5);
    margin-bottom:8px;
}
.id-value{
    font-size:42px;font-weight:900;color:var(--accent-light);
    letter-spacing:6px;line-height:1;
}
.id-hint{font-size:12px;color:rgba(255,255,255,0.45);margin-top:8px;}

/* Steps */
.steps-box{
    background:#f8fafc;border:1px solid var(--border);
    border-radius:14px;padding:20px 22px;margin-bottom:24px;
    text-align:left;
}
.steps-title{
    font-size:11px;font-weight:800;letter-spacing:1.5px;
    text-transform:uppercase;color:var(--text-muted);margin-bottom:16px;
}
.step{display:flex;align-items:flex-start;gap:14px;margin-bottom:14px;}
.step:last-child{margin-bottom:0;}
.step-num{
    width:26px;height:26px;border-radius:50%;
    background:var(--primary);color:var(--accent-light);
    font-size:11px;font-weight:800;
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;margin-top:1px;
}
.step-info{}
.step-info strong{font-size:13px;color:var(--text);display:block;margin-bottom:2px;}
.step-info span{font-size:12px;color:var(--text-muted);line-height:1.45;}

/* Action buttons */
.btn-primary-op{
    display:inline-flex;align-items:center;gap:8px;
    width:100%;justify-content:center;
    padding:14px;background:linear-gradient(135deg,var(--accent),#e8940f);
    color:#fff;border-radius:11px;font-size:14px;font-weight:700;
    text-decoration:none;transition:all 0.25s;
    box-shadow:0 6px 20px rgba(245,166,35,0.3);
    margin-bottom:10px;
}
.btn-primary-op:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(245,166,35,0.4);color:#fff;}
.btn-ghost{
    display:inline-flex;align-items:center;gap:7px;
    width:100%;justify-content:center;
    padding:12px;background:transparent;
    color:var(--primary-mid);border:1.5px solid var(--border);border-radius:11px;
    font-size:14px;font-weight:600;text-decoration:none;transition:all 0.2s;
}
.btn-ghost:hover{background:var(--bg);border-color:var(--primary-mid);color:var(--primary);}

footer{margin-top:28px;font-size:12px;color:var(--text-muted);}

@media(max-width:480px){
    .card{padding:36px 22px 28px;}
    .id-value{font-size:34px;letter-spacing:4px;}
}
</style>
</head>
<body>

<div class="card">
    <div class="success-ring">
        <svg viewBox="0 0 38 38" fill="none">
            <path class="check-path" d="M9 19l8 8 12-12" stroke="#f5a623" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>

    <h1>You're Registered!</h1>
    <p class="subtitle">
        Welcome, <strong><?php echo htmlspecialchars($name); ?></strong>. Successfully registered! We will contact you for more detail information when a matching opportunity is found.
    </p>

    <div class="id-card">
        <div class="id-label">Your Seeker ID</div>
        <div class="id-value"><?php echo $seeker_id; ?></div>
        <div class="id-hint">Save this ID &mdash; you may need it for follow-up</div>
    </div>

    <div class="steps-box">
        <div class="steps-title">What happens next</div>
        <div class="step">
            <div class="step-num">1</div>
            <div class="step-info">
                <strong>Profile Review</strong>
                <span>Our recruitment team reviews your submitted information.</span>
            </div>
        </div>
        <div class="step">
            <div class="step-num">2</div>
            <div class="step-info">
                <strong>Employer Matching</strong>
                <span>We match you with suitable employers in your area.</span>
            </div>
        </div>
        <div class="step">
            <div class="step-num">3</div>
            <div class="step-info">
                <strong>We Contact You</strong>
                <span>You'll be called or messaged when a match is found.</span>
            </div>
        </div>
    </div>

    <a href="<?php echo base_url('register'); ?>" class="btn-primary-op">
        <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M7.5 2v11M2 7.5h11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        Register Another Person
    </a>
    <a href="<?php echo base_url(); ?>" class="btn-ghost">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M11 7H3M6 4L3 7l3 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Back to Home
    </a>
</div>

<footer>&copy; <?php echo date('Y'); ?> Oscar Promotion &middot; All rights reserved</footer>

</body>
</html>
