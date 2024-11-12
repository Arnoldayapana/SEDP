document.addEventListener('DOMContentLoaded', function() {
    const monthCards = document.querySelectorAll('.month-card');
    const submissionDateInput = document.getElementById('submissionDate');

    const monthToNumber = {
        'january': 1,
        'february': 2,
        'march': 3,
        'april': 4,
        'may': 5,
        'june': 6,
        'july': 7,
        'august': 8,
        'september': 9,
        'october': 10,
        'november': 11,
        'december': 12
    };

    monthCards.forEach(function(card) {
        card.addEventListener('click', function() {
            // Remove 'selected' class from all month cards
            monthCards.forEach(c => c.classList.remove('selected'));
            // Add 'selected' class to the clicked card
            this.classList.add('selected');

            // Get the month title from the card
            const monthTitle = this.querySelector('.card-title').textContent.trim().toLowerCase();
            const monthNumber = monthToNumber[monthTitle];

            if (monthNumber) {
                // Set the date in the submissionDate input
                const currentYear = new Date().getFullYear();
                submissionDateInput.value = `${currentYear}-${monthNumber.toString().padStart(2, '0')}-01`;
            }

            // Smooth scroll the selected card to the top of the container
            this.closest('.col-12').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    });
});