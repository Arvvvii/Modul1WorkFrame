<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Undangan</title>
    <style>
      @page { size: A4 portrait; margin: 36mm; }
      html,body { margin:0; padding:0; }
      body { font-family: 'DejaVu Sans', sans-serif; color:#222; }
      .kop { text-align:center; }
      .kop .univ { font-size:18px; font-weight:700 }
      .kop .fakultas { font-size:16px; font-weight:600 }
      .alamat { font-size:12px; margin-top:4px }
      .nomor { margin-top:12px; margin-bottom:20px }
      .content { font-size:14px; text-align:justify; }
      .signature { margin-top:40px; text-align:right }
    </style>
  </head>
  <body>
    <div class="kop">
      <div class="univ">UNIVERSITAS AIRLANGGA</div>
      <div class="fakultas">FAKULTAS VOKASI</div>
      <div class="alamat">Kampus C, Jl. Dharmawangsa Dalam Surabaya</div>
    </div>

    <div class="nomor">Nomor: {{ $nomor_surat ?? '001/UND/' . date('Y') }}</div>

    <div class="content">
      <p>Kepada Yth.</p>
      <p>Saudara/i <strong>{{ $user->name }}</strong></p>
      <p>Dengan hormat,</p>
      <p>Sehubungan dengan penyelenggaraan kegiatan akademik oleh Fakultas Vokasi Universitas Airlangga, kami mengundang Saudara/i untuk hadir pada acara tersebut. Kehadiran Saudara/i sangat kami harapkan dalam rangka mendukung kelancaran kegiatan.</p>
      <p>Adapun rincian undangan adalah sebagai berikut:</p>
      <ul>
        <li>Hari/Tanggal: dd/mm/yyyy</li>
        <li>Waktu: 09.00 WIB</li>
        <li>Tempat: Aula Fakultas Vokasi</li>
      </ul>
      <p>Demikian undangan ini kami sampaikan. Atas perhatian dan kehadiran Saudara/i, kami ucapkan terima kasih.</p>
    </div>

    <div class="signature">
      <div>Hormat kami,</div>
      <div style="margin-top:40px">Prof. Dian Yulie Reindrawati</div>
      <div>Dekan Fakultas Vokasi</div>
    </div>
  </body>
</html>
