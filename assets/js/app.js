function initNavToggle() {
  const toggleBtn = document.getElementById("nav-toggle-btn");
  const nav = document.querySelector("header nav");

  if (!toggleBtn || !nav) return;

  toggleBtn.addEventListener("click", function () {
    nav.classList.toggle("nav-open");
  });
}

function updateCounter() {
  const table = document.querySelector(".table-responsive table");
  const counterEl = document.getElementById("table-counter");

  if (!table || !counterEl) return;

  const totalRows = table.querySelectorAll("tbody tr").length;
  let visibleRows = 0;

  table.querySelectorAll("tbody tr").forEach(function (row) {
    if (row.style.display !== "none") {
      visibleRows++;
    }
  });

  counterEl.textContent = `Menampilkan ${visibleRows} dari ${totalRows} data`;
}

// [DIUBAH] Dari event "click" menjadi event "submit"
function initHapusConfirm() {
  document.addEventListener("submit", function (e) {
    const form = e.target;
    if (!form.classList.contains("form-hapus")) return;

    const row = form.closest("tr");
    const nama = row ? row.querySelector("td")?.textContent : "data ini";

    const yakin = confirm('Yakin ingin menghapus "' + nama + '"?');
    if (!yakin) {
      e.preventDefault();
    }
  });
}

function initEditConfirm() {
  document.addEventListener("submit", function (e) {
    const form = e.target;
    if (!form.classList.contains("form-edit")) return;

    const yakin = confirm("Yakin ingin menyimpan perubahan?");
    if (!yakin) {
      e.preventDefault();
    }
  });
}

function initTableFilter() {
  const input = document.getElementById("search-input");
  const table = document.querySelector(".table-responsive table");

  if (!input || !table) return;

  input.addEventListener("keyup", function () {
    const keyword = input.value.toLowerCase();
    const rows = table.querySelectorAll("tbody tr");

    rows.forEach(function (row) {
      const tdJudul = row.querySelector("td");
      const teks = tdJudul ? tdJudul.textContent.toLowerCase() : "";

      row.style.display = teks.includes(keyword) ? "" : "none";
    });
    updateCounter();
  });
}

function tampilkanError(input, pesan) {
  hapusError(input);
  const span = document.createElement("span");
  span.className = "error";
  span.textContent = pesan;
  input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
  const next = input.nextElementSibling;
  if (next && next.classList.contains("error")) {
    next.remove();
  }
}

function initValidasiForm() {
  const form = document.getElementById("form-tambah");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    let valid = true;

    const rules = [
      {
        selector: "[name='judul'], [name='nama']",
        pesan: "Field ini wajib diisi.",
      },
      {
        selector: "[name='pengarang']",
        pesan: "Pengarang wajib diisi.",
      },
    ];

    rules.forEach(function (rule) {
      const field = form.querySelector(rule.selector);
      if (field) {
        if (field.value.trim() === "") {
          tampilkanError(field, rule.pesan);
          valid = false;
        } else {
          hapusError(field);
        }
      }
    });

    const tahun = form.querySelector("[name='tahun']");
    if (tahun) {
      const nilai = parseInt(tahun.value, 10);
      if (isNaN(nilai) || nilai < 1900 || nilai > 2026) {
        tampilkanError(tahun, "Tahun harus di antara 1900-2026.");
        valid = false;
      } else {
        hapusError(tahun);
      }
    }

    const stok = form.querySelector("[name='stok']");
    if (stok && stok.value.trim() !== "") {
      const nilaiStok = parseInt(stok.value, 10);
      if (isNaN(nilaiStok) || nilaiStok < 0) {
        tampilkanError(stok, "Stok tidak boleh bernilai negatif.");
        valid = false;
      } else {
        hapusError(stok);
      }
    }

    const isbn = form.querySelector("[name='isbn']");
    if (isbn && isbn.value.trim() !== "") {
      const isValidISBN = /^[0-9-]+$/.test(isbn.value.trim());

      if (!isValidISBN) {
        tampilkanError(isbn, "ISBN hanya boleh berisi angka dan tanda hubung (-).");
        valid = false;
      } else {
        hapusError(isbn);
      }
    } else if (isbn) {
      hapusError(isbn);
    }

    if (!valid) {
      e.preventDefault();
    }
  });
}

async function muatDataGenerik(urlData, daftarKunci) {
  const tbody = document.querySelector(".table-responsive table tbody");
  const loading = document.getElementById("loading-indicator");

  if (!tbody) return;

  if (loading) loading.style.display = "block";
  tbody.innerHTML = "";

  try {
    await new Promise((resolve) => setTimeout(resolve, 3000));

    const res = await fetch(urlData);
    if (!res.ok) {
      throw new Error("Gagal mengambil data (status " + res.status + ")");
    }

    const daftarData = await res.json();

    daftarData.forEach(function (item) {
      const tr = document.createElement("tr");

      let htmlKolom = "";
      daftarKunci.forEach(function (kunci) {
        htmlKolom += "<td>" + (item[kunci] !== undefined ? item[kunci] : "") + "</td>";
      });

      const htmlAksi =
        "<td>" +
        '<button type="button" class="btn-edit">Edit</button> ' +
        '<button type="button" class="btn-detail">Detail</button> ' +
        '<button type="button" class="btn-hapus">Hapus</button>' +
        "</td>";

      tr.innerHTML = htmlKolom + htmlAksi;
      tbody.appendChild(tr);
    });

    updateCounter();
  } catch (err) {
    const totalKolom = daftarKunci.length + 1;
    tbody.innerHTML =
      '<tr><td colspan="' + totalKolom + '">Gagal memuat data: ' + err.message + "</td></tr>";
  } finally {
    if (loading) loading.style.display = "none";
  }
}

document.addEventListener("DOMContentLoaded", function () {
  initNavToggle();
  initHapusConfirm();
  initEditConfirm();
  initTableFilter();
  updateCounter();


  const path = window.location.pathname;

  function muatHalamanIni() {
    if (path.includes("/buku/list.html")) {
      muatDataGenerik("../data/buku.json", ["judul", "pengarang", "tahun", "stok", "kategori"]);
    } else if (path.includes("/anggota/list.html")) {
      muatDataGenerik("../data/anggota.json", ["no_anggota", "nama", "alamat", "no_hp"]);
    }
  }

  muatHalamanIni();

  const btnReload = document.getElementById("btn-reload");
  if (btnReload) {
    btnReload.addEventListener("click", muatHalamanIni);
  } 
}
);