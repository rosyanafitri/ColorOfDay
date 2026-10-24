// Toggle Dark Mode
function toggleDarkMode() {
    document.documentElement.classList.toggle("dark");
}

// Update Character Counter
function updateCounter() {
    const story = document.getElementById("story");
    document.getElementById("counter").textContent = story.value.length;
}

// ================= DATABASE WARNA TERHARMONISASI =================
const fashionColorPalettes = [
    {
        top: { name: "Off-White Cream", class: "bg-amber-50" },
        bottom: { name: "Classic Denim Blue", class: "bg-blue-600" },
        reason: "Kombinasi klasik minimalis yang bersih, segar, dan tidak pernah salah."
    },
    {
        top: { name: "Pure White", class: "bg-slate-100" },
        bottom: { name: "Charcoal Dark Grey", class: "bg-slate-800" },
        reason: "Kontras netral monochrome yang memberikan kesan rapi, modern, dan tegas."
    },
    {
        top: { name: "Soft Beige / Oatmeal", class: "bg-stone-200" },
        bottom: { name: "Jet Black", class: "bg-slate-950" },
        reason: "Perpaduan warna netral hangat dan gelap yang sangat seimbang."
    },
    {
        top: { name: "Sage Green", class: "bg-emerald-600" },
        bottom: { name: "Sand Khaki / Cream", class: "bg-amber-100" },
        reason: "Warna Earth Tone lembut yang menenangan dan enak dipandang."
    },
    {
        top: { name: "Terracotta Warm", class: "bg-amber-700" },
        bottom: { name: "Off-White / Beige", class: "bg-stone-200" },
        reason: "Atasan bernuansa hangat yang diredam sempurna oleh bawahan netral terang."
    },
    {
        top: { name: "Mocha / Chocolate", class: "bg-amber-950" },
        bottom: { name: "Soft Ivory Cream", class: "bg-stone-100" },
        reason: "Kombinasi warna cokelat kental dan cream yang elegan & estetik."
    },
    {
        top: { name: "Mustard Gold", class: "bg-amber-500" },
        bottom: { name: "Deep Navy Blue", class: "bg-blue-950" },
        reason: "Warna Mustard yang menonjol diseimbangkan oleh Navy tua agar tidak kusam/tabrakan."
    },
    {
        top: { name: "Dusty Rose / Mauve", class: "bg-rose-300" },
        bottom: { name: "Light Grey / Off-White", class: "bg-slate-200" },
        reason: "Nuansa manis dan lembut dengan warna dasar netral yang sejuk."
    },
    {
        top: { name: "Sky Blue Breeze", class: "bg-sky-400" },
        bottom: { name: "White / Light Khaki", class: "bg-stone-100" },
        reason: "Warna biru langit yang segar dipadukan dengan putih untuk tampilan ceria."
    },
    {
        top: { name: "Burgundy Wine", class: "bg-rose-900" },
        bottom: { name: "Black / Dark Denim", class: "bg-slate-900" },
        reason: "Kombinasi warna tua yang berkelas, matang, dan berkarakter kuat."
    },
    {
        top: { name: "Lilac Lavender", class: "bg-purple-300" },
        bottom: { name: "Cloudy White", class: "bg-slate-100" },
        reason: "Perpaduan warna pastel yang chic dan memberikan aura bersahabat."
    },
    {
        top: { name: "Olive Green", class: "bg-lime-800" },
        bottom: { name: "Warm Cream / Off-White", class: "bg-amber-50" },
        reason: "Warna Olive tampil menawan dan terang saat dipadukan dengan bawahan Cream."
    }
];

// ================= DATABASE OUTFIT GABUNGAN =================
const tops = [
    "Kemeja Oxford berkerah rapi",
    "Kemeja Linen relaxed fit",
    "Blouse Chiffon berpotongan elegan",
    "Oversized T-shirt Cotton Combed",
    "Sweatshirt / Hoodie simpel",
    "Cardigan Rajut V-neck",
    "Blazer Slim-fit minimalis",
    "Turtleneck Fitted / Ribbed",
    "Outer Jaket Denim / Parka",
    "Kaos Polo Knitted breathable"
];

const bottoms = [
    "Celana Chino Tapered Fit",
    "Straight-leg Jeans Denim",
    "Celana Slacks Ankle Pants Rapi",
    "Celana Kulot High-Waist Flowy",
    "Rok Plisket / Pleated Skirt Panjang",
    "Celana Cargo Santai",
    "Shorts / Celana Pendek Chino",
    "Jogger Pants Minimalis"
];

const accessories = [
    "Jam tangan strap kulit / stainless + Kacamata hitam frame tipis",
    "Sneakers putih klasik + Topi Baseball Cap",
    "Loafers / Flatshoes + Scarf Sutra",
    "Sling bag compact + Smartwatch",
    "Tote Bag Kanvas + Kalung Rantai Tipis Estetik",
    "Sepatu Boots kulit + Backpack kanvas"
];

let currentRecommendation = null;
let lastColorIndex = -1;

function getRandom(arr) {
    return arr[Math.floor(Math.random() * arr.length)];
}

function getRecommendation() {
    const storyInput = document.getElementById("story").value.toLowerCase().trim();

    if (storyInput === "") {
        alert("Ceritakan dulu harimu sebelum mendapatkan rekomendasi!");
        return;
    }

    let colorIndex;
    do {
        colorIndex = Math.floor(Math.random() * fashionColorPalettes.length);
    } while (colorIndex === lastColorIndex && fashionColorPalettes.length > 1);
    lastColorIndex = colorIndex;

    const colorPair = fashionColorPalettes[colorIndex];
    const selectedTop = getRandom(tops);
    const selectedBottom = getRandom(bottoms);
    const selectedAcc = getRandom(accessories);

    let themeTitle = "Effortless Daily Look";
    if (storyInput.includes("presentasi") || storyInput.includes("rapat") || storyInput.includes("kerja") || storyInput.includes("skripsi")) {
        themeTitle = "Professional & Confident";
    } else if (storyInput.includes("hujan") || storyInput.includes("dingin") || storyInput.includes("mendung")) {
        themeTitle = "Cozy & Warm Layers";
    } else if (storyInput.includes("jalan") || storyInput.includes("nongkrong") || storyInput.includes("cafe") || storyInput.includes("hangout")) {
        themeTitle = "Trendy Urban Chic";
    } else if (storyInput.includes("galau") || storyInput.includes("capek") || storyInput.includes("sedih") || storyInput.includes("pusing")) {
        themeTitle = "Healing Comfort Vibe";
    } else if (storyInput.includes("panas") || storyInput.includes("pantai") || storyInput.includes("cerah")) {
        themeTitle = "Breezy Summer Refresh";
    }

    const fullExplanation = `${colorPair.reason} Perpaduan ${selectedTop} (${colorPair.top.name}) dan ${selectedBottom} (${colorPair.bottom.name}) akan terlihat sangat proporsional & stylish!`;

    currentRecommendation = {
        title: themeTitle,
        top: `${selectedTop} — (Warna: ${colorPair.top.name})`,
        bottom: `${selectedBottom} — (Warna: ${colorPair.bottom.name})`,
        accessories: selectedAcc,
        explanation: fullExplanation,
        topColorClass: colorPair.top.class,
        bottomColorClass: colorPair.bottom.class
    };

    document.getElementById("resultTitle").textContent = currentRecommendation.title;
    document.getElementById("resultTop").textContent = currentRecommendation.top;
    document.getElementById("resultBottom").textContent = currentRecommendation.bottom;
    document.getElementById("resultAcc").textContent = currentRecommendation.accessories;
    document.getElementById("resultExplanation").textContent = currentRecommendation.explanation;

    const circle1 = document.getElementById("colorCircleTop");
    const circle2 = document.getElementById("colorCircleBottom");
    if (circle1) circle1.className = "w-10 h-10 rounded-full shadow-md border border-slate-600 transition-all duration-500 " + colorPair.top.class;
    if (circle2) circle2.className = "w-10 h-10 rounded-full shadow-md border border-slate-600 transition-all duration-500 " + colorPair.bottom.class;

    const resultBox = document.getElementById("result");
    resultBox.classList.remove("hidden");
    document.getElementById("savedMessage").classList.add("hidden");

    resultBox.scrollIntoView({
        behavior: "smooth",
        block: "center"
    });
}

function saveRecommendation() {
    if (!currentRecommendation) return;

    try {
        localStorage.setItem("colorOfDayRecommendation", JSON.stringify(currentRecommendation));
        const favorites = readFavorites();
        const favorite = { ...currentRecommendation, id: `recommendation-${Date.now()}`, kind: "recommendation" };
        favorites.unshift(favorite);
        localStorage.setItem("colorOfDayFavorites", JSON.stringify(favorites));
        document.getElementById("savedMessage").textContent = "✓ Rekomendasi berhasil disimpan ke Favorit!";
        document.getElementById("savedMessage").classList.remove("hidden", "text-rose-400");
        document.getElementById("savedMessage").classList.add("text-emerald-400");
        updateFavoriteNavCount();
    } catch (error) {
        const savedMessage = document.getElementById("savedMessage");
        savedMessage.textContent = `Gagal menyimpan favorit: ${error.message}`;
        savedMessage.classList.remove("hidden", "text-emerald-400");
        savedMessage.classList.add("text-rose-400");
    }
}

let outfitData = [];

function escapeHtml(value) {
    return String(value).replace(/[&<>"']/g, (character) => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;"
    })[character]);
}

function readFavorites() {
    const stored = localStorage.getItem("colorOfDayFavorites");
    if (!stored) {
        const previousRecommendation = localStorage.getItem("colorOfDayRecommendation");
        return previousRecommendation
            ? [{ ...JSON.parse(previousRecommendation), id: "recommendation-previous", kind: "recommendation" }]
            : [];
    }

    const favorites = JSON.parse(stored);
    if (!Array.isArray(favorites)) {
        throw new Error("Format data favorit tidak valid.");
    }
    return favorites;
}

function updateFavoriteNavCount() {
    const count = document.getElementById("favoriteNavCount");
    if (!count) return;
    count.textContent = `(${readFavorites().length})`;
}

function setLoadingState(isLoading) {
    const loadingState = document.getElementById("loadingState");

    if (loadingState) {
        loadingState.classList.toggle("hidden", !isLoading);
    }
}

function renderOutfitCards(items) {
    const dataGrid = document.getElementById("dataGrid");
    const resultCount = document.getElementById("resultCount");

    if (!dataGrid || !resultCount) return;

    if (!items.length) {
        dataGrid.innerHTML = `
            <div class="col-span-full rounded-2xl border border-dashed border-slate-700 bg-[#131C2E] p-8 text-center text-slate-300">
                Data tidak ditemukan. Coba kata kunci lain atau reset filter.
            </div>
        `;
        resultCount.textContent = "0 item";
        return;
    }

    const cards = items.map((item) => {
        const colorList = item.colors.map((color) => `<span class="inline-block rounded-full bg-slate-800 px-2 py-1 text-xs text-slate-200">${escapeHtml(color)}</span>`).join(" ");

        return `
            <article class="rounded-2xl border border-slate-700 bg-[#131C2E] p-5 shadow-lg shadow-slate-950/20">
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center rounded-full bg-cyan-500/10 px-2.5 py-1 text-xs font-semibold text-cyan-300">${escapeHtml(item.mood)}</span>
                    <span class="text-xs uppercase tracking-wide text-slate-400">${escapeHtml(item.category)}</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">${escapeHtml(item.name)}</h3>
                <p class="text-sm text-slate-300 leading-relaxed mb-4">${escapeHtml(item.description)}</p>
                <div class="flex flex-wrap gap-2 mb-4">${colorList}</div>
                <div class="rounded-xl bg-[#0F172A] p-3 text-sm text-slate-300">
                    <span class="font-semibold text-cyan-300">Style:</span> ${escapeHtml(item.style)}
                </div>
                <button type="button" data-save-outfit="${escapeHtml(item.id)}" class="mt-4 w-full rounded-xl border border-cyan-500/50 px-4 py-2.5 text-sm font-semibold text-cyan-300 transition hover:bg-cyan-500/10">♡ Simpan ke Favorit</button>
            </article>
        `;
    }).join("");

    dataGrid.innerHTML = cards;
    resultCount.textContent = `${items.length} item`;
}

function getFilteredOutfits() {
    const searchValue = document.getElementById("searchInput")?.value.trim().toLowerCase() || "";
    const moodValue = document.getElementById("moodFilter")?.value || "all";

    return outfitData.filter((item) => {
        const matchSearch = !searchValue || [item.name, item.mood, item.category, item.style, item.description, item.colors.join(" ")]
            .join(" ")
            .toLowerCase()
            .includes(searchValue);

        const matchMood = moodValue === "all" || item.mood === moodValue;
        return matchSearch && matchMood;
    });
}

function updateFilteredOutfits() {
    renderOutfitCards(getFilteredOutfits());
}

async function loadOutfitData() {
    const errorState = document.getElementById("errorState");
    if (errorState) {
        errorState.classList.add("hidden");
        errorState.textContent = "";
    }
    setLoadingState(true);

    try {
        const response = await fetch("./src/data/outfits.json");

        if (!response.ok) {
            throw new Error(`Gagal mengambil data (${response.status})`);
        }

        const data = await response.json();
        outfitData = Array.isArray(data) ? data : data.outfits || [];
        updateFilteredOutfits();
        if (errorState) {
            errorState.classList.add("hidden");
        }
    } catch (error) {
        if (errorState) {
            errorState.textContent = `Error saat mengambil data: ${error.message}`;
            errorState.classList.remove("hidden");
        }
        outfitData = [];
        renderOutfitCards([]);
    } finally {
        setLoadingState(false);
    }
}

function resetFilters() {
    const searchInput = document.getElementById("searchInput");
    const moodFilter = document.getElementById("moodFilter");

    if (searchInput) searchInput.value = "";
    if (moodFilter) moodFilter.value = "all";
    updateFilteredOutfits();
}

document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const moodFilter = document.getElementById("moodFilter");
    const loadDataBtn = document.getElementById("loadDataBtn");
    const resetFilterBtn = document.getElementById("resetFilterBtn");

    if (searchInput) {
        searchInput.addEventListener("input", updateFilteredOutfits);
    }

    if (moodFilter) {
        moodFilter.addEventListener("change", updateFilteredOutfits);
    }

    if (loadDataBtn) {
        loadDataBtn.addEventListener("click", loadOutfitData);
    }

    if (resetFilterBtn) {
        resetFilterBtn.addEventListener("click", resetFilters);
    }

    const dataGrid = document.getElementById("dataGrid");
    if (dataGrid) {
        dataGrid.addEventListener("click", (event) => {
            const button = event.target.closest("[data-save-outfit]");
            if (!button) return;

            const outfit = outfitData.find((item) => String(item.id) === button.dataset.saveOutfit);
            if (!outfit) return;

            try {
                const favorites = readFavorites();
                if (!favorites.some((favorite) => favorite.id === `outfit-${outfit.id}`)) {
                    favorites.unshift({ ...outfit, id: `outfit-${outfit.id}`, kind: "outfit" });
                    localStorage.setItem("colorOfDayFavorites", JSON.stringify(favorites));
                }
                button.textContent = "✓ Tersimpan";
                button.classList.add("text-emerald-300", "border-emerald-500/50");
                updateFavoriteNavCount();
            } catch (error) {
                button.textContent = `Gagal menyimpan: ${error.message}`;
            }
        });
    }

    updateFavoriteNavCount();
    loadOutfitData();
});