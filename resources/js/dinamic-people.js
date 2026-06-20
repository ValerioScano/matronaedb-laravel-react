const peopleContainer = document.getElementById('people-container');
let personCount = 0;

function getExistingPeople() {
    return JSON.parse(peopleContainer.dataset.people || '[]');
}

function createPersonRow(index, values = {}) {
    return `
        <div class="row mb-3 person-row align-items-center border border-info-subtle rounded-4 p-3" id="person-${index}">
            <div class="col-3 mb-3">
                <label class="form-label">Praenomen</label>
                <input type="text" name="people[${index}][praenomen]" class="form-control" value="${values.praenomen ?? ''}">
            </div>
            <div class="col-3 mb-3">
                <label class="form-label">Nomen</label>
                <input type="text" name="people[${index}][nomen]" class="form-control" value="${values.nomen ?? ''}">
            </div>
            <div class="col-3 mb-3">
                <label class="form-label">Cognomen</label>
                <input type="text" name="people[${index}][cognomen]" class="form-control" value="${values.cognomen ?? ''}">
            </div>
            <div class="col-2 mb-3">
                <label class="form-label">TM_PER id</label>
                <input type="number" name="people[${index}][TM_PER_id]" class="form-control" min="0" value="${values.TM_PER_id ?? ''}">
            </div>
            <div class="col-1 mb-3 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-person-btn">×</button>
            </div>
        </div>
    `;
}

function initExistingPeople() {
    const existing = getExistingPeople();
    existing.forEach(person => {
        personCount++;
        peopleContainer.insertAdjacentHTML('beforeend', createPersonRow(personCount, person));
    });
}

function handleRemovePerson(e) {
    if (!e.target.classList.contains('remove-person-btn')) return;
    e.target.closest('.person-row').remove();
}

function handleAddPerson() {
    personCount++;
    peopleContainer.insertAdjacentHTML('beforeend', createPersonRow(personCount));
}

if (peopleContainer && document.getElementById('add-person-btn')) {
    document.getElementById('add-person-btn').addEventListener('click', handleAddPerson);
    peopleContainer.addEventListener('click', handleRemovePerson);
    initExistingPeople();
}
