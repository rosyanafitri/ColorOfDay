const stylePalettes = [
    { name: "Soft Morning", mood: "Calm", note: "Lembut dan nyaman untuk hari santai.", colors: [{ name: "Oat Cream", hex: "#F2E8D5" }, { name: "Sage", hex: "#9CAF88" }, { name: "Misty Blue", hex: "#A9C4D2" }, { name: "Warm Taupe", hex: "#8A7663" }] },
    { name: "Bold Energy", mood: "Bold", note: "Kontras kuat untuk tampil percaya diri.", colors: [{ name: "Cherry Red", hex: "#C83E4D" }, { name: "Ink Navy", hex: "#172A46" }, { name: "Soft White", hex: "#F5F3EE" }, { name: "Golden Sand", hex: "#D5A64A" }] },
    { name: "Fresh Start", mood: "Fresh", note: "Cerah dan ringan untuk energi yang baru.", colors: [{ name: "Sky", hex: "#70B7D5" }, { name: "Lemon", hex: "#EEDB72" }, { name: "Cloud", hex: "#F4F7F7" }, { name: "Mint", hex: "#93C7A5" }] },
    { name: "Modern Muse", mood: "Elegant", note: "Netral modern dengan aksen yang berkelas.", colors: [{ name: "Charcoal", hex: "#34383D" }, { name: "Ivory", hex: "#F3EBDD" }, { name: "Mauve", hex: "#A47783" }, { name: "Mocha", hex: "#765443" }] },
    { name: "Petal Whisper", mood: "Romantic", note: "Pastel manis yang tetap terasa dewasa.", colors: [{ name: "Rose", hex: "#D99AA5" }, { name: "Lilac", hex: "#B4A4CE" }, { name: "Vanilla", hex: "#F6EEDC" }, { name: "Berry", hex: "#79485A" }] },
    { name: "Earth Explorer", mood: "Calm", note: "Warna bumi yang natural dan mudah dipadukan.", colors: [{ name: "Olive", hex: "#737B4C" }, { name: "Terracotta", hex: "#BD6B4C" }, { name: "Sand", hex: "#D9C3A0" }, { name: "Espresso", hex: "#493A32" }] },
    { name: "Golden Hour", mood: "Bold", note: "Hangat dan ekspresif untuk jadi pusat perhatian.", colors: [{ name: "Amber", hex: "#D78B32" }, { name: "Plum", hex: "#68425F" }, { name: "Cream", hex: "#F3E7CF" }, { name: "Forest", hex: "#315747" }] },
    { name: "Coastal Breeze", mood: "Fresh", note: "Nuansa pantai yang adem dan effortless.", colors: [{ name: "Ocean", hex: "#327D91" }, { name: "Seafoam", hex: "#A5C9B8" }, { name: "Shell", hex: "#E9D5C3" }, { name: "Deep Teal", hex: "#24545A" }] },
    { name: "Quiet Luxury", mood: "Elegant", note: "Perpaduan klasik untuk tampilan yang polished.", colors: [{ name: "Black", hex: "#292929" }, { name: "Stone", hex: "#B5AA9A" }, { name: "Champagne", hex: "#D6C3A0" }, { name: "White", hex: "#F4F1EA" }] },
    { name: "Blush Daydream", mood: "Romantic", note: "Warna lembut untuk suasana yang manis dan hangat.", colors: [{ name: "Blush", hex: "#E8B7B2" }, { name: "Peach", hex: "#F0B28C" }, { name: "Milk", hex: "#FFF4E8" }, { name: "Cocoa", hex: "#8B6255" }] }
];

function featureEscapeHtml(value) {
    return String(value).replace(/[&<>"']/g, (character) => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;"
    })[character]);
}

function readFeatureFavorites() {
    const saved = localStorage.getItem("colorOfDayFavorites");
    if (!saved) {
        const previousRecommendation = localStorage.getItem("colorOfDayRecommendation");
        return previousRecommendation
            ? [{ ...JSON.parse(previousRecommendation), id: "recommendation-previous", kind: "recommendation" }]
            : [];
    }
    const favorites = JSON.parse(saved);
    if (!Array.isArray(favorites)) throw new Error("Format data favorit tidak valid.");
    return favorites;
}

function updateFeatureFavoriteCount() {
    const count = document.querySelector("[data-favorite-count]");
    if (!count) return;
    count.textContent = `(${readFeatureFavorites().length})`;
}

function renderPalette(palette) {
    const container = document.getElementById("paletteResult");
    if (!container) return;
    const chips = palette.colors.map((color, index) => `
        <button type="button" data-copy-color="${index}" class="group overflow-hidden rounded-2xl border border-white/10 bg-[#0F172A] text-left transition hover:-translate-y-1 hover:border-cyan-400">
            <span class="block h-24 sm:h-32" style="background-color: ${featureEscapeHtml(color.hex)}"></span>
            <span class="block p-3">
                <span class="block font-semibold text-white">${featureEscapeHtml(color.name)}</span>
                <span class="mt-1 block font-mono text-xs text-slate-400">${featureEscapeHtml(color.hex)}</span>
                <span class="mt-2 block text-xs text-cyan-300">Klik untuk salin</span>
            </span>
        </button>
    `).join("");

    container.innerHTML = `
        <div class="mb-5 flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.15em] text-cyan-300">${featureEscapeHtml(palette.mood)} mood</p>
                <h2 class="mt-1 text-2xl font-extrabold text-white">${featureEscapeHtml(palette.name)}</h2>
                <p class="mt-1 text-sm text-slate-400">${featureEscapeHtml(palette.note)}</p>
            </div>
            <button id="savePalette" type="button" class="rounded-xl border border-rose-500/60 px-4 py-2.5 font-semibold text-rose-300 transition hover:bg-rose-500/10">♡ Simpan palet</button>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">${chips}</div>
    `;

    container.querySelectorAll("[data-copy-color]").forEach((button) => {
        button.addEventListener("click", async () => {
            const color = palette.colors[Number(button.dataset.copyColor)];
            const message = document.getElementById("paletteMessage");
            try {
                await navigator.clipboard.writeText(color.hex);
                message.textContent = `${color.hex} disalin ke clipboard.`;
                message.classList.remove("text-rose-300");
                message.classList.add("text-emerald-300");
            } catch (error) {
                message.textContent = `Tidak dapat menyalin warna: ${error.message}`;
                message.classList.remove("text-emerald-300");
                message.classList.add("text-rose-300");
            }
        });
    });

    container.querySelector("#savePalette").addEventListener("click", () => {
        const message = document.getElementById("paletteMessage");
        try {
            const favorites = readFeatureFavorites();
            if (!favorites.some((item) => item.kind === "palette" && item.name === palette.name)) {
                favorites.unshift({ ...palette, id: `palette-${palette.name}`, kind: "palette" });
                localStorage.setItem("colorOfDayFavorites", JSON.stringify(favorites));
            }
            message.textContent = "Palet berhasil disimpan ke Favorit.";
            message.classList.remove("text-rose-300");
            message.classList.add("text-emerald-300");
            updateFeatureFavoriteCount();
        } catch (error) {
            message.textContent = `Gagal menyimpan palet: ${error.message}`;
            message.classList.remove("text-emerald-300");
            message.classList.add("text-rose-300");
        }
    });
}

function initializePaletteStudio() {
    const moodSelect = document.getElementById("paletteMood");
    const shuffleButton = document.getElementById("shufflePalette");
    if (!moodSelect || !shuffleButton) return;

    let currentPalette = null;
    const shuffle = () => {
        const mood = moodSelect.value;
        const available = mood === "all" ? stylePalettes : stylePalettes.filter((palette) => palette.mood === mood);
        const choices = available.filter((palette) => palette !== currentPalette);
        currentPalette = choices[Math.floor(Math.random() * choices.length)] || available[0];
        document.getElementById("paletteMessage").textContent = "";
        renderPalette(currentPalette);
    };

    shuffleButton.addEventListener("click", shuffle);
    moodSelect.addEventListener("change", shuffle);
    shuffle();
}

function renderFavorites() {
    const grid = document.getElementById("favoritesGrid");
    const message = document.getElementById("favoritesMessage");
    const clearButton = document.getElementById("clearFavorites");
    if (!grid || !message || !clearButton) return;

    let favorites;
    try {
        favorites = readFeatureFavorites();
    } catch (error) {
        message.textContent = `Koleksi tidak dapat dibaca: ${error.message}`;
        message.classList.add("text-rose-300");
        return;
    }

    updateFeatureFavoriteCount();
    clearButton.classList.toggle("hidden", favorites.length === 0);
    message.textContent = favorites.length ? `${favorites.length} inspirasi tersimpan` : "";

    if (!favorites.length) {
        grid.innerHTML = `
            <div class="col-span-full rounded-3xl border border-dashed border-slate-700 bg-[#131C2E] px-6 py-14 text-center">
                <span class="text-5xl">🧺</span>
                <h2 class="mt-5 text-xl font-bold text-white">Koleksimu masih kosong</h2>
                <p class="mx-auto mt-2 max-w-md text-slate-400">Simpan rekomendasi outfit atau palet warna yang kamu suka, lalu semuanya akan muncul di sini.</p>
                <a href="./index.html#cerita" class="mt-6 inline-block rounded-full bg-cyan-500 px-6 py-3 font-bold text-white transition hover:bg-cyan-600">Buat rekomendasi outfit →</a>
                <a href="./palette-studio.html" class="mt-3 inline-block rounded-full border border-slate-600 px-6 py-3 font-bold text-slate-200 transition hover:border-cyan-400 hover:text-cyan-300 sm:ml-3">Jelajahi warna</a>
            </div>
        `;
        return;
    }

    grid.innerHTML = favorites.map((item, index) => {
        let details;
        if (item.kind === "palette") {
            const colors = (item.colors || []).map((color) => `
                <span class="inline-flex items-center gap-2 rounded-full bg-slate-800 px-3 py-1.5 text-xs text-slate-200">
                    <span class="h-3 w-3 rounded-full" style="background-color:${featureEscapeHtml(color.hex)}"></span>${featureEscapeHtml(color.name)}
                </span>
            `).join("");
            details = `<p class="mb-3 text-sm text-slate-300">${featureEscapeHtml(item.note || "")}</p><div class="flex flex-wrap gap-2">${colors}</div>`;
        } else if (item.kind === "outfit") {
            details = `<p class="text-sm leading-relaxed text-slate-300">${featureEscapeHtml(item.description || "")}</p><p class="mt-3 text-sm text-cyan-300">${featureEscapeHtml((item.colors || []).join(" · "))} · ${featureEscapeHtml(item.style || "")}</p>`;
        } else {
            details = `<div class="space-y-2 text-sm text-slate-300"><p>👕 ${featureEscapeHtml(item.top || "")}</p><p>👖 ${featureEscapeHtml(item.bottom || "")}</p><p>💍 ${featureEscapeHtml(item.accessories || "")}</p></div>`;
        }

        const title = item.name || item.title || "Inspirasi Outfit";
        const label = item.kind === "palette" ? "PALET WARNA" : item.kind === "outfit" ? "IDE OUTFIT" : "REKOMENDASI";
        return `
            <article class="rounded-2xl border border-slate-700 bg-[#131C2E] p-5 shadow-lg">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <span class="rounded-full bg-rose-500/10 px-3 py-1 text-xs font-bold tracking-wide text-rose-300">${label}</span>
                    <button type="button" data-remove-favorite="${index}" aria-label="Hapus ${featureEscapeHtml(title)} dari favorit" class="rounded-lg px-3 py-1.5 text-sm text-slate-400 transition hover:bg-rose-500/10 hover:text-rose-300">Hapus</button>
                </div>
                <h2 class="mb-3 text-xl font-bold text-white">${featureEscapeHtml(title)}</h2>
                ${details}
            </article>
        `;
    }).join("");

    grid.querySelectorAll("[data-remove-favorite]").forEach((button) => {
        button.addEventListener("click", () => {
            const currentFavorites = readFeatureFavorites();
            currentFavorites.splice(Number(button.dataset.removeFavorite), 1);
            localStorage.setItem("colorOfDayFavorites", JSON.stringify(currentFavorites));
            renderFavorites();
        });
    });
}

function initializeFavorites() {
    const grid = document.getElementById("favoritesGrid");
    const clearButton = document.getElementById("clearFavorites");
    if (!grid || !clearButton) return;

    clearButton.addEventListener("click", () => {
        if (!window.confirm("Hapus semua inspirasi dari Favorit?")) return;
        localStorage.removeItem("colorOfDayFavorites");
        renderFavorites();
    });
    renderFavorites();
}

document.addEventListener("DOMContentLoaded", () => {
    updateFeatureFavoriteCount();
    initializePaletteStudio();
    initializeFavorites();
});
