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

    localStorage.setItem(
        "colorOfDayRecommendation",
        JSON.stringify(currentRecommendation)
    );

    document.getElementById("savedMessage").classList.remove("hidden");
}