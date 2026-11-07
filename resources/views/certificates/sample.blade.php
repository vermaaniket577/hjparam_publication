<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>HJPARAM Publication Certificate - Sample Preview</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400;1,700&family=Arimo:wght@400;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --navy:#01448B; --blue:#1B5FA8; --teal:#27A8A4; --green:#5A9E3A; --grey:#555;
    --serif:'Times New Roman',Tinos,Times,serif; --sans:Arial,Arimo,Helvetica,sans-serif;
  }
  @page{ size:A4 landscape; margin:0; }
  *{ box-sizing:border-box; }
  html,body{ margin:0; background:#e9edf2; }
  body{ font-family:var(--serif); color:#111; -webkit-print-color-adjust:exact; print-color-adjust:exact; }

  /* ---------- toolbar (screen only) ---------- */
  .toolbar{ position:sticky; top:0; z-index:50; display:flex; gap:12px; align-items:center; justify-content:center;
    flex-wrap:wrap; padding:12px 18px; background:#ffffff; border-bottom:1px solid #d5dbe3; font-family:var(--sans); font-size:13px; color:#333; box-shadow:0 2px 8px rgba(0,0,0,0.06); }
  .toolbar a.back-btn{ text-decoration:none; color:#475569; font-weight:600; padding:8px 14px; border-radius:6px; border:1px solid #cbd5e1; background:#f8fafc; transition:all 0.2s; }
  .toolbar a.back-btn:hover{ background:#f1f5f9; color:#0f172a; }
  .toolbar button{ font:inherit; font-weight:700; color:#fff; background:var(--navy); border:0; border-radius:6px; padding:8px 18px; cursor:pointer; transition:background 0.2s; box-shadow:0 2px 4px rgba(1,68,139,0.25); }
  .toolbar button:hover{ background:var(--blue); }
  .toolbar .hint{ color:#64748b; font-size:12px; }

  /* ---------- page ---------- */
  .stage{ padding:28px 16px; display:flex; justify-content:center; }
  .scaler{ transform-origin:top center; }
  .page{ position:relative; width:297mm; height:210mm; background:#fff; overflow:hidden; box-shadow:0 8px 30px rgba(0,0,0,.15); }

  /* borders */
  .frame{ position:absolute; inset:8mm; border:2.4pt solid; border-color:var(--navy) var(--green) var(--green) var(--navy); }
  .frame-inner{ position:absolute; inset:11mm; border:0.6pt solid var(--teal); }
  .dot{ position:absolute; width:3.2mm; height:3.2mm; border-radius:50%; background:var(--blue); }
  .dot.tl{ top:6.4mm; left:6.4mm } .dot.tr{ top:6.4mm; right:6.4mm } .dot.bl{ bottom:6.4mm; left:6.4mm } .dot.br{ bottom:6.4mm; right:6.4mm }
  .watermark{ position:absolute; left:50%; top:50%; height:110mm; transform:translate(-50%,-50%); opacity:.07; pointer-events:none; }

  .content{ position:absolute; inset:17mm 22mm 15mm; display:flex; flex-direction:column; }

  /* top band */
  .top{ display:flex; justify-content:space-between; align-items:center; }
  .brand{ display:flex; align-items:center; gap:3mm; font-family:var(--sans); font-size:20pt; }
  .brand img{ height:17mm; width:auto; }
  .brand b{ color:var(--navy); } .brand span{ color:var(--green); }
  .meta{ text-align:right; font-family:var(--sans); font-size:8.5pt; color:var(--grey); line-height:1.5; }
  .rule{ height:1.2pt; background:var(--blue); margin-top:2.5mm; }
  .rule.thin{ height:.6pt; background:var(--green); margin-top:.8mm; }

  /* body */
  .body{ text-align:center; flex:1; display:flex; flex-direction:column; justify-content:center; }
  .title{ font-size:40pt; font-weight:700; color:var(--navy); letter-spacing:.18em; margin:0; line-height:1; padding-left:.18em; }
  .subtitle{ font-family:var(--sans); font-weight:700; font-size:12pt; letter-spacing:.35em; color:var(--teal); margin-top:2mm; padding-left:.35em; }
  .lead{ font-style:italic; font-size:13pt; color:var(--grey); margin:6mm 0 2mm; }
  .lead.second{ margin-top:4.5mm; }
  .name{ font-size:26pt; font-weight:700; font-style:italic; color:var(--blue); line-height:1.15; }
  .affil{ font-size:10.5pt; color:var(--grey); margin-top:1mm; }
  .paper{ font-size:16pt; font-weight:700; max-width:78%; margin:0 auto; line-height:1.25; }
  .journal{ font-size:13pt; margin-top:4.5mm; }
  .journal b{ color:var(--navy); }
  .ids{ font-size:10pt; color:var(--grey); margin-top:1mm; }
  .note{ font-size:10pt; font-style:italic; max-width:72%; margin:3.5mm auto 0; }

  /* bottom */
  .bottom{ display:flex; justify-content:space-between; align-items:flex-end; }
  .seal{ width:30%; text-align:center; font-family:var(--sans); font-size:9.5pt; color:var(--grey); }
  .seal img{ height:16mm; width:auto; display:block; margin:0 auto 1.5mm; }
  .sign{ width:34%; text-align:center; }
  .sign img{ height:16mm; width:auto; display:block; margin:0 auto -1mm; }
  .sign .line{ height:.6pt; background:var(--navy); width:85%; margin:0 auto 1.2mm; }
  .sign .who{ font-weight:700; font-size:13pt; }
  .sign .role{ font-family:var(--sans); font-weight:700; font-size:9.5pt; color:var(--navy); }
  .sign .org{ font-family:var(--sans); font-size:7.5pt; color:var(--grey); }
  .foot{ margin-top:4mm; border-top:.5pt solid var(--teal); padding-top:1.5mm; text-align:center;
    font-family:var(--sans); font-weight:700; font-size:9pt; color:var(--navy); }

  /* editable fields */
  [contenteditable]{ outline:none; border-radius:2px; }
  @media screen{
    [contenteditable]:hover{ background:rgba(39,168,164,.10); box-shadow:0 0 0 1px rgba(39,168,164,.45); }
    [contenteditable]:focus{ background:rgba(27,95,168,.08); box-shadow:0 0 0 1px var(--blue); }
  }

  @media print{
    html,body{ background:#fff; }
    .toolbar{ display:none; }
    .stage{ padding:0; display:block; }
    .scaler{ transform:none !important; height:auto !important; }
    .page{ box-shadow:none; }
  }
</style>
</head>
<body>

<div class="toolbar">
  <a href="{{ url('/') }}" class="back-btn">&larr; Back to Portal</a>
  <span class="hint">Click any text on the certificate to edit it, then</span>
  <button type="button" onclick="window.print()">Print / Save as PDF</button>
  <span class="hint">(choose A4 &middot; Landscape &middot; Margins: None &middot; Background graphics on)</span>
</div>

<div class="stage">
 <div class="scaler" id="scaler">
  <div class="page">
    <img class="watermark" src="{{ asset('images/logo.png') }}" onerror="this.src='/images/logo.png'" alt="">
    <div class="frame"></div><div class="frame-inner"></div>
    <i class="dot tl"></i><i class="dot tr"></i><i class="dot bl"></i><i class="dot br"></i>

    <div class="content">
      <div class="top">
        <div class="brand">
          <img src="{{ asset('images/logo.png') }}" onerror="this.src='/images/logo.png'" alt="HJPARAM logo">
          <div><b>HJPARAM</b> <span>Publication</span></div>
        </div>
        <div class="meta">
          Certificate No.: <span contenteditable="true">HJP/XXXX/2026/XXXX</span><br>
          Manuscript ID: <span contenteditable="true">XXXX-XXXX</span>
        </div>
      </div>
      <div class="rule"></div><div class="rule thin"></div>

      <div class="body">
        <h1 class="title">CERTIFICATE</h1>
        <div class="subtitle">OF PUBLICATION</div>

        <div class="lead">This is to certify that</div>
        <div class="name" contenteditable="true">[Author Name]</div>
        <div class="affil" contenteditable="true">[Designation], [Department], [Institution], [City, Country]</div>

        <div class="lead second">has successfully published the research paper entitled</div>
        <div class="paper" contenteditable="true">&ldquo;[Title of the Research Paper]&rdquo;</div>

        <div class="journal">in <b contenteditable="true">[Journal Name]</b>, <span contenteditable="true">Volume X, Issue X, Month YYYY, pp. XX&ndash;XX</span></div>
        <div class="ids">ISSN: <span contenteditable="true">XXXX-XXXX</span> (Online) &nbsp;|&nbsp; DOI: <span contenteditable="true">10.XXXXX/hjparam.XXXX</span></div>
        <div class="note" contenteditable="true">The paper was peer reviewed and found suitable for publication. We congratulate the author(s) and wish them continued success in their research.</div>
      </div>

      <div class="bottom">
        <div class="seal">
          <img src="{{ asset('images/logo.png') }}" onerror="this.src='/images/logo.png'" alt="">
          Date of Issue: <span contenteditable="true">DD Month YYYY</span>
        </div>
        <div class="sign">
          <img src="data:image/png;base64,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" alt="">
          <div class="line"></div>
          <div class="who">Dr Ayushi Mishra</div>
          <div class="role">Managing Editor</div>
          <div class="org">MD, HJPARAM Intellectual Services &amp; Technology</div>
        </div>
      </div>
      <div class="foot">HJPARAM Intellectual Services &amp; Technology</div>
    </div>
  </div>
 </div>
</div>

<script>
  // Fit the A4 page to narrow screens (printing always uses full size)
  (function(){
    var s=document.getElementById('scaler'), page=s.firstElementChild;
    function fit(){
      var avail=document.documentElement.clientWidth-32, w=page.offsetWidth, k=Math.min(1,avail/w);
      s.style.transform='scale('+k+')'; s.style.height=(page.offsetHeight*k)+'px';
    }
    window.addEventListener('resize',fit); window.addEventListener('load',fit); fit();
  })();
</script>
</body>
</html>
