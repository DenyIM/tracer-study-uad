// Toggle Advanced Filter
document.getElementById('toggleAdvancedFilter').addEventListener('click', function () {
    const advancedFilter = document.getElementById('advancedFilter');
    const icon = this.querySelector('i');

    if (advancedFilter.style.display === 'block') {
        advancedFilter.style.display = 'none';
        icon.className = 'fas fa-sliders-h me-2';
    } else {
        advancedFilter.style.display = 'block';
        icon.className = 'fas fa-times me-2';
    }
});

// Bookmark functionality
function toggleBookmark(button) {
    const icon = button.querySelector('i');
    if (icon.classList.contains('far')) {
        icon.classList.remove('far');
        icon.classList.add('fas');
        button.classList.add('active');
    } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
        button.classList.remove('active');
    }
}

// Sort functionality
document.querySelectorAll('.sort-btn').forEach(button => {
    button.addEventListener('click', function () {
        document.querySelectorAll('.sort-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        this.classList.add('active');

        // Here you would implement actual sorting logic
        const sortType = this.getAttribute('data-sort');
        console.log('Sorting by:', sortType);
    });
});

// View job detail (simulated)
function viewJobDetail(jobId) {
    // In real implementation, this would navigate to job detail page
    console.log('Viewing job detail for ID:', jobId);
    alert(`Membuka detail lowongan ID: ${jobId}\n\nDalam implementasi nyata, ini akan mengarahkan ke halaman detail lowongan kerja.`);
}

// Simulate job count
document.getElementById('jobCount').textContent = '1.245';