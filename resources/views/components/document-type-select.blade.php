<div>
    <select id="documentType" name="document_type" onchange="updateSelections('documentType', this.value)">
        <option value="">Select Document Type</option>
        @foreach ($documentTypes as $type)
            <option value="{{ $type->id }}">{{ ucwords(str_replace('_', ' ', $type->name)) }}</option>
        @endforeach
    </select>

    <select id="state" name="state_name" disabled onchange="updateSelections('state', this.value)">
        <option value="">Select State</option>
        <!-- Options will be populated dynamically -->
    </select>

    <select id="district" name="district_name" disabled onchange="updateSelections('district', this.value)">
        <option value="">Select District</option>
        <!-- Options will be populated dynamically -->
    </select>
    <select id="village" name="village_name" disabled onchange="updateSelections('village', this.value)">
        <option value="">Select village</option>
        <!-- Options will be populated dynamically -->
    </select>

    <select id="document" name="document_id" disabled>
        <option value="">Select Document</option>
        <!-- Options will be populated dynamically -->
    </select>
</div>

<script>
    function updateSelections(type, value) {
        // Reset all child dropdowns when the top hierarchy is changed
        if (type === 'documentType') {
            resetDropdown('state');
            resetDropdown('district');
            resetDropdown('village');
            resetDropdown('document');
        } else if (type === 'state') {
            resetDropdown('district');
            resetDropdown('village');
            resetDropdown('document');
        } else if (type === 'district') {
            resetDropdown('village');
            resetDropdown('document');
        } else if (type === 'village') {
            resetDropdown('document');
        }
        let url = '';
        let target = '';
        let additionalData = {};
        console.log(additionalData);
        switch (type) {
            case 'documentType':
                url = `/api/fetch/states/${value}`;
                target = 'state';
                break;
            case 'state':
                target = 'district';
                additionalData.doc_type_id = document.getElementById('documentType').value;
                url = `/api/fetch/districts/${value}`;
                break;
            case 'district':
                target = 'village';
                additionalData = {
                    doc_type_id: document.getElementById('documentType').value,
                    state_name: document.getElementById('state').value,
                    district_name: document.getElementById('district').value,

                };
                url = `/api/fetch/villages/${value}`;
                break;
            case 'village':
                target = 'document';
                additionalData = {
                    doc_type_id: document.getElementById('documentType').value,
                    state_name: document.getElementById('state').value,
                    district_name: document.getElementById('district').value,
                    village_name: document.getElementById('village').value
                };
                url = `/api/fetch/documents/${value}`;
                break;
            default:
                console.error('Unhandled selection type:', type);
                return; // Early return if the type is not recognized
        }

        // Append additional data to the URL as query parameters if needed







        let queryString = '';
        if (Object.keys(additionalData).length > 0) {
            queryString = Object.keys(additionalData).map(key => {
                return `${encodeURIComponent(key)}=${encodeURIComponent(additionalData[key])}`;
            }).join('&');
        }

        if (queryString.length > 0) {
            url += `?${queryString}`; // Append the query string to the URL if it exists
        }

        console.log('URL:', url); // Log the correct URL

        // Fetch call to the API
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data); // Log the data received from the server
                populateDropdown(target, data); // Populate the appropriate dropdown with the data
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }

    function resetDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        dropdown.innerHTML =
            `<option value="">Select ${dropdownId.charAt(0).toUpperCase() + dropdownId.slice(1)}</option>`;
        dropdown.disabled = true; // Disable dropdown
    }


    function populateDropdown(targetId, data) {
        const dropdown = document.getElementById(targetId);
        dropdown.innerHTML = `<option value="">Select ${targetId.charAt(0).toUpperCase() + targetId.slice(1)}</option>`;

        if (data.length === 0 && targetId === 'document') {
            alert('No documents available for the selected criteria.');
            return;
        }


        data.forEach(item => {
            // If the item has a comma, split it into separate villages
            if (item.name.includes(',')) {
                let villages = item.name.split(',');
                villages.forEach(village => {
                    dropdown.innerHTML +=
                    `<option value="${village.trim()}">${village.trim()}</option>`;
                });
            } else {
                dropdown.innerHTML += `<option value="${item.name}">${item.name}</option>`;
            }
        });

        dropdown.disabled = false; // Enable dropdown
    }



    // Call this function when the document is loaded or when a specific action occurs
    function initializeSelections() {
        // If a document type is already selected (e.g., when editing), you may want to initialize the dropdowns
        const selectedDocumentType = document.getElementById('documentType').value;
        if (selectedDocumentType) {
            updateSelections('documentType', selectedDocumentType);
        }
    }

    // Initialize the dropdowns when the page is loaded
    document.addEventListener('DOMContentLoaded', initializeSelections);
</script>
