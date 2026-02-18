<style>
  html,body{height:100%;margin:0}
  .auth-bg{min-height:100vh;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden}
  .auth-bg .auth-card{max-width:520px;width:100%;background:#ffffff;border-radius:12px;padding:32px;box-shadow:0 20px 40px rgba(23,23,48,0.18);z-index:2}

  /* animated gradient */
  .auth-animated-bg{position:fixed;inset:0;z-index:0;background:linear-gradient(120deg,#6a11cb 0%,#8e54e9 35%,#2575fc 100%);animation:shift 8s ease infinite;background-size:400% 400%}
  @keyframes shift{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}

  /* soft decorative blobs */
  .auth-blob{position:absolute;border-radius:50%;filter:blur(60px);opacity:0.32;transform:translate3d(0,0,0)}
  .auth-blob.b1{width:420px;height:420px;left:-120px;top:-100px;background:radial-gradient(circle at 30% 30%, rgba(255,255,255,0.12), transparent 40%), rgba(255,255,255,0.02)}
  .auth-blob.b2{width:360px;height:360px;right:-80px;bottom:-80px;background:radial-gradient(circle at 70% 70%, rgba(255,255,255,0.12), transparent 40%), rgba(0,0,0,0.04)}

  /* make form inputs a bit larger and airy */
  .auth-card .form-control{height:54px;padding:.75rem 1rem;border-radius:8px}
  .auth-logo{text-align:center;margin-bottom:16px}
  .auth-logo img{height:56px}
  .auth-title{font-weight:700;margin-bottom:6px}
  .auth-subtitle{color:#6c757d;margin-bottom:18px}

  @media (max-width:576px){.auth-card{padding:20px;margin:16px}}
</style>

<div class="auth-animated-bg" aria-hidden="true"></div>
<div class="auth-blob b1" aria-hidden="true"></div>
<div class="auth-blob b2" aria-hidden="true"></div>
