<script>
    document.addEventListener('DOMContentLoaded', function() {

        const customerSelect = document.getElementById('customer');
        const jenisSelect = document.getElementById('jenis');

        if (customerSelect) {
            customerSelect.addEventListener('change', function() {
                const selectedValue = this.value;
                const currentUrl = new URL(window.location);

                if (selectedValue) {
                    currentUrl.searchParams.set('customer', selectedValue);
                } else {
                    currentUrl.searchParams.delete('customer');
                    currentUrl.searchParams.delete('jenis_id');
                }

                history.pushState(null, '', currentUrl.toString());

                // Reload the page to update jenis options
                window.location.reload();
            });
        }

        const searchInput = document.querySelector('.search');
        const tableRows = document.querySelectorAll('.table tbody tr');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();

                tableRows.forEach(function(row) {
                    const linenCell = row.querySelector('td[data-label="Linen"]');
                    if (linenCell) {
                        const linenText = linenCell.textContent.toLowerCase().trim();
                        if (searchTerm === '' || linenText.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });
        }
    });
</script>