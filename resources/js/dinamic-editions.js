const container = document.getElementById('editions-container');
let editionCount = 0;

const fieldsByType = {
    corpus: ['corpus', 'volume', 'number_inscription', 'link'],
    journal: ['corpus', 'publication_year', 'corpus_page', 'number_inscription', 'last_name_author', 'link'],
    book: ['corpus', 'volume', 'last_name_author', 'link']
};

const fieldLabels = {
    corpus: 'Corpus',
    volume: 'Volume',
    number_inscription: 'Inscription number',
    publication_year: 'Publication year',
    corpus_page: 'Corpus page',
    last_name_author: "Author's last name",
    link: "Link to external resource",
};

const fieldTypes = {
    corpus: 'text',
    volume: 'number',
    number_inscription: 'number',
    publication_year: 'number',
    corpus_page: 'number',
    last_name_author: 'text',
    link: 'text',
};

function getExistingEditions() {
    return JSON.parse(container.dataset.editions || '[]');
}

function createTypeSelector(index, selectedType) {
    return `
        <div class="col-12 mb-3">
            <label class="form-label">Select edition type</label>
            <select class="form-select edition-type-select" name="editions[${index}][edition_type]" data-index="${index}">
                <option value="corpus" ${selectedType === 'corpus' ? 'selected' : ''}>Corpus</option>
                <option value="journal" ${selectedType === 'journal' ? 'selected' : ''}>Journal</option>
                <option value="book" ${selectedType === 'book' ? 'selected' : ''}>Book</option>
            </select>
        </div>
    `;
}

function createField(index, fieldName, value = '') {
    const isRequired = fieldName === 'corpus' ? 'required' : '';
    const requiredStar = fieldName === 'corpus' ? '<span class="text-danger">*</span>' : '';
    return `
        <div class="col-3 mb-3 edition-field" data-field="${fieldName}">
            <label class="form-label">${fieldLabels[fieldName]} ${requiredStar}</label>
            <input 
                type="${fieldTypes[fieldName]}" 
                name="editions[${index}][${fieldName}]" 
                class="form-control" 
                id="${fieldName}${index}"
                value="${value}"
                ${isRequired}
            >
        </div>
    `;
}

function createImageField(index, existingImage = null) {
    const currentFile = existingImage
        ? `<p class="text-muted small">Current file: <a href="/storage/${existingImage}" target="_blank">View</a></p>`
        : '';
    return `
        <div class="col-12 mb-3 edition-field" data-field="edition_image">
            <label class="form-label">Printed edition</label>
            ${currentFile}
            <input type="file" name="editions[${index}][edition_image]" class="form-control">
        </div>
    `;
}

function createHiddenId(index, id = null) {
    return id ? `<input type="hidden" name="editions[${index}][id]" value="${id}">` : '';
}

function createDeleteButton() {
    return `
        <div class="col-12 mb-3">
            <button type="button" class="btn btn-danger remove-edition-btn">Delete edition</button>
        </div>
    `;
}

function buildEditionRow(index, type, fieldValues = {}, id = null) {
    const allFields = ['corpus', 'volume', 'number_inscription', 'publication_year', 'corpus_page', 'last_name_author', 'link'];
    const fieldsHtml = allFields.map(field => createField(index, field, fieldValues[field] || '')).join('');

    return `
        <div class="row mb-3 edition-row align-items-center border border-warning-subtle rounded-4 p-3" id="edition-${index}">
            ${createTypeSelector(index, type)}
            ${fieldsHtml}
            ${createImageField(index, fieldValues.edition_image || null)}
            ${createHiddenId(index, id)}
            ${createDeleteButton()}
        </div>
    `;
}

function renderEdition(index, type, fieldValues = {}, id = null) {
    container.insertAdjacentHTML('beforeend', buildEditionRow(index, type, fieldValues, id));
}

function updateFieldsForType(editionRow, type, index) {
    const allFields = editionRow.querySelectorAll('.edition-field[data-field]');
    const activeFields = fieldsByType[type];

    allFields.forEach(field => {
        const fieldName = field.dataset.field;
        if (fieldName === 'edition_image') return;

        const isActive = activeFields.includes(fieldName);
        field.style.display = isActive ? 'block' : 'none';
        field.querySelectorAll('input').forEach(input => {
            input.disabled = !isActive;
        });
    });
}

function initExistingEditions() {
    const existing = getExistingEditions();
    console.log(existing);
    existing.forEach(edition => {
        editionCount++;
         console.log('type:', edition.edition_type);
        renderEdition(editionCount, edition.edition_type || 'corpus', edition, edition.id);
        const row = document.getElementById(`edition-${editionCount}`);
        updateFieldsForType(row, edition.edition_type || 'corpus', editionCount);
    });
}

function handleTypeChange(e) {
    if (!e.target.classList.contains('edition-type-select')) return;
    const index = e.target.dataset.index;
    const type = e.target.value;
    const editionRow = document.getElementById(`edition-${index}`);
    updateFieldsForType(editionRow, type, index);
}

function handleRemove(e) {
    if (!e.target.classList.contains('remove-edition-btn')) return;
    e.target.closest('.edition-row').remove();
}

function handleAddEdition() {
    editionCount++;
    renderEdition(editionCount, 'corpus');
    const row = document.getElementById(`edition-${editionCount}`);
    updateFieldsForType(row, 'corpus', editionCount);
}

document.getElementById('add-edition-btn').addEventListener('click', handleAddEdition);
container.addEventListener('click', handleRemove);
container.addEventListener('change', handleTypeChange);

initExistingEditions();