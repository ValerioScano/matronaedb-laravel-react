const externalResourcesContainer = document.getElementById('external-resources-container');
let externalResourceCount = 0;

function getExistingExternalResources() {
    return JSON.parse(externalResourcesContainer.dataset.externalResources || '[]');
}

function createExternalResourceRow(index, values = {}) {
    return `
        <div class="row mb-3 external-resource-row align-items-center border border-info-subtle rounded-4 p-3" id="external-resource-${index}">
            <div class="col-5 mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="external_resources[${index}][name]" class="form-control" value="${values.name ?? ''}">
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">Link</label>
                <input type="text" name="external_resources[${index}][link]" class="form-control" value="${values.link ?? ''}">
            </div>
            <div class="col-1 mb-3 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-external-resource-btn">×</button>
            </div>
        </div>
    `;
}

function initExistingExternalResources() {
    const existing = getExistingExternalResources();
    existing.forEach(resource => {
        externalResourceCount++;
        externalResourcesContainer.insertAdjacentHTML('beforeend', createExternalResourceRow(externalResourceCount, resource));
    });
}

function handleRemoveExternalResource(e) {
    if (!e.target.classList.contains('remove-external-resource-btn')) return;
    e.target.closest('.external-resource-row').remove();
}

function handleAddExternalResource() {
    externalResourceCount++;
    externalResourcesContainer.insertAdjacentHTML('beforeend', createExternalResourceRow(externalResourceCount));
}

if (externalResourcesContainer && document.getElementById('add-external-resource-btn')) {
    document.getElementById('add-external-resource-btn').addEventListener('click', handleAddExternalResource);
    externalResourcesContainer.addEventListener('click', handleRemoveExternalResource);
    initExistingExternalResources();
}
