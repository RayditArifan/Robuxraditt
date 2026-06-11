@csrf

<div class="form-grid">
  <div class="form-group">
    <label for="kode">Kode Barang</label>
    <input type="text" id="kode" name="kode" value="{{ old('kode', $barang->kode ?? '') }}" placeholder="Contoh: BRG-007">
    @error('kode') <small class="error-message">{{ $message }}</small> @enderror
  </div>

  <div class="form-group">
    <label for="nama">Nama Barang</label>
    <input type="text" id="nama" name="nama" value="{{ old('nama', $barang->nama ?? '') }}" placeholder="Masukkan nama barang">
    @error('nama') <small class="error-message">{{ $message }}</small> @enderror
  </div>

  <div class="form-group">
    <label for="kategori">Kategori</label>
    <select id="kategori" name="kategori">
      <option value="">Pilih kategori</option>
      @foreach (['Gamepass', 'Voucher', 'Private Server'] as $kategori)
        <option value="{{ $kategori }}" @selected(old('kategori', $barang->kategori ?? '') === $kategori)>{{ $kategori }}</option>
      @endforeach
    </select>
    @error('kategori') <small class="error-message">{{ $message }}</small> @enderror
  </div>

  <div class="form-group">
    <label for="stok">Stok</label>
    <input type="number" id="stok" name="stok" min="0" value="{{ old('stok', $barang->stok ?? 0) }}">
    @error('stok') <small class="error-message">{{ $message }}</small> @enderror
  </div>

  <div class="form-group">
    <label for="harga">Harga</label>
    <input type="number" id="harga" name="harga" min="1000" step="1000" value="{{ old('harga', $barang->harga ?? '') }}" placeholder="Contoh: 35000">
    @error('harga') <small class="error-message">{{ $message }}</small> @enderror
  </div>

  <div class="form-group">
    <label for="tanggal_masuk">Tanggal Masuk</label>
    <input type="date" id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', isset($barang) && $barang->tanggal_masuk ? $barang->tanggal_masuk->format('Y-m-d') : date('Y-m-d')) }}">
    @error('tanggal_masuk') <small class="error-message">{{ $message }}</small> @enderror
  </div>

  <div class="form-group">
    <label for="foto">Foto Barang</label>
    <input type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/jpg">
    @error('foto') <small class="error-message">{{ $message }}</small> @enderror

    @isset($barang)
      @if ($barang->foto)
        <small>Foto saat ini: {{ $barang->foto }}</small>
      @endif
    @endisset
  </div>
</div>

<div class="button-row">
  <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
  <a href="{{ route('pengelolaan') }}" class="btn btn-secondary">Batal</a>
</div>
