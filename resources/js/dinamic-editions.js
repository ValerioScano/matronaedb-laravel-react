let editionCount = 0;

document.getElementById('add-edition-btn').addEventListener('click', addEdition);

document.getElementById('editions-container').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-edition-btn')) {
        e.target.closest('.edition-row').remove();
    }
    if (e.target.classList.contains('edition-type-switch')) {
        const editionRow = e.target.closest('.edition-row');
        const journalFields = editionRow.querySelectorAll('.journal-only');
        journalFields.forEach(field => {
            field.style.display = e.target.checked ? 'none' : 'block';
        });
    } f
});

function addEdition() {
    editionCount++;
    const container = document.getElementById('editions-container');

    const divEdition = document.createElement('div');
    divEdition.classList.add('row', 'mb-3', 'edition-row', 'align-items-center', 'border', 'border-warning-subtle', 'rounded-4', 'p-3');
    divEdition.id = `edition-${editionCount}`;
    divEdition.innerHTML = `
    <div class="col-12 mb-3">
        <div class="form-check form-switch">
            <input class="form-check-input edition-type-switch" type="checkbox" role="switch" id="switch${editionCount}" checked>
            <label class="form-check-label" for="switch${editionCount}">Corpus (checked) / Journal (unchecked)</label>
        </div>
    </div>
    <div class="col-3 mb-3">
        <label for="corpus${editionCount}" class="form-label">Corpus or Journal</label>   
        <input type="text" name="editions[${editionCount}][corpus]" class="form-control" id="corpus${editionCount}">
    </div>

    <div class="col-3 mb-3">
        <label for="volume${editionCount}" class="form-label">Volume in arabic numbers</label>
        <input type="number" name="editions[${editionCount}][volume]" class="form-control" id="volume${editionCount}">
    </div>

    <div class="col-3 mb-3">
        <label for="number_inscription${editionCount}" class="form-label">Inscription number</label>  
        <input type="number" name="editions[${editionCount}][number_inscription]" class="form-control" id="number_inscription${editionCount}">
    </div>

    <div class="col-3 mb-3 journal-only">
        <label for="publication_year${editionCount}" class="form-label">Publication year for Journals</label> 
        <input type="number" name="editions[${editionCount}][publication_year]" class="form-control" id="publication_year${editionCount}">
    </div>

    <div class="col-3 mb-3 journal-only">
        <label for="corpus_page${editionCount}" class="form-label">Corpus page for Journals</label>
        <input type="number" name="editions[${editionCount}][corpus_page]" class="form-control" id="corpus_page${editionCount}">
    </div>

    <div class="col-3 mb-3 journal-only">
        <label for="last_name_author${editionCount}" class="form-label">Author's last name</label>  
        <input type="text" name="editions[${editionCount}][last_name_author]" class="form-control" id="last_name_author${editionCount}">
    </div>

    <div class="mb-3 journal-only">
        <label for="edition_image">Printed edition</label>
        <input type="file" name="editions[${editionCount}][edition_image]" id="edition_image" class="form-control journal-only">
    </div>


    <div class="col-3 mb-3 ms-5">
    <button type="button" class="btn btn-danger remove-edition-btn">Delete edition</button>
    </div>
    `;
    container.appendChild(divEdition);
    const journalFields = divEdition.querySelectorAll('.journal-only');
    journalFields.forEach(field => {
        field.style.display = 'none';
    });
}


// Expose functions to global scope for inline onclick handlers
