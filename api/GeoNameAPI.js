const username = 'rovicangelolanuza23';

async function initializeGeonameScript(modalId, countrySelectId, regionSelectId, provinceSelectId, citySelectId, preselectedValues = {}) {
    const country = document.getElementById(countrySelectId);
    const regionSelect = document.getElementById(regionSelectId);
    const provinceSelect = document.getElementById(provinceSelectId);
    const citySelect = document.getElementById(citySelectId);

    const countryNameInput = modalId ? document.querySelector(`#${modalId} [name="countryName"]`) : document.getElementById('countryName');
    const regionNameInput = modalId ? document.querySelector(`#${modalId} [name="regionName"]`) : document.getElementById('regionName');
    const provinceNameInput = modalId ? document.querySelector(`#${modalId} [name="provinceName"]`) : document.getElementById('provinceName');
    const cityNameInput = modalId ? document.querySelector(`#${modalId} [name="cityName"]`) : document.getElementById('cityName');

    // Fetch Countries
    try {
        const response = await fetch(`https://secure.geonames.org/countryInfoJSON?username=${username}`);
        const data = await response.json();
        data.geonames.forEach(country => {
            const option = document.createElement('option');
            option.value = country.geonameId;
            option.setAttribute('data-name', country.countryName);
            option.textContent = country.countryName;
            if (preselectedValues.country && preselectedValues.country === country.countryName) {
                option.selected = true;
            }
            countrySelect.appendChild(option);
        });

        if (preselectedValues.country) {
            await loadRegions(countrySelect.value, preselectedValues.region);
        }
    } catch (error) {
        console.error("Error loading countries:", error);
    }

    country.addEventListener('change', async () => {
        regionSelect.innerHTML = '<option value="">Select a Region</option>';
        provinceSelect.innerHTML = '<option value="">Select a Province</option>';
        citySelect.innerHTML = '<option value="">Select a City</option>';
        regionSelect.disabled = true;
        provinceSelect.disabled = true;
        citySelect.disabled = true;

        countryNameInput.value = country.selectedOptions[0].getAttribute('data-name');

        if (country.value) {
            await loadRegions(country.value);
        }
    });

    async function loadRegions(countryId, preselectedRegion) {
        try {
            regionSelect.disabled = false;
            const response = await fetch(`https://secure.geonames.org/childrenJSON?geonameId=${countryId}&username=${username}`);
            const data = await response.json();
            data.geonames.forEach(region => {
                const option = document.createElement('option');
                option.value = region.geonameId;
                option.setAttribute('data-name', region.name);
                option.textContent = region.name;
                if (preselectedRegion && preselectedRegion === region.name) {
                    option.selected = true;
                }
                regionSelect.appendChild(option);
            });

            if (preselectedRegion) {
                await loadProvinces(regionSelect.value, preselectedValues.province);
            }
        } catch (error) {
            console.error("Error loading regions:", error);
        }
    }

    regionSelect.addEventListener('change', async () => {
        provinceSelect.innerHTML = '<option value="">Select a Province</option>';
        citySelect.innerHTML = '<option value="">Select a City</option>';
        provinceSelect.disabled = true;
        citySelect.disabled = true;

        regionNameInput.value = regionSelect.selectedOptions[0].getAttribute('data-name');

        if (regionSelect.value) {
            await loadProvinces(regionSelect.value);
        }
    });

    async function loadProvinces(regionId, preselectedProvince) {
        try {
            provinceSelect.disabled = false;
            const response = await fetch(`https://secure.geonames.org/childrenJSON?geonameId=${regionId}&username=${username}`);
            const data = await response.json();
            data.geonames.forEach(province => {
                const option = document.createElement('option');
                option.value = province.geonameId;
                option.setAttribute('data-name', province.name);
                option.textContent = province.name;
                if (preselectedProvince && preselectedProvince === province.name) {
                    option.selected = true;
                }
                provinceSelect.appendChild(option);
            });

            if (preselectedProvince) {
                await loadCities(provinceSelect.value, preselectedValues.city);
            }
        } catch (error) {
            console.error("Error loading provinces:", error);
        }
    }

    provinceSelect.addEventListener('change', async () => {
        citySelect.innerHTML = '<option value="">Select a City</option>';
        citySelect.disabled = true;

        provinceNameInput.value = provinceSelect.selectedOptions[0].getAttribute('data-name');

        if (provinceSelect.value) {
            await loadCities(provinceSelect.value);
        }
    });

    async function loadCities(provinceId, preselectedCity) {
        try {
            citySelect.disabled = false;
            const response = await fetch(`https://secure.geonames.org/childrenJSON?geonameId=${provinceId}&username=${username}`);
            const data = await response.json();
            data.geonames.forEach(city => {
                const option = document.createElement('option');
                option.value = city.geonameId;
                option.setAttribute('data-name', city.name);
                option.textContent = city.name;
                if (preselectedCity && preselectedCity === city.name) {
                    option.selected = true;
                }
                citySelect.appendChild(option);
            });
        } catch (error) {
            console.error("Error loading cities:", error);
        }
    }

    citySelect.addEventListener('change', () => {
        cityNameInput.value = citySelect.selectedOptions[0].getAttribute('data-name');
    });
}

// Initialize for Create Modal
initializeGeonameScript(null, 'countrySelect', 'regionSelect', 'provinceSelect', 'citySelect');

document.querySelectorAll('[id^="editModal"]').forEach(modal => {
    const branchId = modal.id.replace('editModal', '');

    modal.addEventListener('shown.bs.modal', () => {
        const countrySelect = modal.querySelector(`#countrySelect${branchId}`);
        const regionSelect = modal.querySelector(`#regionSelect${branchId}`);
        const provinceSelect = modal.querySelector(`#provinceSelect${branchId}`);
        const citySelect = modal.querySelector(`#citySelect${branchId}`);

        if (countrySelect && regionSelect && provinceSelect && citySelect) {
            const country = countrySelect.value;
            const region = regionSelect.value;
            const province = provinceSelect.value;
            const city = citySelect.value;
            
            initializeGeonameScript(
                `editModal${branchId}`, 
                `countrySelect${branchId}`, 
                `regionSelect${branchId}`, 
                `provinceSelect${branchId}`, 
                `citySelect${branchId}`, 
                { country, region, province, city }
            );
        } else {
            console.warn(`One or more elements not found for modal with branchId ${branchId}`);
        }
    });
});
