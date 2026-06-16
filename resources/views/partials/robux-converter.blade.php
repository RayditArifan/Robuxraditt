<section class="robux-converter-section">
  <div class="rbc-header">
    <div>
      <h2>Konverter Robux → Rupiah</h2>
      <p>Rate tetap: <strong>1 Robux = Rp 175</strong></p>
    </div>
    <div class="rbc-mode-toggle">
      <button type="button" class="rbc-mode-btn active" data-mode="robux" onclick="rbcSetMode('robux')">Robux → IDR</button>
      <button type="button" class="rbc-mode-btn" data-mode="idr" onclick="rbcSetMode('idr')">IDR → Robux</button>
    </div>
  </div>

  <div class="rbc-body">

    {{-- Mode: Robux ke Rupiah --}}
    <div id="rbc-panel-robux" class="rbc-panel">
      <div class="rbc-input-group">
        <label for="rbc-robux-input">Jumlah Robux</label>
        <div class="rbc-input-wrap">
          <span class="rbc-prefix">R$</span>
          <input
            type="number"
            id="rbc-robux-input"
            class="rbc-input"
            placeholder="Masukkan jumlah Robux"
            min="1"
            oninput="rbcConvertToIDR()"
          >
        </div>
      </div>
      <div class="rbc-arrow">→</div>
      <div class="rbc-result-group">
        <label>Harga dalam Rupiah</label>
        <div class="rbc-result" id="rbc-idr-result">
          <span class="rbc-result-placeholder">Rp —</span>
        </div>
      </div>
    </div>

    {{-- Mode: Rupiah ke Robux --}}
    <div id="rbc-panel-idr" class="rbc-panel rbc-panel-hidden">
      <div class="rbc-input-group">
        <label for="rbc-idr-input">Jumlah Rupiah</label>
        <div class="rbc-input-wrap">
          <span class="rbc-prefix">Rp</span>
          <input
            type="number"
            id="rbc-idr-input"
            class="rbc-input"
            placeholder="Masukkan jumlah Rupiah"
            min="175"
            step="175"
            oninput="rbcConvertToRobux()"
          >
        </div>
      </div>
      <div class="rbc-arrow">→</div>
      <div class="rbc-result-group">
        <label>Jumlah Robux</label>
        <div class="rbc-result" id="rbc-robux-result">
          <span class="rbc-result-placeholder">—</span>
        </div>
      </div>
    </div>

    {{-- Quick presets --}}
    <div class="rbc-presets">
      <span class="rbc-preset-label">Preset cepat:</span>
      <button type="button" class="rbc-preset-btn" onclick="rbcPreset(100)">100 R</button>
      <button type="button" class="rbc-preset-btn" onclick="rbcPreset(400)">400 R</button>
      <button type="button" class="rbc-preset-btn" onclick="rbcPreset(800)">800 R</button>
      <button type="button" class="rbc-preset-btn" onclick="rbcPreset(1700)">1.700 R</button>
      <button type="button" class="rbc-preset-btn" onclick="rbcPreset(4500)">4.500 R</button>
    </div>

  </div>
</section>

<script>
(function () {
  const RATE = 175; // 1 Robux = Rp 175

  window.rbcSetMode = function (mode) {
    document.getElementById('rbc-panel-robux').classList.toggle('rbc-panel-hidden', mode !== 'robux');
    document.getElementById('rbc-panel-idr').classList.toggle('rbc-panel-hidden', mode !== 'idr');
    document.querySelectorAll('.rbc-mode-btn').forEach(function (btn) {
      btn.classList.toggle('active', btn.dataset.mode === mode);
    });
    // Clear inputs & results when switching
    document.getElementById('rbc-robux-input').value = '';
    document.getElementById('rbc-idr-input').value = '';
    document.getElementById('rbc-idr-result').innerHTML = '<span class="rbc-result-placeholder">Rp —</span>';
    document.getElementById('rbc-robux-result').innerHTML = '<span class="rbc-result-placeholder">—</span>';
  };

  window.rbcConvertToIDR = function () {
    const input = document.getElementById('rbc-robux-input');
    const result = document.getElementById('rbc-idr-result');
    const robux = parseFloat(input.value);

    if (!input.value || isNaN(robux) || robux <= 0) {
      result.innerHTML = '<span class="rbc-result-placeholder">Rp —</span>';
      return;
    }

    const idr = robux * RATE;
    result.innerHTML = '<strong class="rbc-result-value">' + formatRupiah(idr) + '</strong>';
  };

  window.rbcConvertToRobux = function () {
    const input = document.getElementById('rbc-idr-input');
    const result = document.getElementById('rbc-robux-result');
    const idr = parseFloat(input.value);

    if (!input.value || isNaN(idr) || idr <= 0) {
      result.innerHTML = '<span class="rbc-result-placeholder">—</span>';
      return;
    }

    const robux = idr / RATE;
    const robuxFormatted = Number.isInteger(robux)
      ? robux.toLocaleString('id-ID')
      : robux.toFixed(2);

    result.innerHTML = '<strong class="rbc-result-value">' + robuxFormatted + ' Robux</strong>';
  };

  window.rbcPreset = function (robux) {
    rbcSetMode('robux');
    const input = document.getElementById('rbc-robux-input');
    input.value = robux;
    rbcConvertToIDR();
  };

  function formatRupiah(value) {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      maximumFractionDigits: 0,
    }).format(value);
  }
})();
</script>
