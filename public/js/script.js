document.querySelectorAll("button.encode, button.decode").forEach(btn => {
  btn.addEventListener("click", e => e.preventDefault());
});

function previewEncodeImage() {
  const fileInput = document.querySelector("input[name=baseFile]");
  const file = fileInput.files[0];
  const errorMsg = document.getElementById("encodeError");

  // Sembunyikan pesan error setiap kali pengguna memilih file baru
  errorMsg.style.display = "none";

  if (!file) return; // Jika pengguna batal memilih file

  // Validasi tipe file (hanya izinkan jpg, jpeg, dan png)
  if (file.type !== "image/jpeg" && file.type !== "image/png") {
    errorMsg.style.display = "block"; // Munculkan tulisan merah
    fileInput.value = ""; // Reset input file agar kosong kembali
    document.querySelector(".images").style.display = "none"; // Sembunyikan canvas
    return; // Hentikan proses
  }

  // Jika file benar, lanjutkan proses seperti biasa
  document.querySelector(".images .nulled").style.display = "none";
  document.querySelector(".images .message").style.display = "none";

  loadImageToCanvas(file, ".original canvas", () => {
    document.querySelector(".images .original").style.display = "block";
    document.querySelector(".images").style.display = "block";
  });
}

function previewDecodeImage() {
  const fileInput = document.querySelector('input[name=decodeFile]');
  const file = fileInput.files[0];
  const errorMsg = document.getElementById("decodeError");

  // Sembunyikan pesan error setiap kali pengguna memilih file baru
  errorMsg.style.display = "none";

  if (!file) return; // Jika pengguna batal memilih file

  // Validasi tipe file
  if (file.type !== "image/jpeg" && file.type !== "image/png") {
    errorMsg.style.display = "block"; // Munculkan tulisan merah
    fileInput.value = ""; // Reset input file
    document.querySelector(".decode").style.display = "none"; // Sembunyikan canvas
    return; // Hentikan proses
  }

  // Jika file benar, lanjutkan proses
  loadImageToCanvas(file, ".decode canvas", () => {
    document.querySelector(".decode").style.display = "block";
  });
}

function loadImageToCanvas(file, selector, onLoadCallback) {
  const reader = new FileReader();
  const img = new Image();
  const canvas = document.querySelector(selector);
  const ctx = canvas.getContext('2d');

  if (file) reader.readAsDataURL(file);

  reader.onloadend = () => {
    img.src = URL.createObjectURL(file);
    img.onload = () => {
      canvas.width = img.width;
      canvas.height = img.height;
      ctx.drawImage(img, 0, 0);
      onLoadCallback();
    };
  };
}

function encodeMessage() {
  $(".error, .binary").hide();

  const rawMsg = $("textarea.message").val();
  const key = $(".encrypt-key").val(); // Ambil kata sandi
  
  // Enkripsi pesan sebelum diproses
  const msg = xorEncryptDecrypt(rawMsg, key); 
  const $orig = $(".original canvas");
  const $norm = $(".nulled canvas"); 
  const $msg = $(".message canvas");

  const ctxOrig = $orig[0].getContext("2d");
  const ctxNorm = $norm[0].getContext("2d");
  const ctxMsg = $msg[0].getContext("2d");

  const width = $orig[0].width;
  const height = $orig[0].height;

  if (msg.length * 8 > width * height * 3) {
    $(".error").text("Pesan terlalu panjang untuk gambar yang dipilih....").fadeIn();
    return;
  }

  [$norm, $msg].forEach($c => {
    $c.prop({ width, height });
  });

  const imgData = ctxOrig.getImageData(0, 0, width, height);
  const px = imgData.data;
  for (let i = 0; i < px.length; i += 4) {
    for (let j = 0; j < 3; j++) {
      if (px[i + j] % 2 !== 0) px[i + j]--;
    }
  }
  ctxNorm.putImageData(imgData, 0, 0);

  let bin = [...msg].map(ch => ch.charCodeAt(0).toString(2).padStart(8, "0")).join("");
  $(".binary textarea").text(bin);

  const newImg = ctxNorm.getImageData(0, 0, width, height);
  const newPx = newImg.data;
  let idx = 0;

  for (let i = 0; i < newPx.length && idx < bin.length; i += 4) {
    for (let j = 0; j < 3 && idx < bin.length; j++) {
      newPx[i + j] += parseInt(bin[idx++]);
    }
  } 

// ... (kode di atasnya memproses ctxMsg) ...
  ctxMsg.putImageData(newImg, 0, 0);
  $(".binary, .images, .message").fadeIn();


  // 1. Ambil data gambar dari canvas message (.message canvas)
  const resultCanvas = document.querySelector(".message canvas");
  const base64Image = resultCanvas ? resultCanvas.toDataURL('image/png') : null;
  // 2. Siapkan data file
  const fileInput = document.querySelector("input[name=baseFile]");
  const fileName = (fileInput && fileInput.files.length > 0) ? fileInput.files[0].name : "unknown.png";
  const msgLength = rawMsg.length;
    
  // 3. Ambil catatan
  const notesElement = document.querySelector(".notes-input");
  const notesValue = notesElement ? notesElement.value : null;


  // 5. Kirim data ke Database!
  saveHistory('encode', fileName, msgLength, key, notesValue, base64Image);
}


function decodeMessage() {
  const $canvas = $(".decode canvas");
  const ctx = $canvas[0].getContext("2d");
  const { width, height } = $canvas[0];
  const imgData = ctx.getImageData(0, 0, width, height).data;

  let binMsg = "";
  for (let i = 0; i < imgData.length; i += 4) {
    for (let j = 0; j < 3; j++) {
      binMsg += (imgData[i + j] % 2 !== 0) ? "1" : "0";
    }
  }

  let decoded = "";
  for (let i = 0; i < binMsg.length; i += 8) {
    const byte = binMsg.slice(i, i + 8);
    if (byte.length === 8) decoded += String.fromCharCode(parseInt(byte, 2));
  }

  // Ambil kata sandi dan dekripsi pesan
  const key = $(".decrypt-key").val();
  const finalMessage = xorEncryptDecrypt(decoded, key);

  $(".binary-decode textarea").text(finalMessage);
  $(".binary-decode").fadeIn();

  // === BAGIAN PENYIMPANAN RIWAYAT DECODE ===
  const fileInput = document.querySelector("input[name=baseFile]");
  const fileName = fileInput && fileInput.files.length > 0 ? fileInput.files[0].name : "unknown.png";
  
  
  const msgLength = finalMessage.length; 
  const hasPassword = key.length > 0 ? 1 : 0;
  
  
  saveHistory('decode', fileName, msgLength, key, null, null);
  // ===========================================
}


// Fungsi untuk Enkripsi dan Dekripsi menggunakan metode XOR
function xorEncryptDecrypt(text, key) {
  if (!key) return text; // Jika tidak ada sandi, biarkan pesan apa adanya
  let result = "";
  for (let i = 0; i < text.length; i++) {
    // Lakukan operasi matematika bitwise XOR antara karakter pesan dan karakter sandi
    result += String.fromCharCode(text.charCodeAt(i) ^ key.charCodeAt(i % key.length));
  }
  return result;
}

function downloadImage() {
  // Ambil elemen canvas yang berisi gambar hasil encode
  const canvas = document.querySelector(".message canvas");
  
  // Ubah canvas menjadi URL data gambar (format PNG agar tidak merusak bit steganografi)
  const imageURL = canvas.toDataURL("image/png");
  
  // Buat elemen anchor (<a>) sementara untuk memicu download
  const link = document.createElement("a");
  link.download = "hasil-steganografi.png"; // Nama file saat didownload
  link.href = imageURL;
  
  // Klik link secara programatik lalu hapus
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

// Ubah fungsi saveHistory untuk menerima parameter baru
function saveHistory(actionType, fileName, msgLength, xorKey, notesVal = null, imageBase64 = null) {
    const isLoggedIn = document.querySelector('meta[name="user-logged-in"]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]');

    if (isLoggedIn && csrfToken) {
        fetch('/history/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content')
            },
            body: JSON.stringify({
                action_type: actionType,
                file_name: fileName,
                message_length: msgLength,
                xor_key: xorKey, 
                notes: notesVal,
                image_base64: imageBase64 // Jika ingin menyimpan gambar juga, bisa ditambahkan
            })
        })
        .then(response => response.json())
        .then(data => console.log("System:", data.message))
        .catch(error => console.error('Error:', error));
    }
}

