function createSearchRow(count) {
    return `<div class="search-row d-flex gap-2 align-items-center mb-2">
    <select class="form-select w-25" name="search[${count}][operator]">
            <option value="AND">AND</option>
            <option value="OR">OR</option>
            <option value="NOT">NOT</option>
    </select>    
    <input type="text" class="form-control" name="search[${count}][term]" placeholder="Search string...">
        <input type="number" class="form-control w-20" name="search[${count}][within]" placeholder="Within n chars from last word">
        <div class="form-check form-switch mb-0 text-nowrap">
            <input class="form-check-input" type="checkbox" name="search[${count}][exact]" id="exact_${count}">
            <label class="form-check-label" for="exact_${count}">Exact</label>
        </div>
        <button type="button" class="btn btn-outline-danger remove-search-row-btn">✕</button>
    </div>`;
}

const search_container = document.getElementById('search-rows-container');
const add_btn = document.getElementById('add-search-row-btn');

if (search_container && add_btn) {
    let container_count = parseInt(search_container.dataset.count, 10) || 1;

    search_container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-search-row-btn')) {
            e.target.closest('.search-row').remove();
        }
    });

    add_btn.addEventListener('click', function () {
        search_container.insertAdjacentHTML('beforeend', createSearchRow(container_count++));
    });
}
