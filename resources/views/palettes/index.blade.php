
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Color Of Day</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 30px;
        }

        h1 {
            text-align: center;
        }

        .form-container {
            background: white;
            max-width: 500px;
            margin: 25px auto;
            padding: 20px;
            border-radius: 12px;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
        }

        button {
            cursor: pointer;
            border: none;
            border-radius: 6px;
            background: #333;
            color: white;
        }

        .card {
            background: white;
            width: 280px;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px #ddd;
        }

        #palette-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .colors {
            display: flex;
            gap: 8px;
        }

        .color {
            width: 50px;
            height: 50px;
            border-radius: 8px;
        }

        .actions {
            display: flex;
            gap: 8px;
            margin-top: 15px;
        }

        .actions button {
            flex: 1;
        }

        .delete {
            background: #c0392b;
        }
    </style>
</head>

<body>

    <h1>Color Of Day</h1>
    <p style="text-align:center">Kelola Palette Warna</p>

    <!-- FORM TAMBAH DAN EDIT -->
    <div class="form-container">
        <h2 id="form-title">Tambah Palette</h2>

        <form id="palette-form">
            <input type="hidden" id="palette-id">

            <label>Nama Palette</label>
            <input type="text" id="nama_palette"
                   placeholder="Contoh: Ocean Blue" required>

            <label>Kategori</label>
            <input type="text" id="kategori"
                   placeholder="Contoh: Calm" required>

            <label>Warna 1</label>
            <input type="color" id="warna1" value="#F4A261">

            <label>Warna 2</label>
            <input type="color" id="warna2" value="#E9C46A">

            <label>Warna 3</label>
            <input type="color" id="warna3" value="#264653">

            <button type="submit">Simpan Palette</button>
            <button type="button" onclick="resetForm()">
                Batal / Reset
            </button>
        </form>
    </div>

    <!-- DAFTAR PALETTE -->
    <h2 style="text-align:center">Daftar Palette</h2>

    <div id="palette-container">
        Memuat data...
    </div>

    <script>
        const API = '/api/palettes';

        // READ
        function loadPalettes() {
            fetch(API)
                .then(response => response.json())
                .then(result => {
                    const container =
                        document.getElementById('palette-container');

                    container.innerHTML = '';

                    result.data.forEach(palette => {
                        container.innerHTML += `
                            <div class="card">
                                <h2>${palette.nama_palette}</h2>
                                <p>Kategori: ${palette.kategori}</p>

                                <div class="colors">
                                    ${palette.array_warna.map(warna => `
                                        <div class="color"
                                             style="background:${warna}">
                                        </div>
                                    `).join('')}
                                </div>

                                <div class="actions">
                                    <button onclick="editPalette(${palette.id})">
                                        Edit
                                    </button>

                                    <button class="delete"
                                            onclick="deletePalette(${palette.id})">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                });
        }

        // CREATE DAN UPDATE
        document.getElementById('palette-form')
            .addEventListener('submit', function(event) {
                event.preventDefault();

                const id = document.getElementById('palette-id').value;

                const data = {
                    nama_palette:
                        document.getElementById('nama_palette').value,

                    kategori:
                        document.getElementById('kategori').value,

                    array_warna: [
                        document.getElementById('warna1').value,
                        document.getElementById('warna2').value,
                        document.getElementById('warna3').value
                    ]
                };

                const url = id ? `${API}/${id}` : API;
                const method = id ? 'PATCH' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal menyimpan palette');
                    }
                    return response.json();
                })
                .then(() => {
                    alert('Palette berhasil disimpan!');
                    resetForm();
                    loadPalettes();
                })
                .catch(error => alert(error.message));
            });

        // MENGAMBIL DATA UNTUK EDIT
        function editPalette(id) {
            fetch(`${API}/${id}`)
                .then(response => response.json())
                .then(result => {
                    const palette = result.data;

                    document.getElementById('palette-id').value =
                        palette.id;

                    document.getElementById('nama_palette').value =
                        palette.nama_palette;

                    document.getElementById('kategori').value =
                        palette.kategori;

                    document.getElementById('warna1').value =
                        palette.array_warna[0] || '#F4A261';

                    document.getElementById('warna2').value =
                        palette.array_warna[1] || '#E9C46A';

                    document.getElementById('warna3').value =
                        palette.array_warna[2] || '#264653';

                    document.getElementById('form-title').textContent =
                        'Edit Palette';

                    window.scrollTo(0, 0);
                });
        }

        // DELETE
        function deletePalette(id) {
            if (!confirm('Yakin ingin menghapus palette ini?')) {
                return;
            }

            fetch(`${API}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal menghapus palette');
                }
                return response.json();
            })
            .then(() => {
                alert('Palette berhasil dihapus!');
                loadPalettes();
            })
            .catch(error => alert(error.message));
        }

        // RESET FORM
        function resetForm() {
            document.getElementById('palette-form').reset();
            document.getElementById('palette-id').value = '';
            document.getElementById('form-title').textContent =
                'Tambah Palette';
        }

        // TAMPILKAN DATA SAAT HALAMAN DIBUKA
        loadPalettes();
    </script>

</body>
</html>
