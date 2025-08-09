const searchInput = document.querySelector('.search-input');
const cards       = document.querySelectorAll('.episode-card');

searchInput.addEventListener('input', () => {
    const term = searchInput.value.trim().toLowerCase();

    cards.forEach(card => {
        const title = card.querySelector('.episode-title').textContent.toLowerCase();
        card.style.display = title.includes(term) ? '' : 'none';
    });
});