@extends('layout.head')

@section('title', 'Plasma Donors List - Mission Plasma')

@section('content')
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow-y: auto;
        max-height: 80vh;
    }

    .container img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
        opacity: 0.5;
    }

    h1 {
        margin-bottom: 20px;
        text-align: center;
    }

    select {
        width: 100%;
        padding: 6px 10px;
        margin-bottom: 15px;
        font-size: 16px;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: .25rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #fff;
        border-radius: 10px;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }

    th {
        background-color: #f2f2f2;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }
</style>
<img src="/storage/images/blood-donation.jpg" alt="Background Image" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1; opacity: 0.5;">
<div class="container mt-5">
    <h1>Plasma Donors List</h1>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="country_filter">Filter by Country:</label>
            <select id="country_filter" class="form-control">
                <option value="">Select Country</option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="state_filter">Filter by State:</label>
            <select id="state_filter" class="form-control">
                <option value="">Select State</option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="blood_group_filter">Filter by Blood Group:</label>
            <select id="blood_group_filter" class="form-control">
                <option value="">Select Blood Group</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Blood Group</th>
                    <th>Country</th>
                    <th>State</th>
                    <th>City</th>
                    <th>Phone Number</th>
                </tr>
            </thead>
            <tbody id="donors_table">
            </tbody>
        </table>
        <!-- Pagination links -->
        <nav aria-label="Page navigation">
            <ul class="pagination" id="pagination">
            </ul>
        </nav>
    </div>
</div>
<script>
    $(document).ready(function() {
        var currentPage = 1;
        var selectedCountry = "";
        var selectedState = "";
        var selectedBloodGroup = "";

        // Function to fetch plasma donors based on filters and pagination
        function fetchPlasmaDonors() {
            $.ajax({
                url: '/api/donors',
                type: 'get',
                data: {
                    page: currentPage,
                    country: selectedCountry,
                    state: selectedState,
                    blood_group: selectedBloodGroup
                },
                success: function(response) {
                    // Update the donors table with the fetched data
                    updateDonorsTable(response.data);
                    // Update pagination links
                    updatePagination(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching plasma donors:', error);
                }
            });
        }

        // Function to update the donors table based on the fetched data
        function updateDonorsTable(donors) {
            $('#donors_table').empty(); // Clear existing table rows
            $.each(donors, function(index, donor) {
                $('#donors_table').append(`
                    <tr>
                        <td>${donor.name}</td>
                        <td>${donor.gender}</td>
                        <td>${donor.age}</td>
                        <td>${donor.blood_group}</td>
                        <td>${donor.country}</td>
                        <td>${donor.state}</td>
                        <td>${donor.city}</td>
                        <td>${donor.phone_number}</td>
                    </tr>
                `);
            });
        }

        // Function to update the pagination links based on the provided data
        function updatePagination(data) {
            $('#pagination').empty(); // Clear existing pagination links
            if (data.lastPage > 1) {
                for (let i = 1; i <= data.lastPage; i++) {
                    $('#pagination').append(`<li class="page-item ${currentPage === i ? 'active' : ''}"><a class="page-link" href="#" onclick="fetchPage(${i})">${i}</a></li>`);
                }
            }
        }

        // Function to fetch a specific page of plasma donors
        window.fetchPage = function(page) {
            currentPage = page;
            fetchPlasmaDonors(); // Fetch plasma donors based on filters and page
        };

        // Populate the country filter dropdown
        function populateCountryFilter() {
            $.ajax({
                url: '/api/countries',
                type: 'get',
                success: function(response) {
                    $('#country_filter').empty().append('<option value="">Select Country</option>');
                    response.forEach(function(country) {
                        $('#country_filter').append(`<option value="${country.name}">${country.name}</option>`);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching countries:', error);
                }
            });
        }

        // Populate the state filter dropdown based on the selected country
        function populateStateFilter(countryName) {
            $.ajax({
                url: '/api/countries',
                type: 'get',
                success: function(response) {
                    var country = response.find(function(c) {
                        return c.name === countryName;
                    });
                    if (country) {
                        $.ajax({
                            url: '/api/states/' + country.id,
                            type: 'get',
                            success: function(statesResponse) {
                                $('#state_filter').empty().append('<option value="">Select State</option>');
                                statesResponse.forEach(function(state) {
                                    $('#state_filter').append(`<option value="${state.name}">${state.name}</option>`);
                                });
                                selectedState = ""; // Reset selectedState when country changes
                            },
                            error: function(xhr, status, error) {
                                console.error('Error fetching states:', error);
                            }
                        });
                    } else {
                        console.error('Country not found:', countryName);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching countries:', error);
                }
            });
        }

        // Event listener for country filter change
        $('#country_filter').change(function() {
            selectedCountry = $(this).val();
            populateStateFilter(selectedCountry); // Populate state filter based on the selected country
            fetchPlasmaDonors(); // Fetch plasma donors based on filters
            updatePagination(data);
        });

        // Event listener for state filter change
        $('#state_filter').change(function() {
            selectedState = $(this).val();
            fetchPlasmaDonors(); // Fetch plasma donors based on filters
            updatePagination(data);
        });

        // Event listener for blood group filter change
        $('#blood_group_filter').change(function() {
            selectedBloodGroup = $(this).val();
            fetchPlasmaDonors(); // Fetch plasma donors based on filters
            updatePagination(data);
        });

        // Initial fetch of all plasma donors
        populateCountryFilter(); // Populate country filter dropdown
        fetchPlasmaDonors(); // Fetch plasma donors
    });
</script>
@endsection