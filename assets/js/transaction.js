// Membuat seluruh kartu dapat diklik untuk memilih jenis cuci
document.querySelectorAll('.wash-type-card').forEach(card => {
  card.addEventListener('click', () => {
    const radio = card.querySelector('input[type="radio"]');
    radio.checked = true;
    
    // Menghapus kelas 'selected' dari semua kartu
    document.querySelectorAll('.wash-type-card').forEach(c => {
        c.classList.remove('selected');
    });
    
    // Menambahkan kelas 'selected' ke kartu yang diklik
    card.classList.add('selected');
  });
});