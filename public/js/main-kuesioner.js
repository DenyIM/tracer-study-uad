// MAIN KUESIONER FUNCTIONALITY - COMPATIBLE VERSION
let selectedCategory = 'umum';

document.querySelectorAll('.category-card').forEach(card => {
    card.addEventListener('click', function () {
        selectedCategory = this.getAttribute('data-category');
    });
});

// Data kuesioner berdasarkan kategori
const questionnaireData = {
    'bekerja': {
        title: 'BEKERJA DI PERUSAHAAN/INSTANSI',
        kuesioner2: {
            title: 'Pengalaman Kerja & Karir',
            desc: 'Kuesioner tentang pengalaman kerja di perusahaan/instansi, posisi, tanggung jawab, dan perkembangan karir.'
        },
        kuesioner3: {
            title: 'Keterampilan di Tempat Kerja',
            desc: 'Kuesioner tentang penerapan keterampilan yang diperoleh selama kuliah di tempat kerja.'
        },
        kuesioner4: {
            title: 'Kepuasan Kerja & Saran',
            desc: 'Kuesioner tentang kepuasan terhadap pekerjaan saat ini dan saran untuk pengembangan karir.'
        }
    },
    'wirausaha': {
        title: 'WIRAUSAHA/PEMILIK USAHA',
        kuesioner2: {
            title: 'Profil Usaha & Pengembangan',
            desc: 'Kuesioner tentang jenis usaha, skala, perkembangan, dan tantangan dalam berwirausaha.'
        },
        kuesioner3: {
            title: 'Keterampilan Kewirausahaan',
            desc: 'Kuesioner tentang keterampilan yang dibutuhkan dalam berwirausaha dan penerapannya.'
        },
        kuesioner4: {
            title: 'Kepuasan & Saran Pengembangan',
            desc: 'Kuesioner tentang kepuasan sebagai wirausaha dan saran untuk pengembangan usaha.'
        }
    },
    'pendidikan': {
        title: 'MELANJUTKAN PENDIDIKAN',
        kuesioner2: {
            title: 'Studi Lanjut & Persiapan',
            desc: 'Kuesioner tentang program studi lanjut, persiapan, dan motivasi melanjutkan pendidikan.'
        },
        kuesioner3: {
            title: 'Keterampilan Akademik',
            desc: 'Kuesioner tentang keterampilan akademik yang diperoleh dan pengembangannya.'
        },
        kuesioner4: {
            title: 'Kepuasan & Rencana Masa Depan',
            desc: 'Kuesioner tentang kepuasan terhadap pendidikan lanjut dan rencana karir setelah lulus.'
        }
    },
    'pencari': {
        title: 'PENCARI KERJA',
        kuesioner2: {
            title: 'Pencarian Kerja & Hambatan',
            desc: 'Kuesioner tentang proses pencarian kerja, hambatan yang dihadapi, dan strategi yang digunakan.'
        },
        kuesioner3: {
            title: 'Kesiapan Kerja & Keterampilan',
            desc: 'Kuesioner tentang kesiapan memasuki dunia kerja dan keterampilan yang perlu dikembangkan.'
        },
        kuesioner4: {
            title: 'Harapan & Dukungan yang Dibutuhkan',
            desc: 'Kuesioner tentang harapan terhadap pekerjaan dan dukungan yang dibutuhkan dari almamater.'
        }
    },
    'tidak-kerja': {
        title: 'TIDAK BEKERJA & TIDAK MENCARI',
        kuesioner2: {
            title: 'Situasi & Aktivitas Saat Ini',
            desc: 'Kuesioner tentang situasi saat ini, aktivitas yang dilakukan, dan alasan tidak bekerja.'
        },
        kuesioner3: {
            title: 'Keterampilan & Minat',
            desc: 'Kuesioner tentang keterampilan yang dimiliki dan minat untuk pengembangan diri.'
        },
        kuesioner4: {
            title: 'Rencana Masa Depan & Dukungan',
            desc: 'Kuesioner tentang rencana masa depan dan jenis dukungan yang dibutuhkan dari almamater.'
        }
    }
};

// Fungsi untuk menginisialisasi kuesioner
function initializeQuestionnaire() {
    console.log('Initializing questionnaire functionality...');

    // Event listener untuk kategori card
    const categoryCards = document.querySelectorAll('.category-card');
    if (categoryCards.length > 0) {
        categoryCards.forEach(card => {
            card.addEventListener('click', function () {
                const category = this.getAttribute('data-category');
                const categoryData = questionnaireData[category];

                if (!categoryData) {
                    console.warn('Data kategori tidak ditemukan:', category);
                    return;
                }

                // Update modal title
                const modalTitle = document.getElementById('modalCategoryTitle');
                if (modalTitle) {
                    modalTitle.textContent = categoryData.title;
                }

                // Update kuesioner 2
                const kuesioner2Title = document.getElementById('kuesioner2Title');
                const kuesioner2Desc = document.getElementById('kuesioner2Desc');
                if (kuesioner2Title && kuesioner2Desc) {
                    kuesioner2Title.textContent = categoryData.kuesioner2.title;
                    kuesioner2Desc.textContent = categoryData.kuesioner2.desc;
                }

                // Update kuesioner 3
                const kuesioner3Title = document.getElementById('kuesioner3Title');
                const kuesioner3Desc = document.getElementById('kuesioner3Desc');
                if (kuesioner3Title && kuesioner3Desc) {
                    kuesioner3Title.textContent = categoryData.kuesioner3.title;
                    kuesioner3Desc.textContent = categoryData.kuesioner3.desc;
                }

                // Update kuesioner 4
                const kuesioner4Title = document.getElementById('kuesioner4Title');
                const kuesioner4Desc = document.getElementById('kuesioner4Desc');
                if (kuesioner4Title && kuesioner4Desc) {
                    kuesioner4Title.textContent = categoryData.kuesioner4.title;
                    kuesioner4Desc.textContent = categoryData.kuesioner4.desc;
                }

                console.log('Kategori dipilih:', categoryData.title);
            });
        });
    } else {
        console.warn('Tidak ditemukan elemen category-card');
    }

    // Event listener untuk tombol mulai kuesioner
    const startButtons = document.querySelectorAll('.btn-start-kuesioner');
    if (startButtons.length > 0) {
        startButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const kuesionerNumber = this.getAttribute('data-kuesioner');
                const categoryTitle = document.getElementById('modalCategoryTitle')?.textContent || 'Umum';

                // Tampilkan modal atau alert
                const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
                modal.hide();

                // Simulasi mulai kuesioner
                setTimeout(() => {
                    // Gunakan SweetAlert atau alert biasa
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: `Kuesioner ${kuesionerNumber}`,
                            html: `Memulai <b>Kuesioner ${kuesionerNumber}</b> untuk kategori:<br><b>${categoryTitle}</b>`,
                            icon: 'info',
                            showCancelButton: true,
                            confirmButtonText: 'Mulai',
                            cancelButtonText: 'Nanti',
                            confirmButtonColor: '#003366'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect ke halaman kuesioner yang sesuai
                                const kategori = selectedCategory || 'umum';

                                window.location.href =
                                    `/kuesioner?kategori=${kategori}&nomor=${kuesionerNumber}`;
                            }
                        });
                    } else {
                        if (confirm(`Memulai Kuesioner ${kuesionerNumber} untuk kategori: ${categoryTitle}\n\nAnda akan diarahkan ke halaman kuesioner.`)) {
                            // Redirect ke halaman kuesioner yang sesuai
                            const kategori = selectedCategory || 'umum';

                            window.location.href =
                                `/kuesioner?kategori=${kategori}&nomor=${kuesionerNumber}`;
                        }
                    }
                }, 300);
            });
        });
    } else {
        console.warn('Tidak ditemukan tombol mulai kuesioner');
    }

    // Event listener untuk tombol "Lanjutkan Kuesioner Sekarang"
    const continueButton = document.querySelector('button[data-bs-target="#categoryModal"]');
    if (continueButton) {
        continueButton.addEventListener('click', function () {
            // Set default category ke "bekerja" jika belum ada yang dipilih
            const modalTitle = document.getElementById('modalCategoryTitle');
            if (modalTitle && modalTitle.textContent === 'BEKERJA DI PERUSAHAAN/INSTANSI') {
                // Sudah ada default, tidak perlu update
            } else {
                // Update dengan default
                const categoryData = questionnaireData['bekerja'];
                if (categoryData) {
                    if (modalTitle) modalTitle.textContent = categoryData.title;

                    const kuesioner2Title = document.getElementById('kuesioner2Title');
                    const kuesioner2Desc = document.getElementById('kuesioner2Desc');
                    if (kuesioner2Title && kuesioner2Desc) {
                        kuesioner2Title.textContent = categoryData.kuesioner2.title;
                        kuesioner2Desc.textContent = categoryData.kuesioner2.desc;
                    }

                    const kuesioner3Title = document.getElementById('kuesioner3Title');
                    const kuesioner3Desc = document.getElementById('kuesioner3Desc');
                    if (kuesioner3Title && kuesioner3Desc) {
                        kuesioner3Title.textContent = categoryData.kuesioner3.title;
                        kuesioner3Desc.textContent = categoryData.kuesioner3.desc;
                    }

                    const kuesioner4Title = document.getElementById('kuesioner4Title');
                    const kuesioner4Desc = document.getElementById('kuesioner4Desc');
                    if (kuesioner4Title && kuesioner4Desc) {
                        kuesioner4Title.textContent = categoryData.kuesioner4.title;
                        kuesioner4Desc.textContent = categoryData.kuesioner4.desc;
                    }
                }
            }
        });
    }

    // Tambahkan efek hover untuk category cards
    const categoryCardsAll = document.querySelectorAll('.category-card');
    categoryCardsAll.forEach(card => {
        // Efek hover CSS sudah ada, tambahkan efek klik
        card.style.cursor = 'pointer';

        card.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.15)';
        });

        card.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
        });
    });

    // Tambahkan animasi untuk progress bar
    const progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
        // Animasikan progress bar
        setTimeout(() => {
            progressBar.style.transition = 'width 1.5s ease-in-out';
        }, 500);
    }

    // Inisialisasi AOS jika ada
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    }
}

// Fungsi untuk menampilkan toast (sama dengan di app.js)
function showToast(message, type = 'info') {
    // Cek jika bootstrap tersedia
    if (typeof bootstrap === 'undefined' || !bootstrap.Toast) {
        console.warn('Bootstrap Toast tidak tersedia');
        return;
    }

    // Buat elemen toast
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;

    // Tambahkan ke container toast yang sudah ada atau buat baru
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '1060';
        document.body.appendChild(toastContainer);
    }

    toastContainer.appendChild(toast);

    // Inisialisasi dan tampilkan toast
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 3000
    });

    bsToast.show();

    // Hapus toast setelah ditutup
    toast.addEventListener('hidden.bs.toast', function () {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    });
}

document.addEventListener('DOMContentLoaded', initializeQuestionnaire);

// Export untuk testing (opsional)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        questionnaireData,
        initializeQuestionnaire,
        showToast
    };
}