import Fuse from 'https://cdn.jsdelivr.net/npm/fuse.js/dist/fuse.min.mjs';

const searchEntries = JSON.parse(document.getElementById('sys-entry-list').getAttribute('data-entries'));
const fuse = new Fuse(searchEntries, {keys: ['name', 'species', 'colour']});

let fusedIds = searchEntries.map(entry => entry.id);
let filteredIds = searchEntries.map(entry => entry.id);

function checkIds() {
    for (const card of document.querySelectorAll('.sys-creature-card')) {
        const id = card.id.replace('sys-card-', '');
        if(fusedIds.includes(id) && filteredIds.includes(id))
            card.style.display = "block";
        else
            card.style.display = "none";
    }
}

document.querySelectorAll('.sys-search').forEach(bar => {
    bar.addEventListener('change', (event) => {
        const result = fuse.search(event.target.value);
        fusedIds = result.map(entry => entry.item.id);

        checkIds();
    });
});

const filters = document.querySelectorAll('.sys-filter-checkboxes .sys-filter-checkbox, .sys-filter-sliders div input');
filters.forEach(filter => {
    filter.addEventListener('change', (event) => {
        let selectedAttributes = {};
        for(const container of Array.from(document.querySelectorAll('.sys-filter-checkboxes')).filter(el => el.checkVisibility())) {
            let checkedValues = []
            const checkedBoxes = container.querySelectorAll('input:checked');
            if(checkedBoxes.length){
                for (const box of checkedBoxes)
                    checkedValues.push(box.value);
                selectedAttributes[container.getAttribute('data-attribute')] = checkedValues;
            }
        }

        let selectedRanges = {};
        for(const container of Array.from(document.querySelectorAll('.sys-filter-sliders')).filter(el => el.checkVisibility())) {
            let min = Number(container.querySelector('input[data-range-side="min"]').value);
            let max = Number(container.querySelector('input[data-range-side="max"]').value);
            selectedRanges[container.getAttribute('data-attribute')] = {"min": min, "max": max};
        }

        filteredIds = []
        entryLoop:
        for(const entry of searchEntries) {
            for(const [attribute, values] of Object.entries(selectedAttributes))
                if(attribute == "colour") {
                    if(!values.some(value => entry[attribute].includes(value)))
                        continue entryLoop;
                }
                else if(!values.includes(entry[attribute]))
                    continue entryLoop;

            for(const [attribute, range] of Object.entries(selectedRanges))
                if(Number(entry[attribute]) < range["min"] || Number(entry[attribute]) > range["max"])
                    continue entryLoop;

            filteredIds.push(entry.id);
        }

        checkIds();
    });
});
